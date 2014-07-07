<?php
class RenderFundRequest extends AppModel 
{
	var $name = 'RenderFundRequest';
	 
	var $belongsTo   = array ('RenderFund');

	
			
	//Variable para la validacion de datos (not nulls)		
	var $validate = array	('render_fund_id' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
										'description' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										),
									
										'price' => array
										(
											'rule' => 'notEmpty',
											'message' => 'Este campo no puede estar vacío.'
										)
	);

}
?>