<?php
class Province extends AppModel 
{
    var $name = 'Province';

	var $belongsTo = array('Region');
	var $hasMany = array('Commune');
	
	var $validate = array(
									'region_id' => array
									(
										'rule' => 'notEmpty',
										'message' => 'Este campo no puede estar vacío.'
									),
									'province_name' => array
									(
										'rule' => 'notEmpty',
										'message' => 'Este campo no puede estar vacío.'
									)
	);
}
?>