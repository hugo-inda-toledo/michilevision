<?php
class EmailsController extends AppController 
{
	public $uses = null;
	
	public $components = array('Email');
	
	public function index() 
	{
		$this->Email->to = 'hugo.inda@gmail.com';
		$this->Email->subject = 'Quickwall Confirmation';
		$this->Email->replyTo = 'noreply@cakequickwall.com';
		$this->Email->from = 'noreply@cakequickwall.com';
		$this->Email->sendAs = 'html';
		$this->Email->template = 'welcome';
		/*$this->set('name', $this->data['User']['username']);
		$this->set('server_name', $_SERVER['SERVER_NAME']);
		$this->set('id', $this->User->getLastInsertID());
		$this->set('code', $this->data['User']['confirm_code']);*/
		if ($this->Email->send()) 
		{
			$this->Session->setFlash('Confirmation mail sent. Please check your inbox');
			$this->redirect(array('controller' => 'users', 'action'=>'index'));
		} 
		else
		{
		//$this->User->del($this->User->getLastInsertID());
		$this->Session->setFlash('There was a problem sending the confirmation mail. Please try again');
		}
	}
}
?>