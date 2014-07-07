<?php
	echo $this->layout = null;
	
	if($provinces != false)
	{
	?>
		<script>
		$('#loadingProvinces').hide('fast');
		</script>
	<?php
		echo $this->form->input('Address.province_id', array('label' => 'Provincia', 'type' =>'select', 'id' => 'province', 'options' => $provinces, 'empty' => 'Seleccione una provincia',  'onChange' => 'Javascript:getCommunesForAddress();'));
	}
	else
	{
	?>
		<script>
		$('#loadingProvinces').hide('fast');
		</script>
	<?php
		echo "No existen provincias para esta región";
	}
?>