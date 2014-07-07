<?php
$this->layout = null;

if(count($selectSistemas) != 0)
{
	echo $this->form->input('System.system_id', array
										(
											'type'=>'select', 
											'options' => $selectSistemas,
											'id' => 'SelectSistema',
											'empty' => '',
											'label' => 'Sistema del usuario',
											'onChange' => 'javascript:getPermisos2();'
										)
									);


	echo '<div id="permisos2"></div>';

}
else
{
	echo '<div class="error-message">No existen sistemas asociados a este usuario</div><br /><br />';
	echo $this->html->link ('Agregue un sistema a un usuario', '/user_systems/add', array('class' => 'link_admin link_add'));
}

?>