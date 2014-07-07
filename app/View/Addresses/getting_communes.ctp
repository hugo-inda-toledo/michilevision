<?php
	echo $this->layout = null;
	
	if($communes != false)
	{
	?>
		<script>
		$('#loadingCommunes').hide('fast');
		</script>
	<?php
		echo $this->form->input('Address.commune_id', array('label' => 'Comuna', 'type' =>'select', 'options' => $communes, 'empty' => 'Seleccione una comuna'));
		echo $this->form->end('Agregar Dirección');
	}
	else
	{
	?>
		<script>
		$('#loadingCommunes').hide('fast');
		</script>
	<?php
		echo "No existen comunas para esta provincia";
	}
?>