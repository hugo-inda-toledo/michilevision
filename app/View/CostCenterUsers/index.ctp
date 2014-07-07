<h3><?php echo $this->html->link('Centro de costos','/cost_centers/'); ?> &gt; <?php echo $this->html->link('Asignaciones a usuarios','/cost_center_users/'); ?> &gt; Listar asignaciones</h3>
<h2><?php echo $this->html->link('Asignar centro de costo a usuario','/cost_center_users/add/',array('class' => 'link_admin link_connect')); ?></h2>
<?php echo $this->Session->flash(); ?>
<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% asignaciones de un total de %count% asignaciones).'));?>
</div>
<table>
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
		<th><?php echo $this->paginator->sort('User.name', 'Nombre del usuario', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre de usuario'));?></th>
		<th><?php echo $this->paginator->sort('CostCenter.cost_center_name', 'Centro de costo asociado', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por centro de costo asociado'));?></th>
		<th><?php echo $this->paginator->sort('System.system_name', 'Sistema asociado', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por sistema'));?></th>
		<th>Ver</th>
		<th>Eliminar</th>
    </tr>


    <?php foreach($cost_center_users as $cost_center_user){ ?>
    <tr>
		<td><?php echo $cost_center_user['CostCenterUser']['id']; ?></td>
		<td><?php echo $this->Html->tag('span', $cost_center_user['User']['name'].' '.$cost_center_user['User']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/jquery_tables/showUserTable/'.$cost_center_user['User']['id'])));?></td>
		<td><?php echo $cost_center_user['CostCenter']['cost_center_name'].' Cod.('.$cost_center_user['CostCenter']['cost_center_code'].")"; ?></td>
		<td><?php echo $this->Html->tag('span', $cost_center_user['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer','title' => $this->RequestAction('/external_functions/showSistemaTable/'.$cost_center_user['System']['id'])));?></td>
		<td>
            <?php
            	echo $this->html->link('Ver Ficha', '/cost_center_users/view/'.$cost_center_user['CostCenterUser']['id'],
           			array('title' => 'Ver ficha asignación', 'class' => 'tip_tip_default link_admin link_zoom'));
           	?>
        </td>
		<td>
			<?php
				echo $this->Html->link('Eliminar',
						array('action' => 'delete', $cost_center_user['CostCenterUser']['id']), 
						array('title' => 'Eliminar esta asignación', 'class' => 'tip_tip_default link_admin link_delete'),
					'Usted está a punto de eliminar la siguiente asignación :\n\nCentro de costo asociado : '.$cost_center_user['CostCenter']['cost_center_name'].
					'.\nUsuario asociado : '.$cost_center_user['User']['name'].' '.$cost_center_user['User']['first_lastname'].'\n\nDesea continuar...?');
			?>
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


