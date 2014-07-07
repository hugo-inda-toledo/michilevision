<?php
class Authorization extends AppModel 
{
    var $name = 'Authorization';

	var $belongsTo = array('CreatedBy' => array('className' => 'User'));
	
	var $validate = array(
									'name' => array
									(
										'rule' => 'notEmpty',
										'message' => 'Este campo no puede estar vacío.'
									),
									'active' => array
									(
										'rule' => 'notEmpty',
										'message' => 'Este campo no puede estar vacío.'
									)
	);
}
?>