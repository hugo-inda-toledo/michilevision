<?php

class Headquarter extends AppModel 
{
    var $name = 'Headquarter';
	var $belongsTo  = array('User', 'Management',  'CreatedBy' => array('className' => 'User'));

		
	var $validate = array(
									'user_id' => array(
										'rule' => 'notEmpty',
										'required' => true,
										'message' => 'Este campo no puede estar vacío.'
									),
									
									'management_id' => array(
										'rule' => 'notEmpty',
										'required' => true,
										'message' => 'Este campo no puede estar vacío.'
									),
								
									'headquarter_name' => array(
										'rule' => 'notEmpty',
										'required' => true,
										'message' => 'Este campo no puede estar vacío.'
									),
								
									'cost_center_code' => array(
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