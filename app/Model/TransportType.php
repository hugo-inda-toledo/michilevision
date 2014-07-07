<?php
class TransportType extends AppModel 
{
    var $name = 'TransportType';
	
	var $hasMany = array('TransportRequest');
	
	var $validate = array(
        'transport_type' => array(
            'rule' => 'notEmpty'
        ),
		'capacity' => array(
            'rule' => 'notEmpty'
        )
    );
}
?>