<?php
	/*echo "<pre>";
	print_r($this->request->data);
	echo "</pre>";*/
?>

<?php
	echo $this->Form->Create('ApprovedOrder', array('id' => 'UserAddForm'));
	echo $this->Form->input('ApprovedOrder.purchase_order_id', array('type' => 'hidden', 'value' => $this->request->data['PurchaseOrder']['id']));
	echo $this->Form->input('PurchaseOrder.order_number', array('label' => 'N° de Orden', 'type' => 'text', 'default' => $this->request->data['PurchaseOrder']['order_number'], 'disabled' => 'disabled'));
	echo $this->Form->input('Badge.badge', array('label' => 'Divisa', 'type' => 'text', 'default' => $this->request->data['Badge']['badge'], 'disabled' => 'disabled'));
	echo $this->Form->input('PurchaseOrder.purchase_type', array('label' => 'Tipo de Compra', 'type' => 'text', 'default' => $this->request->data['PurchaseOrder']['purchase_type'], 'disabled' => 'disabled'));
	echo $this->Form->input('PurchaseOrder.invoice_to', array('label' => 'Facturar a', 'type' => 'select', 'options' => array('Chilevision' => 'Chilevision', 'Turner' => 'Turner'), 'default' => $this->request->data['PurchaseOrder']['invoice_to']));
	
	if($this->request->data['PurchaseOrder']['purchase_type'] == 'Nacional')
	{
		echo $this->Form->input('ApprovedOrder.tax_id', array('label' => 'Impuesto Nacional', 'type' => 'select', 'between' => '<font color="red">Debes seleccionar un tipo de impuesto a aplicar</font><br><br>', 'options' => $taxes));
	}
	
	if($this->request->data['PurchaseOrder']['purchase_type'] == 'Importado')
	{
		echo $this->Form->input('ApprovedOrder.tax_id', array('type' => 'hidden', 'value' => 0));
		echo $this->Form->input('ApprovedOrder.import_tax_name', array('legend' => 'Impuesto Importado', 'type' => 'radio', 'between' => '<font color="red"><strong>Debes seleccionar un tipo de impuesto a aplicar</strong></font><br><br>', 'options' => array('CIF' => 'CIF', 'FOB' => 'FOB')));
		echo $this->Form->input('ApprovedOrder.import_tax_value', array('label' => 'Valor del Impuesto', 'type' => 'text'));
	}
	
	echo $this->Form->input('ApprovedOrder.pay_type', array('label' => 'Forma de Pago', 'type' => 'text', 'value' => '45 Días'));
	echo $this->Form->input('ApprovedOrder.comments', array('label' => 'Comentarios', 'type' => 'combobox', 'rows' => 3));
	
	echo $this->Form->end('Generar');
?>