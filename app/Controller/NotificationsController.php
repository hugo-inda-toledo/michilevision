<?php
	class NotificationsController extends AppController
	{
		var $name = 'Notifications';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('Notification', 'User', 'System', 'Management', 'CostCenter', 'Headquarter', 'Replacement', 'Profile');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		function allNotifications()
		{
			$notifications = $this->Notification->find('all', array('conditions' => array('Notification.readed' => 0, 'Notification.user_id' => $this->Auth->user('id'))));
			
			$root = "/michilevision";
			
			$table = "<table>";
			
			if($notifications != false)
			{
				foreach($notifications as $notification)
				{
					$table .= '<tr>
									<td><a href="'.$root.'/notifications/markAsReaded/'.$notification['Notification']['id'].'" class="tip_tip link_admin '.$notification['Notification']['css_class'].'">'.$notification['Notification']['message_to_user'].'</a></td>
								  <tr>';
				}
			}
			else
			{
				$table .= '<tr><td>No hay nuevas notificaciones</td></tr>';
			}
			
			$table .= '<tr><td><a href="'.$root.'/notifications/showAll/">Ver todas las notificaciones</a></td></tr></table>';
			
			return $table;
		}
		
		/******************************************************************************************************************************************************
		*********************************************************NOTIFICACIONES GLOBALES**********************************************************
		******************************************************************************************************************************************************/
		
		//Envia notificacion cuando todas las firmas de una ficha de un sistema X fueron aprobadas
		function approvedRequestNotification($system=null, $user=null, $relation=null)
		{
			$notification = array();
			
			if($system != null && $user != null && $relation != null)
			{
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['relation_id'] = $relation;
				$notification['Notification']['message_id'] = 1;  //Mensaje de aprobacion por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se verifica el puntero del sistema para tomar el ID
					$systemPointer = $this->verifiedSystemData($system);
					
					//Se reemplaza [ID] por el id del puntero
					$textMessage = str_replace('[ID]', $dataNotification[$systemPointer]['id'], $textMessage);
					
					//Se reemplaza [SYSTEM] por el nombre del sistema 
					$textMessage = str_replace('[SYSTEM]', $dataNotification['System']['system_name'], $textMessage);
					
					$url = "/".$dataNotification['System']['table_name']."/view/".$dataNotification[$systemPointer]['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];

					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		function notificationToAdministrationManagerForAuthorizeModifyOrder($system = null, $user = null, $relation = null)
		{
			$notification = array();
			
			if($system != null && $user != null && $relation != null)
			{
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['relation_id'] = $relation;
				$notification['Notification']['message_id'] = 24;  //Mensaje de rechazo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se verifica el puntero del sistema para tomar el ID
					$systemPointer = $this->verifiedSystemData($system);
					
					//Se reemplaza [ID] por el id del puntero
					$textMessage = str_replace('[ID]', $dataNotification[$systemPointer]['id'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/modified_request_orders/view/".$relation;
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		function notificationToUserForModifiedOrder($system = null, $user = null, $relation = null, $authorize = null)
		{
			$notification = array();
			
			if($system != null && $user != null && $relation != null && $authorize != null)
			{
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['relation_id'] = $relation;
				
				if($authorize == 1)
					$messageId = 25;
				else
					$messageId = 26;
				
				$notification['Notification']['message_id'] = $messageId;  //Mensaje de rechazo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se verifica el puntero del sistema para tomar el ID
					$systemPointer = $this->verifiedSystemData($system);
					
					//Se reemplaza [ID] por el id del puntero
					$textMessage = str_replace('[ID]', $dataNotification[$systemPointer]['id'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/".$dataNotification['System']['table_name']."/view/".$dataNotification[$systemPointer]['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		//Envia notificacion cuando una ficha de un sistema X fue rechazada
		function declinedRequestNotification($system=null, $user=null, $relation=null)
		{
			$notification = array();
			
			if($system != null && $user != null && $relation != null)
			{
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['relation_id'] = $relation;
				$notification['Notification']['message_id'] = 2;  //Mensaje de rechazo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se verifica el puntero del sistema para tomar el ID
					$systemPointer = $this->verifiedSystemData($system);
					
					//Se reemplaza [ID] por el id del puntero
					$textMessage = str_replace('[ID]', $dataNotification[$systemPointer]['id'], $textMessage);
					
					//Se reemplaza [SYSTEM] por el nombre del sistema 
					$textMessage = str_replace('[SYSTEM]', $dataNotification['System']['system_name'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/".$dataNotification['System']['table_name']."/view/".$dataNotification[$systemPointer]['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		
		
		//Envia notificacion cuando un usuario debe aprobar una firma en una X ficha
		function requestToBeSignNotification($system=null, $user=null, $relation=null)
		{
			$notification = array();
			
			if($system != null && $user != null && $relation != null)
			{
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['relation_id'] = $relation;
				$notification['Notification']['message_id'] = 3;  //Mensaje de firma por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se reemplaza [SYSTEM] por el nombre del sistema 
					$textMessage = str_replace('[SYSTEM]', $dataNotification['System']['system_name'], $textMessage);
					
					//Se verifica el puntero del sistema para tomar el ID
					$systemPointer = $this->verifiedSystemData($system);
					
					//Se genera la url de la notificacion
					$url = "/".$dataNotification['System']['table_name']."/view/".$dataNotification[$systemPointer]['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						/*echo "<br><br><br><br><br><br><br><br><br><br><br><br><pre>";
						print_r($dataNotification['Notification']);
						echo "</pre>";*/
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		//Envia notificacion al usuario que se le asigno un reemplazo (Reemplazante)
		function replacingNotification($user=null, $replacement=null)
		{
			$notification = array();
			
			if($user != null && $replacement != null)
			{
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['replacement_id'] = $replacement;
				$notification['Notification']['message_id'] = 4;  //Mensaje de reemplazante por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					$replacedUser = $this->User->find('first', array('conditions' => array('User.id' => $dataNotification['Replacement']['replaced_user_id']), 'fields' => array('User.name', 'User.first_lastname')));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se reemplaza [REPLACED] por el nombre del reemplazado
					$textMessage = str_replace('[REPLACED]', $replacedUser['User']['name']." ".$replacedUser['User']['first_lastname'], $textMessage);
					
					//Se reemplaza [START_DATE] por la fecha de inicio del reemplazo
					$textMessage = str_replace('[START_DATE]', $dataNotification['Replacement']['start_date'], $textMessage);
					
					//Se reemplaza [END_DATE] por la fecha de inicio del reemplazo
					$textMessage = str_replace('[END_DATE]', $dataNotification['Replacement']['end_date'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/replacements/view/".$dataNotification['Replacement']['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						/*echo "<br><br><br><br><br><br><br><br><br><br><br><br><pre>";
						print_r($dataNotification);
						echo "</pre>";*/
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		//Envia notificacion al usuario que se le asigno un reemplazo (Reemplazado)
		function replacedNotification($user=null, $replacement=null)
		{
			$notification = array();
			
			if($user != null && $replacement != null)
			{
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['replacement_id'] = $replacement;
				$notification['Notification']['message_id'] = 5;  //Mensaje de reemplazado por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					$replacingUser = $this->User->find('first', array('conditions' => array('User.id' => $dataNotification['Replacement']['replacing_user_id']), 'fields' => array('User.name', 'User.first_lastname')));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se reemplaza [REPLACING] por el nombre del reemplazante
					$textMessage = str_replace('[REPLACING]', $replacingUser['User']['name']." ".$replacingUser['User']['first_lastname'], $textMessage);
					
					//Se reemplaza [START_DATE] por la fecha de inicio del reemplazo
					$textMessage = str_replace('[START_DATE]', $dataNotification['Replacement']['start_date'], $textMessage);
					
					//Se reemplaza [END_DATE] por la fecha de inicio del reemplazo
					$textMessage = str_replace('[END_DATE]', $dataNotification['Replacement']['end_date'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/replacements/view/".$dataNotification['Replacement']['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		//Envia notificacion al usuario que se le asigno como gerente
		function managementAssignNotification($user=null, $management=null)
		{
			$notification = array();
			
			if($user != null && $management != null)
			{
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['management_id'] = $management;
				$notification['Notification']['message_id'] = 6;  //Mensaje de asignacion de gerencia por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					$management = $this->Management->find('first', array('conditions' => array('Management.id' => $dataNotification['Management']['id'])));

					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se reemplaza [MANAGEMENT] por la gerencia asociada
					$textMessage = str_replace('[MANAGEMENT]', $management['Management']['management_name'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/managements/view/".$management['Management']['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		//Envia notificacion al usuario que se le asigno como jefe
		function headquarterAssignNotification($user=null, $headquarter=null)
		{
			$notification = array();
			
			if($user != null && $headquarter != null)
			{
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['headquarter_id'] = $headquarter;
				$notification['Notification']['message_id'] = 7;  //Mensaje de asignacion de jefatura por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					$headquarter = $this->Headquarter->find('first', array('conditions' => array('Headquarter.id' => $dataNotification['Headquarter']['id'])));

					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se reemplaza [HEADQUARTER] por la jefatura asociada
					$textMessage = str_replace('[HEADQUARTER]', $headquarter['Headquarter']['headquarter_name'], $textMessage);
					
					//Se reemplaza [MANAGEMENT] por la gerencia asociada
					$textMessage = str_replace('[MANAGEMENT]', $headquarter['Management']['management_name'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/headquarters/view/".$headquarter['Headquarter']['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		//Envia notificacion al gerente informando la creacion de una jefatura en su gerencia
		function headquarterAssignNotificationToManagement($user=null, $headquarter=null)
		{
			$notification = array();
			
			if($user != null && $headquarter != null)
			{
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['headquarter_id'] = $headquarter;
				$notification['Notification']['message_id'] = 8;  //Mensaje de asignacion de jefatura por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					$headquarter = $this->Headquarter->find('first', array('conditions' => array('Headquarter.id' => $dataNotification['Headquarter']['id'])));

					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se reemplaza [HEADQUARTER] por la jefatura asociada
					$textMessage = str_replace('[HEADQUARTER]', $headquarter['Headquarter']['headquarter_name'], $textMessage);
					
					
					//Se genera la url de la notificacion
					$url = "/headquarters/view/".$headquarter['Headquarter']['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		//Envia notificacion al usuario que se le asigno un nuevo sistema
		function systemAssignNotification($user=null, $system=null)
		{
			$notification = array();
			
			if($user != null && $system != null)
			{
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['message_id'] = 9;  //Mensaje de asignacion de sistema por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$css = $this->verifiedSystemCss($system);
				
				$notification['Notification']['css_class'] = $css;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se reemplaza [SYSTEM] por el sistema asociado
					$textMessage = str_replace('[SYSTEM]', $dataNotification['System']['system_name'], $textMessage);
					
					
					//Se genera la url de la notificacion
					$url = "/".$dataNotification['System']['table_name']."/mainMenu/";
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->rollback();
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		//Envia notificacion al gerente que se le asigno un nuevo sistema a un usuario de su gerencia
		function systemAssignNotificationToManagement($user=null, $system=null, $user_assign=null)
		{
			$notification = array();
			
			if($user != null && $system != null && $user_assign != null)
			{
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['message_id'] = 10;  //Mensaje de asignacion de sistema por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$css = $this->verifiedSystemCss($system);
				
				$notification['Notification']['css_class'] = $css;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					$dataUserAssign = $this->User->find('first', array('conditions' => array('User.id' => $user_assign), 'fields' => array('User.name', 'User.first_lastname')));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se reemplaza [SYSTEM] por la fecha de inicio del reemplazo
					$textMessage = str_replace('[SYSTEM]', $dataNotification['System']['system_name'], $textMessage);
					
					//Se reemplaza [USER] por la fecha de inicio del reemplazo
					$textMessage = str_replace('[USER]', $dataUserAssign['User']['name']." ".$dataUserAssign['User']['first_lastname'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "#";
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		//Envia notificacion al usuario cuando se le asigna un centro de costo a un sistema X
		function costCenterAssignNotification($user=null, $system=null, $cost_center=null)
		{
			$notification = array();
			
			if($user != null && $system != null && $cost_center != null)
			{
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['cost_center_id'] = $cost_center;
				$notification['Notification']['message_id'] = 11;  //Mensaje de asignacion de centro de costo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se reemplaza [COST_CENTER] por el centro de costo asociado	
					$textMessage = str_replace('[COST_CENTER]', $dataNotification['CostCenter']['cost_center_name']." (".$dataNotification['CostCenter']['cost_center_code'].")", $textMessage);
					
					//Se reemplaza [SYSTEM] por el sistema asociado
					$textMessage = str_replace('[SYSTEM]', $dataNotification['System']['system_name'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/cost_centers/view/".$dataNotification['CostCenter']['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		//Envia notificacion al gerente cuando se agrego un centro de costo a un usuario de su gerencia
		function costCenterAssignNotificationToManagement($user=null, $system=null, $cost_center=null, $user_assign=null)
		{
			$notification = array();
			
			if($user != null && $system != null && $cost_center != null && $user_assign != null)
			{
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['cost_center_id'] = $cost_center;
				$notification['Notification']['message_id'] = 12;  //Mensaje al gerente de asignacion de centro de costo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					$dataUserAssign = $this->User->find('first', array('conditions' => array('User.id' => $user_assign), 'fields' => array('User.name', 'User.first_lastname')));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se reemplaza [COST_CENTER] por el centro de costo asociado
					$textMessage = str_replace('[COST_CENTER]', $dataNotification['CostCenter']['cost_center_name']." (".$dataNotification['CostCenter']['cost_center_code'].")", $textMessage);
					
					//Se reemplaza [USER] por la fecha de inicio del reemplazo
					$textMessage = str_replace('[USER]', $dataUserAssign['User']['name']." ".$dataUserAssign['User']['first_lastname'], $textMessage);
					
					//Se reemplaza [SYSTEM] por el sistema asociado
					$textMessage = str_replace('[SYSTEM]', $dataNotification['System']['system_name'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "#";
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		//Envia notificacion al usuario cuando se le asigna un perfil de un sistema X
		function profileAssignNotification($user=null, $system=null, $profile=null)
		{
			$notification = array();
			
			if($user != null && $system != null && $profile != null)
			{
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['profile_id'] = $profile;
				$notification['Notification']['message_id'] = 13;  //Mensaje de asignacion de centro de costo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se reemplaza [PROFILE] por el centro de costo asociado	
					$textMessage = str_replace('[PROFILE]', $dataNotification['Profile']['profile_name'], $textMessage);
					
					//Se reemplaza [SYSTEM] por el sistema asociado
					$textMessage = str_replace('[SYSTEM]', $dataNotification['System']['system_name'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/profiles/view/".$dataNotification['Profile']['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		//Envia notificacion al gerente cuando se agrego un perfil a un usuario de su gerencia
		function profileAssignNotificationToManagement($user=null, $system=null, $profile=null, $user_assign=null)
		{
			$notification = array();
			
			if($user != null && $system != null && $profile != null && $user_assign != null)
			{
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['profile_id'] = $profile;
				$notification['Notification']['message_id'] = 14;  //Mensaje al gerente de asignacion de centro de costo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
				
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					$dataUserAssign = $this->User->find('first', array('conditions' => array('User.id' => $user_assign), 'fields' => array('User.name', 'User.first_lastname')));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se reemplaza [PROFILE] por el centro de costo asociado
					$textMessage = str_replace('[PROFILE]', $dataNotification['Profile']['profile_name'], $textMessage);
					
					//Se reemplaza [USER] por la fecha de inicio del reemplazo
					$textMessage = str_replace('[USER]', $dataUserAssign['User']['name']." ".$dataUserAssign['User']['first_lastname'], $textMessage);
					
					//Se reemplaza [SYSTEM] por el sistema asociado
					$textMessage = str_replace('[SYSTEM]', $dataNotification['System']['system_name'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "#";
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		/******************************************************************************************************************************************************
		*******************************************************************************************************************************************************
		******************************************************************************************************************************************************/
		
		/******************************************************************************************************************************************************
		************************************************************FONDOS POR RENDIR****************************************************************
		******************************************************************************************************************************************************/
		
		//Se notifica al gerente de finanzas que debe revisar los fondos para una X solicitud
		function notificationToFinanceForMessageToTreasury($system=null, $user=null, $relation=null)
		{
			$notification = array();
			
			if($system != null && $user != null && $relation != null)
			{
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['relation_id'] = $relation;
				$notification['Notification']['message_id'] = 15;  //Mensaje de rechazo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se verifica el puntero del sistema para tomar el ID
					$systemPointer = $this->verifiedSystemData($system);
					
					//Se reemplaza [ID] por el id del puntero
					$textMessage = str_replace('[ID]', $dataNotification[$systemPointer]['id'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/".$dataNotification['System']['table_name']."/view/".$dataNotification[$systemPointer]['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		//Se notifica al tesorero que tiene un mensaje desde finanzas
		function notificationToTreasuryForRead($system=null, $user=null, $relation=null)
		{
			$notification = array();
			
			if($system != null && $user != null && $relation != null)
			{
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['relation_id'] = $relation;
				$notification['Notification']['message_id'] = 16;  //Mensaje de rechazo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se verifica el puntero del sistema para tomar el ID
					$systemPointer = $this->verifiedSystemData($system);
					
					//Se reemplaza [ID] por el id del puntero
					$textMessage = str_replace('[ID]', $dataNotification[$systemPointer]['id'], $textMessage);
					
					//Se reemplaza [SYSTEM] por el sistema asociado
					$textMessage = str_replace('[SYSTEM]', $dataNotification['System']['system_name'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/".$dataNotification['System']['table_name']."/view/".$dataNotification[$systemPointer]['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		//Se notifica al solicitante que tiene un mensaje de tesoreria
		function notificationToRequestUser($system=null, $user=null, $relation=null)
		{
			$notification = array();
			
			if($system != null && $user != null && $relation != null)
			{
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['relation_id'] = $relation;
				$notification['Notification']['message_id'] = 17;  //Mensaje de rechazo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se verifica el puntero del sistema para tomar el ID
					$systemPointer = $this->verifiedSystemData($system);
					
					//Se reemplaza [ID] por el id del puntero
					$textMessage = str_replace('[ID]', $dataNotification[$systemPointer]['id'], $textMessage);
					
					//Se reemplaza [SYSTEM] por el sistema asociado
					$textMessage = str_replace('[SYSTEM]', $dataNotification['System']['system_name'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/".$dataNotification['System']['table_name']."/view/".$dataNotification[$systemPointer]['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		function notificationToRequestUserForSetDate($system=null, $user=null, $relation=null, $date=null)
		{
			$notification = array();
			
			if($system != null && $user != null && $relation != null && $date != null)
			{
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['relation_id'] = $relation;
				$notification['Notification']['message_id'] = 18;  //Mensaje de rechazo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se verifica el puntero del sistema para tomar el ID
					$systemPointer = $this->verifiedSystemData($system);
					
					//Se reemplaza [ID] por el id del puntero
					$textMessage = str_replace('[ID]', $dataNotification[$systemPointer]['id'], $textMessage);
					
					//Se reemplaza [SYSTEM] por el sistema asociado
					$textMessage = str_replace('[SYSTEM]', $dataNotification['System']['system_name'], $textMessage);
					
					//Se reemplaza [SYSTEM] por el sistema asociado
					$textMessage = str_replace('[DATE]', $this->RequestAction('/external_functions/setDate/'.$date), $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/".$dataNotification['System']['table_name']."/view/".$dataNotification[$systemPointer]['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		function notificationToRequestUserForTotalRender($system=null, $user=null, $relation=null)
		{
			$notification = array();
			
			if($system != null && $user != null && $relation != null)
			{
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['relation_id'] = $relation;
				$notification['Notification']['message_id'] = 19;  //Mensaje de rechazo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se verifica el puntero del sistema para tomar el ID
					$systemPointer = $this->verifiedSystemData($system);
					
					//Se reemplaza [ID] por el id del puntero
					$textMessage = str_replace('[ID]', $dataNotification[$systemPointer]['id'], $textMessage);
					
					//Se reemplaza [SYSTEM] por el sistema asociado
					$textMessage = str_replace('[SYSTEM]', $dataNotification['System']['system_name'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/".$dataNotification['System']['table_name']."/view/".$dataNotification[$systemPointer]['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		/******************************************************************************************************************************************************
		*******************************************************************************************************************************************************
		******************************************************************************************************************************************************/
		
		/******************************************************************************************************************************************************
		**************************************************************ORDENES DE COMPRA*************************************************************
		******************************************************************************************************************************************************/
		
		//Se notifica al  jefe de adquisiciones que debe cotizar una orden x
		function notificationToBudgetOrder($system=null, $user=null, $relation=null)
		{
			$notification = array();
			
			if($system != null && $user != null && $relation != null)
			{
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['relation_id'] = $relation;
				$notification['Notification']['message_id'] = 20;  //Mensaje de rechazo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se verifica el puntero del sistema para tomar el ID
					$systemPointer = $this->verifiedSystemData($system);
					
					//Se reemplaza [ID] por el id del puntero
					$textMessage = str_replace('[ID]', $dataNotification[$systemPointer]['id'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/".$dataNotification['System']['table_name']."/view/".$dataNotification[$systemPointer]['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		function notificationToPurchaseOrderRequest($system=null, $user=null, $relation=null)
		{
			$notification = array();
			
			if($system != null && $user != null && $relation != null)
			{
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['relation_id'] = $relation;
				$notification['Notification']['message_id'] = 21;  //Mensaje de rechazo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se verifica el puntero del sistema para tomar el ID
					$systemPointer = $this->verifiedSystemData($system);
					
					//Se reemplaza [ID] por el id del puntero
					$textMessage = str_replace('[ID]', $dataNotification[$systemPointer]['id'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/".$dataNotification['System']['table_name']."/view/".$dataNotification[$systemPointer]['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		function notificationDeclinedBudgetedOrder($system=null, $user=null, $relation=null)
		{
			$notification = array();
			
			if($system != null && $user != null && $relation != null)
			{
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['relation_id'] = $relation;
				$notification['Notification']['message_id'] = 22;  //Mensaje de rechazo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se verifica el puntero del sistema para tomar el ID
					$systemPointer = $this->verifiedSystemData($system);
					
					//Se reemplaza [ID] por el id del puntero
					$textMessage = str_replace('[ID]', $dataNotification[$systemPointer]['id'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/".$dataNotification['System']['table_name']."/view/".$dataNotification[$systemPointer]['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		function notificationToAdministrationManager($system = null, $user = null, $relation = null)
		{
			$notification = array();
			
			if($system != null && $user != null && $relation != null)
			{
				$notification['Notification']['system_id'] = $system;
				$notification['Notification']['user_id'] = $user;
				$notification['Notification']['relation_id'] = $relation;
				$notification['Notification']['message_id'] = 23;  //Mensaje de rechazo por defecto en tabla "Messages"
				$notification['Notification']['readed'] = 0;
				
				$this->Notification->begin();
				
				if($this->Notification->saveAll($notification))
				{
					$this->Notification->commit();
					
					$last_id = $this->Notification->getLastInsertID();
					$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $last_id)));
					
					//Se extrae el texto de aviso por defecto
					$textMessage = $dataNotification['Message']['message'];
					
					//Se verifica el puntero del sistema para tomar el ID
					$systemPointer = $this->verifiedSystemData($system);
					
					//Se reemplaza [ID] por el id del puntero
					$textMessage = str_replace('[ID]', $dataNotification[$systemPointer]['id'], $textMessage);
					
					//Se genera la url de la notificacion
					$url = "/".$dataNotification['System']['table_name']."/view/".$dataNotification[$systemPointer]['id'];
					
					$dataNotification['Notification']['message_to_user'] = $textMessage;
					$dataNotification['Notification']['url'] = $url;
					$dataNotification['Notification']['css_class'] = $dataNotification['Message']['css_class'];
					
					$this->Notification->begin();
					
					if($this->Notification->saveAll($dataNotification['Notification']))
					{
						$this->Notification->commit();
						
						return true;
					}
					
					$this->Notification->rollback();
				}
				
				$this->Notification->rollback();
			}
		}
		
		
		/******************************************************************************************************************************************************
		*******************************************************************************************************************************************************
		******************************************************************************************************************************************************/

		function markAsReaded($id=null)
		{
			if($id != null)
			{
				$dataNotification = $this->Notification->find('first', array('conditions' => array('Notification.id' => $id), 'fields' => array('Notification.id', 'Notification.url', 'Notification.readed')));
				
				if($dataNotification != false)
				{
					$dataNotification['Notification']['readed'] = 1;
					
					$this->Notification->begin();
					
					if($this->Notification->save($dataNotification))
					{
						$this->Notification->commit();
						$this->Session->setFlash('Notificación marcada como leida', 'flash_success');
						
						if($dataNotification['Notification']['url'] == '#')
						{
							$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
						}
						else
						{
							$this->redirect($dataNotification['Notification']['url']);
						}	
					}
					else
					{
						$this->Notification->rollback();
						$this->Session->setFlash('La notificacion no se pudo marcar como leida, intentelo nuevamente', 'flash_alert');
						$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
					}
				}
				else
				{
					$this->Session->setFlash('La notificacion es nula, no se puede marcar como leida', 'flash_alert');
					$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash('La notificacion es nula, no se puede marcar como leida', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function markAllReaded()
		{
			$dataNotifications = $this->Notification->find('all', array('conditions' => array('Notification.user_id' => $this->Auth->user('id'), 'Notification.readed' => 0)));
			
			if($dataNotifications != false)
			{
				for($x=0; $x < count($dataNotifications); $x++)
				{
					$dataNotifications[$x]['Notification']['readed'] = 1;
				}

				$this->Notification->begin();
				
				if($this->Notification->saveAll($dataNotifications))
				{
					$this->Notification->commit();
					$this->Session->setFlash('Notificaciónes marcadas como leidas', 'flash_success');
					$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
				}
				else
				{
					$this->Notification->rollback();
					$this->Session->setFlash('La notificaciones no se pudieron marcar como leidas, intentelo nuevamente', 'flash_alert');
					$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash('No existen notificaciones para marcar como leidas.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		//Verifica el puntero del sistema
		function verifiedSystemData($system)
		{
			if($system == 1)
				return "RenderFundRelation";
			if($system == 2)
				return "PurchaseOrderRelation";
			if($system == 3)
				return "PaymentRequestRelation";
			if($system == 4)
				return "ServiceContractRelation";
			if($system == 5)
				return "HumanResourceRelation";
			if($system == 6)
				return "OperationRelation";
			if($system == 7)
				return "TaxiRequestRelation";
			if($system == 8)
				return "PromotionRelation";
			if($system == 9)
				return "FilmDemoRelation";
		}
		
		function verifiedSystemCss($system)
		{
			$data = $this->System->find('first', array('conditions' => array('System.id' => $system), 'fields' => array('System.css_class_url')));
			return $data['System']['css_class_url'];
		}
		
		function showAll()
		{
			$notificationsReaded = $this->Notification->find('all', array('conditions' => array('Notification.user_id' => $this->Auth->user('id'), 'Notification.readed' => 1), 'order' => 'Notification.modified DESC'));
			$notificationsUnreaded = $this->Notification->find('all', array('conditions' => array('Notification.user_id' => $this->Auth->user('id'), 'Notification.readed' => 0), 'order' => 'Notification.created DESC'));
			
			$this->set('notificationsReaded', $notificationsReaded);
			$this->set('notificationsUnreaded', $notificationsUnreaded);
			$this->set('title_for_layout', 'Notificaciones');
		}
	}
?>