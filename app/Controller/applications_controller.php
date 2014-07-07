<?php
	class ApplicationsController extends AppController
	{
		var $name = 'Applications';
		var $helpers = array('Ajax', 'Session', 'Html', 'Form','Time', 'Auth');
		var $uses = array('Application', 'User', 'System', 'Management', 'CostCenter', 'Authorization', 'Badge');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
	   
			
		/*public function add()
		{
			$resultadoManagements = $this->RequestAction('/external_functions/showManagements');
			$resultadoPositions = $this->RequestAction('/external_functions/showPositions');

			$this->set('selectManagements',$resultadoManagements); // llenar los select...
			$this->set('selectPositions', $resultadoPositions);
		
			if(!empty($this->data))
			{
				$this->data['User']['username'] = strtolower(trim(substr($this->data['User']['name'], 0, 1))).strtolower(trim($this->data['User']['first_lastname']));
				$this->data['User']['email'] = trim(strtolower($this->data['User']['email']));
				$this->data['User']['dni'] = trim(strtolower($this->data['User']['dni']));
				$this->data['User']['created_by_id'] = 3;
				
				$existsDni = $this->User->find('count',array('conditions'=>'LOWER(User.dni) = \''.$this->data['User']['dni'].'\''));
				
				if($existsDni > 0)
				{
					$this->Session->setFlash('El rut '.$this->data['User']['dni'].' ya se encuentra registrado.', 'flash_alert');
				}
				else
				{
					$existsEmail = $this->User->find('count',array('conditions'=>'LOWER(User.email) = \''.$this->data['User']['email'].'\''));
					
					
					if($existsEmail > 0)
					{
						$this->Session->setFlash('El correo electrónico '.$this->data['User']['email'].' ya se encuentra registrado.', 'flash_alert');
					}
					else // Si todos los datos están OK...
					{
						// Se prepara el string 'username' para pasarlo como parámetro a la función removeAccents()... 
						$_name = Sanitize::clean($this->data['User']['name'], array('encode' => false));	
						$_lastname = Sanitize::clean($this->data['User']['first_lastname'], array('encode' => false));
						$this->data['User']['username'] = str_replace(' ','',substr($_name, 0, 1).$_lastname);
									
						
						// Se llama a la función removeAccents() para generar un string saneado...
						$this->data['User']['username'] = $this->RequestAction('/external_functions/removeAccents/'.$this->data['User']['username']);
						
						//Se guarda el creador de la sesion activa
						$this->data['User']['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');
						
						//Se guarda clave de acceso aleatoria
						$passWithoutHash = $this->Password->generatePassword();
						
						//$passWithoutHash = "12345";
						
						//Se genera el hash de la clave por medio de SHA1
						$this->data['User']['password'] = Security::hash($passWithoutHash, null, true);
						
						//Se ejecuta la funcion para general Email
						
						//Creacion del usuario
						$this->User->create();
							
						//Si se guarda el usuario
						if($this->User->save($this->data))
						{
							$sendingWelcomeEmail = $this->_sendWelcomeMail($this->data['User']['name'], $this->data['User']['first_lastname'], $this->data['User']['username'], $passWithoutHash, $this->data['User']['email']);
					
							//Si se envia el mail
							if($sendingWelcomeEmail == true)
							{
								$this->Session->setFlash('Usuario Creado. Se ha enviado automaticamente al usuario un mail con los datos de inicio de seccion', 'flash_success');
								$this->redirect(array('action' => 'index'));
							}
							else
							{
								$this->Session->setFlash('No se ha enviado el mail ', 'flash_error');
								$this->redirect(array('action' => 'index'));
							}
						}
						else
						{
							$this->Session->setFlash('Por favor corrije los errores');
						}
					}	
				}
			}	
		}*/
		
			
		function index() 
		{
			$dataUsers = $this->Application->find('all', array('order' => 'User.created DESC', 'conditions' => array('User.active' => 1)));
			$this->set('users', $dataUsers);
		}
		
		/*function view($id) 
		{
			//Se obtiene el id del usuario
			$this->User->id = $id;
			
			//Se lee la info del usuario de la DB
			$this->data = $this->User->read();	
			$dataUser = $this->data;
			
			
			//Se valida si es un usuario de planta
			if($dataUser['User']['plant'] == 1)
				$dataUser['User']['plant'] = 'Si';
			else
				$dataUser['User']['plant'] = 'No';
			
			//Se valida si es un usuario activo
			if($dataUser['User']['active'] == 1)
				$dataUser['User']['active'] = 'Si';
			else
				$dataUser['User']['active'] = 'No';
			
			$dataUser['User']['birthday'] = $this->RequestAction('/external_functions/setDate/'.$dataUser['User']['birthday']);
			
			
			//Se obtiene arreglo con la info. del creador del registro.
			$dataCreator = $this->User->find('all', array('conditions' => array('User.id' => $dataUser['User']['created_by_id'])));
		
			
			//Se devuelve arreglos a la vista
			$this->set('user',   $dataUser);
			$this->set('dataCreator',   $dataCreator);
		}
		
		//Edición de un usuario en particular
		function edit($id) 
		{
			//Obtengo el id a consulltar
			$this->User->id = $id;
			
			//Si No hay infomacion (pasado por post)
			if (empty($this->data)) 
			{
				//Obtengo la info. a partir del id en cuestión
				$this->data = $this->User->read();
				
				//Ejecuta funciones para los selects
				$resultadoManagements = $this->RequestAction('/external_functions/showManagements');
				$resultadoPositions = $this->RequestAction('/external_functions/showPositions');
				
				$dataUser = $this->data;
				
				/*echo "<pre>";
				print_r($dataUser);
				echo "<pre>";

				//Envio la información del usuario a consultar y para los selects
				$this->set('selectManagements',$resultadoManagements);
				$this->set('selectPositions', $resultadoPositions);
				$this->set('user', $this->data);
			} 
			
			//Si hay infomacion (pasado por post)
			else 
			{
				//Se encapsula esa info. en un arreglo.
				$dataUser = $this->data;
				$dataUser['User']['email'] = trim(strtolower($dataUser['User']['email']));
				
				//Si la info. se guardo
				if ($this->User->save($dataUser))
				{
					//Envia un flash con mensaje de confrimacion y se redirecciona al index
					$this->Session->setFlash('Se ha editado correctamente un usuario existente.', 'flash_success');
					$this->redirect(array('action' => 'index'));
				}
			}
		}
		
		function _sendWelcomeMail($name, $first_lastname, $username, $password, $mail) 
		{

			$body = "Estimado le informamos que si clave es: ".$password;
			

			
			$this->Email->sendAs = 'both';
			$this->Email->layout = 'default';
			$this->Email->template = 'welcome';
			$this->Email->delivery = 'debug';
			/*$this->Email->smtpOptions = array(
														'port' => '465',
														'timeout' => '30',
														'host' => 'ssl://smtp.gmail.com',
														'username' => 'hugo.inda@gmail.com',
														'password' => 'hugoindatoledo',
														'auth' => true
			);
			$this->Email->to = $mail;
			$this->Email->subject = $name.', Bienvenido a MiChilevision.cl';
			$this->Email->replyTo = 'hugo.inda@chilevision.cl';
			$this->Email->from = 'noreply@michilevision.cl';
			$this->Email->from = "MiChilevision Support Team <noreply@michilevision.cl>";
			$this->set('body', $body);
			 
			/* echo "<pre>";
			 print_r($this->Email);
			 echo "</pre>";
			 $sent = $this->Email->send();
			 
			if($sent)
			{
				return true;
			}	
			else
			{
				return false;
			}
		}*/
}
?>