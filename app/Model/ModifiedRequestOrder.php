<?php
class ModifiedRequestOrder extends AppModel 
{
    var $name = 'ModifiedRequestOrder';
	var $belongsTo  = array('PurchaseOrder', 'User');

	
	var $validate = array(
							'purchase_order_id' => array
								(
									'rule' => 'notEmpty',
									'message' => 'Este campo no puede estar vacío.'
								),
						
								'can_modify' => array
								(
									'rule' => 'notEmpty',
									'message' => 'Este campo no puede estar vacío.'
								),
						
								'hash_keypass' => array
								(
									'rule' => 'notEmpty',
									'message' => 'Este campo no puede estar vacío.'
								)
    );
}
?>