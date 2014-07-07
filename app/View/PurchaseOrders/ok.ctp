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


<?php
	echo '<br><br><br><br>';
	echo $this->Session->flash();
	echo '<br><br><p align="center"><input type="button" value="Cerrar Ventana" onclick="javascript:parent.Shadowbox.close();" /></p>';	
?>