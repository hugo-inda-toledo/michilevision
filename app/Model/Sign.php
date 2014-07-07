<?php
class Sign extends AppModel 
{
	var $name = 'Sign';
	 
	//var $hasMany     = array ('System', 'User');
	
	 var $belongsTo = array(
										/*'PaymentRequestRelation' => array('className' => 'PaymentRequest', 'foreignKey' => 'relation_id'),
										'HumanResourceRelation' => array('className' => 'HumanResource', 'foreignKey' => 'relation_id'),
										'OperationRelation' => array('className' => 'Operation', 'foreignKey' => 'relation_id'),
										'PromotionRelation' => array('className' => 'Promotion', 'foreignKey' => 'relation_id'),
										'FilmDemoRelation' => array('className' => 'FilmDemo', 'foreignKey' => 'relation_id'),
										'ServiceContractRelation' => array('className' => 'ServiceContract', 'foreignKey' => 'relation_id'),*/
										'TransportRequestRelation' => array('className' => 'TransportRequest', 'foreignKey' => 'relation_id'),
										'RenderFundRelation' => array('className' => 'RenderFund', 'foreignKey' => 'relation_id'),
										'PurchaseOrderRelation' => array('className' => 'PurchaseOrder', 'foreignKey' => 'relation_id'),
										'System', 'User', 'State'
	 
	 );
	
}
?>