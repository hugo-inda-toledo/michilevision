<?php echo $this->Session->flash();?>

<h3><?php echo $this->html->link('Comunas','/communes/'); ?> &gt; Listar comunas</h3>

<?php echo $this->html->link('Crear nueva comuna','/communes/add/',array('class' => 'link_admin link_add')); ?>

<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% comunas de un total de %count% comunas).'));?>
</div>

<table align="center">
    <tr>
        <th><?php echo $this->paginator->sort('Commune.id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
		<th><?php echo $this->paginator->sort('Commune.commune_name', 'Comuna', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre de comuna'));?></th>
		<th><?php echo $this->paginator->sort('Province.province_name', 'Provincia Asociada', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre de provincia'));?></th>
		<th><?php echo $this->paginator->sort('Region.region_name', 'Region Asociada', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre de región'));?></th>
		<th>Detalle</th>
		<th>Eliminar</th>
	</tr>


    <?php foreach ($communes as $commune): ?>
    <tr>
        <td><?php echo $commune['Commune']['id']; ?></td>
		<td><?php echo $commune['Commune']['commune_name']; ?></td>
		<td><?php echo $commune['Province']['province_name'];?></td>
		<td><?php echo $commune['Region']['region_name'];?></td>

		<td>
            <?php echo $this->html->link("Ver Ficha","/communes/view/".$commune['Commune']['id'], array('title' => 'Ver ficha de '.$commune['Commune']['commune_name'], 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		
		<td>
             <?php echo  $this->Html->link('Eliminar',
            	array('action' => 'delete', $commune['Commune']['id']),
            	array('title' => 'Eliminar '.$commune['Commune']['commune_name'],'class' => 'tip_tip_default link_admin link_delete'),
            	'Usted está a punto de eliminar la siguiente comuna :\n\n'.$commune['Commune']['commune_name'].'.\n\n\nDesea continuar...?');?>
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