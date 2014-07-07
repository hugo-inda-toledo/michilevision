<?php
echo $this->Html->tag('h3',$this->html->link('Conexiones SQL Server','/sqlserver_links/').' &gt; Importación de datos.');
echo $this->Session->flash();
?>

<script>
	function importSoftlandProviders()
	{
			var root = "/michilevision";
			$('#loading').show('fast');
			$.ajax({url: root + '/sqlserver_links/importProvidersFromSoftland/', error:function(xhr){alert("An error occured: " + xhr.status + " " + xhr.statusText); $('#loading').hide('fast');}, 
													success: function(result){$('#loading').hide('fast'); $("#successProviders").show('fast');}});
	}
	
	function importSoftlandCostCenters()
	{
			var root = "/michilevision";
			$('#loading').show('fast');
			$.ajax({url: root + '/sqlserver_links/importCostCentersFromSoftland', error:function(xhr){alert("An error occured: " + xhr.status + " " + xhr.statusText); $('#loading').hide('fast');}, 
													success: function(result){$('#loading').hide('fast');$('#flash-message').attr('class', 'flash_success');$('#flash-message').show('fast');}});
													
													
	}
</script>

<table>
	<tr>
		<td><?php echo $this->Html->link('Importar Proveedores de Softland', '/sqlserver_links/importProvidersFromSoftland', array('id' => 'softlandProviders', 'class' => 'link_admin32x32 link_erp')); ?></td>
		<td><?php echo $this->Html->link('Importar Centros de costo de Softland', '/sqlserver_links/importCostCentersFromSoftland', array('class' => 'link_admin32x32 link_erp')); ?></td>
	</tr>
	<tr>
		<td colspan="2"><div id='loading' style='display:none;'>Cargando</div></td>
	</tr>
</table>


