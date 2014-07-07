<?php
class UserProfilesController extends AppController 
{
	//Declaracion de variables con llamada a helpers, clases externas
    var $name = 'UserProfiles';
	var $helpers = array('Session', 'Html', 'Form','Time');
    var $uses = array('UserProfile','User','Profile');
	var $scaffold;
	var $paginate = array();
	
	//Lista todos los usuarios de la DB
	function index() 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->paginate = array('UserProfile' => array('limit' => 10, 'order' => array('UserProfile.created DESC')));
			$dataUsuarioProfile = $this->paginate();

			$this->set('usuarioProfiles', $dataUsuarioProfile);
			$this->set('title_for_layout', 'Asignaciones de perfiles a usuarios');
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
			$this->UserProfile->id = $id;
			
			//Se lee la info del usuario de la DB
			$dataUsuarioProfile = $this->UserProfile->read();	
			
			//Se devuelve arreglos a la vista
			$this->set('usuarioProfile', $dataUsuarioProfile);
			$this->set('title_for_layout', 'Datos de la asignación');
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
			$dataUsuarios = $this->RequestAction('/external_functions/showUsuarios/');	
			$this->set('usuarios', $dataUsuarios);
			$this->set('title_for_layout', 'Nueva asignación');
			

			//Si hay información
			if (!empty($this->request->data)) 
			{
				$dataAsignaciones = $this->request->data;
				$dataMultiSelect = $this->request->data['UserProfile']['profile_id'];
				$cont = 0;
				
				$dataUserProfile = $this->User->find('first', array('conditions' => array('User.id' => $this->request->data['UserProfile']['user_id']), 'fields' => array('User' => 'id', 'Profile' => 'id')));
				
				$tmp2 = array();
				
				
				foreach($dataMultiSelect as $dataSelect)
				{
					$tmp['user_id'] = $this->request->data['UserProfile']['user_id'];
					$tmp['profile_id'] = $dataSelect;
					$tmp['system_id'] = $this->request->data['Sistema']['system_id'];
					$tmp['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');
			
					array_push($tmp2, $tmp);
			
					$cont = $cont + 1;
				}
				
				$this->request->data['UserProfile'] = $tmp2;
				$dataAsignaciones = $this->request->data['UserProfile'];
				
				$this->UserProfile->begin();
				
				//Si la info. se guardo
				if ($this->UserProfile->saveAll($dataAsignaciones)) 
				{
					$lastAssigns = $this->UserProfile->inserted_ids;
					
					$x = 0;
					foreach($lastAssigns as $last)
					{
						$assign = $this->UserProfile->find('first', array('conditions' => array('UserProfile.id' =>$last)));
						$notificationToAssigned = $this->RequestAction('/notifications/profileAssignNotification/'.$assign['UserProfile']['user_id'].'/'.$assign['UserProfile']['system_id'].'/'.$assign['UserProfile']['profile_id']);
						
						if($notificationToAssigned == true)
						{
							$dataUser = $this->User->find('first', array('conditions' => array('User.id' => $assign['UserProfile']['user_id'])));
							
							if($dataUser['Management']['user_id'] != $assign['UserProfile']['user_id'])
							{
								$notificationToManagement = $this->RequestAction('/notifications/profileAssignNotificationToManagement/'.$dataUser['Management']['user_id'].'/'.$assign['UserProfile']['system_id'].'/'.$assign['UserProfile']['profile_id'].'/'.$assign['UserProfile']['user_id']);
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
									$this->UserProfile->commit();
					
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
				
				$this->UserProfile->rollback();
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
			$this->UserProfile->begin();
			
			if ($this->UserProfile->delete($id))
			{
				$this->UserProfile->commit();
				
				$this->Session->setFlash('Se ha eliminado correctamente la asignación seleccionada.', 'flash_success');
				$this->redirect(array('action'=>'index'));
			}
			
			$this->UserProfile->rollback();
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
}
?>