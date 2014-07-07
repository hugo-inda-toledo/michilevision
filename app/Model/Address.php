<?php
class Address extends AppModel 
{
    var $name = 'Address';
	
	var $belongsTo = array('Region', 'Province', 'Commune');
	
	var $hasAndBelongsToMany = array(
																		'User' => array(
																										'className' => 'User',
																										'joinTable' => 'address_users',
																										'foreignKey' => 'address_id',
																										'associationForeignKey'  => 'user_id',
																										'unique'	=> true
																									)
																	);
	
	var $validate = array(
        'address_name' => array(
            'rule' => 'notEmpty'
        ),
		'number' => array(
            'rule' => 'notEmpty'
        ),
		'region_id' => array(
            'rule' => 'notEmpty'
        ),
		'province_id' => array(
            'rule' => 'notEmpty'
        ),
		'commune_id' => array(
            'rule' => 'notEmpty'
        ),
		'address_title' => array(
            'rule' => 'notEmpty'
        ),
    );
}
?>