<?php
class UserPermissionsController extends AppController 
{
	//Declaracion de variables con llamada a helpers, clases externas
    var $name = 'UserPermissions';
	var $helpers = array('Session', 'Html', 'Form','Time');
    var $uses = array('UserPermission','User','Permission', 'Replacement', 'UserProfile');
	var $scaffold;
	var $paginate = array();
	
	
	function index() 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->paginate = array('UserPermission' => array('limit' => 10, 'order' => array('UserPermission.created DESC')));
			$dataUsuarioPermiso = $this->paginate();
			$this->set('usuarioPermisos', $dataUsuarioPermiso);
			$this->set('title_for_layout', 'Asignaciones de permisos a usuarios');
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
			$this->UserPermission->id = $id;
			
			//Se lee la info del usuario de la DB
			$dataUsuarioPermisos = $this->UserPermission->read();
			
			if($dataUsuarioPermisos['UserPermission']['active'] == 1)
				$dataUsuarioPermisos['UserPermission']['active'] = 'Si';
			else
				$dataUsuarioPermisos['UserPermission']['active'] = 'No';
			
			$dataUsuarioPermisos['UserPermission']['start_date'] = $this->RequestAction('/external_functions/setDate/'.$dataUsuarioPermisos['UserPermission']['start_date']); 
			$dataUsuarioPermisos['UserPermission']['end_date'] =   $this->RequestAction('/external_functions/setDate/'.$dataUsuarioPermisos['UserPermission']['end_date']); 
			
			//Se devuelve arreglos a la vista
			$this->set('usuarioPermiso',   $dataUsuarioPermisos);
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
			$dataUsuarios = $this->RequestAction('/external_functions/showUsuarios/');

			$this->set('usuarios', $dataUsuarios);
			$this->set('title_for_layout', 'Nueva asignación');
			
			//Si hay información
			if (!empty($this->request->data)) 
			{
				//Se encapsula esa info. en un arreglo.
				
				$this->request->data['UserPermission']['system_id'] = $this->request->data['System']['system_id'];
				
				if($this->request->data['UserPermission']['start_date'] <= date('Y-m-d') && $this->request->data['UserPermission']['end_date'] >= date('Y-m-d'))
					$this->request->data['UserPermission']['active'] = 1;
				else
					$this->request->data['UserPermission']['active'] = 0;
					
				$this->request->data['UserPermission']['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');
				
				$this->UserPermission->begin();
				
				if ($this->UserPermission->save($this->request->data)) 
				{
					$this->UserPermission->commit();
					
					$this->Session->setFlash('Se ha creado correctamente una nueva asignación.', 'flash_success');
					$this->redirect(array('action' => 'index'));
				}
				
				$this->UserPermission->rollback();
			}
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));;
		}
    }
	
	function enable($id = null) 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->UserPermission->id = $id;
			$this->request->data = $this->UserPermission->read();
			
			$this->request->data['UserPermission']['active'] = 1;
			
			$this->UserPermission->begin();
			
			if($this->UserPermission->save($this->request->data))
			{
				$this->UserPermission->commit();
				
				$this->Session->setFlash('Se ha habilitado correctamente la asignación seleccionada.', 'flash_success');
				$this->redirect(array('action'=>'index'));
			}
			
			$this->UserPermission->rollback();
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	function disable($id = null) 
	{
		if($this->Auth->user('admin') == 1) 
		{
			$this->UserPermission->id = $id;
			$this->request->data = $this->UserPermission->read();
			
			$this->request->data['UserPermission']['active'] = 0;
			
			$this->UserPermission->begin();
			
			if ($this->UserPermission->save($this->request->data))
			{
				$this->UserPermission->commit();
				
				$this->Session->setFlash('Se ha deshabilitado correctamente la asignación seleccionada.', 'flash_success');
				$this->redirect(array('action'=>'index'));
			}
			
			$this->UserPermission->rollback();
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
			$this->UserPermission->begin();
			
			if ($this->UserPermission->delete($id))
			{
				$this->UserPermission->commit();
				
				$this->Session->setFlash('Se ha eliminado correctamente la asignación seleccionada.', 'flash_success');
				$this->redirect(array('action'=>'index'));
			}
			
			$this->UserPermission->rollback();
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	function addingTemporalReplacementPermissions($replacement_id)
	{
		if($this->Auth->user('admin') == 1)
		{
			$dataUserPermissions = array();
			
			$dataReplacement = $this->Replacement->find('first', array('conditions' => array('Replacement.id' => $replacement_id)));
			$dataReplacedUser = $this->User->find('first', array('conditions' => array('User.id' => $dataReplacement['replaced_user']['id'])));
			$dataReplacingUser = $this->User->find('first', array('conditions' => array('User.id' => $dataReplacement['replacing_user']['id'])));
			
			//MYSQL
			//$profiles = $this->Profile->query("select profile_id, system_id from user_profiles where user_id= ".$dataReplacedUser['User']['id']." union distinct select profile_id, system_id from user_profiles where user_id= ".$dataReplacedUser['User']['id']." order by 2,1;");
			
			//SQL SERVER
			$profiles = $this->Profile->query("select profile_id, system_id from user_profiles where user_id= ".$dataReplacedUser['User']['id']." union  select  distinct profile_id, system_id from user_profiles where user_id= ".$dataReplacedUser['User']['id']." order by 2,1;");
			
			if($profiles != false)
			{
				$y=0;
				foreach($profiles as $profile)
				{
					$dataProfile = $this->Profile->find('first', array('conditions' => array('Profile.id' =>$profile[0]['profile_id'])));
					
					if($dataProfile['ProfilePermission'] != false)
					{			
						for($x=0; $x < count($dataProfile['ProfilePermission']); $x++)
						{
							$dataUserPermissions[$y]['user_id'] = $dataReplacement['replacing_user']['id'];
							$dataUserPermissions[$y]['system_id'] = $profile[0]['system_id'];
							$dataUserPermissions[$y]['permission_id'] = $dataProfile['ProfilePermission'][$x]['permission_id'];
							$dataUserPermissions[$y]['replacement_id'] = $replacement_id;
							
							if($dataReplacement['Replacement']['start_date'] <= date('Y-m-d') && $dataReplacement['Replacement']['end_date'] >= date('Y-m-d'))
								$dataUserPermissions[$y]['active'] = 1;
							else
								$dataUserPermissions[$y]['active'] = 0;
							
							$dataUserPermissions[$y]['start_date'] = $dataReplacement['Replacement']['start_date'];
							$dataUserPermissions[$y]['end_date'] = $dataReplacement['Replacement']['end_date'];
							$dataUserPermissions[$y]['start_original_date'] = $dataReplacement['Replacement']['start_date'];
							
							//$dataUserPermissions[$y]['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');
							$dataUserPermissions[$y]['created_by_id'] = 1;
							$y++;
						}
					}
				}
				$this->UserPermission->begin();
				
				if($this->UserPermission->saveAll($dataUserPermissions))
				{
					$this->UserPermission->commit();
					return true;
				}
				else
				{
					$this->UserPermission->rollback();
					return false;
				}
			}
			else
				return true;
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	function deletedTemporalPermissions($replacement_id)
	{
		if($this->Auth->user('admin') == 1)
		{
			$dataPermissions = $this->UserPermission->find('all', array('conditions' => array('UserPermission.replacement_id' => $replacement_id)));
			$cont=0;
			
			for($x=0; $x < count($dataPermissions); $x++)
			{
				$this->UserPermission->delete($dataPermissions[$x]['UserPermission']['id']);
			}
			return true;
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	function enabledTemporalPermissions($replacement_id)
	{
		if($this->Auth->user('admin') == 1)
		{
			$dataPermissions = $this->UserPermission->find('all', array('conditions' => array('UserPermission.replacement_id' => $replacement_id)));
			
			if($dataPermissions != false)
			{
				for($x=0; $x < count($dataPermissions); $x++)
				{
					$dataPermissions[$x]['UserPermission']['active'] = 1;
					$dataPermissions[$x]['UserPermission']['start_date'] = date('Y-m-d');
				}
				
				$this->UserPermission->begin();
				
				if($this->UserPermission->saveAll($dataPermissions))
				{
					$this->UserPermission->commit();
					return true;
				}
				else
				{
					$this->UserPermission->rollback();
					return false;
				}
			}
			else
				return true;
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	function disabledTemporalPermissions($replacement_id)
	{
		if($this->Auth->user('admin') == 1)
		{
			$dataPermissions = $this->UserPermission->find('all', array('conditions' => array('UserPermission.replacement_id' => $replacement_id)));
			
			if($dataPermissions != false)
			{	
				for($x=0; $x < count($dataPermissions); $x++)
				{
					$dataPermissions[$x]['UserPermission']['active'] = 0;
				
					if($dataPermissions[$x]['UserPermission']['start_date'] != $dataPermissions[$x]['UserPermission']['start_original_date'])
						$dataPermissions[$x]['UserPermission']['start_date'] = $dataPermissions[$x]['UserPermission']['start_original_date'];
				}
				
				$this->UserPermission->begin();
				
				if($this->UserPermission->saveAll($dataPermissions))
				{
					$this->UserPermission->commit();
					return true;
				}
				else
				{
					$this->UserPermission->rollback();
					return false;
				}
			}
			else
				return true;
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
}
?>