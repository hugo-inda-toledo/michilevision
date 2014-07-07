<h3>Cambia tu clave</h3>
<?php echo $this->form->create(null, array('action' => 'reset_password_token', 'id' => 'UserLoginForm')); ?>
    <?php echo $this->form->hidden('User.reset_password_token'); ?>
	<?php echo $this->form->input('User.new_password',  array('type' => 'password', 'label' => 'Nueva Clave', 'between' => '<br />', 'type' => 'password') ); ?>
	<?php echo $this->form->input('User.confirm_password',  array('type' => 'password', 'label' => 'Confirma la nueva clave', 'between' => '<br />', 'type' => 'password') ); ?>
	<?php echo $this->form->end('Cambiar'); ?>