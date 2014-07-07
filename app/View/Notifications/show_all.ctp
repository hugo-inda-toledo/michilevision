<?php
echo $this->Html->tag('h3',$this->html->link('Inicio','/').' &gt; Notificaciones');
echo $this->Html->tag('h2','Mis Notificaciones', array('class' => 'link_admin link_notification'));
echo $this->Session->flash(); 
?>


<div class="notifications">
	<?php
	echo $this->Html->tag('h2','Notificaciones No Leidas', array('class' => 'link_admin link_notification'));
	echo '<table>';
	
		if($notificationsUnreaded != false) 
		{
			echo '<tr>
					<th>Fecha de Generación</th>
					<th>Mensaje</th>
				</tr>';	
					
			foreach($notificationsUnreaded as $notificationUnreaded) 
			{
				echo '<tr>
						<td>'.$notificationUnreaded['Notification']['created'].'</td>
						<td>'.$this->Html->link($notificationUnreaded['Notification']['message_to_user'],  '/notifications/markAsReaded/'.$notificationUnreaded['Notification']['id'], array('class' => 'tip_tip_default link_admin '.$notificationUnreaded['Notification']['css_class'], 'title' => 'Ir a la url')).'</td>
					</tr>';
			}
		}
		
		else {
			echo '<tr>
					<td>No tienes notificaciones pendientes por revisar.</td>
				</tr>';
		}
	
	echo '</table>';
	?>
</div>




<div class="notifications">
	<?php
	echo $this->Html->tag('h2','Notificaciones Leidas', array('class' => 'link_admin link_notification_readed'));
	echo '<table>';
	
		if($notificationsReaded != false) {
			echo '<tr>
					<th>Fecha de Lectura</th>
					<th>Mensaje</th>
					<th>Fecha de Generación</th>
				</tr>';
			
			foreach($notificationsReaded as $notificationReaded) {
				echo '<tr>
						<td>'.$notificationReaded['Notification']['modified'].'</td>
						<td>'.$this->Html->link($notificationReaded['Notification']['message_to_user'], $notificationReaded['Notification']['url'], array('class' => 'tip_tip_default link_admin '.$notificationReaded['Notification']['css_class'], 'title' => 'Ir a la url')).'</td>
						<td>'.$notificationReaded['Notification']['created'].'</td>
					</tr>';
			}
		}
		
		else {
			echo '<tr>
					<td>No tienes notificaciones leidas.</td>
				</tr>';
		}
	
	echo '</table>';
	?>
</div>