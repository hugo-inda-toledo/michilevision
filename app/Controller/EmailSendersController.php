<?php
	
	App::uses('CakeEmail', 'Network/Email');
	App::uses('Sanitize', 'Utility');
	App::uses('Security', 'Utility');
	
	
	class EmailSendersController extends AppController
	{
		var $name = 'EmailSenders';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('User','Management','Position', 'CostCenterUser', 'CostCenter', 'UserPermission', 'UserSystem', 'UserProfile');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		
		
		//Email de Bienvenida
		function sendWelcomeMail($name, $first_lastname, $username, $password, $mail, $userController=null) 
		{
			$nombre = $name;
			
			$body = 'Estimado '.$nombre.' '.$first_lastname.'.<br>Le queremos informar que su correo electronico('.$mail.')
			tiene asociada una cuenta en michilevision.cl.<br>
			Para poder ingresar al sistema le enviamos sus datos de ingreso.<br>
			<table border=1>
			<tr><td>Nombre de usuario: </td><td><b>'.$mail.'</b></td></tr>
			<tr><td>Clave de acceso: </td><td><b>'.$password.'</b></td></tr>
			</table><br>Si tiene alguna duda o consulta envie un correo a soporte@michilevision.cl para resolverla a la brevedad.<br>Que tengas buen día!<br><br>';
			

			
			$this->Email->sendAs = 'both';
			$this->Email->layout = 'default';
			$this->Email->template = 'welcome';
			$this->Email->delivery = 'smtp';
			
			
			
			$this->Email->smtpOptions = array(
														//'port' => '25',
														//'timeout' => '30',
														'host' => 'argsmtpgw.turner.com'
														//'username' => '',
														//'password' => '',
														//'auth' => false
			);
			
			$this->Email->to =$name." ".$first_lastname." <".$mail.">";
			$this->Email->subject = $name.', bienvenido a mi.chilevision.cl';
			$this->Email->replyTo = 'hugo.inda@chilevision.cl';
			$this->Email->from = "El equipo de soporte de Mi Chilevision. <welcome-noreply@chilevision.cl>";
			
			/*$root = "/michilevision/app/webroot/files/purchase_orders/budgets/";
			
			$this->Email->filePaths  = array('c:/wamp/www'.$root);
			$this->Email->attachments = array('45216215.pdf');*/
			
			
			
			$this->set('body', $body);
			 
			$this->Email->send();
			

			if($userController !=null)
			{
				$userData = $this->User->find('first', array('conditions' => array('User.email' => $mail)));
				$userData['User']['password'] = AuthComponent::password($password);
				
				$this->User->begin();
				
				if($this->User->save($userData))
				{
					$this->User->commit();
					$this->Session->setFlash('Se ha reenviado el email a '.$mail.' con una nueva contraseña.', 'flash_success');	
					$this->redirect(array('controller' => 'users','action' => 'index'));
				}
				else
				{
					$this->User->rollback();
				}
			}
			else
				return true;
		}
		
		//Recuperacion de clave
		function sendRecoveryMail($mail) 
		{

			
			$body = "ALOH ".$name." ".$first_lastname.".<br>Le queremos informar que su correo electronico(".$mail.")
			tiene asociada una cuenta en michilevision.cl.<br>
			Para poder ingresar al sistema le enviamos sus datos de ingreso.\n
			<table border=0>
			<tr><td>Nombre de usuario: </td><td><b>".$username."</b></td></tr>
			<tr><td>Clave de acceso: </td><td><b>".$password."</b></td></tr>
			</table><br>Si tiene alguna duda o consulta envie un correo a soporte@michilevision.cl para resolverla a la brevedad.<br>Que tengas buen día!<br><br>Atte.<br>El equipo de desarrollo de miChilevision.cl";
			

			
			$this->Email->sendAs = 'both';
			$this->Email->layout = 'default';
			$this->Email->template = 'welcome';
			$this->Email->delivery = 'smtp';
			$this->Email->smtpOptions = array(
														//'port' => '25',
														//'timeout' => '30',
														'host' => 'argsmtpgw.turner.com'
														//'username' => '',
														//'password' => '',
														//'auth' => false
			);
			
			$this->Email->attachments = array('/michilevision/app/webroot/img/top.png');
			$this->Email->to =$name." ".$first_lastname." <".$mail.">";
			$this->Email->subject = $name.', Bienvenido a miChilevision.cl';
			$this->Email->replyTo = 'hugo.inda@chilevision.cl';
			$this->Email->from = 'noreply@michilevision.cl';
			$this->Email->from = "miChilevision Support Team <welcome-noreply@michilevision.cl>";
			$this->set('body', $body);
			 
			$sent = $this->Email->send();
			 
			if($sent)
			{
				return true;
			}	
			else
			{
				return false;
			}
		}
	}
?>