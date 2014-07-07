<?php

echo $this->Form->create(array('action' => 'login'));
echo $this->Form->inputs(array('legend'  => 'Iniciar Sesión', 
								'username' => array('label' => 'Correo electrónico ', 'between' => '<span>&nbsp;Ej: nombre.apellido@micorreo.cl</span><br />', 'size' => 30),
								'password' => array('label' => 'Contraseña'), 
								'rememberMe' => array('label' => 'Recordarme', 'type' => 'checkbox')
							)
						);
echo '<div class="forgot-password">'.$this->html->link('Olvidaste tu clave de acceso?',array('controller'=>'users','action'=>'forgot_password'), array('class'=>'link_admin link_help')).'</div>';
echo $this->Form->end('Ingresar');
?>