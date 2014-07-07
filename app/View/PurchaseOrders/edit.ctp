<?php
if(count($costCenters) != null)
{
	echo $this->Html->script('AjaxUpload.2.0.min.js');

	$options = '';
	
	foreach ($measuring_units as $key => $value)
	{
		$options .= '<option value="'.$key.'">'.$value.'</option>';
	}
	
	$descriptionInput =  $this->form->input('PurchaseOrderRequest.description', array('name' => 'data[PurchaseOrderRequest][description][]', 'label' => '', 'type' => 'text', 'maxlength' => 45, 'size' => 30));
	$measuringUnitInput = '<label for="PurchaseOrderRequestMeasuringUnitId"></label><select name="data[PurchaseOrderRequest][measuring_unit_id][]" id ="PurchaseOrderRequestMeasuringUnitId">'.$options.'</select>';
	$quantityInput = $this->form->input('PurchaseOrderRequest.quantity', array('id' => '[ID_QTY]', 'name' => 'data[PurchaseOrderRequest][quantity][]', 'label' => '', 'type' => 'text',  'maxlength' => 3));
	$netPriceInput = $this->form->input('PurchaseOrderRequest.net_price', array('id' => '[ID_NET]', 'name' => 'data[PurchaseOrderRequest][net_price][]', 'label' => '', 'type' => 'text',  'maxlength' => 45, 'onBlur' => 'javascript:calculateTotal(this, \'[ID_QTY]\', \'[ID_TOT]\');'));
	$netTotalInput = $this->form->input('PurchaseOrderRequest.net_total', array('id' => '[ID_TOT]', 'name' => 'data[PurchaseOrderRequest][net_total][]', 'label' => '', 'type' => 'text', 'maxlength' => 45, 'disabled' => 'disabled'));
	$deleteRowBtn = $this->html->link('Eliminar fila','javascript:deleteRow(\'[ID_ITEM]\');',array('class' => 'tip_tip_default app_buttons delete_item','title' => 'Eliminar este Ítem'));
?>

<script>
	
	function addRow()
	{
		var numRows = ($('#detail-request tr').length - 1);
		var idRow = 'tr_' + numRows + 1 + '_' + Math.floor(Math.random() * (99999-999+1)) + 999;
		var idQty = 'qty_' + numRows + 1 + '_' + Math.floor(Math.random() * (99999-999+1)) + 999;
		var idNet = 'net_' + numRows + 1 + '_' + Math.floor(Math.random() * (99999-999+1)) + 999;
		var idTot = 'tot_' + numRows + 1 + '_' + Math.floor(Math.random() * (99999-999+1)) + 999;
		
		var btnEliminar = '<?php echo $deleteRowBtn;?>';
		btnEliminar = btnEliminar.replace('[ID_ITEM]',idRow);
		
		var inputQty = '<?php echo $quantityInput; ?>';
		inputQty = inputQty.replace('[ID_QTY]', idQty);
		inputQty = inputQty.replace('[ID_QTY]', idQty);

		
		var inputNet = '<?php echo $netPriceInput; ?>';
		inputNet = inputNet.replace('[ID_NET]', idNet);
		inputNet = inputNet.replace('[ID_NET]', idNet);
		inputNet = inputNet.replace('[ID_QTY]', idQty);
		inputNet = inputNet.replace('[ID_TOT]', idTot);
		
		var inputTotal = '<?php echo $netTotalInput; ?>';
		inputTotal = inputTotal.replace('[ID_TOT]', idTot);
		inputTotal = inputTotal.replace('[ID_TOT]', idTot);

		
		var nuevaFila = '<tr id="' + idRow + '">';
		nuevaFila += '<td><?php echo $descriptionInput;?></td>';
		nuevaFila += '<td><?php echo $measuringUnitInput;?></td>';
		nuevaFila += '<td>' +  inputQty + '</td>';
		nuevaFila += '<td>' +  inputNet + '</td>';
		nuevaFila += '<td>' + inputTotal + '</td>';
		nuevaFila += '<td>' + btnEliminar + '</td>';
		nuevaFila += '</tr>';
		
		$('#detail-request').append(nuevaFila);
		inicializarTipTip('default');
		
		$('#upload_button').css();
		$('#upload_button').css('background-image', 'url(/michilevision/img/file_icon.png)');
		$('#upload_button').enable();
	}
	
	function calculateTotal(idNeto, idCant, idTot)
	{
		var neto = $(idNeto).val();
		var cant = $('#'+idCant).val();
		
		var total = neto*cant;

		$('#'+idTot).val(total);
	}



	function deleteRow(idRow){
	$('#detail-request tr#' + idRow).remove();
	}
	
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
	
	function deleteBudget(archivo, fila, id)
	{
		
		var root = "/michilevision";
		var cant_archivos = parseInt($('#cont_archivos').val());
		
		cant_archivos = cant_archivos - 1;
		
		if(confirm("Estas seguro de eliminar esta cotización? (Una vez eliminada, no puedes revertir el cambio)."))
		{
			$('.deleting').fadeIn('fast');
			$.ajax({url: root + '/purchase_orders/deleteBudget/'+ archivo + '/' + id, success: function(result){ if(result == true){ $('.deleting').hide('fast'); } }});
			$('#'+fila).delay(500).remove();
			$('.deleting').delay(500).fadeOut('fast');
			$('#cont_archivos').val(cant_archivos);
		}
	}
	
</script>
	
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
				
				
				$('#cont_archivos').val(cant_archivos);
				$("#upload_button").hide("normal");
			}   
		});
	});
	
	function messageAlert() 
	{
		$('div.alerta').show('slow');
	}
</script>
<?php
echo $this->Html->tag('h3',$this->html->link('Ordenes de compra','/purchase_orders/mainMenu/').' &gt; Editando una solicitud de compra');
echo $this->Html->tag('h2','Editanto orden de compra cotizada', array('class' => 'link_admin link_edit'));
echo $this->Session->flash(); 
echo $this->form->create('PurchaseOrder', array('class' => 'form-request'));
?>
<table>
	<tr>
		<td>
			<table>
				<tr>
					<td>
					<?php
					echo $this->form->input('PurchaseOrder.id', array('type' => 'hidden', 'value' => $this->request->data['PurchaseOrder']['id']));
					echo $this->form->input('PurchaseOrder.user', array('label' => 'Solicitante', 'type' => 'text', 'disabled' => 'disabled', 'default' => $requestUser['User']['name']." ".$requestUser['User']['first_lastname']));
					echo $this->form->input('PurchaseOrder.user_id', array('type' => 'hidden', 'default' => $requestUser['User']['id']));
					echo $this->form->input('PurchaseOrder.dni_user', array('label' => 'Rut solicitante',  'title' => 'Rut solicitante', 'disabled' => 'disabled', 'type' => 'text', 'default' => $requestUser['User']['dni']));
					echo $this->form->input('PurchaseOrder.management_user', array('label' => 'Gerente', 'type' => 'text', 'disabled' => 'disabled', 'value' => $this->request->data['management_user']['name'].' '.$this->request->data['management_user']['first_lastname']));
					echo $this->form->input('PurchaseOrder.management_user_id', array('type' => 'hidden', 'default' => $this->request->data['management_user']['id']));
					echo $this->form->input('PurchaseOrder.dni_management_user', array('label' => 'Rut gerente', 'disabled' => 'disabled', 'type' => 'text', 'default' => $this->request->data['management_user']['dni']));
					echo $this->form->input('PurchaseOrder.management', array('label' => 'Area', 'disabled' => 'disabled', 'type' => 'text', 'default' => $requestUser['Management']['management_name'], 'size' => 30));
					echo $this->form->input('PurchaseOrder.management_id', array('type' => 'hidden', 'default' => $requestUser['Management']['id'])); 
					echo $this->form->input('PurchaseOrder.reason', array('label' =>'Motivo de la compra', 'type' => 'textarea', 'disabled' => 'disabled', array('rows' => '2', 'cols' => '10')));
					?></td>
					<td class="left_separator">
					<?php
					echo $this->form->input('PurchaseOrder.authorization_id', array('type' => 'hidden', 'default' => $requestUser['Management']['authorization_id']));
					echo $this->form->input('PurchaseOrder.cost_center_id', array('label' => 'Centro de costo', 'type'=>'select', 'options' => $costCenters, 'disabled' => 'disabled', 'div'=>array('class'=>'select')));
					echo $this->form->input('PurchaseOrder.invoice_to', array('label' => 'Facturar a', 'type' => 'select', 'options' => array('Chilevision' => 'Chilevision', 'Turner' => 'Turner'), 'disabled' => 'disabled', 'after' => $this->Html->link('', '/purchase_orders/info', array('class' => 'link_admin link_help', 'title'=>'Información sobre compras de Activo Fijo','rel' => 'shadowbox;width=500;height=300'))));
					echo $this->form->input('PurchaseOrder.purchase_type', array('label' => 'Tipo de compra', 'type' => 'select', 'options' => array('Nacional' => 'Nacional', 'Importado' => 'Importado'), 'disabled' => 'disabled'));
					echo $this->form->input('PurchaseOrder.only_provider', array('label' => 'Proveedor Unico', 'type' => 'select', 'options' => array(1 => 'Si', 0 => 'No'), 'disabled' => 'disabled'));
					echo $this->form->input('PurchaseOrder.budgeted', array('label' => 'Orden Presupuestada', 'type' => 'select', 'options' => array(1 => 'Si', 0 => 'No'), 'disabled' => 'disabled'));
					echo $this->form->input('PurchaseOrder.badge_id', array('label' => 'Tipo de moneda', 'type'=>'select', 'options' => $badges, 'div'=>array('class'=>'select'), 'disabled' => 'disabled'));
					?></td>
					<td valign="top">
						<p class="req-date">Fecha solicitud : <strong><?php echo $this->request->data['PurchaseOrder']['created'];?></p>
					</td>		
				</tr>
			</table>
			
			<?php
			echo '<div class="ui-widget">
				'.$this->form->input('Finder.finder', array('label' => 'Proveedor', 'id' => 'tags', 'type' => 'text', 'size' => 70, 'onblur' => 'javascript:buttonAction();', 'after' => $this->Html->link('Solicitar inserción de nuevo proveedor.', '/purchase_orders/requestAddProvider', array('class' => 'link_admin link_help', 'title'=>'Solicitud para agregar nuevo proveedor','rel' => 'shadowbox;width=500;height=380'))));
					echo $this->Html->div('hidden link_admin link_uploading', 'Selecciona Archivo', array('id' => 'upload_button'));
					echo '<font color="red">'.$this->Html->div('hidden', $this->Html->tag('strong', 'Ya has cargado los 3 archivos.', array('font' => array('color' => 'red'))), array('id' => 'message')).'</font>';
					echo '<font color="#d04c04">'.$this->Html->div('alerta', $this->Html->tag('strong', 'Recuerda que debes seleccionar una cotización.'), array('style' => 'display: none;')).'</font>';
					echo '<table border="1" id="lista">';
					
					if($this->request->data['Budget'] != false)
					{
						$x=0;
						
						foreach($this->request->data['Budget'] as $budget)
						{
							$files = explode("/", $budget['file']);
							
							$nombreArchivo = end($files);
							
							
							
							echo '<tr id="'.$x.'">';
							
							if($budget['selected'] == 1)
							{
								echo '<td><input name="data[Budget][proposal_budget][]"  type="radio" value='.$budget['provider_id'].' checked="checked"/></td>';
							}
							else
							{
								echo '<td><input name="data[Budget][proposal_budget][]"  type="radio" value='.$budget['provider_id'].' /></td>';
							}
		
							echo '<td>Id : <input name="data[Budget][provider_id][]"  type="hidden" value="'.$budget['provider_id'].'"/>'.$budget['provider_id'].'</td>';
							echo '<td>'.$budget['Provider']['provider_name'].'</td>';
							echo '<td>Archivo : <input id="file_name" name="data[Budget][file][]"  type="hidden" value="'.$nombreArchivo.'"/>'.$nombreArchivo.'</td>';
							echo '<td><a href="javascript:deleteBudget(\''.$nombreArchivo.'\', \''.$x.'\', \''.$budget['id'].'\')" class="app_buttons delete_item" title="Elimina esta cotización" /></td>';
							echo '</tr>';
							
							$x++;
						}
					}
					
					echo '</table>
				</div>
				<input type="hidden" id="cont_archivos" value="0">';
				echo $this->Html->div(null, '', array('id' => 'loading', 'style' => 'display:none;'));
				echo $this->Html->div('deleting link_admin link_loading', 'Eliminando...', array('style' => 'display:none;'));
				echo $this->Html->div('link_admin approval_link', 'OK', array('style' => 'display:none;', 'id' => 'deletingOk'));
			?>
		</td>
	</tr>
	

	<tr>
		<td>
			<div class="items-request">
				<table id="detail-request" cellspacing="0">
					<tr>
						<th width="450">Descripcion</th>
						<th width="110">Unid. Medida</th>
						<th width="110">Cantidad</th>
						<th width="110">Precio Neto Unitario $</th>
						<th width="110">Total</th>
						<th>&nbsp;</th>
					</tr>
					<?php
						if($this->request->data['PurchaseOrderRequest']  != false)
						{
							$y=0;
							
							foreach($this->request->data['PurchaseOrderRequest'] as $request)
							{
								echo $this->form->input('PurchaseOrderRequest.'.$y.'.id', array('type' => 'hidden', 'value' => $request['id']));
								echo "<tr>";
								echo "<td>".$this->form->input('PurchaseOrderRequest.'.$y.'.description', array('label' => '', 'type' => 'text', 'maxlength' => 45, 'size' => 30, 'default' => $request['description']))."</td>";
								echo "<td>".$this->form->input('PurchaseOrderRequest.'.$y.'.measuring_unit_id', array('label' => '', 'type' => 'select', 'options' => $measuring_units, 'default' => $request['measuring_unit_id']))."</td>";
								echo "<td>".$this->form->input('PurchaseOrderRequest.'.$y.'.quantity', array('label' => '', 'type' => 'text', 'maxlength' => 3, 'default' => $request['quantity']))."</td>";
								echo "<td>".$this->form->input('PurchaseOrderRequest.'.$y.'.net_price', array('label' => '', 'type' => 'text', 'maxlength' => 45, 'default' => $request['net_price']))."</td>";
								$total = $request['net_price'] * $request['quantity'];
								echo "<td>".$this->form->input('PurchaseOrderRequest.total_price', array('label' => '', 'type' => 'text', 'default' => $total, 'disabled' => 'disabled'))."</td>";
								echo "</tr>";
								
								$y++;
							}
						}
					?>
				</table>
			</div>
			
			<?php echo $this->html->link('Agregar fila','javascript:addRow();',array('class' => 'link_admin link_add add-row')); ?>
		</td>
	</tr>
</table>
		
<?php		
echo $this->form->end('Enviar solicitud');
}


else
{
	echo "<p align='center'><font color='gray'><b><u>No tienes centros de costos asociados a este sistema para generar la solicitud.</b></u></font></p>";
	echo "<p align='center'><b>".$this->html->link('Volver al listado', 'javascript:history.back()')."</b></p>";
}
?>