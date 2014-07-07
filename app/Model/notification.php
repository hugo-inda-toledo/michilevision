<?php
class Notification extends AppModel 
{
	var $name = 'Notification';
	 
	var $belongsTo   = array ('User', 'System',  'Message', 'Replacement', 'CostCenter', 'Profile', 'Management', 'Headquarter',
										
										'RenderFundRelation' => array('className' => 'RenderFund', 'foreignKey' => 'relation_id'),
										'PurchaseOrderRelation' => array('className' => 'PurchaseOrder', 'foreignKey' => 'relation_id')/*,
										'PaymentRequestRelation' => array('className' => 'PaymentRequest', 'foreignKey' => 'relation_id'),
										'ServiceContractRelation' => array('className' => 'ServiceContract', 'foreignKey' => 'relation_id'),
										'HumanResourceRelation' => array('className' => 'HumanResource', 'foreignKey' => 'relation_id'),
										'OperationRelation' => array('className' => 'Operation', 'foreignKey' => 'relation_id'),
										'TaxiRequestRelation' => array('className' => 'TaxiRequest', 'foreignKey' => 'relation_id'),
										'PromotionRelation' => array('className' => 'Promotion', 'foreignKey' => 'relation_id'),
										'FilmDemoRelation' => array('className' => 'FilmDemo', 'foreignKey' => 'relation_id'),*/
										
											
										);
			
	//Variable para la validacion de datos (not nulls)		
	/*var $validate = array	('user_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'authorization_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'system_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'level' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
							
										'position' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'created_by' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'created' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										)
								);*/
}
?>