<?php
	class AddressesController extends AppController
	{
		var $name = 'Addresses';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('Address', 'Region', 'Province', 'Commune', 'AddressUser');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		function index()
		{
			if($this->Auth->user('admin') == 1)
			{
				$this->paginate = array('Address' => array('limit' => 40, 'order' => array('Address.id' => 'ASC')));
				$this->set('addresses', $this->paginate());
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
					$this->Address->id = $id;
					$this->request->data = $this->Address->read();
				}
				else
				{
					$this->Session->setFlash('El Id de la dirección no puede ser nulo', 'flash_error');
					$this->redirect(array('controller' => 'film_demos', 'action' => 'index'));
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
				$users = $this->RequestAction('/external_functions/showUsuarios');
				$this->set('users', $users);
				
				if(!empty($this->request->data))
				{
					$commune = $this->Commune->find('first', array('conditions' => array('Commune.id' => $this->request->data['Address']['commune_id']), 'fields' => array('Commune.commune_name')));
					$region = $this->Region->find('first', array('conditions' => array('Region.id' => $this->request->data['Address']['region_id']), 'fields' => array('Region.region_name')));
					
					$address = $this->request->data['Address']['address_name']." ".$this->request->data['Address']['number'].", ".$commune['Commune']['commune_name'].", ".$region['Region']['region_name'];
					$addressToTitle = $this->request->data['Option']['street_type']." ".$this->request->data['Address']['address_name']." ".$this->request->data['Address']['number'].", ".$commune['Commune']['commune_name'].", ".$region['Region']['region_name'];
					$address = str_replace(" ", "+", $address);

					$json = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=".$address."&sensor=false");

					$json = json_decode($json, true);

					
					$this->request->data['Address']['latitude'] = $json['results'][0]['geometry']['location']['lat'];
					$this->request->data['Address']['longitude'] = $json['results'][0]['geometry']['location']['lng'];
					$this->request->data['Address']['full_address'] = $addressToTitle;


					$this->Address->begin();
					
					if($this->Address->save($this->request->data['Address']))
					{
						if($this->request->data['Option']['for_user'] == 1)
						{
							$last_id = $this->Address->getLastInsertId(); 
							
							$dataAssign ['AddressUser']['user_id'] = $this->request->data['User']['id'];
							$dataAssign ['AddressUser']['address_id'] = $last_id;
							
							$this->AddressUser->begin();
							
							if($this->AddressUser->save($dataAssign))
							{
								$this->AddressUser->commit();
								$this->Address->commit();
								$this->Session->setFlash('Se ha guardado la dirección en la libreta correctamente y se le ha asignado al usuario seleccionado.', 'flash_success');
								$this->redirect(array('action' => 'index'));
							}
							
							$this->AddressUser->rollback();
							$this->Address->rollback();
						}
						
						if($this->request->data['Option']['for_user'] == 0)
						{
							$this->Address->commit();
							$this->Session->setFlash('Se ha guardado la dirección en la libreta correctamente', 'flash_success');
							$this->redirect(array('action' => 'index'));
						}
					}
					
					$this->Address->rollback();
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
			$this->Address->begin();
			
			if ($this->Address->delete($id))
			{
				$relations = $this->AddressUser->find('all', array('conditions' => array('AddressUser.address_id'  => $id)));
				
				if($relations != false)
				{
					$this->AddressUser->begin();
					
					if($this->AddressUser->saveAll($relations))
					{
						$this->AddressUser->commit();
						$this->Address->commit();
						
						$this->Session->setFlash('Se ha eliminado correctamente la dirección y las asociaciones correspondientes.', 'flash_success');
						$this->redirect(array('action'=>'index'));
					}
					
					$this->AddressUser->rollback();
				}
				
				$this->Address->commit();
				
				$this->Session->setFlash('Se ha eliminado correctamente la dirección.', 'flash_success');
				$this->redirect(array('action'=>'index'));
			}
			
			$this->Address->rollback();
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
		
		function gettingProvinces($regionId = null)
		{
			$this->set('provinces', $this->Province->find('list', array('conditions' => array('Province.region_id' => $regionId), 'fields' => array('Province.id', 'Province.province_name'))));
		}
		
		function gettingCommunes($provinceId = null)
		{
			if($provinceId != null)
			{
				$this->set('communes', $this->Commune->find('list', array('conditions' => array('Commune.province_id' => $provinceId), 'fields' => array('Commune.id', 'Commune.commune_name'))));
			}
		}
	}
?>