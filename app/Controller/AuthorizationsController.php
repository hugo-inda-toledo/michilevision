<?php
class AuthorizationsController extends AppController 
{
    var $name = 'Authorizations';
	var $helpers = array('Session', 'Html', 'Form','Time');
	var $uses = array('Authorization');
	var $components = array('Password', 'Email', 'Auth');
	var $scaffold;
	
	function index() 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->set('authorizations', $this->Authorization->find('all'));
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
			if($id != null)
			{
				$this->Authorization->id = $id;
				$this->set('authorization', $this->Authorization->read());
			}
			else
			{
				$this->Session->setFlash('No existe la autorización asociada, no se puede mostrar.', 'flash_deny');
				$this->redirect(array('controller' => 'authorizations', 'action' => 'index'));
			}
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
			if(!empty($this->request->data))
			{
				$this->request->data['Authorization']['created_by_id'] = $this->Auth->user('id');
				
				$this->Authorization->begin();
				
				if($this->Authorization->save($this->request->data))
				{
					$this->Authorization->commit();
					$this->Session->setFlash('Autorización creada correctamente', 'flash_success');
					$this->redirect(array('controller' => 'authorizations', 'action' => 'index'));
				}
				
				$this->Authorization->rollback();
			}
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	function enable($id = null, $route = null)
	{
		if($this->Auth->user('admin') == 1)
		{
			if($id != null && $route != null)
			{
				$dataAuth = $this->Authorization->find('first', array('conditions' => array('Authorization.id' => $id)));
				
				if($dataAuth != false)
				{
					$dataAuth['Authorization']['active'] = 1;
					
					$this->Authorization->begin();
					
					if($this->Authorization->save($dataAuth))
					{
						$this->Authorization->commit();
						$this->Session->setFlash('Autorización activada correctamente.', 'flash_success');
						
						if($route == 'view')
							$this->redirect(array('controller' => 'authorizations', 'action' => $route.'/'.$dataAuth['Authorization']['id']));
						
						if($route == 'index')
							$this->redirect(array('controller' => 'authorizations', 'action' => $route));
					}
					
					$this->Authorization->rollback();
				}
				else
				{
					$this->Session->setFlash('No existe la autorización asociada, no se puede activar.', 'flash_deny');
					$this->redirect(array('controller' => 'authorizations', 'action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash('No se puede activar una autorización sin identificador', 'flash_alert');
				$this->redirect(array('controller' => 'authorizations', 'action' => 'index'));
			}
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	function disable($id = null, $route = null)
	{
		if($this->Auth->user('admin') == 1)
		{
			if($id != null && $route != null)
			{
				$dataAuth = $this->Authorization->find('first', array('conditions' => array('Authorization.id' => $id)));
				
				if($dataAuth != false)
				{
					$dataAuth['Authorization']['active'] = 0;
					
					$this->Authorization->begin();
					
					if($this->Authorization->save($dataAuth))
					{
						$this->Authorization->commit();
						$this->Session->setFlash('Autorización desactivada correctamente.', 'flash_success');
						
						if($route == 'view')
							$this->redirect(array('controller' => 'authorizations', 'action' => $route.'/'.$dataAuth['Authorization']['id']));
						
						if($route == 'index')
							$this->redirect(array('controller' => 'authorizations', 'action' => $route));
					}
					
					$this->Authorization->rollback();
				}
				else
				{
					$this->Session->setFlash('No existe la autorización asociada, no se puede desactivar.', 'flash_deny');
					$this->redirect(array('controller' => 'authorizations', 'action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash('No se puede desactivar una autorización sin identificador', 'flash_alert');
				$this->redirect(array('controller' => 'authorizations', 'action' => 'index'));
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