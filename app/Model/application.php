<?php
class Application extends AppModel 
{
	var $name = 'Application';
	 
	var $belongsTo   = array ('User', 'System', 'Management', 'CostCenter', 'Authorization', 'Badge');
	
	//'CreatedBy' => array('className' => 'User')
	/*var $hasMany     = array ('CostCenterUser',
											'UserSystem',
											'UserPermission', 
											'UserProfile',
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
	);*/
	
	
			
	//Variable para la validacion de datos (not nulls)		
	/*var $validate = array	('management_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										'position_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
									
										'name' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
								
										'first_lastname' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
									
										'second_lastname' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
							
										'dni' => array(
											'rule' => array('custom','/^(\d{1,9})-((\d|k|K){1})$/'),
											'message' => 'Este RUT no es v�lido.'
										),
										'username' => array
										(
											'alphanumeric' => array
											(
												'rule' => 'alphaNumeric',
												'message' => 'S�lo se permiten letras y n�meros'
											),
											'between' => array
											(
												'rule' => array
												(
													'between',5,15
												),
												'message' => 'El nombre de usuario debe tener entre 5 y 15 caracteres.'
											),
										),
										'password' => array
										(
											'rule' => array('minLength', 8),
											'message' => 'Este campo debe tener al menos 8 caracteres.'
										),					
										'token' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										),
										'birthday' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Seleccione fecha de nacimiento.'
										),
										'email' => array
										(
											'rule' => 'email',
											'message' => 'Por favor ingrese un E-Mail v�lido.'
										),
										'created_by_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vac�o.'
										)
	);*/

}
?>