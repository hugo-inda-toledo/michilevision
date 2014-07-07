<head>
	<?php $this->layout = NULL;?>
	<?php echo $this->Html->css('reset');?>
	<?php echo $this->Html->css('cake.generic');?>
	<?php echo $this->Html->css('jquery-ui-1.10.0.custom');?>
	<?php echo $this->Html->css('jquery.tipTip');?>
	<?php echo $this->Html->css('shadowbox_forms');?>
	<?php echo $this->Html->script('jquery-1.9.0.js');?>
	<?php echo $this->Html->script('jquery-ui-1.10.0.custom.js');?>
	<?php echo $this->Html->script('jquery.tipTip');?>
	<?php echo $this->Html->script('functions_system');?>
</head>
<br><br><br>
<?php
	echo $this->Session->flash();
	echo $this->Form->create('ProviderRequest');
	echo $this->Form->input('applicant_id', array('type' => 'hidden', 'value' => $applicant_id));
	echo $this->Form->input('provider_dni', array('label' => 'Rut de la empresa', 'type' => 'text'));
	echo $this->Form->input('provider_name', array('label' => 'Nombre de fantasia', 'type' => 'text'));
	echo $this->Form->input('provider_address', array('label' => 'Dirección de la empresa', 'type' => 'text'));
	echo $this->Form->end('Enviar solicitud');
?>