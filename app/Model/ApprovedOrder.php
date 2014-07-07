<?php
class ApprovedOrder extends AppModel
{
	var $name = 'ApprovedOrder';
	var $belongsTo = array('PurchaseOrder');
	
	var $validate = array(
									'purchase_order_id' => array
									(
										'rule' => 'numeric',
										'message' => 'Este campo debe ser un número.'
									),
									'tax_id' => array
									(
										'rule' => 'numeric',
										'message' => 'Este campo debe ser un número.'
									),
									'pay_type' => array
									(
										'rule' => 'notEmpty',
										'message' => 'Este campo no puede estar vacío.'
									),
									'active' => array
									(
										'rule' => 'numeric',
										'message' => 'Este campo no puede estar vacío.'
									)
	);
}
?>