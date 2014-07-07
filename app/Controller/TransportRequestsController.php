<?php
	class TransportRequestsController extends AppController
	{
		var $name = 'TransportRequests';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('TransportRequest', 'User', 'Management', 'CostCenter', 'CostCenterUser',  'TransportType', 'TransportCompany', 'Region', 'Address', 'Management', 'Headquarter');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		function index()
		{			
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/7/index") == true)
			{
				$this->paginate = array('TransportRequest' => array('limit' => 10, 'order' => array('TransportRequest.created' => 'DESC')));
				$this->set('transportRequests', $this->paginate());
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function view($id = null)
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/7/view") == true)
			{
				if($id != null)
				{
					$this->TransportRequest->id = $id;
					$this->request->data = $this->TransportRequest->read();
				}
				else
				{
					$this->Session->setFlash('El Id del demo no puede ser nulo', 'flash_error');
					$this->redirect(array('controller' => 'film_demos', 'action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function add()
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/7/add") == true)
			{
				$dataUser = $this->User->find('first', array('conditions' =>array('User.id' => $this->Auth->user('id'))));
				$selectCostCenter = array();
				$selectTransportTypes= array();
				$selectAddressBook= array();
				
				foreach($dataUser['CostCenter'] as $costCenter)
				{
					if($costCenter['CostCenterUser']['system_id'] == 7)
					{
						$selectCostCenter[$costCenter['id']] = $costCenter['cost_center_name']." (".$costCenter['cost_center_code'].")";
					}
				}
				
				$transportTypes = $this->TransportType->find('all', array('order' => 'TransportType.transport_type ASC'));
				
				foreach($transportTypes as $type)
				{
					$selectTransportTypes[$type['TransportType']['id']] = $type['TransportType']['transport_type']." (Capacidad para ".$type['TransportType']['capacity']." personas)";
				}
				
				$allUsers = $this->User->find('all', array('conditions' => array('User.management_id' => $dataUser['Management']['id'])));
				
				/*echo "<pre>";
				print_r($allUsers);
				echo "</pre>";*/
				
				foreach($allUsers as $user)
				{
					if(!empty($user['Address']))
					{
						foreach($user['Address'] as $address)
						{
							$selectAddressBook[$address['id']] = $user['User']['name']." ".$user['User']['first_lastname']." (".$address['full_address'].")";
							
							if($dataUser['Management']['user_id'] == $user['User']['id'])
							{
								$managementAddresses[$address['id']] = $address['full_address'];
							}
						}
					}
				}
				
				//************Envio de datos para formulario de ticket nuevo*****************//
				$this->set('dataUser', $dataUser);
				$this->set('costCenters', $selectCostCenter);
				$this->set('transports', $selectTransportTypes);
				//***********************************************************************************//
				
				//************Envio de datos para formulario de direccion nueva*****************//
				$this->set('regions', $this->Region->find('list', array('fields' => array('Region.id', 'Region.region_name'))));
				$users = $this->RequestAction('/external_functions/showUsuarios');
				$this->set('users', $users);
				//***********************************************************************************//
				
				
				//************Envio de datos para formulario de libreta de direcciones*****************//
				$this->set('bookAddress', $selectAddressBook);
				$this->set('managementAddresses', $managementAddresses);
				//***********************************************************************************//
				
				if(!empty($this->request->data))
				{
					echo "<pre>";
					print_r($this->request->data);
					echo "</pre>";
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function gettingCompanies($transport_id = null)
		{
			$companies= array();
			
			if($transport_id != null)
			{
				$companies = $this->TransportCompany->find('list', array('conditions' => array('TransportCompany.transport_type_id' => $transport_id, 'TransportCompany.active' => 1), 'fields' => array('TransportCompany.id', 'TransportCompany.company_name')));
			}
			
			$this->set('companies', $companies);
		}
		
		
		function uploadFiles($namefile, $rutaupload, $usuario, $renderFundId) 
		{
			$maximosize = 5000000; // 750 kb
			$archivo_size = $namefile['size'];
			$fechahora = date("Ymd-His");
			$ruta_temporal = $namefile['tmp_name'];
			$archivo_name = $fechahora.'_'.strtoupper($usuario)."-".str_replace(" ","_",trim($namefile["name"]));
			if ($archivo_size <= $maximosize)
			{
			   if (!move_uploaded_file($ruta_temporal, $rutaupload . $archivo_name))
			   {
					return false;
			   }
			   else
			   {
					$validateLoad = $this->loadFileToDatabase($rutaupload, $archivo_name, $renderFundId);
				   
					if($validateLoad == true)
						return true;
			   }
			}
			else
			{
				return false;
			}
		}
		
		function mainMenu() 
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/7/mainMenu") == true)
			{
				
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function verifiedPermissions($userId)
		{
			$managements = $this->Management->find('all');
			
			foreach($managements as $management)
			{
				if($management['Management']['user_id'] == $userId)
				{
					return true;
					break;
				}
			}
			
			$headquarters = $this->Headquarter->find('all', array('conditions' => array('Headquarter.active' => 1)));
			
			foreach($headquarters as $headquarter)
			{
				if($headquarter['Headquarter']['user_id'] == $userId)
				{
					return true;
					break;
				}
			}
			
			return false;
		}
	}
?>