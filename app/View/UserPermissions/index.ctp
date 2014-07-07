<h3><?php echo $this->html->link('Permisos','/permissions/'); ?> &gt; <?php echo $this->html->link('Asignaciones a usuarios','/user_permissions/'); ?> &gt; Listar asignaciones</h3>
<h2><?php echo $this->html->link('Asignar permiso temporal a usuario','/user_permissions/add/',array('class' => 'link_admin link_user_permission')); ?></h2>
<?php echo $this->Session->flash(); ?>
<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% asignaciones de un total de %count% asignaciones).'));?>
</div>
<table>
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
        <th><?php echo $this->paginator->sort('User.name', 'Nombre del usuario', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre de usuario'));?></th>
		<th><?php echo $this->paginator->sort('Permission.type_permission', 'Permiso asociado', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por permiso asociado'));?></th>
		<th><?php echo $this->paginator->sort('System.system_name', 'Sistema Asociado', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por sistema'));?></th>
		<th>Reemplazo asociado</th>
		<th><?php echo $this->paginator->sort('active', 'Estado', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por estado'));?></th>
		<th>Vista completa</th>
		<th>Accion</th>
		<th>Eliminar</th>
    </tr>


 <?php foreach ($usuarioPermisos as $usuarioPermiso): ?>
    <tr>
        <td><?php echo $usuarioPermiso['UserPermission']['id']; ?></td>
		<td><?php echo $this->Html->tag('span', $usuarioPermiso['User']['name'].' '.$usuarioPermiso['User']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$usuarioPermiso['User']['id'])));?></td>
		<td><?php echo $this->Html->tag('span', $usuarioPermiso['Permission']['type_permission'], array('class' => 'link_admin tip_tip link_permission pointer', 'title' => $this->RequestAction('/external_functions/showPermisoTable/'.$usuarioPermiso['Permission']['id'])));?></td>
		<td><?php echo $this->Html->tag('span', $usuarioPermiso['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$usuarioPermiso['System']['id'])));?></td>
		<td><?php 
						if($usuarioPermiso['UserPermission']['replacement_id'] == null)
							echo $this->Html->tag('span', 'No', array('class' => 'link_admin link_circuit_rejected'));
						else
							echo $this->Html->tag('span', 'Si', array('class' => 'link_admin tip_tip link_circuit_approved pointer', 'title' => $this->RequestAction('/external_functions/showReemplazoTable/'.$usuarioPermiso['UserPermission']['replacement_id'])));
				?>
		</td>
		<td><?php 
						if($usuarioPermiso['UserPermission']['active'] == 1)
							echo $this->Html->tag('span', 'Habilitado', array('class' => 'link_admin link_circuit_approved'));
						else
							echo $this->Html->tag('span', 'Deshabilitado', array('class' => 'link_admin link_circuit_rejected'));
				?>
		</td>
		<td>  
			<?php echo $this->html->link('Ver Asignación','/user_permissions/view/'.$usuarioPermiso['UserPermission']['id'], array('title' => 'Ver detalle de esta asignaci&oacute;n', 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		<td><?php 
						if($usuarioPermiso['UserPermission']['active'] == 1)
							echo $this->Html->link('Deshabilitar', '/user_permissions/disable/'.$usuarioPermiso['UserPermission']['id'], array('title' => 'Deshabilita esta asignacion', 'class' => 'tip_tip_default link_admin link_disable'));
						else
							echo $this->Html->link('Habilitar', '/user_permissions/enable/'.$usuarioPermiso['UserPermission']['id'], array('title' => 'Habilita esta asignacion', 'class' => 'tip_tip_default link_admin link_enable'));
				?>
		</td>
		<td>
           <?php echo  $this->Html->link('Eliminar',
                   array('action' => 'delete', $usuarioPermiso['UserPermission']['id']),
                   array('title' => 'Eliminar esta asignaci&oacute;n', 'class' => 'tip_tip_default link_admin link_delete'),
                   'Usted está a punto de eliminar la siguiente asignacion :\n\nUsuario : '.$usuarioPermiso['User']['name'].' '.$usuarioPermiso['User']['first_lastname'].'\nPermiso asignado : '.$usuarioPermiso['Permission']['type_permission'].'\n\nDesea continuar...?');?>
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

