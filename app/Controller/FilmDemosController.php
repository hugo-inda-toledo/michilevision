<?php
	class FilmDemosController extends AppController
	{
		var $name = 'FilmDemos';
		var $helpers = array('Session', 'Html', 'Form','Time');
		//var $uses = array('FilmDemo','Management','User', 'CostCenterUser', 'CostCenter', 'Authorization', 'Badge', 'PurchaseOrderRequest', 'State', 'Sign', 'CorrelativeNumber', 'AttributeTable', 'Provider', 'MeasuringUnit', 'Budget');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		//var $paginate = array('limit' => 5, 'order' => array('RenderFund.created' => 'desc'));
		
		function index()
		{
			$this->set('filmDemos', $this->FilmDemo->find('all'));
		}
		
		function view($id = null)
		{
			if($id != null)
			{
				$this->FilmDemo->id = $id;
				$this->request->data = $this->FilmDemo->read();
			}
			else
			{
				$this->Session->setFlash('El Id del demo no puede ser nulo', 'flash_error');
				$this->redirect(array('controller' => 'film_demos', 'action' => 'index'));
			}
		}
		
		function add()
		{
			
		}
		
		function uploadFiles($namefile, $rutaupload, $usuario, $renderFundId) 
		{
			    //global $usuario;
                // $rutaupload con / (slash) al final
                $maximosize = 5000000; // 750 kb
                $archivo_size = $namefile['size'];
                $fechahora = date("Ymd-His");
                $ruta_temporal = $namefile['tmp_name'];
                $archivo_name = $fechahora.'_'.strtoupper($usuario)."-".str_replace(" ","_",trim($namefile["name"]));
                if ($archivo_size <= $maximosize)
                {
                               if (!move_uploaded_file($ruta_temporal, $rutaupload . $archivo_name))
                               {
                                               //return "Error al copiar el archivo $ruta_temporal a la ruta $rutaupload" . $archivo_name;
                                               return false;
                               }
                               else
                               {
												//return "Archivo $ruta_temporal subido con exito a la ruta $rutaupload" . $archivo_name;
												$validateLoad = $this->loadFileToDatabase($rutaupload, $archivo_name, $renderFundId);
											   
												if($validateLoad == true)
													return true;
                               }
                }
                else
                {
                               //return "El archivo $ruta_temporal supera los " . ($maximosize/1000) . " kb.";
                               return false;
                }
		}
		
		function mainMenu() {
		}
		
		function info(){
		
		}
	}
?>