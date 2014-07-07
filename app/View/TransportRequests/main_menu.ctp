<h3><?php echo $this->html->link('Movilización', '/taxi_requests/mainMenu/'); ?> &gt; Menú principal</h3>
<?php echo $this->Html->tag('h2', 'Movilización', array('class' => 'link_admin link_taxi'));?>


<div id="MenuUserSystemsHolder">

	
	<div id="_requests" class="MenuUserSystems">
		<div class="_menu">
			<a href="/michilevision/transport_requests/add/" class="tip_tip_default" title="Generar nuevo ticket">
				<?php echo $this->Html->tag('h4', 'Generar nuevo Ticket');?>
				<?php echo $this->Html->tag('h5', 'Generar nuevo Ticket');?>
			</a>
		</div>
	</div>

	
	<div id="_transports" class="MenuUserSystems">
		<div class="_menu">
			<a href="/michilevision/transport_requests/" class="tip_tip_default" title="Listar Tickets Generados">
				<?php echo $this->Html->tag('h4', 'Listado de Tickets');?>
				<?php echo $this->Html->tag('h5', 'Listado de Tickets');?>
			</a>
		</div>
	</div>
	
	
	<div id="_stats" class="MenuUserSystems">
		<div class="_menu">
			<a href="javascript:menuUserSystemShow('_stats');" class="tip_tip_default" title="Estadísticas e informes">
				<?php echo $this->Html->tag('h4', 'Estadísticas e informes');?>
				<?php echo $this->Html->tag('h5', 'Estadísticas e informes');?>
			</a>	
		</div>
	
		<div class="_filters">

			<h5><?php echo $this->Html->link('Estadísticas e informes', '/render_funds/statsMenu'); ?></h5>

			<ul>
				<?php
					if($this->RequestAction('/external_functions/getIdDataSession/') == $this->RequestAction('/attribute_tables/validateTreasurer') || $userAdmin == 1)
					{
					?>
							<li><?php echo $this->html->link('Fondos expirados','/render_funds/expiredFunds', array('class' => 'link_admin link_report'));?></li>
					<?php
					}
					?>
				<li><?php echo $this->html->link('Cuantificador de gastos (Usuarios)','/render_funds/quantizerUsersExpenses', array('class' => 'link_admin link_report'));?></li>
				<li><?php echo $this->html->link('Cuantificador de gastos (C. costos y/o gerencias)','/render_funds/quantizerExpenses/', array('class' => 'link_admin link_report'));?></li>
				<li><?php echo $this->html->link('Cuantificador de gastos (General)','/render_funds/quantizerGeneralExpenses/', array('class' => 'link_admin link_report'));?></li>
			</ul>
		</div>
	</div>

</div>