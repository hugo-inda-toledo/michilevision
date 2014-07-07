<?php

App::uses('AuthComponent', 'Controller/Component');


class User extends AppModel 
{
	var $name = 'User';
	 
	var $belongsTo   = array ('Management', 'Position', 'CreatedBy' => array('className' => 'User'), 'Headquarter', 'Setting');
	var $hasMany     = array ('CostCenterUser',
											'UserSystem',
											'UserPermission', 
											'UserProfile',
											'Sign',
											'Notification',
											'Headquarters' => array
											(
												'className' => 'Headquarter',
												'foreignKey' => 'user_id'
											),
															
											'UserReplacing' => array
											(
												'className' => 'Replacement',
												'foreignKey' => 'replacing_user_id'
											)
	);
	
	var $hasAndBelongsToMany = array(
															'CostCenter' => array(
																							'className'              => 'CostCenter',
																							'joinTable'              => 'cost_center_users',
																							'foreignKey'             => 'user_id',
																							'associationForeignKey'  => 'cost_center_id',
																							'unique'                 => true
																					),
															'System' => array(
																							'className'              => 'System',
																							'joinTable'              => 'user_systems',
																							'foreignKey'             => 'user_id',
																							'associationForeignKey'  => 'system_id',
																							'unique'                 => true
																					),
															'Permission' => array(
																							'className'              => 'Permission',
																							'joinTable'              => 'user_permissions',
																							'foreignKey'             => 'user_id',
																							'associationForeignKey'  => 'permission_id',
																							'unique'                 => true
																					),
															'Profile' => array(
																							'className'              => 'Profile',
																							'joinTable'              => 'user_profiles',
																							'foreignKey'             => 'user_id',
																							'associationForeignKey'  => 'profile_id',
																							'unique'                 => true
																					),
															'Address' => array(
																							'className'              => 'Address',
																							'joinTable'              => 'address_users',
																							'foreignKey'             => 'user_id',
																							'associationForeignKey'  => 'address_id',
																							'unique'                 => true
																					),
    );
	
	
			
	//Variable para la validacion de datos (not nulls)		
	var $validate = array('management_id' => array
										(
											'rule' => array('numeric', '<>', 0),
											'message' => 'Debes asociar una gerencia al usuario.'
										),
										
										'position_id' => array
										(
											'rule' => 'numeric',
											'message' => 'Este campo no puede estar vacío.'
										),
									
										'name' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
								
										'first_lastname' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
							
										'dni' => array(
											'rule' => array('custom','/^(\d{1,9})-((\d|k|K){1})$/'),
											'message' => 'Este RUT no es válido.'
										),
										
										'username' => array
										(
											'rule' => 'email',
											'message' => 'Por favor ingrese un E-Mail válido.'
										),
										
										'password' => array
										(
											'rule' => array('minLength', 8),
											'message' => 'Este campo debe tener al menos 8 caracteres.'
										),					
										'token' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										'birthday' => array
										(
											'rule' => 'date',
											'message' => 'Seleccione fecha de nacimiento.'
										),
										'email' => array
										(
											'rule' => 'email',
											'message' => 'Por favor ingrese un E-Mail válido.'
										),
										'created_by_id' => array
										(
											'rule' => 'numeric',
											'message' => 'Este campo no puede estar vacío.'
										)
	);
}
?>