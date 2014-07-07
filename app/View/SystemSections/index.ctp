<h3><?php echo $this->html->link('Secciones de sistemas','/system_sections/'); ?> &gt; Listar Secciones de Sistemas</h3>
<h2><?php echo $this->html->link('Agregar seccion a un sistema','/system_sections/add/',array('class' => 'link_admin link_add')); ?></h2>

<?php echo $this->Session->flash(); ?>
<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% secciones de sistemas de un total de %count% secciones de sistemas).'));?>
</div>
<table align="center">
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
        <th><?php echo $this->paginator->sort('System.system_name', 'Nombre del sistema', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre del sistema'));?></th>
		<th><?php echo $this->paginator->sort('section_name', 'Nombre de la seccion', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre de seccion'));?></th>
		<th><?php echo $this->paginator->sort('section_function', 'Nombre de la funcion PHP', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por funcion'));?></th>
		<th>Vista completa</th>
		<th>Editar</th>
		<th>Eliminar</th>
    </tr>


 <?php foreach ($sections as $section): ?>
    <tr>
        <td><?php echo $section['SystemSection']['id']; ?></td>
		<td><?php echo $this->Html->tag('span', $section['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$section['System']['id'])));?></td>
		<td><?php echo $section['SystemSection']['section_name']; ?></td>
		<td><?php echo $section['SystemSection']['section_function']; ?></td>
        <td>  
			<?php echo $this->html->link('Ver detalles','/system_sections/view/'.$section['SystemSection']['id'], array('title' => 'Ver detalle de la seccion '.$section['SystemSection']['section_name'], 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		<td>
            <?php echo $this->html->link('Editar','/system_sections/edit/'.$section['SystemSection']['id'], array('title' => 'Editar '.$section['SystemSection']['section_name'], 'class' => 'tip_tip_default link_admin link_edit')); ?>
        </td>
		<td>
            <?php
            echo $this->Html->link(
            	'Eliminar',
            	array('action' => 'delete', $section['SystemSection']['id']),
            	array('title' => 'Eliminar '.$section['SystemSection']['section_name'],
            		'class' => 'tip_tip_default link_admin link_delete'),
            	'Usted está a punto de eliminar la siguiente seccion de un sistema :\n\nSistema'.$section['System']['system_name'].'.\n\nSeccion del sistema'.$section['SystemSection']['section_name'].'\n\nDesea continuar...?');
           	?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<br>
<div class="paging">
	<?php echo $this->paginator->prev('Anterior', null, null, array('class' => 'disabled_prev'));?>
	<?php echo $this->paginator->numbers(array('separator' => '|')); ?>
	<?php echo $this->paginator->next('Siguiente', null, null, array('class' => 'disabled_next'));?>
</div>