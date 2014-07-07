<?php
	class AttributeTablesController extends AppController
	{
		var $name = 'AttributeTables';
		var $helpers = array('Ajax', 'Session', 'Html', 'Form','Time', 'Auth');
		var $uses = array('AttributeTable', 'User', 'System', 'Management', 'CostCenter', 'Authorization', 'Badge');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		
		
		function validateTreasurer()
		{
			$treasureId = $this->AttributeTable->find('first', array('conditions' => array('AttributeTable.key' => 'treasurer_id')));
			return $treasureId['AttributeTable']['value'];
		}
	}	
?>