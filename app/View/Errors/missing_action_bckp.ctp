<script type="text/javascript">
	function redireccionar()
	{
		window.locationf =  history.back();
	} 
	setTimeout ("redireccionar()", 3000);
</script>

<?php	
	echo '<h3>'.$this->Html->para(null, 'Al parecer esta pagina no existe.<br>en 3 segundos seras redirigido a la pagina anterior', array('align' => 'center')).'</h3>';
?>