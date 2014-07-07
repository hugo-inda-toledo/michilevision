<?php
class Region extends AppModel 
{
    var $name = 'Region';

	var $hasMany = array('Province');
	
	var $validate = array(
									'region_name' => array
									(
										'rule' => 'notEmpty',
										'message' => 'Este campo no puede estar vacío.'
									)
	);
}
?>