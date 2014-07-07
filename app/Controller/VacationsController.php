<?php
class VacationsController extends AppController 
{
	//Declaracion de variables con llamada a helpers, clases externas
    var $name = 'Vacations';
	var $helpers = array('Session', 'Html', 'Form','Time');
	var $uses = array('User', 'Vacacion');
	var $scaffold;
	var $paginate = array();
	
	function showVacations()
	{
		$dni = $this->getDniFormat($this->Auth->user('dni'));
		$query = "exec dbo.sp_lista_vacaciones_devengadas_20120509 '".$dni."'; ";
		
		$data = $this->Vacacion->query($query);
		
		
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		
		/*
			use RRHH07;
			exec sp_lista_vacaciones_devengadas_20120509 '$dni'; 
		*/
	}
	
	function getDniFormat($dni)
	{
		$rutTmp = explode( "-", $dni );
		return number_format( $rutTmp[0], 0, "", ".") . '-' . $rutTmp[1];
	}
}
?>