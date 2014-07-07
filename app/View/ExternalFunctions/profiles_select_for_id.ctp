<?php
	$this->layout = null;
	
	if(count($selectProfiles) != 0)
	{
		echo $this->form->input('UserProfile.profile_id', array('label' => 'Perfiles', 'id' => 'SelectProfile', 'empty' => '', 'type' =>'select', 'options' => $selectProfiles, 'multiple' => 'multiple'));
		echo $this->form->end('Guardar Asignacion');
	}
	
	
	else
	{
		echo '<div class="error-message">No existen perfiles asociados a este sistema</div><br />';
		echo $this->html->link ('Agregue un perfil a un sistema', '/profiles/add', array('class' => 'link_admin link_add'));
	}
?>