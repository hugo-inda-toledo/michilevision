<?php
class System extends AppModel 
{
	//Se declara objeto Usuario
    var $name = 'System';
	
	//Pertenencias de acuerdo al modelo relacional
	var $hasMany = array ('CostCenterUser', 'Permission', 'Profile', 'UserSystem', 'SystemSection');
	
	var $belongsTo = array('CreatedBy' => array('className' => 'User'));

			
	//Variable para la validacion de datos (not nulls)		
	var $validate = array(
										'system_name' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacio"
										),
										'table_name' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacio"
										)
    );
}
?>