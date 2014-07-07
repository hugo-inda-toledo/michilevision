<?php
App::import('Core', 'Email');
class PagesController extends AppController
{
	var $name = 'Pages';
	var $helpers = array('Session', 'Html', 'Form','Time');
	var $uses = array('User','Management','Position', 'CostCenterUser', 'CostCenter', 'UserPermission', 'UserSystem', 'UserProfile');
	var $components = array('Password', 'Email', 'Auth');
	var $scaffold;
	  
		
	function display()
	{
		if ($this->RequestAction('/external_functions/getEmailConfirmation/') == false)
		{
			///$this->Session->setFlash('Falta confirmacion de email', 'flash_success');
			if(!empty($this->data))
			{
				if($this->data['User']['password'] != $this->data['User']['password_verified'])
				{
					$this->Session->setFlash('Los campos deben ser iguales.', 'flash_alert');
				}
				else
				{
					$dataSession = $this->RequestAction('/external_functions/getDataSession/');
					
					$dataSession['User']['password'] = Security::hash($this->data['User']['password'], null, true);
					
					$dataSession['User']['email_confirm'] =1;
					
					if($this->User->save($dataSession))
					{
						$this->Session->setFlash('La clave fue cambiada con exito', 'flash_success');
					}
				}
				
				
			}
		}
		else
		{
			//$this->Session->setFlash('Se ha creado el usuario pero no se ha enviado el mail con datos de session ', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}	
}
?>