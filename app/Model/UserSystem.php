<?php
class UserSystem extends AppModel 
{
	//Se declara objeto Usuario
    var $name = 'UserSystem';
	
	//Pertenencias de acuerdo al modelo relacional
	var $belongsTo   = array ('User',  'System', 'CreatedBy' => array('className' => 'User'));
			
	//Variable para la validacion de datos (not nulls)		
	var $validate = array(
										'user_id' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacio"
										),
										'system_id' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacio"
										)
    );
}
?>