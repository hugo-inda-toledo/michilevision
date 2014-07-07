<?php
class PurchaseOrderRequest extends AppModel 
{
	var $name = 'PurchaseOrderRequest';
	 
	var $belongsTo   = array ('PurchaseOrder', 'MeasuringUnit');
	
	var $validate = array('purchase_order_id' => array
										(
											'rule' => 'numeric',
											'message' => 'Este campo no puede estar vacío.'
										),
										'description' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										'measuring_unit_id' => array
										(
											'rule' => 'numeric',
											'message' => 'Este campo no puede estar vacío.'
										),
										'quantity' => array
										(
											'rule' => 'numeric',
											'message' => 'Este campo no puede estar vacío.'
										)
	);
}
?>