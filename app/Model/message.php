<?php
class Message extends AppModel 
{
	var $name = 'Message';
	 
	var $hasMany   = array ('Notification');
			
	//Variable para la validacion de datos (not nulls)		
	/*var $validate = array	('user_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										
										'authorization_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										
										'system_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										
										'level' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
							
										'position' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										
										'created_by' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										
										'created' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										)
								);*/
}
?>