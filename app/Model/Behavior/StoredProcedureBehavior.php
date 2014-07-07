<?php
class StoredProcedureBehavior extends ModelBehavior {

/**
 * execute a stored proc. Call executeMssqlSp to not confuse with the cake execute
 * Taken initially from: http://planetcakephp.org/aggregator/items/4390-cakephp-calling-oracle-stored-procedures-and-functions 
 *
 * @param object $Model instance of model
 * @param string $name stored procedure name e.g. run_validation_algorithms_sp
 * @param array $inParams name=>value array of IN params, $type is assigned based on the php variable type
 * @param array $outParams name=>type array of OUTPUT params, 
 *                            e.g. array('algorithm_id' => SQLINT4, 'RETVAL' => SQLVARCHAR).
 *                            $type: SQLTEXT, SQLVARCHAR, SQLCHAR, SQLINT1, SQLINT2, SQLINT4, SQLBIT, SQLFLT4, SQLFLT8, SQLFLTN
 *@return array $output = array('results' => array(), 'params' => $outParams), $outParams has type replaced with the output value
 */
    function executeMssqlSp(&$Model, $name, $inParams, $outParams = array()) {
        // every model has a datasource
        $dataSource = $Model->getDataSource();
        // and every datasource has a database connection handle
        $connection = $dataSource->connection;
        if (!$connection)
            return -1;

        // Create a new statement
        $statement = mssql_init($name, $connection);
        
        // IN 
        foreach($inParams as $paramName => $paramValue) {
            if (is_string($paramValue)) { // date also
                $type = SQLVARCHAR;
            } else if (is_int($paramValue)) {
                $type = SQLINT4; /** @todo not tested */
            } else if (is_float($paramValue)) {
                $type = SQLFLT4; /** @todo not tested */
            } else if (is_bool($paramValue)) {
                $type = SQLBIT; /** @todo not tested */
            }
            
            // http://www.php.net/manual/en/function.mssql-bind.php
            // bool mssql_bind ( resource $stmt , string $param_name , mixed &$var , int $type [, bool $is_output = false [, bool $is_null = false [, int $maxlen = -1 ]]] )
            mssql_bind($statement, '@'.$paramName, $paramValue, $type);
        }
        
        // OUT
        foreach($outParams as $paramName => $type) {
            // use what was the param type as the return place holder
            mssql_bind($statement, '@'.$paramName, $outParams[$paramName], $type, $is_output = true);
        }
        
        // Execute the statement
        $result = mssql_execute($statement);

        if (is_resource($result)) {
            $output['result'] = $result;
            // have to do this to get OUTPUT parameters out
            mssql_next_result($result);
        } else {
            //Debugger::log('SQL error: '.mssql_get_last_message());
        }
        
        $output['params'] = $outParams;
        
        // Free the resource
        mssql_free_statement($statement);
        
        // return $outParamValue;
        return $output;
    }
}
?>