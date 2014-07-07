<?php
class TransportCompany extends AppModel 
{
    var $name = 'TransportCompany';
	
	var $hasMany = array('TransportRequest');
	var $belongsTo = array('TransportType');
	
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