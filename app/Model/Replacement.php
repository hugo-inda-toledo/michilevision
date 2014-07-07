<?php

class Replacement extends AppModel 
{
    var $name = 'Replacement';
	
	var $belongsTo = array( 'TypeReplacement',	'Management',
							'replacing_user' => array('className' => 'User'),
							'replaced_user' => array('className' => 'User'),
							 'CreatedBy' => array('className' => 'User')
						);
	

	
	var $validate = array(
        'type_replacement_id' => array(
            'rule' => 'notEmpty'
        ),
		'replacing_user_id' => array(
            'rule' => 'notEmpty'
        ),
		'replaced_user_id' => array(
            'rule' => 'notEmpty'
        ),
		'start_date' => array(
            'rule' => 'notEmpty'
        ),
		'end_date' => array(
            'rule' => 'notEmpty'
        ),
		'active' => array(
            'rule' => 'notEmpty'
        ),
		'created_by_id' => array(
            'rule' => 'notEmpty'
        ),
		'created' => array(
            'rule' => 'notEmpty'
        )
    );
}
?>