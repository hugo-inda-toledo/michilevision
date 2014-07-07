<script type="text/javascript">
	function redireccionar()
	{
		window.locationf =  history.back();
	} 
	setTimeout ("redireccionar()", 3000);
</script>

<?php
	echo $this->Html->para(null, $this->Html->image('document-help-icon.png'), array('align' => 'center'));
	echo '<strong>'.$this->Html->para(null, 'Error de Controlador', array('align' => 'center')).'</strong>';
	echo '<h3>'.$this->Html->para(null, 'Al parecer este controlador no existe.<br>en 3 segundos seras redirigido a la pagina anterior', array('align' => 'center')).'</h3>';
?>