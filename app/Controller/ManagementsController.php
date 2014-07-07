<?php
class ManagementsController extends AppController 
{
    	
    var $name = 'Managements';
	var $uses = array('Management','User','Authorization','Replacement');
	var $paginate = array();
	
	
	
	function index() 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->paginate = array('Management' => array('limit' => 10, 'order' => array('Management.id' => 'ASC')));
			$dataGerencias = $this->paginate();
			
			$this->set('gerencias', $dataGerencias);
			$this->set('title_for_layout', 'Gerencias');
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
			$this->Management->id = $id;
			$dataGerencia = $this->Management->read();
			
			$this->set('gerencia', $dataGerencia);
			$this->set('title_for_layout', $dataGerencia['Management']['management_name']);
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
			$dataAuthorizations = $this->Authorization->find('all', array('conditions' => array('Authorization.active' => 1)));

				foreach ($dataUsuarios as $value){
				$resultadosUsuarios[$value['User']['id']]= $value['User']['first_lastname'].' '.$value['User']['second_lastname'].', '.$value['User']['name'];
				}
			
				foreach ($dataAuthorizations as $value){
				$resultadosAuth[$value['Authorization']['id']]= $value['Authorization']['name'];
				}
			
			$this->set('selectUsuariosGerente', $resultadosUsuarios);
			$this->set('selectAutorizacion', $resultadosAuth);
			$this->set('title_for_layout', 'Nueva Gerencia');
			
			
			if (!empty($this->request->data)) 
			{
				$dataGerencia = $this->request->data;
				
				$dataGerencia['Management']['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');
				
				$this->Management->begin();
				
				if ($this->Management->save($dataGerencia)) 
				{
					$lastManagement = $this->Management->getLastInsertId();
					
					$dataLastInsert = $this->Management->find('first', array('conditions' => array('Management.id' => $lastManagement), 'fields' => array('Management.id', 'Management.user_id')));
					
					//Notificación al gerente de la nueva gerencia
					$notificationToUser = $this->RequestAction('/notifications/managementAssignNotification/'.$dataLastInsert['Management']['user_id'].'/'.$dataLastInsert['Management']['id']);
					
					if($notificationToUser == true)
					{
						$this->Management->commit();
						$this->Session->setFlash('Se ha creado correctamente una nueva gerencia.', 'flash_success');
						$this->redirect(array('action' => 'index'));
					}
					
					$this->Management->rollback();
				}
				
				$this->Management->rollback();
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
			$this->Management->id = $id;
			$dataManagement = $this->data;
			
			$dataUsuarios = $this->RequestAction('/external_functions/showUsuarios/');
			$dataAutorizaciones = $this->Authorization->find('all');
			
				foreach ($dataAutorizaciones as $value){
					$resultadosAuth[$value['Authorization']['id']]= $value['Authorization']['name'];
				}
			
			
				if (empty($this->data)){
					$this->data = $this->Management->read();
					$this->set('title_for_layout', 'Editando '.$this->data['Management']['management_name']);
				}
						
				else
				{
					$this->Management->begin();
					
					if ($this->Management->save($dataManagement)) 
					{
						$this->Management->commit();
						
						$this->Session->setFlash('Se ha editado correctamente la gerencia seleccionada.', 'flash_success');
						$this->redirect(array('action' => 'index'));
					}
					
					$this->Management->rollback();
				}
			
			$this->set('selectUsuariosGerente', $dataUsuarios);
			$this->set('selectAutorizacion', $resultadosAuth);
			$this->set('gerencia', $dataManagement);
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
			$this->Management->begin();
			
			if ($this->Management->delete($id))
			{
				$this->Management->commit();
				$this->Session->setFlash('Se ha eliminado correctamente la gerencia seleccionada.', 'flash_success');
				$this->redirect(array('action'=>'index'));
			}
			
			$this->Management->rollback();
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
				$this->paginate = array('Management' => array('limit' => 1000, 'order' => array($this->data['Management']['management_type_search'] => 'ASC'), 'conditions' => array($this->data['Management']['management_type_search'].' LIKE' => '%'.$this->data['Management']['management_param'].'%')));
				$managementSearch = $this->paginate();
				
				$this->set('gerencias', $managementSearch);
				$this->set('dataSearch', $this->data);
			}
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	function search() 
	{
        $this->set('results',$this->Management->search($this->request->data['Management']['q']));
		//$this->set('results', $this->Management->search($this->request->data['Management']['q'], array('recursive' => 2)));  
    } 
	
}
?>