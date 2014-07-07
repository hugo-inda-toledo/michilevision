<?php
class UserProfile extends AppModel 
{
	//Se declara objeto Usuario
    var $name = 'UserProfile';
	
	//Pertenencias de acuerdo al modelo relacional
	var $belongsTo   = array ('User',  'Profile', 'CreatedBy' => array('className' => 'User'), 'System');
			
	//Variable para la validacion de datos (not nulls)		
	var $validate = array(
										'user_id' => array
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
	
	var $inserted_ids = array();
		
	function afterSave($created) 
	{
		if($created) 
		{
			$this->inserted_ids[] = $this->getInsertID();
		}
		return true;
	}
}
?>