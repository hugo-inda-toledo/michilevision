<?php	
 
class AppController extends Controller
{
	var $uses = array('User', 'System', 'Profile', 'Permission', 'Notification');
	public $components = array(
															'Cookie',
															'Session', 
															'Auth' => array(
																						'loginRedirect' => array('controller' => 'dashboard', 'action'  => 'index'),
																						'logoutRedirect' => array('controller' => 'users', 'action'  => 'login'),
																						'authError' => 'No tienes acceso a esta pagina',
																						'authorize' => array('Controller'),
																						'authenticate' => array('Form' => array(
																																					'fields' => array(
																																											'username' => 'email'
																																											), 
																																					'passwordHasher' => array(
																																																'className' => 'Simple',
																																																'hashType' => 'sha256'
																																															)
																																				)
																														)
																				)
	);
    
	public function isAuthorized($user)
	{
		if (!empty($this->params['prefix']) && $this->params['prefix'] == 'admin') 
		{
			if ($this->Auth->user('role') != 'admin') 
			{
				return false;
			}
		}
		return true;
	}
	
	public function beforeFilter()
	{	
		$this->Auth->allow('login', 'forgot_password');
		
		 // set cookie options
		$this->Cookie->httpOnly = true;
     
		if (!$this->Auth->loggedIn() && $this->Cookie->read('rememberMe')) 
		{
			$cookie = $this->Cookie->read('rememberMe');
 
             $this->loadModel('User'); // If the User model is not loaded already
			$user = $this->User->find('first', array(
																			'conditions' => array(
																			'User.username' => $cookie['username'],
																			'User.password' => $cookie['password']
																		)
			));
     
			if ($user && !$this->Auth->login($user['User'])) 
			{
                $this->redirect('/users/logout'); // destroy session & cookie
			}
		}
	}
	
	/*public function beforeRender() 
	{
			$id = $this->Auth->user('id');
		
			$user = $this->User->find('first', array('conditions' => array('User.id' => $id)));
			
			if($user)
			{
				//Obtiene los sistemas de la session y los encapsula en un arreglo para ser enviados al layout
				$mySystems = $this->extractMySystems($user);
				$countNotifications = $this->allNotifications();
				
				
				$this->set('countNotifications', $countNotifications);
				$this->set('mySystems', $mySystems);
			}
	
			$this->set('user', $user);
	}*/
	
	//Extrae los sistemas de un usuario
	public function extractMySystems($user)
	{
		$mySystems = array();
		
		foreach ($user['UserSystem'] as $value)
		{
			$system = $this->System->find('first', array('conditions' => array("System.id" => $value['system_id']), 'order' => 'System.system_name ASC'));
			array_push($mySystems, $system);
		}
		
		return $mySystems;
	}
	
	function allNotifications()
	{
		$notifications = $this->Notification->find('count', array('conditions' => array('Notification.readed' => 0, 'Notification.user_id' => $this->Auth->user('id'))));
		
		return $notifications;
	}
}
?>