<?php
	class ContractServicesController extends AppController
	{
		var $name = 'ContractServices';
		var $helpers = array('Session', 'Html', 'Form','Time');
		//var $uses = array('TransportRequest', 'User', 'Management', 'CostCenter', 'CostCenterUser',  'TransportType', 'TransportCompany', 'Region', 'Address', 'Management', 'Headquarter');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		function mainMenu() 
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/4/mainMenu") == true)
			{
				
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function index()
		{			
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/4/index") == true)
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
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/4/view") == true)
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
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/4/add") == true)
			{
				
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
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
	}
?>