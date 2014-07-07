<h3><?php echo $this->html->link('Perfiles','/profiles/'); ?> &gt; Listar perfiles</h3>
<h2><?php echo $this->html->link('Agregar perfil','/profiles/add/',array('class' => 'link_admin link_add')); ?></h2>
<h2><?php echo $this->html->link('Asignar perfil a usuario','/user_profiles/add/',array('class' => 'link_admin link_user_permission')); ?></h2>
<h2><?php echo $this->html->link('Agregar permiso a un perfil','/profile_permissions/add/',array('class' => 'link_admin link_add_profile')); ?></h2>

<?php echo $this->Session->flash(); ?>
<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% perfiles de un total de %count% perfiles).'));?>
</div>
<table align="center">
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
        <th><?php echo $this->paginator->sort('profile_name', 'Nombre Perfil', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre de perfil'));?></th>
        <th><?php echo $this->paginator->sort('System.system_name', 'Sistema', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por sistema'));?></th>
		<th>Vista completa</th>
		<th>Editar</th>
		<th>Eliminar</th>
    </tr>


 <?php foreach ($profiles as $profile): ?>
    <tr>
		<td><?php echo $profile['Profile']['id']; ?></td>
		<td><?php echo $profile['Profile']['profile_name']; ?></td>
		<td><?php echo $this->Html->tag('span', $profile['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$profile['System']['id'])));?></td>
		<td>  
			<?php echo $this->html->link('Ver Ficha','/profiles/view/'.$profile['Profile']['id'], array('title' => 'Ver ficha del perfil '.$profile['Profile']['profile_name'], 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		<td>
            <?php echo $this->html->link('Editar','/profiles/edit/'.$profile['Profile']['id'], array('title' => 'Editar perfil '.$profile['Profile']['profile_name'], 'class' => 'tip_tip_default link_admin link_edit')); ?>
        </td>
		<td>
            <?php echo  $this->Html->link('Eliminar',
            	array('action' => 'delete', $profile['Profile']['id']),
            	array('title' => 'Eliminar el perfil '.$profile['Profile']['profile_name'],'class' => 'tip_tip_default link_admin link_delete'),
            	'Usted está a punto de eliminar el siguiente perfil :\n\n'.$profile['Profile']['profile_name'].'\n\nDesea continuar...?');?>
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
