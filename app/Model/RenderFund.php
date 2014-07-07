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
											'message' => 'Este campo no puede estar vac�o.'
										),
										'dni_user' => array
										(
											'rule' => array('custom','/^(\d{1,9})-((\d|k|K){1})$/'),
											'message' => 'Este RUT no es v�lido.'
										),
									
										'used_by_name' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
								
										'used_by_dni' => array
										(
											'rule' => array('custom','/^(\d{1,9})-((\d|k|K){1})$/'),
											'message' => 'Este RUT no es v�lido.'
										),
									
										'management_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										
										'render_fund_title' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										
										'authorization_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
							
										'badge_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										
										'cost_center_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										
										'reason' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										
										'state_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										
										'approved' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										
										'deliver' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										
										'render' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										
										'total_price' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										
										'created' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
	);
}
?>