<?php
class Profile extends AppModel 
{
	//Se declara objeto Usuario
    var $name = 'Profile';
	
	var $belongsTo = array ('System',  'CreatedBy' => array('className' => 'User'));
	
		
	var $hasAndBelongsToMany = array(
															'Permission' => array(
																							'className'              => 'Permission',
																							'joinTable'              => 'profile_permissions',
																							'foreignKey'             => 'profile_id',
																							'associationForeignKey'  => 'permission_id',
																							'unique'                 => true
																					)
														);
	var $validate = array(
						'profile_name' => array
						(
								'rule' => 'notEmpty',
								'message' => "Este campo no puede estar vacío"
						),
						'system_id' => array
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