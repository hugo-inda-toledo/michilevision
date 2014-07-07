<?php
echo $this->Html->tag('h3', $this->html->link('Fondos por rendir','/render_funds/mainMenu/').' &gt; Informes y estadisticas');
echo $this->Html->tag('h2', 'Informes Disponibles', array('class' => 'link_admin link_render_fund'));
echo $this->Session->flash();

$root = "/michilevision";

if($this->RequestAction('/external_functions/getIdDataSession/') == $this->RequestAction('/attribute_tables/validateTreasurer') || $userAdmin == 1)
{
	$class = array('css_cols' => '4Cols', 'css_name' => '');
}
else 
{
	$class = array('css_cols' => '3Cols', 'css_name' => '');
}
?>
<div id="MenuSystems" class="MenuSystems-<?php echo $class['css_cols'];?>">
	
	<div id="fr-qtzr_management" class="_menu">
		<div class="_link">
			<a href="<?php echo $root ;?>/render_funds/quantizerExpenses" class="tip_tip_default" title="Informe de gastos por periodos y filtrados por gerencia y/o centro de costo.">
				<?php echo $this->Html->tag('h4', 'Cuantificador de gastos (C.Costos y/o Gerencias)');?>
				<?php echo $this->Html->tag('h5', 'Cuantificador de gastos (C.Costos y/o Gerencias)');?>
			</a>
		</div>
	</div>
	
	
	<div id="fr-qtzr" class="_menu">
		<div class="_link">
			<a href="<?php echo $root ;?>/render_funds/quantizerGeneralExpenses" class="tip_tip_default" title="Informe de gastos generales por periodos.">
				<?php echo $this->Html->tag('h4', 'Cuantificador de gastos (General)');?>
				<?php echo $this->Html->tag('h5', 'Cuantificador de gastos (General)');?>
			</a>
		</div>
	</div>
	
	
	<div id="fr-qtzr_user" class="_menu">
		<div class="_link">
			<a href="<?php echo $root ;?>/render_funds/quantizerUsersExpenses" class="tip_tip_default" title="Informe de gastos por periodos para usuarios.">
				<?php echo $this->Html->tag('h4', 'Cuantificador de gastos (Usuarios)');?>
				<?php echo $this->Html->tag('h5', 'Cuantificador de gastos (Usuarios)');?>
			</a>
		</div>
	</div>
	
	<?php
	if($this->RequestAction('/external_functions/getIdDataSession/') == $this->RequestAction('/attribute_tables/validateTreasurer') || $userAdmin == 1 && $class['css_cols'] == '4Cols')
	{
	echo '<div id="fr-stats" class="_menu">
			<div class="_link">
			<a href="'.$root.'/render_funds/expiredFunds" class="tip_tip_default" title="Revisa los fondos expirados para rendir.">'.
					$this->Html->tag('h4', 'Fondos Expirados').
					$this->Html->tag('h5', 'Fondos Expirados').
				'</a>
			</div>
		</div>';
	}
	?>
</div>