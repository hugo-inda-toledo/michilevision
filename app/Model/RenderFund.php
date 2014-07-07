<?php
class RenderFund extends AppModel 
{
	var $name = 'RenderFund';
	 
	var $belongsTo   = array ('User', 'Management', 'Authorization', 'Badge', 'CostCenter', 'State');
	var $hasMany     = array ('RenderFundRequest', 'RenderFundFile', 'Sign' => array('className' => 'Sign', 'foreignKey' => 'relation_id', 'conditions' => array('Sign.system_id' => 1), 'order' => 'Sign.level ASC'));


	//Variable para la validacion de datos (not nulls)		
	var $validate = array	('user_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										'dni_user' => array
										(
											'rule' => array('custom','/^(\d{1,9})-((\d|k|K){1})$/'),
											'message' => 'Este RUT no es válido.'
										),
									
										'used_by_name' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
								
										'used_by_dni' => array
										(
											'rule' => array('custom','/^(\d{1,9})-((\d|k|K){1})$/'),
											'message' => 'Este RUT no es válido.'
										),
									
										'management_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'render_fund_title' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'authorization_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
							
										'badge_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'cost_center_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'reason' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'state_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'approved' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'deliver' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'render' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'total_price' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'created' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
	);
}
?>