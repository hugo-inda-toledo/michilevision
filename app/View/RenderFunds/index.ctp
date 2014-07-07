<h3><?php echo $this->html->link('Fondos por rendir','/render_funds/mainMenu/'); ?> &gt; Listado de solicitudes</h3>
<?php echo $this->Session->flash();?>

<script type="text/javascript">
$(function() {
	$( "div#accordion" ).accordion({
		header: 'div.header',
		active: false,
		collapsible: true
		}
	);
});
</script>

<?php
/* ...... Opciones para filtrar / Ordenar listados ......... */ 

$sort_asc = $this->Html->link('Ascendente', '/render_funds/index/[PARAM]', array('class' => 'link_admin link_sort_asc'));
$sort_desc = $this->Html->link('Descendente', '/render_funds/index/[PARAM]', array('class' => 'link_admin link_sort_desc'));


echo '<div id="filters">
		<h2 class="flt_new">'.
					//$this->html->link('Crear nueva solicitud','/render_funds/add/',array('class' => 'link_admin link_add'))
					$this->Html->link('Nueva Solicitud', '/render_funds/add', array('class' => 'link_admin link_add', 'title'=>'Nueva solicitud de fondo','rel' => 'shadowbox;width=2200;height=1000'))
					.'</h2>
		<h2 class="flt_filter">'.$this->Html->tag('span', 'Mostrar :', array('class' => 'link_admin link_zoom')).'</h2>
		<div class="flt_filterGroup_01">
			<ul>
				<li>'.$this->Html->link('Todos los fondos','/render_funds/', array('title' =>'Muestra todos los fondos por rendir', 'class' => 'tip_tip_default')).'</li>
				<li>'.$this->Html->link('Fondos por aprobar','/render_funds/index/toApprove', array('title' =>'Muestra solamente los fondos que esperan firmas', 'class' => 'tip_tip_default')).'</li>
				<li>'.$this->Html->link('Fondos aprobados','/render_funds/index/approve', array('title' =>'Muestra solamente los fondos que ya han sido aprobados', 'class' => 'tip_tip_default')).'</li>
				<li>'.$this->Html->link('Fondos rechazados','/render_funds/index/decline', array('title' =>'Muestra solamente los fondos que han sido rechazados', 'class' => 'tip_tip_default')).'</li>
			</ul>
		</div>
		<h2 class="flt_sort">'.$this->Html->tag('span', 'Ordenar por :', array('class' => 'link_admin link_sort')).'</h2>
		<div class="flt_filterGroup_02">
			<ul>
				<li>'.$this->Html->link('Fecha','#',array('title' => str_replace('[PARAM]','date/asc',$sort_asc).'<br />'.str_replace('[PARAM]','date/desc',$sort_desc),'class' => 'tip_tip_click')).'</li>
				<li>'.$this->Html->link('Solicitante','#',array('title' => str_replace('[PARAM]','request_client/asc',$sort_asc).'<br />'.str_replace('[PARAM]','request_client/desc',$sort_desc),'class' => 'tip_tip_click')).'</li>
				<li>'.$this->Html->link('Gerencia','#',array('title' => str_replace('[PARAM]','management/asc',$sort_asc).'<br />'.str_replace('[PARAM]','management/desc',$sort_desc),'class' => 'tip_tip_click')).'</li>
			</ul>
			<ul>
				<li>'.$this->Html->link('Número de fondo','#',array('title' => str_replace('[PARAM]','fund_number/asc',$sort_asc).'<br />'.str_replace('[PARAM]','fund_number/desc',$sort_desc),'class' => 'tip_tip_click')).'</li>
				<li>'.$this->Html->link('Centro de costo (Código)','#',array('title' => str_replace('[PARAM]','cost_center_code/asc',$sort_asc).'<br />'.str_replace('[PARAM]','cost_center_code/desc',$sort_desc),'class' => 'tip_tip_click')).'</li>
				<li>'.$this->Html->link('Centro de costo (Nombre)','#',array('title' => str_replace('[PARAM]','cost_center_name/asc',$sort_asc).'<br />'.str_replace('[PARAM]','cost_center_name/desc',$sort_desc),'class' => 'tip_tip_click')).'</li>
			</ul>
		</div>
	</div>';

/* .............. Fin Opciones .............. */
?>



<br />
<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% solicitudes de un total de %count% registros).'));?>
</div>



<table class="reqlist-head">
	<tr>
		<th width="20">&nbsp;</th>
		<th width="110"><?php echo $this->paginator->sort('RenderFund.created', 'Fecha', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por fecha'));?></th>
		<th width="90">Hora</th>
		<th width="320">Encabezado del fondo</th>
		<th width="230">Nombre solicitante</th>
		<th width="130">Status</th>
	</tr>		
</table>







<?php

/* ...... Listado fondos por rendir ......... */

$z = 0;
$showCallbackMessage = false;


echo '

<script type="text/javascript">
$(document).ready(alert_on);

function alert_on(){
	$(\'.youMustSign\').fadeIn(200).delay(100).fadeOut(200, alert_on);
}

function alert_off(){
	$(\'.youMustSign\').stop(true);
	$(\'.youMustSign\').css({\'display\':\'block\',\'opacity\':1});
}
</script>




<div id="accordion">';


foreach($data as $renderFund)
{
	
	$showCallbackMessage = false;
	$fecha_fondo = substr($renderFund['RenderFund']['created'], 8, 2).'-'.substr($renderFund['RenderFund']['created'], 5, 2) .'-'.substr($renderFund['RenderFund']['created'], 0, 4);
	
	
		
	/* ...... Cabecera para cada item del acordeon ......... */ 
	
	echo '<div class="header" id="header_'.$z.'">
		<div class="fr_date tip_tip_default link_admin link_calendar" title="Fecha de emisión de la solicitud">'.$fecha_fondo.'</div>
		<div class="fr_time tip_tip_default link_admin link_clock" title="Hora de emisión de la solicitud">'.substr($renderFund['RenderFund']['created'], 11, 5).'</div>
		<div class="fr_title">'.$this->RequestAction('/external_functions/recortar_texto_simple/'.$renderFund['RenderFund']['render_fund_title'].'/40').'</div>
		<div class="fr_username link_admin link_user">'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname'].'</div>';
	
		if($renderFund['RenderFund']['state_id'] == 1){
			echo '<div class="acc_status acc_pending" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
		}
		
		if($renderFund['RenderFund']['state_id'] == 2){
			if($renderFund['RenderFund']['deliver'] == 1 && $renderFund['RenderFund']['render'] == 1){
			echo '<div class="acc_status acc_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : RENDIDO">'.$renderFund['State']['state'].' (R)</div>';
			}
	
			else{
				if($renderFund['RenderFund']['deliver'] == 1){
					echo '<div class="acc_status acc_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : ENTREGADO">'.$renderFund['State']['state'].' (E)</div>';
				}
				else{
					echo '<div class="acc_status acc_approved" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
				}
			}
		}
		
		if($renderFund['RenderFund']['state_id'] == 3){
			echo '<div class="acc_status acc_rejected" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
		}
	
	echo '</div>';
	
	
	
	
	
	/* .............. Información acerca de éste ítem  .............. */ 
	
	echo '<div class="acc_detail">
			<div class="fr_info">
				<div class="info_01">
					<table>
						<tr>
							<td><span class="link_admin link_dni">ID:</span></td>
							<td><strong>'.$renderFund['RenderFund']['id'].'</strong></td>
						</tr>
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
							<td width="100"><strong class="link_admin link_money">Monto total :</strong></td>
							<td width="100"><strong>'.$renderFund['Badge']['symbol'].number_format($renderFund['RenderFund']['total_price'], 0,null, '.').'</strong></td>
						</tr>';
						
			
			if($renderFund['RenderFund']['state_id'] == 1)
				$css_status = array(0 => 'link_fr_pending', 1 => 'st_pending');
				
			if($renderFund['RenderFund']['state_id'] == 2)
				$css_status = array(0 => 'link_fr_approved', 1 => 'st_approved');
				
			if($renderFund['RenderFund']['state_id'] == 3)
				$css_status = array(0 => 'link_fr_rejected', 1 => 'st_rejected');
			
				echo '<tr>
						<td><span class="link_admin '.$css_status[0].'"> Estado :</span></td>
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
			
			
			
			
			/* ************* Mensaje de Finanzas... ************* */
			
			if($this->RequestAction('/external_functions/getIdDataSession') == $this->RequestAction('/attribute_tables/validateTreasurer') && $renderFund['RenderFund']['state_id'] == 2 && $renderFund['RenderFund']['comments_finance'] != ''){
				echo '<tr>
						<td><span class="link_admin link_chat">Mensaje de Finanzas :</span></td>
						<td>'.$renderFund['RenderFund']['comments_finance'].'</td>
					</tr>';
				
				$showCallbackMessage = true;
			}
			else{
				$showCallbackMessage = false;
			}
			
			
			
			
			/* ************* Mensaje de Tesoreria... ************* */
			
			if($this->RequestAction('/external_functions/getIdDataSession') == $renderFund['RenderFund']['user_id'] && $renderFund['RenderFund']['state_id'] == 2 && $renderFund['RenderFund']['comments'] != ''){
				echo '<tr>
						<td><span class="link_admin link_chat">Mensaje de Tesorería :</span></td>
						<td>'.$renderFund['RenderFund']['comments'].'</td>
					</tr>';
			}			
			
			
			
			if($renderFund['RenderFund']['render_date_start'] == NULL && $renderFund['RenderFund']['render_date_end'] == NULL && $this->RequestAction('/external_functions/getIdDataSession') == $this->RequestAction('/attribute_tables/validateTreasurer') && $renderFund['RenderFund']['state_id'] == 2 && $renderFund['RenderFund']['comments_finance'] != '' && $renderFund['RenderFund']['comments'] != '')
			{
					
				echo $this->Html->script('functions_admin');
				
				echo '<tr>
					<td colspan="2">'.
					$this->form->input('render_date_end', array('label' => 'Selecciona fecha tope para rendir', 'id' => 'datepickerUsuario', 'readOnly' => 'readOnly', 'type' => 'text')).'<br>'.
					$this->form->button('Guardar Hora', array('type' => 'button','class'=>'link_admin a_button', 'onclick' => 'javascript:validateEndDate('.$renderFund['RenderFund']['id'].');'))
					.'</td>
				</tr>';
			}
			
			else
			{
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
			}
			
			
			
			if($renderFund['RenderFund']['render_date_start'] != NULL && $renderFund['RenderFund']['render_date_end'] != NULL && $this->RequestAction('/external_functions/getIdDataSession') == $this->RequestAction('/attribute_tables/validateTreasurer') && $renderFund['RenderFund']['state_id'] == 2 && $renderFund['RenderFund']['comments_finance'] != '' && $renderFund['RenderFund']['comments'] != '' && $renderFund['RenderFund']['deliver'] == 1)
			{
				echo '<tr>
					<td><span class="link_admin link_calendar">Carga la factura del fondo para rendir.</span></td>
					<td>';
					
				echo $this->form->create('RenderFund', array("enctype" =>"multipart/form-data"));
				echo $this->form->file('render_fund_data', array ('label' => 'Selecciona las boletas a subir'));
				echo $this->form->input('id', array ('type' => 'hidden', 'default' => $renderFund['RenderFund']['id']));
				echo $this->form->end('Subir archivos', array('class' => 'a_button'));	
					
				echo '</td>
				</tr>';
			}
			
			echo '</table>
				</div>';
			
			
		/* *******************  Botones / Acciones  ******************* */
			
		echo '<div class="info_03">
				<table>
					<tr>
						<td>'.
									$this->Html->link('Ver detalle', '/render_funds/view/'.$renderFund['RenderFund']['id'], array('class' => 'a_button', 'title' => $renderFund['RenderFund']['render_fund_title'], 'rel' => 'shadowbox;width=2200;height=1000'))
						.'</td>
					</tr>';
			
			if($renderFund['RenderFund']['state_id'] == 3 && $renderFund['RenderFund']['user_id'] == $this->RequestAction('/external_functions/getIdDataSession')){
				echo '<tr>
					<td>'.$this->html->link('Reenviar fondo','/render_funds/resendFund/'.$renderFund['RenderFund']['id'],array('class'=>'a_button')).'</td>
				</tr>';
			}
			
			if($renderFund['RenderFund']['state_id'] == 2 && $this->RequestAction('/external_functions/getFinanceManagementId') == $this->RequestAction('/external_functions/getIdDataSession') && $renderFund['RenderFund']['comments_finance'] == ''){
				echo '<tr>
					<td>'.$this->html->link('Obs. Tesoreria','#',array('class'=>'link_admin a_button tip_tip_click','title' => $this->RequestAction('/render_funds/financeCommentForm/'.$renderFund['RenderFund']['id']))).'</td>
				</tr>';
			}

			if($renderFund['RenderFund']['state_id'] == 2 && $this->RequestAction('/attribute_tables/validateTreasurer') == $this->RequestAction('/external_functions/getIdDataSession') && $renderFund['RenderFund']['comments'] == '' && $renderFund['RenderFund']['comments_finance'] != ''){
				echo '<tr>
					<td>'.$this->html->link('Obs. Solicitante','#',array('class'=>'link_admin a_button tip_tip_click','title' => $this->RequestAction('/render_funds/CommentForm/'.$renderFund['RenderFund']['id']))).'</td>
				</tr>';
			}
			
			echo '</table>
				</div>';
		
		
		echo '</div>
		
					
			<div class="reason">
				<strong class="link_admin link_reason">Motivo del gasto : </strong> 
				<div>'.$renderFund['RenderFund']['reason'].'</div>
			</div>';
		
				
		
		
		
				
		
		
		
		
		/* .............. Firmas .............. */
		
		echo '<div class="firmas">
				
				<div class="sign">
					<div class="approval_link">
						<span class="link_admin link_circuit_approved st_approved">Solicitante</span>
					</div>
						
					<strong>Solicitante</strong><br />
					'.$this->RequestAction('/external_functions/formatNames/'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname']).'
					<p class="date-sign">'.$fecha_fondo.' '.substr($renderFund['RenderFund']['created'], 11, 8).'</p>
				</div>';
		
		
		$cont = 0;
		$stopSignCircuit = 0;
		
		
		
		
			for($x=0; $x < count($renderFund['Sign']); $x++) // Esperando firmas...
			{	
				if($renderFund['Sign'][$x]['state_id'] == 1){
					$cont++;
					
					if($this->RequestAction('/external_functions/getIdDataSession') == $renderFund['Sign'][$x]['user_id'] && $cont == 1)
					{
						if($stopSignCircuit == 0) //Si la firma anterior no fue rechazada, continua 
						{
							if($renderFund['Sign'][$x]['replacement_sign'] == 1) //Firma de reemplazante
							{
								echo '<div class="sign" style="background-color:#FFFF99;">
											<div class="approval_action">
												'.$this->html->link('Acciones','#',array('class'=>'link_admin link_actions tip_tip_click','title' => $this->RequestAction('/signs/formSign/'.$renderFund['Sign'][$x]['id']))).'
											</div>	
											<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$renderFund['Sign'][$x]['position']).'</strong><br />
											'.@$this->RequestAction('/external_functions/formatNames/'.$renderFund['Sign'][$x]['signer_name']).'
											<p>(Reemplazante)</p>
											<p class="link_admin link_alert youMustSign">Debes firmar!</p>
										</div>';
							}
							else
							{
								echo '<div class="sign" style="background-color:#FFFF99;">
											<div class="approval_action">
												'.$this->html->link('Acciones','#',array('class'=>'link_admin link_actions tip_tip_click','title' => $this->RequestAction('/signs/formSign/'.$renderFund['Sign'][$x]['id']))).'
											</div>	
											<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$renderFund['Sign'][$x]['position']).'</strong><br />
											'.@$this->RequestAction('/external_functions/formatNames/'.$renderFund['Sign'][$x]['signer_name']).'
											<p class="link_admin link_alert youMustSign">Debes firmar!</p>
										</div>';
							}
						}
						else // Si no, se bloquean todas las demas firmas
						{
							echo '<div class="sign">
									<div class="approval_link">
										'.$this->Html->tag('span', 'Sin acciones', array('class' => 'link_admin link_actions tip_tip_default pointer','title' => 'Sin acciones')).'
									</div>	
									<strong>Sin Acciones</strong><br />
									'.@$this->RequestAction('/external_functions/formatNames/'.$renderFund['Sign'][$x]['signer_name']).'
									<p class="date-sign">'.$renderFund['Sign'][$x]['sign_type'].'</p>
								</div>';
						}
					}
					else
					{
						if($renderFund['Sign'][$x]['replacement_sign'] == 1) //Firma de reemplazante
						{
							echo '<div class="sign" style="opacity:0.4;">
									<div class="approval_link">
										'.$this->Html->tag('span', 'En espera', array('class' => 'link_admin link_actions')).'
									</div>	
									<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$renderFund['Sign'][$x]['position']).'</strong><br />
									'.@$this->RequestAction('/external_functions/formatNames/'.$renderFund['Sign'][$x]['signer_name']).'
									<p class="date-sign">Reemplazante</p>
									<p class="date-sign">'.$renderFund['Sign'][$x]['sign_type'].'</p>
								</div>';
						}
						else
						{
							echo '<div class="sign" style="opacity:0.4;">
									
									<div class="approval_link">
										'.$this->Html->tag('span', 'En espera', array('class' => 'link_admin link_actions')).'
									</div>	
									<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$renderFund['Sign'][$x]['position']).'</strong><br />
									'.@$this->RequestAction('/external_functions/formatNames/'.$renderFund['Sign'][$x]['signer_name']).'
									<p class="date-sign">'.$renderFund['Sign'][$x]['sign_type'].'</p>
								</div>';
						}
					}
				}
				
				$day = substr($renderFund['Sign'][$x]['modified'] ,8, 2);
				$month = substr($renderFund['Sign'][$x]['modified'] ,5, 2);
				$year = substr($renderFund['Sign'][$x]['modified'] ,0, 4);
				
				$signDate = $day."-".$month."-".$year;
				$signTime = substr($renderFund['Sign'][$x]['modified'], 10, 9);


				
				if($renderFund['Sign'][$x]['state_id'] == 2)  //Aprobado
				{
				
					if($renderFund['Sign'][$x]['replacement_sign'] == 1) //Firma de reemplazante
					{
						echo '<div class="sign">
							<div class="approval_link">
								<span class="link_admin link_circuit_approved st_approved">Aprobado</span>
							</div>	
							<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$renderFund['Sign'][$x]['position']).'</strong><br />
							'.@$this->RequestAction('/external_functions/formatNames/'.$renderFund['Sign'][$x]['signer_name']).'<br />
							(Reemplazo)
							<p class="date-sign">'.$signDate.' '.$signTime.'</p>
							<p class="date-sign">'.$renderFund['Sign'][$x]['sign_type'].'</p>
							<span class="tip_tip_default link_admin link_comments" title="'.$renderFund['Sign'][$x]['comments'].'">Comentarios</span>
						</div>';
					}
					else
					{
						echo '<div class="sign">
							<div class="approval_link">
								<span class="link_admin link_circuit_approved st_approved">Aprobado</span>
							</div>	
							<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$renderFund['Sign'][$x]['position']).'</strong><br />
							'.@$this->RequestAction('/external_functions/formatNames/'.$renderFund['Sign'][$x]['signer_name']).'
							<p class="date-sign">'.$signDate.' '.$signTime.'</p>
							<p class="date-sign">'.$renderFund['Sign'][$x]['sign_type'].'</p>
							<span class="tip_tip_default link_admin link_comments" title="'.$renderFund['Sign'][$x]['comments'].'">Comentarios</span>
						</div>';
					}
				}



				
				if($renderFund['Sign'][$x]['state_id'] == 3)  //Rechazado
				{
					echo '<div class="sign">
						<div class="approval_link">
							<span class="link_admin link_circuit_rejected st_rejected">Rechazado</span>
						</div>	
						<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$renderFund['Sign'][$x]['position']).'</strong><br />
						'.@$this->RequestAction('/external_functions/formatNames/'.$renderFund['Sign'][$x]['signer_name']).'
						<p class="date-sign">'.$signDate.' '.$signTime.'</p>
						<p class="date-sign">'.$renderFund['Sign'][$x]['sign_type'].'</p>
						<span class="tip_tip_default link_admin link_comments" title="'.$renderFund['Sign'][$x]['comments'].'">Comentarios</span>
					</div>';
					
					$stopSignCircuit = 1;
				}
			}

		/* .............. FIN listado firmas (<div class="firmas">) .............. */ 
		
		echo '</div>';

	echo '</div>';

	$z++;
}


/* ...... FIN Listado fondos por rendir ......... */

echo '</div>';

?>
			
<br /><br />
<div class="paging">
	<?php echo $this->paginator->prev('<< Anterior ', null, null, array('class' => 'disabled_prev'));?>
	<?php echo $this->paginator->numbers(array('separator' => '')); ?>
	<?php echo $this->paginator->next(' Siguiente >>', null, null, array('class' => 'disabled_next'));?>
</div>

