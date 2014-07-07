<?php echo $this->Session->flash(); ?>
<h3><?php echo $this->html->link('Permisos','/permissions/'); ?> &gt; Listar permisos</h3>
<h2><?php echo $this->html->link('Agregar permiso','/permissions/add/',array('class' => 'link_admin link_add')); ?></h2>
<h2><?php echo $this->html->link('Asignar permiso temporal a usuario','/user_permissions/add/',array('class' => 'link_admin link_user_permission')); ?></h2>

<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% permisos de un total de %count% permisos).'));?>
</div>
<table>
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
        <th><?php echo $this->paginator->sort('type_permission', 'Tipo de permiso', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por tipo de permiso'));?></th>
		<th><?php echo $this->paginator->sort('System.system_name', 'Sistema asociado', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por sistema'));?></th>
		<th><?php echo $this->paginator->sort('level', 'Nivel', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nivel'));?></th>
		<th>Vista completa</th>
		<th>Editar</th>
		<th>Eliminar</th>
    </tr>


 <?php foreach ($permisos as $permiso): ?>
    <tr>
		<td><?php echo $permiso['Permission']['id']; ?></td>
		<td><?php echo $permiso['Permission']['type_permission']; ?></td>
		<td><?php echo $this->Html->tag('span', $permiso['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$permiso['System']['id'])));?></td>
		<td><?php echo $permiso['Permission']['level']; ?></td>
        <td>  
			<?php echo $this->html->link('Ver Ficha','/permissions/view/'.$permiso['Permission']['id'], array('title' => 'Ver ficha de '.$permiso['Permission']['type_permission'], 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		<td>
            <?php echo $this->html->link('Editar','/permissions/edit/'.$permiso['Permission']['id'], array('title' => 'Editar permiso '.$permiso['Permission']['type_permission'], 'class' => 'tip_tip_default link_admin link_edit')); ?>
        </td>
		<td>
            <?php echo  $this->Html->link('Eliminar',
            	array('action' => 'delete', $permiso['Permission']['id']),
            	array('title' => 'Eliminar permiso '.$permiso['Permission']['type_permission'],'class' => 'tip_tip_default link_admin link_delete'),
            	'Usted está a punto de eliminar el siguiente permiso :\n\nTipo de permiso : '.$permiso['Permission']['type_permission'].'.\nSistema asociado : '.$permiso['System']['system_name'].'\n\nDesea continuar...?');?>
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

