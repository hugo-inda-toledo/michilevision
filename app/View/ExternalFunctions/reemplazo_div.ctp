<?php $this->layout = null;?>

<script type="text/javascript">
	inicializarDatePicker('range');
</script>

<?php
echo $this->form->input('Replacement.replacing_user_id', array('label' => 'Reemplazante','type' =>'select', 'options' => $selectUsuarios));
echo $this->form->input('Replacement.type_replacement_id', array('label' => 'Tipo de reemplazo', 'type' =>'select', 'options' => $selectTipoReemplazos));
echo $this->form->input('Replacement.management_id', array('label' => 'Gerencia asociada', 'type' =>'select', 'options' => $selectGerencias, 'empty' => ''));
echo $this->form->input('Replacement.start_date', array('label' => 'Fecha de inicio', 'type'=>'text', 'id'=>'datepicker-desde', 'readonly' => 'readonly'));
echo $this->form->input('Replacement.end_date', array('label' => 'Fecha de termino', 'type'=>'text', 'id'=>'datepicker-hasta', 'readonly' => 'readonly'));
echo $this->form->end('Crear Reemplazo');
?>