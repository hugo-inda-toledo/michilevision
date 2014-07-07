<?php
class CostCentersController extends AppController 
{
    var $name = 'CostCenters';
	var $helpers = array('Session','Html','Form','Time');
	var $uses = array('CostCenter', 'User', 'CostCenterUser', 'Management');
	var $components = array('Session');
	var $scaffold;
	var $paginate = array();
    
	
	function index() 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->paginate = array('CostCenter' => array('limit' => 30, 'order' => array('CostCenter.cost_center_code' => 'ASC'), 'conditions' => array(/*'CostCenter.level' => 3, */'CostCenter.valid' => 1, 'CostCenter.cost_center_name <>' => '.')));
			$dataCostCenters = $this->paginate();
			
			$this->set('cost_centers', $dataCostCenters);
			$this->set('title_for_layout', 'Centros de costo');
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
    }
	
	
		
	function view($id = null) 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->CostCenter->id = $id;
			$dataCostCenter = $this->CostCenter->read();
			
			if($dataCostCenter['CostCenter']['valid'] == 1)
				$dataCostCenter['CostCenter']['valid'] = "Si";
			else
				$dataCostCenter['CostCenter']['valid'] = "No";

			$this->set('cost_center', $dataCostCenter);
			$this->set('title_for_layout', $dataCostCenter['CostCenter']['cost_center_name']);
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
    }
	
	
	
	function add() 
	{
		if($this->Auth->user('admin') == 1)
		{
			$dataManagements = $this->RequestAction('/external_functions/showManagements');
			$this->set('selectManagements', $dataManagements);
			$this->set('title_for_layout', 'Nuevo Centro de costo');
			
			if (!empty($this->data)) 
			{
				$dataCostCenter = $this->data;
				
				$dataCostCenter['CostCenter']['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');
				
				$this->CostCenter->begin();
				
				if ($this->CostCenter->save($dataCostCenter)) 
				{
					$this->CostCenter->commit();
					
					$this->Session->setFlash('Se ha creado correctamente un nuevo centro de costos.', 'flash_success');
					$this->redirect(array('action' => 'index'));
				}
				
				$this->CostCenter->rollback();
			}
		}
		else
			{
				$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
    }
	
	
	
	function edit($id) 
	{
		if($this->Auth->user('admin') == 1)
		{
			//Obtengo el id a consultar
			$this->CostCenter->id = $id;
			$dataManagements = $this->RequestAction('/external_functions/showManagements');
			$this->set('selectManagements', $dataManagements);
			//Si no hay informacion
			if (empty($this->data)) 
			{	
				//Extraigo la info del centro de costo en cuestion para setiar datos existentes
				$this->data = $this->CostCenter->read();
				$dataCostCenter =$this->data;
				
				
				//Seteo la vigencia del centro de costo para el select de vigencia
				if($dataCostCenter['CostCenter']['valid'] == 1)
					$dataCostCenter['CostCenter']['valid'] = "Si";
				else
					$dataCostCenter['CostCenter']['valid'] = "No";
				
				$this->set('cost_center', $dataCostCenter);
				$this->set('title_for_layout', 'Editando '.$dataCostCenter['CostCenter']['cost_center_name']);
				
			} 
			//Si hay infomacion (pasado por post)
			else 
			{
				$dataCostCenter = $this->request->data;
				
				$this->CostCenter->begin();
				
				if ($this->CostCenter->save($dataCostCenter)) 
				{
					$this->CostCenter->commit();
					$this->Session->setFlash('Se ha editado correctamente el centro de costos seleccionado.', 'flash_success');
					$this->redirect(array('action' => 'index'));
				}
				
				$this->CostCenter->rollback();
			}
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	function delete($id) 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->CostCenter->begin();
			
			if ($this->CostCenter->delete($id))
			{
				$this->CostCenter->commit();
				
				$this->Session->setFlash('Se ha eliminado correctamente el centro de costos seleccionado.', 'flash_success');
				$this->redirect(array('action'=>'index'));
			}
			
			$this->CostCenter->rollback();
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	function find()
	{
		if($this->Auth->user('admin') == 1)
		{
			if(!empty($this->data))
			{
				$this->paginate = array('CostCenter' => array('limit' => 1000, 'order' => array('CostCenter.'.$this->data['CostCenter']['cost_center_type_search'] => 'ASC'), 'conditions' => array('CostCenter.'.$this->data['CostCenter']['cost_center_type_search'].' LIKE' => '%'.$this->data['CostCenter']['cost_center_param'].'%', 'CostCenter.level' => 3, 'CostCenter.valid' => 1, 'CostCenter.cost_center_name <>' => '.')));
				$dataCostCenters = $this->paginate();

				$this->set('cost_centers', $dataCostCenters);
				$this->set('dataSearch', $this->data);
			}
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
	
	function setCostCenters()
	{
		$costCenters = $this->CostCenter->find('all');
		
		foreach($costCenters as $costCenter)
		{
			$costCenter['CostCenter']['cost_center_name'] = $this->RequestAction('/external_functions/formatNames/'.$costCenter['CostCenter']['cost_center_name']);
			
			$this->CostCenter->save($costCenter);
		}
	}
}

?>