<h3><?php echo $this->html->link('Sistemas','/systems/'); ?> &gt; <?php echo $this->html->link('Asignación de sistemas','/user_systems/'); ?> &gt; Listar asignaciones</h3>
<h2><?php echo $this->html->link('Asignar sistema a usuario','/user_systems/add/',array('class' => 'link_admin link_user link_connect')); ?></h2>
<?php echo $this->Session->flash(); ?>
<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% asignaciones de un total de %count% asignaciones).'));?>
</div>
<table align="center">
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
        <th><?php echo $this->paginator->sort('User.name', 'Nombre del usuario', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por usuario'));?></th>
		<th><?php echo $this->paginator->sort('System.system_name', 'Sistema asociado', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por sistema'));?></th>
		<th>Vista completa</th>
		<th>Eliminar</th>
    </tr>


 <?php foreach ($usuarioSistemas as $usuarioSistema): ?>
    <tr>
        <td><?php echo $usuarioSistema['UserSystem']['id']; ?></td>
        <td><?php echo $this->Html->tag('span', $usuarioSistema['User']['name'].' '.$usuarioSistema['User']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$usuarioSistema['User']['id'])));?></td>
		<td><?php echo $this->Html->tag('span', $usuarioSistema['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$usuarioSistema['System']['id'])));?></td>
		<td>  
			<?php echo $this->html->link('Ver Asignación','/user_systems/view/'.$usuarioSistema['UserSystem']['id'], array('title' => 'Ver ficha asignaci&oacute;n', 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		<td>
            <?php echo  $this->Html->link('Eliminar',
            	array('action' => 'delete', $usuarioSistema['UserSystem']['id']),
            	array('title' => 'Eliminar asignaci&oacute;n', 'class' => 'tip_tip_default link_admin link_delete'),
            	'Usted está a punto de eliminar la siguiente asignación :\n\nSistema : '.$usuarioSistema['System']['system_name'].'.\nAsignado a : '.$usuarioSistema['User']['name'].' '.$usuarioSistema['User']['first_lastname'].'\n\nDesea continuar...?');?>
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

