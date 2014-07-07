<?php
class ReplacementsController extends AppController 
{
	//Declaracion de variables con llamada a helpers, clases externas
    var $name = 'Replacements';
	var $helpers = array('Session', 'Html', 'Form','Time');
	var $uses = array('Replacement','User','Management','TypeReplacement', 'Sign');
	var $scaffold;
	var $paginate = array();
	
	//Lista todos los usuarios de la DB
	function index() 
	{
		if($this->Auth->user('admin') == 1)
		{
			//Se envia arreglo con datos de todos los usuarios
			$this->paginate = array('Replacement' => array('limit' => 15, 'order' => array('Replacement.created DESC')));
			$dataReemplazos = $this->paginate();
			
			$this->set('reemplazos', $dataReemplazos);
			$this->set('title_for_layout', 'Reemplazos');
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
			$this->Replacement->id = $id;
			
			//Se lee la info del reemplazode la DB
			$dataReemplazo = $this->Replacement->read();
			
			if($dataReemplazo['Replacement']['active'] == 1)
				$dataReemplazo['Replacement']['active'] = 'Si';
			else
				$dataReemplazo['Replacement']['active'] = 'No';
				
			/*echo "<br><br><br><br><br><br><br><br><pre>";
			print_r($dataReemplazo);
			echo "</pre>";*/
				
			//Se devuelve arreglos a la vista
			$this->set('reemplazo', $dataReemplazo);
			$this->set('title_for_layout', 'Reemplazo de '.$dataReemplazo['replaced_user']['name'].' '.$dataReemplazo['replaced_user']['first_lastname']);
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
			//Se llama a las funciones de cargos y gerencias para los selects
			$this->set('usuarios', $this->RequestAction('/external_functions/showUsuarios/'));
			$this->set('title_for_layout', 'Nuevo Reemplazo');
			
			//Si hay informacion
			if (!empty($this->request->data)) 
			{
				//Se encapsula esa info. en un arreglo.
				$dataReemplazo = $this->request->data;
				
				if($dataReemplazo['Replacement']['start_date'] == '')
				{
					$this->Session->setFlash('Debe seleccionar la fecha de inicio del reemplazo.', 'flash_alert');
					$this->redirect(array('action' => 'add'));
				}
				
				if($dataReemplazo['Replacement']['end_date'] == '')
				{
					$this->Session->setFlash('Debe seleccionar la fecha de término del reemplazo.', 'flash_alert');
					$this->redirect(array('action' => 'add'));
				}
				
				$dataReemplazo['Replacement']['start_original_date'] = $dataReemplazo['Replacement']['start_date'];
				
				if($dataReemplazo['Replacement']['start_date'] <= date('Y-m-d') && $dataReemplazo['Replacement']['end_date'] >= date('Y-m-d'))
					$dataReemplazo['Replacement']['active'] = 1;
				else
					$dataReemplazo['Replacement']['active'] = 0;
				
				$dataReemplazo['Replacement']['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');;
				//$dataReemplazo['Replacement']['created_by_id'] = 1;
				
				$this->Replacement->begin();
				
				//Si la info. se guardo OK...
				if ($this->Replacement->save($dataReemplazo)) 
				{
					$last_id = $this->Replacement->getLastInsertID();
					
					$changeSigns = $this->RequestAction('/signs/changeReplacementSigns/'.$last_id);
					
					if($changeSigns == true)
					{
						$createTemporalPermissions = $this->RequestAction('/user_permissions/addingTemporalReplacementPermissions/'.$last_id);
						
						if($createTemporalPermissions == true)
						{
							$lastReplacement = $this->Replacement->find('first', array('conditions' => array('Replacement.id' => $last_id), 'fields' => array('Replacement.id', 'Replacement.replaced_user_id', 'Replacement.replacing_user_id')));
							
							$notificationToReplacedUser = $this->RequestAction('/notifications/replacedNotification/'.$lastReplacement['Replacement']['replaced_user_id'].'/'.$last_id);
							
							if($notificationToReplacedUser == true)
							{
								$notificationToReplacingUser = $this->RequestAction('/notifications/replacingNotification/'.$lastReplacement['Replacement']['replacing_user_id'].'/'.$last_id);
								
								if($notificationToReplacingUser == true)
								{
									$this->Replacement->commit();
							
									$this->Session->setFlash('Se ha creado correctamente un nuevo reemplazo.', 'flash_success');
									$this->redirect(array('action' => 'index'));
								}
								
								$this->Replacement->rollback();
							}
							
							$this->Replacement->rollback();
						}
						
						$this->Replacement->rollback();
					}
					
					$this->Replacement->rollback();
				}
				
				$this->Replacement->rollback();
			}
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	
	function enable($id = null) 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->Replacement->id = $id;
			$this->request->data = $this->Replacement->read();
			
			$this->request->data['Replacement']['active'] = 1;
			$this->request->data['Replacement']['start_date'] = date('Y-m-d');
			
			$enabledPermissions = $this->RequestAction('/user_permissions/enabledTemporalPermissions/'.$id);
			
			if($enabledPermissions== true)
			{
				$this->Replacement->begin();
				
				if ($this->Replacement->save($this->request->data))
				{
					$this->Replacement->commit();
					
					$this->Session->setFlash('Se ha habilitado correctamente el reemplazo seleccionado.', 'flash_success');
					$this->redirect(array('action'=>'index'));
				}
				
				$this->Replacement->rollback();
			}
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
			$this->Replacement->id = $id;
			$this->request->data = $this->Replacement->read();
			
			$this->request->data['Replacement']['active'] = 0;
			
			if($this->request->data['Replacement']['start_date'] != $this->request->data['Replacement']['start_original_date'])
				$this->request->data['Replacement']['start_date'] = $this->request->data['Replacement']['start_original_date'];
			
			$disabledPermissions = $this->RequestAction('/user_permissions/disabledTemporalPermissions/'.$id);
			
			if($disabledPermissions== true)
			{
				$this->Replacement->begin();
				
				if ($this->Replacement->save($this->request->data))
				{
					$this->Replacement->commit();
					
					$this->Session->setFlash('Se ha deshabilitado correctamente el reemplazo seleccionado.', 'flash_success');
					$this->redirect(array('action'=>'index'));
				}
				
				$this->Replacement->rollback();
			}
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	

	
	//borrado de un usuario en particular
	function delete($id = null) 
	{
		if($this->Auth->user('admin') == 1)
		{
			$pastInvalidReplacement = $this->Replacement->find('first', array('conditions' => array('Replacement.id' => $id)));
			
			$pastInvalidReplacement['Replacement']['active'] = 0;
			
			$this->Replacement->begin();
			
			if($this->Replacement->save($pastInvalidReplacement))
			{
				$changeSigns = $this->RequestAction('/signs/changeReplacementSigns/'.$id);
					
				if($changeSigns == true)
				{	
					$deletePermissions = $this->RequestAction('/user_permissions/deletedTemporalPermissions/'.$id);
					
					if($deletePermissions == true)
					{
						$this->Replacement->commit();
						
						$this->Replacement->begin();
						
						if ($this->Replacement->delete($id))
						{
							$this->Replacement->commit();
							
							$this->Session->setFlash('El reemplazo ha sido borrado de la base de datos.', 'flash_success');
							$this->redirect(array('action'=>'index'));
						}
						
						$this->Replacement->rollback();
					}
					
					$this->Replacement->rollback();
				}
				
				$this->Replacement->rollback();
			}
			
			$this->Replacement->rollback();
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
}
?>