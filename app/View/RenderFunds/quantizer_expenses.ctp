<?php
	if(count($data) == 0)
	{
	?>
		<h3><?php echo $this->html->link('Fondos por rendir','/render_funds/'); ?> &gt; <?php echo $this->html->link('Estadisticas e Informes','/render_funds/statsMenu'); ?> &gt; Cuantificador de gastos (C.Costos y/o Gerencias)</h3>

		<script>
			 inicializarDatePicker('range');
		</script>

			<?php
				echo $this->form->create('RenderFund', array('id' => 'UserLoginForm'));
				echo $this->form->input('management', array('label' => 'Gerencia del Solicitante', 'type' => 'select', 'options' => $managements, 'default' => 0));
				echo $this->form->input('cost_center', array('label' => 'Centro de costo', 'type' => 'select', 'options' => $cost_centers, 'default' => 0));
				echo $this->form->input('from_date', array('label' => 'Desde', 'id' => 'datepicker-desde', 'readOnly' => 'readOnly'));
				echo $this->form->input('to_date', array('label' => 'Hasta', 'id' => 'datepicker-hasta', 'readOnly' => 'readOnly'));
				echo "<br>";
				echo $this->form->end('Buscar');

				echo "<br>";
				echo "<p align='center'>".$this->html->link('Volver al menu de estadisticas','/render_funds/statsMenu',array('title' => 'Volver al menu de estadisticas de Fondos por rendir','class' => 'tip_tip_default link_admin link_back'))."</p>";
	}
	else
	{
		if($data != false)
		{
			?>
			
			<h3><?php echo $this->html->link('Fondos por rendir','/render_funds/'); ?> &gt; <?php echo $this->html->link('Estadisticas e Informes','/render_funds/statsMenu'); ?> &gt; Cuantificador de gastos (C.Costos y/o Gerencias)</h3>
			
			<script type="text/javascript">
			$(function() {
				$( "div#accordion" ).accordion({
					header: 'div.header',
					active: false,
					collapsible: true
					}
				);
			});

			$(function() {
				$( "div#accordion2" ).accordion({
					header: 'div.header2',
					active: true,
					collapsible: true
					}
				);
			});
			</script>
			
			<?php
				echo $this->Html->script('google_chart_api');
			?>
			
			<script>
				 google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(drawChart);
				function drawChart() 
				{
					var data = google.visualization.arrayToDataTable([
																										['Task', 'Hours per Day'],
																										['Fondos Aprobados',     <?php echo $data['Details']['approved_funds']?>],
																										['Fondos por Aprobar',      <?php echo $data['Details']['to_sign_funds']?>],
																										['Fondos Rechazados',  <?php echo $data['Details']['declined_funds']?>]
																										
					]);

					var options = {
											title: 'Fondos'
					};

					var chart = new google.visualization.PieChart(document.getElementById('chart_funds'));
					chart.draw(data, options);
				}
			</script>
			
			<script>
				 google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(drawChart);
				function drawChart() 
				{
					var data = google.visualization.arrayToDataTable([
																										['Task', 'Hours per Day'],
																										['Fondos Rendidos',     <?php echo $data['Details']['render_funds']?>],
																										['Fondos Solo Aprobados',      <?php echo $data['Details']['only_approved']?>],
																										['Fondos por Rendir',  <?php echo $data['Details']['to_render_funds']?>],
																										['Fondos Expirados',  <?php echo $data['Details']['expired_funds']?>]
																										
					]);

					var options = {
											title: 'Fondos Aprobados'
					};

					var chart = new google.visualization.PieChart(document.getElementById('chart_approved_funds'));
					chart.draw(data, options);
				}
			</script>
			
			<div class="ViewTitleOptions">
					<?php 
						if($data['Details']['management_name'] != 'N/A' && $data['Details']['cost_center_name'] != 'N/A')
						{
							echo $this->Html->tag('strong', 'Resultado para la busqueda de fondos de la '.$data['Details']['management_name'].', especificamente el centro de costo '.$data['Details']['cost_center_name'], array('class' => 'link_admin link_report'));
						}
						else
						{
							if($data['Details']['management_name'] != 'N/A')
							{
								echo $this->Html->tag('strong', 'Resultado para la busqueda de fondos de la '.$data['Details']['management_name'], array('class' => 'link_admin link_report'));
							}
							else
							{
								echo $this->Html->tag('strong', 'Resultado para la busqueda de fondos del centro de costo '.$data['Details']['cost_center_name'], array('class' => 'link_admin link_report'));
							}
						}
					?>
			</div>
			
			<br><br>
			
			<div class="dashboard-row-1">
				<div class="dashboard-sec graph-1">
						<div id="chart_funds" style="width: 350px; height: 150px;"></div>
				</div>
				<div class="dashboard-sec graph-2">
						<div id="chart_approved_funds" style="width: 350px; height: 150px;"></div>
				</div>
			</div>
			
			<?php echo $this->Html->tag('h3', 'Detalle', array('class' => 'link_admin link_zoom'));?>

			<table>
				<tr>
					<th>Divisa</th>
					<th>Total Dinero de Fondos</th>
					<th>Total Dinero Rendido</th>
					<th>Deuda Total</th>
				</tr>
				<tr>
					<td>Peso Chileno</td>
					<td><?php echo 'CLP $'.number_format($data['Details']['clp_total'], 0, null, '.'); ?></td>
					<td><?php echo 'CLP $'.number_format($data['Details']['clp_total_render'], 0, null, '.'); ?></td>
					<td><font color="red"><?php echo 'CLP $'.number_format($data['Details']['clp_total'] - $data['Details']['clp_total_render'], 0, null, '.'); ?></font></td>
				</tr>
				<tr>
					<td>Dolar Americano</td>
					<td><?php echo 'USD $'.number_format($data['Details']['usd_total'], 0, null, '.'); ?></td>
					<td><?php echo 'USD $'.number_format($data['Details']['usd_total_render'], 0, null, '.'); ?></td>
					<td><font color="red"><?php echo 'USD $'.number_format($data['Details']['usd_total'] - $data['Details']['usd_total_render'], 0, null, '.'); ?></font></td>
				</tr>
				<tr>
					<td>Euro</td>
					<td><?php echo '€ $'.number_format($data['Details']['euro_total'], 0, null, '.'); ?></td>
					<td><?php echo '€ $'.number_format($data['Details']['euro_total_render'], 0, null, '.'); ?></td>
					<td><font color="red"><?php echo '€ $'.number_format($data['Details']['euro_total'] - $data['Details']['euro_total_render'], 0, null, '.'); ?></font></td>
				</tr>
				<tr>
					<td>UF</td>
					<td><?php echo 'UF $'.number_format($data['Details']['uf_total'], 0, null, '.'); ?></td>
					<td><?php echo 'UF $'.number_format($data['Details']['uf_total_render'], 0, null, '.'); ?></td>
					<td><font color="red"><?php echo 'UF $'.number_format($data['Details']['uf_total'] - $data['Details']['uf_total_render'], 0, null, '.'); ?></font></td>
				</tr>
				<tr>
					<td>UTM</td>
					<td><?php echo 'UTM $'.number_format($data['Details']['utm_total'], 0, null, '.'); ?></td>
					<td><?php echo 'UTM $'.number_format($data['Details']['utm_total_render'], 0, null, '.'); ?></td>
					<td><font color="red"><?php echo 'UTM $'.number_format($data['Details']['utm_total'] - $data['Details']['utm_total_render'], 0, null, '.'); ?></font></td>
				</tr>
			</table>
			
			<div id="accordion2">
				<div class="header2">
					<?php echo $this->Html->tag('span', 'Fondos aprobados ('.$data['Details']['approved_funds'].')', array('class' => 'link_admin link_circuit_approved'));?>
				</div>
				<div class="fr_detail">
					
					<?php
						if(isset($data['ApprovedFunds']))
						{
							if($data['ApprovedFunds'] != false)
							{
								echo '<div id="accordion">';
								$z=0;
							
								if(isset($data['ApprovedFunds']['RenderFunds']))
								{
									if($data['ApprovedFunds']['RenderFunds']  != false)
									{
										echo $this->Html->tag('span', 'Rendidos: '.$data['Details']['render_funds']).'<br>';
										
										foreach($data['ApprovedFunds']['RenderFunds'] as $renderFund)
										{
											$fecha_fondo = substr($renderFund['RenderFund']['created'], 8, 2).'-'.substr($renderFund['RenderFund']['created'], 5, 2) .'-'.substr($renderFund['RenderFund']['created'], 0, 4);
											
											////////////////Inicio de cabezera accordion///////////////////////
											echo '<div class="header" id="header_'.$z.'">
														<div class="fr_date tip_tip_default link_admin link_calendar" title="Fecha de emisión de la solicitud">'.$fecha_fondo.'</div>
														<div class="fr_time tip_tip_default link_admin link_clock" title="Hora de emisión de la solicitud">'.substr($renderFund['RenderFund']['created'], 11, 5).'</div>
														<div class="fr_title">'.$this->RequestAction('/external_functions/recortar_texto_simple/'.$renderFund['RenderFund']['render_fund_title'].'/40').'</div>
														<div class="fr_username tip_tip_default link_admin link_user" title="Usuario solicitante">'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname'].'</div>';
				
												if($renderFund['RenderFund']['state_id'] == 1){
													echo '<div class="fr_status fr_pending" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
												}
						
												if($renderFund['RenderFund']['state_id'] == 2){
													if($renderFund['RenderFund']['deliver'] == 1 && $renderFund['RenderFund']['render'] == 1){
													echo '<div class="fr_status fr_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : RENDIDO">'.$renderFund['State']['state'].' (R)</div>';
													}
											
													else{
														if($renderFund['RenderFund']['deliver'] == 1){
															echo '<div class="fr_status fr_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : ENTREGADO">'.$renderFund['State']['state'].' (E)</div>';
														}
														else{
															echo '<div class="fr_status fr_approved" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
														}
													}
												}
												
												if($renderFund['RenderFund']['state_id'] == 3){
													echo '<div class="fr_status fr_rejected" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
												}
											
										echo '</div>';
										////////////////Fin del cabezera accordion///////////////////////
										
										////////////////Inicio del Contenido///////////////////////
										echo '<div class="fr_detail">
														<div class="fr_info">
															<div class="info_01">
																<table>
																	<tr>
																		<td><span class="link_admin link_user">Solicitante :</span></td>
																		<td><strong>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname']).'</strong></td>
																	</tr>
																	<tr>
																		<td><span class="link_admin link_used_for">Utilizado por :</span></td>
																		<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['RenderFund']['used_by_name']).'</strong></td>
																	</tr>
																	<tr>
																		<td><span class="link_admin link_management">Gerencia :</span></td>
																		<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['Management']['management_name']).'</td>
																	</tr>
																	<tr>
																		<td><span class="link_admin link_cost_center">Centro costo :</span></td>
																		<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['CostCenter']['cost_center_code'].' '.$renderFund['CostCenter']['cost_center_name']).'</td>
																	</tr>
																</table>
															</div>
														
															<div class="info_02">
																<table>
																	<tr>
																		<td width="100"><strong class="link_admin link_money" title="Monto total"> Monto total</strong> :</td>
																		<td width="100"><strong>'.$renderFund['Badge']['symbol'].number_format($renderFund['RenderFund']['total_price'], 0,null, '.').'</strong></td>
																	</tr>';
																
													
													if($renderFund['RenderFund']['state_id'] == 1)
														$css_status = array(0 => 'link_fr_pending', 1 => 'st_pending');
														
													if($renderFund['RenderFund']['state_id'] == 2)
														$css_status = array(0 => 'link_fr_approved', 1 => 'st_approved');
														
													if($renderFund['RenderFund']['state_id'] == 3)
														$css_status = array(0 => 'link_fr_rejected', 1 => 'st_rejected');
													
														echo '<tr>
																		<td><strong class="link_admin '.$css_status[0].'"> Estado</strong> :</td>
																	<td><strong class="'.$css_status[1].'">'.$renderFund['State']['state'].'</strong></td>
																</tr>';	
																
																
													if(trim($renderFund['RenderFund']['fund_number']) != ''){
														echo '<tr>
																	<td><span class="link_admin link_ticket">N° Fondo por rendir :</span></td>
																	<td><strong>'.$renderFund['RenderFund']['fund_number'].'</strong></td>
																</tr>';
													}	
														
													
													echo '<tr>
																<td>&nbsp;</td>
																<td>&nbsp;</td>
															</tr>';
													
													if($renderFund['RenderFund']['render_date_start'] != NULL && $renderFund['RenderFund']['render_date_end'] != NULL && $renderFund['RenderFund']['state_id'] == 2 &&  $this->RequestAction('/external_functions/getIdDataSession') == $renderFund['RenderFund']['user_id'] && $renderFund['RenderFund']['render'] == 1){
														echo '<tr>
																	<td><span class="link_admin link_calendar">Fecha tope para rendir :</span></td>
																	<td>'.$this->Html->tag('span', $this->RequestAction('/external_functions/setDate/'.$renderFund['RenderFund']['render_date_end']), array('class'  => 'link_admin link_ok')).'</td>
																</tr>';
													}
													else
													{
														if($renderFund['RenderFund']['render_date_start'] != NULL && $renderFund['RenderFund']['render_date_end'] != NULL && $renderFund['RenderFund']['state_id'] == 2 &&  $this->RequestAction('/external_functions/getIdDataSession') == $renderFund['RenderFund']['user_id'])
														{
														echo '<tr>
																	<td><span class="link_admin link_calendar">Fecha tope para rendir :</span></td>
																	<td>'.$this->RequestAction('/external_functions/setDate/'.$renderFund['RenderFund']['render_date_end']).'</td>
																</tr>';
														}
													}
													
													echo '</table>
															</div>';
													
													
												/* *******************  Botones / Acciones  ******************* */
													
												echo '<div class="info_03">
														<table>
															<tr>
																<td>'.$this->html->link('Ver detalle','/render_funds/view/'.$renderFund['RenderFund']['id'],array('class'=>'a_button', 'target' => '_blank')).'</td>
															</tr>';
													
													echo '</table>
														</div>';
												
												
												echo '</div>';
												echo "</div>";
					
											$z++;
										
										}
									}
								}
								
								if(isset($data['ApprovedFunds']['ToRenderFunds']))
								{
									if($data['ApprovedFunds']['ToRenderFunds']  != false)
									{
										echo $this->Html->tag('span', 'Por rendir: '.$data['Details']['to_render_funds']).'<br>';
										
										foreach($data['ApprovedFunds']['ToRenderFunds'] as $renderFund)
										{
											$fecha_fondo = substr($renderFund['RenderFund']['created'], 8, 2).'-'.substr($renderFund['RenderFund']['created'], 5, 2) .'-'.substr($renderFund['RenderFund']['created'], 0, 4);
											
											////////////////Inicio de cabezera accordion///////////////////////
											echo '<div class="header" id="header_'.$z.'">
														<div class="fr_date tip_tip_default link_admin link_calendar" title="Fecha de emisión de la solicitud">'.$fecha_fondo.'</div>
														<div class="fr_time tip_tip_default link_admin link_clock" title="Hora de emisión de la solicitud">'.substr($renderFund['RenderFund']['created'], 11, 5).'</div>
														<div class="fr_title">'.$this->RequestAction('/external_functions/recortar_texto_simple/'.$renderFund['RenderFund']['render_fund_title'].'/40').'</div>
														<div class="fr_username tip_tip_default link_admin link_user" title="Usuario solicitante">'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname'].'</div>';
				
												if($renderFund['RenderFund']['state_id'] == 1){
													echo '<div class="fr_status fr_pending" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
												}
						
												if($renderFund['RenderFund']['state_id'] == 2){
													if($renderFund['RenderFund']['deliver'] == 1 && $renderFund['RenderFund']['render'] == 1){
													echo '<div class="fr_status fr_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : RENDIDO">'.$renderFund['State']['state'].' (R)</div>';
													}
											
													else{
														if($renderFund['RenderFund']['deliver'] == 1){
															echo '<div class="fr_status fr_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : ENTREGADO">'.$renderFund['State']['state'].' (E)</div>';
														}
														else{
															echo '<div class="fr_status fr_approved" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
														}
													}
												}
												
												if($renderFund['RenderFund']['state_id'] == 3){
													echo '<div class="fr_status fr_rejected" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
												}
											
										echo '</div>';
										////////////////Fin del cabezera accordion///////////////////////
										
										////////////////Inicio del Contenido///////////////////////
										echo '<div class="fr_detail">
														<div class="fr_info">
															<div class="info_01">
																<table>
																	<tr>
																		<td><span class="link_admin link_user">Solicitante :</span></td>
																		<td><strong>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname']).'</strong></td>
																	</tr>
																	<tr>
																		<td><span class="link_admin link_used_for">Utilizado por :</span></td>
																		<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['RenderFund']['used_by_name']).'</strong></td>
																	</tr>
																	<tr>
																		<td><span class="link_admin link_management">Gerencia :</span></td>
																		<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['Management']['management_name']).'</td>
																	</tr>
																	<tr>
																		<td><span class="link_admin link_cost_center">Centro costo :</span></td>
																		<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['CostCenter']['cost_center_code'].' '.$renderFund['CostCenter']['cost_center_name']).'</td>
																	</tr>
																</table>
															</div>
														
															<div class="info_02">
																<table>
																	<tr>
																		<td width="100"><strong class="link_admin link_money" title="Monto total"> Monto total</strong> :</td>
																		<td width="100"><strong>'.$renderFund['Badge']['symbol'].number_format($renderFund['RenderFund']['total_price'], 0,null, '.').'</strong></td>
																	</tr>';
																
													
													if($renderFund['RenderFund']['state_id'] == 1)
														$css_status = array(0 => 'link_fr_pending', 1 => 'st_pending');
														
													if($renderFund['RenderFund']['state_id'] == 2)
														$css_status = array(0 => 'link_fr_approved', 1 => 'st_approved');
														
													if($renderFund['RenderFund']['state_id'] == 3)
														$css_status = array(0 => 'link_fr_rejected', 1 => 'st_rejected');
													
														echo '<tr>
																		<td><strong class="link_admin '.$css_status[0].'"> Estado</strong> :</td>
																	<td><strong class="'.$css_status[1].'">'.$renderFund['State']['state'].'</strong></td>
																</tr>';	
																
																
													if(trim($renderFund['RenderFund']['fund_number']) != ''){
														echo '<tr>
																	<td><span class="link_admin link_ticket">N° Fondo por rendir :</span></td>
																	<td><strong>'.$renderFund['RenderFund']['fund_number'].'</strong></td>
																</tr>';
													}	
														
													
													echo '<tr>
																<td>&nbsp;</td>
																<td>&nbsp;</td>
															</tr>';
													
													if($renderFund['RenderFund']['render_date_start'] != NULL && $renderFund['RenderFund']['render_date_end'] != NULL && $renderFund['RenderFund']['state_id'] == 2 &&  $this->RequestAction('/external_functions/getIdDataSession') == $renderFund['RenderFund']['user_id'] && $renderFund['RenderFund']['render'] == 1){
														echo '<tr>
																	<td><span class="link_admin link_calendar">Fecha tope para rendir :</span></td>
																	<td>'.$this->Html->tag('span', $this->RequestAction('/external_functions/setDate/'.$renderFund['RenderFund']['render_date_end']), array('class'  => 'link_admin link_ok')).'</td>
																</tr>';
													}
													else
													{
														if($renderFund['RenderFund']['render_date_start'] != NULL && $renderFund['RenderFund']['render_date_end'] != NULL && $renderFund['RenderFund']['state_id'] == 2 &&  $this->RequestAction('/external_functions/getIdDataSession') == $renderFund['RenderFund']['user_id'])
														{
														echo '<tr>
																	<td><span class="link_admin link_calendar">Fecha tope para rendir :</span></td>
																	<td>'.$this->RequestAction('/external_functions/setDate/'.$renderFund['RenderFund']['render_date_end']).'</td>
																</tr>';
														}
													}
													
													echo '</table>
															</div>';
													
													
												/* *******************  Botones / Acciones  ******************* */
													
												echo '<div class="info_03">
														<table>
															<tr>
																<td>'.$this->html->link('Ver detalle','/render_funds/view/'.$renderFund['RenderFund']['id'],array('class'=>'a_button', 'target' => '_blank')).'</td>
															</tr>';
													
													echo '</table>
														</div>';
												
												
												echo '</div>';
												echo "</div>";
					
											$z++;
										
										}
									}
								}
								
								if(isset($data['ApprovedFunds']['ExpiredFunds']))
								{
									if($data['ApprovedFunds']['ExpiredFunds']  != false)
									{
										echo $this->Html->tag('span', 'Expirados: '.$data['Details']['expired_funds']).'<br>';
										
										foreach($data['ApprovedFunds']['ExpiredFunds'] as $renderFund)
										{
											$fecha_fondo = substr($renderFund['RenderFund']['created'], 8, 2).'-'.substr($renderFund['RenderFund']['created'], 5, 2) .'-'.substr($renderFund['RenderFund']['created'], 0, 4);
											
											////////////////Inicio de cabezera accordion///////////////////////
											echo '<div class="header" id="header_'.$z.'">
														<div class="fr_date tip_tip_default link_admin link_calendar" title="Fecha de emisión de la solicitud">'.$fecha_fondo.'</div>
														<div class="fr_time tip_tip_default link_admin link_clock" title="Hora de emisión de la solicitud">'.substr($renderFund['RenderFund']['created'], 11, 5).'</div>
														<div class="fr_title">'.$this->RequestAction('/external_functions/recortar_texto_simple/'.$renderFund['RenderFund']['render_fund_title'].'/40').'</div>
														<div class="fr_username tip_tip_default link_admin link_user" title="Usuario solicitante">'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname'].'</div>';
				
												if($renderFund['RenderFund']['state_id'] == 1){
													echo '<div class="fr_status fr_pending" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
												}
						
												if($renderFund['RenderFund']['state_id'] == 2){
													if($renderFund['RenderFund']['deliver'] == 1 && $renderFund['RenderFund']['render'] == 1){
													echo '<div class="fr_status fr_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : RENDIDO">'.$renderFund['State']['state'].' (R)</div>';
													}
											
													else{
														if($renderFund['RenderFund']['deliver'] == 1){
															echo '<div class="fr_status fr_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : ENTREGADO">'.$renderFund['State']['state'].' (E)</div>';
														}
														else{
															echo '<div class="fr_status fr_approved" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
														}
													}
												}
												
												if($renderFund['RenderFund']['state_id'] == 3){
													echo '<div class="fr_status fr_rejected" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
												}
											
										echo '</div>';
										////////////////Fin del cabezera accordion///////////////////////
										
										////////////////Inicio del Contenido///////////////////////
										echo '<div class="fr_detail">
														<div class="fr_info">
															<div class="info_01">
																<table>
																	<tr>
																		<td><span class="link_admin link_user">Solicitante :</span></td>
																		<td><strong>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname']).'</strong></td>
																	</tr>
																	<tr>
																		<td><span class="link_admin link_used_for">Utilizado por :</span></td>
																		<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['RenderFund']['used_by_name']).'</strong></td>
																	</tr>
																	<tr>
																		<td><span class="link_admin link_management">Gerencia :</span></td>
																		<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['Management']['management_name']).'</td>
																	</tr>
																	<tr>
																		<td><span class="link_admin link_cost_center">Centro costo :</span></td>
																		<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['CostCenter']['cost_center_code'].' '.$renderFund['CostCenter']['cost_center_name']).'</td>
																	</tr>
																</table>
															</div>
														
															<div class="info_02">
																<table>
																	<tr>
																		<td width="100"><strong class="link_admin link_money" title="Monto total"> Monto total</strong> :</td>
																		<td width="100"><strong>'.$renderFund['Badge']['symbol'].number_format($renderFund['RenderFund']['total_price'], 0,null, '.').'</strong></td>
																	</tr>';
																
													
													if($renderFund['RenderFund']['state_id'] == 1)
														$css_status = array(0 => 'link_fr_pending', 1 => 'st_pending');
														
													if($renderFund['RenderFund']['state_id'] == 2)
														$css_status = array(0 => 'link_fr_approved', 1 => 'st_approved');
														
													if($renderFund['RenderFund']['state_id'] == 3)
														$css_status = array(0 => 'link_fr_rejected', 1 => 'st_rejected');
													
														echo '<tr>
																		<td><strong class="link_admin '.$css_status[0].'"> Estado</strong> :</td>
																	<td><strong class="'.$css_status[1].'">'.$renderFund['State']['state'].'</strong></td>
																</tr>';	
																
																
													if(trim($renderFund['RenderFund']['fund_number']) != ''){
														echo '<tr>
																	<td><span class="link_admin link_ticket">N° Fondo por rendir :</span></td>
																	<td><strong>'.$renderFund['RenderFund']['fund_number'].'</strong></td>
																</tr>';
													}	
														
													
													echo '<tr>
																<td>&nbsp;</td>
																<td>&nbsp;</td>
															</tr>';
													
													if($renderFund['RenderFund']['render_date_start'] != NULL && $renderFund['RenderFund']['render_date_end'] != NULL && $renderFund['RenderFund']['state_id'] == 2 &&  $this->RequestAction('/external_functions/getIdDataSession') == $renderFund['RenderFund']['user_id'] && $renderFund['RenderFund']['render'] == 1){
														echo '<tr>
																	<td><span class="link_admin link_calendar">Fecha tope para rendir :</span></td>
																	<td>'.$this->Html->tag('span', $this->RequestAction('/external_functions/setDate/'.$renderFund['RenderFund']['render_date_end']), array('class'  => 'link_admin link_ok')).'</td>
																</tr>';
													}
													else
													{
														if($renderFund['RenderFund']['render_date_start'] != NULL && $renderFund['RenderFund']['render_date_end'] != NULL && $renderFund['RenderFund']['state_id'] == 2 &&  $this->RequestAction('/external_functions/getIdDataSession') == $renderFund['RenderFund']['user_id'])
														{
														echo '<tr>
																	<td><span class="link_admin link_calendar">Fecha tope para rendir :</span></td>
																	<td>'.$this->RequestAction('/external_functions/setDate/'.$renderFund['RenderFund']['render_date_end']).'</td>
																</tr>';
														}
													}
													
													echo '</table>
															</div>';
													
													
												/* *******************  Botones / Acciones  ******************* */
													
												echo '<div class="info_03">
														<table>
															<tr>
																<td>'.$this->html->link('Ver detalle','/render_funds/view/'.$renderFund['RenderFund']['id'],array('class'=>'a_button', 'target' => '_blank')).'</td>
															</tr>';
													
													echo '</table>
														</div>';
												
												
												echo '</div>';
												echo "</div>";
					
											$z++;
										
										}
									}
								}
								
								if(isset($data['ApprovedFunds']['Approved']))
								{
									if($data['ApprovedFunds']['Approved']  != false)
									{
										echo $this->Html->tag('span', 'Solo aprobados: '.$data['Details']['only_approved']).'<br>';
										
										foreach($data['ApprovedFunds']['Approved'] as $renderFund)
										{
											$fecha_fondo = substr($renderFund['RenderFund']['created'], 8, 2).'-'.substr($renderFund['RenderFund']['created'], 5, 2) .'-'.substr($renderFund['RenderFund']['created'], 0, 4);
											
											////////////////Inicio de cabezera accordion///////////////////////
											echo '<div class="header" id="header_'.$z.'">
														<div class="fr_date tip_tip_default link_admin link_calendar" title="Fecha de emisión de la solicitud">'.$fecha_fondo.'</div>
														<div class="fr_time tip_tip_default link_admin link_clock" title="Hora de emisión de la solicitud">'.substr($renderFund['RenderFund']['created'], 11, 5).'</div>
														<div class="fr_title">'.$this->RequestAction('/external_functions/recortar_texto_simple/'.$renderFund['RenderFund']['render_fund_title'].'/40').'</div>
														<div class="fr_username tip_tip_default link_admin link_user" title="Usuario solicitante">'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname'].'</div>';
				
												if($renderFund['RenderFund']['state_id'] == 1){
													echo '<div class="fr_status fr_pending" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
												}
						
												if($renderFund['RenderFund']['state_id'] == 2){
													if($renderFund['RenderFund']['deliver'] == 1 && $renderFund['RenderFund']['render'] == 1){
													echo '<div class="fr_status fr_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : RENDIDO">'.$renderFund['State']['state'].' (R)</div>';
													}
											
													else{
														if($renderFund['RenderFund']['deliver'] == 1){
															echo '<div class="fr_status fr_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : ENTREGADO">'.$renderFund['State']['state'].' (E)</div>';
														}
														else{
															echo '<div class="fr_status fr_approved" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
														}
													}
												}
												
												if($renderFund['RenderFund']['state_id'] == 3){
													echo '<div class="fr_status fr_rejected" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
												}
											
										echo '</div>';
										////////////////Fin del cabezera accordion///////////////////////
										
										////////////////Inicio del Contenido///////////////////////
										echo '<div class="fr_detail">
														<div class="fr_info">
															<div class="info_01">
																<table>
																	<tr>
																		<td><span class="link_admin link_user">Solicitante :</span></td>
																		<td><strong>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname']).'</strong></td>
																	</tr>
																	<tr>
																		<td><span class="link_admin link_used_for">Utilizado por :</span></td>
																		<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['RenderFund']['used_by_name']).'</strong></td>
																	</tr>
																	<tr>
																		<td><span class="link_admin link_management">Gerencia :</span></td>
																		<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['Management']['management_name']).'</td>
																	</tr>
																	<tr>
																		<td><span class="link_admin link_cost_center">Centro costo :</span></td>
																		<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['CostCenter']['cost_center_code'].' '.$renderFund['CostCenter']['cost_center_name']).'</td>
																	</tr>
																</table>
															</div>
														
															<div class="info_02">
																<table>
																	<tr>
																		<td width="100"><strong class="link_admin link_money" title="Monto total"> Monto total</strong> :</td>
																		<td width="100"><strong>'.$renderFund['Badge']['symbol'].number_format($renderFund['RenderFund']['total_price'], 0,null, '.').'</strong></td>
																	</tr>';
																
													
													if($renderFund['RenderFund']['state_id'] == 1)
														$css_status = array(0 => 'link_fr_pending', 1 => 'st_pending');
														
													if($renderFund['RenderFund']['state_id'] == 2)
														$css_status = array(0 => 'link_fr_approved', 1 => 'st_approved');
														
													if($renderFund['RenderFund']['state_id'] == 3)
														$css_status = array(0 => 'link_fr_rejected', 1 => 'st_rejected');
													
														echo '<tr>
																		<td><strong class="link_admin '.$css_status[0].'"> Estado</strong> :</td>
																	<td><strong class="'.$css_status[1].'">'.$renderFund['State']['state'].'</strong></td>
																</tr>';	
																
																
													if(trim($renderFund['RenderFund']['fund_number']) != ''){
														echo '<tr>
																	<td><span class="link_admin link_ticket">N° Fondo por rendir :</span></td>
																	<td><strong>'.$renderFund['RenderFund']['fund_number'].'</strong></td>
																</tr>';
													}	
														
													
													echo '<tr>
																<td>&nbsp;</td>
																<td>&nbsp;</td>
															</tr>';
													
													if($renderFund['RenderFund']['render_date_start'] != NULL && $renderFund['RenderFund']['render_date_end'] != NULL && $renderFund['RenderFund']['state_id'] == 2 &&  $this->RequestAction('/external_functions/getIdDataSession') == $renderFund['RenderFund']['user_id'] && $renderFund['RenderFund']['render'] == 1){
														echo '<tr>
																	<td><span class="link_admin link_calendar">Fecha tope para rendir :</span></td>
																	<td>'.$this->Html->tag('span', $this->RequestAction('/external_functions/setDate/'.$renderFund['RenderFund']['render_date_end']), array('class'  => 'link_admin link_ok')).'</td>
																</tr>';
													}
													else
													{
														if($renderFund['RenderFund']['render_date_start'] != NULL && $renderFund['RenderFund']['render_date_end'] != NULL && $renderFund['RenderFund']['state_id'] == 2 &&  $this->RequestAction('/external_functions/getIdDataSession') == $renderFund['RenderFund']['user_id'])
														{
														echo '<tr>
																	<td><span class="link_admin link_calendar">Fecha tope para rendir :</span></td>
																	<td>'.$this->RequestAction('/external_functions/setDate/'.$renderFund['RenderFund']['render_date_end']).'</td>
																</tr>';
														}
													}
													
													echo '</table>
															</div>';
													
													
												/* *******************  Botones / Acciones  ******************* */
													
												echo '<div class="info_03">
														<table>
															<tr>
																<td>'.$this->html->link('Ver detalle','/render_funds/view/'.$renderFund['RenderFund']['id'],array('class'=>'a_button', 'target' => '_blank')).'</td>
															</tr>';
													
													echo '</table>
														</div>';
												
												
												echo '</div>';
												echo "</div>";
					
											$z++;
										
										}
									}
								}
								
								echo "</div>";
							}
						}
						else
						{
							echo $this->Html->tag('span', 'No existen Fondos', array('class' => 'link_admin link_render_fund'));
						}
					?>
				</div>
				<div class="header2">
					<?php echo $this->Html->tag('span', 'Fondos rechazados ('.$data['Details']['declined_funds'].')', array('class' => 'link_admin link_circuit_rejected'));?>
				</div>
				<div class="fr_detail">
					<?php
						if(isset($data['DeclinedFunds']))
						{
							if($data['DeclinedFunds'] != 0)
							{
								echo '<div id="accordion">';
								
								$z=0;
								
								foreach($data['DeclinedFunds'] as $renderFund)
								{
									$fecha_fondo = substr($renderFund['RenderFund']['created'], 8, 2).'-'.substr($renderFund['RenderFund']['created'], 5, 2) .'-'.substr($renderFund['RenderFund']['created'], 0, 4);
									
									////////////////Inicio de cabezera accordion///////////////////////
									echo '<div class="header" id="header_'.$z.'">
												<div class="fr_date tip_tip_default link_admin link_calendar" title="Fecha de emisión de la solicitud">'.$fecha_fondo.'</div>
												<div class="fr_time tip_tip_default link_admin link_clock" title="Hora de emisión de la solicitud">'.substr($renderFund['RenderFund']['created'], 11, 5).'</div>
												<div class="fr_title">'.$this->RequestAction('/external_functions/recortar_texto_simple/'.$renderFund['RenderFund']['render_fund_title'].'/40').'</div>
												<div class="fr_username tip_tip_default link_admin link_user" title="Usuario solicitante">'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname'].'</div>';
		
										if($renderFund['RenderFund']['state_id'] == 1){
											echo '<div class="fr_status fr_pending" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
										}
				
										if($renderFund['RenderFund']['state_id'] == 2){
											if($renderFund['RenderFund']['deliver'] == 1 && $renderFund['RenderFund']['render'] == 1){
											echo '<div class="fr_status fr_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : RENDIDO">'.$renderFund['State']['state'].' (R)</div>';
											}
									
											else{
												if($renderFund['RenderFund']['deliver'] == 1){
													echo '<div class="fr_status fr_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : ENTREGADO">'.$renderFund['State']['state'].' (E)</div>';
												}
												else{
													echo '<div class="fr_status fr_approved" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
												}
											}
										}
										
										if($renderFund['RenderFund']['state_id'] == 3){
											echo '<div class="fr_status fr_rejected" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
										}
									
								echo '</div>';
								////////////////Fin del cabezera accordion///////////////////////
								
								////////////////Inicio del Contenido///////////////////////
								echo '<div class="fr_detail">
												<div class="fr_info">
													<div class="info_01">
														<table>
															<tr>
																<td><span class="link_admin link_user">Solicitante :</span></td>
																<td><strong>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname']).'</strong></td>
															</tr>
															<tr>
																<td><span class="link_admin link_used_for">Utilizado por :</span></td>
																<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['RenderFund']['used_by_name']).'</strong></td>
															</tr>
															<tr>
																<td><span class="link_admin link_management">Gerencia :</span></td>
																<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['Management']['management_name']).'</td>
															</tr>
															<tr>
																<td><span class="link_admin link_cost_center">Centro costo :</span></td>
																<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['CostCenter']['cost_center_code'].' '.$renderFund['CostCenter']['cost_center_name']).'</td>
															</tr>
														</table>
													</div>
												
													<div class="info_02">
														<table>
															<tr>
																<td width="100"><strong class="link_admin link_money" title="Monto total"> Monto total</strong> :</td>
																<td width="100"><strong>'.$renderFund['Badge']['symbol'].number_format($renderFund['RenderFund']['total_price'], 0,null, '.').'</strong></td>
															</tr>';
														
											
											if($renderFund['RenderFund']['state_id'] == 1)
												$css_status = array(0 => 'link_fr_pending', 1 => 'st_pending');
												
											if($renderFund['RenderFund']['state_id'] == 2)
												$css_status = array(0 => 'link_fr_approved', 1 => 'st_approved');
												
											if($renderFund['RenderFund']['state_id'] == 3)
												$css_status = array(0 => 'link_fr_rejected', 1 => 'st_rejected');
											
												echo '<tr>
																<td><strong class="link_admin '.$css_status[0].'"> Estado</strong> :</td>
															<td><strong class="'.$css_status[1].'">'.$renderFund['State']['state'].'</strong></td>
														</tr>';	
														
														
											if(trim($renderFund['RenderFund']['fund_number']) != ''){
												echo '<tr>
															<td><span class="link_admin link_ticket">N° Fondo por rendir :</span></td>
															<td><strong>'.$renderFund['RenderFund']['fund_number'].'</strong></td>
														</tr>';
											}	
												
											
											echo '<tr>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
													</tr>';
											
											if($renderFund['RenderFund']['render_date_start'] != NULL && $renderFund['RenderFund']['render_date_end'] != NULL && $renderFund['RenderFund']['state_id'] == 2 &&  $this->RequestAction('/external_functions/getIdDataSession') == $renderFund['RenderFund']['user_id'] && $renderFund['RenderFund']['render'] == 1){
												echo '<tr>
															<td><span class="link_admin link_calendar">Fecha tope para rendir :</span></td>
															<td>'.$this->Html->tag('span', $this->RequestAction('/external_functions/setDate/'.$renderFund['RenderFund']['render_date_end']), array('class'  => 'link_admin link_ok')).'</td>
														</tr>';
											}
											else
											{
												if($renderFund['RenderFund']['render_date_start'] != NULL && $renderFund['RenderFund']['render_date_end'] != NULL && $renderFund['RenderFund']['state_id'] == 2 &&  $this->RequestAction('/external_functions/getIdDataSession') == $renderFund['RenderFund']['user_id'])
												{
												echo '<tr>
															<td><span class="link_admin link_calendar">Fecha tope para rendir :</span></td>
															<td>'.$this->RequestAction('/external_functions/setDate/'.$renderFund['RenderFund']['render_date_end']).'</td>
														</tr>';
												}
											}
											
											echo '</table>
													</div>';
											
											
										/* *******************  Botones / Acciones  ******************* */
											
										echo '<div class="info_03">
												<table>
													<tr>
														<td>'.$this->html->link('Ver detalle','/render_funds/view/'.$renderFund['RenderFund']['id'],array('class'=>'a_button', 'target' => '_blank')).'</td>
													</tr>';
											
											if($renderFund['RenderFund']['state_id'] == 3 && $renderFund['RenderFund']['user_id'] == $this->RequestAction('/external_functions/getIdDataSession')){
												echo '<tr>
													<td>'.$this->html->link('Reenviar fondo','/render_funds/resendFund/'.$renderFund['RenderFund']['id'],array('class'=>'a_button', 'target' => '_blank')).'</td>
												</tr>';
											}
											
											echo '</table>
												</div>';
										
										
										echo '</div>';
										echo "</div>";
			
									$z++;
								
								}
								echo "</div>";
							}
						}
						else
							echo $this->Html->tag('span', 'No existen Fondos', array('class' => 'link_admin link_render_fund'));
					?>
				</div>
				<div class="header2">
					<?php echo $this->Html->tag('span', 'Fondos por aprobar ('.$data['Details']['to_sign_funds'].')', array('class' => 'link_admin link_status'));?>
				</div>
				<div class="fr_detail">
					<?php
						if(isset($data['ToSignFunds']))
						{
							if($data['ToSignFunds'] != 0)
							{
								echo '<div id="accordion">';
								
								$z=0;
								
								foreach($data['ToSignFunds'] as $renderFund)
								{
									$fecha_fondo = substr($renderFund['RenderFund']['created'], 8, 2).'-'.substr($renderFund['RenderFund']['created'], 5, 2) .'-'.substr($renderFund['RenderFund']['created'], 0, 4);
									
									////////////////Inicio de cabezera accordion///////////////////////
									echo '<div class="header" id="header_'.$z.'">
												<div class="fr_date tip_tip_default link_admin link_calendar" title="Fecha de emisión de la solicitud">'.$fecha_fondo.'</div>
												<div class="fr_time tip_tip_default link_admin link_clock" title="Hora de emisión de la solicitud">'.substr($renderFund['RenderFund']['created'], 11, 5).'</div>
												<div class="fr_title">'.$this->RequestAction('/external_functions/recortar_texto_simple/'.$renderFund['RenderFund']['render_fund_title'].'/40').'</div>
												<div class="fr_username tip_tip_default link_admin link_user" title="Usuario solicitante">'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname'].'</div>';
		
										if($renderFund['RenderFund']['state_id'] == 1){
											echo '<div class="fr_status fr_pending" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
										}
				
										if($renderFund['RenderFund']['state_id'] == 2){
											if($renderFund['RenderFund']['deliver'] == 1 && $renderFund['RenderFund']['render'] == 1){
											echo '<div class="fr_status fr_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : RENDIDO">'.$renderFund['State']['state'].' (R)</div>';
											}
									
											else{
												if($renderFund['RenderFund']['deliver'] == 1){
													echo '<div class="fr_status fr_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : ENTREGADO">'.$renderFund['State']['state'].' (E)</div>';
												}
												else{
													echo '<div class="fr_status fr_approved" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
												}
											}
										}
										
										if($renderFund['RenderFund']['state_id'] == 3){
											echo '<div class="fr_status fr_rejected" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
										}
									
								echo '</div>';
								////////////////Fin del cabezera accordion///////////////////////
								
								////////////////Inicio del Contenido///////////////////////
								echo '<div class="fr_detail">
												<div class="fr_info">
													<div class="info_01">
														<table>
															<tr>
																<td><span class="link_admin link_user">Solicitante :</span></td>
																<td><strong>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname']).'</strong></td>
															</tr>
															<tr>
																<td><span class="link_admin link_used_for">Utilizado por :</span></td>
																<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['RenderFund']['used_by_name']).'</strong></td>
															</tr>
															<tr>
																<td><span class="link_admin link_management">Gerencia :</span></td>
																<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['Management']['management_name']).'</td>
															</tr>
															<tr>
																<td><span class="link_admin link_cost_center">Centro costo :</span></td>
																<td>'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['CostCenter']['cost_center_code'].' '.$renderFund['CostCenter']['cost_center_name']).'</td>
															</tr>
														</table>
													</div>
												
													<div class="info_02">
														<table>
															<tr>
																<td width="100"><strong class="link_admin link_money" title="Monto total"> Monto total</strong> :</td>
																<td width="100"><strong>'.$renderFund['Badge']['symbol'].number_format($renderFund['RenderFund']['total_price'], 0,null, '.').'</strong></td>
															</tr>';
														
											
											if($renderFund['RenderFund']['state_id'] == 1)
												$css_status = array(0 => 'link_fr_pending', 1 => 'st_pending');
												
											if($renderFund['RenderFund']['state_id'] == 2)
												$css_status = array(0 => 'link_fr_approved', 1 => 'st_approved');
												
											if($renderFund['RenderFund']['state_id'] == 3)
												$css_status = array(0 => 'link_fr_rejected', 1 => 'st_rejected');
											
												echo '<tr>
																<td><strong class="link_admin '.$css_status[0].'"> Estado</strong> :</td>
															<td><strong class="'.$css_status[1].'">'.$renderFund['State']['state'].'</strong></td>
														</tr>';	
														
														
											if(trim($renderFund['RenderFund']['fund_number']) != ''){
												echo '<tr>
															<td><span class="link_admin link_ticket">N° Fondo por rendir :</span></td>
															<td><strong>'.$renderFund['RenderFund']['fund_number'].'</strong></td>
														</tr>';
											}	
												
											
											echo '<tr>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
													</tr>';
											
											if($renderFund['RenderFund']['render_date_start'] != NULL && $renderFund['RenderFund']['render_date_end'] != NULL && $renderFund['RenderFund']['state_id'] == 2 &&  $this->RequestAction('/external_functions/getIdDataSession') == $renderFund['RenderFund']['user_id'] && $renderFund['RenderFund']['render'] == 1){
												echo '<tr>
															<td><span class="link_admin link_calendar">Fecha tope para rendir :</span></td>
															<td>'.$this->Html->tag('span', $this->RequestAction('/external_functions/setDate/'.$renderFund['RenderFund']['render_date_end']), array('class'  => 'link_admin link_ok')).'</td>
														</tr>';
											}
											else
											{
												if($renderFund['RenderFund']['render_date_start'] != NULL && $renderFund['RenderFund']['render_date_end'] != NULL && $renderFund['RenderFund']['state_id'] == 2 &&  $this->RequestAction('/external_functions/getIdDataSession') == $renderFund['RenderFund']['user_id'])
												{
												echo '<tr>
															<td><span class="link_admin link_calendar">Fecha tope para rendir :</span></td>
															<td>'.$this->RequestAction('/external_functions/setDate/'.$renderFund['RenderFund']['render_date_end']).'</td>
														</tr>';
												}
											}
											
											echo '</table>
													</div>';
											
											
										/* *******************  Botones / Acciones  ******************* */
											
										echo '<div class="info_03">
												<table>
													<tr>
														<td>'.$this->html->link('Ver detalle','/render_funds/view/'.$renderFund['RenderFund']['id'],array('class'=>'a_button', 'target' => '_blank')).'</td>
													</tr>';
											
											if($renderFund['RenderFund']['state_id'] == 3 && $renderFund['RenderFund']['user_id'] == $this->RequestAction('/external_functions/getIdDataSession')){
												echo '<tr>
													<td>'.$this->html->link('Reenviar fondo','/render_funds/resendFund/'.$renderFund['RenderFund']['id'],array('class'=>'a_button', 'target' => '_blank')).'</td>
												</tr>';
											}
											
											echo '</table>
												</div>';
										
										
										echo '</div>';
										echo "</div>";
			
									$z++;
								
								}
								echo "</div>";
							}
						}
						else
							echo $this->Html->tag('span', 'No existen Fondos', array('class' => 'link_admin link_render_fund'));
					?>
				</div>
			</div>
			<br>
			<div class="ViewTitleOptions">
					<?php echo $this->Html->tag('h3', 'Fondos en total: '.$data['Details']['total_funds'], array('class' => 'link_admin link_render_fund'));?>	
			</div>

			<div class="ViewTitleOptions">
				<br /><br />
				<?php
					
					echo $this->html->link('Descargar en PDF','/render_funds/quantizer_expenses_management_report/'.$forDownload['RenderFund']['cost_center'].'/'.$forDownload['RenderFund']['management'].'/'.$forDownload['RenderFund']['from_date'].'/'.$forDownload['RenderFund']['to_date'], 
					array('class' => 'tip_tip_default link_admin link_pdf', 'title' => 'Descargar en version PDF'));
					
					echo "<br /><br />";
					
					echo $this->html->link('Realizar otra busqueda','/render_funds/quantizerExpenses/',
					array('class' => 'tip_tip_default link_admin link_zoom','title' => 'Realizar otra busqueda con nuevos parametros.'));
					
					echo"<br><br>";
					
					echo $this->html->link('Volver al menu','/render_funds/statsMenu',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al menu de estadisticas e informes'));
				?>
			</div>
	<?php
		}
		else
		{
			echo $this->Html->para(null, $this->Html->tag('strong', 'No existen fondos para esta busqueda.', array('class' => 'link_admin link_circuit_rejected')), array('align' => 'center'));
		?>
			<div class="ViewTitleOptions">
				<br /><br />
				<?php
					echo $this->html->link('Realizar otra busqueda','/render_funds/quantizerExpenses/',
					array('class' => 'tip_tip_default link_admin link_zoom','title' => 'Realizar otra busqueda con nuevos parametros.'));
					
					echo"<br><br>";
					
					echo $this->html->link('Volver al menu','/render_funds/statsMenu',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al menu de estadisticas e informes'));
				?>
			</div>
	<?php
		}	 
	}
	?>