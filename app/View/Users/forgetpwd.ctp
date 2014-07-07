<?php
	echo $this->Form->create(array('action' => 'forgetpwd', 'id' => 'UserLoginForm'));
	echo $this->Form->inputs(array('legend'  => 'Recuperar Clave', 
													'email' => array('label' => 'Ingrese su correo electronico')
												)
										);
	echo $this->Form->end('Recuperar');
	echo '<br>';
	echo '<p align="center">'.$html->link('Volver al inicio','/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al inicio de sesion')).'</p>';
?>