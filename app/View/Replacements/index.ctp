<h3><?php echo $this->html->link('Reemplazos','/replacements/'); ?> &gt; Listar reemplazos</h3>
<?php echo $this->Session->flash(); ?>

<h2><?php echo $this->html->link('Ingresar Reemplazo','/replacements/add/', array('class' => 'link_admin link_replace')); ?></h2>

<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% reemplazos de un total de %count% reemplazos).'));?>
</div>

<table>
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
        <th><?php echo $this->paginator->sort('TypeReplacement.type_replacement', 'Tipo de reemplazo', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por tipo de reemplazo'));?></th>
		<th><?php echo $this->paginator->sort('replaced_user.name', 'Usuario reemplazado', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por usuario reemplazado.'));?></th>
		<th><?php echo $this->paginator->sort('replacing_user.name', 'Usuario reemplazante', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por usuario reemplazante.'));?></th>
		<th><?php echo $this->paginator->sort('active', 'Estado', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por estado'));?></th>
		<th>Detalle</th>
		<th>Accion</th>
		<th>Eliminar</th>
    </tr>


    <?php foreach ($reemplazos as $reemplazo): ?>
    <tr>
        <td><?php echo $reemplazo['Replacement']['id']; ?></td>
		<td><?php echo $reemplazo['TypeReplacement']['type_replacement']; ?></td>
		<td><?php echo $this->Html->tag('span', $reemplazo['replaced_user']['name'].' '.$reemplazo['replaced_user']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$reemplazo['replaced_user']['id'])));?></td>
		<td><?php echo $this->Html->tag('span', $reemplazo['replacing_user']['name'].' '.$reemplazo['replacing_user']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$reemplazo['replacing_user']['id'])));?></td>
		
		<td>
			<?php 
						if($reemplazo['Replacement']['active'] == 1)
							echo $this->Html->tag('span', 'Habilitado', array('class' => 'link_admin link_circuit_approved'));
						else
							echo $this->Html->tag('span', 'Deshabilitado', array('class' => 'link_admin link_circuit_rejected'));
				?>
		</td>
		
		<td><?php echo $this->html->link('Ver Reemplazo','/replacements/view/'.$reemplazo['Replacement']['id'],array('title' => 'Ver reemplazo', 'class' => 'tip_tip_default link_admin link_zoom')); ?></td>
		
		<td>
		<?php 
						if($reemplazo['Replacement']['active'] == 1)
							echo $this->Html->link('Deshabilitar', '/replacements/disable/'.$reemplazo['Replacement']['id'], array('title' => 'Deshabilita esta asignacion', 'class' => 'tip_tip_default link_admin link_disable'));
						else
							echo $this->Html->link('Habilitar', '/replacements/enable/'.$reemplazo['Replacement']['id'], array('title' => 'Habilita esta asignacion', 'class' => 'tip_tip_default link_admin link_enable'));
				?>
		</td>
		
		<td>
            <?php echo  $this->Html->link('Eliminar',
            	array('action' => 'delete', $reemplazo['Replacement']['id']),
            	array('title' => 'Eliminar este reemplazo','class' => 'tip_tip_default link_admin link_delete'),
            	'Usted está a punto de eliminar el reemplazo seleccionado\n\nDesea continuar...?');?>
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
