<?php
class DashboardController extends AppController
{
	var $name = 'Dashboard';
	var $helpers = array('Session', 'Html', 'Form','Time');
	var $uses = array('System', 'User', 'Notification');
	var $components = array('Password', 'Email', 'Auth');
	var $scaffold;
	
	function index()
	{
		$dataActiveUser = $this->RequestAction('/external_functions/getDataSession/');
		$systems = array();
		
		$x=0;
		
		$user = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id'))));
		$systems = $this->System->find('all', array('fields' => array('System.id', 'System.system_name', 'System.table_name', 'System.css_class_url', 'System.system_description')));
		
		
		
		for($x=0; $x < count($systems); $x++)
		{
			$switch = false;
			
			if($user['System'] != false)
			{
				for($y=0; $y < count($user['System']); $y++)
				{
					if(($systems[$x]['System']['id'] == $user['System'][$y]['id']) && $switch == false)
					{
						$systems[$x]['System']['enabled'] = 1;
						$switch = true;
					}
					elseif ($switch == false)
					{
						$systems[$x]['System']['enabled'] = 0;
					}
				}
			}
			else
			{
				$systems[$x]['System']['enabled'] = 0;
			}
		}
		
		$notifications = $this->Notification->find('all', array('conditions' => array('Notification.user_id' =>$this->Auth->user('id'), 'Notification.readed' => 0), 'order' => 'Notification.created DESC'));
		
		for($y=0; $y < count($notifications); $y++)
		{
			$notifications[$y]['Notification']['time_elapsed'] = $this->getMinuts($notifications[$y]['Notification']['created'], date('Y-m-d H:i:s'));
		}
	
		
		$this->set('userData', $user);
		$this->set('notifications', $notifications);
		$this->set('systems', $systems);
		$this->set('title_for_layout', 'Dashboard');
	}
	
	function getMinuts($notificationDate, $nowDate)
	{
		$notificationDate = strtotime($notificationDate);
		$nowDate = strtotime($nowDate);
		$minutes = round(($nowDate - $notificationDate) / 60);
		
		$text = $this->getTimeMessage($minutes);
		return $text;
	}
	
	function getTimeMessage($minutes)
	{
		if($minutes > 483840)
			return 'Mas de 1 año atras.';
		
		if($minutes == 483840)
			return '1 año atras.';
			
		if($minutes >= 443520)
			return '11 meses atras.';
			
		if($minutes >= 403200)
			return '10 meses atras.';
			
		if($minutes >= 362880)
			return '9 meses atras.';
			
		if($minutes >= 322560)
			return '8 meses atras.';
			
		if($minutes >= 282240)
			return '7 meses atras.';
			
		if($minutes >= 241920)
			return '6 meses atras.';
			
		if($minutes >= 201600)
			return '5 meses atras.';
			
		if($minutes >= 161280)
			return '4 meses atras.';
			
		if($minutes >= 120960)
			return '3 meses atras.';
			
		if($minutes >= 80640)
			return '2 meses atras.';
			
		if($minutes >= 40320)
			return '1 mes atras.';

		if($minutes >= 30240)
			return '3 semanas atras.';
			
		if($minutes >= 20160)
			return '2 semanas atras.';
			
		if($minutes >= 10080)
			return '1 semana atras.';
			
		if($minutes >= 8640)
			return '6 días atras.';
			
		if($minutes >= 7200)
			return '5 días atras.';
			
		if($minutes >= 5760)
			return '4 días atras.';
			
		if($minutes >= 4320)
			return '3 días atras.';
			
		if($minutes >= 2880)
			return '2 días atras.';
			
		if($minutes >= 1440)
			return 'Ayer.';
			
		if($minutes > 1410)
			return 'Mas de 23 horas y media atras.';
		
		if($minutes == 1410)
			return '23 horas y media atras.';
			
		if($minutes > 1380)
			return 'Mas de 23 horas atras.';
		
		if($minutes == 1380)
			return '23 horas atras.';
			
		if($minutes > 1350)
			return 'Mas de 22 horas y media atras.';
		
		if($minutes == 1350)
			return '22 horas y media atras.';
			
		if($minutes > 1320)
			return 'Mas de 22 horas atras.';
		
		if($minutes == 1320)
			return '22 horas atras.';
			
		if($minutes > 1290)
			return 'Mas de 21 horas y media atras.';
		
		if($minutes == 1290)
			return '21 horas y media atras.';
			
		if($minutes > 1260)
			return 'Mas de 21 horas atras.';
		
		if($minutes == 1260)
			return '21 horas atras.';
			
		if($minutes > 1230)
			return 'Mas de 20 horas y media atras.';
		
		if($minutes == 1230)
			return '20 horas y media atras.';
			
		if($minutes > 1200)
			return 'Mas de 20 horas atras.';
		
		if($minutes == 1200)
			return '20 horas atras.';
			
		if($minutes > 1170)
			return 'Mas de 19 horas y media atras.';
		
		if($minutes == 1170)
			return '19 horas y media atras.';
			
		if($minutes > 1140)
			return 'Mas de 19 horas atras.';
		
		if($minutes == 1140)
			return '19 horas atras.';
			
		if($minutes > 1110)
			return 'Mas de 18 horas y media atras.';
		
		if($minutes == 1110)
			return '18 horas y media atras.';
			
		if($minutes > 1080)
			return 'Mas de 18 horas atras.';
		
		if($minutes == 1080)
			return '18 horas atras.';
			
		if($minutes > 1050)
			return 'Mas de 17 horas y media atras.';
		
		if($minutes == 1050)
			return '17 horas y media atras.';
			
		if($minutes > 1020)
			return 'Mas de 17 horas atras.';
		
		if($minutes == 1020)
			return '17 horas atras.';
			
		if($minutes > 990)
			return 'Mas de 16 horas y media atras.';
		
		if($minutes == 990)
			return '16 horas y media atras.';
			
		if($minutes > 960)
			return 'Mas de 16 horas atras.';
		
		if($minutes == 960)
			return '16 horas atras.';
			
		if($minutes > 930)
			return 'Mas de 15 horas y media atras.';
		
		if($minutes == 930)
			return '15 horas y media atras.';
			
		if($minutes > 900)
			return 'Mas de 15 horas atras.';
		
		if($minutes == 900)
			return '15 horas atras.';
			
		if($minutes > 870)
			return 'Mas de 14 horas y media atras.';
		
		if($minutes == 870)
			return '14 horas y media atras.';
			
		if($minutes > 840)
			return 'Mas de 14 horas atras.';
		
		if($minutes == 840)
			return '14 horas atras.';
			
		if($minutes > 810)
			return 'Mas de 13 horas y media atras.';
		
		if($minutes == 810)
			return '13 horas y media atras.';
			
		if($minutes > 780)
			return 'Mas de 13 horas atras.';
		
		if($minutes == 780)
			return '13 horas atras.';
			
		if($minutes > 750)
			return 'Mas de 12 horas y media atras.';
		
		if($minutes == 750)
			return '12 horas y media atras.';
			
		if($minutes > 720)
			return 'Mas de 12 horas atras.';
		
		if($minutes == 720)
			return '12 horas atras.';
			
		if($minutes > 690)
			return 'Mas de 11 horas y media atras.';
		
		if($minutes == 690)
			return '11 horas y media atras.';
			
		if($minutes > 660)
			return 'Mas de 11 horas atras.';
		
		if($minutes == 660)
			return '11 horas atras.';
			
		if($minutes > 630)
			return 'Mas de 10 horas y media atras.';
		
		if($minutes == 630)
			return '10 horas y media atras.';
			
		if($minutes > 600)
			return 'Mas de 10 horas atras.';
		
		if($minutes == 600)
			return '10 horas atras.';
			
		if($minutes > 570)
			return 'Mas de 9 horas y media atras.';
		
		if($minutes == 570)
			return '9 horas y media atras.';
			
		if($minutes > 540)
			return 'Mas de 9 horas atras.';
		
		if($minutes == 540)
			return '9 horas atras.';
			
		if($minutes > 510)
			return 'Mas de 8 horas y media atras.';
		
		if($minutes == 510)
			return '8 horas y media atras.';
			
		if($minutes > 480)
			return 'Mas de 8 horas atras.';
		
		if($minutes == 480)
			return '8 horas atras.';
			
		if($minutes > 450)
			return 'Mas de 7 horas y media atras.';
		
		if($minutes == 450)
			return '7 horas y media atras.';
			
		if($minutes > 420)
			return 'Mas de 7 horas atras.';
		
		if($minutes == 420)
			return '7 horas atras.';
			
		if($minutes > 390)
			return 'Mas de 6 horas y media atras.';
		
		if($minutes == 390)
			return '6 horas y media atras.';
			
		if($minutes > 360)
			return 'Mas de 6 horas atras.';
		
		if($minutes == 360)
			return '6 horas atras.';
			
		if($minutes > 330)
			return 'Mas de 5 horas y media atras.';
		
		if($minutes == 330)
			return '5 horas y media atras.';
			
		if($minutes > 300)
			return 'Mas de 5 horas atras.';
		
		if($minutes == 300)
			return '5 horas atras.';
		
		if($minutes > 270)
			return 'Mas de 4 horas y media atras.';
		
		if($minutes == 270)
			return '4 horas y media atras.';
		
		if($minutes > 240)
			return 'Mas de 4 horas atras.';
		
		if($minutes == 240)
			return '4 horas atras.';
		
		if($minutes > 210)
			return 'Mas de 3 horas y media atras.';
		
		if($minutes == 210)
			return '3 horas y media atras.';
		
		if($minutes > 180)
			return 'Mas de 3 horas atras.';
		
		if($minutes == 180)
			return '3 horas atras';
		
		if($minutes > 150)
			return 'Mas de 2 horas y media atras.';
		
		if($minutes == 150)
			return '2 horas y media atras.';
		
		if($minutes > 120)
			return 'Mas de 2 horas atras.';
			
		if($minutes == 120)
			return '2 horas atras.';
		
		if($minutes > 90)
			return 'Mas de 1 hora y media atras.';
		
		if($minutes == 90)
			return '1 hora y media atras.';
		
		if($minutes > 60)
			return 'Mas de 1 hora atras.';
		
		if($minutes == 60)
			return '1 hora atras.';
		
		if($minutes < 1)
			return 'Hace unos segundos.';
			
		if($minutes == 1)
			return $minutes.' minuto atras.';
		
		if($minutes <= 59)
			return $minutes.' minutos atras.';
		
		
	}
}
?>