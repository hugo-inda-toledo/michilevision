<?php
	class PayrollsController extends AppController
	{
		var $name = 'Payrolls';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('Circuit', 'User', 'System', 'Authorization', 'Management', 'Headquarter', 'Replacement');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		
		function mainMenu()
		{
			$this->redirect(array('action' => 'index'));
		}
		
		
		function index() 
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/12/index") == true)
			{
				echo "pasa";
			}
			else
			{
				$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		
}
?>