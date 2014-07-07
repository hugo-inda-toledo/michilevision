<?php
	class RegionsController extends AppController
	{
		var $name = 'Regions';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('Region', 'Province', 'Commune');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		function index()
		{
			if($this->Auth->user('admin') == 1)
			{
				$this->paginate = array('Region' => array('limit' => 20, 'order' => array('Region.id' => 'ASC')));
				$this->set('regions', $this->paginate());
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
					$this->Region->id = $id;
					$this->request->data = $this->Region->read();
				}
				else
				{
					$this->Session->setFlash('El Id de la región no puede ser nulo', 'flash_error');
					$this->redirect(array('controller' => 'regions', 'action' => 'index'));
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
					$validateRegion = $this->Region->find('first', array('conditions' => array('Region.region_name' => $this->request->data['Region']['region_name'])));
					
					if($validateRegion != false)
					{
						$this->Session->setFlash('Esta región ya existe.', 'flash_alert');
						return false;
					}
					else
					{
						$this->Region->begin();
						
						if($this->Region->save($this->request->data))
						{
							$this->Region->commit();
							$this->Session->setFlash('Región Agregada Correctamente.', 'flash_success');
							$this->redirect(array('action' => 'index'));
						}
						
						$this->Region->rollback();
					}
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
				$this->Region->id = $id;
				
				//Si no hay informacion
				if (empty($this->request->data)) 
				{
					//Obtengo la info. a partir del id en cuestión
					$this->request->data = $this->Region->read();
					
					//Envio la información del usuario a consultar y para los selects
					$this->set('title_for_layout', 'Editando la region de '.$this->request->data['Region']['region_name']);
				} 
				//Si hay infomacion (pasado por post)
				else 
				{
					$this->Region->begin();
					
					if ($this->Region->save($this->request->data))
					{
						$this->Region->commit();
						
						$this->Session->setFlash('Se ha editado correctamente la región existente.', 'flash_success');
						$this->redirect(array('action' => 'index'));
					}
					
					$this->Region->rollback();
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
				$this->Region->begin();
				
				if ($this->Region->delete($id))
				{
					$this->Region->commit();
					
					$this->Session->setFlash('Se ha eliminado correctamente la región seleccionada.', 'flash_success');
					$this->redirect(array('action'=>'index'));
				}
				
				$this->Region->rollback();
			}
			else
			{
				$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
	}
?>