<?php
$this->layout = null;

if(count($selectProfiles) != 0)
{
	if(count($selectPermisos) != 0)
	{
		echo $this->form->input('ProfilePermission.profile_id', array
											(
												'label' => 'Perfil Asociado',
												'type'=>'select', 
												'options' => $selectProfiles,
												'id' => 'SelectProfile',
												'empty' => ''
											)
		);
		echo $this->form->input('ProfilePermission.permission_id', array
												( 
													'label' => 'Permiso Asociado',
													'type'=>'select', 
													'options'=> $selectPermisos, 
													'multiple' => 'multiple', 
													'div'=>array('class'=>'select')
												)
		);
		
		echo $this->form->end('Guardar Asignación');
	}
	
	
	else
	{
		echo '<div class="error-message">No existen permisos asociados a este sistema</div><br /><br />';
		echo $this->html->link ('Agregue un permiso a un sistema', '/permissions/add', array('class' => 'link_admin link_add'));
	}
}



else{
	echo '<div class="error-message">No existen perfiles asociados a este sistema</div><br /><br />';
	echo $this->html->link ('Agregue un perfil a un sistema', '/profiles/add', array('class' => 'link_admin link_add'));
}
?>