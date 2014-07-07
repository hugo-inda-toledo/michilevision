<script>
	 inicializarDatePicker('default');
</script>
<?php
	$cont=0;
	echo $this->form->create('RenderFund');
	if(count($expiredFunds) != 0)
	{
	?>
		<table border=1>
		<tr>
			<th colspan=7><p align='center'>Fondos expirados.</p></th>
		</tr>
		<tr>
			<th></th>
			<th>Id</th>
			<th>Titulo del fondo</th>
			<th>Solicitante</th>
			<th>Total del fondo</th>
			<th>Fecha de expiracion</th>
			<th>Ver detalle</th>
		</tr>
<?php
		foreach($expiredFunds as $expiredFund)
		{
			
			echo '<tr>';
				echo '<td>'.$this->form->input('FundChecked.'.$cont.'.check_fund', array('label' => '', 'type' => 'checkbox', 'value' => $expiredFund['RenderFund']['id'])).'</td>';
				echo '<td>'.$expiredFund['RenderFund']['id'].'</td>';
				echo '<td>'.$expiredFund['RenderFund']['render_fund_title'].'</td>';
				echo '<td>'.$expiredFund['User']['name'].' '.$expiredFund['User']['first_lastname'].'</td>';
				echo '<td>'.$expiredFund['Badge']['symbol'].number_format($expiredFund['RenderFund']['total_price'], 0, null, '.').'</td>';
				echo '<td>'.$expiredFund['RenderFund']['render_date_end'].'</td>';
				echo '<td>'.$this->html->link('Ver detalle', '/render_funds/view/'.$expiredFund['RenderFund']['id'], array('target' => '_blank')).'</td>';
			echo '</tr>';
			
			$cont++;
		}
		
		echo "</table>";
		
		echo $this->form->input('render_date', array('label' => 'Selecciona fecha para cambiar fecha de rendicion', 'id' => 'datepickerUsuario', 'readOnly' => 'readOnly'));
		echo "<br>";
		echo $this->form->end('Cambiar fecha de expiracion');
	}
	else
		echo "<p align='center'><span class='link_admin link_circuit_rejected'>No hay fondos expirados a la fecha.</span></p>";
		
	echo $this->html->link('Volver al menu de estadisticas','/render_funds/statsMenu',array('title' => 'Volver al menu de estadisticas de Fondos por rendir','class' => 'tip_tip_default link_admin link_back'));
?>
