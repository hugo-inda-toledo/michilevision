<?php
class ProviderRequest extends AppModel 
{
	var $name = 'ProviderRequest';
	 
	var $belongsTo   = array ('applicant' => array('className' => 'User'));
	
	var $validate = array('applicant_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										'provider_name' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
									
										'provider_address' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
								
										'provider_dni' => array
										(
											'rule' => array('custom','/^(\d{1,9})-((\d|k|K){1})$/'),
											'message' => 'Este RUT no es válido.'
										)
	);
 }
 ?>