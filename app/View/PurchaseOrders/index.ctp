<?php
echo $this->Html->tag('h3',$this->html->link('Ordenes de compra','/purchase_orders/').' &gt; Listado de solicitudes');
echo $this->Session->flash(); 

if($this->RequestAction('/external_functions/verifiedAccess/'.$this->RequestAction('/external_functions/getIdDataSession')."/2/generateOrderPdf") == true)
{
?>
<script>
	function generateOrder(id)
	{
		var root = "/michilevision";
		
		if($('#loading').load(root+'/purchase_orders/generateOrderPdf/' + id))
		{
			alert('Orden Generada');
			location.reload();
		}
		else
		{
			alert('Fallo la generacion de la orden de compra');
		}
		
	}
</script>

<?php
}
?>

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

$sort_asc = $this->Html->link('Ascendente', '/purchase_orders/index/[PARAM]', array('class' => 'link_admin link_sort_asc'));
$sort_desc = $this->Html->link('Descendente', '/purchase_orders/index/[PARAM]', array('class' => 'link_admin link_sort_desc'));

/*$budgeted = $this->Html->link('He cotizado yo', '/purchase_orders/add/[PARAM]', array('class' => 'tip_tip link_admin link_folder_add', 'title' => 'Deberas cargas por lo menos una cotización para enviar la solicitud de orden.'));
$unbudgeted = $this->Html->link('Quiero que me cotizen', '/purchase_orders/add/[PARAM]', array('class' => 'tip_tip_default link_admin link_folder_delete', 'title' => 'No es necesario que este cotizado, el departamento correspondiente hará este trabajo por ti.'));*/

/*echo "<pre>";
print_r($data);
echo "</pre>";*/

echo '<div id="filters">
		<h2 class="flt_new">'.$this->Html->link('Nueva Solicitud', '/purchase_orders/add', array('class' => 'link_admin link_add', 'title'=>'Nueva solicitud de orden de compra','rel' => 'shadowbox;width=2200;height=1000')).'</h2>
		<h2 class="flt_filter">'.$this->Html->tag('span', 'Mostrar :', array('class' => 'link_admin link_zoom')).'</h2>
		<div class="flt_filterGroup_01">
			<ul>
				<li>'.$this->Html->link('Todas las órdenes', '/purchase_orders/', array('title' =>'Titulo para este enlace', 'class' => 'tip_tip_default')).'</li>
				<li>'.$this->Html->link('Mis órdenes', '/purchase_orders/index/me', array('title' =>'Titulo para este enlace', 'class' => 'tip_tip_default')).'</li>
				<li>'.$this->Html->link('Con Cotizaciones','/purchase_orders/index/budgeted', array('title' =>'Titulo para este enlace', 'class' => 'tip_tip_default')).'</li>
				<li>'.$this->Html->link('Sin Cotizaciones','/purchase_orders/index/unbudgeted', array('title' =>'Titulo para este enlace', 'class' => 'tip_tip_default')).'</li>
				<li>'.$this->Html->link('Aprobadas','/purchase_orders/index/approved', array('title' =>'Titulo para este enlace', 'class' => 'tip_tip_default')).'</li>
				<li>'.$this->Html->link('Pendientes','/purchase_orders/index/waiting', array('title' =>'Titulo para este enlace', 'class' => 'tip_tip_default')).'</li>
				<li>'.$this->Html->link('Rechazadas','/purchase_orders/index/decline', array('title' =>'Titulo para este enlace', 'class' => 'tip_tip_default')).'</li>
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
				<li>'.$this->Html->link('Número de orden','#',array('title' => str_replace('[PARAM]','fund_number/asc',$sort_asc).'<br />'.str_replace('[PARAM]','fund_number/desc',$sort_desc),'class' => 'tip_tip_click')).'</li>
				<li>'.$this->Html->link('Centro de costo (Código)','#',array('title' => str_replace('[PARAM]','cost_center_code/asc',$sort_asc).'<br />'.str_replace('[PARAM]','cost_center_code/desc',$sort_desc),'class' => 'tip_tip_click')).'</li>
				<li>'.$this->Html->link('Centro de costo (Nombre)','#',array('title' => str_replace('[PARAM]','cost_center_name/asc',$sort_asc).'<br />'.str_replace('[PARAM]','cost_center_name/desc',$sort_desc),'class' => 'tip_tip_click')).'</li>
			</ul>
		</div>
	</div>';

?>

<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% solicitudes de un total de %count% registros).'));?>
</div>

<table class="reqlist-head">
	<tr>
		<th width="20">&nbsp;</th>
		<th width="110">Fecha</th>
		<th width="90">Hora</th>
		<th width="320">Numero de solicitud</th>
		<th width="230">Nombre solicitante</th>
		<th width="130">Status</th>
	</tr>		
</table>

<div id="accordion">
<?php
$z = 0;

	foreach($data as $value)
	{
		$fecha_orden = substr($value['PurchaseOrder']['created'], 8, 2).'-'.substr($value['PurchaseOrder']['created'], 5, 2) .'-'.substr($value['PurchaseOrder']['created'], 0, 4);
		
		echo '<div class="header" id="header_'.$z.'">
			<div class="oc_date tip_tip_default link_admin link_calendar" title="Fecha de emisión de la solicitud">'.$fecha_orden.'</div>
			<div class="oc_time tip_tip_default link_admin link_clock" title="Hora de emisión de la solicitud">'.substr($value['PurchaseOrder']['created'], 11, 5).'</div>';
			
			if($value['PurchaseOrder']['upload_user'] == 1)
			{
				if($value['PurchaseOrder']['request_number'] != '' && $value['PurchaseOrder']['order_number'] == '')
				{
					echo '<div class="oc_reason tip_tip_default link_admin link_attach" style="margin: 0px 0px 0px 0px;" title="Orden con Cotización Adjunta">'.$value['PurchaseOrder']['request_number'].'</div>';
				}
				else
				{
					echo '<div class="oc_reason tip_tip_default link_admin link_attach" style="margin: 0px 0px 0px 0px;" title="Orden con Cotización Adjunta">'.$value['PurchaseOrder']['order_number'].'</div>';
				}
			}
			else
			{
				echo '<div class="oc_reason tip_tip_default"  title="Orden sin Cotización Adjunta">'.$value['PurchaseOrder']['request_number'].'</div>';
			}
			echo '<div class="oc_username tip_tip_default link_admin link_user" title="Usuario solicitante">'.$value['User']['name'].' '.$value['User']['first_lastname'].'</div>';
	
		if($value['PurchaseOrder']['state_id'] == 1)
		{
			echo '<div class="acc_status acc_pending" id="status_'.$z.'">'.$value['State']['state'].'</div>';
		}
	
		if($value['PurchaseOrder']['state_id'] == 2)
		{
			echo '<div class="acc_status acc_approved" id="status_'.$z.'">'.$value['State']['state'].'</div>';
		}
	
		if($value['PurchaseOrder']['state_id'] == 3)
		{
			echo '<div class="acc_status acc_rejected" id="status_'.$z.'">'.$value['State']['state'].'</div>';
		}
		
		if($value['PurchaseOrder']['state_id'] == 4)
		{
			echo '<div class="acc_status acc_invoice" id="status_'.$z.'">'.$value['State']['state'].'</div>';
		}
		
		if($value['PurchaseOrder']['state_id'] == 5)
		{
			echo '<div class="acc_status acc_print" id="status_'.$z.'">'.$value['State']['state'].'</div>';
		}
		
		if($value['PurchaseOrder']['state_id'] == 6)
		{
			echo '<div class="acc_status acc_evaluate" id="status_'.$z.'">'.$value['State']['state'].'</div>';
		}
		
		if($value['PurchaseOrder']['state_id'] == 7)
		{
			echo '<div class="acc_status acc_cancel" id="status_'.$z.'">'.$value['State']['state'].'</div>';
		}
		
		echo '</div>';
		/* .............. Información acerca de éste ítem  .............. */ 
	
	echo '<div class="acc_detail">
			<div class="fr_info">
				
				<div class="reason">
					<strong class="link_admin link_reason">Motivo de la orden : </strong> 
					<div>'.$value['PurchaseOrder']['reason'].'</div>
				</div>';
	
				
				
		echo '<div class="info_01">
					<table>
						<tr>
							<td width="200"><span class="link_admin link_dni">ID:</span></td>
							<td width="200"><strong>'.$value['PurchaseOrder']['id'].'</strong></td>
						</tr>
						<tr>
							<td width="200"><span class="link_admin link_user">Solicitante :</span></td>
							<td width="200"><strong>'.$this->RequestAction('/external_functions/formatNames/'.$value['User']['name'].' '.$value['User']['first_lastname']).'</strong></td>
						</tr>
						<tr>
							<td width="200"><span class="link_admin link_management">Gerencia :</span></td>
							<td width="200">'.$this->RequestAction('/external_functions/formatNames/'.$value['Management']['management_name']).'</td>
						</tr>
						<tr>
							<td width="200"><span class="link_admin link_cost_center">Centro costo :</span></td>
							<td width="200">'.$this->RequestAction('/external_functions/formatNames/'.$value['CostCenter']['cost_center_code'].' '.$value['CostCenter']['cost_center_name']).'</td>
						</tr>';
				if($value['PurchaseOrder']['state_id'] == 1)
					$css_status = array(0 => 'link_fr_pending', 1 => 'st_pending');
				
				if($value['PurchaseOrder']['state_id'] == 2)
					$css_status = array(0 => 'link_fr_approved', 1 => 'st_approved');
				
				if($value['PurchaseOrder']['state_id'] == 3)
					$css_status = array(0 => 'link_fr_rejected', 1 => 'st_rejected');
				
				if($value['PurchaseOrder']['state_id'] == 4)
					$css_status = array(0 => 'link_fr_pending', 1 => 'st_pending');
				
				if($value['PurchaseOrder']['state_id'] == 5)
					$css_status = array(0 => 'link_fr_pending', 1 => 'st_pending');
					
				if($value['PurchaseOrder']['state_id'] == 6)
					$css_status = array(0 => 'link_fr_pending', 1 => 'st_pending');
				
				if($value['PurchaseOrder']['state_id'] == 7)
					$css_status = array(0 => 'link_fr_cancel', 1 => 'st_pending');
	
						
				if(trim($value['PurchaseOrder']['request_number']) != '' || $value['PurchaseOrder']['request_number'] != NULL){
					echo '<tr>
						<td width="200"><span class="link_admin link_ticket">N° Solicitud :</span></td>
						<td width="200"><strong>'.$value['PurchaseOrder']['request_number'].'</strong></td>
					</tr>';
				}	
			
				if(trim($value['PurchaseOrder']['order_number']) != '' || $value['PurchaseOrder']['order_number'] != NULL){
					echo '<tr>
						<td width="200"><span class="link_admin link_ticket">N° Orden:</span></td>
						<td width="200"><strong>'.$value['PurchaseOrder']['order_number'].'</strong></td>
					</tr>';
				}	
				
				echo '</table>
				</div>
				
				<div class="info_02">
					<table>
						<tr>
							<td width="200"><span class="link_admin '.$css_status[0].'"> Estado :</span></td>
							<td width="200"><strong class="'.$css_status[1].'">'.$value['State']['state'].'</strong></td>
						</tr>';
				
				if($value['State']['id'] == 7)
				{
					echo '<tr>
							<td width="200"><span class="link_admin link_reason"> Comentario :</span></td>
							<td width="200"><strong>'.$value['PurchaseOrder']['comments'].'</strong></td>
						</tr>';
				}
				
				echo '<tr>
							<td width="200"><strong class="link_admin link_invoice">Facturar a :</strong></td>
							<td width="200">'.$value['PurchaseOrder']['invoice_to'].'</td>
						</tr>
						<tr>
							<td width="200"><strong class="link_admin link_buy_type">Tipo de compra :</strong></td>
							<td width="200">'.$value['PurchaseOrder']['purchase_type'].'</td>
						</tr>';
						
						$answer;
						
						if($value['PurchaseOrder']['only_provider'] == 1)
						{
							$answer = "Si";
						}
						else
						{
							$answer = "No";
						}
						
						$answer2;
						
						if($value['PurchaseOrder']['budgeted'] == 1)
						{
							$answer2 = "Si";
						}
						else
						{
							$answer2 = "No";
						}
						
						echo '<tr>
							<td width="200"><strong class="link_admin link_provider">Proveedor unico :</strong></td>
							<td width="200">'.$answer.'</td>
						</tr>
						<tr>
							<td width="200"><strong class="link_admin link_budgeted">Orden presupuestada :</strong></td>
							<td width="200">'.$answer2.'</td>
						</tr>

						<tr>
							<td width="200"><strong class="link_admin link_money">Monto total :</strong></td>
							<td width="200"><strong>'.$value['Badge']['symbol'].number_format($value['PurchaseOrder']['grand_net_total_price'], 0,null, '.').'</strong></td>
						</tr>';

			echo '</table>
				</div>';
			
			
		/* *******************  Botones / Acciones  ******************* */
		
		
			
		echo '<div class="info_03">
				<table>
					<tr>
						<td>';
								if($value['PurchaseOrder']['upload_user'] == 1)
								{
									if($value['PurchaseOrder']['request_number'] != '' && $value['PurchaseOrder']['order_number'] == '')
									{
										echo $this->Html->link('Ver detalle', '/purchase_orders/view/'.$value['PurchaseOrder']['id'], array('class' => 'a_button', 'title' => $value['PurchaseOrder']['request_number'], 'rel' => 'shadowbox;width=2200;height=1000'));
									}
									else
									{
										echo $this->Html->link('Ver detalle', '/purchase_orders/view/'.$value['PurchaseOrder']['id'], array('class' => 'a_button', 'title' => $value['PurchaseOrder']['order_number'], 'rel' => 'shadowbox;width=2200;height=1000'));
									}
								}
								else
								{
									echo $this->Html->link('Ver detalle', '/purchase_orders/view/'.$value['PurchaseOrder']['id'], array('class' => 'a_button', 'title' => $value['PurchaseOrder']['request_number'], 'rel' => 'shadowbox;width=2200;height=1000'));
								}
						echo '</td>
					</tr>';
			
			if($value['PurchaseOrder']['state_id'] == 3 && $value['PurchaseOrder']['user_id'] == $this->RequestAction('/external_functions/getIdDataSession')){
				echo '<tr>
					<td>'.$this->html->link('Reenviar orden','/purchase_orders/resendOrder/'.$value['PurchaseOrder']['id'],array('class'=>'a_button')).'</td>
				</tr>';
			}

			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->RequestAction('/external_functions/getIdDataSession')."/2/budgetOrder") == true && $value['PurchaseOrder']['upload_user'] == 0 && $value['PurchaseOrder']['state_id'] != 7)
			{
				echo '<tr>
							<td>'.$this->html->link('Cotizar','/purchase_orders/budgetOrder/'.$value['PurchaseOrder']['id'], array('class'=>'a_button')).'</td>
						</tr>';
			}
			
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->RequestAction('/external_functions/getIdDataSession')."/2/generatingBill") == true && $value['PurchaseOrder']['approved'] == 1 && $value['PurchaseOrder']['state_id'] != 7)
			{
				echo '<tr>
							<td>'.$this->html->link('Ingreso de Impuestos', '/purchase_orders/generatingBill/'.$value['PurchaseOrder']['id'], array('class'=>'a_button')).'</td>
						</tr>';
			}
			
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->RequestAction('/external_functions/getIdDataSession')."/2/modifiedBudgetRequest") == true && $value['PurchaseOrder']['approved'] == 1 && $value['PurchaseOrder']['state_id'] != 7)
			{
				echo '<tr>
							<td>'.$this->html->link('Solicita Modificación','#', array('class'=>'tip_tip_click a_button', 'title' => $this->RequestAction('/purchase_orders/requestModifyForm/'.$value['PurchaseOrder']['id']))).'</td>
						</tr>';
			}
			
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->RequestAction('/external_functions/getIdDataSession')."/2/modifiedBudgetRequest") == true && $value['PurchaseOrder']['approved'] == 1 && $value['PurchaseOrder']['state_id'] != 7)
			{
				if($value['ModifiedRequestOrder'] != false)
				{
					foreach($value['ModifiedRequestOrder'] as $request)
					{
						if($request['user_id'] == $this->RequestAction('/external_functions/getIdDataSession') && $request['can_modify'] == 1 && $request['block'] == 0 && $request['hash_keypass'] == $value['PurchaseOrder']['hash_keypass'])
						{
							echo '<tr>
											<td>'.$this->html->link('Edita la Orden', '/purchase_orders/edit/'.$value['PurchaseOrder']['id'], array('class'=>'a_button')).'</td>
										</tr>';
							break;
						}
					}
				}
			}
			
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->RequestAction('/external_functions/getIdDataSession')."/2/cancelOrder") == true && $value['PurchaseOrder']['approved'] == 1 && $this->RequestAction('/external_functions/verifiedAccess/'.$this->RequestAction('/external_functions/getAcquisitionUser/')."/2/cancelOrder") == true && $value['PurchaseOrder']['state_id'] != 7)
			{
				echo '<tr>
							<td>'.$this->html->link('Anular esta Orden','#', array('class'=>'tip_tip_click a_button', 'title' => $this->RequestAction('/purchase_orders/cancelOrdenForm/'.$value['PurchaseOrder']['id']))).'</td>
						</tr>';
			}

			
			echo '</table>
				</div>';
		
		
		echo '</div>';
			
		echo '<div class="fr_info">';
		
						if($value['PurchaseOrderRequest'] != false)
						{
							echo '<div class="info_01">';
							echo '<table border=0>';
							echo '<tr><td colspan=3 width="200"><strong>Detalle</strong></td></tr>';
							echo '<tr><td width="200"><strong>Descripción</strong></td><td><strong>Cantidad</strong></td><td><strong>Precio Neto</strong></td></tr>';
							
							foreach($value['PurchaseOrderRequest'] as $request)
							{
								echo '<tr>';
								echo '<td width="200">'.$request['description'].'</td>';
								
								if($request['quantity'] == 1)
									echo '<td width="200">'.$request['quantity'].' '.$request['unit'].'</td>';
								else
								{
									if($request['unit'] == 'Unidad')
										echo '<td width="200">'.$request['quantity'].' '.$request['unit'].'es</td>';
									else
										echo '<td width="200">'.$request['quantity'].' '.$request['unit'].'s</td>';
								}
								
								echo '<td width="200">'.$value['Badge']['symbol'].number_format($request['net_price'], 0,null, '.').'</td>';
								echo '</tr>';
							}
							
							echo '</table>';
							echo '</div>';
						}

						if($value['PurchaseOrder']['upload_user'] == 1)
						{
							if($value['Budget'] != false)
							{
								echo '<div class="info_02">';
								echo '<table border=0>';
								echo '<tr><td width="200" colspan="3"><strong>Cotizaciones</strong></td></tr>';
								echo '<tr><td><strong>Selección</strong></td><td width="200"><strong>Proveedor</strong></td><td><strong>Adjunto</strong></td></tr>';

								foreach($value['Budget'] as $budget)
								{
										echo '<tr>';
										
										if($budget['proposal_budget'] == 1)
										{
											echo '<td><strong class="link_admin link_ok">&nbsp;</strong></td>';
											
											$idProposal_budget = $budget['id'];
										}
										else
										{
											echo '<td>&nbsp;</td>';
										}
										
										echo '<td width="200"><strong>'.$budget['provider_name'].'</strong></td>
												<td>'.$this->Html->link('Descarga', '/'.$budget['file'], array('target' => '_blank', 'class' => 'link_admin link_attach')).'</td>';
									
										echo '</tr>';
								}
								echo "</table>";
								
								if($userLogin == $value['User']['id'] && $value['PurchaseOrder']['state_id'] == 6 && count($value['Sign']) == 0)
								{
									echo $this->html->link('Evaluar','#',array('class'=>'link_admin a_button tip_tip_click','title' => $this->RequestAction('/purchase_orders/validateBudget/'.$idProposal_budget)));
								}
								
								echo '</div>';
							}
						}
						
						if($value['ApprovedOrder']	!= false)
						{
							foreach($value['ApprovedOrder'] as $approve)
							{
								if($approve['active'] == 1)
								{
									echo '<div class="info_02">';
									echo '<table border=0>
													<tr><td colspan="2"><strong>Impuestos Agregados</strong></td></tr>';
									
									//Nacional
									if($approve['tax_id'] != 0)
									{
										echo '<tr><td>'.$approve['Tax']['tax_name'].' : </td><td>'.$approve['Tax']['value'].'%</td></tr>';
									}
									else
									{
										echo '<tr><td>'.$approve['import_tax_name'].'</td><td>'.$approve['import_tax_value'].' %</td></tr>';
									}
									
									echo '<tr><td>Forma de Pago :</td><td>'.$approve['pay_type'].'</td></tr>';
									
									if(file_exists('files/purchase_orders/orders/'.$value['PurchaseOrder']['order_number'].'.pdf'))
									{
										$ok = 0;
										
										foreach($value['Sign'] as $sign)
										{
											if($sign['user_id'] == $this->RequestAction('/external_functions/getIdDataSession') || $this->RequestAction('/external_functions/verifiedAccess/'.$this->RequestAction('/external_functions/getIdDataSession')."/2/generateOrderPdf") == true)
											{
												echo '<tr><td colspan="2">'.$this->Html->link('Descarga la orden de compra', '/files/purchase_orders/orders/'.$value['PurchaseOrder']['order_number'].'.pdf', array('class' => 'link_admin link_pdf', 'target' => '_blank')).'</td></tr>';
												
												$ok = 1;
												break;
											}
										}
										
										if($this->RequestAction('/external_functions/getIdDataSession') ==$value['User']['id'] || $this->RequestAction('/external_functions/verifiedAccess/'.$this->RequestAction('/external_functions/getIdDataSession')."/2/generateOrderPdf") == true)
										{
											if($ok == 0)
											{
												echo '<tr><td colspan="2">'.$this->Html->link('Descarga la orden de compra', '/files/purchase_orders/orders/'.$value['PurchaseOrder']['order_number'].'.pdf', array('class' => 'link_admin link_pdf', 'target' => '_blank')).'</td></tr>';
											}
										}
										
										if($this->RequestAction('/external_functions/verifiedAccess/'.$this->RequestAction('/external_functions/getIdDataSession')."/2/sendOrderToProvider") == true)
										{
											echo '<tr><td colspan="2">'.$this->Html->link('Envia la orden al proveedor', '/purchase_orders/sendOrderToProvider/'.$value['PurchaseOrder']['id'], array('class' => 'link_admin link_email')).'</td></tr>';
										}
										
										if($this->RequestAction('/external_functions/verifiedAccess/'.$this->RequestAction('/external_functions/getIdDataSession')."/2/associateBill") == true)
										{
											echo '<tr><td colspan="2">'.$this->Html->link('Asociar Facturas', '/purchase_orders/associateBill/'.$value['PurchaseOrder']['id'], array('class' => 'link_admin link_invoice')).'</td></tr>';
										}
									}
									else
									{
										echo '<tr><td>'.$this->Html->tag('strong', 'No existe el archivo PDF con la orden de compra', array('class' => 'link_admin st_rejected')).'</td></tr>';
										
										if($this->RequestAction('/external_functions/verifiedAccess/'.$this->RequestAction('/external_functions/getIdDataSession')."/2/generateOrderPdf") == true)
										{
											echo '<tr><td>'.$this->Html->link('Genera la orden de compra', '#', array('class' => 'link_admin link_pdf', 'onClick' => 'Javascript:generateOrder("'.$approve['id'].'");')).'</td><td><div id="loading"></div></td></tr>';
										}
									}
								
									echo '</table>';
									echo "</div>";
								}
							}
						}
						
		echo '</div>';
		
					
			
		
		if(count($value['Sign']) != 0)
		{
			echo '<div class="firmas">
					
					<div class="sign">
						<div class="approval_link">
							<span class="link_admin link_circuit_approved st_approved">Solicitante</span>
						</div>
							
						<strong>Solicitante</strong><br />
						'.$this->RequestAction('/external_functions/formatNames/'.$value['User']['name'].' '.$value['User']['first_lastname']).'
						<p class="date-sign">'.$fecha_orden.' '.substr($value['PurchaseOrder']['created'], 11, 8).'</p>
					</div>';
		}
		
		
		$cont = 0;
		$stopSignCircuit = 0;

		if(count($value['Sign']) != 0)
		{
			for($x=0; $x < count($value['Sign']); $x++) // Esperando firmas...
			{	
				if($value['Sign'][$x]['state_id'] == 1){
					$cont++;
					
					if($this->RequestAction('/external_functions/getIdDataSession') == $value['Sign'][$x]['user_id'] && $cont == 1)
					{
						if($stopSignCircuit == 0) //Si la firma anterior no fue rechazada, continua 
						{
							if($value['Sign'][$x]['replacement_sign'] == 1) //Firma de reemplazante
							{
								echo '<div class="sign" style="background-color:#FFFF99;">
											<div class="approval_action">
												'.$this->html->link('Acciones','#',array('class'=>'link_admin link_actions tip_tip_click','title' => $this->RequestAction('/signs/formSign/'.$value['Sign'][$x]['id']))).'
											</div>	
											<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$value['Sign'][$x]['position']).'</strong><br />
											'.@$this->RequestAction('/external_functions/formatNames/'.$value['Sign'][$x]['signer_name']).'
											<p>(Reemplazante)</p>
											<p class="link_admin link_alert youMustSign">Debes firmar!</p>
										</div>';
							}
							else
							{
								echo '<div class="sign" style="background-color:#FFFF99;">
											<div class="approval_action">
												'.$this->html->link('Acciones','#',array('class'=>'link_admin link_actions tip_tip_click','title' => $this->RequestAction('/signs/formSign/'.$value['Sign'][$x]['id']))).'
											</div>	
											<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$value['Sign'][$x]['position']).'</strong><br />
											'.@$this->RequestAction('/external_functions/formatNames/'.$value['Sign'][$x]['signer_name']).'
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
									'.@$this->RequestAction('/external_functions/formatNames/'.$value['Sign'][$x]['signer_name']).'
									<p class="date-sign">'.$value['Sign'][$x]['sign_type'].'</p>
								</div>';
						}
					}
					else
					{
						if($value['Sign'][$x]['replacement_sign'] == 1) //Firma de reemplazante
						{
							echo '<div class="sign" style="opacity:0.4;">
									<div class="approval_link">
										'.$this->Html->tag('span', 'En espera', array('class' => 'link_admin link_actions')).'
									</div>	
									<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$value['Sign'][$x]['position']).'</strong><br />
									'.@$this->RequestAction('/external_functions/formatNames/'.$value['Sign'][$x]['signer_name']).'
									<p class="date-sign">Reemplazante</p>
									<p class="date-sign">'.$value['Sign'][$x]['sign_type'].'</p>
								</div>';
						}
						else
						{
							echo '<div class="sign" style="opacity:0.4;">
									
									<div class="approval_link">
										'.$this->Html->tag('span', 'En espera', array('class' => 'link_admin link_actions')).'
									</div>	
									<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$value['Sign'][$x]['position']).'</strong><br />
									'.@$this->RequestAction('/external_functions/formatNames/'.$value['Sign'][$x]['signer_name']).'
									<p class="date-sign">'.$value['Sign'][$x]['sign_type'].'</p>
								</div>';
						}
					}
				}
				
				$day = substr($value['Sign'][$x]['modified'] ,8, 2);
				$month = substr($value['Sign'][$x]['modified'] ,5, 2);
				$year = substr($value['Sign'][$x]['modified'] ,0, 4);
				
				$signDate = $day."-".$month."-".$year;
				$signTime = substr($value['Sign'][$x]['modified'], 10, 9);


				
				if($value['Sign'][$x]['state_id'] == 2)  //Aprobado
				{
				
					if($value['Sign'][$x]['replacement_sign'] == 1) //Firma de reemplazante
					{
						echo '<div class="sign">
							<div class="approval_link">
								<span class="link_admin link_circuit_approved st_approved">Aprobado</span>
							</div>	
							<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$value['Sign'][$x]['position']).'</strong><br />
							'.@$this->RequestAction('/external_functions/formatNames/'.$value['Sign'][$x]['signer_name']).'<br />
							(Reemplazo)
							<p class="date-sign">'.$signDate.' '.$signTime.'</p>
							<p class="date-sign">'.$value['Sign'][$x]['sign_type'].'</p>
							<span class="tip_tip_default link_admin link_comments" title="'.$value['Sign'][$x]['comments'].'">Comentarios</span>
						</div>';
					}
					else
					{
						echo '<div class="sign">
							<div class="approval_link">
								<span class="link_admin link_circuit_approved st_approved">Aprobado</span>
							</div>	
							<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$value['Sign'][$x]['position']).'</strong><br />
							'.@$this->RequestAction('/external_functions/formatNames/'.$value['Sign'][$x]['signer_name']).'
							<p class="date-sign">'.$signDate.' '.$signTime.'</p>
							<p class="date-sign">'.$value['Sign'][$x]['sign_type'].'</p>
							<span class="tip_tip_default link_admin link_comments" title="'.$value['Sign'][$x]['comments'].'">Comentarios</span>
						</div>';
					}
				}



				
				if($value['Sign'][$x]['state_id'] == 3)  //Rechazado
				{
					echo '<div class="sign">
						<div class="approval_link">
							<span class="link_admin link_circuit_rejected st_rejected">Rechazado</span>
						</div>	
						<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$value['Sign'][$x]['position']).'</strong><br />
						'.@$this->RequestAction('/external_functions/formatNames/'.$value['Sign'][$x]['signer_name']).'
						<p class="date-sign">'.$signDate.' '.$signTime.'</p>
						<p class="date-sign">'.$value['Sign'][$x]['sign_type'].'</p>
						<span class="tip_tip_default link_admin link_comments" title="'.$value['Sign'][$x]['comments'].'">Comentarios</span>
					</div>';
					
					$stopSignCircuit = 1;
				}
			}
			echo "</div>";
		}
			
		echo '</div>';
		
		$z++;
	}

?>
</div>

<br /><br />
<div class="paging">
	<?php echo $this->paginator->prev('<< Anterior ', null, null, array('class' => 'disabled_prev'));?>
	<?php echo $this->paginator->numbers(array('separator' => '')); ?>
	<?php echo $this->paginator->next(' Siguiente >>', null, null, array('class' => 'disabled_next'));?>
</div>