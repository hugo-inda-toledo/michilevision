<h3><?php echo $this->html->link('Circuitos','/circuits/'); ?> &gt; Listar Circuitos</h3>
<?php echo $this->Session->flash();?>

<h2><?php echo $this->html->link('Crear circuito','/circuits/add/',array('class' => 'link_admin link_add')); ?></h2>

<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% circuitos de un total de %count% circuitos).'));?>
</div>

<table>
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
        <th><?php echo $this->paginator->sort('System.system_name', 'Sistema', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por sistema'));?></th>
        <th><?php echo $this->paginator->sort('Authorization.name', 'Tipo de autorización', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por autorizacion'));?></th>
        <th><?php echo $this->paginator->sort('level', 'Nivel', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nivel'));?></th>
		<th><?php echo $this->paginator->sort('User.name', 'Firmante', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por firmante'));?></th>
		<th><?php echo $this->paginator->sort('position', 'Cargo', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por cargo'));?></th>
		<th>Detalle</th>
		<th>Editar</th>
    </tr>


 <?php foreach ($circuits as $circuit): ?>
    <tr>
        <td><?php echo $circuit['Circuit']['id']; ?></td>
		<td><?php echo $this->Html->tag('span', $circuit['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$circuit['System']['id'])));?></td>
		<td><?php echo $circuit['Authorization']['name']; ?></td>
		<td><?php echo $circuit['Circuit']['level']; ?></td>
		<td><?php echo $this->Html->tag('span', $circuit['User']['name'].' '.$circuit['User']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$circuit['User']['id'])));?></td>
		<td><?php echo ucwords(mb_strtolower($circuit['Circuit']['position'])); ?></td>
        <td><?php echo $this->html->link('Ver Ficha','/circuits/view/'.$circuit['Circuit']['id'],array('title' => 'Ver Circuito', 'class' => 'tip_tip_default link_admin link_zoom'));?></td>
		<td><?php echo $this->html->link('Editar','/circuits/edit/'.$circuit['Circuit']['id'],array('title' => 'Editar Circuito', 'class' => 'tip_tip_default link_admin link_edit')); ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<br>
<div class="paging">
	<?php echo $this->paginator->prev('Anterior', null, null, array('class' => 'disabled_prev'));?>
	<?php echo $this->paginator->numbers(array('separator' => '')); ?>
	<?php echo $this->paginator->next('Siguiente', null, null, array('class' => 'disabled_next'));?>
</div>
