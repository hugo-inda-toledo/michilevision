<head>
	<?php $this->layout = NULL;?>
	<?php echo $this->Html->css('reset');?>
	<?php echo $this->Html->css('cake.generic');?>
	<?php echo $this->Html->css('jquery-ui-1.10.0.custom');?>
	<?php echo $this->Html->css('jquery.tipTip');?>
	<?php echo $this->Html->css('shadowbox_forms');?>
	<?php echo $this->Html->script('jquery-1.9.0.js');?>
	<?php echo $this->Html->script('jquery-ui-1.10.0.custom.js');?>
	<?php echo $this->Html->script('jquery.tipTip');?>
	<?php echo $this->Html->script('functions_system');?>
	<script>
		function closeSession()
		{
			parent.Shadowbox.close();
			//$(location).attr('/michilevision/users/logout',url);
		}
	</script>
</head>
<body>	
<?php
if(!empty($userSession)){
	
	if($userSession['User']['email_confirm'] == 1)
	{
		echo '<br><br><br><br>';
		echo $this->Session->flash();
		echo '<br><br><p align="center"><input type="button" value="Cerrar Ventana" onclick="javascript:parent.Shadowbox.close();" /></p>';	
	}
	
	else
	{
		echo '<p align="center"><font color="#FFFFFF"><strong>Al ser el primer inicio de sesion débes cambiar la contraseña</strong></font></p><br>';
		echo '<p align="center"><font color="#B40404"><strong><u>La contraseña debe ser de 6 caracteres y tener al menos una mayuscula y un numero.</u></strong></font></p>';
		echo $this->Session->flash();
		echo $this->form->create('User', array('id' => 'UserLoginForm'));
		echo $this->form->input('new_password', array('label' => 'Ingrese nueva contraseña', 'type' => 'password'));
		echo $this->form->input('new_password_confirm', array('label' => 'Confirme nueva contraseña', 'type' => 'password'));
		echo $this->Form->end('Listo!');
		echo "<br><br><br>";
		echo $this->html->link('Cerrar esta ventana', '#', array('class' => 'tip_tip_default link_admin link_delete', 'onClick' => 'Javascript:closeSession();'));
		echo "<br><br>";
		echo $this->Html->tag('h3', 'Aviso: Si no cambias la contraseña, cada vez que refresques el sitio volvera a aparecer este pop-up.', array('class' => 'link_admin link_alert'));
	}
}
?>
</body>
</html>