<?php
class UserPermission extends AppModel 
{
	//Se declara objeto Usuario
    var $name = 'UserPermission';
	
	//Pertenencias de acuerdo al modelo relacional
	var $belongsTo   = array ('User',  'Permission', 'CreatedBy' => array('className' => 'User'), 'System', 'Replacement');
	var $hasMany = array('ProfilePermission' => array('className' => 'ProfilePermission', 'foreignKey' => 'permission_id'));
			
	//Variable para la validacion de datos (not nulls)		
	var $validate = array(
										'user_id' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacio"
										),
										'permission_id' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacio"
										),
										'start_date' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacio"
										),
										'end_date' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacio"
										)
    );
}
?>