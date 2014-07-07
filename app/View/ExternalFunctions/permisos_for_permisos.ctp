<?php $this->layout = null;?>

<script type="text/javascript">
	inicializarDatePicker('range');
</script>

<?php
if(count($permisos) != 0)
{
	echo $this->form->input('UserPermission.permission_id', array
							(
								'type'=>'select', 
								'options' => $permisos,
								'id' => 'SelectPermiso',
								'label' => 'Permiso asociado'
								)
							);

	echo $this->form->input('UserPermission.start_date', array('label' => 'Fecha de inicio', 'type'=>'text', 'id'=>'datepicker-desde', 'readonly' => 'readonly'));
	echo $this->form->input('UserPermission.end_date', array('label' => 'Fecha de termino', 'type'=>'text', 'id'=>'datepicker-hasta', 'readonly' => 'readonly'));
	echo $this->form->end('Guardar Asignación');
}


else
{
	echo '<div class="error-message">No existen permisos asociados a este sistema</div><br /><br />';
	echo $this->html->link ('Agregue un permiso a un sistema', '/permissions/add', array('class' => 'link_admin link_add'));
}
?>