<?php
if(count($costCenters) != null)
{
	$options = '';
	
	foreach ($measuring_units as $key => $value)
	{
		$options .= '<option value="'.$key.'">'.$value.'</option>';
	}
	
	$descriptionInput =  $this->form->input('PurchaseOrderRequest.description', array('name' => 'data[PurchaseOrderRequest][description][]', 'label' => '', 'type' => 'text', 'maxlength' => 45, 'size' => 30));
	$measuringUnitInput = '<label for="PurchaseOrderRequestMeasuringUnitId"></label><select name="data[PurchaseOrderRequest][measuring_unit_id][]" id ="PurchaseOrderRequestMeasuringUnitId">'.$options.'</select>';
	$quantityInput = $this->form->input('PurchaseOrderRequest.quantity', array('id' => '[ID_QTY]', 'name' => 'data[PurchaseOrderRequest][quantity][]', 'label' => '', 'type' => 'text',  'maxlength' => 3));
	$netPriceInput = $this->form->input('PurchaseOrderRequest.net_price', array('id' => '[ID_NET]', 'name' => 'data[PurchaseOrderRequest][net_price][]', 'label' => '', 'type' => 'text',  'maxlength' => 45, 'onBlur' => 'javascript:calculateTotal(this, \'[ID_QTY]\', \'[ID_TOT]\');', 'disabled' => 'disabled'));
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

		
		var nuevaFila = '<tr id="' + idRow + '">';
		nuevaFila += '<td><?php echo $descriptionInput;?></td>';
		nuevaFila += '<td><?php echo $measuringUnitInput;?></td>';
		nuevaFila += '<td>' +  inputQty + '</td>';
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



	function deleteRow(idRow)
	{
		$('#detail-request tr#' + idRow).remove();
	}
</script>
	

<?php
echo $this->Html->tag('h3',$this->html->link('Ordenes de compra','/purchase_orders/mainMenu/').' &gt; Nueva solicitud de compra');
echo $this->Html->tag('h2','Nueva orden de compra con solicitud de cotización', array('class' => 'link_admin link_add'));
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
					echo $this->form->input('user', array('label' => 'Solicitante', 'type' => 'text', 'disabled' => 'disabled', 'default' => $requestUser['User']['name']." ".$requestUser['User']['first_lastname']));
					echo $this->form->input('user_id', array('type' => 'hidden', 'default' => $requestUser['User']['id']));
					echo $this->form->input('dni_user', array('label' => 'Rut solicitante',  'title' => 'Rut solicitante', 'disabled' => 'disabled', 'type' => 'text', 'default' => $requestUser['User']['dni']));
					echo $this->form->input('management_user', array('label' => 'Gerente', 'type' => 'text', 'disabled' => 'disabled', 'default' => $management_user['User']['name'].' '.$management_user['User']['first_lastname']));
					echo $this->form->input('management_user_id', array('type' => 'hidden', 'default' => $management_user['User']['id']));
					echo $this->form->input('dni_management_user', array('label' => 'Rut gerente', 'disabled' => 'disabled', 'type' => 'text', 'default' => $management_user['User']['dni']));
					echo $this->form->input('management', array('label' => 'Area', 'disabled' => 'disabled', 'type' => 'text', 'default' => $requestUser['Management']['management_name'], 'size' => 30));
					echo $this->form->input('management_id', array('type' => 'hidden', 'default' => $requestUser['Management']['id'])); 
					echo $this->form->input('reason', array('label' =>'Motivo de la compra', 'type' => 'textarea', array('rows' => '2', 'cols' => '10')));
					?></td>
					<td class="left_separator">
					<?php
					echo $this->form->input('authorization_id', array('type' => 'hidden', 'default' => $requestUser['Management']['authorization_id']));
					echo $this->form->input('cost_center_id', array('label' => 'Centro de costo', 'type'=>'select', 'options' => $costCenters, 'div'=>array('class'=>'select')));
					echo $this->form->input('invoice_to', array('label' => 'Facturar a', 'type' => 'select', 'options' => array('Chilevision' => 'Chilevision', 'Turner' => 'Turner'), 'after' => $this->Html->link('', '/purchase_orders/info', array('class' => 'link_admin link_help', 'title'=>'Información sobre compras de Activo Fijo','rel' => 'shadowbox;width=500;height=300'))));
					echo $this->form->input('purchase_type', array('label' => 'Tipo de compra', 'type' => 'select', 'options' => array('Nacional' => 'Nacional', 'Importado' => 'Importado')));
					echo $this->form->input('only_provider', array('label' => 'Proveedor Unico', 'type' => 'select', 'options' => array(1 => 'Si', 0 => 'No')));
					echo $this->form->input('budgeted', array('label' => 'Orden Presupuestada', 'type' => 'select', 'options' => array(1 => 'Si', 0 => 'No')));
					echo $this->form->input('badge_id', array('label' => 'Tipo de moneda', 'type'=>'select', 'options' => $badges, 'div'=>array('class'=>'select')));
					?></td>
					<td valign="top">
						<p class="req-date">Fecha solicitud : <strong><?php echo date('d-m-Y');?></p>
					</td>		
				</tr>
			</table>
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
						<th>&nbsp;</th>
					</tr>
					<?php
					$idQty = 'qty_'.rand(1,999999).'_'.rand(1,999999);
					$idNet = 'net_'.rand(1,999999).'_'.rand(1,999999);
					$idTot = 'tot_'.rand(1,999999).'_'.rand(1,999999);
					
					$quantityInput = str_replace('[ID_QTY]',$idQty,$quantityInput);
					?>
					<tr>
						<td><?php echo $descriptionInput;?></td>
						<td><?php echo $measuringUnitInput;?></td>
						<td><?php echo $quantityInput;?></td>
						<td>&nbsp;</td>
					</tr>
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