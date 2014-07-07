<?php echo $this->Session->flash();?>

<h3><?php echo $this->html->link('Libreta de Direcciones','/addresses/'); ?> &gt; Listar Direcciones</h3>

<?php echo $this->html->link('Crear nueva dirección','/addresses/add/',array('class' => 'link_admin link_add')); ?>

<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% direcciones de un total de %count% direcciones).'));?>
</div>

<table align="center">
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
		<th><?php echo $this->paginator->sort('address_title', 'Nombre Corto', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre corto de dirección.'));?></th>
		<th><?php echo $this->paginator->sort('address_name', 'Calle', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre de calle'));?></th>
		<th><?php echo $this->paginator->sort('number', 'Número', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por numero'));?></th>
		<th><?php echo $this->paginator->sort('apart_block', 'Depto/Oficina', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por departamento o block'));?></th>
		<th><?php echo $this->paginator->sort('Region.region_name', 'Región', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por región'));?></th>
		<th><?php echo $this->paginator->sort('Province.province_name', 'Provincia', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por provincia'));?></th>
		<th><?php echo $this->paginator->sort('Commune.commune_name', 'Comuna', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por comuna'));?></th>
		<th>Detalle</th>
		<th>Editar</th>
		<th>Eliminar</th>
	</tr>


    <?php foreach ($addresses as $address): ?>
    <tr>
        <td><?php echo $address['Address']['id']; ?></td>
		<td><?php echo $address['Address']['address_title']; ?></td>
		<td><?php echo $address['Address']['address_name'];?></td>
		<td><?php echo $address['Address']['number'];?></td>
		<td><?php echo $address['Address']['apart_block'];?></td>
		<td><?php echo $address['Region']['region_name'];?></td>
		<td><?php echo $address['Province']['province_name'];?></td>
		<td><?php echo $address['Commune']['commune_name'];?></td>

		<td>
            <?php echo $this->html->link("Ver Ficha","/addresses/view/".$address['Address']['id'], array('title' => 'Ver ficha de '.$address['Address']['address_title'], 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		
		<td>
            <?php echo $this->html->link("Editar Ficha","/addresses/edit/".$address['Address']['id'], array('title' => 'Editar ficha de '.$address['Address']['address_title'], 'class' => 'tip_tip_default link_admin link_edit')); ?>
        </td>
		
		<td>
             <?php echo  $this->Html->link('Eliminar',
            	array('action' => 'delete', $address['Address']['id']),
            	array('title' => 'Eliminar '.$address['Address']['address_title'],'class' => 'tip_tip_default link_admin link_delete'),
            	'Usted está a punto de eliminar la siguiente direccion :\n\n'.$address['Address']['address_title'].'.\n'.$address['Address']['address_name'].' #'.$address['Address']['number'].' '.$address['Address']['apart_block'].'.'.$address['Commune']['commune_name'].', '.$address['Province']['province_name'].'\n\n\nDesea continuar...?');?>
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