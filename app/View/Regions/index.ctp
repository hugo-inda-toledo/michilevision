<?php echo $this->Session->flash();?>

<h3><?php echo $this->html->link('Regiones','/regions/'); ?> &gt; Listar regiones</h3>

<?php echo $this->html->link('Crear nueva región','/regions/add/',array('class' => 'link_admin link_add')); ?>

<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% regiones de un total de %count% regiones).'));?>
</div>

<table align="center">
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
		<th><?php echo $this->paginator->sort('region_name', 'Region', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre de región'));?></th>
		<th>Detalle</th>
		<th>Editar</th>
		<th>Eliminar</th>
	</tr>


    <?php foreach ($regions as $region): ?>
    <tr>
        <td><?php echo $region['Region']['id']; ?></td>
		<td><?php echo $region['Region']['region_name'];?></td>

		<td>
            <?php echo $this->html->link("Ver Ficha","/regions/view/".$region['Region']['id'], array('title' => 'Ver ficha de '.$region['Region']['region_name'], 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		
		<td>
            <?php echo $this->html->link("Editar Ficha","/regions/edit/".$region['Region']['id'], array('title' => 'Ver ficha de '.$region['Region']['region_name'], 'class' => 'tip_tip_default link_admin link_edit')); ?>
        </td>
		
		<td>
             <?php echo  $this->Html->link('Eliminar',
            	array('action' => 'delete', $region['Region']['id']),
            	array('title' => 'Eliminar '.$region['Region']['region_name'],'class' => 'tip_tip_default link_admin link_delete'),
            	'Usted está a punto de eliminar la siguiente región :\n\n'.$region['Region']['region_name'].'.\n\n\nDesea continuar...?');?>
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