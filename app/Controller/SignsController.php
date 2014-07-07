<?php
	class SignsController extends AppController
	{
		var $name = 'Signs';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('RenderFund', 'PurchaseOrder', 'Management', 'User', 'Position', 'CostCenter', 'Authorization', 'State', 'Sign', 'Replacement', 'Headquarter', 'Circuit');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		
		//Firma de la jefatura (si aplica para la gerencia del usuario activo) verificando si la jefatura tiene un reemplazo activo.
		function validateFirstSign($loginUserId = null, $system=null, $relacionId=null)
		{
			//Si los parametros no son nulos, comienza el proceso de firma
			if($loginUserId != null && $system != null && $relacionId != null)
			{
				$validateFirstNotification = 0;
			
				//Arreglo donde se guardara la firma.
				$dataSign = array();
				
				$type = "Jefatura del solicitante";
				
				//Nivel de firma (1 para iniciar).
				$signLevel =1;
				
				//Variable para la verificacion de solicitante-firmante
				$validateSigned = 0;
				
				//Datos del usuario en login.
				$dataLoginUser = $this->User->find('first', array('conditions' => array('User.id' => $loginUserId)));
				
				//Se verifica si hay jefatura para el usuario en login.
				$dataHeadquarterUser = $this->getHeadquarterUserId($dataLoginUser['Headquarter']['id']);
				
				//Si el usuario tiene jefatura asociada
				if($dataHeadquarterUser != false)
				{
					//Busca si la jefatura tiene un reemplazo asociado.
					$dataReplacementUser = $this->getReplacementUserId($dataHeadquarterUser['User']['id']);
					
					//Si la jefatura tiene reemplazo...
					if($dataReplacementUser != false)
					{
						$position = $this->Position->find('first', array('conditions' => array('Position.id' => $dataReplacementUser['replacing_user']['position_id'])));
						
						// El reemplazante tendra la primera firma
						$dataSign['Sign']['system_id'] = $system;
						$dataSign['Sign']['user_id'] = $dataReplacementUser['Replacement']['replacing_user_id'];
						$dataSign['Sign']['signer_name'] = $dataReplacementUser['replacing_user']['name'].' '.$dataReplacementUser['replacing_user']['first_lastname'];
						$dataSign['Sign']['position'] = $position['Position']['position'];
						$dataSign['Sign']['relation_id'] = $relacionId;
						$dataSign['Sign']['level'] = $signLevel;
						$dataSign['Sign']['sign_type'] = $type;
						
						if($dataReplacementUser['Replacement']['replacing_user_id'] == $loginUserId)
						{
							$dataSign['Sign']['state_id'] = 2;
							$dataSign['Sign']['comments'] = "Ok";
						}
						else
						{
							$dataSign['Sign']['state_id'] = 1;
							$validateSigned = 1;
						}	
							
						$dataSign['Sign']['block'] = 0;
						$dataSign['Sign']['replacement_sign'] = 1;
						
						$this->Sign->begin();
						
						// Si se crea en la base de datos la firma...
						if($this->Sign->saveAll($dataSign))
						{
							// Se suma un nivel
							$signLevel++;
							
							if($validateSigned == 1)
							{
								//Se crea la notificacion
								$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$dataReplacementUser['Replacement']['replacing_user_id'].'/'.$relacionId);
								
								if($createNotification == true)
								{	
									$validateFirstNotification = 1;
									// Se envian los parametros necesarios para la funcion que crea la siguiente firma (Gerencia)
									$createManagementSign = $this->managementSign($dataLoginUser, $system, $relacionId, $signLevel, $validateFirstNotification, $validateSigned);
						
									
									// Si se crean las demas firmas, envia true a la ficha para validar 
									if($createManagementSign == true)
									{	
										$this->Sign->commit();
										return true;
									}
									else
									{
										$this->Sign->rollback();
										return false;
									}
								}
								else
								{
									$createManagementSign = $this->managementSign($dataLoginUser, $system, $relacionId, $signLevel, $validateFirstNotification, $validateSigned);
						
									// Si se crean las demas firmas, envia true a la ficha para validar 
									if($createManagementSign == true)
									{
										$this->Sign->commit();
										return true;
									}
									else
									{
										$this->Sign->rollvback();
										return false;
									}
								}
							}
							else
							{
								$createManagementSign = $this->managementSign($dataLoginUser, $system, $relacionId, $signLevel, $validateFirstNotification, $validateSigned);
						
								// Si se crean las demas firmas, envia true a la ficha para validar 
								if($createManagementSign == true)
								{
									$this->Sign->commit();
									return true;
								}
								else
								{
									$this->Sign->rollback();
									return false;
								}
							}
						}
						
						$this->Sign->rollback();
					}
					else //Si no, el jefe de la gerencia tendra la primera firma
					{
						$position = $this->Position->find('first', array('conditions' => array('Position.id' => $dataHeadquarterUser['User']['position_id'])));
						
						$dataSign['Sign']['system_id'] = $system;
						$dataSign['Sign']['user_id'] = $dataHeadquarterUser['Headquarter']['user_id'];
						$dataSign['Sign']['signer_name'] = $dataHeadquarterUser['User']['name'].' '.$dataHeadquarterUser['User']['first_lastname'];
						$dataSign['Sign']['position'] = $position['Position']['position'];
						$dataSign['Sign']['relation_id'] = $relacionId;
						$dataSign['Sign']['level'] = $signLevel;
						$dataSign['Sign']['sign_type'] = $type;
						
						if($dataHeadquarterUser['Headquarter']['user_id'] == $loginUserId)
						{
							$dataSign['Sign']['state_id'] = 2;
							$dataSign['Sign']['comments'] = "Ok";
						}
						else
						{
							$dataSign['Sign']['state_id'] = 1;
							$validateSigned = 1;
						}
							
						$dataSign['Sign']['block'] = 0;
						$dataSign['Sign']['replacement_sign'] = 0;
						
						$this->Sign->begin();
						
						// Si se crea en la base de datos la firma...
						if($this->Sign->saveAll($dataSign))
						{
							// Se suma un nivel
							$signLevel++;
							
							if($validateSigned == 1)
							{
								//Se crea la notificacion
								$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$dataHeadquarterUser['Headquarter']['user_id'].'/'.$relacionId);
								
								if($createNotification == true)
								{	
									$validateFirstNotification = 1;
								
									// Se envian los parametros necesarios para la funcion que crea la siguiente firma (Gerencia)
									$createManagementSign = $this->managementSign($dataLoginUser, $system, $relacionId, $signLevel, $validateFirstNotification, $validateSigned);
						
									// Si se crean las demas firmas, envia true a la ficha para validar 
									if($createManagementSign == true)
									{
										$this->Sign->commit();
										return true;
									}
									else
									{
										$this->Sign->rollback();
										return false;
									}
								}
								
								$this->Sign->rollback();
							}
							else
							{
								// Se envian los parametros necesarios para la funcion que crea la siguiente firma (Gerencia)
								$createManagementSign = $this->managementSign($dataLoginUser, $system, $relacionId, $signLevel, $validateFirstNotification, $validateSigned);
						
								// Si se crean las demas firmas, envia true a la ficha para validar 
								if($createManagementSign == true)
								{
									$this->Sign->commit();
									return true;
								}
								else
								{
									$this->Sign->rollback();
									return false;
								}
							}
						}
						
						$this->Sign->rollback();
					}
				}
				else
				{
					//Si no, se envian los parametros necesarios para la funcion que crea la siguiente firma (Gerencia)
					$createManagementSign = $this->managementSign($dataLoginUser, $system, $relacionId, $signLevel, $validateFirstNotification, $validateSigned);
					
					// Si se crean las demas firmas, envia true a la ficha para validar 
					if($createManagementSign == true)
						return true;
					else
						return false;
				}
			}
			else
			{
				//Si los parametros son nulos, se envia mensaje de error al sistema correspondiente
				$model = $this->verifiedSystemUrl($system);
				
				return $this->Session->setFlash('No se agrego la primera o las primeras firma/s', 'flash_alert');	
				$this->redirect(array('controller' => $model, 'action' => 'index'));
			}
		}
		
		//Firma de la gerencia verificando si el gerente tiene un reemplazo activo
		function managementSign($dataLoginUser, $system, $relacionId, $signLevel, $validateFirstNotification, $validateSigned)
		{
			$type = "Gerencia del solicitante";
			
			// Se busca si el gerente de la gerencia tiene reemplazo
			$dataManagementUserId = $this->getReplacementUserId($dataLoginUser['Management']['user_id']);
			
			//Si el gerente tiene un reemplazo, se le agisna la primer firma al reemplazante del gerente
			if($dataManagementUserId != false)
			{
				$position = $this->Position->find('first', array('conditions' => array('Position.id' => $dataManagementUserId['replacing_user']['position_id'])));
				
				$dataSign['Sign']['system_id'] = $system;
				$dataSign['Sign']['user_id'] = $dataManagementUserId['Replacement']['replacing_user_id'];
				$dataSign['Sign']['signer_name'] = $dataManagementUserId['replacing_user']['name'].' '.$dataManagementUserId['replacing_user']['first_lastname'];
				$dataSign['Sign']['position'] = $position['Position']['position'];
				$dataSign['Sign']['relation_id'] = $relacionId;
				$dataSign['Sign']['level'] = $signLevel;
				$dataSign['Sign']['sign_type'] = $type;
				
				if($dataManagementUserId['Replacement']['replacing_user_id'] == $dataLoginUser['User']['id'])
				{
					if($validateSigned == 0)
					{
						$dataSign['Sign']['state_id'] = 2;
						$dataSign['Sign']['comments'] = "Ok";
					}
					else
					{
						$dataSign[$x]['Sign']['state_id'] = 1;
					}
				}
				else
				{
					$dataSign['Sign']['state_id'] = 1;
					$validateSigned = 1;
				}
					
				$dataSign['Sign']['block'] = 0;
				$dataSign['Sign']['replacement_sign'] = 1;
				
				$this->Sign->begin();
				
				// Si la firma se guarda...
				if($this->Sign->saveAll($dataSign))
				{
					if($validateFirstNotification == 0)
					{
						if($validateSigned == 1)
						{
							$validateFirstNotification = 1;
							
							//Se crea la notificacion
							$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$dataManagementUserId['Replacement']['replacing_user_id'].'/'.$relacionId);
							
							if($createNotification == true)
							{	
								//Se obtiene el ID de dicha firma
								$last_id = $this->Sign->getLastInsertID();
								
								//para obtener los datos de la firma con el sistema en cuestion
								$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
								
								//Se verifica el puntero del sistema
								$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
								
								//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
								$createNextSign = $this->headquarterCostCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
								
								if($createNextSign == true)
								{
									$this->Sign->commit();
									return true;
								}
								else
								{
									$this->Sign->rollback();
									return false;
								}
							}
							
							$this->Sign->rollback();
						}							
						else
						{
							//Se obtiene el ID de dicha firma
							$last_id = $this->Sign->getLastInsertID();
							
							//para obtener los datos de la firma con el sistema en cuestion
							$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
							
							//Se verifica el puntero del sistema
							$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
							
							//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
							$createNextSign = $this->headquarterCostCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
							
							if($createNextSign == true)
							{
								$this->Sign->commit();
								return true;
							}
							else
							{
								$this->Sign->rollback();
								return false;
							}
						}
					}
					else
					{
					
						//Se obtiene el ID de dicha firma
						$last_id = $this->Sign->getLastInsertID();
						
						//para obtener los datos de la firma con el sistema en cuestion
						$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
						
						//Se verifica el puntero del sistema
						$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
						
						//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
						$createNextSign = $this->headquarterCostCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
						
						if($createNextSign == true)
						{
							$this->Sign->commit();
							return true;
						}
						else
						{
							$this->Sign->rollback();
							return false;
						}
					}
				}
				
				$this->Sign->rollback();
			}
			else //Si no, la primera firma va al gerente del area
			{
				//Se obtienen los datos del gerente
				$dataManagement = $this->User->find('first', array('conditions' => array('User.id' => $dataLoginUser['Management']['user_id'])));
				
				$dataSign['Sign']['system_id'] = $system;
				$dataSign['Sign']['user_id'] = $dataManagement['User']['id'];
				$dataSign['Sign']['signer_name'] = $dataManagement['User']['name'].' '.$dataManagement['User']['first_lastname'];
				$dataSign['Sign']['position'] = $dataManagement['Position']['position'];
				$dataSign['Sign']['relation_id'] = $relacionId;
				$dataSign['Sign']['level'] = $signLevel;
				$dataSign['Sign']['sign_type'] = $type;
				
				if($dataManagement['User']['id']  == $dataLoginUser['User']['id'])
				{
					if($validateSigned == 0)
					{
						$dataSign['Sign']['state_id'] = 2;
						$dataSign['Sign']['comments'] = "Ok";
					}
					else
					{
						$dataSign[$x]['Sign']['state_id'] = 1;
					}
				}
				else
				{
					$dataSign['Sign']['state_id'] = 1;
					$validateSigned = 1;
				}
					
					
				$dataSign['Sign']['block'] = 0;
				$dataSign['Sign']['replacement_sign'] = 0;
				
				$this->Sign->begin();

				//Si la firma se guarda...
				if($this->Sign->saveAll($dataSign))
				{
					if($validateFirstNotification == 0)
					{
						if($validateSigned == 1)
						{
							$validateFirstNotification = 1;
							
							//Se crea la notificacion
							$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$dataManagement['User']['id'].'/'.$relacionId);
							
							if($createNotification == true)
							{	
								//Se obtiene el ID de dicha firma
								$last_id = $this->Sign->getLastInsertID();
								
								//para obtener los datos de la firma con el sistema en cuestion
								$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
								
								//Se verifica el puntero del sistema
								$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
								
								//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
								$createNextSign = $this->headquarterCostCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
								
								if($createNextSign == true)
								{
									$this->Sign->commit();
									return true;
								}
								else
								{
									$this->Sign->rollback();
									return false;
								}
							}
							
							$this->Sign->rollback();
						}
						else
						{
							//Se obtiene el ID de dicha firma
							$last_id = $this->Sign->getLastInsertID();
							
							//para obtener los datos de la firma con el sistema en cuestion
							$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
							
							//Se verifica el puntero del sistema
							$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
							
							//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
							$createNextSign = $this->headquarterCostCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
							
							if($createNextSign == true)
							{
								$this->Sign->commit();
								return true;
							}
							else
							{
								$this->Sign->rollback();
								return false;
							}
						}
					}
					else
					{
						//Se obtiene el ID de dicha firma
						$last_id = $this->Sign->getLastInsertID();
						
						//para obtener los datos de la firma con el sistema en cuestion
						$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
						
						//Se verifica el puntero del sistema
						$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
						
						//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
						$createNextSign = $this->headquarterCostCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
						
						if($createNextSign == true)
						{
							$this->Sign->commit();
							return true;
						}
						else
						{
							$this->Sign->rollback();
							return false;
						}
					}
				}
				
				$this->Sign->rollback();
			}
		}
		
		//Crea la siguiente firma si aplica jefe del centro de costo
		function headquarterCostCenterSign($cost_center=null, $level=null, $system=null, $relacionId=null, $validateSigned, $sessionUserId, $validateFirstNotification)
		{
			if($cost_center != null && $level != null && $system != null && $relacionId != null)
			{
				static $signLevel = 0;
				$signLevel = $level + 1;
				$dataSign = array();
				$type = "Jefatura del centro de costo";
				
				//Busco la data del centro de costo
				$dataCostCenter = $this->CostCenter->find('first', array('conditions' => array('CostCenter.id' => $cost_center)));
				
				$format_cost_center = $dataCostCenter['CostCenter']['cost_center_code'];
				
				
				//Busco si existe una jefatura con dicho centro de costo
				$dataHeadquarterCostCenter = $this->getHeadquarterCostCenter($format_cost_center);
				
				//Si existe una jefatura con dicho centro de costo
				if($dataHeadquarterCostCenter != false)
				{
					//Busca si la jefatura tiene un reemplazo asociado.
					$hasReplacement = $this->getReplacementUserId($dataHeadquarterCostCenter['User']['id']);
					
					//Si tiene genero firma del reemplazo
					if($hasReplacement != false)
					{
						$position = $this->Position->find('first', array('conditions' => array('Position.id' => $hasReplacement['replacing_user']['position_id'])));
					
						//Se le da la firma al reemplazo
						$dataSign['Sign']['system_id'] = $system;
						$dataSign['Sign']['user_id'] = $hasReplacement['Replacement']['replacing_user_id'];
						$dataSign['Sign']['signer_name'] = $hasReplacement['replacing_user']['name'].' '.$hasReplacement['replacing_user']['first_lastname'];
						$dataSign['Sign']['position'] = $position['Position']['position'];
						$dataSign['Sign']['relation_id'] = $relacionId;
						$dataSign['Sign']['level'] = $signLevel;
						$dataSign['Sign']['sign_type'] = $type;
						
						if($hasReplacement['Replacement']['replacing_user_id'] == $sessionUserId)
						{
							if($validateSigned == 0)
							{
								$dataSign['Sign']['state_id'] = 2;
								$dataSign['Sign']['comments'] = "Ok";
							}
							else
							{
								$dataSign[$x]['Sign']['state_id'] = 1;
							}
						}
						else
						{
							$dataSign['Sign']['state_id'] = 1;
							$validateSigned = 1;
						}
						
						$dataSign['Sign']['state_id'] = 1;
						$dataSign['Sign']['block'] = 0;
						$dataSign['Sign']['replacement_sign'] = 1;
						
						$this->Sign->begin();
						
						if($this->Sign->saveAll($dataSign))
						{
							if($validateFirstNotification == 0)
							{
								if($validateSigned == 1)
								{
									$validateFirstNotification = 1;
									
									//Se crea la notificacion
									$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$hasReplacement['Replacement']['replacing_user_id'].'/'.$relacionId);
								
									if($createNotification == true)
									{	
										//Se obtiene el ID de dicha firma
										$last_id = $this->Sign->getLastInsertID();
										
										//para obtener los datos de la firma con el sistema en cuestion
										$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
										
										//Se verifica el puntero del sistema
										$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
										
										//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
										$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
										
										if($createNextSign == true)
										{
											$this->Sign->commit();
											return true;
										}
										else
										{
											$this->Sign->rollback();
											return false;
										}
									}
									
									$this->Sign->rollback();
								}
								else
								{
									//Se obtiene el ID de dicha firma
									$last_id = $this->Sign->getLastInsertID();
									
									//para obtener los datos de la firma con el sistema en cuestion
									$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
									
									//Se verifica el puntero del sistema
									$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
									
									//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
									$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
									
									if($createNextSign == true)
									{
										$this->Sign->commit();
										return true;
									}
									else
									{
										$this->Sign->rollback();
										return false;
									}
								}
							}
							else
							{
								//Se obtiene el ID de dicha firma
								$last_id = $this->Sign->getLastInsertID();
								
								//para obtener los datos de la firma con el sistema en cuestion
								$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
								
								//Se verifica el puntero del sistema
								$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
								
								//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
								$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
								
								if($createNextSign == true)
								{
									$this->Sign->commit();
									return true;
								}
								else
								{
									$this->Sign->rollback();
									return false;
								}
							}	
						}
						
						$this->Sign->rollback();
					}
					else //Si no, genero firma del jefe de la jefatura
					{
						//Se obtienen los datos del jefe
						$dataHeadquarter = $this->User->find('first', array('conditions' => array('User.id' => $dataHeadquarterCostCenter['User']['id'])));
						
						$dataSign['Sign']['system_id'] = $system;
						$dataSign['Sign']['user_id'] = $dataHeadquarter['User']['id'];
						$dataSign['Sign']['signer_name'] = $dataHeadquarter['User']['name'].' '.$dataHeadquarter['User']['first_lastname'];
						$dataSign['Sign']['position'] = $dataHeadquarter['Position']['position'];
						$dataSign['Sign']['relation_id'] = $relacionId;
						$dataSign['Sign']['level'] = $signLevel;
						$dataSign['Sign']['sign_type'] = $type;
						
						if($dataHeadquarter['User']['id']  == $sessionUserId)
						{
							if($validateSigned == 0)
							{
								$dataSign['Sign']['state_id'] = 2;
								$dataSign['Sign']['comments'] = "Ok";
							}
							else
							{
								$dataSign[$x]['Sign']['state_id'] = 1;
							}
						}
						else
						{
							$dataSign['Sign']['state_id'] = 1;
							$validateSigned = 1;
						}
							
							
						$dataSign['Sign']['block'] = 0;
						$dataSign['Sign']['replacement_sign'] = 0;
						
						$this->Sign->begin();

						//Si la firma se guarda...
						if($this->Sign->saveAll($dataSign))
						{
							if($validateFirstNotification == 0)
							{
								if($validateSigned == 1)
								{
									$validateFirstNotification = 1;
									
									//Se crea la notificacion
									$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$dataHeadquarter['User']['id'].'/'.$relacionId);
									
									if($createNotification == true)
									{	
										//Se obtiene el ID de dicha firma
										$last_id = $this->Sign->getLastInsertID();
										
										//para obtener los datos de la firma con el sistema en cuestion
										$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
										
										//Se verifica el puntero del sistema
										$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
										
										//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
										$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
										
										if($createNextSign == true)
										{
											$this->Sign->commit();
											return true;
										}
										else
										{
											$this->Sign->rollback();
											return false;
										}
									}
									
									$this->Sign->rollback();
								}
								else
								{
									//Se obtiene el ID de dicha firma
									$last_id = $this->Sign->getLastInsertID();
									
									//para obtener los datos de la firma con el sistema en cuestion
									$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
									
									//Se verifica el puntero del sistema
									$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
									
									//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
									$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
									
									if($createNextSign == true)
									{
										$this->Sign->commit();
										return true;
									}
									else
									{
										$this->Sign->rollback();
										return false;
									}
								}
							}
							else
							{
								//Se obtiene el ID de dicha firma
								$last_id = $this->Sign->getLastInsertID();
								
								//para obtener los datos de la firma con el sistema en cuestion
								$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
								
								//Se verifica el puntero del sistema
								$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
								
								//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
								$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
								
								if($createNextSign == true)
								{
									$this->Sign->commit();
									return true;
								}
								else
								{
									$this->Sign->rollback();
									return false;
								}
							}
						}
						
						$this->Sign->rollback();
					}
				}
				else
				{
					$format_cost_center = substr($dataCostCenter['CostCenter']['cost_center_code'], 0, 6).'00';
					
					
					$dataHeadquarterCostCenter = $this->getHeadquarterCostCenter($format_cost_center);
					
					if($dataHeadquarterCostCenter != false)
					{
						//Busca si la jefatura tiene un reemplazo asociado.
						$hasReplacement = $this->getReplacementUserId($dataHeadquarterCostCenter['User']['id']);
						
						//Si tiene genero firma del reemplazo
						if($hasReplacement != false)
						{
							$position = $this->Position->find('first', array('conditions' => array('Position.id' => $hasReplacement['replacing_user']['position_id'])));
						
							//Se le da la firma al reemplazo
							$dataSign['Sign']['system_id'] = $system;
							$dataSign['Sign']['user_id'] = $hasReplacement['Replacement']['replacing_user_id'];
							$dataSign['Sign']['signer_name'] = $hasReplacement['replacing_user']['name'].' '.$hasReplacement['replacing_user']['first_lastname'];
							$dataSign['Sign']['position'] = $position['Position']['position'];
							$dataSign['Sign']['relation_id'] = $relacionId;
							$dataSign['Sign']['level'] = $signLevel;
							$dataSign['Sign']['sign_type'] = $type;
							
							if($hasReplacement['Replacement']['replacing_user_id'] == $sessionUserId)
							{
								if($validateSigned == 0)
								{
									$dataSign['Sign']['state_id'] = 2;
									$dataSign['Sign']['comments'] = "Ok";
								}
								else
								{
									$dataSign[$x]['Sign']['state_id'] = 1;
								}
							}
							else
							{
								$dataSign['Sign']['state_id'] = 1;
								$validateSigned = 1;
							}
							
							$dataSign['Sign']['state_id'] = 1;
							$dataSign['Sign']['block'] = 0;
							$dataSign['Sign']['replacement_sign'] = 1;
							
							$this->Sign->begin();
							
							if($this->Sign->saveAll($dataSign))
							{
								if($validateFirstNotification == 0)
								{
									if($validateSigned == 1)
									{
										$validateFirstNotification = 1;
										
										//Se crea la notificacion
										$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$hasReplacement['Replacement']['replacing_user_id'].'/'.$relacionId);
									
										if($createNotification == true)
										{	
											//Se obtiene el ID de dicha firma
											$last_id = $this->Sign->getLastInsertID();
											
											//para obtener los datos de la firma con el sistema en cuestion
											$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
											
											//Se verifica el puntero del sistema
											$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
											
											//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
											$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
											
											if($createNextSign == true)
											{
												$this->Sign->commit();
												return true;
											}
											else
											{
												$this->Sign->rollback();
												return false;
											}
										}
										
										$this->Sign->rollback();
									}
									else
									{
										//Se obtiene el ID de dicha firma
										$last_id = $this->Sign->getLastInsertID();
										
										//para obtener los datos de la firma con el sistema en cuestion
										$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
										
										//Se verifica el puntero del sistema
										$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
										
										//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
										$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
										
										if($createNextSign == true)
										{
											$this->Sign->commit();
											return true;
										}
										else
										{
											$this->Sign->rollback();
											return false;
										}
									}
								}
								else
								{
									//Se obtiene el ID de dicha firma
									$last_id = $this->Sign->getLastInsertID();
									
									//para obtener los datos de la firma con el sistema en cuestion
									$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
									
									//Se verifica el puntero del sistema
									$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
									
									//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
									$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
									
									if($createNextSign == true)
									{
										$this->Sign->commit();
										return true;
									}
									else
									{
										$this->Sign->rollback();
										return false;
									}
								}	
							}
							
							$this->Sign->rollback();
						}
						else //Si no, genero firma del jefe de la jefatura
						{
							//Se obtienen los datos del jefe
							$dataHeadquarter = $this->User->find('first', array('conditions' => array('User.id' => $dataHeadquarterCostCenter['User']['id'])));
							
							$dataSign['Sign']['system_id'] = $system;
							$dataSign['Sign']['user_id'] = $dataHeadquarter['User']['id'];
							$dataSign['Sign']['signer_name'] = $dataHeadquarter['User']['name'].' '.$dataHeadquarter['User']['first_lastname'];
							$dataSign['Sign']['position'] = $dataHeadquarter['Position']['position'];
							$dataSign['Sign']['relation_id'] = $relacionId;
							$dataSign['Sign']['level'] = $signLevel;
							$dataSign['Sign']['sign_type'] = $type;
							
							if($dataHeadquarter['User']['id']  == $sessionUserId)
							{
								if($validateSigned == 0)
								{
									$dataSign['Sign']['state_id'] = 2;
									$dataSign['Sign']['comments'] = "Ok";
								}
								else
								{
									$dataSign[$x]['Sign']['state_id'] = 1;
								}
							}
							else
							{
								$dataSign['Sign']['state_id'] = 1;
								$validateSigned = 1;
							}
								
								
							$dataSign['Sign']['block'] = 0;
							$dataSign['Sign']['replacement_sign'] = 0;
							
							$this->Sign->begin();

							//Si la firma se guarda...
							if($this->Sign->saveAll($dataSign))
							{
								if($validateFirstNotification == 0)
								{
									if($validateSigned == 1)
									{
										$validateFirstNotification = 1;
										
										//Se crea la notificacion
										$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$dataHeadquarter['User']['id'].'/'.$relacionId);
										
										if($createNotification == true)
										{	
											//Se obtiene el ID de dicha firma
											$last_id = $this->Sign->getLastInsertID();
											
											//para obtener los datos de la firma con el sistema en cuestion
											$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
											
											//Se verifica el puntero del sistema
											$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
											
											//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
											$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
											
											if($createNextSign == true)
											{
												$this->Sign->commit();
												return true;
											}
											else
											{
												$this->Sign->rollback();
												return false;
											}
										}
										
										$this->Sign->rollback();
									}
									else
									{
										//Se obtiene el ID de dicha firma
										$last_id = $this->Sign->getLastInsertID();
										
										//para obtener los datos de la firma con el sistema en cuestion
										$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
										
										//Se verifica el puntero del sistema
										$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
										
										//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
										$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
										
										if($createNextSign == true)
										{
											$this->Sign->commit();
											return true;
										}
										else
										{
											$this->Sign->rollback();
											return false;
										}
									}
								}
								else
								{
									//Se obtiene el ID de dicha firma
									$last_id = $this->Sign->getLastInsertID();
									
									//para obtener los datos de la firma con el sistema en cuestion
									$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
									
									//Se verifica el puntero del sistema
									$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
									
									//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
									$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
									
									if($createNextSign == true)
									{
										$this->Sign->commit();
										return true;
									}
									else
									{
										$this->Sign->rollback();
										return false;
									}
								}
							}
							
							$this->Sign->rollback();
						}
					}
					else
					{
						$format_cost_center = substr($dataCostCenter['CostCenter']['cost_center_code'], 0, 4).'-000';
						
						$dataHeadquarterCostCenter = $this->getHeadquarterCostCenter($format_cost_center);
						
						if($dataHeadquarterCostCenter != false)
						{
							//Busca si la jefatura tiene un reemplazo asociado.
							$hasReplacement = $this->getReplacementUserId($dataHeadquarterCostCenter['User']['id']);
							
							//Si tiene genero firma del reemplazo
							if($hasReplacement != false)
							{
								$position = $this->Position->find('first', array('conditions' => array('Position.id' => $hasReplacement['replacing_user']['position_id'])));
							
								//Se le da la firma al reemplazo
								$dataSign['Sign']['system_id'] = $system;
								$dataSign['Sign']['user_id'] = $hasReplacement['Replacement']['replacing_user_id'];
								$dataSign['Sign']['signer_name'] = $hasReplacement['replacing_user']['name'].' '.$hasReplacement['replacing_user']['first_lastname'];
								$dataSign['Sign']['position'] = $position['Position']['position'];
								$dataSign['Sign']['relation_id'] = $relacionId;
								$dataSign['Sign']['level'] = $signLevel;
								$dataSign['Sign']['sign_type'] = $type;
								
								if($hasReplacement['Replacement']['replacing_user_id'] == $sessionUserId)
								{
									if($validateSigned == 0)
									{
										$dataSign['Sign']['state_id'] = 2;
										$dataSign['Sign']['comments'] = "Ok";
									}
									else
									{
										$dataSign[$x]['Sign']['state_id'] = 1;
									}
								}
								else
								{
									$dataSign['Sign']['state_id'] = 1;
									$validateSigned = 1;
								}
								
								$dataSign['Sign']['state_id'] = 1;
								$dataSign['Sign']['block'] = 0;
								$dataSign['Sign']['replacement_sign'] = 1;
								
								$this->Sign->begin();
								
								if($this->Sign->saveAll($dataSign))
								{
									if($validateFirstNotification == 0)
									{
										if($validateSigned == 1)
										{
											$validateFirstNotification = 1;
											
											//Se crea la notificacion
											$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$hasReplacement['Replacement']['replacing_user_id'].'/'.$relacionId);
										
											if($createNotification == true)
											{	
												//Se obtiene el ID de dicha firma
												$last_id = $this->Sign->getLastInsertID();
												
												//para obtener los datos de la firma con el sistema en cuestion
												$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
												
												//Se verifica el puntero del sistema
												$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
												
												//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
												$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
												
												if($createNextSign == true)
												{
													$this->Sign->commit();
													return true;
												}
												else
												{
													$this->Sign->rollback();
													return false;
												}
											}
											
											$this->Sign->rollback();
										}
										else
										{
											//Se obtiene el ID de dicha firma
											$last_id = $this->Sign->getLastInsertID();
											
											//para obtener los datos de la firma con el sistema en cuestion
											$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
											
											//Se verifica el puntero del sistema
											$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
											
											//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
											$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
											
											if($createNextSign == true)
											{
												$this->Sign->commit();
												return true;
											}
											else
											{
												$this->Sign->rollback();
												return false;
											}
										}
									}
									else
									{
										//Se obtiene el ID de dicha firma
										$last_id = $this->Sign->getLastInsertID();
										
										//para obtener los datos de la firma con el sistema en cuestion
										$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
										
										//Se verifica el puntero del sistema
										$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
										
										//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
										$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
										
										if($createNextSign == true)
										{
											$this->Sign->commit();
											return true;
										}
										else
										{
											$this->Sign->rollback();
											return false;
										}
									}	
								}
								
								$this->Sign->rollback();
							}
							else //Si no, genero firma del jefe de la jefatura
							{
								//Se obtienen los datos del jefe
								$dataHeadquarter = $this->User->find('first', array('conditions' => array('User.id' => $dataHeadquarterCostCenter['User']['id'])));
								
								$dataSign['Sign']['system_id'] = $system;
								$dataSign['Sign']['user_id'] = $dataHeadquarter['User']['id'];
								$dataSign['Sign']['signer_name'] = $dataHeadquarter['User']['name'].' '.$dataHeadquarter['User']['first_lastname'];
								$dataSign['Sign']['position'] = $dataHeadquarter['Position']['position'];
								$dataSign['Sign']['relation_id'] = $relacionId;
								$dataSign['Sign']['level'] = $signLevel;
								$dataSign['Sign']['sign_type'] = $type;
								
								if($dataHeadquarter['User']['id']  == $dataLoginUser['User']['id'])
								{
									if($validateSigned == 0)
									{
										$dataSign['Sign']['state_id'] = 2;
										$dataSign['Sign']['comments'] = "Ok";
									}
									else
									{
										$dataSign[$x]['Sign']['state_id'] = 1;
									}
								}
								else
								{
									$dataSign['Sign']['state_id'] = 1;
									$validateSigned = 1;
								}
									
									
								$dataSign['Sign']['block'] = 0;
								$dataSign['Sign']['replacement_sign'] = 0;
								
								$this->Sign->begin();

								//Si la firma se guarda...
								if($this->Sign->saveAll($dataSign))
								{
									if($validateFirstNotification == 0)
									{
										if($validateSigned == 1)
										{
											$validateFirstNotification = 1;
											
											//Se crea la notificacion
											$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$dataHeadquarter['User']['id'].'/'.$relacionId);
											
											if($createNotification == true)
											{	
												//Se obtiene el ID de dicha firma
												$last_id = $this->Sign->getLastInsertID();
												
												//para obtener los datos de la firma con el sistema en cuestion
												$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
												
												//Se verifica el puntero del sistema
												$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
												
												//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
												$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
												
												if($createNextSign == true)
												{
													$this->Sign->commit();
													return true;
												}
												else
												{
													$this->Sign->rollback();
													return false;
												}
											}
											
											$this->Sign->rollback();
										}
										else
										{
											//Se obtiene el ID de dicha firma
											$last_id = $this->Sign->getLastInsertID();
											
											//para obtener los datos de la firma con el sistema en cuestion
											$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
											
											//Se verifica el puntero del sistema
											$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
											
											//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
											$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
											
											if($createNextSign == true)
											{
												$this->Sign->commit();
												return true;
											}
											else
											{
												$this->Sign->rollback();
												return false;
											}
										}
									}
									else
									{
										//Se obtiene el ID de dicha firma
										$last_id = $this->Sign->getLastInsertID();
										
										//para obtener los datos de la firma con el sistema en cuestion
										$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
										
										//Se verifica el puntero del sistema
										$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
										
										//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
										$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
										
										if($createNextSign == true)
										{
											$this->Sign->commit();
											return true;
										}
										else
										{
											$this->Sign->rollback();
											return false;
										}
									}
								}
								
								$this->Sign->rollback();
							}
						}
						else
						{
							$format_cost_center = substr($dataCostCenter['CostCenter']['cost_center_code'], 0, 2).'00-000';
							
						
							$dataHeadquarterCostCenter = $this->getHeadquarterCostCenter($format_cost_center);
							
							if($dataHeadquarterCostCenter != false)
							{
								//Busca si la jefatura tiene un reemplazo asociado.
								$hasReplacement = $this->getReplacementUserId($dataHeadquarterCostCenter['User']['id']);
								
								//Si tiene genero firma del reemplazo
								if($hasReplacement != false)
								{
									$position = $this->Position->find('first', array('conditions' => array('Position.id' => $hasReplacement['replacing_user']['position_id'])));
								
									//Se le da la firma al reemplazo
									$dataSign['Sign']['system_id'] = $system;
									$dataSign['Sign']['user_id'] = $hasReplacement['Replacement']['replacing_user_id'];
									$dataSign['Sign']['signer_name'] = $hasReplacement['replacing_user']['name'].' '.$hasReplacement['replacing_user']['first_lastname'];
									$dataSign['Sign']['position'] = $position['Position']['position'];
									$dataSign['Sign']['relation_id'] = $relacionId;
									$dataSign['Sign']['level'] = $signLevel;
									$dataSign['Sign']['sign_type'] = $type;
									
									if($hasReplacement['Replacement']['replacing_user_id'] == $sessionUserId)
									{
										if($validateSigned == 0)
										{
											$dataSign['Sign']['state_id'] = 2;
											$dataSign['Sign']['comments'] = "Ok";
										}
										else
										{
											$dataSign[$x]['Sign']['state_id'] = 1;
										}
									}
									else
									{
										$dataSign['Sign']['state_id'] = 1;
										$validateSigned = 1;
									}
									
									$dataSign['Sign']['state_id'] = 1;
									$dataSign['Sign']['block'] = 0;
									$dataSign['Sign']['replacement_sign'] = 1;
									
									$this->Sign->begin();
									
									if($this->Sign->saveAll($dataSign))
									{
										if($validateFirstNotification == 0)
										{
											if($validateSigned == 1)
											{
												$validateFirstNotification = 1;
												
												//Se crea la notificacion
												$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$hasReplacement['Replacement']['replacing_user_id'].'/'.$relacionId);
											
												if($createNotification == true)
												{	
													//Se obtiene el ID de dicha firma
													$last_id = $this->Sign->getLastInsertID();
													
													//para obtener los datos de la firma con el sistema en cuestion
													$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
													
													//Se verifica el puntero del sistema
													$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
													
													//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
													$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
													
													if($createNextSign == true)
													{
														$this->Sign->commit();
														return true;
													}
													else
													{
														$this->Sign->rollback();
														return false;
													}
												}
												
												$this->Sign->rollback();
											}
											else
											{
												//Se obtiene el ID de dicha firma
												$last_id = $this->Sign->getLastInsertID();
												
												//para obtener los datos de la firma con el sistema en cuestion
												$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
												
												//Se verifica el puntero del sistema
												$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
												
												//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
												$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
												
												if($createNextSign == true)
												{
													$this->Sign->commit();
													return true;
												}
												else
												{
													$this->Sign->rollback();
													return false;
												}
											}
										}
										else
										{
											//Se obtiene el ID de dicha firma
											$last_id = $this->Sign->getLastInsertID();
											
											//para obtener los datos de la firma con el sistema en cuestion
											$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
											
											//Se verifica el puntero del sistema
											$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
											
											//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
											$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
											
											if($createNextSign == true)
											{
												$this->Sign->commit();
												return true;
											}
											else
											{
												$this->Sign->rollback();
												return false;
											}
										}	
									}
									
									$this->Sign->rollback();
								}
								else //Si no, genero firma del jefe de la jefatura
								{
									//Se obtienen los datos del jefe
									$dataHeadquarter = $this->User->find('first', array('conditions' => array('User.id' => $dataHeadquarterCostCenter['User']['id'])));
									
									$dataSign['Sign']['system_id'] = $system;
									$dataSign['Sign']['user_id'] = $dataHeadquarter['User']['id'];
									$dataSign['Sign']['signer_name'] = $dataHeadquarter['User']['name'].' '.$dataHeadquarter['User']['first_lastname'];
									$dataSign['Sign']['position'] = $dataHeadquarter['Position']['position'];
									$dataSign['Sign']['relation_id'] = $relacionId;
									$dataSign['Sign']['level'] = $signLevel;
									$dataSign['Sign']['sign_type'] = $type;
									
									if($dataHeadquarter['User']['id']  == $dataLoginUser['User']['id'])
									{
										if($validateSigned == 0)
										{
											$dataSign['Sign']['state_id'] = 2;
											$dataSign['Sign']['comments'] = "Ok";
										}
										else
										{
											$dataSign[$x]['Sign']['state_id'] = 1;
										}
									}
									else
									{
										$dataSign['Sign']['state_id'] = 1;
										$validateSigned = 1;
									}
										
										
									$dataSign['Sign']['block'] = 0;
									$dataSign['Sign']['replacement_sign'] = 0;
									
									$this->Sign->begin();

									//Si la firma se guarda...
									if($this->Sign->saveAll($dataSign))
									{
										if($validateFirstNotification == 0)
										{
											if($validateSigned == 1)
											{
												$validateFirstNotification = 1;
												
												//Se crea la notificacion
												$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$dataHeadquarter['User']['id'].'/'.$relacionId);
												
												if($createNotification == true)
												{	
													//Se obtiene el ID de dicha firma
													$last_id = $this->Sign->getLastInsertID();
													
													//para obtener los datos de la firma con el sistema en cuestion
													$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
													
													//Se verifica el puntero del sistema
													$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
													
													//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
													$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
													
													if($createNextSign == true)
													{
														$this->Sign->commit();
														return true;
													}
													else
													{
														$this->Sign->rollback();
														return false;
													}
												}
												
												$this->Sign->rollback();
											}
											else
											{
												//Se obtiene el ID de dicha firma
												$last_id = $this->Sign->getLastInsertID();
												
												//para obtener los datos de la firma con el sistema en cuestion
												$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
												
												//Se verifica el puntero del sistema
												$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
												
												//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
												$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
												
												if($createNextSign == true)
												{
													$this->Sign->commit();
													return true;
												}
												else
												{
													$this->Sign->rollback();
													return false;
												}
											}
										}
										else
										{
											//Se obtiene el ID de dicha firma
											$last_id = $this->Sign->getLastInsertID();
											
											//para obtener los datos de la firma con el sistema en cuestion
											$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $last_id, 'System.id' => $system)));
											
											//Se verifica el puntero del sistema
											$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
											
											//Para obtener el centro de costo de dicha firma y enviar dicho ID mas otros parametros a la siguiente firma (Gerente del centro de costo)
											$createNextSign = $this->costCenterSign($lastSign[$systemPointer]['cost_center_id'], $signLevel, $lastSign['Sign']['system_id'], $lastSign['Sign']['relation_id'], $validateSigned, $dataLoginUser['User']['id'], $validateFirstNotification);
											
											if($createNextSign == true)
											{
												$this->Sign->commit();
												return true;
											}
											else
											{
												$this->Sign->rollback();
												return false;
											}
										}
									}
									
									$this->Sign->rollback();
								}
							}
							else
							{
								$createNextSign = $this->costCenterSign($cost_center, $signLevel, $system, $relacionId, $validateSigned, $sessionUserId, $validateFirstNotification);
											
								if($createNextSign == true)
								{
									return true;
								}
								else
								{
									return false;
								}
							}
						}
					}
				}
			}
		}
		
		
		//Crea la siguente firma del gerente del centro de costo
		function costCenterSign($cost_center=null, $level=null, $system=null, $relacionId=null, $validateSigned, $sessionUserId, $validateFirstNotification)
		{
			
			if($cost_center != null && $level != null && $system != null && $relacionId != null)
			{
				static $level_sign = 0;
				$level_sign = $level + 1;
				$dataSign = array();
				$type = "Gerencia del centro de costo";
				
				$dataCostCenter = $this->CostCenter->find('first', array('conditions' => array('CostCenter.id' => $cost_center)));
				
				$dataManagement = $this->getManagementCostCenter($dataCostCenter['CostCenter']['cost_center_code']);
				
				/*echo "<br><br><br><br><br><br><br><br><br><br><br><br>Fondo solo".$dataCostCenter['CostCenter']['cost_center_code']."<pre>";
				print_r($dataManagement);
				echo "</pre>";*/
					
				//Si encuentra un centro de costo padre
				if($dataManagement != false)
				{
					
					//Busca reeemplazo para el gerente del centro de costo padre
					$hasReplacement = $this->getReplacementUserId($dataManagement['Management']['user_id']);
				
					//Si tiene reemplazo el gerente del centro de costo
					if($hasReplacement != false)
					{
						$position = $this->Position->find('first', array('conditions' => array('Position.id' => $hasReplacement['replacing_user']['position_id'])));
						
						//Se le da la firma al reemplazo
						$dataSign['Sign']['system_id'] = $system;
						$dataSign['Sign']['user_id'] = $hasReplacement['Replacement']['replacing_user_id'];
						$dataSign['Sign']['signer_name'] = $hasReplacement['replacing_user']['name'].' '.$hasReplacement['replacing_user']['first_lastname'];
						$dataSign['Sign']['position'] = $position['Position']['position'];
						$dataSign['Sign']['relation_id'] = $relacionId;
						$dataSign['Sign']['level'] = $level_sign;
						$dataSign['Sign']['sign_type'] = $type;
						
						if($hasReplacement['Replacement']['replacing_user_id'] == $sessionUserId)
						{
							if($validateSigned == 0)
							{
								$dataSign['Sign']['state_id'] = 2;
								$dataSign['Sign']['comments'] = "Ok";
							}
							else
							{
								$dataSign[$x]['Sign']['state_id'] = 1;
							}
						}
						else
						{
							$dataSign['Sign']['state_id'] = 1;
							$validateSigned = 1;
						}
						
						$dataSign['Sign']['state_id'] = 1;
						$dataSign['Sign']['block'] = 0;
						$dataSign['Sign']['replacement_sign'] = 1;
						
						$this->Sign->begin();
						
						if($this->Sign->saveAll($dataSign))
						{
							if($validateFirstNotification == 0)
							{
								if($validateSigned == 1)
								{
									$validateFirstNotification = 1;
									
									//Se crea la notificacion
									$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$hasReplacement['Replacement']['replacing_user_id'].'/'.$relacionId);
								
									if($createNotification == true)
									{	
										$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
								
										if($createLastSigns == true)
										{
											$this->Sign->commit();
											return true;
										}
										else
										{
											$this->Sign->rollback();
											return false;
										}
									}
									
									$this->Sign->rollback();
								}
								else
								{
									$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
								
									if($createLastSigns == true)
									{
										$this->Sign->commit();
										return true;
									}
									else
									{
										$this->Sign->rollback();
										return false;
									}
								}
							}
							else
							{
								$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
								
								if($createLastSigns == true)
								{
									$this->Sign->commit();
									return true;
								}
								else
								{
									$this->Sign->rollback();
									return false;
								}
							}	
						}
						
						$this->Sign->rollback();
					}
					else //Si no, se le da la firma al gerente del centro de costo
					{	
						$position = $this->Position->find('first', array('conditions' => array('Position.id' => $dataManagement['User']['position_id'])));
						
						$dataSign['Sign']['system_id'] = $system;
						$dataSign['Sign']['user_id'] = $dataManagement['User']['id'];
						$dataSign['Sign']['signer_name'] = $dataManagement['User']['name'].' '.$dataManagement['User']['first_lastname'];
						$dataSign['Sign']['position'] = $position['Position']['position'];
						$dataSign['Sign']['relation_id'] = $relacionId;
						$dataSign['Sign']['level'] = $level_sign;
						$dataSign['Sign']['sign_type'] = $type;
						
						if($dataManagement['User']['id'] == $sessionUserId)
						{
							if($validateSigned == 0)
							{
								$dataSign['Sign']['state_id'] = 2;
								$dataSign['Sign']['comments'] = "Ok";
							}
							else
							{
								$dataSign[$x]['Sign']['state_id'] = 1;
							}
						}
						else
						{
							$dataSign['Sign']['state_id'] = 1;
							$validateSigned = 1;
						}
						
						$dataSign['Sign']['block'] = 0;
						$dataSign['Sign']['replacement_sign'] = 0;
						
						$this->Sign->begin();
						
						if($this->Sign->saveAll($dataSign))
						{
							if($validateFirstNotification == 0)
							{
								if($validateSigned == 1)
								{
									$validateFirstNotification = 1;
									
									//Se crea la notificacion
									$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$dataManagement['User']['id'].'/'.$relacionId);
								
									if($createNotification == true)
									{
										$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
								
										if($createLastSigns == true)
										{
											$this->Sign->commit();
											return true;
										}
										else
										{
											$this->Sign->rollback();
											return false;
										}
									}
									
									$this->Sign->rollback();
								}
								else
								{
									$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
								
									if($createLastSigns == true)
									{
										$this->Sign->commit();
										return true;
									}
									else
									{
										$this->Sign->rollback();
										return false;
									}
								}
							}
							else
							{
								$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
								
								if($createLastSigns == true)
								{
									$this->Sign->commit();
									return true;
								}
								else
								{
									$this->Sign->rollback();
									return false;
								}
							}
						}
						
						$this->Sign->rollback();
					}		
				}			
				else
				{
				
					$father_cost_center = substr($dataCostCenter['CostCenter']['cost_center_code'], 0, 4).'-000';
				
					//Se busca por centro de costo padre terminado en "-000"
					$dataManagement = $this->getManagementCostCenter($father_cost_center);
					
					/*echo "<br><br><br><br><br><br><br><br><br><br><br><br>Fondo con ".$father_cost_center."<pre>";
					print_r($dataManagement);
					echo "</pre>";*/
					
					
					//Si encuentra un centro de costo padre
					if($dataManagement != false)
					{
						$hasReplacement = $this->getReplacementUserId($dataManagement['Management']['user_id']);
						
						//Si tiene reemplazo el gerente del centro de costo
						if($hasReplacement != false)
						{
							$position = $this->Position->find('first', array('conditions' => array('Position.id' => $hasReplacement['replacing_user']['position_id'])));
							
							//Se le da la firma al reemplazo
							$dataSign['Sign']['system_id'] = $system;
							$dataSign['Sign']['user_id'] = $hasReplacement['Replacement']['replacing_user_id'];
							$dataSign['Sign']['signer_name'] = $hasReplacement['replacing_user']['name'].' '.$hasReplacement['replacing_user']['first_lastname'];
							$dataSign['Sign']['position'] = $position['Position']['position'];
							$dataSign['Sign']['relation_id'] = $relacionId;
							$dataSign['Sign']['level'] = $level_sign;
							$dataSign['Sign']['sign_type'] = $type;
							
							if($hasReplacement['Replacement']['replacing_user_id'] == $sessionUserId)
							{
								if($validateSigned == 0)
								{
									$dataSign['Sign']['state_id'] = 2;
									$dataSign['Sign']['comments'] = "Ok";
								}
								else
								{
									$dataSign[$x]['Sign']['state_id'] = 1;
								}
							}
							else
							{
								$dataSign['Sign']['state_id'] = 1;
								$validateSigned = 1;
							}

							
							$dataSign['Sign']['block'] = 0;
							$dataSign['Sign']['replacement_sign'] = 1;
							
							$this->Sign->begin();
							
							if($this->Sign->saveAll($dataSign))
							{
								if($validateFirstNotification == 0)
								{
									if($validateSigned == 1)
									{
										$validateFirstNotification = 1;
										
										//Se crea la notificacion
										$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$hasReplacement['Replacement']['replacing_user_id'].'/'.$relacionId);
								
										if($createNotification == true)
										{
											$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
									
											if($createLastSigns == true)
											{
												$this->Sign->commit();
												return true;
											}
											else
											{
												$this->Sign->rollback();
												return false;
											}
										}
										
										$this->Sign->rollback();
									}
									else
									{
										$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
									
										if($createLastSigns == true)
										{
											$this->Sign->commit();
											return true;
										}
										else
										{
											$this->Sign->rollback();
											return false;
										}
									}
								}
								else
								{
									$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
									
									if($createLastSigns == true)
									{
										$this->Sign->commit();
										return true;
									}
									else
									{
										$this->Sign->rollback();
										return false;
									}
								}
							}
							
							$this->Sign->rollback();
						}
						else
						{
							$position = $this->Position->find('first', array('conditions' => array('Position.id' => $dataManagement['User']['position_id'])));
							
							$dataSign['Sign']['system_id'] = $system;
							$dataSign['Sign']['user_id'] = $dataManagement['User']['id'];
							$dataSign['Sign']['signer_name'] = $dataManagement['User']['name'].' '.$dataManagement['User']['first_lastname'];
							$dataSign['Sign']['position'] = $position['Position']['position'];
							$dataSign['Sign']['relation_id'] = $relacionId;
							$dataSign['Sign']['level'] = $level_sign;
							$dataSign['Sign']['sign_type'] = $type;
							
							if($dataManagement['User']['id'] == $sessionUserId)
							{
								if($validateSigned == 0)
								{
									$dataSign['Sign']['state_id'] = 2;
									$dataSign['Sign']['comments'] = "Ok";
								}
								else
								{
									$dataSign[$x]['Sign']['state_id'] = 1;
								}
							}
							else
							{
								$dataSign['Sign']['state_id'] = 1;
								$validateSigned = 1;
							}
							
							$dataSign['Sign']['block'] = 0;
							$dataSign['Sign']['replacement_sign'] = 0;
							
							$this->Sign->begin();
							
							if($this->Sign->saveAll($dataSign))
							{
								if($validateFirstNotification == 0)
								{
									if($validateSigned == 1)
									{
										$validateFirstNotification = 1;
										
										//Se crea la notificacion
										$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$dataManagement['User']['id'].'/'.$relacionId);
								
										if($createNotification == true)
										{
											$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
									
											if($createLastSigns == true)
											{
												$this->Sign->commit();
												return true;
											}
											else
											{
												$this->Sign->rollback();
												return false;
											}
										}
										
										$this->Sign->rollback();
									}
									else
									{
										$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
									
										if($createLastSigns == true)
										{
											$this->Sign->commit();
											return true;
										}
										else
										{
											$this->Sign->rollback();
											return false;
										}
									}
								}
								else
								{
									$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
									
									if($createLastSigns == true)
									{
										$this->Sign->commit();
										return true;
									}
									else
									{
										$this->Sign->rollback();
										return false;
									}
								}
							}
								
							$this->Sign->rollback();
						}
					}
					else //Si no, busca por centro de costo padre terminado en "00-000"
					{
						
						$father_cost_center = substr($dataCostCenter['CostCenter']['cost_center_code'], 0, 2).'00-000';
						
						$dataManagement = $this->getManagementCostCenter($father_cost_center);
						
						//Si encuentra un centro de costo padre
						if($dataManagement != false)
						{
						
							//Busca reeemplazo para el gerente del centro de costo padre
							$hasReplacement = $this->getReplacementUserId($dataManagement['Management']['user_id']);
						
							//Si tiene reemplazo el gerente del centro de costo
							if($hasReplacement != false)
							{
								$position = $this->Position->find('first', array('conditions' => array('Position.id' => $hasReplacement['replacing_user']['position_id'])));
								
								//Se le da la firma al reemplazo
								$dataSign['Sign']['system_id'] = $system;
								$dataSign['Sign']['user_id'] = $hasReplacement['Replacement']['replacing_user_id'];
								$dataSign['Sign']['signer_name'] = $hasReplacement['replacing_user']['name'].' '.$hasReplacement['replacing_user']['first_lastname'];
								$dataSign['Sign']['position'] = $position['Position']['position'];
								$dataSign['Sign']['relation_id'] = $relacionId;
								$dataSign['Sign']['level'] = $level_sign;
								$dataSign['Sign']['sign_type'] = $type;
								
								if($hasReplacement['Replacement']['replacing_user_id'] == $sessionUserId)
								{
									if($validateSigned == 0)
									{
										$dataSign['Sign']['state_id'] = 2;
										$dataSign['Sign']['comments'] = "Ok";
									}
									else
									{
										$dataSign[$x]['Sign']['state_id'] = 1;
									}
								}
								else
								{
									$dataSign['Sign']['state_id'] = 1;
									$validateSigned = 1;
								}
								
								$dataSign['Sign']['block'] = 0;
								$dataSign['Sign']['replacement_sign'] = 1;
								
								$this->Sign->begin();
								
								if($this->Sign->saveAll($dataSign))
								{
									if($validateFirstNotification == 0)
									{
										if($validateSigned == 1)
										{
											$validateFirstNotification = 1;
											
											//Se crea la notificacion
											$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$hasReplacement['Replacement']['replacing_user_id'].'/'.$relacionId);
								
											if($createNotification == true)
											{
												$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
										
												if($createLastSigns == true)
												{
													$this->Sign->commit();
													return true;
												}
												else
												{
													$this->Sign->rollback();
													return false;
												}
											}
											
											$this->Sign->rollback();
										}
										else
										{
											$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
										
											if($createLastSigns == true)
											{
												$this->Sign->commit();
												return true;
											}
											else
											{
												$this->Sign->rollback();
												return false;
											}
										}
									}
									else
									{
										$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
										
										if($createLastSigns == true)
										{
											$this->Sign->commit();
											return true;
										}
										else
										{
											$this->Sign->rollback();
											return false;
										}
									}
								}
								
								$this->Sign->rollback();
							}
							else //Si no, se le da la firma al gerente del centro de costo
							{	
								$position = $this->Position->find('first', array('conditions' => array('Position.id' => $dataManagement['User']['position_id'])));
								
								$dataSign['Sign']['system_id'] = $system;
								$dataSign['Sign']['user_id'] = $dataManagement['User']['id'];
								$dataSign['Sign']['signer_name'] = $dataManagement['User']['name'].' '.$dataManagement['User']['first_lastname'];
								$dataSign['Sign']['position'] = $position['Position']['position'];
								$dataSign['Sign']['relation_id'] = $relacionId;
								$dataSign['Sign']['level'] = $level_sign;
								$dataSign['Sign']['sign_type'] = $type;
								
								if($dataManagement['User']['id'] == $sessionUserId)
								{
									if($validateSigned == 0)
									{
										$dataSign['Sign']['state_id'] = 2;
										$dataSign['Sign']['comments'] = "Ok";
									}
									else
									{
										$dataSign[$x]['Sign']['state_id'] = 1;
									}
								}
								else
								{
									$dataSign['Sign']['state_id'] = 1;
									$validateSigned = 1;
								}
								
								$dataSign['Sign']['block'] = 0;
								$dataSign['Sign']['replacement_sign'] = 0;
								
								$this->Sign->begin();
								
								if($this->Sign->saveAll($dataSign))
								{
									if($validateFirstNotification == 0)
									{
										if($validateSigned == 1)
										{
											$validateFirstNotification = 1;
											
											//Se crea la notificacion
											$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$system.'/'.$dataManagement['User']['id'].'/'.$relacionId);
								
											if($createNotification == true)
											{
												$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
										
												if($createLastSigns == true)
												{
													$this->Sign->commit();
													return true;
												}
												else
												{
													$this->Sign->rollback();
													return false;
												}
											}
											
											$this->Sign->rollback();
										}
										else
										{
											$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
										
											if($createLastSigns == true)
											{
												$this->Sign->commit();
												return true;
											}
											else
											{
												$this->Sign->rollback();
												return false;
											}
										}
									}
									else
									{
										$createLastSigns = $this->lastCircuitSigns($dataManagement['Authorization']['id'], $relacionId, $system, $sessionUserId, $validateSigned, $validateFirstNotification);
										
										if($createLastSigns == true)
										{
											$this->Sign->commit();
											return true;
										}
										else
										{
											$this->Sign->rollback();
											return false;
										}
									}
								}
								
								$this->Sign->rollback();
							}
						}
						else
						{
							$this->Session->setFlash('No encontro el centro de costo padre.', 'flash_alert');	
							$this->redirect(array('controller' => 'render_funds', 'action' => 'index'));
						}
					}
				}
			}
			else
			{
				$model = $this->verifiedSystemUrl($system);
				
				$this->Session->setFlash('No se agrego la siguiente firma', 'flash_alert');	
				$this->redirect(array('controller' => $model, 'action' => 'index'));
			}
		}
		
		function lastCircuitSigns($authId=null, $relacionId=null, $systemId=null, $sessionUserId, $validateSigned, $validateFirstNotification)
		{
			$dataSign = array();
			
			if($authId != null && $relacionId !=null && $systemId != null)
			{
				$circuits = $this->Circuit->find('all', array('conditions' => array('Circuit.authorization_id' => $authId, 'Circuit.system_id' => $systemId)));
				
				$authName = $this->Authorization->find('first', array('conditions' => array('Authorization.id' => $authId)));
				
				$type = "Firma de autorización ".$authName['Authorization']['name'];
				
				for($x=0; $x < count($circuits); $x++)
				{
					$hasReplacement =	$this->getReplacementUserId($circuits[$x]['User']['id']);
					
					//Firma al reemplazante del circuito
					if($hasReplacement!= false)
					{
						$position = $this->Position->find('first', array('conditions' => array('Position.id' => $circuits[$x]['User']['position_id'])));
						
						//Se le da la firma al reemplazo
						$dataSign[$x]['Sign']['system_id'] = $systemId;
						$dataSign[$x]['Sign']['user_id'] = $hasReplacement['Replacement']['replacing_user_id'];
						$dataSign[$x]['Sign']['signer_name'] = $hasReplacement['replacing_user']['name'].' '.$hasReplacement['replacing_user']['first_lastname'];
						$dataSign[$x]['Sign']['position'] = $position['Position']['position'];
						$dataSign[$x]['Sign']['relation_id'] = $relacionId;
						$dataSign[$x]['Sign']['level'] = $circuits[$x]['Circuit']['level'];
						$dataSign[$x]['Sign']['sign_type'] = $type;
						
						if($hasReplacement['Replacement']['replacing_user_id'] == $sessionUserId)
						{
							if($validateSigned == 0)
							{
								$dataSign[$x]['Sign']['state_id'] = 2;
								$dataSign[$x]['Sign']['comments'] = "Ok";
							}
						}
						else
						{
							$dataSign[$x]['Sign']['state_id'] = 1;
							$validateSigned = 1;
						}
						
						$dataSign[$x]['Sign']['block'] = 0;
						$dataSign[$x]['Sign']['replacement_sign'] = 1;
						
						if($validateFirstNotification == 0)
						{
							if($validateSigned == 1)
							{
								$validateFirstNotification = 1;
								
								$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$systemId.'/'.$circuits[$x]['Circuit']['user_id'].'/'.$relacionId);
							}
						}
					}
					else
					{
						$position = $this->Position->find('first', array('conditions' => array('Position.id' => $circuits[$x]['User']['position_id'])));
						
						$dataSign[$x]['Sign']['system_id'] = $systemId;
						$dataSign[$x]['Sign']['user_id'] = $circuits[$x]['Circuit']['user_id'];
						$dataSign[$x]['Sign']['signer_name'] = $circuits[$x]['User']['name'].' '.$circuits[$x]['User']['first_lastname'];
						$dataSign[$x]['Sign']['position'] = $position['Position']['position'];
						$dataSign[$x]['Sign']['relation_id'] = $relacionId;
						$dataSign[$x]['Sign']['level'] = $circuits[$x]['Circuit']['level'];
						$dataSign[$x]['Sign']['sign_type'] = $type;
						
						if($circuits[$x]['Circuit']['user_id'] == $sessionUserId)
						{
							if($validateSigned == 0)
							{
								$dataSign[$x]['Sign']['state_id'] = 2;
								$dataSign[$x]['Sign']['comments'] = "Ok";
							}
							else
							{
								$dataSign[$x]['Sign']['state_id'] = 1;
							}
						}
						else
						{
							$dataSign[$x]['Sign']['state_id'] = 1;
							$validateSigned = 1;
						}
						
						$dataSign[$x]['Sign']['block'] = 0;
						$dataSign[$x]['Sign']['replacement_sign'] = 0;
						
						if($validateFirstNotification == 0)
						{
							if($validateSigned == 1)
							{
								$validateFirstNotification = 1;
								
								$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$systemId.'/'.$circuits[$x]['Circuit']['user_id'].'/'.$relacionId);
							}
						}
					}
				}
				
				$this->Sign->begin();
				
				if($this->Sign->saveAll($dataSign))
				{
					$this->Sign->commit();
					return true;
				}
				else
				{	
					$this->Sign->rollback();
					return false;
				}
			}
			else
			{
				$model = $this->verifiedSystemUrl($system);
				
				$this->Session->setFlash('No se agregaron las ultimas firmas firma', 'flash_alert');	
				$this->redirect(array('controller' => $model, 'action' => 'index'));
			}
		}
		
		
		//Envia el nombre del puntero a usar cuando hay relaciones con sistemas iguales
		function verifiedSystemData($system)
		{
			if($system == 1)
				return "RenderFundRelation";
			if($system == 2)
				return "PurchaseOrderRelation";
			if($system == 3)
				return "PaymentRequestRelation";
			if($system == 4)
				return "ServiceContractRelation";
			if($system == 5)
				return "HumanResourceRelation";
			if($system == 6)
				return "OperationRelation";
			if($system == 7)
				return "TransportRequestRelation";
			if($system == 8)
				return "PromotionRelation";
			if($system == 9)
				return "FilmDemoRelation";
		}
		
		function verifiedSystemClass($system)
		{
			if($system == 1)
				return "RenderFund";
			if($system == 2)
				return "PurchaseOrder";
			if($system == 3)
				return "PaymentRequest";
			if($system == 4)
				return "ServiceContract";
			if($system == 5)
				return "HumanResource";
			if($system == 6)
				return "Operation";
			if($system == 7)
				return "TransportRequest";
			if($system == 8)
				return "Promotion";
			if($system == 9)
				return "FilmDemo";
		}
		
		function verifiedSystemUrl($system)
		{
			if($system == 1)
				return "render_funds";
			if($system == 2)
				return "purchase_orders";
			if($system == 3)
				return "payment_requests";
			if($system == 4)
				return "service_contracts";
			if($system == 5)
				return "human_resources";
			if($system == 6)
				return "operations";
			if($system == 7)
				return "transport_requests";
			if($system == 8)
				return "promotions";
			if($system == 9)
				return "film_demos";
		}

		//Busca algun reemplazo existente y devuelve el id del usuario que esta reemplazando
		function getReplacementUserId($userId)
		{
			$actualDate = date('Y-m-d');
			$verifiedReplacementUser = $this->Replacement->find('first', array('conditions' => array('Replacement.replaced_user_id' => $userId, 'Replacement.active' => 1, 'Replacement.start_date <=' => $actualDate, 'Replacement.end_date >=' => $actualDate)));
			
			//echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br>".$this->Replacement('sql_dump');
			
			if(count($verifiedReplacementUser) != 0)
				return $verifiedReplacementUser;
			else
				return false;
				
		}
		
		//Busca alguna jefatura existente a partir del id de la gerencia y retorna el id del usuario jefe
		function getHeadquarterUserId($id)
		{
			$verifiedHeadquarterUser = $this->Headquarter->find('first', array('conditions' => array('Headquarter.id' => $id, 'Headquarter.active' => 1)));
				
			if(count($verifiedHeadquarterUser) != 0)
				return $verifiedHeadquarterUser;
			else
				return false;
		}
		
		function getManagementUserId($managementId)
		{
			$verifiedManagement = $this->Management->find('first', array('conditions' => array('Management.user_id' => $managementId)));
			
			if(count($verifiedManagement) != 0)
				return $verifiedManagement;
			else
				return false;
		}
		
		function getAllSigns()
		{
			$signs =  $this->Sign->find('all');
			
			echo "<pre><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
						print_r($signs);
						echo "</pre>";
		}
		
		function getManagementCostCenter($father)
		{
			$dataManagement = $this->Management->find('first', array('conditions' => array('Management.cost_center_father_code' => $father)));
			
			if(count($dataManagement) != 0)
				return $dataManagement;
			else
				return false;
		}
		
		function getHeadquarterCostCenter($cost_center)
		{
			$dataHeadquarter = $this->Headquarter->find('first', array('conditions' => array('Headquarter.cost_center_code' => $cost_center)));
			
			if(count($dataHeadquarter) != 0)
				return $dataHeadquarter;
			else
				return false;
		}
		
		
		//Formulario flotante de firmas
		function formSign($id = null)
		{
			$table='';
				
			$js = "<script>
					
					function validateComment()
					{
						var comments = document.getElementById('comments').value;
						
						if (comments == '')
						{
							alert('El comentario en esta firma no puede ir vacío.');
						}
						else
							getDataToSign()
					}
					
					function getDataToSign()
					{
						for(i=0; i < document.SignEditForm.state.length; i++)
						{
							if(document.SignEditForm.state[i].checked)
							{
								
								var stateSelected = document.SignEditForm.state[i].value;
								var comments = document.getElementById('comments').value;
								var sign = document.getElementById('id_sign').value;
								var signerUserId = document.getElementById('signerUserId').value;
								var raiz = '/michilevision';
								
								document.location.href = raiz + '/signs/signingFile/'+sign+'/'+stateSelected+'/'+comments+'/'+signerUserId;
							}
						}
						
					}
					</script>";
			
			$table .= $js;
			$table .= '<form id="SignEditForm" name="SignEditForm" accept-charset="utf-8">
			<table>
				<tr>
					<td style="border:none;"><textarea name="comments" style="width:180px;height:80px;" id="comments"></textarea></td>
					<td style="border:none;"><input name="state" type="radio" value="2" id="state_id" checked="checked" />
					<label for="accion_aprueba">Aprobar</label>
					<input name="state" type="radio" value="3" id="state_id" />
					<label for="accion_rechaza">Rechazar</label>
					<input name="id_sign" type="hidden" value="'.$id.'" id="id_sign" /><br />
					<input name="signerUserId" type="hidden" value="'.$this->Auth->user('id').'" id="signerUserId" /><br />
					<button type="button" style="padding:5px;float:left;" class="a_button" onclick="javascript:validateComment();">Firmar</button>
					<button type="button" style="padding:5px;float:left;" class="a_button" onclick="hide_tipTip();">Cancelar</button></td>
				</tr>
				</table>
			</form>';
			
			return $table;
		}
		
		//Firmando de acuerdo a los datos entregados por el formulario flotante
		function signingFile($sign = null, $state = null, $comments = null, $signerUserId = null)
		{
			if($sign != null && $state != null && $comments != null && $signerUserId != null)
			{
				$dataSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $sign)));
				
				if($dataSign != false)
				{
					$dataSign['Sign']['state_id'] = $state;
					$dataSign['Sign']['comments'] = $comments;
					$dataSign['Sign']['modified'] = date("Y-m-d H:i:s");  
					
					if($state == 3)
					{
						$system = $this->verifiedSystemClass($dataSign['Sign']['system_id']);
						$file = $this->$system->find('first', array('conditions' => array($system.'.id' => $dataSign['Sign']['relation_id'])));
						
						
						$file[$system]['state_id'] =3;
						
						$this->$system->begin();
						
						if($this->$system->save($file))
						{
							$this->Sign->begin();
							
							if ($this->Sign->save($dataSign))
							{
								$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $sign)));
		
								$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
								
								$url = $this->verifiedSystemUrl($lastSign['Sign']['system_id']);
								
								$NotificationToUser = $lastSign[$systemPointer]['user_id'];
							
								$sendNotification = $this->RequestAction('/notifications/declinedRequestNotification/'.$lastSign['Sign']['system_id'].'/'.$NotificationToUser.'/'.$lastSign['Sign']['relation_id']);

								if($sendNotification == true)
								{
									$this->$system->commit();
									$this->Sign->commit();
									//Envia un flash con mensaje de confrimacion y se redirecciona al index
									$this->Session->setFlash('Se ha enviado tu firma.', 'flash_success');
									$this->redirect(array('controller' => $url, 'action' => 'index'));
								}
							}
							
							$this->Sign->rollback();
						}
						
						$this->$system->rollback();
					}
					
					if($dataSign['Sign']['level'] == 10)
					{
						if($state == 2)
						{
							$system = $this->verifiedSystemClass($dataSign['Sign']['system_id']);
							$file = $this->$system->find('first', array('conditions' => array($system.'.id' => $dataSign['Sign']['relation_id'])));
							
							$file[$system]['state_id'] =2;
							$file[$system]['approved'] =1;
							
							if($system == 'PurchaseOrder')
							{
								$file[$system]['order_number'] = $this->RequestAction('/correlative_numbers/generateCorrelativeNumber/2/'.date('Y').'/correlative_type/orden');
								$file[$system]['approved'] = 1;
							}
							
							$this->$system->begin();
							
							if($this->$system->save($file))
							{
								$this->Sign->begin();
								
								if ($this->Sign->save($dataSign))
								{
									$lastSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $sign)));
									
									$url = $this->verifiedSystemUrl($lastSign['Sign']['system_id']);
		
									$systemPointer = $this->verifiedSystemData($lastSign['Sign']['system_id']);
									
									$NotificationToUser = $lastSign[$systemPointer]['user_id'];
								
									$sendNotification = $this->RequestAction('/notifications/approvedRequestNotification/'.$lastSign['Sign']['system_id'].'/'.$NotificationToUser.'/'.$lastSign['Sign']['relation_id']);

									if($sendNotification == true)
									{
										if($system == 'RenderFund')
										{
											$messageFinance = $this->RequestAction('/notifications/notificationToFinanceForMessageToTreasury/'.$lastSign['Sign']['system_id'].'/'.$this->RequestAction('/external_functions/getFinanceManagementId').'/'.$lastSign['Sign']['relation_id']);
										
											if($messageFinance ==  true)
											{
												$this->$system->commit();
												$this->Sign->commit();
											
												//Envia un flash con mensaje de confrimacion y se redirecciona al index
												$this->Session->setFlash('Se ha enviado tu firma y se ha aprobado totalmente.', 'flash_success');
												$this->redirect(array('controller' => $url, 'action' => 'index'));
											}
										}
										
										if($system == 'PurchaseOrder')
										{
											//$administrationManagerMessage = $this->RequestAction('/notifications/approvedRequestNotification/2/'.$this->RequestAction('/external_functions/getAdministrationManagementId').'/'.$lastSign['Sign']['relation_id']);
											
											//if($administrationManagerMessage == true)
											//{
												$this->$system->commit();
												$this->Sign->commit();
											
												//Envia un flash con mensaje de confrimacion y se redirecciona al index
												$this->Session->setFlash('Se ha enviado tu firma y se ha aprobado totalmente.', 'flash_success');
												$this->redirect(array('controller' => $url, 'action' => 'index'));
											//}
										}						
									}
								}
								
								$this->Sign->rollback();
							}
							
							$this->$system->rollback();
						}
					}
					else
					{
						if ($this->Sign->save($dataSign))
						{
							//Extraigo los datos de la firma actual
							$actualSign = $this->Sign->find('first', array('conditions' => array('Sign.id' => $sign)));
							
							$url = $this->verifiedSystemUrl($actualSign['Sign']['system_id']);
							
							//Para extraer el circuito completo de firmas
							$signs = $this->Sign->find('all', array('conditions' => array('Sign.system_id' => $actualSign['Sign']['system_id'], 'Sign.relation_id' => $actualSign['Sign']['relation_id']), 'order' => 'Sign.level ASC'));						
							
							//Se recorrer las firmas
							foreach($signs as $sign)
							{	
								//Si la siguiente firma esta en espera
								if($sign['Sign']['state_id'] == 1)
								{
									if($signerUserId == $sign['Sign']['user_id'])
									{
										$saveSignSameSigner = $this->Sign->find('first', array('conditions' => array('Sign.id' => $sign['Sign']['id'])));
										$saveSignSameSigner['Sign']['state_id'] = 2;
										$saveSignSameSigner['Sign']['comments'] = $comments;
										
										$this->Sign->begin();
										
										if($this->Sign->save($saveSignSameSigner))
										{
											$this->Sign->commit();
										}
										
										$this->Sign->rollback();
									}
									else
									{
										//Notifica al firmante
										$createNotification = $this->RequestAction('/notifications/requestToBeSignNotification/'.$sign['Sign']['system_id'].'/'.$sign['Sign']['user_id'].'/'.$sign['Sign']['relation_id']);
									
										//y corta el ciclo
										break;
									}
								}
							}
						
							//Envia un flash con mensaje de confrimacion y se redirecciona al index
							$this->Session->setFlash('Se ha enviado tu firma asignada.', 'flash_success');
							$this->redirect(array('controller' => $url , 'action' => 'index'));
						}
					}
				}
				else
				{
					$this->Session->setFlash('Esta firma no existe.', 'flash_deny');
						$this->redirect(array('controller' => 'render_funds', 'action' => 'index'));
				}
			}
		}
		
		
		function changeReplacementSigns($replacement_id = null)
		{
			$lastReplacement = $this->Replacement->find('first', array('conditions' => array('Replacement.id' => $replacement_id)));
					
			//Devuelve firmas cuando no esta activo el reemplazo
			if($lastReplacement['Replacement']['active'] == 0)
			{
				$signs = $this->Sign->find('all', array('conditions' => array('Sign.user_id' => $lastReplacement['Replacement']['replacing_user_id'])));
				
				if($signs != false)
				{
					for($x=0; $x < count($signs); $x++)
					{
						if($signs[$x]['Sign']['state_id'] == 1)
						{
							$signs[$x]['Sign']['replacement_sign'] = 0;
							$signs[$x]['Sign']['user_id'] = $lastReplacement['Replacement']['replaced_user_id'];
							$signs[$x]['Sign']['signer_name'] = $lastReplacement['replaced_user']['name']." ".$lastReplacement['replaced_user']['first_lastname'];
						}
					}
					
					if($this->Sign->saveAll($signs))
						return true;
					else
						return false;
				}
				else
				{
					return true;
				}			
			}
			
			//Cambia firmas cuando se activa un reemplazo
			
			if($lastReplacement['Replacement']['active'] == 1)
			{
				$signs = $this->Sign->find('all', array('conditions' => array('Sign.user_id' => $lastReplacement['Replacement']['replaced_user_id'])));
				
				if($signs != false)
				{
					for($x=0; $x < count($signs); $x++)
					{
						if($signs[$x]['Sign']['state_id'] == 1)
						{
							$signs[$x]['Sign']['replacement_sign'] = 1;
							$signs[$x]['Sign']['user_id'] = $lastReplacement['Replacement']['replacing_user_id'];
							$signs[$x]['Sign']['signer_name'] = $lastReplacement['replacing_user']['name']." ".$lastReplacement['replacing_user']['first_lastname'];
						}
					}
					
					if($this->Sign->saveAll($signs))
						return true;
					else
						return false;
				}
				else
				{
					return true;
				}
			}
		}
	}
?>
