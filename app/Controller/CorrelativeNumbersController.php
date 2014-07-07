<?php
	class CorrelativeNumbersController extends AppController
	{
		var $name = 'CorrelativeNumbers';
		var $helpers = array('Ajax', 'Session', 'Html', 'Form','Time', 'Auth');
		var $uses = array('CorrelativeNumber', 'User', 'System', 'Management', 'CostCenter', 'Authorization', 'Badge');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		
		function getActualYear()
		{
			return date('Y');
		}
		
		function getCorrelativeNumber($quantity, $number)
		{
			$number = $number + 1;
			return sprintf("%0".$quantity."d", $number);
		}
		
		function generateCorrelativeNumber($system, $year, $key = null, $value = null)
		{
			$correlative='';
			
			if($key == null and $value == null)
			{
				$dataCorrelative = $this->CorrelativeNumber->find('first', array('conditions' => array('CorrelativeNumber.system_id' => $system)));
			}
			else
			{
				$dataCorrelative = $this->CorrelativeNumber->find('first', array('conditions' => array('CorrelativeNumber.system_id' => $system, 'CorrelativeNumber.'.$key => $value)));
			}
			
			$correlative .= $dataCorrelative['CorrelativeNumber']['initials'];
			$correlative .= $dataCorrelative['CorrelativeNumber']['year_month'];
			$dataCorrelative['CorrelativeNumber']['year_month'] = date('Y');
			$correlative .= $this->getCorrelativeNumber(4, $dataCorrelative['CorrelativeNumber']['last_assign']);
			
			$dataCorrelative['CorrelativeNumber']['last_assign'] = $dataCorrelative['CorrelativeNumber']['last_assign'] +1;
			
			if($this->CorrelativeNumber->save($dataCorrelative))
				return $correlative;
		}
	}	
?>