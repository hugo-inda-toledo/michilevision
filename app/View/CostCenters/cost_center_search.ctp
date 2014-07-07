<h3><?php echo $html->link('Centro de costos','/cost_centers/'); ?> &gt; Listar centros de costos</h3>

<?php
	echo $form->create('CostCenter', array('action' => 'CostCenterSearch', 'inputDefaults' => array ('fieldset' => false, 'legend' => false)));		
	echo $form->input('cost_center_param',array('label' => '', 'type'=>'text'));
	echo $form->input('cost_center_type_search', array('type' => 'radio', 'separator' => '', 'options' => array('cost_center_name' => 'Nombre', 	'cost_center_code' => 'Codigo')));
	echo $form->submit('Buscar');
?>

<h2><?php echo $html->link('Agregar centro de costos','/cost_centers/add/',array('class' => 'link_admin link_add')); ?></h2>
<h2><?php echo $html->link('Asignar centro de costo a usuario','/cost_center_users/add/',array('class' => 'link_admin link_connect')); ?></h2>

<?php 
	echo $this->Session->flash();

	if(!empty($cost_centers))
	{
?>
		
<div class="paging_results">
	<?php echo $paginator->counter(array('format' => 'Pagina %page% de %pages% (Encontrado %current% centros de costo de un total de %count% centros de costo).'));?>
</div>

<table align="center">
    <tr>
        <th><?php echo $paginator->sort('Id', 'id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
		<th><?php echo $paginator->sort('Nombre del Centro de costo', 'cost_center_name', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre del centro de costo'));?></th>
		<th><?php echo $paginator->sort('Gerencia Asociada', 'Management.management_name', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por gerencia asociada'));?></th>
		<th><?php echo $paginator->sort('Codigo', 'cost_center_code', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por codigo de centro de costo'));?></th>
		<th>Ver</th>
		<th>Editar</th>
		<th>Eliminar</th>
    </tr>

    <!-- Aqui se hace el ciclo que recorre nuestros arreglo $posts , imprimiendo la informaci?n de cada post-->

    <?php foreach ($cost_centers as $cost_center): ?>
    <tr>
        <td><?php echo $cost_center['CostCenter']['id']; ?></td>
		<td><?php echo strtoupper($cost_center['CostCenter']['cost_center_name']); ?></td>
		<td><?php echo $cost_center['Management']['management_name']; ?></td>
		<td><?php echo $cost_center['CostCenter']['cost_center_code']; ?></td>
		<td>
            <?php echo $html->link('Ver Ficha','/cost_centers/view/'.$cost_center['CostCenter']['id'], array('title' => 'Ver ficha de '.$cost_center['CostCenter']['cost_center_name'], 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		<td>
            <?php echo $html->link('Editar','/cost_centers/edit/'.$cost_center['CostCenter']['id'], array('title' => 'Editar ficha de '.$cost_center['CostCenter']['cost_center_name'], 'class' => 'tip_tip_default link_admin link_edit')); ?>
        </td>
		<td>
			<?php echo  $this->Html->link('Eliminar', 
				array('action' => 'delete', $cost_center['CostCenter']['id']), 
				array('title' => 'Eliminar ficha de '.$cost_center['CostCenter']['cost_center_name'], 'class' => 'tip_tip_default link_admin link_delete'),
					'Usted está a punto de eliminar el siguiente centro de costo :\n\n'.$cost_center['CostCenter']['cost_center_name'].'.\n\n\nDesea continuar...?')?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<br>
<div class="paging">
	<?php echo $paginator->prev('Anterior', null, null, array('class' => 'disabled_prev'));?>
	<?php echo $paginator->numbers(array('separator' => '')); ?>
	<?php echo $paginator->next('Siguiente', null, null, array('class' => 'disabled_next'));?>
</div>

<?php
	}
	else
	{
		echo "<br><br><br><br><br>";
		echo "<h3>La busqueda no arrojo resultados.</h3>";
	}
?>

<ul>
		<li><?php echo $html->link('Volver al listado','/cost_centers/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de centros de costos'));?></li>
</ul>