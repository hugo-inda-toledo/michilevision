<?php

class TypeReplacement extends AppModel 
{
    var $name = 'TypeReplacement';
	var $hasOne = array ('Replacement');
	
	/*var $validate = array(
        'id_usuario_gerente' => array(
            'rule' => 'notEmpty'
        ),
        'id_usuario_supervisor' => array(
            'rule' => 'notEmpty'
        ),
		'id_autorizacion' => array(
            'rule' => 'notEmpty'
        ),
		'codigo_gerencia' => array(
            'rule' => 'notEmpty'
        ),
		'nombre_gerencia' => array(
            'rule' => 'notEmpty'
        ),
		'created_by_id' => array(
            'rule' => 'notEmpty'
        ),
		'created' => array(
            'rule' => 'notEmpty'
        )
    );*/
}
?>