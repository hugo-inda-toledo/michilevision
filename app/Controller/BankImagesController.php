<?php
	class BankImagesController extends AppController
	{
		var $name = 'BankImages';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('RenderFund','Management','User', 'CostCenterUser', 'CostCenter', 'Authorization', 'Badge', 'RenderFundRequest', 'State', 'Sign', 'CorrelativeNumber', 'AttributeTable', 'RenderFundFile');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		
		/*********************************************************************************/
		/*********************************************************************************/
		/******************Menu principal de fondos por rendir***********************/
		/*********************************************************************************/
		/*********************************************************************************/
		
		function mainMenu() 
		{
			$this->set('title_for_layout', 'Fondos por rendir :: Menu Principal');
		}
	}
?>