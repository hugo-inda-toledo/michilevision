<?php
	class RenderFundsController extends AppController
	{
		var $name = 'RenderFunds';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('RenderFund','Management','User', 'CostCenterUser', 'CostCenter', 'Authorization', 'Badge', 'RenderFundRequest', 'State', 'Sign', 'CorrelativeNumber', 'AttributeTable', 'RenderFundFile');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		
		/*********************************************************************************/
		/*********************************************************************************/
		/******************Menu principal de fondos por rendir***********************/
		/*********************************************************************************/
		/*********************************************************************************/
		
		function mainMenu() 
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/1/mainMenu") == true)
			{
				$this->set('title_for_layout', 'Fondos por rendir :: Menu Principal');
				$this->set('userAdmin', $this->Auth->user('admin'));
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		/*********************************************************************************/
		/*********************************************************************************/
		/*******Creacion, Modificacion y Lógica para Fondos por rendir**********/
		/*********************************************************************************/
		/*********************************************************************************/
	  
		function index($source='default', $sort=null) 
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/1/index") == true)
			{
				$this->set('title_for_layout', 'Fondos por rendir');
				
				//fecha
				if($source == 'date')
				{
					$this->paginate = array('RenderFund' => array('limit' => 10, 'order' => array('RenderFund.created' => $sort)));
					
					if($sort =='asc')
						$this->Session->setFlash('Se ordeno por fecha ascendente', 'flash_success');
					else
						$this->Session->setFlash('Se ordeno por fecha descendente', 'flash_success');
				}
				
				//Solicitante
				if($source == 'request_client')
				{			
					$this->paginate = array('RenderFund' => array('limit' => 10, 'order' => array('User.name' => $sort)));
					
					if($sort =='asc')
						$this->Session->setFlash('Se ordeno por solicitante ascendente', 'flash_success');
					else
						$this->Session->setFlash('Se ordeno por solicitante descendente', 'flash_success');
				}

				//Gerencia	
				if($source == 'management')
				{	
					$this->paginate = array('RenderFund' => array('limit' => 10, 'order' => array('Management.management_name' => $sort)));
					
					if($sort =='asc')
						$this->Session->setFlash('Se ordeno por gerencia ascendente', 'flash_success');
					else
						$this->Session->setFlash('Se ordeno por gerencia descendente', 'flash_success');
				}
				
				//Numero de fondo
				if($source == 'fund_number')
				{
					$this->paginate = array('RenderFund' => array('limit' => 10, 'order' => array('RenderFund.fund_number' => $sort)));
					
					if($sort =='asc')
						$this->Session->setFlash('Se ordeno por numero de fondo ascendente', 'flash_success');
					else
						$this->Session->setFlash('Se ordeno por numero de fondo descendente', 'flash_success');
				}	

				//Centro de costo (Codigo)
				if($source == 'cost_center_code')
				{
					$this->paginate = array('RenderFund' => array('limit' => 10, 'order' => array('CostCenter.cost_center_code' => $sort)));
					
					if($sort =='asc')
						$this->Session->setFlash('Se ordeno por codigo de centro de costo ascendente', 'flash_success');
					else
						$this->Session->setFlash('Se ordeno por codigo de centro de costo descendente', 'flash_success');
				}
				
				//Centro de costo (Nombre)
				if($source == 'cost_center_name')
				{
					$this->paginate = array('RenderFund' => array('limit' => 10, 'order' => array('CostCenter.cost_center_name' => $sort)));
					
					if($sort =='asc')
						$this->Session->setFlash('Se ordeno por nombre de centro de costo ascendente', 'flash_success');
					else
						$this->Session->setFlash('Se ordeno por nombre de centro de costo descendente', 'flash_success');
				}
				
				/***** FILTROS ***/
				
				if($source == 'toApprove')
				{				
					$this->paginate = array('RenderFund' => array('limit' => 10, 'conditions' => array('RenderFund.state_id' => 1), 'order' => array('RenderFund.created' => 'DESC')));
					
					$this->Session->setFlash('Se filtro por fondos en aprobacion', 'flash_success');
				}
				if($source == 'approve')
				{
					$this->paginate = array('RenderFund' => array('limit' => 10, 'conditions' => array('RenderFund.state_id' => 2), 'order' => array('RenderFund.created' => 'DESC')));
					$this->Session->setFlash('Se filtro por fondos aprobados', 'flash_success');
				}
				if($source == 'decline')
				{
					$this->paginate = array('RenderFund' => array('limit' => 10, 'conditions' => array('RenderFund.state_id' => 3), 'order' => array('RenderFund.created' => 'DESC')));
					$this->Session->setFlash('Se filtro por fondos rechazados', 'flash_success');
				}
		
				
				/****** FIN DE FILTROS *****/

				//Default
				if($source == 'default')
				{
					$this->paginate = array('RenderFund' => array('limit' => 10, 'order' => array('RenderFund.created' => 'desc')));
				}	

				$this->set('data', $this->paginate());
				
				if(!empty($this->data))
				{
					$dataUser = $this->RequestAction('/external_functions/getDataSession');
					$rutaupload = 'files/render_funds/bills/';
					
					$result = $this->uploadFiles($this->data['RenderFund']['render_fund_data'], $rutaupload, $dataUser['User']['username'], $this->data['RenderFund']['id']);
					
					if($result == true)
					{
						$this->Session->setFlash('Se ha cargado el archivo exitosamente.', 'flash_success');	
						$this->redirect(array('action' => 'index'));
					}
					else
					{
						$this->Session->setFlash('Hubo un error al cargar el archivo, intentelo nuevamente', 'flash_error');	
						$this->redirect(array('action' => 'index'));
					}
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function  add()
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/1/add") == true)
			{
				$this->set('title_for_layout', 'Nuevo fondo por rendir');
				
				$data = $this->Auth->user('id');
				
				$last_id;
				
				$requestUser = $this->User->find('first', array('conditions' => array('User.id' => $data)));
				
				$costCenters = $this->CostCenterUser->find('all', array('conditions' => array('CostCenterUser.system_id' => 1, 'CostCenterUser.user_id' => $data), 'order' => 'CostCenter.cost_center_code ASC'));
				
				$badges = $this->Badge->find('list', array('fields' => array('Badge.id', 'Badge.badge')));
				
				$selectCostCenter = array();
				
				foreach($costCenters as $costCenter)
				{
					$selectCostCenter[$costCenter['CostCenter']['id']] = $costCenter['CostCenter']['cost_center_name']." (".$costCenter['CostCenter']['cost_center_code'].")";
				}
						
				$this->set('data', $requestUser);
				$this->set('costCenters', $selectCostCenter);
				$this->set('badges', $badges);
				
				if(!empty($this->data))
				{
					//Relleno los datos faltantes del arreglo
					$this->request->data['RenderFund']['user_id'] = $requestUser['User']['id'];
					$this->request->data['RenderFund']['dni_user'] = $requestUser['User']['dni'];
					$this->request->data['RenderFund']['management_id'] = $requestUser['Management']['id'];
					$this->request->data['RenderFund']['state_id'] = 1;
					
					//Contar precio total del fondo
					$grandTotal=0;
					foreach($this->request->data['RenderFundRequest']['price'] as $value)
					{
						$grandTotal += $value;
					}
					
					//Extraigo solo el arreglo con los datos del fondo
					$renderFunds['RenderFunds'] = $this->request->data['RenderFund'];
					
					//Extraigo y formateo arreglo con los montos y descripciones del fondo
					$renderFundsRequests = $this->formatArrayRequests($this->request->data['RenderFundRequest']);

					//Relleno nuevo arreglo en orden igual a la estructura de la base de datos
					$tmp = array();
					$tmp['RenderFund']['user_id'] = $renderFunds['RenderFunds']['user_id'];
					$tmp['RenderFund']['dni_user'] = $renderFunds['RenderFunds']['dni_user'];
					$tmp['RenderFund']['used_by_name'] = $renderFunds['RenderFunds']['used_by_name'];
					$tmp['RenderFund']['used_by_dni'] = $renderFunds['RenderFunds']['used_by_dni'];
					$tmp['RenderFund']['management_id'] = $renderFunds['RenderFunds']['management_id'];
					$tmp['RenderFund']['fund_number'] = "";
					$tmp['RenderFund']['render_fund_title'] = $renderFunds['RenderFunds']['render_fund_title'];
					$tmp['RenderFund']['authorization_id'] = $renderFunds['RenderFunds']['authorization_id'];
					$tmp['RenderFund']['badge_id'] = $renderFunds['RenderFunds']['badge_id'];
					$tmp['RenderFund']['cost_center_id'] = $renderFunds['RenderFunds']['cost_center_id'];
					$tmp['RenderFund']['reason'] = $renderFunds['RenderFunds']['reason'];
					$tmp['RenderFund']['state_id'] = $renderFunds['RenderFunds']['state_id'];
					$tmp['RenderFund']['approved'] = 0;
					$tmp['RenderFund']['deliver'] = 0;
					$tmp['RenderFund']['render'] =0;
					$tmp['RenderFund']['render_date_start'] = "";
					$tmp['RenderFund']['render_date_end'] = "";
					$tmp['RenderFund']['total_price'] = $grandTotal;
					
					//validacion de RenderFund
					if($tmp['RenderFund']['render_fund_title'] == '')
					{
						$this->Session->setFlash('Debes agregar un breve titulo al fondo a rendir.');
					}
					if($tmp['RenderFund']['used_by_name'] == '')
					{
						$this->Session->setFlash('Deben haber datos en el campo "Utilizado por"');
					}
					if($tmp['RenderFund']['used_by_dni'] == '')
					{
						$this->Session->setFlash('Deben haber datos en el campo "Rut Utilizante"');
					}
					if($tmp['RenderFund']['reason'] == '')
					{
						$this->Session->setFlash('Deben dar una razon en el campo "Motivo del gasto"');
					}
					
					//Pasar descripciones y precios al arreglo master
					foreach($renderFundsRequests as $key => $value)
					{	
						$tmp['RenderFundRequest'] =$value;
					}
					
					//Validacion de RenderFundRequest
					for($x=0; $x<count($tmp['RenderFundRequest']); $x++)
					{	
						if($tmp['RenderFundRequest'][$x]['description'] == '')
						{
							$this->Session->setFlash('Deben haber datos en los campos de descripcion');
							$this->redirect(array('action' => 'add'));
						}
						if($tmp['RenderFundRequest'][$x]['price'] == '')
						{
							$this->Session->setFlash('Deben haber datos en los campos de precio');
							$this->redirect(array('action' => 'add'));
						}
					}
					
					$this->RenderFund->begin();
					
					if ($this->RenderFund->save($tmp['RenderFund'])) 
					{	
						$last_id = $this->RenderFund->getLastInsertID();
						
						for($x=0; $x<count($tmp['RenderFundRequest']); $x++)
						{		
							$tmp['RenderFundRequest'][$x]['render_fund_id'] = $last_id;
						}
						
						$this->RenderFundRequest->begin();
						
						if($this->RenderFundRequest->saveAll($tmp['RenderFundRequest']))
						{
							$firstSign = $this->RequestAction('/signs/validateFirstSign/'.$this->RequestAction('/external_functions/getIdDataSession').'/1/'.$last_id);
							
							if($firstSign == true)
							{
								$this->RenderFundRequest->commit();
								$this->RenderFund->commit();
								
								$this->Session->setFlash('Se ha enviado el fondo por rendir.', 'flash_success');	
								$this->redirect(array('action' => 'index'));
							}
							
							$this->RenderFundRequest->rollback();
							$this->RenderFund->rollback();
						}
						
						$this->RenderFundRequest->rollback();
						$this->RenderFund->rollback();
					}
					
					$this->RenderFund->rollback();
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function view($id) 
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/1/view") == true)
			{
				//Se obtiene el id del fondo
				$this->RenderFund->id = $id;
				
				//Se lee la info del fondo  de la DB
				$this->data = $this->RenderFund->read();	
				$dataRenderFund = $this->data;
				
				$this->set('title_for_layout', 'Fondos por rendir :: '.$dataRenderFund['RenderFund']['render_fund_title']);
				
				//Se devuelve arreglos a la vista
				$this->set('data',   $dataRenderFund);
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		
		function resendFund($id =null)
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/1/resendFund") == true)
			{
				if(empty($this->data))
				{
					$requestUser = $this->RenderFund->find('first', array('conditions' => array('RenderFund.id' => $id)));
					
					$last_id;
					
					$this->set('title_for_layout', 'Reenviar fondo :: '.$requestUser['RenderFund']['render_fund_title']);
					
					$data = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id'))));
					
					$costCenters = $this->CostCenterUser->find('all', array('conditions' => array('CostCenterUser.system_id' => 1, 'CostCenterUser.user_id' => $data['User']['id'])));
					
					$badges = $this->Badge->find('list', array('fields' => array('Badge.id', 'Badge.badge')));
					
					$selectCostCenter = array();
					
					foreach($costCenters as $costCenter)
					{
						$selectCostCenter[$costCenter['CostCenter']['id']] = $costCenter['CostCenter']['cost_center_name']." (".$costCenter['CostCenter']['cost_center_code'].")";
					}
							
					$this->set('badges', $badges);
					$this->set('cost_centers', $selectCostCenter);
					$this->set('data', $requestUser);
				}
				
				if(!empty($this->request->data))
				{
					$dataSession = array();
					
					$dataSession = $this->RequestAction('/external_functions/getDataSession');
					//Relleno los datos faltantes del arreglo
					$this->request->data['RenderFund']['user_id'] = $dataSession['User']['id'];
					$this->request->data['RenderFund']['dni_user'] = $dataSession['User']['dni'];
					$this->request->data['RenderFund']['management_id'] = $dataSession['Management']['id'];
					$this->request->data['RenderFund']['state_id'] = 1;
					
					
					//Contar precio total del fondo
					$grandTotal=0;
					foreach($this->request->data['RenderFundRequest']['price'] as $value)
					{
						$grandTotal += $value;
					}
					
					//Extraigo solo el arreglo con los datos del fondo
					//$renderFunds['RenderFund'] = $this->data['RenderFund'];
					
					//Extraigo y formateo arreglo con los montos y descripciones del fondo
					$renderFundsRequests = $this->formatArrayRequests($this->request->data['RenderFundRequest']);

					//Relleno nuevo arreglo en orden igual a la estructura de la base de datos
					$tmp = array();
					$tmp['RenderFund']['user_id'] = $this->request->data['RenderFund']['user_id'];
					$tmp['RenderFund']['dni_user'] = $this->request->data['RenderFund']['dni_user'];
					$tmp['RenderFund']['used_by_name'] = $this->request->data['RenderFund']['used_by_name'];
					$tmp['RenderFund']['used_by_dni'] = $this->request->data['RenderFund']['used_by_dni'];
					$tmp['RenderFund']['management_id'] = $this->request->data['RenderFund']['management_id'];
					$tmp['RenderFund']['fund_number'] = "";
					$tmp['RenderFund']['render_fund_title'] = $this->request->data['RenderFund']['render_fund_title'];
					$tmp['RenderFund']['authorization_id'] = $this->request->data['RenderFund']['authorization_id'];
					$tmp['RenderFund']['badge_id'] = $this->request->data['RenderFund']['badge_id'];
					$tmp['RenderFund']['cost_center_id'] = $this->request->data['RenderFund']['cost_center_id'];
					$tmp['RenderFund']['reason'] = $this->request->data['RenderFund']['reason'];
					$tmp['RenderFund']['state_id'] = $this->request->data['RenderFund']['state_id'];
					$tmp['RenderFund']['approved'] = 0;
					$tmp['RenderFund']['deliver'] = 0;
					$tmp['RenderFund']['render'] =0;
					$tmp['RenderFund']['render_date_start'] = "";
					$tmp['RenderFund']['render_date_end'] = "";
					$tmp['RenderFund']['total_price'] = $grandTotal;
					
					//validacion de RenderFund
					if($tmp['RenderFund']['render_fund_title'] == '')
					{
						$this->Session->setFlash('Debes agregar un breve titulo al fondo a rendir.');
					}
					if($tmp['RenderFund']['used_by_name'] == '')
					{
						$this->Session->setFlash('Deben haber datos en el campo "Utilizado por"');
					}
					if($tmp['RenderFund']['used_by_dni'] == '')
					{
						$this->Session->setFlash('Deben haber datos en el campo "Rut Utilizante"');
					}
					if($tmp['RenderFund']['reason'] == '')
					{
						$this->Session->setFlash('Deben dar una razon en el campo "Motivo del gasto"');
					}
					
					//Pasar descripciones y precios al arreglo master
					foreach($renderFundsRequests as $key => $value)
					{	
						$tmp['RenderFundRequest'] =$value;
					}
					
					//Validacion de RenderFundRequest
					for($x=0; $x<count($tmp['RenderFundRequest']); $x++)
					{	
						if($tmp['RenderFundRequest'][$x]['description'] == '')
						{
							$this->Session->setFlash('Deben haber datos en los campos de descripcion');
						}
						if($tmp['RenderFundRequest'][$x]['price'] == '')
						{
							$this->Session->setFlash('Deben haber datos en los campos de precio');
						}
					}
					
					
					if ($this->RenderFund->save($tmp['RenderFund'])) 
					{	
						$last_id = $this->RenderFund->getLastInsertID();
						
						for($x=0; $x<count($tmp['RenderFundRequest']); $x++)
						{		
							$tmp['RenderFundRequest'][$x]['render_fund_id'] = $last_id;
						}
						
						if($this->RenderFundRequest->saveAll($tmp['RenderFundRequest']))
						{
							$firstSign = $this->RequestAction('/signs/validateFirstSign/'.$this->RequestAction('/external_functions/getIdDataSession').'/1/'.$last_id);
							
							if($firstSign == true)
							{
								$this->Session->setFlash('Se ha reenviado el fondo por rendir.', 'flash_success');	
								$this->redirect(array('action' => 'index'));
							}
						}
					}
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function financeCommentForm($id)
		{
			$table='';
				
				$js = "<script>
						
						function validateFinanceComment()
						{
							var comments = document.getElementById('comments_finance').value;
							var fund = document.getElementById('id_sign').value;
							var raiz = '';
							
							if (comments == '')
							{
								alert('El comentario no puede ir vacio');
							}
							else
							{
								document.location.href = 'raiz + /render_funds/commentToTreasury/'+comments+'/'+fund;
							}
						}
						
						</script>";
				
				$table .= $js;
				$table .= '<form id="CommentEditForm" name="CommentEditForm" accept-charset="utf-8">
				<table>
					<tr>
						<td><textarea name="comments_finance" style="width:200px;height:80px;" id="comments_finance"></textarea></td>
						<td><input name="id_sign" type="hidden" value="'.$id.'" id="id_sign" /><br />
						<button type="button" class="a_button" onclick="javascript:validateFinanceComment();">Comentar</button>
						<button type="button" class="a_button" onclick="hide_tipTip();">Cancelar</button></td>
					</tr>
					</table>
				</form>';
				
				return $table;
		}
		
		function commentToTreasury($comment, $fund)
		{
			$renderFund = $this->RenderFund->find('first', array('conditions' => array('RenderFund.id' => $fund)));
			
			$renderFund['RenderFund']['comments_finance'] = $comment;
			
			$renderFund['RenderFund']['fund_number']  = $this->RequestAction('/correlative_numbers/generateCorrelativeNumber/1/'.substr($renderFund['RenderFund']['created'], 0, 4));
			
			$this->RenderFund->begin();
			
			if($this->RenderFund->save($renderFund))
			{
				$messageToTreasury = $this->RequestAction('/notifications/notificationToTreasuryForRead/1/'.$this->RequestAction('/attribute_tables/validateTreasurer').'/'.$fund);
				if($messageToTreasury == true)
				{
					$this->RenderFund->commit();
				
					$this->Session->setFlash('Se ha enviado el comentario a tesoreria.', 'flash_success');	
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					$this->RenderFund->rollback();
					$this->Session->setFlash('No se ha enviado el comentario a tesoreria, intentelo nuevamente', 'flash_deny');	
					$this->redirect(array('action' => 'index'));
				}
			}
			
			$this->RenderFund->rollback();
		}
				
		function formatArrayRequests($formatArray)
		{
			$response = array();
			$cont = 0;
				
			
			foreach($formatArray['description'] as $value)
			{	
				$response['RenderFundRequest'][$cont]['render_fund_id'] = "";
				$response['RenderFundRequest'][$cont]['description'] = $value;
				$cont = $cont +1;
			}
			$cont = 0;
			
			foreach($formatArray['price'] as $value)
			{	
				$response['RenderFundRequest'][$cont]['price'] = $value;
				
				$cont = $cont +1;
			}
			
			return $response;
		}
		
		function CommentForm($id)
		{
			$table='';
				
				$js = "<script>
						
						function validateComment()
						{
							var comments = document.getElementById('comments_finance').value;
							var fund = document.getElementById('id_sign').value;
							var raiz = '';
							
							if (comments == '')
							{
								alert('El comentario no puede ir vacio');
							}
							else
							{
								document.location.href = raiz + '/render_funds/commentToRequestUser/'+comments+'/'+fund;
							}
						}
						
						</script>";
				
				$table .= $js;
				$table .= '<form id="CommentEditForm" name="CommentEditForm" accept-charset="utf-8">
				<table>
					<tr>
						<td><textarea name="comments_finance" style="width:200px;height:80px;" id="comments_finance"></textarea></td>
						<td><input name="id_sign" type="hidden" value="'.$id.'" id="id_sign" /><br />
						<button type="button" class="a_button" onclick="javascript:validateComment();">Comentar</button>
						<button type="button" class="a_button" onclick="hide_tipTip();">Cancelar</button></td>
					</tr>
					</table>
				</form>';
				
				return $table;
		}
		
		function commentToRequestUser($comment, $fund)
		{
			$renderFund = $this->RenderFund->find('first', array('conditions' => array('RenderFund.id' => $fund)));
			
			$renderFund['RenderFund']['comments'] = $comment;
			
			$this->RenderFund->begin();
			
			if($this->RenderFund->save($renderFund))
			{
				$messageToRequestUser = $this->RequestAction('/notifications/notificationToRequestUser/1/'.$renderFund['User']['id'].'/'.$fund);
				
				if($messageToRequestUser == true)
				{
					$this->RenderFund->commit();
				
					$this->Session->setFlash('Se ha enviado el comentario al solicitante.', 'flash_success');	
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					$this->RenderFund->rollback();
					$this->Session->setFlash('No se ha enviado el comentario al solicitante, intentalo nuevamente.', 'flash_deny');	
					$this->redirect(array('action' => 'index'));
				}
			}
			
			$this->RenderFund->rollback();
		}
		
		function setEndRenderDate($id, $calendar)
		{
			$fund = $this->RenderFund->find('first', array('conditions' => array('RenderFund.id' => $id)));
			$fund['RenderFund']['render_date_start'] = date('Y-m-d');
			$fund['RenderFund']['render_date_end'] = $calendar;
			$fund['RenderFund']['deliver'] = 1;
			
			$this->RenderFund->begin();
			
			if($this->RenderFund->save($fund))
			{
				$messageToRequestUser = $this->RequestAction('/notifications/notificationToRequestUserForSetDate/1/'.$fund['User']['id'].'/'.$id.'/'.$calendar);
				
				if($messageToRequestUser == true)
				{
					$this->RenderFund->commit();
				
					$this->Session->setFlash('Se ha guardado la fecha de tope con exito!', 'flash_success');	
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					$this->RenderFund->rollback();
					
					$this->Session->setFlash('No se ha guardado la fecha de tope, favor intentar nuevamente.', 'flash_deny');	
					$this->redirect(array('action' => 'index'));
				}
			}
			
			$this->RenderFund->rollback();
		}
		
		function uploadFiles($namefile, $rutaupload, $usuario, $renderFundId) 
		{
			    //global $usuario;
                // $rutaupload con / (slash) al final
                $maximosize = 5000000; // 750 kb
                $archivo_size = $namefile['size'];
                $fechahora = date("Ymd-His");
                $ruta_temporal = $namefile['tmp_name'];
                $archivo_name = $fechahora.'_'.strtoupper($usuario)."-".str_replace(" ","_",trim($namefile["name"]));
                if ($archivo_size <= $maximosize)
                {
                               if (!move_uploaded_file($ruta_temporal, $rutaupload . $archivo_name))
                               {
                                               //return "Error al copiar el archivo $ruta_temporal a la ruta $rutaupload" . $archivo_name;
                                               return false;
                               }
                               else
                               {
												//return "Archivo $ruta_temporal subido con exito a la ruta $rutaupload" . $archivo_name;
												$validateLoad = $this->loadFileToDatabase($rutaupload, $archivo_name, $renderFundId);
											   
												if($validateLoad == true)
													return true;
                               }
                }
                else
                {
                               //return "El archivo $ruta_temporal supera los " . ($maximosize/1000) . " kb.";
                               return false;
                }
		}
		
		function loadFileToDatabase($route, $filename, $fundId)
		{
			$databaseFile['render_fund_id'] = $fundId;
			$databaseFile['url'] = $route;
			$databaseFile['file'] = $filename;
			
			$this->RenderFundFile->begin();
			
			if($this->RenderFundFile->save($databaseFile))
			{	
				$dataFund = $this->RenderFund->find('first', array('conditions' => array('RenderFund.id' => $fundId)));
				$dataFund['RenderFund']['render'] = 1;
				
				$this->RenderFund->begin();
				
				if($this->RenderFund->save($dataFund))
				{
					$notificationToUser = $this->RequestAction('/notifications/notificationToRequestUserForTotalRender/1/'.$dataFund['User']['id'].'/'.$fundId);
					
					if($notificationToUser == true)
					{
						$this->RenderFundFile->commit();
						$this->RenderFund->commit();
					
						return true;
					}
					
					$this->RenderFundFile->rollback();
					$this->RenderFund->rollback();
					return false;
				}
				else
				{
					$this->RenderFundFile->rollback();
					$this->RenderFund->rollback();
					return false;
				}
			}
			
			$this->RenderFundFile->rollback();
		}
		
		/*********************************************************************************/
		/*********************************************************************************/
		/*********************************************************************************/
		/*********************************************************************************/
		/*********************************************************************************/
		
		/*********************************************************************************/
		/*********************************************************************************/
		/*************Informes y estadisticas para Fondos por rendir***************/
		/*********************************************************************************/
		/*********************************************************************************/
		
		function statsMenu() 
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/1/statsMenu") == true)
			{
				$this->set('title_for_layout', 'Fondos por rendir :: Informes y estadisticas');
				$this->set('userAdmin', $this->Auth->user('admin'));
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}	
		}
		
		function expiredFunds() 
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/1/expiredFunds") == true)
			{
				if($this->Auth->user('id') == $this->RequestAction('/attribute_tables/validateTreasurer') || $this->Auth->user('admin') == 1)
				{	
					$dataExpiredFunds = $this->RenderFund->find('all', array('conditions' => array('RenderFund.deliver' => 1, 'RenderFund.render' => 0, 'RenderFund.render_date_end <' => date('Y-m-d'))));
					
					$this->set('expiredFunds', $dataExpiredFunds);
					$this->set('title_for_layout', 'Fondos expirados');
					
					if(!empty($this->request->data))
					{
						/*echo "<pre>";
						print_r($this->request->data);
						echo "</pre>";*/
						
						if($this->request->data['RenderFund']['render_date'] != '')
						{
							if($this->request->data['RenderFund']['render_date'] <= date('Y-m-d'))
							{
								$this->Session->setFlash('La fecha debe ser mayor a la fecha de hoy ('.date('Y-m-d').').', 'flash_alert');	
								return false;
							}
							else
							{
								$editedFunds = 0;
								
								for($x=0; $x < count($this->request->data['FundChecked']); $x++)
								{
									if($this->request->data['FundChecked'][$x]['check_fund'] != 0)
									{
										$dataFund = $this->RenderFund->find('first', array('conditions' => array('RenderFund.id' => $this->request->data['FundChecked'][$x]['check_fund'])));
										$dataFund['RenderFund']['render_date_end'] = $this->request->data['RenderFund']['render_date'];
										
										$this->RenderFund->begin();
										
										if($this->RenderFund->save($dataFund))
										{
											$this->RenderFund->commit();
											$editedFunds++;
										}
										else
										{
											$this->RenderFund->rollback();
										}
									}
								}
								
								if($editedFunds == 1)
								{
									$this->Session->setFlash('El fondo fue editado en su fecha de expiracion.', 'flash_success');	
									$this->redirect(array('action' => 'expiredFunds'));
								}
								else
								{
									$this->Session->setFlash($editedFunds.' fondos fueron editados en su fecha de expiracion.', 'flash_success');	
									$this->redirect(array('action' => 'expiredFunds'));
								}
							}
						}
						else
						{
							$this->Session->setFlash('Debes seleccionar una fecha para asignar a los fondos expirados', 'flash_alert');	
							$this->redirect(array('action' => 'expiredFunds'));
						}
					}
				}
				else
				{
					$this->Session->setFlash('¿Estas seguro de tener permisos para ver esto?', 'flash_alert');	
					$this->redirect(array('action' => 'statsMenu'));
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		
		function quantizerExpenses() 
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/1/quantizerExpenses") == true)
			{
				$data = array();
				
				$cost_centers = $this->RequestAction('/external_functions/getCostCenterSelect');
				$managements = $this->RequestAction('/external_functions/showManagements');
										
				$this->set('cost_centers', $cost_centers);
				$this->set('managements', $managements);
				$this->set('data', $data);
				$this->set('title_for_layout', 'Cuantificador de gastos (C.Costos y/o Gerencias)');
				
				if(!empty($this->request->data))
				{
					if($this->request->data['RenderFund']['cost_center'] == 0 && $this->request->data['RenderFund']['management'] == 0)
					{
						$this->Session->setFlash('Debes seleccionar al menos un centro de costo o una gerencia', 'flash_alert');	
						return false;
					}
					
					if($this->request->data['RenderFund']['from_date'] == '')
					{
						$this->Session->setFlash('Debes seleccionar una fecha "Desde".', 'flash_alert');	
						return false;
					}
					
					if($this->request->data['RenderFund']['to_date'] == '')
					{
						$this->Session->setFlash('Debes seleccionar una fecha "Hasta".', 'flash_alert');	
						return false;
					}
					
					if($this->request->data['RenderFund']['from_date'] > $this->request->data['RenderFund']['to_date'])
					{
						$this->Session->setFlash('La fecha "Desde" debe ser mayor que la fecha "Hasta".', 'flash_alert');	
						return false;
					}
					
					$data = $this->calculateQuantizerExpenses($this->request->data['RenderFund']['cost_center'], $this->request->data['RenderFund']['management'], $this->request->data['RenderFund']['from_date'], $this->request->data['RenderFund']['to_date']);
					
					$this->set('forDownload', $this->request->data);
					$this->set('data', $data);
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function calculateQuantizerExpenses($cost_center, $management, $from_date, $to_date) 
		{
			$result = array();
			$dataToSend = array();

			$totalFunds=0;
			$approvedFunds=0;
			$onlyApproved=0;
			$renderFunds=0;
			$toRenderFunds=0;
			$expiredFunds=0;
			$declinedFunds=0;
			$toSignFunds =0;
			
			$clpTotal = 0;
			$usdTotal = 0;
			$euroTotal =0;
			$ufTotal = 0;
			$utmTotal = 0;
			
			$clpTotalRender = 0;
			$usdTotalRender = 0;
			$euroTotalRender =0;
			$ufTotalRender = 0;
			$utmTotalRender = 0;
			
			if($cost_center == 0 && $management >= 1)
			{
				$result =$this->RenderFund->find('all', array('conditions' => array('RenderFund.management_id' => $management, 'RenderFund.created BETWEEN ?  AND ?' => array($from_date, $to_date))));
				
				if(count($result) != 0)	
				{
					$dataToSend['management_name'] = $result[0]['Management']['management_name'];
					$dataToSend['cost_center_name'] = 'N/A';
				}
			}
			
			if($management == 0 && $cost_center >= 1)
			{
				$result =$this->RenderFund->find('all', array('conditions' => array('RenderFund.cost_center_id' => $cost_center, 'RenderFund.created BETWEEN ?  AND ?' => array($from_date, $to_date))));
				
				if(count($result) != 0)	
				{
					$dataToSend['cost_center_name'] = $result[0]['CostCenter']['cost_center_name'].' ('.$result[0]['CostCenter']['cost_center_code'].')';
					$dataToSend['management_name'] = 'N/A';
				}
			}
			
			if($management >= 1 && $cost_center >= 1)
			{
				$result =$this->RenderFund->find('all', array('conditions' => array('RenderFund.management_id' => $management, 'RenderFund.cost_center_id' => $cost_center, 'RenderFund.created BETWEEN ?  AND ?' => array($from_date, $to_date))));
				
				if(count($result) != 0)	
				{
					$dataToSend['management_name'] = $result[0]['Management']['management_name'];
					$dataToSend['cost_center_name'] = $result[0]['CostCenter']['cost_center_name'].' ('.$result[0]['CostCenter']['cost_center_code'].')';
				}
			}
			
			if(count($result) != 0)	
			{
				$u=0;
				$v=0;
				$w=0;
				$x = 0;
				$y=0;
				$z=0;
				
				foreach($result as $value)
				{
					if($value['RenderFund']['approved'] == 1)
					{
						$approvedFunds++;
						
						if(($value['RenderFund']['render'] == 1)  || ($value['RenderFund']['deliver'] == 1))
						{
							if($value['RenderFund']['render'] == 1)
							{
								$renderFunds++;
								
								$responseFunds['ApprovedFunds']['RenderFunds'][$v] = $value; 
								$v++;
								
								if($value['RenderFund']['badge_id'] == 1)
									$clpTotalRender += $value['RenderFund']['total_price'];
									
								if($value['RenderFund']['badge_id'] == 2)
									$usdTotalRender += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 3)
									$euroTotalRender += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 4)
									$ufTotalRender += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 5)
									$utmTotalRender += $value['RenderFund']['total_price'];
							}
							
							if($value['RenderFund']['deliver'] == 1)
							{
								if($value['RenderFund']['render_date_end'] < date('Y-m-d') && $value['RenderFund']['render'] != 1)
								{
									$expiredFunds++;
									$responseFunds['ApprovedFunds']['ExpiredFunds'][$w] = $value; 
									$w++;
								}	
								
								if($value['RenderFund']['render'] == 0)
								{
									$toRenderFunds++;
									$responseFunds['ApprovedFunds']['ToRenderFunds'][$x] = $value; 
									$x++;
								}	
								
								if($value['RenderFund']['badge_id'] == 1)
									$clpTotal += $value['RenderFund']['total_price'];
									
								if($value['RenderFund']['badge_id'] == 2)
									$usdTotal += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 3)
									$euroTotal += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 4)
									$ufTotal += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 5)
									$utmTotal += $value['RenderFund']['total_price'];
							}
						}
						else
						{
							$responseFunds['ApprovedFunds']['Approved'][$u] = $value; 
							$onlyApproved++;
							$u++;
						}
					}
					else
					{
						if($value['RenderFund']['state_id'] == 3)
						{
							$declinedFunds++;
							$responseFunds['DeclinedFunds'][$y] = $value; 
							$y++;
						}
						else
						{
							if($value['RenderFund']['state_id'] == 1)
							{
								$toSignFunds++;
								$responseFunds['ToSignFunds'][$z] = $value; 
								$z++;
							}
						}
					}
					
					$totalFunds++;
				}
				
				$dataToSend['approved_funds'] = $approvedFunds;
				$dataToSend['render_funds'] = $renderFunds;
				$dataToSend['to_render_funds'] = $toRenderFunds;
				$dataToSend['expired_funds'] = $expiredFunds;
				$dataToSend['only_approved'] = $onlyApproved;
				
				$dataToSend['clp_total'] = $clpTotal;
				$dataToSend['usd_total'] = $usdTotal;
				$dataToSend['euro_total'] = $euroTotal;
				$dataToSend['uf_total'] = $ufTotal;
				$dataToSend['utm_total'] = $utmTotal;
				
				$dataToSend['clp_total_render'] = $clpTotalRender;
				$dataToSend['usd_total_render'] = $usdTotalRender;
				$dataToSend['euro_total_render'] = $euroTotalRender;
				$dataToSend['uf_total_render'] = $ufTotalRender;
				$dataToSend['utm_total_render'] = $utmTotalRender;
				
				$dataToSend['total_funds'] = $totalFunds;
				$dataToSend['declined_funds'] = $declinedFunds;
				$dataToSend['to_sign_funds'] = $toSignFunds;
				
				
				$responseFunds['Details'] = $dataToSend;
				
				return $responseFunds;
			}
			else
			{
				return false;
			}
		}
		
		function quantizerUsersExpenses() 
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/1/quantizerUsersExpenses") == true)
			{
				$data = array();
				$dataUsers = $this->RequestAction('/external_functions/showAllUsuarios/');
				
				$this->set('users', $dataUsers);
				$this->set('data', $data);
				$this->set('title_for_layout', 'Cuantificador de gastos (Usuarios)');
				
				if(!empty($this->request->data))
				{
					if($this->request->data['RenderFund']['user'] == 0)
					{
						$this->Session->setFlash('Debes seleccionar un usuario.', 'flash_alert');	
						return false;
					}
					
					if($this->request->data['RenderFund']['from_date'] == '')
					{
						$this->Session->setFlash('Debes seleccionar una fecha "Desde".', 'flash_alert');	
						return false;
					}
					
					if($this->request->data['RenderFund']['to_date'] == '')
					{
						$this->Session->setFlash('Debes seleccionar una fecha "Hasta".', 'flash_alert');	
						return false;
					}
					
					$data = $this->calculateQuantizerUsersExpenses($this->request->data['RenderFund']['user'], $this->request->data['RenderFund']['from_date'], $this->request->data['RenderFund']['to_date']);
					
					$this->set('data', $data);
					$this->set('forDownload', $this->request->data);
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function calculateQuantizerUsersExpenses($user, $from_date, $to_date) 
		{
			$result = array();
			$dataToSend = array();
			
			$totalFunds=0;
			$approvedFunds=0;
			$onlyApproved=0;
			$renderFunds=0;
			$toRenderFunds=0;
			$expiredFunds=0;
			$declinedFunds=0;
			$toSignFunds =0;
			
			$clpTotal = 0;
			$usdTotal = 0;
			$euroTotal =0;
			$ufTotal = 0;
			$utmTotal = 0;
			
			$clpTotalRender = 0;
			$usdTotalRender = 0;
			$euroTotalRender =0;
			$ufTotalRender = 0;
			$utmTotalRender = 0;
	
			$result =$this->RenderFund->find('all', array('conditions' => array('RenderFund.user_id' => $user, 'RenderFund.created BETWEEN ?  AND ?' => array($from_date, $to_date))));
			
			if(count($result) != 0)	
			{
				$dataToSend['user'] = $result[0]['User']['name'].' '.$result[0]['User']['first_lastname'];
				
				$u=0;
				$v=0;
				$w=0;
				$x = 0;
				$y=0;
				$z=0;
				
				foreach($result as $value)
				{
					if($value['RenderFund']['approved'] == 1)
					{
						$approvedFunds++;
						
						if(($value['RenderFund']['render'] == 1)  || ($value['RenderFund']['deliver'] == 1))
						{
							if($value['RenderFund']['render'] == 1)
							{
								$renderFunds++;
								
								$responseFunds['ApprovedFunds']['RenderFunds'][$v] = $value; 
								$v++;
								
								if($value['RenderFund']['badge_id'] == 1)
									$clpTotalRender += $value['RenderFund']['total_price'];
									
								if($value['RenderFund']['badge_id'] == 2)
									$usdTotalRender += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 3)
									$euroTotalRender += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 4)
									$ufTotalRender += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 5)
									$utmTotalRender += $value['RenderFund']['total_price'];
							}
							
							if($value['RenderFund']['deliver'] == 1)
							{
								if($value['RenderFund']['render_date_end'] < date('Y-m-d') && $value['RenderFund']['render'] != 1)
								{
									$expiredFunds++;
									$responseFunds['ApprovedFunds']['ExpiredFunds'][$w] = $value; 
									$w++;
								}	
								
								if($value['RenderFund']['render'] == 0)
								{
									$toRenderFunds++;
									$responseFunds['ApprovedFunds']['ToRenderFunds'][$x] = $value; 
									$x++;
								}	
								
								if($value['RenderFund']['badge_id'] == 1)
									$clpTotal += $value['RenderFund']['total_price'];
									
								if($value['RenderFund']['badge_id'] == 2)
									$usdTotal += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 3)
									$euroTotal += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 4)
									$ufTotal += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 5)
									$utmTotal += $value['RenderFund']['total_price'];
							}
						}
						else
						{
							$responseFunds['ApprovedFunds']['Approved'][$u] = $value; 
							$onlyApproved++;
							$u++;
						}
					}
					else
					{
						if($value['RenderFund']['state_id'] == 3)
						{
							$declinedFunds++;
							$responseFunds['DeclinedFunds'][$y] = $value; 
							$y++;
						}
						else
						{
							if($value['RenderFund']['state_id'] == 1)
							{
								$toSignFunds++;
								$responseFunds['ToSignFunds'][$z] = $value; 
								$z++;
							}
						}
					}
					
					$totalFunds++;
				}
				
				$dataToSend['approved_funds'] = $approvedFunds;
				$dataToSend['only_approved'] = $onlyApproved;
				$dataToSend['render_funds'] = $renderFunds;
				$dataToSend['to_render_funds'] = $toRenderFunds;
				$dataToSend['expired_funds'] = $expiredFunds;
				$dataToSend['clp_total'] = $clpTotal;
				$dataToSend['usd_total'] = $usdTotal;
				$dataToSend['euro_total'] = $euroTotal;
				$dataToSend['uf_total'] = $ufTotal;
				$dataToSend['utm_total'] = $utmTotal;
				
				$dataToSend['clp_total_render'] = $clpTotalRender;
				$dataToSend['usd_total_render'] = $usdTotalRender;
				$dataToSend['euro_total_render'] = $euroTotalRender;
				$dataToSend['uf_total_render'] = $ufTotalRender;
				$dataToSend['utm_total_render'] = $utmTotalRender;
				
				$dataToSend['total_funds'] = $totalFunds;
				$dataToSend['declined_funds'] = $declinedFunds;
				$dataToSend['to_sign_funds'] = $toSignFunds;

				$responseFunds['Details'] = $dataToSend;
				
				return $responseFunds;
			}
			else
			{
				return false;
			}
		}
		
		function quantizerGeneralExpenses() 
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/1/quantizerGeneralExpenses") == true)
			{
				$data = array();
				
				$this->set('data', $data);
				$this->set('title_for_layout', 'Cuantificador de gastos (General)');
				
				if(!empty($this->request->data))
				{
					if($this->request->data['RenderFund']['from_date'] == '')
					{
						$this->Session->setFlash('Debes seleccionar una fecha "Desde".', 'flash_alert');	
						$this->redirect(array('action' => 'quantizerUsersExpenses'));
					}
					
					if($this->request->data['RenderFund']['to_date'] == '')
					{
						$this->Session->setFlash('Debes seleccionar una fecha "Hasta".', 'flash_alert');	
						$this->redirect(array('action' => 'quantizerUsersExpenses'));
					}
					
					$data = $this->calculateQuantizerGeneralExpenses($this->request->data['RenderFund']['from_date'], $this->request->data['RenderFund']['to_date']);
					
					$this->set('data', $data);
					$this->set('forDownload', $this->request->data);
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function calculateQuantizerGeneralExpenses($from_date, $to_date) 
		{
			$result = array();
			$dataToSend = array();
			
			$totalFunds=0;
			$approvedFunds=0;
			$onlyApproved=0;
			$renderFunds=0;
			$toRenderFunds=0;
			$expiredFunds=0;
			$declinedFunds=0;
			$toSignFunds =0;
			
			$clpTotal = 0;
			$usdTotal = 0;
			$euroTotal =0;
			$ufTotal = 0;
			$utmTotal = 0;
			
			$clpTotalRender = 0;
			$usdTotalRender = 0;
			$euroTotalRender =0;
			$ufTotalRender = 0;
			$utmTotalRender = 0;
			
	
			$result =$this->RenderFund->find('all', array('conditions' => array('RenderFund.created BETWEEN ?  AND ?' => array($from_date, $to_date))));
			
			if(count($result) != 0)	
			{
				
				$u=0;
				$v=0;
				$w=0;
				$x = 0;
				$y=0;
				$z=0;
				
				foreach($result as $value)
				{
					if($value['RenderFund']['approved'] == 1)
					{
						$approvedFunds++;
						
						if(($value['RenderFund']['render'] == 1)  || ($value['RenderFund']['deliver'] == 1))
						{
							if($value['RenderFund']['render'] == 1)
							{
								$renderFunds++;
								
								$responseFunds['ApprovedFunds']['RenderFunds'][$v] = $value; 
								$v++;
								
								if($value['RenderFund']['badge_id'] == 1)
									$clpTotalRender += $value['RenderFund']['total_price'];
									
								if($value['RenderFund']['badge_id'] == 2)
									$usdTotalRender += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 3)
									$euroTotalRender += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 4)
									$ufTotalRender += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 5)
									$utmTotalRender += $value['RenderFund']['total_price'];
							}
							
							if($value['RenderFund']['deliver'] == 1)
							{
								if($value['RenderFund']['render_date_end'] < date('Y-m-d') && $value['RenderFund']['render'] != 1)
								{
									$expiredFunds++;
									$responseFunds['ApprovedFunds']['ExpiredFunds'][$w] = $value; 
									$w++;
								}	
								
								if($value['RenderFund']['render'] == 0)
								{
									$toRenderFunds++;
									$responseFunds['ApprovedFunds']['ToRenderFunds'][$x] = $value; 
									$x++;
								}	
								
								if($value['RenderFund']['badge_id'] == 1)
									$clpTotal += $value['RenderFund']['total_price'];
									
								if($value['RenderFund']['badge_id'] == 2)
									$usdTotal += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 3)
									$euroTotal += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 4)
									$ufTotal += $value['RenderFund']['total_price'];
								
								if($value['RenderFund']['badge_id'] == 5)
									$utmTotal += $value['RenderFund']['total_price'];
							}
						}
						else
						{
							$responseFunds['ApprovedFunds']['Approved'][$u] = $value; 
							$onlyApproved++;
							$u++;
						}
					}
					else
					{
						if($value['RenderFund']['state_id'] == 3)
						{
							$declinedFunds++;
							$responseFunds['DeclinedFunds'][$y] = $value; 
							$y++;
						}
						else
						{
							if($value['RenderFund']['state_id'] == 1)
							{
								$toSignFunds++;
								$responseFunds['ToSignFunds'][$z] = $value; 
								$z++;
							}
						}
					}
					
					$totalFunds++;
				}
				
				$dataToSend['approved_funds'] = $approvedFunds;
				$dataToSend['only_approved'] = $onlyApproved;
				$dataToSend['render_funds'] = $renderFunds;
				$dataToSend['to_render_funds'] = $toRenderFunds;
				$dataToSend['expired_funds'] = $expiredFunds;
				$dataToSend['clp_total'] = $clpTotal;
				$dataToSend['usd_total'] = $usdTotal;
				$dataToSend['euro_total'] = $euroTotal;
				$dataToSend['uf_total'] = $ufTotal;
				$dataToSend['utm_total'] = $utmTotal;
				
				$dataToSend['clp_total_render'] = $clpTotalRender;
				$dataToSend['usd_total_render'] = $usdTotalRender;
				$dataToSend['euro_total_render'] = $euroTotalRender;
				$dataToSend['uf_total_render'] = $ufTotalRender;
				$dataToSend['utm_total_render'] = $utmTotalRender;
				
				$dataToSend['total_funds'] = $totalFunds;
				$dataToSend['declined_funds'] = $declinedFunds;
				$dataToSend['to_sign_funds'] = $toSignFunds;
				$dataToSend['from_date'] = $from_date;
				$dataToSend['to_date'] = $to_date;

				$responseFunds['Details'] = $dataToSend;
				
				return $responseFunds;
			}
			else
			{
				return false;
			}
		}
		
		function quantizer_expenses_management_report($cost_center = null, $management = null, $start_date= null, $end_date = null)
		{
			// Sobrescribimos para que no aparezcan los resultados de debuggin
			// ya que sino daria un error al generar el pdf.
			Configure::write('debug',0);

			$resultado = $this->calculateQuantizerExpenses($cost_center, $management, $start_date, $end_date);
			
			$resultado['Details']['start_date'] = $this->RequestAction('/external_functions/setDate/'.$start_date);
			$resultado['Details']['end_date'] = $this->RequestAction('/external_functions/setDate/'.$end_date);
			$resultado['Details']['date_today'] = date('d-m-Y H:i:s');
			
			$user = $this->RequestAction('/external_functions/getDataSession');
			
			$resultado['Details']['generated_by'] = $user['User']['name'].' '.$user['User']['first_lastname'];
			
			/*echo "<pre>";
			print_r($resultado);
			echo "</pre>";*/
			
			$this->set('data', $resultado);
			$this->layout = 'pdf';
			$this->render();
		}
		
		function quantizer_expenses_general_report($start_date= null, $end_date = null)
		{
			// Sobrescribimos para que no aparezcan los resultados de debuggin
			// ya que sino daria un error al generar el pdf.
			Configure::write('debug',0);

			$resultado = $this->calculateQuantizerGeneralExpenses($start_date, $end_date);
			
			$resultado['Details']['start_date'] = $this->RequestAction('/external_functions/setDate/'.$start_date);
			$resultado['Details']['end_date'] = $this->RequestAction('/external_functions/setDate/'.$end_date);
			$resultado['Details']['date_today'] = date('d-m-Y H:i:s');
			
			$user = $this->RequestAction('/external_functions/getDataSession');
			
			$resultado['Details']['generated_by'] = $user['User']['name'].' '.$user['User']['first_lastname'];

			$this->set('data', $resultado);
			$this->layout = 'pdf';
			$this->render();
		}
		
		function quantizer_expenses_user_report($user = null, $start_date= null, $end_date = null)
		{
			// Sobrescribimos para que no aparezcan los resultados de debuggin
			// ya que sino daria un error al generar el pdf.
			Configure::write('debug',0);

			$resultado = $this->calculateQuantizerUsersExpenses($user, $start_date, $end_date);
			
			$resultado['Details']['start_date'] = $this->RequestAction('/external_functions/setDate/'.$start_date);
			$resultado['Details']['end_date'] = $this->RequestAction('/external_functions/setDate/'.$end_date);
			$resultado['Details']['date_today'] = date('d-m-Y H:i:s');
			
			$user = $this->RequestAction('/external_functions/getDataSession');
			
			$resultado['Details']['generated_by'] = $user['User']['name'].' '.$user['User']['first_lastname'];

			$this->set('data', $resultado);
			$this->layout = 'pdf';
			$this->render();
		}
		
		
		function activeAction($action)
		{
			parent::beforeFilter();
			$this->Auth->allow($action);
		}
		
		function arrayShow()
		{
			$renderFunds = $this->RenderFund->find('all');
			$this->set('renderFunds', $renderFunds);
		}
	}
?>