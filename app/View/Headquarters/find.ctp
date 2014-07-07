<?php echo $this->Session->flash();?>


<script>
	
	function pulsar(e) 
	{
		tecla = (document.all) ? e.keyCode : e.which;
		return (tecla != 13);
	}
	
	function validateSearch()
	{
		var texto = $('#HeadquarterHeadquarterParam').val();
		
		if(texto == '')
		{
			$('#alertMessage').show('fast');
			return false;
		}
		else
		{
			$('#alertMessage').hide('fast');
		}
			
		if($("#HeadquarterFindForm input[name='data[Headquarter][headquarter_type_search]']:radio").is(':checked')) 
		{
			$('#alertMessage2').hide('fast');
			$('#HeadquarterFindForm').submit();
		}
		else 
		{
			$('#alertMessage2').show('fast');
			return false;
		}
	}
</script>


<h3><?php echo $this->html->link('Jefaturas','/headquarters/'); ?> &gt; Listar jefaturas</h3>

<!-- Buscador de centros de costo con botones-->

<?php echo $this->form->create('Headquarter', array('action' => 'find', 'inputDefaults' => array ('fieldset' => false, 'legend' => false), 'onkeypress' => 'return pulsar(event)')); ?>
<table border='0'>
	<tr>
		<td colspan='2'><h2><?php echo $this->html->link('Agregar jefatura','/headquarters/add/',array('class' => 'link_admin link_add')); ?></h2></td>
	</tr>
	<tr>
		<td><?php echo $this->form->input('headquarter_param',array('label' => '', 'type'=>'text', 'placeholder' => 'Buscar...'))."<br><div id='alertMessage' style='display:none;'><h3>El texto de busqueda no puede estar vacío.</h3></div>"; ?></td>
		<td><?php echo $this->form->input('headquarter_type_search', array(
																				'type' => 'radio', 
																				'separator' => '', 
																				'options' => array(
																											'Headquarter.headquarter_name' => 'Jefatura', 
																											'User.name' => 'Jefe',
																											'Headquarter.cost_center_code' => 'C.Costo', 
																											),
																				'checked' => 'Headquarter.headquarter_name'
																				))."<br><div id='alertMessage2' style='display:none;'><h3>Debes seleccionar un parametro de busqueda.</h3></div>"; ?>
		</td>
		<td><?php echo $this->form->button('Buscar', array('type' => 'button', 'onclick' => 'Javascript:validateSearch();',  'class' => 'a_button')); ?></td>
	</tr>
</table>
</form>

<!-- Fin del buscador -->

<?php 
	echo $this->Session->flash();

	if(!empty($jefaturas))
	{
?>

<p align='center'><strong><u><font color='#CC2424'>Resultado de la busqueda.</font></u></strong></p>
<p align='center'><strong><font color='#008141' size="1">Parametro de busqueda: 
	<?php 
		if($dataSearch['Headquarter']['headquarter_type_search'] == 'Headquarter.headquarter_name')
			echo 'Nombre de la jefatura';
 		if($dataSearch['Headquarter']['headquarter_type_search'] == 'User.name')
			echo 'Jefe';
		if($dataSearch['Headquarter']['headquarter_type_search'] == 'Headquarter.cost_center_code')
			echo 'Centro de costo';
		
		echo ', texto a buscar: '.$dataSearch['Headquarter']['headquarter_param'];?></font></strong>
</p>

<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% jefaturas de un total de %count% jefaturas).'));?>
</div>
<table>
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
		<th><?php echo $this->paginator->sort('headquarter_name', 'Jefatura', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por jefatura'));?></th>
		<th><?php echo $this->paginator->sort('cost_center_code', 'C.Costo', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por centro de costo'));?></th>
		<th><?php echo $this->paginator->sort('User.name', 'Jefe Asociado', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por jefe'));?></th>
		<th><?php echo $this->paginator->sort('Management.management_name', 'Gerencia', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por gerencia'));?></th>
		<th>Detalle</th>
		<th>Editar</th>
		<th>Eliminar</th>
	</tr>

    <?php foreach ($jefaturas as $jefatura): ?>
    <tr>
        <td><?php echo $jefatura['Headquarter']['id']; ?></td>
		<td><?php echo $jefatura['Headquarter']['headquarter_name']; ?></td>
		<td><?php echo $jefatura['Headquarter']['cost_center_code']; ?></td>
   		<td><?php echo $this->Html->tag('span', $jefatura['User']['name'].' '.$jefatura['User']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$jefatura['User']['id'])));?></td>
   		<td><?php echo $this->Html->tag('span', $jefatura['Management']['management_name'], array('class' => 'link_admin tip_tip link_management pointer','title' => $this->RequestAction('/external_functions/showGerenciaTable/'.$jefatura['Management']['id'])));?></td>
		<td>
            <?php echo $this->html->link('Ver Ficha','/headquarters/view/'.$jefatura['Headquarter']['id'], array('title' => "Ver ficha jefatura", 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		<td>
            <?php echo $this->html->link('Editar','/headquarters/edit/'.$jefatura['Headquarter']['id'], array('title' => 'Editar ficha jefatura', 'class' => 'tip_tip_default link_admin link_edit')); ?>
        </td>
		<td>
             <?php echo $this->Html->link('Eliminar',
            	array('action' => 'delete', $jefatura['Headquarter']['id']),
            	array('title' => 'Eliminar esta jefatura','class' => 'tip_tip_default link_admin link_delete'),
            	'Usted está a punto de eliminar la jefatura seleccionada\n\nDesea continuar...?');?>
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
		<li><p align='center'><?php echo $this->html->link('Volver al listado','/headquarters/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de jefaturas'));?></p></li>
</ul>