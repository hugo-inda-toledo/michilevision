<?php echo $this->Session->flash();?>

<h3><?php echo $this->html->link('Provincias','/provinces/'); ?> &gt; Listar provincias</h3>

<?php echo $this->html->link('Crear nueva provincia','/provinces/add/',array('class' => 'link_admin link_add')); ?>

<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% provincias de un total de %count% provincias).'));?>
</div>

<table align="center">
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
		<th><?php echo $this->paginator->sort('province_name', 'Provincia', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre de región'));?></th>
		<th><?php echo $this->paginator->sort('Region.region_name', 'Region Asociada', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre de región'));?></th>
		<th>Detalle</th>
		<th>Editar</th>
		<th>Eliminar</th>
	</tr>


    <?php foreach ($provinces as $province): ?>
    <tr>
        <td><?php echo $province['Province']['id']; ?></td>
		<td><?php echo $province['Province']['province_name']; ?></td>
		<td><?php echo $province['Region']['region_name'];?></td>

		<td>
            <?php echo $this->html->link("Ver Ficha","/provinces/view/".$province['Province']['id'], array('title' => 'Ver ficha de '.$province['Province']['province_name'], 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		
		<td>
            <?php echo $this->html->link("Editar Ficha","/provinces/edit/".$province['Province']['id'], array('title' => 'Editar ficha de '.$province['Province']['province_name'], 'class' => 'tip_tip_default link_admin link_edit')); ?>
        </td>
		
		<td>
             <?php echo  $this->Html->link('Eliminar',
            	array('action' => 'delete', $province['Province']['id']),
            	array('title' => 'Eliminar '.$province['Province']['province_name'],'class' => 'tip_tip_default link_admin link_delete'),
            	'Usted está a punto de eliminar la siguiente provincia :\n\n'.$province['Province']['province_name'].'.\n\n\nDesea continuar...?');?>
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