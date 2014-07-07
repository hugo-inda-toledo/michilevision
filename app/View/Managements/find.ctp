<?php echo $this->Session->flash();?>


<script>
	
	function pulsar(e) 
	{
		tecla = (document.all) ? e.keyCode : e.which;
		return (tecla != 13);
	}
	
	function validateSearch()
	{
		var texto = $('#ManagementManagementParam').val();
		
		if(texto == '')
		{
			$('#alertMessage').show('fast');
			return false;
		}
		else
		{
			$('#alertMessage').hide('fast');
		}
			
		if($("#ManagementFindForm input[name='data[Management][management_type_search]']:radio").is(':checked')) 
		{
			$('#alertMessage2').hide('fast');
			$('#ManagementFindForm').submit();
		}
		else 
		{
			$('#alertMessage2').show('fast');
			return false;
		}
	}
</script>


<h3><?php echo $this->html->link('Gerencias','/managements/'); ?> &gt; Listar gerencias</h3>

<!-- Buscador de centros de costo con botones-->

<?php echo $this->form->create('Management', array('action' => 'find', 'inputDefaults' => array ('fieldset' => false, 'legend' => false), 'onkeypress' => 'return pulsar(event)')); ?>
<table border='0'>
	<tr>
		<td colspan='2'><h2><?php echo $this->html->link('Agregar gerencia','/managements/add/',array('class' => 'link_admin link_add')); ?></h2></td>
	</tr>
	<tr>
		<td><?php echo $this->form->input('management_param',array('label' => '', 'type'=>'text', 'placeholder' => 'Buscar...'))."<br><div id='alertMessage' style='display:none;'><h3>El texto de busqueda no puede estar vacío.</h3></div>"; ?></td>
		<td><?php echo $this->form->input('management_type_search', array(
																				'type' => 'radio', 
																				'separator' => '', 
																				'options' => array(
																											'Management.management_name' => 'Gerencia', 
																											'User.name' => 'Gerente',
																											'Management.cost_center_father_code' => 'C.Costo Padre', 
																											),
																				'checked' => 'Management.management_name'
																				))."<br><div id='alertMessage2' style='display:none;'><h3>Debes seleccionar un parametro de busqueda.</h3></div>"; ?>
		</td>
		<td><?php echo $this->form->button('Buscar', array('type' => 'button', 'onclick' => 'Javascript:validateSearch();',  'class' => 'a_button')); ?></td>
	</tr>
</table>
</form>

<!-- Fin del buscador -->

<?php 
	echo $this->Session->flash();

	if(!empty($gerencias))
	{
?>

<p align='center'><strong><u><font color='#CC2424'>Resultado de la busqueda.</font></u></strong></p>
<p align='center'><strong><font color='#008141' size="1">Parametro de busqueda: 
	<?php 
		if($dataSearch['Management']['management_type_search'] == 'Management.management_name')
			echo 'Nombre de la gerencia';
 		if($dataSearch['Management']['management_type_search'] == 'User.name')
			echo 'Gerente';
		if($dataSearch['Management']['management_type_search'] == 'Management.cost_center_father_code')
			echo 'Centro de costo padre';
		
		echo ', texto a buscar: '.$dataSearch['Management']['management_param'];?></font></strong>
</p>

<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% gerencias de un total de busqueda de %count% gerencias).'));?>
</div>

<table align="center">
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
		<th><?php echo $this->paginator->sort('management_name', 'Nombre gerencia', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre de gerencia'));?></th>
		<th><?php echo $this->paginator->sort('User.name', 'Gerente', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por gerente'));?></th>
		<th><?php echo $this->paginator->sort('cost_center_father_code', 'C.Costo Padre', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por centro de costo padre'));?></th>
		<th><?php echo $this->paginator->sort('Authorization.name', 'Autorizacion', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por tipo de autorizacion'));?></th>
		<th>Detalle</th>
		<th>Editar</th>
		<th>Eliminar</th>
	</tr>


    <?php foreach ($gerencias as $gerencia): ?>
    <tr>
        <td><?php echo $gerencia['Management']['id']; ?></td>
		<td><?php echo $gerencia['Management']['management_name'];?></td>
		<td><?php echo $this->Html->tag('span', $gerencia['User']['name'].' '.$gerencia['User']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/jquery_tables/showUserTable/'.$gerencia['User']['id'])));?></td>
		<td><?php echo $gerencia['Management']['cost_center_father_code']; ?></td>
		<td><?php echo $gerencia['Authorization']['name']; ?></td>

		<td>
            <?php echo $this->html->link("Ver Ficha","/managements/view/".$gerencia['Management']['id'], array('title' => 'Ver ficha de '.$gerencia['Management']['management_name'], 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		<td>
            <?php echo $this->html->link("Editar", "/managements/edit/".$gerencia['Management']['id'], array('title' => 'Editar ficha de '.$gerencia['Management']['management_name'], 'class' => 'tip_tip_default link_admin link_edit')); ?>
        </td>

		<td>
             <?php echo  $this->Html->link('Eliminar',
            	array('action' => 'delete', $gerencia['Management']['id']),
            	array('title' => 'Eliminar '.$gerencia['Management']['management_name'],'class' => 'tip_tip_default link_admin link_delete'),
            	'Usted está a punto de eliminar la siguiente gerencia :\n\n'.$gerencia['Management']['management_name'].'.\n\n\nDesea continuar...?');?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<br>
<div class="paging">
	<?php echo $this->paginator->prev('Anterior ', null, null, array('class' => 'disabled_prev'));?>
	<?php echo $this->paginator->numbers(array('separator' => '')); ?>
	<?php echo $this->paginator->next(' Siguiente', null, null, array('class' => 'disabled_next'));?>
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
		<li><p align='center'><?php echo $this->html->link('Volver al listado','/managements/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de gerencias'));?></p></li>
</ul>