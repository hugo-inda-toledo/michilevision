<?php
class Commune extends AppModel 
{
    var $name = 'Commune';

	var $belongsTo = array('Province', 'Region');
	
	var $validate = array(
									'region_id' => array
									(
										'rule' => 'notEmpty',
										'message' => 'Este campo no puede estar vacío.'
									),
									'province_id' => array
									(
										'rule' => 'notEmpty',
										'message' => 'Este campo no puede estar vacío.'
									),
									'commune_name' => array
									(
										'rule' => 'notEmpty',
										'message' => 'Este campo no puede estar vacío.'
									)
	);
}
?>