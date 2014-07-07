<?php

class Management extends AppModel 
{
    var $name = 'Management';
	var $belongsTo  = array('User', 'Authorization', 'CreatedBy' => array('className' => 'User'));
	var $hasMany    = array('Replacement', 'CostCenter');
	
	var $validate = array(
        'user_id' => array(
            'rule' => 'notEmpty',
            'message' => 'Este campo no puede estar vacío.'
        ),
		'authorization_id' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'Este campo no puede estar vacío.'
        ),
		'management_name' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'Este campo no puede estar vacío.'
        ),
		'cost_center_father_code' => array(
            'rule' => 'notEmpty',
            'required' => true,
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

}
?>