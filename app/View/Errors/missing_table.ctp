<script type="text/javascript">
	function redireccionar()
	{
		window.locationf =  history.back();
	} 
</script>

<?php
	echo $this->Html->para(null, $this->Html->image('document-help-icon.png'), array('align' => 'center'));
	echo '<strong>'.$this->Html->para(null, 'Error de Tabla.', array('align' => 'center')).'</strong>';
	echo "<p align='center'>".__d('cake_dev', 'La tabla %1$s para el modelo %2$s no fue encontrada en la base de datos %3$s.', '<em>' . $table . '</em>',  '<em>' . $class . '</em>', '<em>' . $ds . '</em>')."</p>";
	echo '<h3>'.$this->Html->para(null, $this->Html->link('Volver atras.', '#', array('onClick' => 'Javascript:redireccionar();')), array('align' => 'center')).'</h3>';
?>