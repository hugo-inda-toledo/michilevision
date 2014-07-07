<?php
$this->layout = null;

if(count($selectSistemas) != 0)
{
	echo $this->form->input('CostCenterUser.system_id', array
												(
													'type'=>'select', 
													'options' => $selectSistemas,
													'id' => 'SelectSistema',
													'label' => 'Sistema Asociado'
												)
											);
	
	echo $this->form->input('CostCenterUser.cost_center_id', array
												( 
													'type'=>'select', 
													'options'=>$resultCentroCostos, 
													'multiple' => 'multiple',
													'label' => 'Centros de costo',
												'div'=>array('class'=>'select')
												)
											);

	echo $this->form->end('Guardar Asignación');

}


else
{
		
	echo '<div class="error-message">No existen sistemas asociados a este usuario</div><br />';	
	echo $this->html->link ('Agregue un sistema a un usuario', '/user_systems/add', array('class' => 'tip_tip_default link_admin link_add'));
}

?>