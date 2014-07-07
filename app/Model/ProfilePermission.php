<?php
class ProfilePermission extends AppModel 
{
	//Se declara objeto Usuario
    var $name = 'ProfilePermission';
	
	//Pertenencias de acuerdo al modelo relacional
	var $belongsTo   = array ('Profile', 'Permission',  'CreatedBy' => array('className' => 'User'));
			
	//Variable para la validacion de datos (not nulls)		
	var $validate = array(
										'permission_id' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacio"
										),
										'profile_id' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacio"
										),
										'created_by_id' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacio"
										),
										'created' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacio"
										)
    );
}
?>