<h3><?php echo $this->html->link('Sistemas','/systems/'); ?> &gt; Listar Sistemas</h3>
<h2><?php echo $this->html->link('Agregar sistema','/systems/add/',array('class' => 'link_admin link_add')); ?></h2>
<h2><?php echo $this->html->link('Asignar sistema a usuario','/user_systems/add/',array('class' => 'link_admin link_connect')); ?></h2>
<?php echo $this->Session->flash(); ?>
<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% sistemas de un total de %count% sistemas).'));?>
</div>
<table align="center">
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
        <th><?php echo $this->paginator->sort('system_name', 'Nombre del sistema', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre del sistema'));?></th>
		<th><?php echo $this->paginator->sort('table_name', 'Nombre de la tabla', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre de tabla'));?></th>
		<th><?php echo $this->paginator->sort('css_class_url', 'Ícono CSS', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por clase css'));?></th>
		<th>Vista completa</th>
		<th>Editar</th>
		<th>Eliminar</th>
    </tr>


 <?php foreach ($sistemas as $sistema): ?>
    <tr>
        <td><?php echo $sistema['System']['id']; ?></td>
		<td><?php echo $this->Html->tag('span', $sistema['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$sistema['System']['id'])));?></td>
		<td><?php echo $sistema['System']['table_name']; ?></td>
		<td><?php echo $this->Html->tag('span', '', array('class' => 'link_admin32x32 '.$sistema['System']['css_class_url'])) ?></td>
        <td>  
			<?php echo $this->html->link('Ver Ficha','/systems/view/'.$sistema['System']['id'], array('title' => 'Ver ficha de '.$sistema['System']['system_name'], 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		<td>
            <?php echo $this->html->link('Editar','/systems/edit/'.$sistema['System']['id'], array('title' => 'Editar '.$sistema['System']['system_name'], 'class' => 'tip_tip_default link_admin link_edit')); ?>
        </td>
		<td>
            <?php
            echo $this->Html->link(
            	'Eliminar',
            	array('action' => 'delete', $sistema['System']['id']),
            	array('title' => 'Eliminar '.$sistema['System']['system_name'],
            		'class' => 'tip_tip_default link_admin link_delete'),
            	'Usted está a punto de eliminar el siguiente sistema :\n\n'.$sistema['System']['system_name'].'.\n\n\nDesea continuar...?');
           	?>
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