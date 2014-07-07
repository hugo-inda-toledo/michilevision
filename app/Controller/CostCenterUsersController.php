<?php
class CostCenterUsersController  extends AppController 
{
    var $name = 'CostCenterUsers';
	var $uses = array('CostCenterUser', 'User', 'CostCenter', 'UserSystem', 'System');
	var $helpers = array('Session','Html','Form','Time');
	var $paginate = array();
	
	
	function index() 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->paginate = array('CostCenterUser' => array('limit' => 15, 'order' => array('User.name ASC', 'System.system_name ASC')));
			$dataCostCenterUsers = $this->paginate();
			
			$this->set('cost_center_users', $dataCostCenterUsers);
			$this->set('title_for_layout', 'Asignaciones de centros de costo');
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
    }
	
	
	function view($id = null) 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->CostCenterUser->id = $id;
			
			$dataAsignacion = $this->CostCenterUser->read();
			
			
			if($dataAsignacion['CostCenter']['valid'] == 1)
				$dataAsignacion['CostCenter']['valid'] = 'Si';
			else
				$dataAsignacion['CostCenter']['valid'] = 'No';
				
			
			$this->set('asignacion', $dataAsignacion);
			$this->set('title_for_layout', $dataAsignacion['CostCenter']['cost_center_name'].' asignado a '.$dataAsignacion['User']['name'].' '.$dataAsignacion['User']['first_lastname']);
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
    }
	
	
	function add() 
	{
		if($this->Auth->user('admin') == 1)
		{
			/* Arreglo con usuarios para select */
			$dataUsuarios = $this->User->find('all', array('conditions' => array('User.active' => 1),'order' => 'User.first_lastname ASC'));
			
			
			foreach ($dataUsuarios as $value){
			$resultadosUsuarios[$value['User']['id']]= $value['User']['first_lastname'].' '.$value['User']['second_lastname'].', '.$value['User']['name'];
			}
			
			$this->set('resultUsuarios', $resultadosUsuarios);
			$this->set('title_for_layout', 'Nueva asignación');
			
			 if (!empty($this->request->data)) 
			{
				$dataAsignaciones = $this->request->data;
				$dataAsignaciones['CostCenterUser']['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');
				$dataMultiSelect = $this->request->data['CostCenterUser']['cost_center_id'];
				$tmp2 = array();
				$cont = 0;
				
				foreach($dataMultiSelect as $dataSelect)
				{
					$tmp['cost_center_id'] = $dataSelect;
					$tmp['user_id'] = $this->request->data['CostCenterUser']['user_id'];
					$tmp['system_id'] = $this->request->data['CostCenterUser']['system_id'];
					$tmp['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');
					
					array_push($tmp2, $tmp);
					$cont = $cont +1;
				}
				
				$this->request->data['CostCenterUser'] = $tmp2;
				$dataAsignaciones = $this->request->data['CostCenterUser'];
				
				$this->CostCenterUser->begin();
				
				if ($this->CostCenterUser->saveAll($dataAsignaciones)) 
				{
					$lastIds = $this->CostCenterUser->inserted_ids;
					
					$x = 0;
					
					foreach($lastIds as $last)
					{
						$assign = $this->CostCenterUser->find('first', array('conditions' => array('CostCenterUser.id' =>$last)));
						$notificationToAssigned = $this->RequestAction('/notifications/costCenterAssignNotification/'.$assign['CostCenterUser']['user_id'].'/'.$assign['CostCenterUser']['system_id'].'/'.$assign['CostCenterUser']['cost_center_id']);
						
						if($notificationToAssigned == true)
						{
							$dataUser = $this->User->find('first', array('conditions' => array('User.id' => $assign['CostCenterUser']['user_id'])));
							
							if($dataUser['Management']['user_id'] != $assign['CostCenterUser']['user_id'])
							{
								$notificationToManagement = $this->RequestAction('/notifications/costCenterAssignNotificationToManagement/'.$dataUser['Management']['user_id'].'/'.$assign['CostCenterUser']['system_id'].'/'.$assign['CostCenterUser']['cost_center_id'].'/'.$assign['CostCenterUser']['user_id']);
							}
							else
							{
								$notificationToManagement = true;
							}
						
							if($notificationToManagement == true)
							{
								$x++;
								
								if($x == $cont)
								{
									$this->CostCenterUser->commit();
					
									if($cont == 1)
										$this->Session->setFlash('Se ha creado correctamente una nueva asignación.', 'flash_success');
									else
										$this->Session->setFlash('Se han creado correctamente '.$cont.' asignaciones.', 'flash_success');
										
									$this->redirect(array('action' => 'index'));
								}
							}
						}
					}
				}
				
				$this->CostCenterUser->rollback();
			}
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
    }
	
	function delete($id) 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->CostCenterUser->begin();
			
			if ($this->CostCenterUser->delete($id))
			{
				$this->CostCenterUser->commit();
				
				$this->Session->setFlash('Se ha eliminado correctamente la asignación seleccionada.', 'flash_success');
				$this->redirect(array('action'=>'index'));
			}
			
			$this->CostCenterUser->rollback();
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	function arrayAll($id = null)
	{
		if($id != null)
		{
			echo "<br><br><br><br><br><br><br><br><br><br><pre>";
			print_r($this->CostCenterUser->find('all', array('conditions' => array('CostCenterUser.cost_center_id' => $id))));
			echo "</pre>";
		}
		else
		{
			echo "<br><br><br><br><br><br><br><br><br><br><pre>";
			print_r($this->CostCenterUser->find('all'));
			echo "</pre>";
		}
	}
}
?>