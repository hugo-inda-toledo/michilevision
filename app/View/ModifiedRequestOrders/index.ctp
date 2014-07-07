<h3><?php echo $this->html->link('Solicitudes de modificacion','/modified_request_orders/'); ?> &gt; Listar Solicitudes de modificación</h3>
<?php echo $this->Session->flash(); ?>

<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% solicitudes de un total de %count% solicitudes).'));?>
</div>

<table align="center">
    <tr>
        <th><?php echo $this->paginator->sort('ModifiedRequestOrder.id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
		<th><?php echo $this->paginator->sort('User.name', 'Solicitante', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por solicitante'));?></th>
        <th><?php echo $this->paginator->sort('PurchaseOrder.order_number', 'Numero de Orden', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por numero de orden'));?></th>
		<th><?php echo $this->paginator->sort('ModifiedRequestOrder.can_modify', 'Autorizado?', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por modificación'));?></th>
		<th>Vista completa</th>
		<th>Autorizar</th>
    </tr>

 <?php foreach ($requests as $request): ?>
    <tr>
        <td><?php echo $request['ModifiedRequestOrder']['id']; ?></td>
		<td><?php echo $request['User']['name'].' '.$request['User']['first_lastname']; ?></td>
		<td><?php echo $request['PurchaseOrder']['order_number'];?></td>
		<td><?php if($request['ModifiedRequestOrder']['can_modify'] == 1) echo "Si"; else echo "No"; ?></td>
        <td>  
			<?php echo $this->html->link('Ver Solicitud','/modified_request_orders/view/'.$request['ModifiedRequestOrder']['id'], array('title' => 'Ver información de la solicitud', 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		<td>
            <?php 
				if($request['ModifiedRequestOrder']['can_modify'] == 1)
				{
					echo $this->html->tag('span', 'Autorizado', array('class' => 'link_admin link_ok'));
				}
				else
				{
					echo $this->html->link('Autorizar', '#',array('class' => 'tip_tip_click link_admin link_edit', 'title' => $this->RequestAction('/modified_request_orders/authorizeForm/'.$request['ModifiedRequestOrder']['id'])));
				}
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