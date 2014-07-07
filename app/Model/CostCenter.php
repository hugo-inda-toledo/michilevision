<?php

class CostCenter extends AppModel 
{
    var $name = 'CostCenter';
	var $belongsTo  = array('Management', 'CreatedBy' => array('className' => 'User'));

	
	var $validate = array(
							'cost_center_name' => array
								(
									'rule' => 'notEmpty',
									'message' => 'Este campo no puede estar vacío.'
								),
						
								'cost_center_code' => array
								(
									'rule' => 'notEmpty',
									'message' => 'Este campo no puede estar vacío.'
								),
						
								'level' => array
								(
									'rule' => 'notEmpty',
									'message' => 'Este campo no puede estar vacío.'
								),
						
								'valid' => array
								(
									'rule' => 'notEmpty',
									'message' => 'Este campo no puede estar vacío.'
								)
    );
	
	var $paginate = array('limit' =>  15, 'order' => array('CostCenter.created' => 'desc'));
}
?>