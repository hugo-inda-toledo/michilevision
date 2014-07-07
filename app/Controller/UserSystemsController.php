<?php
class UserSystemsController extends AppController 
{
	//Declaracion de variables con llamada a helpers, clases externas
    var $name = 'UserSystems';
	var $helpers = array('Session', 'Html', 'Form','Time');
    var $uses = array('UserSystem', 'System','User');
	var $scaffold;
	var $paginate = array();

	function index() 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->paginate = array('UserSystem' => array('limit' => 10, 'order' => array('User.name ASC', 'System.system_name ASC')));
			$dataUsuarioSistemas = $this->paginate();
			
			$this->set('usuarioSistemas', $dataUsuarioSistemas);
			$this->set('title_for_layout', 'Asignaciones de sistemas a usuarios');
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
			//Se obtiene el id del usuario
			$this->UserSystem->id = $id;
			
			//Se lee la info del usuario de la DB
			$dataUsuarioSistemas = $this->UserSystem->read();	

			
			//Se devuelve arreglos a la vista
			$this->set('usuarioSistema',   $dataUsuarioSistemas);
			$this->set('title_for_layout', 'Datos de la asignación');
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
			$dataSistemas = $this->RequestAction('/external_functions/showSistemasForSelect');
			$dataUsuarios = $this->RequestAction('/external_functions/showUsuarios');
			
			$this->set('sistemas', $dataSistemas);
			$this->set('usuarios', $dataUsuarios);
			$this->set('title_for_layout', 'Nueva asignación');
			
			if (!empty($this->request->data)) 
			{
				//Se encapsula esa info. en un arreglo.
				$dataAsignacion = $this->request->data;
				$dataAsignacion['UserSystem']['created_by_id'] = $this->Auth->user('id');
				
				$this->UserSystem->begin();
				
				if ($this->UserSystem->save($dataAsignacion)) 
				{
					$lastAssign= $this->UserSystem->find('first', array('conditions' => array('UserSystem.id' => $this->UserSystem->getLastInsertId())));
					
					$notificationToAssignedUser = $this->RequestAction('/notifications/systemAssignNotification/'.$lastAssign['UserSystem']['user_id'].'/'.$lastAssign['UserSystem']['system_id']);
					
					if($notificationToAssignedUser == true)
					{
						$dataUserToGetManagement = $this->User->find('first', array('conditions' => array('User.id' => $lastAssign['UserSystem']['user_id'])));
						
						if($dataUserToGetManagement['Management']['user_id'] != $dataUserToGetManagement['User']['id'])
						{
							$notificationToManagement = $this->RequestAction('/notifications/systemAssignNotificationToManagement/'.$dataUserToGetManagement['Management']['user_id'].'/'.$lastAssign['UserSystem']['system_id'].'/'.$dataUserToGetManagement['User']['id']);
						}
						else
						{
							$notificationToManagement = true;
						}
						
						if($notificationToManagement == true)
						{
							$this->UserSystem->commit();
					
							$this->Session->setFlash('Se ha creado correctamente una nueva asignación.', 'flash_success');
							$this->redirect(array('action' => 'index'));
						}
						
						$this->UserSystem->rollback();
					}
					
					$this->UserSystem->rollback();
				}
				
				$this->UserSystem->rollback();
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
			//Obtengo el id a consulltar
			$this->UserSystem->id = $id;
			$dataSistemas = $this->RequestAction('/external_functions/showSistemasForSelect');
			$dataUsuarios = $this->RequestAction('/external_functions/showUsuarios');
			$this->set('title_for_layout', 'Editando la asignación');

			if (empty($this->request->data)) 
			{
				//Obtengo la info. a partir del id en cuestion
				$this->request->data = $this->UserSystem->read();
				$dataUsuarioSistema = $this->request->data;
				
				//Envio la información del usuario a consultar y para los selects
				$this->set('usuarioSistema', $dataUsuarioSistema);
				$this->set('sistemas', $dataSistemas);
				$this->set('usuarios', $dataUsuarios);
			} 
			else 
			{
				//Se encapsula esa info. en un arreglo.
				$dataAsignacion = $this->request->data;
				
				$this->UserSystem->begin();
				
				//Si la info. se guardo
				if ($this->UserSystem->save($dataAsignacion))
				{
					$this->UserSystem->commit();
					
					$this->Session->setFlash('Se ha editado correctamente una asignación existente.', 'flash_success');
					$this->redirect(array('action' => 'index'));
				}
				
				$this->UserSystem->rollback();
			}
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
			$this->UserSystem->begin();
			
			if ($this->UserSystem->delete($id))
			{
				$this->UserSystem->commit();
				
				$this->Session->setFlash('Se ha eliminado correctamente la asignación seleccionada.', 'flash_success');
				$this->redirect(array('action'=>'index'));
			}
			
			$this->UserSystem->rollback();
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
}
?>