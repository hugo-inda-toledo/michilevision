<?php
class HeadquartersController extends AppController {

	var $name = 'Headquarters';
	var $uses = array('Headquarter','Management','User');
	var $helpers = array('Session','Html','Form','Time');
	var $components = array('Session');
	var $scaffold;
	var $paginate = array();
	
	
	
	function index()
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->paginate = array('Headquarter' => array('limit' => 10, 'order' => array('Headquarter.id' => 'ASC')));
			$dataJefaturas = $this->paginate();
			$this->set('jefaturas', $dataJefaturas);
			$this->set('title_for_layout', 'Jefaturas');
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
			$this->Headquarter->id = $id;
			$dataJefatura = $this->Headquarter->read();

			if($dataJefatura['Headquarter']['active'] == 1)
				$dataJefatura['Headquarter']['active'] = 'Si';
			else
				$dataJefatura['Headquarter']['active'] = 'No';
			
			$this->set('jefatura', $dataJefatura);
			$this->set('title_for_layout', $dataJefatura['Headquarter']['headquarter_name']);
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
			$dataUsuarios = $this->User->find('all', array('conditions' => array('User.active' => 1),'order' => 'User.first_lastname ASC'));
			$dataGerencias = $this->Management->find('all', array('order' => 'Management.management_name ASC'));
			
			foreach ($dataUsuarios as $value){
				$resultados1[$value['User']['id']]= $value['User']['first_lastname'].' '.$value['User']['second_lastname'].', '.$value['User']['name'];
			}
			
			foreach ($dataGerencias as $value){
				$resultados2[$value['Management']['id']] = $value['Management']['management_name'];
			}
			
			$this->set('selectUsuarios', $resultados1);
			$this->set('selectGerencias', $resultados2);
			$this->set('title_for_layout', 'Nueva Jefatura');
			
			
			if (!empty($this->request->data)) 
			{
				$dataJefatura = $this->request->data;
				
				$dataJefatura['Headquarter']['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');
				
				$this->Headquarter->begin();
				
				if ($this->Headquarter->save($dataJefatura)) 
				{
					$lastHeadquarter = $this->Headquarter->find('first', array('conditions' => array('Headquarter.id' => $this->Headquarter->getLastInsertId())));
					
					$notificationToBoss = $this->RequestAction('/notifications/headquarterAssignNotification/'.$lastHeadquarter['Headquarter']['user_id'].'/'.$lastHeadquarter['Headquarter']['id']);
					
					if($notificationToBoss == true)
					{
						$managementData = $this->Management->find('first', array('conditions' => array('Management.id' => $lastHeadquarter['Headquarter']['management_id'])));
						
						$notificationToManager = $this->RequestAction('/notifications/headquarterAssignNotificationToManagement/'.$managementData['Management']['user_id'].'/'.$lastHeadquarter['Headquarter']['id']);
						
						if($notificationToManager == true)
						{
							$this->Headquarter->commit();
							$this->Session->setFlash('Se ha creado correctamente una nueva jefatura.', 'flash_success');
							$this->redirect(array('action' => 'index'));
						}
						
						$this->Headquarter->rollback();
					}
					
					$this->Headquarter->rollback();
				}
				
				$this->Headquarter->rollback();
			}
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}


	function edit($id = null)
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->Headquarter->id = $id;
			$dataHeadquarter = $this->data;
			
			$dataUsuarios = $this->RequestAction('/external_functions/showUsuarios/');
			$dataGerencias = $this->Management->find('all', array('order' => 'Management.management_name ASC'));
			
				foreach ($dataGerencias as $value)
				{
					$resultadosGerencias[$value['Management']['id']] = $value['Management']['management_name'];
				}
			
				if(empty($this->data))
				{
					$this->data = $this->Headquarter->read();
					$this->set('title_for_layout', 'Editando '.$this->data['Headquarter']['headquarter_name']);
				}
			
				else 
				{
					$this->Headquarter->begin();
					
					if ($this->Headquarter->save($dataHeadquarter)) 
					{
						$this->Headquarter->commit();
						
						$this->Session->setFlash('Se ha editado correctamente la jefatura seleccionada.', 'flash_success');
						$this->redirect(array('action' => 'index'));
					}
					
					$this->Headquarter->rollback();
				}

			$this->set('selectUsuarios', $dataUsuarios);
			$this->set('selectGerencias', $resultadosGerencias);
			$this->set('jefatura', $dataHeadquarter);
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	
	
	function delete($id = null) 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->Headquarter->begin();
			
			if ($this->Headquarter->delete($id))
			{
				$this->Headquarter->commit();
				
				$this->Session->setFlash('Se ha eliminado correctamente la jefatura seleccionada.', 'flash_success');
				$this->redirect(array('action'=>'index'));
			}
			
			$this->Headquarter->rollback();
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	function find()
	{
		if($this->Auth->user('admin') == 1)
		{
			if(!empty($this->data))
			{	
				$this->paginate = array('Headquarter' => array('limit' => 1000, 'conditions' => array($this->data['Headquarter']['headquarter_type_search'].' LIKE' => '%'.$this->data['Headquarter']['headquarter_param'].'%')));
				$headquarterSearch = $this->paginate();
				
				$this->set('jefaturas', $headquarterSearch);
				$this->set('dataSearch', $this->data);
			}
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	
}
?>