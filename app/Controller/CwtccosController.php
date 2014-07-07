<?php
	class CwtccosController extends AppController
	{
		var $name = 'Cwtccos';
		var $helpers = array('Session', 'Html', 'Form','Time');
		//var $uses = array('Notification', 'User', 'System', 'Management', 'CostCenter', 'Headquarter', 'Replacement', 'Profile');
		var $uses = array('Cwtauxi');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		function index()
		{
			//$data = $this->Cwtcco->query("SELECT * FROM softland.cwtccos;");
			$data = $this->Cwtauxi->query("SELECT codaux,rutaux,replace(nomaux,'\t',' ') nomaux,diraux,dirnum,ciuaux,comaux,paiaux FROM softland.cwtauxi where ActAux='S' and ClaPro='S' and RutAux <> '0-0'");
			echo "<pre>";
			print_r($data);
			echo "</pre>";
		}
	}
?>