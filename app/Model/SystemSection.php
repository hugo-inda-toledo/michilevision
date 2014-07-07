<?php
class SystemSection extends AppModel 
{
    var $name = 'SystemSection';
	
	var $belongsTo = array('System');
	
	var $validate = array(
        'system_id' => array(
            'rule' => 'numeric'
        ),
		'section_name' => array(
            'rule' => 'notEmpty'
        ),
		'section_function' => array(
            'rule' => 'notEmpty'
        )
    );
}
?>