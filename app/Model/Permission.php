<?php
class Permission extends AppModel 
{
	//Se declara objeto Usuario
    var $name = 'Permission';
	
	var $belongsTo = array ('System', 'CreatedBy' => array('className' => 'User'));
	
	
	//Variable para la validacion de datos (not nulls)		
	var $validate = array(
										'system_id' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacío"
										),
										'type_permission' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacío"
										),
										'level' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacío"
										),
										'created_by_id' => array
										(
											'rule' => 'notEmpty',
											'message' => "Este campo no puede estar vacío"
										)
    );
}
?>