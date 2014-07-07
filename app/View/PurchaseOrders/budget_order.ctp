<?php 
	echo $this->Html->script('AjaxUpload.2.0.min.js'); 
?>


<script>
	$(function() 
	{
		var availableTags = [	
									<?php
										foreach($providers as $provider)
										{
											echo '"'.$provider['Provider']['provider_name'].' (Rut: '.$provider['Provider']['provider_dni'].') ID: '.$provider['Provider']['id'].'", ';
										}
									?>
									""];
									
		$( "#tags" ).autocomplete({
		source: availableTags
		});
	});
</script>
	
<script>
	$(document).ready(function(){
		var button = $('#upload_button'), interval;
		var cant_archivos = parseInt($('#cont_archivos').val());
		var newRow;
			new AjaxUpload('#upload_button', {
			
			
			action: '/michilevision/external_functions/uploadFile',
			
			onSubmit : function(file , ext){
				
				if(cant_archivos <= 2)
				{
					//Cambio el texto del boton y lo deshabilito
					button.text('Cargando...');
					button.css('background-image', 'url(/michilevision/img/loading.gif)');
					this.disable();
				}
				if(cant_archivos > 4)
					alert('Ya existen los 5 archivos.');
			},
			
			onComplete: function(file, response){
				button.text('Cargado');
				button.css('background-image', 'url(/michilevision/img/ok.png)');
				
				var provider_text = $('.ui-autocomplete-input').val();
				var id_provider = provider_text.split('ID: ');
				
				var rut = id_provider[0].split('(Rut: ');
				
				
				// habilito upload button                       
				this.enable();
				button.text('Selecciona Archivo');
				button.css('background-image', 'url(/michilevision/img/uploading.png)');
				
				
				var idFila = 'tr_' + id_provider[1]  + 1 + '_' + Math.floor(Math.random() * (99999-999+1)) + 999;
				
				// Agrega archivo a la tabla
				newRow = '<tr id="' + idFila+'">';
				newRow += '<td><input name="data[Budget][proposal_budget][]"  type="radio" value='+ id_provider[1] +' /></td>';
				newRow += '<td>Id : <input name="data[Budget][provider_id][]"  type="hidden" value="' + id_provider[1] + '"/>'+ id_provider[1]  +'</td>';
				newRow += '<td>' + id_provider[0] + '</td>';
				newRow += '<td>Archivo : <input id="file_name" name="data[Budget][file][]"  type="hidden" value="' + file + '"/>'+ file  +'</td>';
				newRow += '<td><a href="javascript:deleteBudget(\'' + file + '\', \'' + idFila + '\')" class="app_buttons delete_item" title="Elimina esta cotización" /></td>';
				newRow += '</tr>';
				
		
				$(newRow).appendTo('#lista');
				
				$('.ui-autocomplete-input').val('');
				
				if(cant_archivos == 0)
				{
					messageAlert();
				}
				
				cant_archivos = cant_archivos + 1; 
				
				if(cant_archivos >= 1)
				{
					$("#submit").show("normal");
				}
				
				
				$('#cont_archivos').val(cant_archivos);
				$("#upload_button").hide("normal");
			}   
		});
	});
	
	function buttonAction()
	{
		var text = $('.ui-autocomplete-input').val();
		var id_provider = text.split('ID: ');
		var cont = $('#cont_archivos').val();

		if(cont < 5)
		{
			if(/^[0-9]+$/.test(id_provider[1])) 
			{
				if(id_provider[1].length == 0)
				{
					$("#upload_button").hide("normal");
				}	
				if(id_provider[1].length != 0)
				{
					$("#upload_button").show("normal");
				}
			}
			else
				$("#upload_button").hide("normal");
		}
		else
		{
			$('.ui-autocomplete-input').val('');
			$('.ui-autocomplete-input').attr('readonly', 'readonly');
			$('.ui-autocomplete-input').css('background-color', '#CCCCCC');
			$('#message').show('normal');
		}
	}
	
	function deleteBudget(archivo, fila)
	{
		
		var root = "/michilevision";
		var cant_archivos = parseInt($('#cont_archivos').val());
		
		cant_archivos = cant_archivos - 1;
		
		$('.deleting').fadeIn('fast');
		$.ajax({url: root + '/purchase_orders/deleteBudget/'+ archivo, success: function(result){ if(result == true){ $('.deleting').hide('fast'); } }});
		$('#'+fila).delay(500).remove();
		$('.deleting').delay(500).hide('fast');
		$('#cont_archivos').val(cant_archivos);
		
		if(cant_archivos == 0)
		{
			$("#submit").hide("normal");
		}
	}
	
	function messageAlert() 
	{
		$('div.alerta').show('slow');
	}
</script>

<?php
	echo $this->Html->tag('h3', $this->html->link('Ordenes de compra','/purchase_orders/mainMenu/').' &gt; Cotizar Orden de Compra');
	echo $this->Html->tag('h2', 'Ordenes de compra', array('class' => 'link_admin link_purchase_order'));
	echo $this->Session->flash();

	echo '<div class="acc_detail">
			<div class="fr_info" style="margin: 0px 60px 15px; ">
				<div class="info_01">
					<table>
						<tr>
							<td><span class="link_admin link_dni">ID:</span></td>
							<td><strong>'.$this->request->data['PurchaseOrder']['id'].'</strong></td>
						</tr>
						<tr>
							<td><span class="link_admin link_user">Solicitante :</span></td>
							<td><strong>'.$this->RequestAction('/external_functions/formatNames/'.$this->request->data['User']['name'].' '.$this->request->data['User']['first_lastname']).'</strong></td>
						</tr>
						<tr>
							<td><span class="link_admin link_management">Gerencia :</span></td>
							<td>'.$this->RequestAction('/external_functions/formatNames/'.$this->request->data['Management']['management_name']).'</td>
						</tr>
						<tr>
							<td><span class="link_admin link_cost_center">Centro costo :</span></td>
							<td>'.$this->RequestAction('/external_functions/formatNames/'.$this->request->data['CostCenter']['cost_center_code'].' '.$this->request->data['CostCenter']['cost_center_name']).'</td>
						</tr>';
				
				if($this->request->data['PurchaseOrder']['state_id'] == 1)
					$css_status = array(0 => 'link_fr_pending', 1 => 'st_pending');
				
				if($this->request->data['PurchaseOrder']['state_id'] == 2)
					$css_status = array(0 => 'link_fr_approved', 1 => 'st_approved');
				
				if($this->request->data['PurchaseOrder']['state_id'] == 3)
					$css_status = array(0 => 'link_fr_rejected', 1 => 'st_rejected');
				
				if($this->request->data['PurchaseOrder']['state_id'] == 4)
					$css_status = array(0 => 'link_fr_pending', 1 => 'st_pending');
			
				echo '<tr>
						<td><span class="link_admin '.$css_status[0].'"> Estado :</span></td>
						<td><strong class="'.$css_status[1].'">'.$this->request->data['State']['state'].'</strong></td>
					</tr>';
						
						
				if(trim($this->request->data['PurchaseOrder']['request_number']) != '' || $this->request->data['PurchaseOrder']['request_number'] != NULL){
					echo '<tr>
						<td><span class="link_admin link_ticket">N° Solicitud :</span></td>
						<td><strong>'.$this->request->data['PurchaseOrder']['request_number'].'</strong></td>
					</tr>';
				}	
			
				if(trim($this->request->data['PurchaseOrder']['order_number']) != '' || $this->request->data['PurchaseOrder']['order_number'] != NULL){
					echo '<tr>
						<td><span class="link_admin link_ticket">N° Orden:</span></td>
						<td><strong>'.$this->request->data['PurchaseOrder']['order_number'].'</strong></td>
					</tr>';
				}	
				
				echo '</table>
				</div>
				
				<div class="info_02">
					<table>
						<tr>
							<td width="100"><strong class="link_admin link_invoice">Facturar a :</strong></td>
							<td width="100">'.$this->request->data['PurchaseOrder']['invoice_to'].'</td>
						</tr>
						<tr>
							<td width="100"><strong class="link_admin link_buy_type">Tipo de compra :</strong></td>
							<td width="100">'.$this->request->data['PurchaseOrder']['purchase_type'].'</td>
						</tr>';
						
						$answer;
						
						if($this->request->data['PurchaseOrder']['only_provider'] == 1)
						{
							$answer = "Si";
						}
						else
						{
							$answer = "No";
						}
						
						$answer2;
						
						if($this->request->data['PurchaseOrder']['budgeted'] == 1)
						{
							$answer2 = "Si";
						}
						else
						{
							$answer2 = "No";
						}
						
						echo '<tr>
							<td width="100"><strong class="link_admin link_provider">Proveedor unico :</strong></td>
							<td width="100">'.$answer.'</td>
						</tr>
						<tr>
							<td width="100"><strong class="link_admin link_budgeted">Orden presupuestada :</strong></td>
							<td width="100">'.$answer2.'</td>
						</tr>

						<tr>
							<td width="100"><strong class="link_admin link_money">Monto total :</strong></td>
							<td width="100"><strong>'.$this->request->data['Badge']['symbol'].number_format($this->request->data['PurchaseOrder']['grand_net_total_price'], 0,null, '.').'</strong></td>
						</tr>';

			echo '</table>
				</div>
			</div>';
		
		
	echo '<div class="reason" style="margin: 0px 60px 20px; width: 795px;">
				<strong class="link_admin link_reason">Motivo de la orden : </strong> 
				<div>'.
					$this->request->data['PurchaseOrder']['reason']
				.'</div>
			</div>';
			
	

	echo '<div class="ui-widget" style="margin: 0px 60px 20px; width: 795px;">';
	echo $this->form->create('Budget');
	echo $this->form->input('purchase_order_id', array('type' => 'hidden', 'value' => $this->request->data['PurchaseOrder']['id']));
	echo $this->form->input('Finder.finder', array('label' => 'Proveedor', 'id' => 'tags', 'type' => 'text', 'size' => 70, 'onblur' => 'javascript:buttonAction();', 'after' => $this->Html->link('No existe su proveedor?', '/purchase_orders/requestAddProvider', array('class' => 'link_admin link_help', 'title'=>'Solicitud para agregar nuevo proveedor','rel' => 'shadowbox;width=500;height=380'))));
	echo $this->Html->div('hidden link_admin link_uploading', 'Selecciona Archivo', array('id' => 'upload_button'));
	echo '<font color="red">'.$this->Html->div('hidden', $this->Html->tag('strong', 'Ya has cargado los 3 archivos.', array('font' => array('color' => 'red'))), array('id' => 'message')).'</font>';
	echo '<font color="#d04c04">'.$this->Html->div('alerta', $this->Html->tag('strong', 'Recuerda que debes seleccionar una cotización.'), array('style' => 'display: none;')).'</font>';
	echo '<table border="1" id="lista">
				</table>
				</div>
		<input type="hidden" id="cont_archivos" value="0">';
	echo $this->Html->div(null, '', array('id' => 'loading', 'style' => 'display:none;'));
	echo $this->Html->div('deleting link_admin link_loading', 'Eliminando...', array('style' => 'display:none;'));
	echo '<div id="submit" style="display: none;">';
	?>
	
	<div class="items-request">
				<table id="detail-request" cellspacing="0">
					<tr>
						<th width="450">Descripcion</th>
						<th width="110">Unid. Medida</th>
						<th width="110">Cantidad</th>
						<th width="110">Precio Neto Unitario $</th>
						<th>&nbsp;</th>
					</tr>
					<?php
					if(isset($this->request->data['PurchaseOrderRequest']))
					{
						if($this->request->data['PurchaseOrderRequest'] != false)
						{
							$count=0;
							
							foreach($this->request->data['PurchaseOrderRequest'] as $request)
							{
								
								echo $this->form->input('PurchaseOrderRequest.'.$count.'.id', array('label' => '', 'type' => 'hidden', 'default' => $request['id']));
								echo $this->form->input('PurchaseOrderRequest.'.$count.'.description', array('label' => '', 'type' => 'hidden', 'default' => $request['description']));
								echo $this->form->input('PurchaseOrderRequest.'.$count.'.quantity', array('label' => '', 'type' => 'hidden', 'default' => $request['quantity']));
								
								echo "<tr>";
								echo "<td>".$request['description']."</td>";
								echo "<td>".$this->form->input('PurchaseOrderRequest.'.$count.'.measuring_unit_id', array('type' => 'select', 'options' => $measuring_units, 'default' => $request['measuring_unit_id']))."</td>";
								echo "<td>".$request['quantity']."</td>";
								echo "<td>".$this->form->input('PurchaseOrderRequest.'.$count.'.net_price', array('label' => '', 'type' => 'text',  'maxlength' => 45, 'default' => $request['net_price']))."</td>";
								echo "</tr>";
								
								$count++;
							}
						}
					}
					?>
					
				</table>
			</div>
			
	<?php 
	
	echo $this->form->end('Ingresar cotización');
	echo '</div>';
	echo '</div>';
?>