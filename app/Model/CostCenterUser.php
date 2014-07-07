<?php

class CostCenterUser extends AppModel 
{
    var $name = 'CostCenterUser';
	
	var $belongsTo = array('User','CostCenter', 'System',  'CreatedBy' => array('className' => 'User'));
	
	var $validate = array(
			'user_id' => array(
	            'rule' => 'notEmpty',
	            'message' => 'Este campo no puede estar vacío.'
	        ),
		 	'system_id' => array(
	            'rule' => 'notEmpty',
	            'message' => 'Este campo no puede estar vacío.'
	        ),
		 	'cost_center_id' => array(
	            'rule' => 'notEmpty',
	            'message' => 'Este campo no puede estar vacío.'
	        ),
			
			'created_by_id' => array(
	            'rule' => 'notEmpty',
	            'message' => 'Este campo no puede estar vacío.'
	        ),
			'created' => array(
	            'rule' => 'notEmpty',
	            'message' => 'Este campo no puede estar vacío.'
	        )
	    );
		
	var $inserted_ids = array();
		
	function afterSave($created) 
	{
		if($created) 
		{
			$this->inserted_ids[] = $this->getInsertID();
		}
		return true;
	}
}
?>