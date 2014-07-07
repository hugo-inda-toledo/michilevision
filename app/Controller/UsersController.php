<?php

	App::uses('CakeEmail', 'Network/Email');
	App::uses('Sanitize', 'Utility');
	App::uses('Security', 'Utility');
	
	class UsersController extends AppController
	{
		var $name = 'Users';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('User','Management','Position', 'CostCenterUser', 'Headquarter', 'CostCenter', 'UserPermission', 'UserSystem', 'UserProfile', 'System', 'Permission', 'ProfilePermission', 'Setting');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		var $allowCookie = true;
		var $cookieTerm = '0';
		
		public function beforeFilter()
		{
			parent::beforeFilter();
			$this->Auth->allow('login');
			$this->Auth->allow('forgot_password');
			$this->Auth->allow('reset_password_token');
			$this->Auth->allow('__generatePasswordToken');
			$this->Auth->allow('__sendForgotPasswordEmail');
			$this->Auth->allow('__validToken');
			$this->Auth->allow('__sendPasswordChangedEmail');
		}
		
		/*public function isAuthorized() 
		{
			if ($this->Auth->user('role') != 'admin') 
			{
				$this->Auth->deny('index', 'view', 'add', 'edit', 'find');//put your all non-admin actions separated by comma
			} 
			else 
			{
				$this->Auth->allow('*');//put your all admin actions separated by comma
			}
		}*/
		
		/*public function login() 
		{
			if($this->Auth->user() == '')
			{
				$this->set('title_for_layout', 'Iniciar Sesión');
				
				if($this->request->is('post'))
				{
					if($this->Auth->login())
					{
						if($this->Auth->user('active') == 1)
						{
							if ($this->request->data['User']['rememberMe'] == 1) 
							{
								// After what time frame should the cookie expire
								$cookieTime = "1 week"; // You can do e.g: 1 week, 17 weeks, 14 days
	 
								// remove "remember me checkbox"
								unset($this->request->data['User']['rememberMe']);
					 
								// hash the user's password
								$this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
					 
								// write the cookie
								$this->Cookie->write('rememberMe', $this->request->data['User'], true, $cookieTime);
							}
							
							return $this->redirect($this->Auth->redirect());
						}
						else
						{
							$this->Session->setFlash('La cuenta esta desactivada.');
							$this->redirect($this->Auth->logout());
						}
					}
					else
					{
						$this->Session->setFlash('Tu email o contraseña son incorrectos');
					}
				}
			}
			else
			{
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}*/
		
		public function login()
		{
			if($this->Auth->user() == '')
			{
				$this->set('title_for_layout', 'Iniciar Sesión');
				
				if($this->request->is('post'))
				{
					$username = $this->request->data['User']['username'];
					$password = Security::hash($this->data['User']['password'], 'sha1', false);

					$user = $this->User->find('first', array('conditions' => array('User.username' => $username, 'User.password' => $password)));
					
					if($user !== false)
					{
						if($this->Auth->login())
						{
							if($this->Auth->user('active') == 1)
							{
								if ($this->request->data['User']['rememberMe'] == 1) 
								{
									// After what time frame should the cookie expire
									$cookieTime = "1 week"; // You can do e.g: 1 week, 17 weeks, 14 days
		 
									// remove "remember me checkbox"
									unset($this->request->data['User']['rememberMe']);
						 
									// hash the user's password
									$this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
						 
									// write the cookie
									$this->Cookie->write('rememberMe', $this->request->data['User'], true, $cookieTime);
								}
								
								return $this->redirect($this->Auth->redirect());
							}
							else
							{
								$this->Session->setFlash('La cuenta esta desactivada.');
								$this->redirect($this->Auth->logout());
							}
						}
					}
					else
					{
						$this->Session->setFlash('Tu email o contraseña son incorrectos');
						$this->redirect(array('controller' => 'users', 'action' => 'login'));
					}
				}
			}
			else
			{
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function forgetpwd() 
		{
			$this->Session->setFlash('Has cerrado la sesión.', 'flash_success');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}	
		

		public function logout()
		{
			$this->Cookie->delete('rememberMe');
			$this->redirect($this->Auth->logout());
		}
		
	/***************************************************************************************************************************
	************************************************Recuperacion de contraseña*******************************************
	****************************************************************************************************************************/
		
		function forgot_password() 
		{
			$this->set('title_for_layout', 'Recuperación de contraseña');
			
			if (!empty($this->data)) 
			{
				$user = $this->User->findByEmail($this->data['User']['email']);
				if (empty($user)) 
				{
					$this->Session->setflash('Lo siento, el correo electronico proporcionado no existe en el sistema', 'flash_alert');
					$this->redirect('/users/forgot_password');
				} 
				else 
				{
					if($user['User']['active'] != 1)
					{
						$this->Session->setflash('La direccion de correo electronico proporcionada esta deshabilitada del sistema.', 'flash_alert');
					}
					else
					{
						$user = $this->__generatePasswordToken($user);
						if ($this->User->save($user) && $this->__sendForgotPasswordEmail($user['User']['id'])) 
						{
							$this->Session->setflash('Las instrucciones para cambiar la clave fueron enviadas al correo electronico. Tienes 60 minutos para completar la solicitud.', 'flash_success');
							$this->redirect('/users/login');
						}
					}
				}
			}
		}

		/**
		* Allow user to reset password if $token is valid.
		* @return
		*/
	 
		function reset_password_token($reset_password_token = null) 
		{
			$this->set('title_for_layout', 'Cambio de contraseña');
			
			if (empty($this->request->data)) 
			{
				$this->request->data = $this->User->findByResetPasswordToken($reset_password_token);
				if (!empty($this->request->data['User']['reset_password_token']) && !empty($this->request->data['User']['token_created_at']) && $this->__validToken($this->request->data['User']['token_created_at'])) 
				{
					$this->request->data['User']['id'] = null;
					$_SESSION['token'] = $reset_password_token;
				} 
				else 
				{
					$this->Session->setflash('La solicitud de restablecimiento de contraseña ha caducado o no es válida.', 'flash_alert');
					$this->redirect('/users/login');
				}
			} 
			else 
			{
				/*if ($this->request->data['User']['reset_password_token'] != $_SESSION['token']) 
				{
					$this->Session->setflash('La solicitud de restablecimiento de contraseña ha caducado o no es válida.', 'flash_alert');
					$this->redirect('/users/login');
				}*/

				if($this->request->data['User']['new_password'] != $this->request->data['User']['confirm_password'])
				{
					$this->Session->setflash('Las claves deben ser iguales', 'flash_alert');
					$this->redirect(array('action' => 'reset_password_token/'.$this->request->data['User']['reset_password_token']));
				}
				else
				{
					$dataUser = $this->User->find('first', array('conditions' => array('User.reset_password_token' => $this->request->data['User']['reset_password_token'])));
					
					$dataUser['User']['password'] = Security::hash($this->request->data['User']['new_password'], null, true);
					
					
					if ($this->User->save($dataUser, array('validate' => 'only'))) 
					{
						$dataUser['User']['reset_password_token'] = null;
						$dataUser['User']['token_created_at'] = null;
						
						if ($this->User->save($dataUser) && $this->__sendPasswordChangedEmail($dataUser['User']['id'])) 
						{
							unset($_SESSION['token']);
							$this->Session->setflash('Su contraseña se ha cambiado correctamente. Inicia sesión para continuar.', 'flash_success');
							$this->redirect('/users/login');
						}
					}
				}
			}
		}

		/**
		* Generate a unique hash / token.
		* @param Object User
		* @return Object User
		*/
		function __generatePasswordToken($user) 
		{
			if (empty($user)) 
			{
				return null;
			}

			// Generate a random string 100 chars in length.
			$token = "";
			for ($i = 0; $i < 100; $i++) 
			{
				$d = rand(1, 100000) % 2;
				$d ? $token .= chr(rand(33,79)) : $token .= chr(rand(80,126));
			}
	
			(rand(1, 100000) % 2) ? $token = strrev($token) : $token = $token;
	
			// Generate hash of random string
			$hash = Security::hash($token, 'sha256', true);
			
			for ($i = 0; $i < 20; $i++) 
			{
				$hash = Security::hash($hash, 'sha256', true);
			}

			$user['User']['reset_password_token'] = $hash;
			$user['User']['token_created_at']     = date('Y-m-d H:i:s');

			return $user;
		}

		/**
		* Validate token created at time.
		* @param String $token_created_at
		* @return Boolean
		*/
		function __validToken($token_created_at) 
		{
			$expired = strtotime($token_created_at) + 3600;
			$time = strtotime("now");
			if ($time < $expired) 
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		/**
		* Sends password reset email to user's email address.
		* @param $id
		* @return
		*/
		function __sendForgotPasswordEmail($id = null) 
		{
			if (!empty($id)) 
			{
				$this->User->id = $id;
				$User = $this->User->read();

				$this->Email->to 			= $User['User']['email'];
				$this->Email->subject 	= 'Solicitud de cambio de contraseña';
				$this->Email->replyTo 	= 'do-not-reply@michilevision.cl';
				$this->Email->from 		= 'MiChilevision Support Team <do-not-reply@michilevision.cl>';
				$this->Email->template = 'reset_password_request';
				$this->Email->sendAs 	= 'both';
				$this->Email->delivery = 'smtp';
				$this->Email->charset = 'utf-8';
				$this->Email->smtpOptions = array(
														//'port' => '25',
														//'timeout' => '30',
														'host' => 'argsmtpgw.turner.com'
														//'username' => '',
														//'password' => '',
														//'auth' => false
				);
				$this->set('User', $User);
				$this->Email->send();

				return true;
			}
			return false;
		}

		/**
		* Notifies user their password has changed.
		* @param $id
		* @return
		*/
		
		function __sendPasswordChangedEmail($id = null) 
		{
			if (!empty($id)) 
			{
				$this->User->id = $id;
				$User = $this->User->read();
	
				$this->Email->to 			= $User['User']['email'];
				$this->Email->subject 	= 'Contraseña cambiada';
				$this->Email->replyTo 	= 'do-not-reply@michilevision.cl';
				$this->Email->from 		= 'MiChilevision Support Team <do-not-reply@michilevision.cl>';
				$this->Email->template = 'password_reset_success';
				$this->Email->sendAs 	= 'both';
				$this->Email->delivery = 'smtp';
				$this->Email->charset = 'utf-8';
				$this->Email->smtpOptions = array(
														//'port' => '25',
														//'timeout' => '30',
														'host' => 'argsmtpgw.turner.com'
														//'username' => '',
														//'password' => '',
														//'auth' => false
				);
				$this->set('User', $User);
				$this->Email->send();

				return true;
			}
			return false;
		}
		
		function __newAccount($name, $first_lastname, $username, $password, $mail) 
		{
			if (!empty($name) && !empty($first_lastname) && !empty($username) && !empty($password) &&  !empty($email)) 
			{
				$this->Email->to 			= $email;
				$this->Email->subject 	= $name.', Bienvenido a miChilevision.cl';
				$this->Email->replyTo 	= 'do-not-reply@michilevision.cl';
				$this->Email->from 		= 'MiChilevision Support Team <do-not-reply@michilevision.cl>';
				$this->Email->template = 'welcome';
				$this->Email->sendAs 	= 'both';
				$this->Email->delivery = 'smtp';
				$this->Email->charset = 'utf-8';
				$this->Email->smtpOptions = array(
														//'port' => '25',
														//'timeout' => '30',
														'host' => '192.168.0.3'
														//'username' => '',
														//'password' => '',
														//'auth' => false
				);
				$this->set(compact($name, $first_lastname, $username, $password, $email));
				$this->Email->send();

				return true;
			}
			return false;
		}
	
	/***************************************************************************************************************************
	****************************************************************************************************************************
	****************************************************************************************************************************/
			
		function index() 
		{
			if($this->Auth->user('admin') == 1)
			{
				$this->paginate = array('User' => array('limit' => 30, 'order' => array('User.created' => 'DESC')));
				$dataUsers = $this->paginate();
				
				for($x=0; $x< count($dataUsers); $x++)
				{
					if($dataUsers[$x]['User']['email_confirm'] == 1)
						$dataUsers[$x]['User']['email_confirm'] = "Si";
					else
					{
						$dataUsers[$x]['User']['email_confirm'] = "No";
						$dataUsers[$x]['Pass']['pass_withoutHash'] = $this->Password->generatePassword();
					}
				}
				
				$this->set('users', $dataUsers);
				$this->set('title_for_layout', 'Usuarios');
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
				$this->User->id = $id;
				
				//Se lee la info del usuario de la DB
				$this->data = $this->User->read();
				
				
				//Se valida si es un usuario de planta
				if($this->data['User']['plant'] == 1)
					$this->request->data['User']['plant'] = 'Si';
				else
					$this->data['User']['plant'] = 'No';
				
				//Se valida si es un usuario activo
				if($this->data['User']['active'] == 1)
					$this->request->data['User']['active'] = 'Si';
				else
					$this->request->data['User']['active'] = 'No';
				
				//Le inserto el nombre del sistema en la informacion
				for($x=0; $x < count($this->request->data['UserSystem']); $x++)
				{
					$getSystemName = $this->System->find('first', array('conditions' => array('System.id' => $this->request->data['UserSystem'][$x]['system_id'])));
					
					 $this->request->data['UserSystem'][$x]['system_name'] = $getSystemName['System']['system_name'];
					 $this->request->data['UserSystem'][$x]['css_class_url'] = $getSystemName['System']['css_class_url'];
				}
				
				//Le inserto el/los nombre/s y centro de costo/s a la informacion
				for($x=0; $x < count($this->request->data['CostCenterUser']); $x++)
				{
					$getCostCenterName = $this->CostCenter->find('first', array('conditions' => array('CostCenter.id' => $this->request->data['CostCenterUser'][$x]['cost_center_id']), 'fields' => array('CostCenter.cost_center_name', 'CostCenter.cost_center_code')));
					 $this->request->data['CostCenterUser'][$x]['cost_center_name'] = $getCostCenterName['CostCenter']['cost_center_name'];
					 $this->request->data['CostCenterUser'][$x]['cost_center_code'] = $getCostCenterName['CostCenter']['cost_center_code'];
				}
				
				//Le inserto los datos de los perfiles a la informacion
				for($x=0; $x < count($this->data['UserProfile']); $x++)
				{
					$getProfileData = $this->Profile->find('first', array('conditions' => array('Profile.id' => $this->request->data['UserProfile'][$x]['profile_id'])));
					$this->request->data['UserProfile'][$x]['profile_name'] = $getProfileData['Profile']['profile_name'];
					$this->request->data['UserProfile'][$x]['description']   = $getProfileData['Profile']['description'];
					$this->request->data['UserProfile'][$x]['System']  = $getProfileData['System'];
					
					//$getPermissionsData = $this->ProfilePermission->find('all', array('conditions' => array('ProfilePermission.profile_id' => $this->data['UserProfile'][$x]['profile_id'])));
				}
				
				
				$this->set('title_for_layout', $this->data['User']['name'].' '.$this->request->data['User']['first_lastname']);
			}
			else
			{
				$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
			
		public function add()
		{
			if($this->Auth->user('admin') == 1)
			{
				$resultadoManagements = $this->RequestAction('/external_functions/showManagements');
				//$resultadoManagements = $this->Management->find('list', array('fields' => array('Management.id', 'Management.management_name')));
				$resultadoPositions = $this->RequestAction('/external_functions/showPositions');
				//$resultadoHeadquarters = $this->Headquarter->find('list', array('fields' => array('Headquarter.id', 'Headquarter.headquarter_name')));
				
				$resultadoHeadquarters = $this->RequestAction('/external_functions/showHeaquarters');
				
				
				
				$this->set('selectManagements',$resultadoManagements);
				$this->set('selectPositions', $resultadoPositions);
				$this->set('selectHeadquarters', $resultadoHeadquarters);
				$this->set('title_for_layout', 'Agregar Usuario');
			
				if(!empty($this->request->data))
				{
					$this->request->data['User']['username'] = trim(strtolower($this->request->data['User']['email']));
					$this->request->data['User']['email'] = trim(strtolower($this->request->data['User']['email']));
					$this->request->data['User']['dni'] = trim(strtolower($this->request->data['User']['dni']));
					
					
					$existsDni = $this->User->find('count',array('conditions'=>'LOWER(User.dni) = \''.$this->request->data['User']['dni'].'\''));
					
					if($existsDni > 0)
					{
						$this->Session->setFlash('El rut '.$this->request->data['User']['dni'].' ya se encuentra registrado.', 'flash_alert');
						return false;
					}
					else
					{
						$existsEmail = $this->User->find('count',array('conditions'=>'LOWER(User.email) = \''.$this->request->data['User']['email'].'\''));
						
						if($existsEmail > 0)
						{
							$this->Session->setFlash('El correo electrónico '.$this->request->data['User']['email'].' ya se encuentra registrado.', 'flash_alert');
							return false;
						}
						else // Si todos los datos estan OK...
						{
							
							if($this->request->data['User']['management_id'] == 0)
							{
								$this->Session->setFlash('Debes asociar una gerencia al usuario', 'flash_alert');
								return false;
							}
							// Se prepara el string 'username' para pasarlo como parámetro a la función removeAccents()... 
							/*$_name = Sanitize::clean($this->request->data['User']['name'], array('encode' => false));	
							$_lastname = Sanitize::clean($this->request->data['User']['first_lastname'], array('encode' => false));
							$this->request->data['User']['username'] = str_replace(' ','',substr($_name, 0, 1).$_lastname);*/
										
							
							// Se llama a la función removeAccents() para generar un string saneado...
							//$this->request->data['User']['username'] = $this->RequestAction('/external_functions/removeAccents/'.$this->request->data['User']['username']);
							
							
							//Se setea por defecto que el usuario este activo.
							$this->request->data['User']['active'] = 1;
							
							//Se setea por defecto que el usuario no halla visto el tour
							$this->request->data['User']['tour_validated'] = 0;
							
							//Se setea por defecto que el usuario no halla confirmado el email
							$this->request->data['User']['email_confirm'] = 0;
							
							//El token de perdida de contraseña entra nulo
							$this->request->data['User']['reset_password_token'] = '';
							
							//La fecha de creacion del token entra nula
							$this->request->data['User']['token_created_at'] = '0000-00-00 00:00:00';
							
							//Se guarda el creador de la sesion activa
							$this->request->data['User']['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');
							
							//Se guarda clave de acceso aleatoria
							$passWithoutHash = $this->Password->generatePassword();
							//$passWithoutHash = "123456";
							
							//Se genera el hash de la clave por medio de SHA1
							//$this->request->data['User']['password'] = AuthComponent::password($passWithoutHash);
							$this->request->data['User']['password'] = Security::hash($passWithoutHash, 'sha1');
							
							if($this->request->data['User']['admin'] == 1)
							{
								$this->request->data['User']['role'] = "admin";
							}
							else
							{
								$this->request->data['User']['role'] = "regular";
							}

							//$this->request->data['User']['password'] = $passWithoutHash;

							
							$setting['Setting']['receive_notifications'] = 1;
							$setting['Setting']['receive_notifications_with_sound'] = 1;
							$setting['Setting']['receive_email_notifications'] = 0;
							
							$this->Setting->begin();
							
							if($this->Setting->save($setting))
							{	
								$setting_id = $this->Setting->getLastInsertId(); 

								$this->request->data['User']['setting_id'] = $setting_id;
								
								/*echo "<pre>";
								print_r($this->request->data);
								echo "</pre>";*/
									
								$this->User->begin();
								
								//Si se guarda el usuario
								if($this->User->save($this->request->data['User']))
								{
									//Se ejecuta la funcion para general Email
									if($this->__newAccount($this->request->data['User']['name'], $this->request->data['User']['first_lastname'], $this->request->data['User']['username'], $passWithoutHash, $this->request->data['User']['email']));
									{
										// Se insertan los nuevos datos
										$this->User->commit();
										$this->Setting->commit();
										$this->Session->setFlash('Usuario creado. Se ha enviado automáticamente al usuario un correo con sus datos de inicio de sesión.', 'flash_success');
										$this->redirect(array('action' => 'index'));
									}
									
									$this->User->rollback();
									$this->Setting->rollback();
									$this->Session->setFlash('Error al enviar correo electronico. Por favor intente nuevamente.');
								}
								else
								{
									$this->User->rollback();
									$this->Setting->rollback();
									$this->Session->setFlash('Error al crear el usuario en la base de datos. Por favor intente nuevamente.');
								}
							}
							
							$this->Setting->rollback();
						}
					}
				}
			}
			else
			{
				$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		//Edición de un usuario en particular
		function edit($id = null) 
		{
			if($this->Auth->user('admin') == 1)
			{
				//Obtengo el id a consulltar
				$this->User->id = $id;
				
				//Ejecuta funciones para los selects
				$resultadoManagements = $this->RequestAction('/external_functions/showManagements');
				$resultadoPositions = $this->RequestAction('/external_functions/showPositions');
				$resultadoHeadquarters = $this->Headquarter->find('list', array('fields' => array('Headquarter.id', 'Headquarter.headquarter_name')));
				//$dataUser = $this->data;
				
				//Envio la información del usuario a consultar y para los selects
				$this->set('selectManagements',$resultadoManagements);
				$this->set('selectPositions', $resultadoPositions);
				$this->set('selectHeadquarters', $resultadoHeadquarters);

				
				
				//Si No hay infomacion (pasado por post)
				if (empty($this->request->data))
				{
					$this->request->data = $this->User->read();
					$this->set('title_for_layout', 'Editando a '.$this->request->data['User']['name'].' '.$this->request->data['User']['first_lastname']);
				}
				//Si hay infomacion (pasado por post)
				else 
				{
					//Se encapsula esa info. en un arreglo.
					$dataUser = $this->request->data;
					$dataUser['User']['email'] = trim(strtolower($dataUser['User']['email']));

					if ((isset($dataUser['User']['file']['error']) && $dataUser['User']['file']['error'] == 0) || (!empty( $dataUser['User']['file']['tmp_name']) && $dataUser['User']['file']['tmp_name'] != 'none')) 
					{
						if ((isset($dataUser['User']['file']['type']) && $dataUser['User']['file']['type'] == 'image/jpeg'))
						{
							if ((isset($dataUser['User']['file']['size']) && $dataUser['User']['file']['size'] <= 1500000))
							{
								$uploaddir =  'img/users/profile_pics/';

								$link ='users/profile_pics/';

								$fileName = $this->uploadResizedImage($dataUser['User']['file'], 200, 200, 1500000, 'users/edit', 'users/edit', 'users/edit', $uploaddir);

								$dataUser['User']['picture'] = $link.$fileName;
							}
							else
							{
								$this->Session->setFlash('El archivo debe ser de menos de 15 MB', 'flash_alert');
								return false;
							}
						}
						else
						{
							$this->Session->setFlash('El archivo debe ser de formato imagen (jpg/jpeg)', 'flash_alert');
							return false;
						}	
					}
					
					$this->User->begin();
					
					//Si la info. se guardo
					if ($this->User->save($dataUser))
					{
						$this->User->commit();
						//Envia un flash con mensaje de confrimacion y se redirecciona al index
						$this->Session->setFlash('Se ha editado correctamente el usuario seleccionado.', 'flash_success');
						$this->redirect(array('action' => 'index'));
					}
					$this->User->rollback();
				}
			}
			else
			{
				$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		
		
		
		function settings()
		{
			$activeUser = $this->RequestAction('/external_functions/getDataSession');
			$this->Setting->id = $activeUser['Setting']['id'];
			$this->set('title_for_layout', 'Preferencias de '.$activeUser['User']['name'].' '.$activeUser['User']['first_lastname']);
			$this->set('activeUser', $activeUser);
			
			if(empty($this->request->data))
			{
				$this->request->data = $this->Setting->read();
			}	
			else
			{
				$this->Setting->begin();
				
				if($this->Setting->save($this->request->data))
				{
					$this->Setting->commit();
					$this->Session->setFlash('Se han guardado las preferencias satisfactoriamente', 'flash_success');
					$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
				}
				
				$this->Setting->rollback();
			}
		}


		
		// Ventana modal para cambio obligatorio de contraseña...
		function change_password ()
		{
			$user = $this->RequestAction('/external_functions/getDataSession');
			$this->set('userSession', $user);
		
			if(!empty($this->request->data))
			{
				
				if($this->request->data['User']['new_password'] == '')
				{
					$this->Session->setFlash('La nueva contraseña no debe estar vacía.', 'flash_alert');
					$this->redirect(array('action' => 'change_password'));
				}
				
				if(strlen($this->request->data['User']['new_password']) <= 5 )
				{
					$this->Session->setFlash('La nueva contraseña debe con 6 caracteres como minimo.', 'flash_alert');
					$this->redirect(array('action' => 'change_password'));
				}
				
				$pass = $this->request->data['User']['new_password'];
				$validNumber = 0;
				$validUpper = 0;
				
				for($x=0; $x < strlen($pass); $x++)
				{
					if(is_numeric($pass[$x]))
						$validNumber = 1;
						
					if (ctype_upper($pass[$x]))
						$validUpper = 1;
				}
				
				if($validNumber == 0)
				{
					$this->Session->setFlash('La nueva contraseña debe contener por lo menos un numero.', 'flash_alert');
					$this->redirect(array('action' => 'change_password'));
				}
				if($validUpper == 0)
				{
					$this->Session->setFlash('La nueva contraseña debe contener por lo menos un caracter en mayuscula.', 'flash_alert');
					$this->redirect(array('action' => 'change_password'));
				}

				if($this->request->data['User']['new_password_confirm'] == '')
				{
					$this->Session->setFlash('La confirmacion de la contraseña no debe estar vacía.', 'flash_alert');
					$this->redirect(array('action' => 'change_password'));				
				}

				if($this->request->data['User']['new_password'] != $this->request->data['User']['new_password_confirm'])
				{
					$this->Session->setFlash('Ambos campos deben ser iguales.', 'flash_alert');
					$this->redirect(array('action' => 'change_password'));	
				}

				$userData = $this->RequestAction('/external_functions/getDataSession');
				$userData['User']['password'] = Security::hash($this->request->data['User']['new_password'], 'sha1');
				//$userData['User']['password'] = AuthComponent::password($this->request->data['User']['new_password']);
				$userData['User']['email_confirm'] = 1;
				
				$this->User->begin();
				
				if($this->User->save($userData))
				{
					$this->User->commit();
					$userId = $this->User->getLastInsertID();
					$this->Session->setFlash('La contraseña ha sido cambiado con exito!', 'flash_success');
					$this->redirect(array('action' => 'change_password'));
				}
				
				$this->User->rollback();
			}
		}

		function find()
		{
			if($this->Auth->user('admin') == 1)
			{
				if(!empty($this->request->data))
				{
					$this->paginate = array('User' => array('limit' => 1000, 'order' => array('User.'.$this->request->data['User']['user_type_search'] => 'ASC'), 'conditions' => array('User.'.$this->request->data['User']['user_type_search'].' LIKE' => '%'.$this->request->data['User']['user_param'].'%', 'User.active' => 1)));
					$dataUsers = $this->paginate();
					
					$this->set('users', $dataUsers);
					$this->set('dataSearch', $this->request->data);
				}
			}
			else
			{
				$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}

		function uploadResizedImage($campoImagen,$maxX,$maxY,$tamMaxBytes,$cod_error_dim,$cod_error_tam,$pagina_upl,$directorio_imagen_final)
		{

			$insize = true;

			if(isset($campoImagen) && strlen($campoImagen['name']) > 0)
			{
				list($width, $height) = @getimagesize($campoImagen['tmp_name']);

				if($width <= $maxX && $height <= $maxY)
				{
					header("Location: $cod_error_dim");
					exit(0);
				}
			
				if($campoImagen['size'] > $tamMaxBytes)
				{
					header("Location: $cod_error_tam");
					exit(0);	
				}
				else
				{
					$fileExtension = explode('.',$campoImagen['name']);
					$fileExtension = strtolower($fileExtension[count($fileExtension)-1]);
					$fileSize = $campoImagen['size']/1024;

					if($width > $maxX || $height > $maxY)
					{
					
						$imagePercentX = ( ( bcmul($maxX,100,2) ) / $width ) / 100;
						$imagePercentY = ( ( bcmul($maxY,100,2) ) / $height ) / 100;
				
						if( $width > $height )
						{
							$newWidth = (int) ($width * $imagePercentX );
							$newHeigth = (int) round($height * $imagePercentX );
						}
						elseif ($width < $height)
						{
							$newHeigth = (int)($height * $imagePercentY );
							$newWidth = (int) round($width * $imagePercentY );
						}
						else
						{
							$newHeigth = (int)($height * $imagePercentY );
							$newWidth = (int) round($width * $imagePercentY );
						}
						
						$resized = imagecreatetruecolor($newWidth,$newHeigth);
						$imagename = str_replace("ñ","n",$campoImagen['name']);
						
						switch ($fileExtension) 
						{
							case 'jpg':
							$source = imagecreatefromjpeg($campoImagen['tmp_name']);
							break;
							
							case 'jpeg':
							$source = imagecreatefromjpeg($campoImagen['tmp_name']);
							break;
					
							default:
							print "Image was not created...";
							exit();
							break;
						}
					
						$newicon = $source;
						$thnewicon = $source;
								
						if(imagecopyresampled($resized,$source,0, 0, 0, 0, $newWidth, $newHeigth, $width, $height)) 
						{
							$newicon = $resized;
						}
						else 
						{
							$newicon = false;
						}
					}
				
					$insize = false;
					
					if( $fileExtension != 'jpg' && $fileExtension != 'jpeg' )
					{
						print "extensi&oacute;n de archivo no v&aacute;lida...";
					}
					else 
					{
				
						if($insize)
						{
							switch ($fileExtension)
							{
								case 'jpg':
								$newicon = imagecreatefromjpeg($campoImagen['tmp_name']);
								break;
							
								case 'jpeg':
								$newicon = imagecreatefromjpeg($campoImagen['tmp_name']);
								break;
							}
						}
				
						define('FINAL_DIR',$directorio_imagen_final);
						$num_aleatorio = mt_rand(1,10000);
						$string_fecha = date("YmdHis");
						$nombreImg = md5($string_fecha.$num_aleatorio);
						$nombreImg = $nombreImg.".jpg";
						
						switch ($fileExtension)
						{
							case 'jpg':
							@imagejpeg($newicon, FINAL_DIR.$nombreImg, 80);
							break;
				
							case 'jpeg':
							@imagejpeg($newicon, FINAL_DIR.$nombreImg, 80);
							break;
						}
						
						return $nombreImg; // DEVUELVE EL NOMBRE DE LA IMAGEN
					}
				}
			}
		}
		
		function  myData()
		{
			$this->request->data = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id'))));
			
			//Se valida si es un usuario de planta
			if($this->request->data['User']['plant'] == 1)
				$this->request->data['User']['plant'] = 'Si';
			else
				$this->request->data['User']['plant'] = 'No';
			
			//Se valida si es un usuario activo
			if($this->request->data['User']['active'] == 1)
				$this->request->data['User']['active'] = 'Si';
			else
				$this->request->data['User']['active'] = 'No';
			
			//Le inserto el nombre del sistema en la informacion
			for($x=0; $x < count($this->request->data['UserSystem']); $x++)
			{
				$getSystemName = $this->System->find('first', array('conditions' => array('System.id' => $this->request->data['UserSystem'][$x]['system_id'])));
				
				 $this->request->data['UserSystem'][$x]['system_name'] = $getSystemName['System']['system_name'];
				 $this->request->data['UserSystem'][$x]['css_class_url'] = $getSystemName['System']['css_class_url'];
			}
			
			//Le inserto el/los nombre/s y centro de costo/s a la informacion
			for($x=0; $x < count($this->request->data['CostCenterUser']); $x++)
			{
				$getCostCenterName = $this->CostCenter->find('first', array('conditions' => array('CostCenter.id' => $this->request->data['CostCenterUser'][$x]['cost_center_id']), 'fields' => array('CostCenter.cost_center_name', 'CostCenter.cost_center_code')));
				 $this->request->data['CostCenterUser'][$x]['cost_center_name'] = $getCostCenterName['CostCenter']['cost_center_name'];
				 $this->request->data['CostCenterUser'][$x]['cost_center_code'] = $getCostCenterName['CostCenter']['cost_center_code'];
			}
			
			//Le inserto los datos de los perfiles a la informacion
			for($x=0; $x < count($this->request->data['UserProfile']); $x++)
			{
				$getProfileData = $this->Profile->find('first', array('conditions' => array('Profile.id' => $this->request->data['UserProfile'][$x]['profile_id'])));
				$this->request->data['UserProfile'][$x]['profile_name'] = $getProfileData['Profile']['profile_name'];
				$this->request->data['UserProfile'][$x]['description']   = $getProfileData['Profile']['description'];
				$this->request->data['UserProfile'][$x]['System']  = $getProfileData['System'];
				
				//$getPermissionsData = $this->ProfilePermission->find('all', array('conditions' => array('ProfilePermission.profile_id' => $this->data['UserProfile'][$x]['profile_id'])));
			}
			
			
			$this->set('title_for_layout', $this->data['User']['name'].' '.$this->request->data['User']['first_lastname']);
		
		
		}
	}

?>