<script>
$(function(){
	$('#sound').click(function(){
		
		if($('#sound').prop('checked') == true){
			$('embed').remove();
			$('body').append('<embed src="/michilevision/files/notification.mp3" autostart="true" hidden="true" loop="false">');     
		}
	}); 
});

function enabledButton()
{
	var attribute = $('#notifications').prop('checked');
	
	if(attribute == false){
		$('#sound').attr('checked', false);
		$('#sound').prop('disabled', true);
	}
	
	if(attribute == true){
		$('#sound').prop('disabled', false);
	}
	
}
</script>

<?php
		if($this->data['Setting']['receive_notifications'] == 1)
		{
			$checkNotification = "checked";
			$disabledButton = "";
		}
		else
		{
			$checkNotification = "";
			$disabledButton = "disabled";
		}
			
		if($this->data['Setting']['receive_notifications_with_sound'] == 1)
			$checkNotificationSound = "checked";
		else
			$checkNotificationSound = "";
			
		if($this->data['Setting']['receive_email_notifications'] == 1)
			$checkNotificationEmail = "checked";
		else
			$checkNotificationEmail = "";
	
	echo $this->Form->create(array('action' => 'settings', 'id' => 'UserLoginForm'));
	echo $this->Form->inputs(array('legend'  => 'Mis Preferencias ('.$activeUser['User']['name'].' '.$activeUser['User']['first_lastname'].')', 
													'Setting.receive_notifications' => array('label' => 'Recibir Notificaciones','type' => 'checkbox', 'checked' => $checkNotification , 'id' => 'notifications', 'onclick' => 'javascript:enabledButton();'),
													'Setting.receive_notifications_with_sound' => array('label' => 'Reproducir Sonido al Notificar','type' => 'checkbox', 'checked' => $checkNotificationSound, 'id' =>'sound', 'disabled' => $disabledButton),
													'Setting.receive_email_notifications' => array('label' => 'Recibir Correos con Notificaciones', 'type' => 'checkbox', 'checked' => $checkNotificationEmail)));

	echo $this->Form->end('Guardar');
?>

<br><br>
<div class="dashboard-row-2">
	<div class="dashboard-sec">
		<?php echo $this->Html->para(null, 'Acciones'); ?>
		<ul align="center">
			<li>
				<?php echo $this->Html->link('Ir al Dashboard', '/dashboard', array('class' => 'tip_tip_default link_admin32x32 link_menu', 'title' => 'Ir a Mis Preferencias'))?>
			</li>
			<li>
				<?php echo $this->Html->link('Ir a Mis Notificaciones', '/notifications/showAll', array('class' => 'tip_tip_default link_admin32x32 link_notification', 'title' => 'Ir a Todas Mis Notificaciones'))?>
			</li>
			<li>
				<?php echo $this->Html->link('Ir a Mi Información', '/users/myData', array('class' => 'tip_tip_default link_admin32x32 link_user', 'title' => 'Ir a Mis Datos en la Plataforma'))?>
			</li>
			<li>
				<?php echo $this->Html->link('Cerrar Sesión', '/users/logout', array('class' => 'tip_tip_default link_admin32x32 link_padlock', 'title' => 'Cierra esta sesión de usuario'))?>
			</li>
		</ul>
	</div>
</div>

</body>