<?php
	class BudgetsController extends AppController
	{
		var $name = 'Budgets';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('PurchaseOrder',' Budget');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		function selectingBudget()
		{
			if(!empty($this->request->data))
			{
				echo "<pre>";
				print_r($this->request->data);
				echo "</pre>";
			}
		}
	}
?>