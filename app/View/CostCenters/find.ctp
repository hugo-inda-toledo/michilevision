<script>
	
	function pulsar(e) 
	{
		tecla = (document.all) ? e.keyCode : e.which;
		return (tecla != 13);
	}
	
	function validateSearch()
	{
		var texto = $('#CostCenterCostCenterParam').val();
		
		if(texto == '')
		{
			$('#alertMessage').show('fast');
			return false;
		}
		else
		{
			$('#alertMessage').hide('fast');
		}
			
		if($("#CostCenterFindForm input[name='data[CostCenter][cost_center_type_search]']:radio").is(':checked')) 
		{
			$('#alertMessage2').hide('fast');
			$('#CostCenterFindForm').submit();
		}
		else 
		{
			$('#alertMessage2').show('fast');
			return false;
		}
	}
</script>

<?php 
	echo $this->Session->flash();
?>

<h3><?php echo $this->html->link('Centro de costos','/cost_centers/'); ?> &gt; Listar centros de costos</h3>

<!-- Buscador de centros de costo con botones-->
<?php echo $this->form->create('CostCenter', array('action' => 'find', 'inputDefaults' => array ('fieldset' => false, 'legend' => false), 'onkeypress' => 'return pulsar(event)')); ?>
<table border='0'>
	<tr>
		<td><h2><?php echo $this->html->link('Agregar centro de costos','/cost_centers/add/',array('class' => 'link_admin link_add')); ?></h2></td>
		<td><h2><?php echo $this->html->link('Asignar centro de costo a usuario','/cost_center_users/add/',array('class' => 'link_admin link_connect')); ?></h2></td>
	</tr>
	<tr>
		<td><?php echo $this->form->input('cost_center_param',array('label' => '', 'type'=>'text', 'placeholder' => 'Buscar...'))."<br><div id='alertMessage' style='display:none;'><h3>El texto de busqueda no puede estar vac√≠o.</h3></div>"; ?></td>
		<td><?php echo $this->form->input('cost_center_type_search', array(
																				'type' => 'radio', 
																				'separator' => '', 
																				'options' => array(
																											'cost_center_name' => 'Nombre', 
																											'cost_center_code' => 'Codigo'
																											),
																				'checked' => 'cost_center_name'
																				))."<br><div id='alertMessage2' style='display:none;'><h3>Debes seleccionar un parametro de busqueda.</h3></div>"; ?>
		</td>
		<td><?php echo $this->form->button('Buscar', array('type' => 'button', 'onclick' => 'Javascript:validateSearch();',  'class' => 'a_button')); ?></td>
	</tr>
</table>
</form>

<!-- Fin del buscador -->

<?php 
	if(!empty($cost_centers))
	{
?>


<p align='center'><strong><u><font color='#CC2424'>Resultado de la busqueda.</font></u></strong></p>
<p align='center'><strong><font color='#008141' size="1">Parametro de busqueda: <?php if($dataSearch['CostCenter']['cost_center_type_search'] == 'cost_center_code')echo 'Codigo';else echo 'Nombre'; echo ', texto a buscar: '.$dataSearch['CostCenter']['cost_center_param'];?></font></strong></p>

<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% centros de costo de un total de busqueda de %count% centros de costo).'));?>
</div>

<table align="center">
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
		<th><?php echo $this->paginator->sort('cost_center_name', 'Nombre del Centro de costo', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre del centro de costo'));?></th>
		<th><?php echo $this->paginator->sort('Management.management_name', 'Gerencia Asociada', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por gerencia asociada'));?></th>
		<th><?php echo $this->paginator->sort('cost_center_code', 'Codigo', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por codigo de centro de costo'));?></th>
		<th>Ver</th>
		<th>Editar</th>
		<th>Eliminar</th>
    </tr>

    <!-- Aqui se hace el ciclo que recorre nuestros arreglo $posts , imprimiendo la informaci?n de cada post-->

    <?php foreach ($cost_centers as $cost_center): ?>
    <tr>
        <td><?php echo $cost_center['CostCenter']['id']; ?></td>
		<td><?php echo $cost_center['CostCenter']['cost_center_name']; ?></td>
		<td><?php echo $cost_center['Management']['management_name']; ?></td>
		<td><?php echo $cost_center['CostCenter']['cost_center_code']; ?></td>
		<td>
            <?php echo $this->html->link('Ver Ficha','/cost_centers/view/'.$cost_center['CostCenter']['id'], array('title' => 'Ver ficha de '.$cost_center['CostCenter']['cost_center_name'], 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		<td>
            <?php echo $this->html->link('Editar','/cost_centers/edit/'.$cost_center['CostCenter']['id'], array('title' => 'Editar ficha de '.$cost_center['CostCenter']['cost_center_name'], 'class' => 'tip_tip_default link_admin link_edit')); ?>
        </td>
		<td>
			<?php echo  $this->Html->link('Eliminar', 
				array('action' => 'delete', $cost_center['CostCenter']['id']), 
				array('title' => 'Eliminar ficha de '.$cost_center['CostCenter']['cost_center_name'], 'class' => 'tip_tip_default link_admin link_delete'),
					'Usted est· a punto de eliminar el siguiente centro de costo :\n\n'.$cost_center['CostCenter']['cost_center_name'].'.\n\n\nDesea continuar...?')?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<br>
<div class="paging">
	<?php echo $this->paginator->prev('Anterior', null, null, array('class' => 'disabled_prev'));?>
	<?php echo $this->paginator->numbers(array('separator' => '')); ?>
	<?php echo $this->paginator->next('Siguiente', null, null, array('class' => 'disabled_next'));?>
</div>

<?php
	}
	else
	{
		echo "<br><br><br>";
		echo "<h3><p align='center'>La busqueda no arrojo resultados.</p></h3>";
	}
?>

<ul>
		<li><p align='center'><?php echo $this->html->link('Volver al listado','/cost_centers/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de centros de costos'));?></p></li>
</ul>