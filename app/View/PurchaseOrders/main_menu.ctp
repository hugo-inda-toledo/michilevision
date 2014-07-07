<?php
echo $this->Html->tag('h3', $this->html->link('Ordenes de compra','/purchase_orders/mainMenu/').' &gt; Menú principal');
echo $this->Html->tag('h2', 'Ordenes de compra', array('class' => 'link_admin link_purchase_order'));
echo $this->Session->flash();

$class;

$raiz = "/michilevision";

if($this->RequestAction('/external_functions/verifiedAccess/'.$userLogged."/2/modifiedRequestOrder") == true || $this->RequestAction('/external_functions/verifiedAdministrationUserLogged/'.$userLogged) == true)
{
	$class= '4Cols';
}
else
{
	$class= '3Cols';
}
?>

<?php echo "<div id='MenuSystems' class='MenuSystems-".$class."' >"; ?>
	
	<div id="oc-requests" class="_menu">
		<div class="_link">
				<a href=<?php echo $raiz."/purchase_orders/add/"; ?> class="tip_tip_default" title="Crear nueva solicitud">
					<?php echo $this->Html->tag('h4', 'Crear nueva solicitud');?>
					<?php echo $this->Html->tag('h5', 'Crear nueva solicitud');?>
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

	
	<div id="oc-listings" class="_menu">
		<div class="_link">
			<a href="javascript:menuSystemShow('oc-listings');" class="tip_tip_default" title="Ver listado de ordenes de compra">
				<?php echo $this->Html->tag('h4', 'Ver listado de órdenes de compra');?>
				<?php echo $this->Html->tag('h5', 'Ver listado de órdenes de compra');?>
			</a>	
		</div>
		
		<div class="_filters">
			<h5>Solicitudes</h5>	
			<ul>
				<li><?php echo $this->Html->link('Todas las órdenes', '/purchase_orders/', array('title' =>'Lista todas las ordenes de compra.', 'class' => 'tip_tip_default')); ?></li>
				<li><?php echo $this->Html->link('Con Cotizaciones','/purchase_orders/index/budgeted', array('title' =>'Lista todas las ordenes de compra que tienen cotización asignada.', 'class' => 'tip_tip_default')); ?></li>
				<li><?php echo $this->Html->link('Sin Cotizaciones','/purchase_orders/index/unbudgeted', array('title' =>'Lista todas las ordenes de compra sin cotización asignada.', 'class' => 'tip_tip_default')); ?></li>
				<li><?php echo $this->Html->link('Aprobadas','/purchase_orders/index/approved', array('title' =>'Lista todas las ordenes de compra aprobadas.', 'class' => 'tip_tip_default')); ?></li>
				<li><?php echo $this->Html->link('Pendientes','/purchase_orders/index/waiting', array('title' =>'Lista todas las ordenes de compra pendientes.', 'class' => 'tip_tip_default')); ?></li>
				<li><?php echo $this->Html->link('Rechazadas','/purchase_orders/index/decline', array('title' =>'Lista todas las ordenes de compra rechazadas.', 'class' => 'tip_tip_default')); ?></li>
			</ul>
		</div>
	</div>
	
	
	<div id="oc-stats" class="_menu">
		<div class="_link">
			<a href="javascript:menuSystemShow('oc-stats');" class="tip_tip_default" title="Estadísticas e informes">
				<?php echo $this->Html->tag('h4', 'Estadísticas e informes');?>
				<?php echo $this->Html->tag('h5', 'Estadísticas e informes');?>
			</a>	
		</div>
	
		<div class="_filters">

			<h5>Estadísticas e informes</h5>
			<ul>
				<li><?php echo $this->Html->link('Enlace menu 01','#');?></li>
				<li><?php echo $this->Html->link('Enlace menu 02','#');?></li>
				<li><?php echo $this->Html->link('Enlace menu 03','#');?></li>
				<li><?php echo $this->Html->link('Enlace menu 04','#');?></li>
			</ul>
		</div>
	</div>
	
	<?php
	if($this->RequestAction('/external_functions/verifiedAccess/'.$userLogged."/2/modifiedRequestOrder") == true || $this->RequestAction('/external_functions/verifiedAdministrationUserLogged/'.$userLogged) == true)
	{
	?>
	<div id="oc-keys" class="_menu">
		<div class="_link">
			<a href=<?php echo $raiz."/modified_request_orders/"; ?> title="Solicitudes de modificación">
				<?php echo $this->Html->tag('h4', 'Solicitudes de modificación');?>
				<?php echo $this->Html->tag('h5', 'Solicitudes de modificación');?>
			</a>
		</div>
	</div>
	<?php
	}
	?>
</div>