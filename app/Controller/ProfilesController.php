<?php
class ProfilesController extends AppController 
{
	//Declaracion de variables con llamada a helpers, clases externas
    var $name = 'Profiles';
	var $helpers = array('Session', 'Html', 'Form','Time');
    var $uses = array('Profile', 'System', 'User', 'CostCenter');
	var $scaffold;
	var $paginate = array();
	
	//Lista todos los usuarios de la DB
	function index() 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->paginate = array('Profile' => array('limit' => 10, 'order' => array('Profile.created DESC')));
			$dataProfiles = $this->paginate();
			$this->set('profiles', $dataProfiles);
			$this->set('title_for_layout', 'Perfiles');
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
    }
	
	
	
	//Muestra los datos de un usuario en particular
	function view($id = null) 
	{
		if($this->Auth->user('admin') == 1)
		{
			//Se obtiene el id del usuario
			$this->Profile->id = $id;
			
			//Se lee la info del usuario de la DB
			$dataProfile = $this->Profile->read();	
			
			//Se devuelve arreglos a la vista
			$this->set('profile', $dataProfile);
			$this->set('title_for_layout', 'Datos del perfil '.$dataProfile['Profile']['profile_name']);
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
    }
	
	
	 
	
	
	//Funcion para agregar un nuevo usuario
	function add() 
	{
		if($this->Auth->user('admin') == 1)
		{
			$dataSistemas = $this->System->find('list', array('fields' => array('System.id', 'System.system_name'), 'order' => 'System.system_name ASC'));
			$this->set('sistemas', $dataSistemas);
			$this->set('title_for_layout', 'Nuevo Perfil');
		
			//Si hay información
			if (!empty($this->data)) 
			{
				//Se encapsula esa info. en un arreglo.
				$dataProfile = $this->data;
				$dataProfile['Profile']['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');
				
				$this->Profile->begin();
				
				//Si la info. se guardo
				if ($this->Profile->save($dataProfile)) 
				{
					$this->Profile->commit();
					//Envia un flash con mensaje de confrimacion y se redirecciona al index
					$this->Session->setFlash('Se ha creado correctamente un nuevo perfil.', 'flash_success');
					$this->redirect(array('action' => 'index'));
				}
				
				$this->Profile->rollback();
			}
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
    }
	
	

	
	
	//Edicion de un usuario en particular
	function edit($id = null)
	{
		if($this->Auth->user('admin') == 1)
		{
			//Obtengo el id a consulltar
			$this->Profile->id = $id;
			$dataSistemas = $this->System->find('list', array('fields' => array('System.id', 'System.system_name'), 'order' => 'System.system_name ASC'));
			
			//Si no hay informacion
			if (empty($this->data)) 
			{
				
				$this->data = $this->Profile->read();
				$this->set('sistemas', $dataSistemas);
				$this->set('title_for_layout', 'Editando el perfil '.$this->data['Profile']['profile_name']);
				
			} 
			
			//Si hay infomacion (pasado por post)
			else 
			{
				//Se encapsula esa info. en un arreglo.
				$dataProfile = $this->data;
				$this->set('sistemas', $dataSistemas);
				
				$this->Profile->begin();
				
				if ($this->Profile->save($dataProfile))
				{
					$this->Profile->commit();
					//Envia un flash con mensaje de confrimacion y se redirecciona al index
					$this->Session->setFlash('Se ha editado correctamente un perfil existente.', 'flash_success');
					$this->redirect(array('action' => 'index'));
				}
				
				$this->Profile->rollback();
			}
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}

	
	
	
	//borrado de un usuario en particular
	function delete($id) 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->Profile->begin();
			
			if ($this->Profile->delete($id))
			{
				$this->Profile->commit();
				
				$this->Session->setFlash('Se ha eliminado correctamente el perfil seleccionado.', 'flash_success');
				$this->redirect(array('action'=>'index'));
			}
			
			$this->Profile->rollback();
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}

}
?>