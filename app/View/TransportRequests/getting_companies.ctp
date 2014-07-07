<?php
	echo $this->layout = null;
	
	if($companies != false)
	{
	?>
		<script>
		$('#loadingCompanies').hide('slow');
		</script>
	<?php
		echo $this->form->input('TransportRequest.transport_company_id', array('label' => 'Empresa de transporte', 'type' =>'select', 'options' => $companies, 'empty' => 'Seleccione una compania'));
	}
	else
	{
	?>
		<script>
		$('#loadingCompanies').hide('slow');
		</script>
	<?php
		echo $this->Html->tag('h3', 'No existen empresas para este tipo de transporte.', array('class' => 'link_admin link_alert'));
	}
?>