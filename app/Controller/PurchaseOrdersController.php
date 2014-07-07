<?php

	App::uses('CakeEmail', 'Network/Email');
	
	class PurchaseOrdersController extends AppController
	{
		var $name = 'PurchaseOrders';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('PurchaseOrder','Management','User', 'CostCenterUser', 'CostCenter', 'Authorization', 'Badge', 
									'PurchaseOrderRequest', 'State', 'Sign', 'CorrelativeNumber', 'AttributeTable', 'Provider', 'MeasuringUnit', 
										'Budget', 'ProviderRequest', 'Cwtauxi', 'ModifiedRequestOrder', 'Tax', 'ApprovedOrder', 'Tax', 'Cwmovim');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		function index($source='default', $sort=null) 
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/2/index") == true)
			{
				//$dataOrders = $this->PurchaseOrder->find('all', array('order' => 'PurchaseOrder.created DESC'));
				if($source == 'default')
				{
					$this->paginate = array('PurchaseOrder' => array('limit' => 10, 'order' => array('PurchaseOrder.created' => 'Desc')));
				}
				
				if($source == 'me')
				{
					$this->paginate = array('PurchaseOrder' => array('limit' => 10, 'conditions' => array('PurchaseOrder.user_id' => $this->Auth->user('id')), 'order' => array('PurchaseOrder.created' => 'Desc')));
					$this->Session->setFlash('Se ha ordenado por ordenes hechas por mi', 'flash_success');
				}
				
				if($source == 'budgeted')
				{
					$this->paginate = array('PurchaseOrder' => array('limit' => 10, 'conditions' => array('PurchaseOrder.upload_user' => 1), 'order' => array('PurchaseOrder.created' => 'Desc')));
					$this->Session->setFlash('Se ha ordenado por ordenes con cotizaciones', 'flash_success');
				}
				
				if($source == 'unbudgeted')
				{
					$this->paginate = array('PurchaseOrder' => array('limit' => 10, 'conditions' => array('PurchaseOrder.upload_user' => 0), 'order' => array('PurchaseOrder.created' => 'Desc')));
					$this->Session->setFlash('Se ha ordenado por ordenes sin cotizaciones', 'flash_success');
				}
				
				if($source == 'approved')
				{
					$this->paginate = array('PurchaseOrder' => array('limit' => 10, 'conditions' => array('PurchaseOrder.state_id' => 2), 'order' => array('PurchaseOrder.created' => 'Desc')));
					$this->Session->setFlash('Se ha ordenado por ordenes aprobadas', 'flash_success');
				}
				
				if($source == 'waiting')
				{
					$this->paginate = array('PurchaseOrder' => array('limit' => 10, 'conditions' => array('PurchaseOrder.state_id' => 1), 'order' => array('PurchaseOrder.created' => 'Desc')));
					$this->Session->setFlash('Se ha ordenado por ordenes en espera', 'flash_success');
				}
				
				if($source == 'decline')
				{
					$this->paginate = array('PurchaseOrder' => array('limit' => 10, 'conditions' => array('PurchaseOrder.state_id' => 3), 'order' => array('PurchaseOrder.created' => 'Desc')));
					$this->Session->setFlash('Se ha ordenado por ordenes rechazadas', 'flash_success');
				}
				
				$x=0;
				
				$orders = $this->paginate();
				
				foreach($orders as $order)
				{
					$y=0;
					
					foreach($order['PurchaseOrderRequest'] as $request)
					{
						$unit = $this->MeasuringUnit->find('first', array('conditions' => array('MeasuringUnit.id' => $request['measuring_unit_id']), 'fields' => array('MeasuringUnit.unit')));
						$orders[$x]['PurchaseOrderRequest'][$y]['unit'] = $unit['MeasuringUnit']['unit'];
						
						$y++;
					}
					
					$x++;
				}
				
				for($x=0; $x < count($orders); $x++)
				{
					if($orders[$x]['ApprovedOrder'] != false)
					{
						for($y=0; $y < count($orders[$x]['ApprovedOrder'] ); $y++)
						{
							if($orders[$x]['ApprovedOrder'][$y]['active'] == 1)
							{
								if($orders[$x]['ApprovedOrder'][$y]['tax_id'] != 0)
								{
									$dataTax = $this->Tax->find('first', array('conditions' => array('Tax.id' => $orders[$x]['ApprovedOrder'][$y]['tax_id'] )));
									$orders[$x]['ApprovedOrder'][$y]['Tax']  = $dataTax['Tax'];
								}
							}
						}
					}
				}
				
				/*echo "<pre>";
				print_r($orders);
				echo "</pre>";*/
				
				$this->set('data', $orders);
				$this->set('userLogin', $this->Auth->user('id'));
				
				$this->set('title_for_layout', 'Ordenes de Compra');
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function view($id = null)
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/2/view") == true)
			{
				if($id != null)
				{
					$this->PurchaseOrder->id = $id;
					$value = $this->PurchaseOrder->read();
					
					/*echo "<pre>";
					print_r($value);
					echo "</pre>";*/
					
					for($x=0; $x < count($value['PurchaseOrderRequest']); $x++)
					{
						$unit = $this->MeasuringUnit->find('first', array('conditions' => array('MeasuringUnit.id' => $value['PurchaseOrderRequest'][$x]['measuring_unit_id']), 'fields' => array('MeasuringUnit.unit')));
						$value['PurchaseOrderRequest'][$x]['unit'] = $unit['MeasuringUnit']['unit'];
					}
					
					if($value['ApprovedOrder'] != false)
					{
						for($y=0; $y < count($value['ApprovedOrder'] ); $y++)
						{
							if($value['ApprovedOrder'][$y]['active'] == 1)
							{
								if($value['ApprovedOrder'][$y]['tax_id'] != 0)
								{
									$dataTax = $this->Tax->find('first', array('conditions' => array('Tax.id' => $value['ApprovedOrder'][$y]['tax_id'] )));
									$value['ApprovedOrder'][$y]['Tax']  = $dataTax['Tax'];
								}
							}
						}
					}
					
					$this->set('value', $value);
					$this->set('title_for_layout', 'Ordenes de Compra :: Vista de la Orden');
					$this->set('userLogin', $this->Auth->user('id'));
				}
				else
				{
					$this->Session->setFlash('El Id de la orden de compra no puede ser nulo', 'flash_error');
					$this->redirect(array('controller' => 'purchase_orders', 'action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function add($option = null)
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/2/add") == true)
			{
				$data = $this->Auth->user('id');
					
				$requestUser = $this->User->find('first', array('conditions' => array('User.id' => $data)));
				$costCenters = $this->CostCenterUser->find('all', array('conditions' => array('CostCenterUser.system_id' => 2, 'CostCenterUser.user_id' => $data)));
				$badges = $this->Badge->find('list', array('fields' => array('Badge.id', 'Badge.badge')));
				$managementUserData = $this->User->find('first', array('conditions' => array('User.id' => $requestUser['Management']['user_id'])));
				
				
				$providers = $this->Provider->find('all', array('order' => 'Provider.provider_name ASC'));	
				
				$measuring_units = $this->MeasuringUnit->find('list', array('fields'=> array('MeasuringUnit.id', 'MeasuringUnit.unit'), 'order' => 'MeasuringUnit.id ASC'));
				
				$selectCostCenter = array();
				
				foreach($costCenters as $costCenter)
				{
					$selectCostCenter[$costCenter['CostCenter']['id']] = $costCenter['CostCenter']['cost_center_name']." (".$costCenter['CostCenter']['cost_center_code'].")";
				}
				
				$this->set('measuring_units', $measuring_units);
				$this->set('providers', $providers);
				$this->set('requestUser', $requestUser);
				$this->set('costCenters', $selectCostCenter);
				$this->set('badges', $badges);
				$this->set('management_user', $managementUserData);
				$this->set('title_for_layout', 'Ordenes de Compra :: Nueva Orden');
				
				if(!empty($this->request->data))
				{
					$dataOrder = array();
					$last_id = '';
					$grandTotal;
					
					if(isset($this->request->data['Budget']))
					{
						if(@count($this->request->data['Budget']) == 0)
						{	
							$this->Session->setFlash('Debes cargar por lo menos una cotizacion a la orden de compra');
							return false;
						}
						
						if(@count($this->request->data['Budget']['proposal_budget']) == 0)
						{
							foreach($this->request->data['Budget']['file'] as $value)
							{
								unlink('files/purchase_orders/budgets/'.$value);
							}
							
							$this->Session->setFlash('Debes seleccionar una cotización.', 'flash_alert');
							return false;
						}
						
						if($this->request->data['Budget'] != false)
						{
							$purchaseOrdersBudgets = $this->formatArrayBudgets($this->request->data['Budget']);
						}
						else
						{
							$this->Session->setFlash('Debe haber por lo menos una cotización cargada con su respectivo proveedor', 'flash_alert');
							return false;
						}
					}
					
					//datos de la orden de compra
					$dataOrder['PurchaseOrder']['user_id'] = $this->request->data['PurchaseOrder']['user_id'];
					$dataOrder['PurchaseOrder']['management_id'] = $this->request->data['PurchaseOrder']['management_id'];
					$dataOrder['PurchaseOrder']['management_user_id'] = $this->request->data['PurchaseOrder']['management_user_id'];
					$dataOrder['PurchaseOrder']['dni_user'] = $this->RequestAction('/external_functions/getDniUser/'.$this->request->data['PurchaseOrder']['user_id']);
					$dataOrder['PurchaseOrder']['dni_management_user'] = $this->RequestAction('/external_functions/getDniUser/'. $this->request->data['PurchaseOrder']['management_user_id']);
					$dataOrder['PurchaseOrder']['cost_center_id'] = $this->request->data['PurchaseOrder']['cost_center_id'];
					$dataOrder['PurchaseOrder']['invoice_to'] = $this->request->data['PurchaseOrder']['invoice_to'];
					$dataOrder['PurchaseOrder']['purchase_type'] = $this->request->data['PurchaseOrder']['purchase_type'];
					$dataOrder['PurchaseOrder']['only_provider'] = $this->request->data['PurchaseOrder']['only_provider'];
					$dataOrder['PurchaseOrder']['budgeted'] = $this->request->data['PurchaseOrder']['budgeted'];
					$dataOrder['PurchaseOrder']['reason'] = $this->request->data['PurchaseOrder']['reason'];
					$dataOrder['PurchaseOrder']['authorization_id'] = $this->request->data['PurchaseOrder']['authorization_id'];
					$dataOrder['PurchaseOrder']['badge_id'] = $this->request->data['PurchaseOrder']['badge_id'];
					$dataOrder['PurchaseOrder']['approved'] = 0;
					$dataOrder['PurchaseOrder']['state_id'] = 1;
					$dataOrder['PurchaseOrder']['request_number'] = $this->RequestAction('/correlative_numbers/generateCorrelativeNumber/2/'.date('Y').'/correlative_type/solicitud');
					$dataOrder['PurchaseOrder']['order_number'] = '';
					$dataOrder['PurchaseOrder']['upload_user'] = 1;
					$dataOrder['PurchaseOrder']['comments'] = '';
					
					//Se formatea el arreglo para insercion de datos
					$purchaseOrdersRequests = $this->formatArrayRequests($this->request->data['PurchaseOrderRequest'], $dataOrder['PurchaseOrder']['upload_user']);
					
					//Se agregan los datos del arreglo formateado con los "requests" al arreglo con toda la data.
					foreach($purchaseOrdersRequests as $key => $value)
					{	
						$dataOrder['PurchaseOrderRequest'] = $value;
					}
					
					//Se agregan los datos del arreglo formateado con las cotizaciones al arreglo con toda la data.
					if(isset($this->request->data['Budget']))
					{
						if($this->request->data['Budget'] != false)
						{
							foreach($purchaseOrdersBudgets as $key => $value)
							{	
								$dataOrder['Budget'] = $value;
							}
						}
					}
					
					for($x=0; $x < count($dataOrder['PurchaseOrderRequest']); $x++)
					{
						$totalRequest = $dataOrder['PurchaseOrderRequest'][$x]['net_price'] * $dataOrder['PurchaseOrderRequest'][$x]['quantity'];
						
						$grandTotal = $grandTotal + $totalRequest;
					}
					
					$dataOrder['PurchaseOrder']['grand_net_total_price'] = $grandTotal;
					
					$validateArray = $this->validatePurchaseOrderBudgeted($dataOrder);

					if($validateArray == true)
					{
						$this->PurchaseOrder->begin();
						
						if($this->PurchaseOrder->saveAll($dataOrder['PurchaseOrder']))
						{
							$last_id = $this->PurchaseOrder->getLastInsertID();
							
							for($x=0; $x<count($dataOrder['PurchaseOrderRequest']); $x++)
							{		
								$dataOrder['PurchaseOrderRequest'][$x]['purchase_order_id'] = $last_id;
							}
							
							$this->PurchaseOrderRequest->begin();
							
							if($this->PurchaseOrderRequest->saveAll($dataOrder['PurchaseOrderRequest']))
							{
								for($x=0; $x<count($dataOrder['Budget']); $x++)
								{		
									$dataOrder['Budget'][$x]['purchase_order_id'] = $last_id;
									if($dataOrder['Budget'][$x]['proposal_budget'] == 1)
									{
										$dataOrder['Budget'][$x]['selected'] = 1;
									}
									else
									{
										$dataOrder['Budget'][$x]['selected'] = 0;
									}
								}
								
								$this->Budget->begin();
								
								if($this->Budget->saveAll($dataOrder['Budget']))
								{
									$notificationToAdquisitionUser = $this->RequestAction('/notifications/notificationToBudgetOrder/2/'.$this->RequestAction('/external_functions/getAcquisitionUser/').'/'.$last_id.'/');
									
									if($notificationToAdquisitionUser == true)
									{
										$this->PurchaseOrder->commit();
										$this->PurchaseOrderRequest->commit();
										$this->Budget->commit();
										
										$this->Session->setFlash('Se ha generado la nueva orden de compra con exito!', 'flash_success');
										$this->redirect(array('action' => 'index'));
									}
									
									$this->PurchaseOrder->rollback();
									$this->PurchaseOrderRequest->rollback();
									$this->Budget->rollback();
									$this->Session->setFlash('No se pudo generar la notificacion al jefe de adqusiciones y con esto, tampoco se genero la orden. Intentalo nuevamente', 'flash_alert');
								}
								else
								{
									$this->PurchaseOrder->rollback();
									$this->PurchaseOrderRequest->rollback();
									$this->Budget->rollback();
									$this->Session->setFlash('No se pudo guardar las cotizaciones, intentalo nuevamente.', 'flash_alert');
								}
							}
							else
							{
								$this->PurchaseOrder->rollback();
								$this->PurchaseOrderRequest->rollback();
								$this->Session->setFlash('No se pudo guardar los datos de la orden, intentalo nuevamente.', 'flash_alert');
							}
						}
						else
						{
							$this->Session->setFlash('No se pudo guardar la orden, intentalo nuevamente.', 'flash_alert');
							$this->PurchaseOrder->rollback();
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
		
		/*function add($option = null)
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/2/add") == true)
			{
				if($option != null)
				{
					if($option == 'budgeted')
					{
						$this->redirect(array('controller' => 'purchase_orders', 'action' => 'newBudgetedOrder'));
					}
					
					if($option == 'unbudgeted')
					{
						$this->redirect(array('controller' => 'purchase_orders', 'action' => 'newUnbudgetedOrder'));
					}
					else
					{
						$this->Session->setFlash('La opción para el tipo de orden no es valida.', 'flash_alert');
						$this->redirect(array('controller' => 'purchase_orders', 'action' => 'index'));
					}
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}*/
		
		function newBudgetedOrder()
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/2/newBudgetedOrder") == true)
			{
				$data = $this->Auth->user('id');
					
				$requestUser = $this->User->find('first', array('conditions' => array('User.id' => $data)));
				$costCenters = $this->CostCenterUser->find('all', array('conditions' => array('CostCenterUser.system_id' => 2, 'CostCenterUser.user_id' => $data)));
				$badges = $this->Badge->find('list', array('fields' => array('Badge.id', 'Badge.badge')));
				$managementUserData = $this->User->find('first', array('conditions' => array('User.id' => $requestUser['Management']['user_id'])));
				
				
				$providers = $this->Provider->find('all', array('order' => 'Provider.provider_name ASC'));	
				
				$measuring_units = $this->MeasuringUnit->find('list', array('fields'=> array('MeasuringUnit.id', 'MeasuringUnit.unit'), 'order' => 'MeasuringUnit.id ASC'));
				
				$selectCostCenter = array();
				
				foreach($costCenters as $costCenter)
				{
					$selectCostCenter[$costCenter['CostCenter']['id']] = $costCenter['CostCenter']['cost_center_name']." (".$costCenter['CostCenter']['cost_center_code'].")";
				}
				
				$this->set('measuring_units', $measuring_units);
				$this->set('providers', $providers);
				$this->set('requestUser', $requestUser);
				$this->set('costCenters', $selectCostCenter);
				$this->set('badges', $badges);
				$this->set('management_user', $managementUserData);
				$this->set('title_for_layout', 'Ordenes de Compra :: Nueva Orden');
				
				if(!empty($this->request->data))
				{
					$dataOrder = array();
					$last_id = '';
					$grandTotal;
					
					//Validacion de cotizaciones
					if(@count($this->request->data['Budget']) == 0)
					{	
						$this->Session->setFlash('Debes cargar por lo menos una cotizacion a la orden de compra');
						return false;
					}
					
					if(@count($this->request->data['Budget']['proposal_budget']) == 0)
					{
						foreach($this->request->data['Budget']['file'] as $value)
						{
							unlink('files/purchase_orders/budgets/'.$value);
						}
						
						$this->Session->setFlash('Debes seleccionar una cotización.', 'flash_alert');
						return false;
					}
					
					if(isset($this->request->data['Budget']))
					{
						if($this->request->data['Budget'] != false)
						{
							$purchaseOrdersBudgets = $this->formatArrayBudgets($this->request->data['Budget']);
						}
						else
						{
							$this->Session->setFlash('Debe haber por lo menos una cotización cargada con su respectivo proveedor', 'flash_alert');
							return false;
						}
					}
					
					//datos de la orden de compra
					$dataOrder['PurchaseOrder']['user_id'] = $this->request->data['PurchaseOrder']['user_id'];
					$dataOrder['PurchaseOrder']['management_id'] = $this->request->data['PurchaseOrder']['management_id'];
					$dataOrder['PurchaseOrder']['management_user_id'] = $this->request->data['PurchaseOrder']['management_user_id'];
					$dataOrder['PurchaseOrder']['dni_user'] = $this->RequestAction('/external_functions/getDniUser/'.$this->request->data['PurchaseOrder']['user_id']);
					$dataOrder['PurchaseOrder']['dni_management_user'] = $this->RequestAction('/external_functions/getDniUser/'. $this->request->data['PurchaseOrder']['management_user_id']);
					$dataOrder['PurchaseOrder']['cost_center_id'] = $this->request->data['PurchaseOrder']['cost_center_id'];
					$dataOrder['PurchaseOrder']['invoice_to'] = $this->request->data['PurchaseOrder']['invoice_to'];
					$dataOrder['PurchaseOrder']['purchase_type'] = $this->request->data['PurchaseOrder']['purchase_type'];
					$dataOrder['PurchaseOrder']['only_provider'] = $this->request->data['PurchaseOrder']['only_provider'];
					$dataOrder['PurchaseOrder']['budgeted'] = $this->request->data['PurchaseOrder']['budgeted'];
					$dataOrder['PurchaseOrder']['reason'] = $this->request->data['PurchaseOrder']['reason'];
					$dataOrder['PurchaseOrder']['authorization_id'] = $this->request->data['PurchaseOrder']['authorization_id'];
					$dataOrder['PurchaseOrder']['badge_id'] = $this->request->data['PurchaseOrder']['badge_id'];
					$dataOrder['PurchaseOrder']['approved'] = 0;
					$dataOrder['PurchaseOrder']['state_id'] = 1;
					$dataOrder['PurchaseOrder']['request_number'] = $this->RequestAction('/correlative_numbers/generateCorrelativeNumber/2/'.date('Y').'/correlative_type/solicitud');
					$dataOrder['PurchaseOrder']['order_number'] = '';
					$dataOrder['PurchaseOrder']['upload_user'] = 1;
					$dataOrder['PurchaseOrder']['comments'] = '';
					
					//Se formatea el arreglo para insercion de datos
					$purchaseOrdersRequests = $this->formatArrayRequests($this->request->data['PurchaseOrderRequest'], $dataOrder['PurchaseOrder']['upload_user']);
					
					//Se agregan los datos del arreglo formateado con los "requests" al arreglo con toda la data.
					foreach($purchaseOrdersRequests as $key => $value)
					{	
						$dataOrder['PurchaseOrderRequest'] = $value;
					}
					
					//Se agregan los datos del arreglo formateado con las cotizaciones al arreglo con toda la data.
					if(isset($this->request->data['Budget']))
					{
						if($this->request->data['Budget'] != false)
						{
							foreach($purchaseOrdersBudgets as $key => $value)
							{	
								$dataOrder['Budget'] = $value;
							}
						}
					}
					
					for($x=0; $x < count($dataOrder['PurchaseOrderRequest']); $x++)
					{
						$totalRequest = $dataOrder['PurchaseOrderRequest'][$x]['net_price'] * $dataOrder['PurchaseOrderRequest'][$x]['quantity'];
						
						$grandTotal = $grandTotal + $totalRequest;
					}
					
					$dataOrder['PurchaseOrder']['grand_net_total_price'] = $grandTotal;
					
					$validateArray = $this->validatePurchaseOrderBudgeted($dataOrder);

					if($validateArray == true)
					{
						$this->PurchaseOrder->begin();
						
						if($this->PurchaseOrder->saveAll($dataOrder['PurchaseOrder']))
						{
							$last_id = $this->PurchaseOrder->getLastInsertID();
							
							for($x=0; $x<count($dataOrder['PurchaseOrderRequest']); $x++)
							{		
								$dataOrder['PurchaseOrderRequest'][$x]['purchase_order_id'] = $last_id;
							}
							
							$this->PurchaseOrderRequest->begin();
							
							if($this->PurchaseOrderRequest->saveAll($dataOrder['PurchaseOrderRequest']))
							{
								for($x=0; $x<count($dataOrder['Budget']); $x++)
								{		
									$dataOrder['Budget'][$x]['purchase_order_id'] = $last_id;
									if($dataOrder['Budget'][$x]['proposal_budget'] == 1)
									{
										$dataOrder['Budget'][$x]['selected'] = 1;
									}
									else
									{
										$dataOrder['Budget'][$x]['selected'] = 0;
									}
								}
								
								$this->Budget->begin();
								
								if($this->Budget->saveAll($dataOrder['Budget']))
								{
									$firstSign = $this->RequestAction('/signs/validateFirstSign/'.$this->Auth->user('id').'/2/'.$last_id);
									
									if($firstSign == true)
									{
										$this->PurchaseOrder->commit();
										$this->PurchaseOrderRequest->commit();
										$this->Budget->commit();
										
										$this->Session->setFlash('Se ha generado la nueva orden de compra con exito!', 'flash_success');
										$this->redirect(array('action' => 'index'));
									}
									
									$this->PurchaseOrder->rollback();
									$this->PurchaseOrderRequest->rollback();
									$this->Budget->rollback();
								}
								else
								{
									$this->PurchaseOrder->rollback();
									$this->PurchaseOrderRequest->rollback();
									$this->Budget->rollback();
									$this->Session->setFlash('No se pudo guardar las cotizaciones, intentalo nuevamente.', 'flash_alert');
								}
							}
							else
							{
								$this->PurchaseOrder->rollback();
								$this->PurchaseOrderRequest->rollback();
								$this->Session->setFlash('No se pudo guardar los datos de la orden, intentalo nuevamente.', 'flash_alert');
							}
						}
						else
						{
							$this->Session->setFlash('No se pudo guardar la orden, intentalo nuevamente.', 'flash_alert');
							$this->PurchaseOrder->rollback();
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
		
		function newUnbudgetedOrder()
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/2/newUnbudgetedOrder") == true)
			{
				$data = $this->Auth->user('id');
					
				$requestUser = $this->User->find('first', array('conditions' => array('User.id' => $data)));
				$costCenters = $this->CostCenterUser->find('all', array('conditions' => array('CostCenterUser.system_id' => 2, 'CostCenterUser.user_id' => $data)));
				$badges = $this->Badge->find('list', array('fields' => array('Badge.id', 'Badge.badge')));
				$managementUserData = $this->User->find('first', array('conditions' => array('User.id' => $requestUser['Management']['user_id'])));
				$providers = $this->Provider->find('all', array('order' => 'Provider.provider_name ASC'));
				
				$measuring_units = $this->MeasuringUnit->find('list', array('fields'=> array('MeasuringUnit.id', 'MeasuringUnit.unit'), 'order' => 'MeasuringUnit.id ASC'));
				
				$selectCostCenter = array();
				
				foreach($costCenters as $costCenter)
				{
					$selectCostCenter[$costCenter['CostCenter']['id']] = $costCenter['CostCenter']['cost_center_name']." (".$costCenter['CostCenter']['cost_center_code'].")";
				}
				
				$this->set('measuring_units', $measuring_units);
				$this->set('providers', $providers);
				$this->set('requestUser', $requestUser);
				$this->set('costCenters', $selectCostCenter);
				$this->set('badges', $badges);
				$this->set('management_user', $managementUserData);
				$this->set('title_for_layout', 'Ordenes de Compra :: Nueva Orden');
				
				if(!empty($this->request->data))
				{
					$dataOrder = array();
					$last_id = '';
					
					//Contar precio total del fondo
					$grandTotal=0;
					
					//datos de la orden de compra
					$dataOrder['PurchaseOrder']['user_id'] = $this->request->data['PurchaseOrder']['user_id'];
					$dataOrder['PurchaseOrder']['management_id'] = $this->request->data['PurchaseOrder']['management_id'];
					$dataOrder['PurchaseOrder']['management_user_id'] = $this->request->data['PurchaseOrder']['management_user_id'];
					$dataOrder['PurchaseOrder']['dni_user'] = $this->RequestAction('/external_functions/getDniUser/'.$this->request->data['PurchaseOrder']['user_id']);
					$dataOrder['PurchaseOrder']['dni_management_user'] = $this->RequestAction('/external_functions/getDniUser/'. $this->request->data['PurchaseOrder']['management_user_id']);
					$dataOrder['PurchaseOrder']['cost_center_id'] = $this->request->data['PurchaseOrder']['cost_center_id'];
					$dataOrder['PurchaseOrder']['invoice_to'] = $this->request->data['PurchaseOrder']['invoice_to'];
					$dataOrder['PurchaseOrder']['purchase_type'] = $this->request->data['PurchaseOrder']['purchase_type'];
					$dataOrder['PurchaseOrder']['only_provider'] = $this->request->data['PurchaseOrder']['only_provider'];
					$dataOrder['PurchaseOrder']['budgeted'] = $this->request->data['PurchaseOrder']['budgeted'];
					$dataOrder['PurchaseOrder']['reason'] = $this->request->data['PurchaseOrder']['reason'];
					$dataOrder['PurchaseOrder']['authorization_id'] = $this->request->data['PurchaseOrder']['authorization_id'];
					$dataOrder['PurchaseOrder']['badge_id'] = $this->request->data['PurchaseOrder']['badge_id'];
					$dataOrder['PurchaseOrder']['grand_net_total_price'] = $grandTotal;
					$dataOrder['PurchaseOrder']['approved'] = 0;
					$dataOrder['PurchaseOrder']['state_id'] = 4;
					$dataOrder['PurchaseOrder']['request_number'] = $this->RequestAction('/correlative_numbers/generateCorrelativeNumber/2/'.date('Y').'/correlative_type/solicitud');
					$dataOrder['PurchaseOrder']['order_number'] = '';
					$dataOrder['PurchaseOrder']['upload_user'] = 0;
					$dataOrder['PurchaseOrder']['comments'] = '';
					
					//Se formatea el arreglo para insercion de datos
					$purchaseOrdersRequests = $this->formatArrayRequests($this->request->data['PurchaseOrderRequest'], $dataOrder['PurchaseOrder']['upload_user']);		
					
					//Se agregan los datos del arreglo formateado con los "requests" al arreglo con toda la data.
					foreach($purchaseOrdersRequests as $key => $value)
					{	
						$dataOrder['PurchaseOrderRequest'] = $value;
					}
					
					$validateArray = $this->validatePurchaseOrderUnbudgeted($dataOrder);
					
					/**/

					if($validateArray == true)
					{
					
						$this->PurchaseOrder->begin();
						
						if($this->PurchaseOrder->saveAll($dataOrder['PurchaseOrder']))
						{
							$last_id = $this->PurchaseOrder->getLastInsertID();
							
							for($x=0; $x<count($dataOrder['PurchaseOrderRequest']); $x++)
							{		
								$dataOrder['PurchaseOrderRequest'][$x]['purchase_order_id'] = $last_id;
							}
							
							
							
							$this->PurchaseOrderRequest->begin();
							
							if($this->PurchaseOrderRequest->saveAll($dataOrder['PurchaseOrderRequest']))
							{
									$notificationToAdquisitionUser = $this->RequestAction('/notifications/notificationToBudgetOrder/2/'.$this->RequestAction('/external_functions/getAcquisitionUser/').'/'.$last_id.'/');
									
									if($notificationToAdquisitionUser == true)
									{
										$this->PurchaseOrder->commit();
										$this->PurchaseOrderRequest->commit();
										
										$this->Session->setFlash('Se ha generado la nueva orden de compra con exito!', 'flash_success');
										$this->redirect(array('action' => 'index'));
									}
									else
									{
										$this->PurchaseOrder->rollback();
										$this->PurchaseOrderRequest->rollback();
										$this->Session->setFlash('No se pudo generar la notificacion al jefe de adqusiciones y con esto, tampoco se genero la orden. Intentalo nuevamente', 'flash_alert');
									}
							}
							else
							{
								$this->PurchaseOrder->rollback();
								$this->PurchaseOrderRequest->rollback();
								$this->Session->setFlash('No se pudo guardar los datos de la orden, intentalo nuevamente.', 'flash_alert');
							}
						}
						else
						{
							$this->Session->setFlash('No se pudo guardar la orden, intentalo nuevamente.', 'flash_alert');
							$this->PurchaseOrder->rollback();
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
		
		function formatArrayRequests($formatArray, $uploadUser)
		{
			$response = array();
			$cont = 0;
				
			
			foreach($formatArray['description'] as $value)
			{	
				$response['PurchaseOrderRequest'][$cont]['purchase_order_id'] = "";
				$response['PurchaseOrderRequest'][$cont]['description'] = $value;
				$cont = $cont +1;
			}
			
			$cont = 0;
			
			foreach($formatArray['measuring_unit_id'] as $value)
			{	
				$response['PurchaseOrderRequest'][$cont]['measuring_unit_id'] = $value;
				
				$cont = $cont +1;
			}
			
			$cont = 0;
			
			foreach($formatArray['quantity'] as $value)
			{	
				$response['PurchaseOrderRequest'][$cont]['quantity'] = $value;
				
				$cont = $cont +1;
			}
			
			$cont = 0;
			
			if($uploadUser == 1)
			{
				foreach($formatArray['net_price'] as $value)
				{	
					$response['PurchaseOrderRequest'][$cont]['net_price'] = $value;
					
					$cont = $cont +1;
				}
			}
			
			return $response;
		}
		
		function validatePurchaseOrderBudgeted($tmp)
		{
			//validacion de PurchaseOrder
			
			if($tmp['PurchaseOrder']['reason'] == '')
			{
				$this->Session->setFlash('Deben dar una razon en el campo "Motivo de la compra"');
				return false;
			}
			 
			
			//Validacion de RenderFundRequest
			for($x=0; $x<count($tmp['PurchaseOrderRequest']); $x++)
			{	
				if($tmp['PurchaseOrderRequest'][$x]['description'] == '')
				{
					$this->Session->setFlash('Deben haber datos en los campo "Descripcion"');
					return false;
				}
				if($tmp['PurchaseOrderRequest'][$x]['quantity'] == '')
				{
					$this->Session->setFlash('Deben haber datos numericos en el campo "Cantidad"');
					return false;
				}
				
				if($tmp['PurchaseOrderRequest'][$x]['net_price'] == '')
				{
					$this->Session->setFlash('Deben haber datos numericos en el campo "Precio neto"');
					return false;
				}
			}
			return true;
		}
		
		function validatePurchaseOrderUnbudgeted($tmp)
		{
			//validacion de PurchaseOrder
			
			if($tmp['PurchaseOrder']['reason'] == '')
			{
				$this->Session->setFlash('Deben dar una razon en el campo "Motivo de la compra"');
				return false;
			}
			 
			
			//Validacion de RenderFundRequest
			for($x=0; $x<count($tmp['PurchaseOrderRequest']); $x++)
			{	
				if($tmp['PurchaseOrderRequest'][$x]['description'] == '')
				{
					$this->Session->setFlash('Deben haber datos en los campo "Descripcion"');
					return false;
				}
				if($tmp['PurchaseOrderRequest'][$x]['quantity'] == '')
				{
					$this->Session->setFlash('Deben haber datos numericos en el campo "Cantidad"');
					return false;
				}
			}
			return true;
		}
		
		
		function formatArrayBudgets($formatArray)
		{
			$response = array();
			$cont = 0;
				
			foreach($formatArray['provider_id'] as $value)
			{	
				$response['Budget'][$cont]['purchase_order_id'] = "";
				$response['Budget'][$cont]['provider_id'] = $value;
				
				$provider = $this->Provider->find('first', array('conditions' => array('Provider.id' => $value), 'fields' => array('Provider.id', 'Provider.provider_name')));
				
				$response['Budget'][$cont]['provider_name'] = $provider['Provider']['provider_name'];
				
				$ok = 0;
				
				if(isset($formatArray['proposal_budget']))
				{
					if($formatArray['proposal_budget'] != false)
					{
						foreach($formatArray['proposal_budget'] as $key => $value2)
						{
							if($value2 == $value)
							{
								$response['Budget'][$cont]['proposal_budget'] = 1;
								$ok = 1;
							}
							else
							{
								$response['Budget'][$cont]['proposal_budget'] = 0;
							}
						}
					}
				}
				

				$cont = $cont +1;
			}
			
			$cont = 0;
			
			foreach($formatArray['file'] as $value)
			{	
				$response['Budget'][$cont]['file'] = 'files/purchase_orders/budgets/'.$value;
				
				$cont = $cont +1;
			}
			
			return $response;
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
		
		function mainMenu() 
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/2/mainMenu") == true)
			{
				$this->set('title_for_layout', 'Ordenes de Compra :: Menú Principal');
				$this->set('userLogged', $this->Auth->user('id'));
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function info(){
		
		}
		
		function requestAddProvider()
		{
			$this->set('applicant_id', $this->Auth->user('id'));
			
			if(!empty($this->request->data))
			{
				$this->request->data['ProviderRequest']['enable'] = 0;
				
				$this->ProviderRequest->begin();
				
				if($this->ProviderRequest->save($this->request->data))
				{
					$this->ProviderRequest->commit();
					$this->Session->setFlash('Se envió la solicitud correctamente.', 'flash_success');
					$this->redirect(array('action' => 'ok'));
				}
				else
				{
					$this->Session->setFlash('Hubo un error al enviar la solicitud, intentalo nuevamente.', 'flash_alert');
					$this->ProviderRequest->rollback();
				}
			}
		}
		
		function ok()
		{
		
		}
		
		function deleteBudget($file = null, $budgetId = null)
		{
			if($file != null)
			{
				$urlFile =  'files/purchase_orders/budgets/'.$file;
				
				if($budgetId != null)
				{
					$dataBudget = $this->Budget->find('first', array('conditions' => array('Budget.id' => $budgetId)));
					
					if($dataBudget != false)
					{
						$this->Budget->begin();
						
						if($this->Budget->delete($dataBudget['Budget']))
						{
							if(unlink($urlFile))
							{
								$this->Budget->commit();
								return true;
							}
							else
							{
								$this->Budget->rollback();
								return false;
							}
						}
						else
						{
							$this->Budget->rollback();
							return false;
						}
					}
				}
				else
				{
					if(unlink($urlFile))
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
		
		function budgetOrder($id = null)
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/2/budgetOrder") == true)
			{
				if($id != null)
				{
					$this->PurchaseOrder->id = $id;
					$measuring_units = $this->MeasuringUnit->find('list', array('fields'=> array('MeasuringUnit.id', 'MeasuringUnit.unit'), 'order' => 'MeasuringUnit.id ASC'));
					$this->set('measuring_units', $measuring_units);
					
					if(empty($this->request->data))
					{
						$this->request->data = $this->PurchaseOrder->read();
						$this->set('providers', $this->Provider->find('all', array('order' => 'Provider.provider_name ASC')));
					}
					else
					{	
						$purchaseOrdersBudgets = array();
						
							//Validacion de cotizaciones
						if(@count($this->request->data['Budget']) == 0)
						{
							$this->Session->setFlash('Debes cargar por lo menos una cotizacion a la orden de compra');
							$this->redirect(array('controller' => 'purchase_orders', 'action' => 'budgetOrder/'.$id));
						}
					
						if(@count($this->request->data['Budget']['proposal_budget']) == 0)
						{
							foreach($this->request->data['Budget']['file'] as $value)
							{
								unlink('files/purchase_orders/budgets/'.$value);
							}
							
							$this->Session->setFlash('Debes seleccionar una cotización.', 'flash_alert');
							$this->redirect(array('controller' => 'purchase_orders', 'action' => 'budgetOrder/'.$id));
						}
						
						if(isset($this->request->data['Budget']))
						{
							if($this->request->data['Budget']['provider_id'] != false && $this->request->data['Budget']['file'] != false)
							{
								$purchaseOrdersBudgets = $this->formatArrayBudgets($this->request->data['Budget']);
							}
							else
							{
								$this->Session->setFlash('Debe haber por lo menos una cotización cargada con su respectivo proveedor', 'flash_alert');
								$this->redirect(array('controller' => 'purchase_orders', 'action' => 'budgetOrder/'.$id));
							}
						}
						
						$cont = 0;
						
						for($x=0; $x < count($purchaseOrdersBudgets['Budget']); $x++)
						{
							$purchaseOrdersBudgets['Budget'][$x]['purchase_order_id'] = $id;
							$purchaseOrdersBudgets['Budget'][$x]['selected'] = 0;
							$cont++;
						}
						
						$grandTotal = 0;
						
						for($x=0; $x < count($this->request->data['PurchaseOrderRequest']); $x++)
						{
							$totalRequest = $this->request->data['PurchaseOrderRequest'][$x]['net_price'] * $this->request->data['PurchaseOrderRequest'][$x]['quantity'];
							
							$grandTotal = $grandTotal + $totalRequest;
						}
						
						
						$this->Budget->begin();
						
						if($this->Budget->saveAll($purchaseOrdersBudgets['Budget']))
						{
							$dataOrder = $this->PurchaseOrder->find('first', array('conditions' => array('PurchaseOrder.id' => $id)));
							$dataOrder['PurchaseOrder']['upload_user'] = 1;
							$dataOrder['PurchaseOrder']['grand_net_total_price'] = $grandTotal;
							$dataOrder['PurchaseOrder']['state_id'] = 6;
							
							
							$this->PurchaseOrder->begin();
							
							if($this->PurchaseOrder->save($dataOrder['PurchaseOrder']))
							{
								$this->PurchaseOrderRequest->begin();
								
								if($this->PurchaseOrderRequest->saveAll($this->request->data['PurchaseOrderRequest']))
								{
									$notificationToRequestUser = $this->RequestAction('/notifications/notificationToPurchaseOrderRequest/2/'.$dataOrder['User']['id'].'/'.$id);
									
									if($notificationToRequestUser == true)
									{
										$this->Budget->commit();
										$this->PurchaseOrder->commit();
										$this->PurchaseOrderRequest->commit();
										
										if($cont == 1)
										{
											$this->Session->setFlash('Cotizacion correctamente agregada a la orden. ', 'flash_success');
										}
										else
										{
											$this->Session->setFlash($cont.' cotizaciones correctamente agregadas a la orden. ', 'flash_success');
										}
										
										$this->redirect(array('controller' => 'purchase_orders', 'action' => 'index'));
									}
									else
									{
										$this->Budget->rollback();
										$this->PurchaseOrder->rollback();
										$this->PurchaseOrderRequest->commit();
										$this->Session->setFlash('Hubo un error al notificar al usuario, intentelo nuevamente', 'flash_error');
										$this->redirect(array('controller' => 'purchase_orders', 'action' => 'budgetOrder/'.$id));
									}
								}
								else
								{
									$this->Budget->rollback();
									$this->PurchaseOrder->rollback();
									$this->PurchaseOrderRequest->rollback();
									$this->Session->setFlash('Hubo un error al ingresar los detalles de la orden, intentelo nuevamente', 'flash_error');
									$this->redirect(array('controller' => 'purchase_orders', 'action' => 'budgetOrder/'.$id));
								}
							}
							else
							{
								$this->Budget->rollback();
								$this->PurchaseOrder->rollback();
								$this->Session->setFlash('Hubo un error al actualizar la orden, intentelo nuevamente', 'flash_error');
								$this->redirect(array('controller' => 'purchase_orders', 'action' => 'budgetOrder/'.$id));
							}
						}
						else
						{
							$this->Budget->rollback();
							$this->Session->setFlash('Hubo un error al guardar las cotizaciones, intentelo nuevamente', 'flash_alert');
							$this->redirect(array('controller' => 'purchase_orders/budgetOrder/'.$id, 'action' => 'index'));
						}
					}
				}
				else
				{
					$this->Session->setFlash('El id de la orden no puede ser nulo.', 'flash_alert');
					$this->redirect(array('controller' => 'purchase_orders', 'action' => 'index'));
				}
			}
		}
		
		function validateBudget($budgetId = null)
		{
			$table='';
				
			$js = "<script>
					
					function getDataToSign()
					{
						for(i=0; i < document.BudgetEditForm.selected.length; i++)
						{
							if(document.BudgetEditForm.selected[i].checked)
							{
								var comments = document.getElementById('comments_budget').value;
								var selection = document.BudgetEditForm.selected[i].value;
								var budget_id = document.getElementById('id_budget').value;
								var raiz = '/michilevision';
								
								if(selection == 0)
								{
									if(comments == '')
									{
										alert('El comentario no puede ir vacio');
									}
									else
									{
										document.location.href = raiz + '/purchase_orders/evaluating/'+budget_id+'/'+selection+'/'+comments;
									}
								}
								else
								{
									document.location.href = raiz + '/purchase_orders/evaluating/'+budget_id+'/'+selection;
								}
							}
						}
					}
					
					function showComments()
					{
						$('#comment').show('fast');
						$('#question').show('fast');
					}
					function hideComments()
					{
						$('#comment').hide('fast');
						$('#question').hide('fast');
					}
					</script>";
			
			$table .= $js;
			$table .= '<form id="BudgetEditForm" name="BudgetEditForm" accept-charset="utf-8">
			<table>
				<tr>
					<td style="border:none;"><input name="selected" type="radio" value="1" id="state_id" checked="checked" onClick="Javascript:hideComments();"/>
					<label for="accion_aprueba">Acepto la cotización</label>
					<input name="selected" type="radio" value="0" id="state_id" onClick="Javascript:showComments();" />
					<label for="accion_rechaza">Rechazo la cotización</label>
					<input name="id_budget" type="hidden" value="'.$budgetId.'" id="id_budget" /><br />
					<div id="question" style="display:none;">¿Porque?</div>
					<div id="comment" style="display:none;"><textarea name="comments" style="width:200px;height:80px;" id="comments_budget"></textarea></div>
					<button type="button" style="padding:5px;float:left;" class="a_button" onclick="javascript:getDataToSign();">Enviar</button>
					<button type="button" style="padding:5px;float:left;" class="a_button" onclick="hide_tipTip();">Cancelar</button></td>
				</tr>
				</table>
			</form>';
			
			return $table;
		}
		
		function evaluating($budgetId = null, $selection = null, $comments = null)
		{
			if($budgetId != null && $selection != null)
			{
				$dataBudget  = $this->Budget->find('first', array('conditions' => array('Budget.id' => $budgetId)));
				
				if($selection == 1)
				{
					$dataBudget['Budget']['selected'] = $selected;
					
					$dataOrder = $this->PurchaseOrder->find('first', array('conditions' => array('PurchaseOrder.id' => $dataBudget['Budget']['purchase_order_id'])));
					$dataOrder['PurchaseOrder']['state_id'] = 1;
					
					$this->Budget->begin();
					$this->PurchaseOrder->begin();
					
					if($this->Budget->save($dataBudget))
					{
						if($this->PurchaseOrder->save($dataOrder))
						{
							$firstSign = $this->RequestAction('/signs/validateFirstSign/'.$this->Auth->user('id').'/2/'.$dataBudget['Budget']['purchase_order_id']);
							
							if($firstSign == true)
							{
								$this->Budget->commit();
								$this->PurchaseOrder->commit();
								$this->Session->setFlash('Aceptaste la cotización propuesta. Comienza el proceso de firmas.', 'flash_success');
								$this->redirect(array('action' => 'index'));
							}
							else
							{
								$this->Budget->rollback();
								$this->PurchaseOrder->rollback();
								$this->Session->setFlash('Hubo un error en el proceso de firmas, intentalo nuevamente.', 'flash_alert');
								$this->redirect(array('action' => 'index'));
							}
						}
						else
						{
							$this->Budget->rollback();
							$this->PurchaseOrder->rollback();
							$this->Session->setFlash('Hubo un error en la inserción de los datos de la orden, intentalo nuevamente', 'flash_alert');
							$this->redirect(array('action' => 'index'));
						}
					}
					else
					{
						$this->Budget->rollback();
						$this->PurchaseOrder->rollback();
						$this->Session->setFlash('Hubo un error en la inserción de los datos de la cotización, intentalo nuevamente', 'flash_alert');
						$this->redirect(array('action' => 'index'));
					}
				}
				
				if($selection == 0)
				{
					$dataPurchase = $this->PurchaseOrder->find('first', array('conditions' => array('PurchaseOrder.id' => $dataBudget['Budget']['purchase_order_id'])));
					
					$dataPurchase['PurchaseOrder']['state_id'] = 3;
					$dataPurchase['PurchaseOrder']['comments'] = $comments;
					
					$this->PurchaseOrder->begin();
					
					if($this->PurchaseOrder->save($dataPurchase))
					{
						$notification = $this->RequestAction('/notifications/notificationDeclinedBudgetedOrder/2/'.$this->RequestAction('/external_functions/getAcquisitionUser').'/'.$dataPurchase['PurchaseOrder']['id']);
						
						if($notification == true)
						{
							$this->PurchaseOrder->commit();
							$this->Session->setFlash('Se envio tu rechazo de cotización.', 'flash_success');
							$this->redirect(array('action' => 'index'));
						}
						else
						{
							$this->PurchaseOrder->rollback();
							$this->Session->setFlash('Hubo un error en el proceso de notificación, intentalo nuevamente.', 'flash_alert');
							$this->redirect(array('action' => 'index'));
						}
					}
					else
					{
						$this->PurchaseOrder->rollback();
						$this->Session->setFlash('Hubo un error en la inserción de los datos de la orden, intentalo nuevamente', 'flash_alert');
						$this->redirect(array('action' => 'index'));
					}
				}
			}
		}
		
		function requestModifyForm($orderId = null)
		{
			$table = '';
			
			if($orderId != null)
			{
				
					
					$js = "<script>
						
							function getDataToSign()
							{
								var comments = document.getElementById('request_message').value;
								var purchase_order_id = document.getElementById('purchase_order_id').value;
								var raiz = '/michilevision';
								

								if(comments == '')
								{
									alert('El comentario no puede ir vacio');
									return false;
								}
								else
								{
									document.location.href = raiz + '/purchase_orders/modifiedBudgetRequest/'+purchase_order_id+'/'+comments;
								}
							}
							
							</script>";
					
					$table .= $js;
					$table .= '<form id="ModifiedRequestOrderEditForm" name="ModifiedRequestOrderEditForm" accept-charset="utf-8">
					<table>
						<tr>
							<td style="border:none;">
							<input name="purchase_order_id" type="hidden" value="'.$orderId.'" id="purchase_order_id" /><br />
							<div id="comment">¿Porque necesitas modificar la orden?<br><textarea name="request_message" style="width:200px;height:80px;" id="request_message"></textarea></div>
							<button type="button" style="padding:5px;float:left;" class="a_button" onclick="javascript:getDataToSign();">Enviar</button>
							<button type="button" style="padding:5px;float:left;" class="a_button" onclick="hide_tipTip();">Cancelar</button>
							</td>
						</tr>
						</table>
					</form>';
					
					return $table;
			}
			else
			{
				$table = "<table>
								<tr>
									<td>El id de la orden no puede estar nulo.</td>
								</tr>
							</table>";
				
				return $table;
			}
		}
		
		function modifiedBudgetRequest($id = null, $request_message = null)
		{
			if($id != null && $request_message != null)
			{
				$dataOrder = $this->PurchaseOrder->find('first', array('conditions' => array('PurchaseOrder.id' => $id)));
				
				if($dataOrder != false)
				{
					if($dataOrder['PurchaseOrder']['approved'] == 1)
					{
						$dataRequest = $this->ModifiedRequestOrder->find('first', array('conditions' => array('ModifiedRequestOrder.hash_keypass' => $dataOrder['PurchaseOrder']['hash_keypass'])));
						
						$request = array();
						
						//Se verifica que no exista otra solicitud de modificacion.
						if($dataRequest != false)
						{
							$dataRequest['ModifiedRequestOrder']['block'] = 1;
							
							$responseBlock = $this->blockLastRequest($dataRequest);
							
							if($responseBlock == true)
							{
								$newRequest;
								
								$allRequests = $this->ModifiedRequestOrder->find('all', array('order' => 'ModifiedRequestOrder.id DESC', 'fields' => array('ModifiedRequestOrder.id')));
								
								$newId = $allRequests[0]['ModifiedRequestOrder']['id'] + 1;
								
								$newRequest['ModifiedRequestOrder']['id'] = $newId;
								$newRequest['ModifiedRequestOrder']['purchase_order_id'] = $id;
								$newRequest['ModifiedRequestOrder']['user_id'] = $this->Auth->user('id');
								$newRequest['ModifiedRequestOrder']['can_modify'] = 0;
								
								// Generate a random string 100 chars in length.
								$token = "";
								for ($i = 0; $i < 100; $i++) 
								{
									$d = rand(1, 100000) % 2;
									$d ? $token .= chr(rand(33,79)) : $token .= chr(rand(80,126));
								}
						
								(rand(1, 100000) % 2) ? $token = strrev($token) : $token = $token;
						
								// Generate hash of random string
								$hash = Security::hash($token, 'sha256', true);
								
								for ($i = 0; $i < 20; $i++) 
								{
									$hash = Security::hash($hash, 'sha256', true);
								}

								$newRequest['ModifiedRequestOrder']['hash_keypass'] = $hash;
								$newRequest['ModifiedRequestOrder']['request_message'] = $request_message;
								$newRequest['ModifiedRequestOrder']['response_message'] = '';
								$newRequest['ModifiedRequestOrder']['block'] = 0;
								
								$dataOrder['PurchaseOrder']['hash_keypass'] = $hash;
								
								$this->PurchaseOrder->begin();
								$this->ModifiedRequestOrder->begin();
								
								if($this->PurchaseOrder->save($dataOrder['PurchaseOrder']))
								{
									if($this->ModifiedRequestOrder->save($newRequest['ModifiedRequestOrder']))
									{
										if($this->RequestAction('/notifications/notificationToAdministrationManagerForAuthorizeModifyOrder/2/'.$this->RequestAction('/external_functions/getAdministrationManagementId').'/'.$newId))
										{
											$this->PurchaseOrder->commit();
											$this->ModifiedRequestOrder->commit();
											$this->Session->setFlash('Se envio la solicitud de modificación correctamente. Tus notificaciones te avisaran cuando este aprobada o rechazada tu solicitud.', 'flash_success');
											$this->redirect(array('action' => 'index'));
										}
										else
										{
											$this->PurchaseOrder->rollback();
											$this->ModifiedRequestOrder->rollback();
											$this->Session->setFlash('Error al generar la notificación, intentalo nuevamente. si existe '.$last, 'flash_alert');
											$this->redirect(array('action' => 'index'));
										}
									}
									else
									{
										$this->PurchaseOrder->rollback();
										$this->ModifiedRequestOrder->rollback();
										$this->Session->setFlash('Error al guardar la solicitud, intentalo nuevamente.', 'flash_alert');
										$this->redirect(array('action' => 'index'));
									}
								}
								else
								{
									$this->PurchaseOrder->rollback();
									$this->ModifiedRequestOrder->rollback();
									$this->Session->setFlash('Error al guardar la orden, intetalo nuevamente.', 'flash_alert');
									$this->redirect(array('action' => 'index'));
								}
							}
							else
							{
								$this->ModifiedRequestOrder->rollback();
								$this->Session->setFlash('Error al anular la solicitud antigua, intentalo nuevamente.', 'flash_alert');
								$this->redirect(array('action' => 'index'));
							}
						}
						else
						{
							$request['ModifiedRequestOrder']['user_id'] = $this->Auth->user('id');
							$request['ModifiedRequestOrder']['purchase_order_id'] = $id;
							$request['ModifiedRequestOrder']['can_modify'] = 0;
							$request['ModifiedRequestOrder']['request_message'] = $request_message;
							$dataRequest['ModifiedRequestOrder']['block'] = 0;
							
							// Generate a random string 100 chars in length.
							$token = "";
							for ($i = 0; $i < 100; $i++) 
							{
								$d = rand(1, 100000) % 2;
								$d ? $token .= chr(rand(33,79)) : $token .= chr(rand(80,126));
							}
					
							(rand(1, 100000) % 2) ? $token = strrev($token) : $token = $token;
					
							// Generate hash of random string
							$hash = Security::hash($token, 'sha256', true);
							
							for ($i = 0; $i < 20; $i++) 
							{
								$hash = Security::hash($hash, 'sha256', true);
							}

							$request['ModifiedRequestOrder']['hash_keypass'] = $hash;
							$dataOrder['PurchaseOrder']['hash_keypass'] = $hash;
							
							$this->PurchaseOrder->begin();
							
							if($this->PurchaseOrder->save($dataOrder['PurchaseOrder']))
							{
								$this->ModifiedRequestOrder->begin();
								
								if($this->ModifiedRequestOrder->save($request['ModifiedRequestOrder']))
								{
									$last_id = $this->ModifiedRequestOrder->getLastInsertId();
									
									if($this->RequestAction('/notifications/notificationToAdministrationManagerForAuthorizeModifyOrder/2/'.$this->RequestAction('/external_functions/getAdministrationManagementId').'/'.$last_id))
									{
										$this->PurchaseOrder->commit();
										$this->ModifiedRequestOrder->commit();
										$this->Session->setFlash('Se envio la solicitud de modificación correctamente. Tus notificaciones te avisaran cuando este aprobada tu solicitud.', 'flash_success');
										$this->redirect(array('action' => 'index'));
									}
									else
									{
										$this->PurchaseOrder->rollback();
										$this->ModifiedRequestOrder->rollback();
										$this->Session->setFlash('Error al generar la notificación, intentalo nuevamente.', 'flash_alert');
										$this->redirect(array('action' => 'index'));
									}
								}
								else
								{
									$this->PurchaseOrder->rollback();
									$this->ModifiedRequestOrder->rollback();
									$this->Session->setFlash('Error al guardar la solicitud, intentalo nuevamente. no existe', 'flash_alert');
									$this->redirect(array('action' => 'index'));
								}
							}
							else
							{
								$this->PurchaseOrder->rollback();
								$this->Session->setFlash('Error al guardar la orden, intetalo nuevamente.', 'flash_alert');
								$this->redirect(array('action' => 'index'));
							}
						}
					}
					else
					{
						$this->Session->setFlash('La orden debe estar aprobada totalmente para ser modificada.', 'flash_alert');
						$this->redirect(array('action' => 'index'));
					}
				}
				else
				{
					$this->Session->setFlash('La orden de compra no existe', 'flash_error');
					$this->redirect(array('action' => 'index'));
				}
			}
		}
		
		function blockLastRequest($request)
		{
			$this->ModifiedRequestOrder->begin();
			
			if($this->ModifiedRequestOrder->save($request['ModifiedRequestOrder']))
			{
				$this->ModifiedRequestOrder->commit();
				return true;
			}
			else
			{
				$this->ModifiedRequestOrder->rollback();
				return false;
			}
		}
		
		function generatingBill($orderId = null)
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/2/generatingBill") == true)
			{
				if($orderId != null)
				{
					if(empty($this->request->data))
					{
						$dataOrder = $this->PurchaseOrder->find('first', array('conditions' => array('PurchaseOrder.id' => $orderId)));
						
						if($dataOrder['PurchaseOrder']['purchase_type'] == 'Nacional')
						{
							$dataTaxes = $this->Tax->find('all');
							
							$taxes = array();
							
							foreach($dataTaxes as $tax)
							{
								$taxes[$tax['Tax']['id']] = $tax['Tax']['tax_name'].' ('.$tax['Tax']['value'].'%)';
							}
							
							$this->set('taxes', $taxes);
						}
						
						$this->request->data = $dataOrder;
					}
					else
					{
						//Importado
						if($this->request->data['ApprovedOrder']['tax_id'] == 0)
						{
							if($this->request->data['ApprovedOrder']['import_tax_name'] == '')
							{
								$this->Session->setFlash('Debes selecionar un impuesto de importación para la orden de compra', 'flash_alert');
								$this->redirect(array('action' => '/generatingBill/'.$orderId));
							}
							
							if($this->request->data['ApprovedOrder']['import_tax_value'] == '')
							{
								$this->Session->setFlash('El valor del impuesto de importación no puede estar vacío', 'flash_alert');
								$this->redirect(array('action' => '/generatingBill/'.$orderId));
							}
							
							if(is_numeric($this->request->data['ApprovedOrder']['import_tax_value']) == false)
							{
								$this->Session->setFlash('El valor del impuesto debe ser un número', 'flash_alert');
								$this->redirect(array('action' => '/generatingBill/'.$orderId));
							}
						}
						
						$dataOrder = $this->PurchaseOrder->find('first', array('conditions' => array('PurchaseOrder.id' => $orderId), 'fields' => array('PurchaseOrder.order_number')));
						
						if(file_exists('files/purchase_orders/orders/'.$dataOrder['PurchaseOrder']['order_number'].'.pdf'))
						{
							unlink('files/purchase_orders/orders/'.$dataOrder['PurchaseOrder']['order_number'].'.pdf');
						}
						
						$lastApprovedOrders = $this->ApprovedOrder->find('all', array('conditions' => array('ApprovedOrder.purchase_order_id' => $this->request->data['ApprovedOrder']['purchase_order_id'])));
						
						if($lastApprovedOrders != false)
						{
							$run = 'Ok';
						}
						else
						{
							$run = 'Forget';
						}
						
						$this->request->data['ApprovedOrder']['active'] = 1;
						
						$this->ApprovedOrder->begin();
						
						if($this->ApprovedOrder->save($this->request->data['ApprovedOrder']))
						{
							$last_id = $this->ApprovedOrder->getLastInsertId();
							
							if($run == 'Forget')
							{
									$this->ApprovedOrder->commit();
									$this->Session->setFlash('Se asignó el impuesto correctamente', 'flash_success');
									$this->redirect(array('action' => '/view/'.$orderId));
							}
							
							if($run == 'Ok')
							{
								if($this->blockLastApprovedOrders($lastApprovedOrders) == true)
								{

										$this->ApprovedOrder->commit();
										$this->Session->setFlash('Se asignó el impuesto correctamente', 'flash_success');
										$this->redirect(array('action' => '/view/'.$orderId));
								}
								else
								{
									$this->ApprovedOrder->rollback();
									$this->Session->setFlash('Hubo un error en la generación de la orden de compra', 'flash_error');
									$this->redirect(array('action' => '/generatingBill/'.$orderId));
								}
							}
							else
							{
								$this->ApprovedOrder->rollback();
								$this->Session->setFlash('Hubo un error en el bloqueo de las ordenes antiguas, intentalo nuevamente', 'flash_error');
								$this->redirect(array('action' => '/generatingBill/'.$orderId));
							}
						}
						else
						{
							$this->ApprovedOrder->rollback();
							$this->Session->setFlash('Hubo un error en la inserción de los datos, intentalo nuevamente', 'flash_error');
							$this->redirect(array('action' => '/generatingBill/'.$orderId));
						}
					}
					
				}
				else
				{
					$this->Session->setFlash('El id de la orden no puede ser nulo.', 'flash_alert');
					$this->redirect(array('controller' => 'purchase_orders', 'action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function blockLastApprovedOrders($lastApprovedOrders)
		{
			for($x=0; $x < count($lastApprovedOrders); $x++)
			{
				$lastApprovedOrders[$x]['ApprovedOrder']['active'] = 0;
			}
			
			$this->ApprovedOrder->begin();
			
			if($this->ApprovedOrder->saveAll($lastApprovedOrders))
			{
				$this->ApprovedOrder->commit();
				return true;
			}
			else
			{
				$this->ApprovedOrder->rollback();
				return false;
			}
		}
		
		function generateOrderPdf($approvedId)
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/2/generateOrderPdf") == true)
			{
				// Sobrescribimos para que no aparezcan los resultados de debuggin
				// ya que sino daria un error al generar el pdf.
				Configure::write('debug',0);
				
				$dataBill = $this->ApprovedOrder->find('first', array('conditions' => array('ApprovedOrder.id' => $approvedId)));
				$data = $this->PurchaseOrder->find('first', array('conditions' => array('PurchaseOrder.id' => $dataBill['PurchaseOrder']['id'])));
				
				for($x=0; $x < count($data['Budget']); $x++)
				{
					if($data['Budget'][$x]['selected'] == 1)
					{
						$providerInfo = $this->Provider->find('first', array('conditions' => array('Provider.id' => $data['Budget'][$x]['provider_id'])));
						$data['Budget'][$x]['ProviderSelected'] = $providerInfo['Provider'];
					}
				}
				
				if($dataBill['ApprovedOrder']['tax_id'] != 0)
				{
					$dataTax = $this->Tax->find('first', array('conditions' => array('Tax.id' => $dataBill['ApprovedOrder']['tax_id'])));
					
					$data['Tax'] = $dataTax['Tax'];
				}
				
				$data['ApprovedOrder'] = $dataBill['ApprovedOrder'];
				$data['Details'] = $this->Auth->user();
				
				
				$adminUser = $this->User->find('first', array('conditions' => array('User.id' => $this->RequestAction('/external_functions/getAdministrationManagementId')), 'fields' => array('User.name', 'User.first_lastname')));
				$data['AdministrationManager'] = $adminUser['User'];
				
				$adquisitonUser = $this->User->find('first', array('conditions' => array('User.id' => $this->RequestAction('/external_functions/getAcquisitionUser')), 'fields' => array('User.name', 'User.first_lastname')));
				$data['AdquisitorUser'] = $adquisitonUser['User'];

				$this->set('data', $data);
				$this->layout = 'pdf';
				
				/*echo "<pre>";
				print_r($data);
				echo "</pre>";*/
				
				/*$this->Session->setFlash('Se genero la orden de compra correctamente.', 'flash_success');
				$this->redirect(array('action' => 'index'));*/
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function sendOrderToProvider($orderId)
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/2/sendOrderToProvider") == true)
			{
				if($orderId != null)
				{
					$this->PurchaseOrder->id = $orderId;
					$dataOrder = $this->PurchaseOrder->read();
					
					$providerId;
					
					foreach($dataOrder['Budget'] as $budget)
					{
						if($budget['selected'] == 1)
						{
							$providerId = $budget['provider_id'];
						}
					}
					
					$dataProvider = $this->Provider->find('first', array('conditions' => array('Provider.id' => $providerId)));
					
					$this->Email->sendAs = 'both';
					$this->Email->layout = 'default';
					$this->Email->template = 'purchase_order';
					$this->Email->delivery = 'smtp';
					
					$root = "/michilevision/app/webroot/files/purchase_orders/orders/";
					$this->Email->filePaths  = array('c:/wamp/www'.$root);
					$this->Email->attachments = array($dataOrder['PurchaseOrder']['order_number'].'.pdf');
					
					$this->Email->smtpOptions = array('host' => 'argsmtpgw.turner.com');
					
					/*if($dataProvider['Provider']['provider_email'] != '' || $dataProvider['Provider']['provider_email'] != null)
					{
						$this->Email->to =$dataProvider['Provider']['provider_name']." <".$dataProvider['Provider']['provider_email'].">";
						$this->Email->from = "Ordenes de Compra - Chilevisión <noreply@chilevision.cl>";
						$this->Email->subject = 'Orden de compra N°'.$dataOrder['PurchaseOrder']['order_number'].' para '.$dataProvider['Provider']['provider_name'].' - CHV';
						
						$body = "Señores de ".$dataProvider['Provider']['provider_name'].".<br>  Le informamos que su cotización fue aprobada por nuestro canal, por lo cual le adjuntamos la orden de compra N°".$dataOrder['PurchaseOrder']['order_number'].".<br> Sin otro inconveniente.<br> Muchas gracias.";
						
						$this->set('body', $body);
					 
						if($this->Email->send())
						{
							$adquisitionMail = "jvenegas@chilevision.cl";
							$chvUsers = array('jsantibanez@chilevision.cl', 'tgonzalez@chilevision.cl', 'fdotoro@chilevision.cl', 'cgallardo@chilevision.cl');
							
							if($this->sendCopyToChvUsers($dataProvider, $adquisitionMail, $chvUsers, $dataOrder['PurchaseOrder']['order_number'], $dataOrder['PurchaseOrder']['request_number']) == true)
							{
								$this->Session->setFlash('Se envio la orden de compra al proveedor correctamente.', 'flash_success');
								$this->redirect(array('action' => 'index'));
							}
							else
							{
								$this->Session->setFlash('Se envió la orden de compra al jefe de adquisiciones pero no a los miembros asignados de Chilevisión.', 'flash_alert');
								$this->redirect(array('action' => 'index'));
							}
						}
						else
						{
							$this->Session->setFlash('Hubo un error en el envio de la orden de compra, intente nuevamente.', 'flash_alert');
							$this->redirect(array('action' => 'index'));
						}
					}
					else
					{
						$idAdquisitor = $this->RequestAction('/external_functions/getAcquisitionUser/');
						$dataAdquisitor = $this->User->find('first', array('conditions' => array('User.id' => $idAdquisitor), 'fields' => array('User.name', 'User.first_lastname', 'User.email')));
						
						$this->Email->to =$dataAdquisitor['User']['name']." ".$dataAdquisitor['User']['first_lastname']."<".$dataAdquisitor['User']['email'].">";
						$this->Email->from = "Ordenes de Compra - Chilevisión <noreply@chilevision.cl>";
						$this->Email->subject = 'Orden de compra N°'.$dataOrder['PurchaseOrder']['order_number'].' para '.$dataProvider['Provider']['provider_name'].' - CHV';
						
						$body = "Estimado ".$dataAdquisitor['User']['name'].".<br> Se adjunta la orden de compra N°".$dataOrder['PurchaseOrder']['order_number']." para el envio a el proveedor correspondiente.<br> Sin otro inconveniente.<br> Muchas gracias.";
						
						$this->set('body', $body);
					 
						if($this->Email->send())
						{
							$adquisitionMail = 'jsantibanez@chilevision.cl';
							$chvUsers = array('tgonzalez@chilevision.cl', 'fdotoro@chilevision.cl', 'cgallardo@chilevision.cl');
							
							if($this->sendCopyToChvUsers($dataProvider, $adquisitionMail, $chvUsers, $dataOrder['PurchaseOrder']['order_number'], $dataOrder['PurchaseOrder']['request_number']) == true)
							{
								$this->Session->setFlash('Se envio la orden de compra al proveedor correctamente.', 'flash_success');
								$this->redirect(array('action' => 'index'));
							}
							else
							{
								$this->Session->setFlash('Se envió la orden de compra al jefe de adquisiciones pero no a los miembros asignados de Chilevisión.', 'flash_alert');
								$this->redirect(array('action' => 'index'));
							}
						}
						else
						{
							$this->Session->setFlash('Hubo un error en el envio de la orden de compra, intente nuevamente.', 'flash_alert');
							$this->redirect(array('action' => 'index'));
						}
					}*/
					
					/************************TEST************************/
					$this->Email->to ='hugo.inda@chilevision.cl';
					$this->Email->from = "Ordenes de Compra - Chilevisión <noreply@chilevision.cl>";
					$this->Email->subject = 'Orden de compra N°'.$dataOrder['PurchaseOrder']['order_number'].' para '.$dataProvider['Provider']['provider_name'].' - CHV';
					
					$body = "Estimado.\n Le informamos que su cotización fue aprobada por nuestro canal, por lo cual le adjuntamos la orden de compra.\n Sin otro inconveniente.\n Muchas gracias";
					$this->set('body', $body);
					
					if($this->Email->send())
					{
						$adquisitionMail = 'oscar.contreras@chilevision.cl';
						$chvUsers = array('luis.gajardo@chilevision.cl', 'benjamin.ortuzar@chilevision.cl');
							
						if($this->sendCopyToChvUsers($dataProvider, $adquisitionMail, $chvUsers, $dataOrder['PurchaseOrder']['order_number'], $dataOrder['PurchaseOrder']['request_number']) == true)
						{
							$this->Session->setFlash('Se envio la orden de compra al proveedor correctamente.', 'flash_success');
							$this->redirect(array('action' => 'index'));
						}
						else
						{
							$this->Session->setFlash('Se envió la orden de compra al jefe de adquisiciones pero no a los miembros asignados de Chilevisión.', 'flash_alert');
							$this->redirect(array('action' => 'index'));
						}
					}
					else
					{
						$this->Session->setFlash('Hubo un error en el envio de la orden de compra, intente nuevamente.', 'flash_alert');
						$this->redirect(array('action' => 'index'));
					}
					/******************************************************/
				}
				else
				{
					$this->Session->setFlash('El Id de la orden no puede estar nula.', 'flash_alert');
					$this->redirect(array('action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function sendCopyToChvUsers($dataProvider = null, $toMail = null, $chvUsers = null, $orderNumber = null, $requestNumber = null)
		{
			if($dataProvider != null && $toMail != null && $chvUsers != null && $orderNumber != null && $requestNumber != null)
			{
				$this->Email->sendAs = 'both';
				$this->Email->layout = 'default';
				$this->Email->template = 'purchase_order';
				$this->Email->delivery = 'smtp';
				
				$this->Email->smtpOptions = array('host' => 'argsmtpgw.turner.com');
				
				$root = "/michilevision/app/webroot/files/purchase_orders/orders/";
				$this->Email->filePaths  = array('c:/wamp/www'.$root);
				$this->Email->attachments = array($orderNumber.'.pdf');
				
				$this->Email->to = $toMail;
				$this->Email->cc = $chvUsers;
				$this->Email->from = "Ordenes de Compra - Chilevisión <noreply@chilevision.cl>";
				$this->Email->subject = 'Orden de compra N°'.$orderNumber.' - CHV';
				
				/*if($dataProvider['Provider']['provider_email'] != '' || $dataProvider['Provider']['provider_email'] != null)
				{
					$providerMail = $dataProvider['Provider']['provider_mail'];
				}
				else
				{*/
					$providerMail = "hugo.inda@chilevision.cl";
				//}
				
				$body = "Se adjunta la Orden de Compra N°".$orderNumber." generada a partir de la Solicitud N°".$requestNumber.". Esta se ha enviado vía email al Proveedor ".$dataProvider['Provider']['provider_name']." (".$providerMail.")<br>";
				
				$this->set('body', $body);
				
				if($this->Email->send())
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		
		function associateBill($orderId = null)
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/2/associateBill") == true)
			{
				if($orderId != null)
				{
					$providerId;
					
					$this->PurchaseOrder->id = $orderId;
					$this->request->data = $this->PurchaseOrder->read();
					
					foreach($this->request->data['Budget'] as $budget)
					{
						if($budget['selected'] == 1)
						{
							$providerId = $budget['provider_id'];
						}
					}
					
					$dataProvider = $this->Provider->find('first', array('conditions' => array('Provider.id' => $providerId)));
					
					//$dataBills = $this->Cwmovim->find('all', array('conditions' => array('Cwmovim.CodAux' => $dataProvider['Provider']['auxiliar_provider_code'], 'Cwmovim.cpbnum <>' => '00000000'), 'fields' => array('Cwmovim.CpbNum', 'Cwmovim.MovNum', 'Cwmovim.NumDoc', 'Cwmovim.MovGlosa')));
					//$dataBills = $this->Cwmovim->query("select TOP 10 m.*, c.* from softland.cwmovim m, softland.cwcpbte c where m.CpbAno=c.CpbAno and m.CpbNum=c.CpbNum and c.CpbEst='V' AND c.CpbNum<> '00000000' AND m.CodAux='".$dataProvider['Provider']['auxiliar_provider_code']."' AND m.TtdCod in('FC', 'FL', 'FX', 'XL', 'FV', 'IN', 'FE', 'BH', 'BL')");
					
					$dataBills = $this->Cwmovim->query("select top 10
																							'#' + CONVERT(varchar, CAST(mov.numdoc AS int), 0) + --factura numero
																							' - $' + REPLACE(CONVERT(varchar, CAST(mov.movhaber AS money), 1),'.00','') + --total
																							' - ' + nomaux --razonsocial
																							as result
																							, REPLACE(CONVERT(varchar, CAST(mov.movhaber AS money), 1),'.00','') total
																							, CONVERT(varchar, CONVERT(int, det.dlicoint))+'-'+CONVERT(varchar, CONVERT(int, mov.cpbmes)) as corr	
																							, mov.numdoc, mov.MovFe, mon.DesMon, mon.SimMon, mov.MovHaber, mov.CpbAno, mov.TtdCod, aux.NomAux, aux.RutAux, mov.CodAux from softland.cwmovim mov
																							inner join softland.cwtauxi aux on mov.codaux = aux.codaux
																							inner join softland.CWDETLI det on det.cpbano = mov.cpbano and det.movnum = mov.movnum and det.CpbNum = mov.CpbNum
																							inner join softland.cwtmone mon on mon.codmon = mov.moncod
																							where mov.movhaber > 0 and
																							mov.cpbano >= DATEPART(YEAR, DATEADD(MONTH, -3, GETDATE())) and
																							mov.cpbmes >= DATEPART(MONTH, DATEADD(MONTH, -3, GETDATE())) and
																							mov.ttdcod in ('FC', 'FL', 'FX', 'XL', 'FV', 'IN', 'FE', 'BH', 'BL') and
																							mov.codaux like '".$dataProvider['Provider']['auxiliar_provider_code']."' +'%'
																							order by movfe desc;");
					
					
					echo "<pre>";
					print_r($dataBills);
					echo "</pre>";
					
				}
				else
				{
					$this->Session->setFlash('El Id de la orden no puede estar nula.', 'flash_alert');
					$this->redirect(array('action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function edit($orderId = null)
		{
			if($orderId != null)
			{
				$dataOrder = $this->PurchaseOrder->find('first', array('conditions' => array('PurchaseOrder.id' => $orderId)));
				
				/*$validate = 0; 
				
				if($dataOrder['ModifiedRequestOrder'] != false)
				{
					foreach($dataOrder['ModifiedRequestOrder'] as $request)
					{
						if($request['user_id'] == $this->RequestAction('/external_functions/getIdDataSession') && $request['can_modify'] == 1 && $request['block'] == 0 && $request['hash_keypass'] == $dataOrder['PurchaseOrder']['hash_keypass'])
						{
							$validate = 1;
						}
					}
				}
				
				if($validate == 1)
				{*/
					
					/*****/
					//DATA PARA EL FORM
					
					$requestUser = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id'))));
					
					$badges = $this->Badge->find('list', array('fields' => array('Badge.id', 'Badge.badge')));
					$managementUserData = $this->User->find('first', array('conditions' => array('User.id' => $requestUser['Management']['user_id'])));
					
					
					$providers = $this->Provider->find('all', array('order' => 'Provider.provider_name ASC'));	
					
					$measuring_units = $this->MeasuringUnit->find('list', array('fields'=> array('MeasuringUnit.id', 'MeasuringUnit.unit'), 'order' => 'MeasuringUnit.id ASC'));
					
					
					
					
					$this->set('measuring_units', $measuring_units);
					$this->set('providers', $providers);
					$this->set('requestUser', $requestUser);
					$this->set('badges', $badges);
					//$this->set('management_user', $managementUserData);
					/*****/
					
					if(empty($this->request->data))
					{
						$this->PurchaseOrder->id = $orderId;
						$this->request->data = $this->PurchaseOrder->read();
						
						$selectCostCenter = array();
					
						$selectCostCenter[$this->request->data['CostCenter']['id']] = $this->request->data['CostCenter']['cost_center_name']." (".$this->request->data['CostCenter']['cost_center_code'].")";
						
						$this->set('costCenters', $selectCostCenter);
						
						if($this->request->data['Budget'] != false)
						{
							for($x=0; $x < count($this->request->data['Budget']); $x++)
							{
								$dataProvider = $this->Provider->find('first', array('conditions' => array('Provider.id' => $this->request->data['Budget'][$x]['provider_id'])));
								
								$this->request->data['Budget'][$x]['Provider'] = $dataProvider['Provider'];
								
							}
						}
						
						if($this->request->data['PurchaseOrderRequest'] != false)
						{
							for($x=0; $x < count($this->request->data['PurchaseOrderRequest']); $x++)
							{
								$dataUnit = $this->MeasuringUnit->find('first', array('conditions' => array('MeasuringUnit.id' => $this->request->data['PurchaseOrderRequest'][$x]['measuring_unit_id'])));
								
								$this->request->data['PurchaseOrderRequest'][$x]['MeasuringUnit'] = $dataUnit['MeasuringUnit'];
								
							}
						}
					}
					else
					{
						if($this->request->data['PurchaseOrderRequest']  != false)
						{
							for($x=0; $x < count($this->request->data['PurchaseOrderRequest']); $x++)
							{
								if($this->request->data['PurchaseOrderRequest'][$x]['description'] == '')
								{
									$this->Session->setFlash('La descripción no puede estar vacia.', 'flash_alert');
									$this->redirect(array('action' => 'edit/'.$orderId));
								}
								else
								{
									if($this->request->data['PurchaseOrderRequest'][$x]['measuring_unit_id'] == '')
									{
										$this->Session->setFlash('Debe seleccionar una unidad de medida.', 'flash_alert');
										$this->redirect(array('action' => 'edit/'.$orderId));
									}
									else
									{
										if($this->request->data['PurchaseOrderRequest'][$x]['quantity'] == '')
										{
											$this->Session->setFlash('La cantidad no puede estar vacia.', 'flash_alert');
											$this->redirect(array('action' => 'edit/'.$orderId));
										}
										else
										{
											if($this->request->data['PurchaseOrderRequest'][$x]['net_price'] == '')
											{
												$this->Session->setFlash('El valor no puede estar vacio.', 'flash_alert');
												$this->redirect(array('action' => 'edit/'.$orderId));
											}
											else
											{
												$this->request->data['PurchaseOrderRequest'][$x]['purchase_order_id']  = $this->request->data['PurchaseOrder']['id'];
											}
										}
									}
								}
							}
						}
						else
						{
							$this->Session->setFlash('Debe existir al menos un detalle por orden.', 'flash_alert');
							$this->redirect(array('action' => 'edit/'.$orderId));
						}
						
						if(isset($this->request->data['Budget']))
						{
							if($this->request->data['Budget'] != false)
							{
								$budgets = $this->formatArrayBudgets($this->request->data['Budget']);
								unset($this->request->data['Budget']);
								
								$this->request->data['Budget'] = $budgets['Budget'];
								
							}
							else
							{
								$this->Session->setFlash('Debe haber por lo menos una cotización cargada con su respectivo proveedor', 'flash_alert');
								return false;
							}
						}
						
						for($x=0; $x < count($this->request->data['Budget']); $x++)
						{
							$this->request->data['Budget'][$x]['purchase_order_id'] = $this->request->data['PurchaseOrder']['id'];
						}
						
						$this->Budget->begin();
						
						if($this->Budget->saveAll($this->request->data['Budget']))
						{
							$this->PurchaseOrderRequest->begin();
							
							if($this->PurchaseOrderRequest->saveAll($this->request->data['PurchaseOrderRequest']))
							{
								$this->PurchaseOrderRequest->commit();
								$this->Budget->commit();
								
								$this->Session->setFlash('Orden de compra editada correctamente.', 'flash_success');
								$this->redirect(array('action' => 'index'));
							}
							else
							{
								$this->PurchaseOrderRequest->rollback();
								$this->Budget->rollback();
								
								$this->Session->setFlash('Hubo un error en la actualización de la orden, intentalo nuevamente.', 'flash_error');
								$this->redirect(array('action' => 'edit/'.$orderId));
							}
						}
						else
						{
							$this->Budget->rollback();
							$this->Session->setFlash('Hubo un error en la actualización de la orden, intentalo nuevamente.', 'flash_error');
							$this->redirect(array('action' => 'edit/'.$orderId));
						}
					}
				/*}
				else
				{
					$this->Session->setFlash('No tienes autorización para editar esta orden.', 'flash_error');
					$this->redirect(array('action' => 'index'));
				}*/
			}
			else
			{
				$this->Session->setFlash('El Id de la orden no puede estar nula.', 'flash_alert');
				$this->redirect(array('action' => 'index'));
			}
		}
		
		function cancelOrdenForm($orderId = null)
		{
			$table = '';
			
			if($orderId != null)
			{
				
					
					$js = "<script>
						
							function getDataToSign()
							{
								var comments = document.getElementById('request_message').value;
								var purchase_order_id = document.getElementById('id').value;
								var raiz = '/michilevision';
								

								if(comments == '')
								{
									alert('El comentario no puede ir vacio');
									return false;
								}
								else
								{
									document.location.href = raiz + '/purchase_orders/cancelOrder/'+purchase_order_id+'/'+comments;
								}
							}
							
							</script>";
					
					$table .= $js;
					$table .= '<form id="PurchaseOrderEditForm" name="PurchaseOrderEditForm" accept-charset="utf-8">
					<table>
						<tr>
							<td style="border:none;">
							<input name="id" type="hidden" value="'.$orderId.'" id="id" /><br />
							<div id="comment">¿Porque quieres anular la orden?<br><textarea name="request_message" style="width:200px;height:80px;" id="request_message"></textarea></div>
							<button type="button" style="padding:5px;float:left;" class="a_button" onclick="javascript:getDataToSign();">Enviar</button>
							<button type="button" style="padding:5px;float:left;" class="a_button" onclick="hide_tipTip();">Cancelar</button>
							</td>
						</tr>
						</table>
					</form>';
					
					return $table;
			}
			else
			{
				$table = "<table>
								<tr>
									<td>El id de la orden no puede estar nulo.</td>
								</tr>
							</table>";
				
				return $table;
			}
		}
		
		function cancelOrder($orderId = null, $comment = null)
		{
			if($orderId != null && $comment != null)
			{
				$dataOrder = $this->PurchaseOrder->find('first', array('conditions' => array('PurchaseOrder.id' => $orderId)));
				$dataOrder['PurchaseOrder']['comments'] = $comment;
				$dataOrder['PurchaseOrder']['state_id'] = 7;
				
				$this->PurchaseOrder->begin();
				
				if($this->PurchaseOrder->save($dataOrder['PurchaseOrder']))
				{
					$this->PurchaseOrder->commit();
					$this->Session->setFlash('Orden anulada correctamente.', 'flash_success');
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					$this->PurchaseOrder->rollback();
					$this->Session->setFlash('Hubo un error en la anulación de la orden, intentalo nuevamente.', 'flash_alert');
					$this->redirect(array('action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash('El Id de la orden no puede estar nula.', 'flash_alert');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
?>