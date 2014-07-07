<?php
	class SystemSectionsController extends AppController
	{
		var $name = 'SystemSections';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('SystemSection', 'System');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		

		function index() 
		{
			if($this->Auth->user('admin') == 1)
			{
				$this->paginate = array('SystemSection' => array('limit' => 20, 'order' => array('SystemSection.id')));
				$dataSections = $this->paginate();
				$this->set('sections', $dataSections);
				$this->set('title_for_layout', 'Secciones de Sistemas');
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
					//Se obtiene el id del usuario
					$this->SystemSection->id = $id;
					
					//Se lee la info del usuario de la DB
					$dataSection = $this->SystemSection->read();
					
					//Se devuelve arreglos a la vista
					$this->set('section',   $dataSection);
					$this->set('title_for_layout', 'Seccion '.$dataSection['SystemSection']['section_name'].' para el sistema '.$dataSection['System']['system_name']);
				}
				else
				{
					$this->Session->setFlash('No se ha especificado el id de la seccion para ser visualizado', 'flash_alert');
					$this->redirect(array('controller' => 'system_sections', 'action' => 'index'));
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
				$this->set('title_for_layout', 'Nueva Seccion');
				
				$systems = $this->System->find('list', array('fields' => array('System.id', 'System.system_name')));
				$this->set('systems', $systems);

				if (!empty($this->request->data)) 
				{
					if($this->request->data['SystemSection']['system_id'] == null || $this->request->data['SystemSection']['system_id'] == '')
					{
						$this->Session->setFlash('La sección debe tener un sistema asociado', 'flash_alert');
						return false;
					}
					
					if($this->verifySection($this->request->data['SystemSection']['system_id'], $this->request->data['SystemSection']['section_function']) == true)
					{
						$this->Session->setFlash('La sección ya existe para el sistema seleccionado.', 'flash_alert');
						return false;
					}
						
					
					$this->SystemSection->begin();
					
					//Si la info. se guardo
					if ($this->SystemSection->save($this->request->data)) 
					{
						$this->SystemSection->commit();
						
						$this->Session->setFlash('Se ha creado correctamente la seccion.', 'flash_success');
						$this->redirect(array('action' => 'index'));
					}
					
					$this->SystemSection->rollback();
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
				if($id != null)
				{
					//Obtengo el id a consulltar
					$this->SystemSection->id = $id;
				
					$this->set('systems', $this->System->find('list', array('fields' => array('System.id', 'System.system_name'))));
					
					//Si no hay informacion
					if (empty($this->request->data)) 
					{
						//Obtengo la info. a partir del id en cuestión
						$this->request->data = $this->SystemSection->read();
						
						//Envio la información del usuario a consultar y para los selects
						$this->set('sistema', $this->request->data);
						$this->set('title_for_layout', 'Editando el sistema '.$this->request->data['System']['system_name']);
					} 
					//Si hay infomacion (pasado por post)
					else 
					{
						if($this->request->data['SystemSection']['system_id'] == null || $this->request->data['SystemSection']['system_id'] == '')
						{
							$this->Session->setFlash('La sección debe tener un sistema asociado', 'flash_alert');
							$this->redirect(array('controller' => 'system_sections', 'action' => 'edit/'.$id));
						}
						
						$this->SystemSection->begin();
						
						if ($this->SystemSection->save($this->request->data))
						{
							$this->SystemSection->commit();
							
							$this->Session->setFlash('Se ha editado correctamente seccion.', 'flash_success');
							$this->redirect(array('action' => 'index'));
						}
						
						$this->SystemSection->rollback();
					}	
				}
				else
				{
					$this->Session->setFlash('No se ha especificado el id de la seccion para ser editado', 'flash_alert');
					$this->redirect(array('controller' => 'system_sections', 'action' => 'index'));
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
				if($id != null)
				{
					$this->SystemSection->begin();
					
					if ($this->SystemSection->delete($id))
					{
						$this->SystemSection->commit();
						
						$this->Session->setFlash('Se ha eliminado correctamente la sección.', 'flash_success');
						$this->redirect(array('action'=>'index'));
					}
					
					$this->SystemSection->rollback();
				}
				else
				{
					$this->Session->setFlash('No se ha especificado el id de la seccion para ser eliminado', 'flash_alert');
					$this->redirect(array('controller' => 'system_sections', 'action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function verifySection($system = null, $sectionFunction= null)
		{
			if($system != null && $section != null)
			{
				$exist = 0;
				
				$sections = $this->SystemSection->find('all', array('conditions' => array('SystemSection.system_id' => $system)));
				
				foreach($sections as $section)
				{
					if($section['SystemSection']['section_function'] == $sectionFunction)
					{
						$exist = 1;
					}
				}
				
				if($exist == 1)
				{
					return false;
				}
				else
				{
					return true;
				}
			}
		}
}
?>