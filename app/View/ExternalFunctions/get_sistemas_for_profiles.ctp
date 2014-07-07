<?php
$this->layout = null;

if(count($selectSistemas) != 0)
{
	echo $this->form->input('Sistema.system_id', array
											(
												'label' => 'Sistema',
												'type'=>'select', 
												'options' => $selectSistemas,
												'id' => 'SelectSistema',
												'empty' => ' ',
												'onChange' => 'javascript:getProfiles();'
											)
										);


	echo '<div id="profiles"></div>';

}
else
{
	echo '<div class="error-message">No existen sistemas asociados a este usuario</div><br /><br />';
	echo $this->html->link ('Agregue un sistema a un usuario', '/user_systems/add', array('class' => 'link_admin link_add'));
}

?>