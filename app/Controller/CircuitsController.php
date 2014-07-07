<?php
	class CircuitsController extends AppController
	{
		var $name = 'Circuits';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('Circuit', 'User', 'System', 'Authorization', 'Management', 'Headquarter', 'Replacement');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		function index() 
		{
			if($this->Auth->user('admin') == 1)
			{
				$this->paginate = array('Circuit' => array('limit' => 10, 'order' => array('Circuit.id ASC')));
				$dataCircuits = $this->paginate();
				
				$this->set('circuits', $dataCircuits);
				$this->set('title_for_layout', 'Circuitos');
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
				$authorizations = $this->Authorization->find('list', array('fields' => array('Authorization.id', 'Authorization.name')));
				$systems = $this->RequestAction('/external_functions/showSistemasForSelect/');
				$approvedUsers = $this->getAuthorizedUsers();
				
				$this->set('authorizations',$authorizations);
				$this->set('systems',$systems);
				$this->set('users', $approvedUsers);
				$this->set('title_for_layout', 'Nuevo Circuito');
				
				
				if(!empty($this->data))
				{
					$dataCircuit = $this->data;
					
					$getPositionUser = $this->User->find('first', array('conditions' => array('User.id' => $dataCircuit['Circuit']['user_id']), 'fields' => array('Position.position')));
					
					$dataCircuit['Circuit']['position'] = $getPositionUser['Position']['position'];
					$dataCircuit['Circuit']['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');
					
					$this->validateCircuit($dataCircuit, 'add', 0);
					
					$this->Circuit->begin();
					
					if($this->Circuit->save($dataCircuit))
					{
						$this->Circuit->commit();
						
						$this->Session->setFlash('Se ha creado correctamente un nuevo circuito.', 'flash_success');
						$this->redirect(array('action' => 'index'));
					}
					
					$this->Circuit->rollback();
				}
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
				$this->Circuit->id = $id;
				
				//Se lee la info del usuario de la DB
				$this->data = $this->Circuit->read();	
				$dataCircuit = $this->data;
				
				//Se devuelve arreglos a la vista
				$this->set('circuit', $dataCircuit);
				$this->set('title_for_layout', 'Datos del circuito');
			}
			else
			{
				$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		
		
		
		
		
		//Edicion de un usuario en particular...
		function edit($id = null) 
		{
			if($this->Auth->user('admin') == 1)
			{
				//Obtengo el id a consultar
				$this->Circuit->id = $id;
				
				$authorizations = $this->Authorization->find('list', array('fields' => array('Authorization.id', 'Authorization.name')));
				$systems = $this->RequestAction('/external_functions/showSistemasForSelect/');
				$approvedUsers = $this->getAuthorizedUsers();
				
				//Envio la informacion a los selects
				$this->set('authorizations',$authorizations);
				$this->set('systems',$systems);
				$this->set('users', $approvedUsers);
				$this->set('circuit', $this->data);
				$this->set('title_for_layout', 'Editando un circuito');
				
				
				//Si No hay infomacion (pasado por post)
				if (empty($this->data)){
					$this->data = $this->Circuit->read();
				} 
				
				
				//Si hay infomacion (pasado por post)
				else 
				{
					//Se encapsula esa info. en un arreglo.
					$dataCircuit = $this->request->data;
					$getPositionUser = $this->User->find('first', array('conditions' => array('User.id' => $dataCircuit['Circuit']['user_id']), 'fields' => array('Position.position')));
					$dataCircuit['Circuit']['position'] = $getPositionUser['Position']['position'];
					$this->validateCircuit($dataCircuit, 'edit', $id);
								
					$this->Circuit->begin();
					
					//Si la info. se guardo
					if ($this->Circuit->save($dataCircuit))
					{
						$this->Circuit->commit();
						
						//Envia un flash con mensaje de confirmacion y se redirecciona al index
						$this->Session->setFlash('Se ha editado correctamente el circuito.', 'flash_success');
						$this->redirect(array('action' => 'index'));
					}
					
					$this->Circuit->rollback();
				}
			}
			else
			{
				$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		
		
		
		
		
		
		function getAuthorizedUsers()
		{
			$approvedUsers = array();
			
			//Buscando gerentes y pasandolos al arreglo de usuarios
			$managementUsers = $this->Management->find('all', array('order' => 'User.first_lastname ASC'));
			
			for($x=0; $x < count($managementUsers); $x++)
			{
				$approvedUsers[$managementUsers[$x]['User']['id']] = $managementUsers[$x]['User']['first_lastname'].' '.$managementUsers[$x]['User']['second_lastname'].', '.$managementUsers[$x]['User']['name'];
			}
			
			//Buscando jefas y pasandolos al arreglo de usuarios
			$headquarterUsers = $this->Headquarter->find('all', array('order' => 'User.first_lastname ASC'));
			
			for($x=0; $x < count($headquarterUsers); $x++)
			{
				foreach($approvedUsers as $key => $value)
				{
					if($key != $headquarterUsers[$x]['User']['id'])
					{
						$approvedUsers[$headquarterUsers[$x]['User']['id']] = $headquarterUsers[$x]['User']['first_lastname'].' '.$headquarterUsers[$x]['User']['second_lastname'].', '.$headquarterUsers[$x]['User']['name'];
					}
				}
			}
			
			//Buscando reemplazantes y pasandolos al arreglo de usuarios
			$replacementUsers = $this->Replacement->find('all');
			
			for($x=0; $x < count($replacementUsers); $x++)
			{
				foreach($approvedUsers as $key => $value)
				{
					if($key != $replacementUsers[$x]['replacing_user']['id'])
					{
						$approvedUsers[$replacementUsers[$x]['replacing_user']['id']] = $replacementUsers[$x]['replacing_user']['first_lastname'].' '.$replacementUsers[$x]['replacing_user']['second_lastname'].', '.$replacementUsers[$x]['replacing_user']['name'];
					}
				}
			}
			
			return $approvedUsers;
		}





		
		function validateCircuit($dataCircuit, $action, $id = null)
		{
			if($action == 'add'){
			$actualCircuits = $this->Circuit->find('all');
			$load = $action;	
			}
			
			if($action == 'edit'){
			$actualCircuits = $this->Circuit->find('all', array('conditions' => array('Circuit.id <> ' => $id)));	
			$load = $action.'/'.$id;
			}
				
			for($x=0; $x < count($actualCircuits); $x++)
			{
				if($actualCircuits[$x]['System']['id'] == $dataCircuit['Circuit']['system_id'])
				{
					if($actualCircuits[$x]['Authorization']['id'] == $dataCircuit['Circuit']['authorization_id'])
					{
						if($actualCircuits[$x]['Circuit']['level'] == $dataCircuit['Circuit']['level'])
						{
							$this->Session->setFlash('Ya existe un circuito con nivel '. $dataCircuit['Circuit']['level'].' para el sistema "'.$actualCircuits[$x]['System']['system_name'].'" con autorización "'.$actualCircuits[$x]['Authorization']['name'].'"', 'flash_error');
							$this->redirect(array('action' => $load));
						}
					}
				}
			}
		}
}
?>