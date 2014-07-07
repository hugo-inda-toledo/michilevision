<?php

class Position extends AppModel 
{
    var $name = 'Position';
	
	var $hasOne = array ('User');
	
	
	/*var $validate = array(
        'id_gerencia' => array(
            'rule' => 'notEmpty'
        ),
        'id_cargo' => array(
            'rule' => 'notEmpty'
        ),
		'primer_nombre' => array(
            'rule' => 'notEmpty'
        ),
		'primer_apellido' => array(
            'rule' => 'notEmpty'
        ),
		'segundo_apellido' => array(
            'rule' => 'notEmpty'
        ),
		'rut' => array(
            'rule' => 'notEmpty'
        ),
		'fecha_naciminiento' => array(
            'rule' => 'notEmpty'
        ),
		'email' => array(
            'rule' => 'notEmpty'
        ),
		'planta' => array(
            'rule' => 'notEmpty'
        ),
		'activo' => array(
            'rule' => 'notEmpty'
        )
    );*/
}
?>