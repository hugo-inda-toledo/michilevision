<?php
class Vacacion extends AppModel 
{
	var $name = 'Vacacion';
	var $useTable = 'vacaciones'; 
	var $useDbConfig   = 'rrhh06';
	
	var $actsAs = array('StoredProcedure');

    function runStoredProc($stringParam, $intParam, &$outIntParam) {
        $inParams = array(
            'string_param'     => $stringParam,
            'int_param'     => $intParam
        );
        
        // place holder array, it is returned as $output['params'] with the type e.g. SQLINT4 as the value
        $outParams = array('out_int_param' => SQLINT4);
                
        $output = $this->executeMssqlSp('run_validation_algorithms_sp', $inParams, $outParams);
        
        $outIntParam = $output['params']['out_int_param'];
        
        return $output['result'];        
    }
}
?>