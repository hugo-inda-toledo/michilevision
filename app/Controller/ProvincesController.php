<?php
	class ProvincesController extends AppController
	{
		var $name = 'Provinces';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('Province', 'Region', 'Commune');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		function index()
		{
			if($this->Auth->user('admin') == 1)
			{
				$this->paginate = array('Province' => array('limit' => 20, 'order' => array('Region.id' => 'ASC')));
				$this->set('provinces', $this->paginate());
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
					$this->Province->id = $id;
					$this->request->data = $this->Province->read();
				}
				else
				{
					$this->Session->setFlash('El Id de la provincia no puede ser nulo', 'flash_error');
					$this->redirect(array('controller' => 'provinces', 'action' => 'index'));
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
				$this->set('regions', $this->Region->find('list', array('fields' => array('Region.id', 'Region.region_name'))));
				
				if(!empty($this->request->data))
				{
					if($this->request->data['Province']['region_id'] == "")
					{
						$this->Session->setFlash('Debes seleccionar una region asociada a la provincia.', 'flash_alert');
						return false;
					}
					$validateProvince = $this->Province->find('first', array('conditions' => array('Province.province_name' => $this->request->data['Province']['province_name'], 'Province.region_id' => $this->request->data['Province']['region_id'])));
					
					if($validateProvince != false)
					{
						$this->Session->setFlash('Esta provincia ya existe.', 'flash_alert');
						return false;
					}
					else
					{
						$this->Province->begin();
						
						if($this->Province->save($this->request->data))
						{
							$this->Province->commit();
							$this->Session->setFlash('Provincia Agregada Correctamente.', 'flash_success');
							$this->redirect(array('action' => 'index'));
						}
						
						$this->Province->rollback();
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
				$this->Province->id = $id;
				
				$this->set('regions', $this->Region->find('list', array('fields' => array('Region.id', 'Region.region_name'))));
				
				//Si no hay informacion
				if (empty($this->request->data)) 
				{
					//Obtengo la info. a partir del id en cuestión
					$this->request->data = $this->Province->read();
					
					//Envio la información del usuario a consultar y para los selects
					$this->set('title_for_layout', 'Editando la provincia de '.$this->request->data['Province']['province_name']);
				} 
				//Si hay infomacion (pasado por post)
				else 
				{
					$this->Province->begin();
					
					if ($this->Province->save($this->request->data))
					{
						$this->Province->commit();
						
						$this->Session->setFlash('Se ha editado correctamente la provincia existente.', 'flash_success');
						$this->redirect(array('action' => 'index'));
					}
					
					$this->Province->rollback();
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
				$this->Province->begin();
				
				if ($this->Province->delete($id))
				{
					$this->Province->commit();
					
					$this->Session->setFlash('Se ha eliminado correctamente la provincia seleccionada.', 'flash_success');
					$this->redirect(array('action'=>'index'));
				}
				
				$this->Province->rollback();
			}
			else
			{
				$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
	}
?>