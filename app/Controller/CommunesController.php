<?php
	class CommunesController extends AppController
	{
		var $name = 'Communes';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('Commune', 'Province', 'Region');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		function index()
		{
			if($this->Auth->user('admin') == 1)
			{
				$this->paginate = array('Commune' => array('limit' => 40, 'order' => array('Commune.id' => 'ASC')));
				$this->set('communes', $this->paginate());
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
					$this->Commune->id = $id;
					$this->request->data = $this->Commune->read();
				}
				else
				{
					$this->Session->setFlash('El Id de la comuna no puede ser nulo', 'flash_error');
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
					if($this->request->data['Commune']['region_id'] == "")
					{
						$this->Session->setFlash('Debes seleccionar una region asociada a la comuna.', 'flash_alert');
						return false;
					}
					
					if($this->request->data['Commune']['province_id'] == "")
					{
						$this->Session->setFlash('Debes seleccionar una provincia asociada a la comuna.', 'flash_alert');
						return false;
					}
					
					$validateProvince = $this->Commune->find('first', array('conditions' => array('Commune.commune_name' => $this->request->data['Commune']['commune_name'], 'Commune.region_id' => $this->request->data['Commune']['region_id'], 'Commune.province_id' => $this->request->data['Commune']['province_id'])));
					
					if($validateProvince != false)
					{
						$this->Session->setFlash('Esta comuna ya existe.', 'flash_alert');
						return false;
					}
					else
					{
						$this->Commune->begin();
						
						if($this->Commune->save($this->request->data))
						{
							$this->Commune->commit();
							$this->Session->setFlash('Comuna Agregada Correctamente.', 'flash_success');
							$this->redirect(array('action' => 'index'));
						}
						
						$this->Commune->rollback();
					}
				}
			}
			else
			{
				$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function gettingProvinces($regionId = null)
		{
			if($regionId != null)
			{
				$provinces = $this->Province->find('list', array('conditions' => array('Province.region_id' => $regionId), 'fields' => array('Province.id', 'Province.province_name')));
				
				$this->set('provinces', $provinces);
			}
		}
		
		function delete($id = null) 
		{
			if($this->Auth->user('admin') == 1)
			{
				$this->Commune->begin();
				
				if ($this->Commune->delete($id))
				{
					$this->Commune->commit();
					
					$this->Session->setFlash('Se ha eliminado correctamente la comuna seleccionada.', 'flash_success');
					$this->redirect(array('action'=>'index'));
				}
				
				$this->Commune->rollback();
			}
			else
			{
				$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
	}
?>