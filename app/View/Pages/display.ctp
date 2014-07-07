<?php
echo $this->Session->flash();
?>

<h3>Inicio &gt; Iniciar sesión</h3>

<?php
	echo $this->Form->create(array('id' => 'UserLoginForm'));
	echo $this->Form->inputs(array('legend'  => 'Cambio de Clave de Acceso', 'password' => array('label' => 'Escribe una nueva contraseña'), 'password_verified' => array('label' => 'Repite la nueva contraseña', 'type' => 'password')));
	echo $this->Form->end('Confirmar');
?>