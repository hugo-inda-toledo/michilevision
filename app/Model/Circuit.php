<?php
class Circuit extends AppModel 
{
	var $name = 'Circuit';
	 
	var $belongsTo   = array ('User', 'Authorization', 'System',  'CreatedBy' => array('className' => 'User'));
			
	//Variable para la validacion de datos (not nulls)		
	var $validate = array	('user_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'authorization_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'system_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'level' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
							
										'position' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'created_by' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										
										'created' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										)
								);
}
?>