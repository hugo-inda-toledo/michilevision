<?php
class SystemsController extends AppController 
{
	//Declaracion de variables con llamada a helpers, clases externas
    var $name = 'Systems';
	var $helpers = array('Session', 'Html', 'Form','Time');
    var $uses = array('System','User','UserSystem', 'Permission', 'Profile', 'Notification', 'ProfilePermission');
	var $scaffold;
	var $paginate = array();

	function index() 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->paginate = array('System' => array('limit' => 20, 'order' => array('System.id')));
			$dataSistemas = $this->paginate();
			$this->set('sistemas', $dataSistemas);
			$this->set('title_for_layout', 'Sistemas');
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
			$this->System->id = $id;
			
			//Se lee la info del usuario de la DB
			$dataSistema = $this->System->read();
			
			$data = array();
			
			$x=0;
			
			foreach($dataSistema['Profile'] as $profile)
			{
				$data[$x]['Profile'] = $profile;
				
				$profilePermissions = $this->ProfilePermission->find('all', array('conditions' => array('ProfilePermission.profile_id' => $profile['id'])));
				
				$y=0;
				foreach($profilePermissions as $profilePermission)
				{
					$data[$x]['Permission'][$y] = $profilePermission['Permission'];
					$y++;
				}
				
				$x++;
			}

			
			//Se devuelve arreglos a la vista
			$this->set('sistema',   $dataSistema);
			$this->set('data',   $data);
			$this->set('title_for_layout', 'Sistema '.$dataSistema['System']['system_name']);
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
			$this->set('title_for_layout', 'Nuevo Sistema');
			//Si hay informacion
			if (!empty($this->request->data)) 
			{
				//Se encapsula esa info. en un arreglo.
				$dataSistema = $this->request->data;
				
				$dataSistema['System']['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');
				
				$this->System->begin();
				
				//Si la info. se guardo
				if ($this->System->save($dataSistema)) 
				{
					$this->System->commit();
					
					$this->Session->setFlash('Se ha creado correctamente un nuevo sistema.', 'flash_success');
					$this->redirect(array('action' => 'index'));
				}
				
				$this->System->rollback();
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
			$this->System->id = $id;
			
			//Si no hay informacion
			if (empty($this->data)) 
			{
				//Obtengo la info. a partir del id en cuestión
				$this->data = $this->System->read();
				$dataSistema = $this->data;
				
				//Envio la información del usuario a consultar y para los selects
				$this->set('sistema', $dataSistema);
				$this->set('title_for_layout', 'Editando el sistema '.$dataSistema['System']['system_name']);
			} 
			//Si hay infomacion (pasado por post)
			else 
			{
				//Se encapsula esa info. en un arreglo.
				$dataSistema = $this->data;
				
				$this->System->begin();
				
				if ($this->System->save($dataSistema))
				{
					$this->System->commit();
					
					$this->Session->setFlash('Se ha editado correctamente el sistema existente.', 'flash_success');
					$this->redirect(array('action' => 'index'));
				}
				
				$this->System->rollback();
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
			$this->System->begin();
			
			if ($this->System->delete($id))
			{
				$this->System->commit();
				
				$this->Session->setFlash('Se ha eliminado correctamente un sistema existente.', 'flash_success');
				$this->redirect(array('action'=>'index'));
			}
			
			$this->System->rollback();
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	function showNotifications()
	{
		$dataActiveUserId = $this->RequestAction('/external_functions/getIdDataSession/');
		
		$notifications = $this->Notification->find('all', array('conditions' => array('Notification.user_id' => $dataActiveUserId, 'Notification.readed' => 0), 'order' => 'Notification.created DESC'));
		
		$this->set('notifications', $notifications);
	}
	
}
?>