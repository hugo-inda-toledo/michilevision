<?php

class PositionsController extends AppController 
{
    var $name = 'Positions';
	var $helpers = array('Html');
    var $uses = array('User','Management','Position', 'CostCenter', 'Provider');
	
	
	function index() 
	{
        $this->set('cargos', $this->Position->find('all'));
    }
	
	
	
	
	function formatNames($string){
	$pieces = explode(' ', $string);
		
		for($p = 0; $p < count($pieces); $p++){
			if(strlen($pieces[$p]) > 3)
				$pieces[$p] = ucwords(mb_strtolower($pieces[$p]));
			
			if(strlen($pieces[$p]) <= 3)
				$pieces[$p] = mb_strtolower($pieces[$p]);	
		}
		
	$string = implode(' ', $pieces);
	return $string;
	}
	
	
	
	function normalizePositions(){
	$positions = $this->Position->find('all', array('order' => 'Position.id ASC'));
	$p = 0;
	
	/*echo '<pre>';
	print_r($positions);
	echo '</pre>';*/			  
		
		foreach ($positions as $position){
			$formatPosition = $this->formatNames($position['Position']['position']);
			$position['Position']['position'] = $formatPosition;
			
			$this->Position->save($position);
		}
	}
	
	function normalizeModel($model = 'CostCenter')
	{
		$dataModel = $this->$model->find('all', array('order' => $model.'.id ASC'));
	  
		
		foreach ($dataModel as $data)
		{
			$formatModelName = $this->formatNames($data[$model]['cost_center_name']);
			$data[$model]['cost_center_name'] = $formatModelName;
			
			$this->$model->save($data);
		}
	}
	
	function normalizeProviders(){
	$positions = $this->Provider->find('all', array('order' => 'Provider.id ASC'));
	$p = 0;
	
	/*echo '<pre>';
	print_r($positions);
	echo '</pre>';*/			  
		
		foreach ($positions as $position){
			$formatProviderName = $this->formatNames($position['Provider']['provider_name']);
			$formatProviderAdrress = $this->formatNames($position['Provider']['provider_address']);
			
			$position['Provider']['provider_name'] = $formatProviderName;
			$position['Provider']['provider_address'] = $formatProviderAdrress;
			
			$this->Provider->save($position);
		}
	}
	
	
	
	
	
	
	
	
	/*function view($id = null) 
	{
        $this->Usuario->id = $id;
		$this->set('gerencia', $this->Gerencia->find(array('Gerencia.id' => $id)));
		$this->set('cargo',     $this->Cargo->find(array('Cargo.id' => $id)));
        $this->set('usuario',   $this->Usuario->read());
    }
	
	function add() 
	{
	
	    $valuesA = $this->Gerencia->find('all');

		foreach ($valuesA as $value)
		{
			$resultados[$value['Gerencia']['id']]= $value['Gerencia']['nombre_gerencia']." ".$value['Gerencia']['codigo_gerencia'];
		}
		
		$this->set('selectGerencia',$resultados);
		
		$this->set('cargos', $this->Cargo->find('all'));
	
        if (!empty($this->data)) {
            if ($this->Usuario->save($this->data)) {
                $this->Session->setFlash('El usuario ha sido guardado.');
                $this->redirect(array('action' => 'index'));
            }
        }
    }
	
	function edit($id) 
	{
		$this->Usuario->id = $id;
		
		$valuesA = $this->Usuario->read();

		foreach ($valuesA as $value)
		{
			$resultados[$value['Gerencia']['id']]= $value['Gerencia']['nombre_gerencia']." ".$value['Gerencia']['codigo_gerencia'];
		}
		
		$this->set('selectGerencia',$resultados);
		$this->set('cargos', $this->Cargo->find('all'));
		
		if (empty($this->data)) 
		{	
			$this->set('usuario',$this->Usuario->find('all'));
			$this->data = $this->Usuario->read();
		} 
		else 
		{
			if ($this->Usuario->save($this->data)) 
			{
				$this->Session->setFlash('El usuario ha sido editado con exito.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function delete($id) 
	{
		if ($this->Usuario->delete($id))
		{
			$this->Session->setFlash('El usuario ha sido borrado de la base de datos.');
			$this->redirect(array('action'=>'index'));
		}
	}
		
	function muestraUsuarios()
	{
		$this->set('usuarios', $this->Usuario->find('all'));
	}*/
}
?>