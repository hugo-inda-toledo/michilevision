<?php
$this->layout = null;

if(count($selectSistemas) != 0)
{
	echo $form->input('CentroCostoUsuario.usuario_sistema_id', array
												(
													'type'=>'select', 
													'options' => $selectSistemas,
													'id' => 'SelectSistema',
													'empty' => ''
												)
											);
	
	echo $form->input('CentroCostoUsuario.CentroDeCostoAsociado', array
												( 
													'type'=>'select', 
													'options'=>$resultCentroCostos, 
													'multiple' => 'multiple', 
													'div'=>array('class'=>'select')
												)
											);
	
	echo $form->end('Guardar Asignación');

}



else
{
	echo '<div class="error-message">No existen sistemas asociados a este usuario</div><br />';
	echo $html->link ('Agregue un sistema a un usuario', '/user_systems/add', array('class' => 'link_admin link_add'));
}

?>