<h3><?php echo $this->html->link('Perfiles','/profiles/'); ?> &gt; <?php echo $this->html->link('Asignaciones de permisos','/profile_permissions/'); ?> &gt; Listar asignaciones</h3>
<?php echo $this->Session->flash();?>

<h2><?php echo $this->html->link('Agregar permiso a un perfil','/profile_permissions/add/',array('class' => 'link_admin link_add_profile')); ?></h2>
<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% asignaciones de un total de %count% asignaciones).'));?>
</div>

<table>
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
        <th><?php echo $this->paginator->sort('Profile.profile_name', 'Perfil', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por perfil'));?></th>
		<th>Sistema asociado</th>
		<th><?php echo $this->paginator->sort('Permission.type_permission', 'Permiso asociado', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por permiso'));?></th>
		<th>Detalle</th>
		<th>Eliminar</th>
    </tr>

	<?php for($x=0; $x < count($profilePermisos);$x++){?>
    <tr>
        <td><?php echo $profilePermisos[$x]['ProfilePermission']['id']; ?></td>
      	<td><?php echo $this->Html->tag('span', $profilePermisos[$x]['Profile']['profile_name'], array('class' => 'link_admin tip_tip link_add_profile pointer', 'title' => $this->RequestAction('/external_functions/showProfileTable/'.$profilePermisos[$x]['Profile']['id'])));?></td>
		<td><?php echo $this->Html->tag('span', $profilePermisos[$x]['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$profilePermisos[$x]['System']['id'])));?></td>
		<td><?php echo $this->Html->tag('span', $profilePermisos[$x]['Permission']['type_permission'], array('class' => 'link_admin tip_tip link_permission pointer', 'title' => $this->RequestAction('/external_functions/showPermisoTable/'.$profilePermisos[$x]['Permission']['id'])));?></td>
        <td><?php echo $this->html->link('Ver Asignación','/profile_permissions/view/'.$profilePermisos[$x]['ProfilePermission']['id'], array('title' => 'Ver ficha asignacion', 'class' => 'link_admin link_zoom')); ?></td>
		<td>
            <?php echo  $this->Html->link('Eliminar',
            	array('action' => 'delete', $profilePermisos[$x]['ProfilePermission']['id']),
            	array('title' => 'Eliminar Asignación', 'class' => 'link_admin link_delete'),
            	'Usted está a punto de eliminar la siguiente asignación :\n\nPerfil: '.$profilePermisos[$x]['Profile']['profile_name'].'\nPermiso Asignado: '.$profilePermisos[$x]['Permission']['type_permission'].'\n\nDesea continuar...?');?>
        </td>
    </tr>
    <?php
	}
	?>
</table>
<br>
<div class="paging">
	<?php echo $this->paginator->prev('Anterior', null, null, array('class' => 'disabled_prev'));?>
	<?php echo $this->paginator->numbers(array('separator' => '')); ?>
	<?php echo $this->paginator->next('Siguiente', null, null, array('class' => 'disabled_next'));?>
</div>
