<?php
$this->layout = null;

if(count($perfiles) != 0)
{
	if(count($permisos) != 0)
	{
		echo $form->input('ProfilePermiso.profile_id', array
											(
												'type'=>'select', 
												'options' => $perfiles,
												'id' => 'SelectProfile'
											)
										);

		echo $form->input('ProfilePermiso.permiso_id', array
											( 
												'type'=>'select', 
												'options'=>$permisos, 
												'multiple' => 'multiple', 
												'div'=>array('class'=>'select')
											)
										);

		echo $form->end('Guardar Asignacion');
	}
	
	
	else
	{
		echo '<div class="error-message">No existen permisos asociados a este sistema</div><br /><br />';
		echo $html->link ('Agregue un permiso a un sistema', '/permissions/add', array('class' => 'link_admin link_add'));
	}
}




else
{
	echo '<div class="error-message">No existen perfiles asociados a este sistema</div><br /><br />';
	echo $html->link ('Agregue un perfil a un sistema', '/profiles/add', array('class' => 'link_admin link_add'));
}


?>