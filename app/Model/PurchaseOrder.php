<?php
class PurchaseOrder extends AppModel 
{
	var $name = 'PurchaseOrder';
	 
	var $belongsTo   = array ('User', 'Management', 'Authorization', 'Badge', 'CostCenter', 'State', 'management_user' => array('className' => 'User'));
	var $hasMany     = array ('PurchaseOrderRequest', 'Budget', 'Sign' => array('className' => 'Sign', 'foreignKey' => 'relation_id', 'conditions' => array('Sign.system_id' => 2), 'order' => 'Sign.level ASC'), 'ModifiedRequestOrder', 'ApprovedOrder');
	
	var $validate = array('user_id' => array
										(
											'rule' => 'numeric',
											'message' => 'Este campo no puede estar vacío.'
										),
										'management_id' => array
										(
											'rule' => 'numeric',
											'message' => 'Este campo no puede estar vacío.'
										),
									
										'management_user_id' => array
										(
											'rule' => 'numeric',
											'message' => 'Este campo no puede estar vacío.'
										),
								
										'dni_user' => array
										(
											'rule' => array('custom','/^(\d{1,9})-((\d|k|K){1})$/'),
											'message' => 'Este RUT no es válido.'
										),
										
										'dni_management_user' => array
										(
											'rule' => array('custom','/^(\d{1,9})-((\d|k|K){1})$/'),
											'message' => 'Este RUT no es válido.'
										),
									
										'cost_center_id' => array
										(
											'rule' => 'numeric',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'invoice_to' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'purchase_type' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
							
										'only_provider' => array
										(
											'rule' => 'numeric',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'budgeted' => array
										(
											'rule' => 'numeric',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'reason' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'authorization_id' => array
										(
											'rule' => 'numeric',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'badge_id' => array
										(
											'rule' => 'numeric',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'approved' => array
										(
											'rule' => 'numeric',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'state_id' => array
										(
											'rule' => 'numeric',
											'message' => 'Este campo no puede estar vacío.'
										)
	);
}
?>