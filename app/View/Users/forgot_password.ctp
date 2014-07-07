<?php
	echo $this->Form->create(array('action' => 'forgot_password', 'id' => 'UserLoginForm'));
	echo $this->Form->inputs(array('legend'  => 'Recuperar Clave', 
													'email' => array('label' => 'Ingrese su correo electronico')
												)
										);
	echo $this->Form->end('Recuperar');
	echo '<br>';
	echo '<p align="center">'.$this->html->link('Volver al inicio','/users/login',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al inicio de sesion')).'</p>';
?>