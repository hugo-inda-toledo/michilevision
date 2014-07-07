<?php
echo $this->Html->tag('h3', $this->html->link('Fondos por rendir','/render_funds/mainMenu/').' &gt; Menú principal');
echo $this->Html->tag('h2', 'Fondos por rendir', array('class' => 'link_admin link_render_fund'));
echo $this->Session->flash();

$raiz = "/michilevision_cakephp";
?>


<div id="MenuSystems" class="MenuSystems-3Cols">
	<div id="fr-requests" class="_menu">
		<div class="_link">
			<a href=<?php echo $raiz."/render_funds/add/"; ?> class="tip_tip_default" title="Crear nueva solicitud">
				<?php echo $this->Html->tag('h4', 'Crear nueva solicitud');?>
				<?php echo $this->Html->tag('h5', 'Crear nueva solicitud');?>
			</a>
		</div>
	</div>

	
	<div id="fr-listings" class="_menu">
		<div class="_link">
			<a href="javascript:menuSystemShow('fr-listings');" class="tip_tip_default" title="Solicitudes">
				<?php echo $this->Html->tag('h4', 'Ver listado de solicitudes');?>
				<?php echo $this->Html->tag('h5', 'Ver listado de solicitudes');?>
			</a>	
		</div>
		
		<div class="_filters">
			<h5>Solicitudes</h5>	
			<ul>
				<li><?php echo $this->html->link('Ver todos los fondos','/render_funds/index');?></li>
				<li><?php echo $this->html->link('Fondos por aprobar','/render_funds/index/toApprove');?></li>
				<li><?php echo $this->html->link('Fondos aprobados','/render_funds/index/approve');?></li>
				<li><?php echo $this->html->link('Fondos rechazados','/render_funds/index/decline');?></li>
			</ul>
		</div>
	</div>
	
	
	<div id="fr-stats" class="_menu">
		<div class="_link">
			<a href="javascript:menuSystemShow('fr-stats');" class="tip_tip_default" title="Estadísticas e informes">
				<?php echo $this->Html->tag('h4', 'Estadísticas e informes');?>
				<?php echo $this->Html->tag('h5', 'Estadísticas e informes');?>
			</a>	
		</div>
	
		<div class="_filters">
			<h5>Estadísticas e informes</h5>
			<ul>
				<?php
				if($this->RequestAction('/external_functions/getIdDataSession/') == $this->RequestAction('/attribute_tables/validateTreasurer') || $userAdmin == 1)
				{
					echo '<li>'.$this->html->link('Fondos expirados','/render_funds/expiredFunds').'</li>';
				}
				?>
				<li><?php echo $this->html->link('Cuantificador de gastos (Usuarios)','/render_funds/quantizerUsersExpenses');?></li>
				<li><?php echo $this->html->link('Cuantificador de gastos (C. costos y/o gerencias)','/render_funds/quantizerExpenses/');?></li>
				<li><?php echo $this->html->link('Cuantificador de gastos (General)','/render_funds/quantizerGeneralExpenses/');?></li>
			</ul>
		</div>
	</div>
</div>