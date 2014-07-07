<h3><?php echo $html->link('Fondos por rendir','/render_funds/mainMenu/'); ?> &gt; Listado de solicitudes</h3>

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
$sort_asc = $this->Html->link('Ascendente', '/render_funds/index/[PARAM]', array('class' => 'link_admin link_sort_asc'));
$sort_desc = $this->Html->link('Descendente', '/render_funds/index/[PARAM]', array('class' => 'link_admin link_sort_desc'));





/* ...... Opciones para filtrar / Ordenar listados ......... */ 

echo '<div id="filters_holder">
		
		<h2 class="flt_new">'.$html->link('Crear nueva solicitud','/render_funds/add/',array('class' => 'link_admin link_add')).'</h2>
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
	<?php echo $paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% solicitudes de un total de %count% registros).'));?>
</div>



	
<table style="width:950px;">
	<tr>
		<th width="20">&nbsp;</th>
		<th width="110"><?php echo $paginator->sort('Fecha', 'RenderFund.created', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por fecha'));?></th>
		<th width="90">Hora</th>
		<th width="320">Nombre Ref. Solicitud</th>
		<th width="230">Nombre Solicitante</th>
		<th width="130">Status</th>
	</tr>		
</table>



	

<div id="accordion">
<?php

$z = 0;
$showCallbackMessage = false;

foreach($data as $renderFund)
{
	
	if($renderFund['State']['id'] == 1) $css_status = 'st_pending';
	if($renderFund['State']['id'] == 2) $css_status = 'st_approved';
	if($renderFund['State']['id'] == 3) $css_status = 'st_rejected';
	
	$showCallbackMessage = false;
	$fecha_fondo = substr($renderFund['RenderFund']['created'], 8, 2).'-'.substr($renderFund['RenderFund']['created'], 5, 2) .'-'.substr($renderFund['RenderFund']['created'], 0, 4);

	
	
	
	/* ...... Cabecera para cada item del acordeon ......... */ 
	
	echo '<div class="header" id="header_'.$z.'">
		<div class="fr_date tip_tip_default link_admin link_calendar" title="Fecha de emisión de la solicitud">'.$fecha_fondo.'</div>
		<div class="fr_time tip_tip_default link_admin link_clock" title="Hora de emisión de la solicitud">'.substr($renderFund['RenderFund']['created'], 11, 5).'</div>
		<div class="fr_title">'.$this->RequestAction('/external_functions/recortar_texto_simple/'.$renderFund['RenderFund']['render_fund_title'].'/40').'</div>
		<div class="fr_username tip_tip_default link_admin link_user" title="Usuario solicitante">'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname'].'</div>';
	
	if($renderFund['RenderFund']['state_id'] == 1)
		echo '<div class="fr_status fr_pending" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
	
	if($renderFund['RenderFund']['state_id'] == 2){
			
		if($renderFund['RenderFund']['deliver'] == 1 && $renderFund['RenderFund']['render'] == 1){
		echo '<div class="fr_status fr_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : RENDIDO">'.$renderFund['State']['state'].' (R)</div>';
		}
		else{
			if($renderFund['RenderFund']['deliver'] == 1)
				echo '<div class="fr_status fr_approved tip_tip_default" id="status_'.$z.'" title="Fondo N° '.$renderFund['RenderFund']['fund_number'].' | Status : ENTREGADO">'.$renderFund['State']['state'].' (E)</div>';
				
			else
				echo '<div class="fr_status fr_approved" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
		}
	}	
	
	if($renderFund['RenderFund']['state_id'] == 3)
		echo '<div class="fr_status fr_rejected" id="status_'.$z.'">'.$renderFund['State']['state'].'</div>';
	
	
	echo '</div>';
	
		
	/* .............. Fin Cabecera .............. */ 
	
	
	
	
		
	
	
	
	echo '<div>
				<div class="fr_info">
					
					<div class="fr_info_left">
						<p><strong class="link_admin link_money" title="Monto total"> Monto total : '.$renderFund['Badge']['symbol'].number_format($renderFund['RenderFund']['total_price'], 0,null, '.').'</strong></p>
						<p><span class="link_admin link_reason" title="Motivo del gasto">Motivo : '.$this->RequestAction('/external_functions/recortar_texto/'.$renderFund['RenderFund']['reason'].'/180').'</p>
					</div>
					
					<div class="fr_info_right">
						<p><span class="link_admin link_user" title="Solicitante"> Solicitante : <b>'.$renderFund['User']['name'].' '.$renderFund['User']['first_lastname'].'</b></span><br />
						<span class="link_admin link_used_for" title="Usado por"> Para : '.$renderFund['RenderFund']['used_by_name'].'<span/><br />
						<span class="link_admin link_management" title="Gerencia"> Gerencia : '.$renderFund['Management']['management_name'].'</span><br />
						<span class="link_admin link_cost_center" title="Centro de costo"> C. Costo : '.$renderFund['CostCenter']['cost_center_code'].' '.ucwords(strtolower($renderFund['CostCenter']['cost_center_name'])).'</span><br />';
						
						if($renderFund['RenderFund']['state_id'] == 1)
							echo '<span class="link_admin link_status" title="Estado"> Estado : <strong class="'.$css_status.'">'.$renderFund['State']['state'].'</strong></span>';
						
						if($renderFund['RenderFund']['state_id'] == 2)
							echo '<span class="link_admin link_status link_circuit_approved" title="Estado"> Estado : <strong class="'.$css_status.'">'.$renderFund['State']['state'].'</strong></span>';
						
						if($renderFund['RenderFund']['state_id'] == 3)
							echo '<span class="link_admin link_status link_circuit_rejected" title="Estado"> Estado : <strong class="'.$css_status.'">'.$renderFund['State']['state'].'</strong></span>';
						
						if($renderFund['RenderFund']['fund_number'] != '')
							echo '<br><span class="link_admin link_ticket" title="Numero de fondo"> Numero de fondo : <strong>'.$renderFund['RenderFund']['fund_number'].'</strong></span>';
						
						if($this->RequestAction('/external_functions/getIdDataSession') == $this->RequestAction('/attribute_tables/validateTreasurer') && $renderFund['RenderFund']['state_id'] == 2 && $renderFund['RenderFund']['comments_finance'] != '')	
						{
							echo '<br><span class="link_admin link_reason" title="Mensaje para tesoreria"> <strong>Mensaje del Sub-Gerente de Finanzas : </strong><br><strong class="st_rejected">'.$renderFund['RenderFund']['comments_finance'].'</strong></span>';
							$showCallbackMessage = true;
						}
						else
							$showCallbackMessage = false;
						
						
						
						
						if($this->RequestAction('/external_functions/getIdDataSession') == $renderFund['RenderFund']['user_id'] && $renderFund['RenderFund']['state_id'] == 2 && $renderFund['RenderFund']['comments'] != '')	
							echo '<br><span class="link_admin link_reason" title="Hey, tienes un mensaje desde tesoreria!"> Mensaje de tesoreria : <strong>'.$renderFund['RenderFund']['comments'].'</strong></span>';
						
						if($renderFund['RenderFund']['render_date_start'] == '0000-00-00' && $renderFund['RenderFund']['render_date_end'] == '0000-00-00' && $this->RequestAction('/external_functions/getIdDataSession') == $this->RequestAction('/attribute_tables/validateTreasurer') && $renderFund['RenderFund']['state_id'] == 2 && $renderFund['RenderFund']['comments_finance'] != '' && $renderFund['RenderFund']['comments'] != '')
						{
							echo $this->Html->script('functions_admin');		
							echo '<br>'.$form->input('render_date_end', array('label' => 'Selecciona fecha tope para rendir', 'id' => 'datepickerUsuario', 'readOnly' => 'readOnly')).'</span>';
							echo '<br>'.$form->button('Guardar Hora', array('type' => 'button','class'=>'link_admin a_button', 'onclick' => 'javascript:validateEndDate('.$renderFund['RenderFund']['id'].');'));
						}
						else
						{
							if($renderFund['RenderFund']['render_date_start'] != '0000-00-00' && $renderFund['RenderFund']['render_date_end'] != '0000-00-00' && $renderFund['RenderFund']['state_id'] == 2 &&  $this->RequestAction('/external_functions/getIdDataSession') == $renderFund['RenderFund']['user_id'])
								echo '<br><span class="link_admin link_calendar" title="Fecha tope para rendir"> Fecha de tope para rendir: <strong class="st_rejected">'.$renderFund['RenderFund']['render_date_end'].'</strong></span>';
						}
					
						if($renderFund['RenderFund']['render_date_start'] != '0000-00-00' && $renderFund['RenderFund']['render_date_end'] != '0000-00-00' && $this->RequestAction('/external_functions/getIdDataSession') == $this->RequestAction('/attribute_tables/validateTreasurer') && $renderFund['RenderFund']['state_id'] == 2 && $renderFund['RenderFund']['comments_finance'] != '' && $renderFund['RenderFund']['comments'] != '')
						{
							echo '<br><span class="link_admin link_calendar" title="Fecha tope para rendir"> Fecha de tope para rendir: <strong class="st_rejected">'.$renderFund['RenderFund']['render_date_end'].'</strong></span>';
							echo $form->create('RenderFund', array("enctype" =>"multipart/form-data"));
							echo $form->file('render_fund_data', array ('label' => 'Selecciona las boletas a subir'));
							echo $form->input('id', array ('type' => 'hidden', 'default' => $renderFund['RenderFund']['id']));
							echo $form->end('Subir archivos', array('class' => 'a_button'));
							echo "</p>";
						}
						else
							echo "</p>";
						
						
					echo '</div>';
					
					if(count($renderFund['RenderFundFile']) != 0)
					{
						echo '<div class="fr_info_left">';
							for($x=0; $x < count($renderFund['RenderFundFile']); $x++);
							{
								
							}
						echo '</div>';
					}
					
					echo '<div class="fr_ver_detalle">
						'.$html->link('Ver detalle','/render_funds/view/'.$renderFund['RenderFund']['id'],array('class'=>'a_button'));
						if($renderFund['RenderFund']['state_id'] == 3 && $renderFund['RenderFund']['user_id'] == $this->RequestAction('/external_functions/getIdDataSession'))
						{
							echo '<br>'.$html->link('Reenviar fondo','/render_funds/resendFund/'.$renderFund['RenderFund']['id'],array('class'=>'a_button'));
						}
						if($renderFund['RenderFund']['state_id'] == 2 && $this->RequestAction('/external_functions/getFinanceManagementId') == $this->RequestAction('/external_functions/getIdDataSession') && $renderFund['RenderFund']['comments_finance'] == '')
						{
							echo '<br>'.$html->link('Obs. Tesoreria','#',array('class'=>'link_admin a_button tip_tip_click','title' => $this->RequestAction('/render_funds/financeCommentForm/'.$renderFund['RenderFund']['id'])));
						}
						if($showCallbackMessage == true && $renderFund['RenderFund']['comments'] == '' && $renderFund['RenderFund']['comments_finance'] != '')
						{
							echo '<br>'.$html->link('Obs. Solicitante','#',array('class'=>'link_admin a_button tip_tip_click','title' => $this->RequestAction('/render_funds/CommentForm/'.$renderFund['RenderFund']['id'])));
						}
					echo '</div>'.
				'</div>';?>
			
			
			

			
			<div class='fr_firmas'>
			<?php
			//Contenido del acordion
				echo '<div class="sign">
					<div class="approval_link">
						<span class="link_admin link_circuit_approved st_approved">Solicitante</span>
					</div>	
					<strong>Solicitante</strong><br />'.
				 	$renderFund['User']['name'].' '.$renderFund['User']['first_lastname']
					.'<p class="fr_date">'.$renderFund['RenderFund']['created'].'</p>
				</div>';
			
			$cont = 0;
			$stopSignCircuit = 0;
			
			for($x=0; $x < count($renderFund['Sign']); $x++)
			{
				if($renderFund['Sign'][$x]['state_id'] == 1)  //Esperando firmas
				{
					$cont++;
					
					if($this->RequestAction('/external_functions/getIdDataSession') == $renderFund['Sign'][$x]['user_id'] && $cont == 1)
					{
						if($stopSignCircuit == 0) //Si la firma anterior no fue rechazada, continua 
						{
							if($renderFund['Sign'][$x]['replacement_sign'] == 1) //Firma de reemplazante
							{
								echo '<div class="sign">
											<div class="approval_link">
												'.$html->link('Acciones','#',array('class'=>'link_admin link_actions tip_tip_click','title' => $this->RequestAction('/signs/formSign/'.$renderFund['Sign'][$x]['id']))).'
											</div>	
											<strong class="link_admin link_alert">Debes firmar!</strong><br />'.
											$renderFund['Sign'][$x]['signer_name']
											.'<p>Reemplazante</p>
										</div>';
								
								echo '<script type="text/javascript">$("#status_'.$z.'").append("<span class=\'link_admin link_alert\'><strong>Debes firmar!</strong></span>");</script>';
							}
							else
							{
								echo '<div class="sign">
											<div class="approval_link">
												'.$html->link('Acciones','#',array('class'=>'link_admin link_actions tip_tip_click','title' => $this->RequestAction('/signs/formSign/'.$renderFund['Sign'][$x]['id']))).'
											</div>	
											<strong class="link_admin link_alert">Debes firmar!</strong><br />'.
											$renderFund['Sign'][$x]['signer_name']
											.'<p class="fr_date"></p>
										</div>';
								
								echo '<script type="text/javascript">$("#status_'.$z.'").append("<span class=\'link_admin link_alert\'><strong>Debes firmar!</strong></span>");</script>';
							}
						}
						else // Si no, se bloquean todas las demas firmas
						{
							echo '<div class="sign">
											<div class="approval_link">
												'.$html->link('Sin acciones','#',array('class'=>'link_admin link_actions tip_tip_default','title' => 'Sin acciones')).'
											</div>	
											<strong>Sin Acciones</strong><br />'.
											$renderFund['Sign'][$x]['signer_name']
											.'<p class="fr_date"></p>
										</div>';
						}
					}
					else
					{
						if($renderFund['Sign'][$x]['replacement_sign'] == 1) //Firma de reemplazante
						{
							echo '<div class="sign">
											<div class="approval_link">
												'.$html->link('En espera','#',array('class'=>'link_admin link_actions tip_tip_default','title' => 'En espera')).'
											</div>	
											<strong>'.strtoupper($renderFund['Sign'][$x]['position']).'</strong><br />'.
											$renderFund['Sign'][$x]['signer_name']
											.'<p class="fr_date">Reemplazante</p>
										</div>';
						}
						else
						{
							echo '<div class="sign">
											<div class="approval_link">
												'.$html->link('En espera','#',array('class'=>'link_admin link_actions tip_tip_default','title' => 'En espera')).'
											</div>	
											<strong>'.strtolower($renderFund['Sign'][$x]['position']).'</strong><br />'.
											$renderFund['Sign'][$x]['signer_name']
											.'<p class="fr_date"></p>
										</div>';
						}
					}
				}
				
				if($renderFund['Sign'][$x]['state_id'] == 2)  //Aprobado
				{
				
					if($renderFund['Sign'][$x]['replacement_sign'] == 1) //Firma de reemplazante
					{
						echo '<div class="sign">
						<div class="approval_link">
							<span class="tip_tip_default link_admin link_circuit_approved st_approved" title="'.$renderFund['Sign'][$x]['comments'].'">Aprobado</span>
						</div>	
						<strong>'.strtolower($renderFund['Sign'][$x]['position']).'</strong><br />'.
						$renderFund['Sign'][$x]['signer_name'].'<br>(Reemplazo)'
						.'<p class="fr_date">'.$renderFund['RenderFund']['created'].'<br><strong>'.$renderFund['Sign'][$x]['comments'].'</strong></p>
						</div>';
						
						if($renderFund['RenderFund']['state_id'] == 2)
							echo '<script type="text/javascript">$("#header_'.$z.'").css("background-color", "#088A68");</script>';
					}
					else
					{
						echo '<div class="sign">
						<div class="approval_link">
							<span class="tip_tip_default link_admin link_circuit_approved st_approved" title="'.$renderFund['Sign'][$x]['comments'].'">Aprobado</span>
						</div>	
						<strong>'.strtolower($renderFund['Sign'][$x]['position']).'</strong><br />'.
						$renderFund['Sign'][$x]['signer_name']
						.'<p class="fr_date">'.$renderFund['RenderFund']['created'].'<br><strong>'.$renderFund['Sign'][$x]['comments'].'</strong></p>
						</div>';
						
						if($renderFund['RenderFund']['state_id'] == 2)
							echo '<script type="text/javascript">$("#header_'.$z.'").css("background-color", "#088A68");</script>';
					}
				}
				
				if($renderFund['Sign'][$x]['state_id'] == 3)  //Rechazado
				{
					echo '<div class="sign">
					<div class="approval_link">
						<span class="tip_tip_default link_admin link_circuit_rejected st_rejected" title="'.$renderFund['Sign'][$x]['comments'].'">Rechazado</span>
					</div>	
					<strong>'.strtolower($renderFund['Sign'][$x]['position']).'</strong><br />'.
				 	$renderFund['Sign'][$x]['signer_name']
					.'<p class="fr_date">'.$renderFund['RenderFund']['created'].'<br><strong>'.$renderFund['Sign'][$x]['comments'].'</strong></p>
					</div>';
					
						echo '<script type="text/javascript">$("#header_'.$z.'").css("background-color", "#C62424");</script>';
					
					$stopSignCircuit = 1;
				}
			}
			
			echo '</div>
	
		</div>';
		$z++;
}

?>
</div>


<br /><br />
<div class="paging">
	<?php echo $paginator->prev('« Anterior ', null, null, array('class' => 'disabled_prev'));?>
	<?php echo $paginator->numbers(array('separator' => '')); ?>
	<?php echo $paginator->next(' Siguiente »', null, null, array('class' => 'disabled_next'));?>
</div>



<br /><br />
<p>
	<?php echo $html->link('Volver al menú principal','/render_funds/mainMenu',array('title' => 'Volver al menú principal del sistema', 'class' => 'tip_tip_default link_admin link_back'));?>
</p>

<p>&nbsp;</p>

