<?php
echo $this->Html->tag('h3', $this->html->link('Ordenes de compra','/purchase_orders/mainMenu/').' &gt; Nueva Orden de Compra');
echo $this->Html->tag('h2', 'Ordenes de compra', array('class' => 'link_admin link_purchase_order'));
echo $this->Session->flash();
?>

<div id="MenuSystems" class="MenuSystems-3Cols" style="margin-left: 300px;">
	<div id="oc-requests" class="_menu">
	<div class="_link">
		<a href="javascript:menuSystemShow('oc-requests');" class="tip_tip_default" title="Crear nueva orden de compra">
			<?php echo $this->Html->tag('h4', 'Crear nueva orden de compra');?>
			<?php echo $this->Html->tag('h5', 'Crear nueva orden de compra');?>
		</a>
	</div>

	<div class="_filters">
		<h5>Nueva orden de compra</h5>	
		<ul>
			<li><?php echo $this->Html->link('He cotizado yo', '/purchase_orders/add/budgeted', array('class' => 'link_admin link_folder_add'));?></li>
			<li><?php echo $this->Html->link('Quiero que me cotizen', '/purchase_orders/add/unbudgeted', array('class' => 'link_admin link_folder_delete'));?></li>
		</ul>
	</div>
	</div>
</div>