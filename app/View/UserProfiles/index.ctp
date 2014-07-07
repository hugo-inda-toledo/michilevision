<h3><?php echo $this->html->link('Perfiles','/profiles/'); ?> &gt; <?php echo $this->html->link('Asignaciones a usuarios','/user_profiles/'); ?> &gt; Listar asignaciones</h3>
<h2><?php echo $this->html->link('Asignar perfil a usuario','/user_profiles/add/',array('class' => 'link_admin link_add_profile')); ?></h2>
<?php echo $this->Session->flash(); ?>
<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% asignaciones de un total de %count% asignaciones).'));?>
</div>
<table>
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
        <th><?php echo $this->paginator->sort('User.name', 'Nombre de usuario', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre de usuario'));?></th>
		<th><?php echo $this->paginator->sort('Profile.profile_name', 'Perfil asociado', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por perfil'));?></th>
		<th><?php echo $this->paginator->sort('System.system_name', 'Sistema asociado', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por sistema'));?></th>
		<th>Vista completa</th>
		<th>Eliminar</th>
    </tr>


 <?php foreach ($usuarioProfiles as $usuarioProfile): ?>
    <tr>
        <td><?php echo $usuarioProfile['UserProfile']['id']; ?></td>
		<td><?php echo $this->Html->tag('span', $usuarioProfile['User']['name'].' '.$usuarioProfile['User']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => @$this->RequestAction('/external_functions/showUsuarioTable/'.$usuarioProfile['User']['id'])));?></td>
		<td><?php echo $this->Html->tag('span', $usuarioProfile['Profile']['profile_name'], array('class' => 'link_admin tip_tip link_profile pointer', 'title' => @$this->RequestAction('/external_functions/showProfileTable/'.$usuarioProfile['UserProfile']['profile_id'])));?></td>
		<td><?php echo $this->Html->tag('span', $usuarioProfile['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => @$this->RequestAction('/external_functions/showSistemaTable/'.$usuarioProfile['System']['system_id'])));?></td>
		<td><?php echo $this->html->link('Ver Asignación','/user_profiles/view/'.$usuarioProfile['UserProfile']['id'], array('title' => 'Ver ficha asignaci&oacute;n', 'class' => 'tip_tip_default link_admin link_zoom')); ?></td>
		<td>
            <?php echo  $this->Html->link('Eliminar',
            	array('action' => 'delete', $usuarioProfile['UserProfile']['id']),
            	array('title' => 'Eliminar Asignación', 'class' => 'tip_tip_default link_admin link_delete'),
            	'Usted está a punto de eliminar la siguiente asignación :\n\nUsuario : '.$usuarioProfile['User']['name'].' '.$usuarioProfile['User']['first_lastname'].'\nPerfil asignado : '.$usuarioProfile['Profile']['profile_name'].'\n\nDesea continuar...?');?>
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
