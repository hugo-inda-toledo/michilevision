<?php
	class SqlserverLinksController extends AppController
	{
		var $name = 'SqlserverLinks';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('Provider', 'CostCenter', 'Cwtauxi', 'SoftlandProvider', 'Cwtcco');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		function index()
		{
			if($this->Auth->user('admin') == 1)
			{
				
			}
			else
			{
				$this->Session->setFlash('¿Que estas intentando hacer?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function importProvidersFromSoftland()
		{
			if($this->Auth->user('admin') == 1)
			{
				$dataToMySql = array();
				$dataToSqlServer = array();
				
				$dataToSqlServer = $this->Cwtauxi->find('all', array('fields' => array('Cwtauxi.codaux', 'Cwtauxi.rutaux', 'Cwtauxi.nomaux', 'Cwtauxi.diraux', 'Cwtauxi.dirnum', 'Cwtauxi.ciuaux', 'Cwtauxi.comaux', 'Cwtauxi.paiaux',  'Cwtauxi.EMail'), 'conditions' => array('Cwtauxi.ActAux' => 'S', 'Cwtauxi.ClaPro' => 'S', 'Cwtauxi.RutAux <>' => "0-0")));
				
				$softlandProviders = $this->Provider->find('all');
				
				$x=0;
				
				foreach($dataToSqlServer as $dataSqlserver)
				{
					if($softlandProviders != false)
					{
						foreach($softlandProviders as $dataMysql)
						{
							if($dataMysql['Provider']['auxiliar_provider_code'] != $dataSqlserver['Cwtauxi']['codaux'])
							{
									$dataToMySql['Provider'][$x]['auxiliar_provider_code'] = $dataSqlserver['Cwtauxi']['codaux'];
									$dataToMySql['Provider'][$x]['provider_dni'] = $dataSqlserver['Cwtauxi']['rutaux'];
									$dataToMySql['Provider'][$x]['provider_name'] = $this->formatNames($dataSqlserver['Cwtauxi']['nomaux']);
									$dataToMySql['Provider'][$x]['provider_address'] = $this->formatNames($dataSqlserver['Cwtauxi']['diraux'].' '.$dataSqlserver['Cwtauxi']['dirnum'].' '.$dataSqlserver['Cwtauxi']['ciuaux'].' '.$dataSqlserver['Cwtauxi']['comaux'].' '.$dataSqlserver['Cwtauxi']['paiaux']);
									$dataToMySql['Provider'][$x]['provider_email'] = $dataSqlserver['Cwtauxi']['EMail'];
								$x++;
							}
						}
					}
					else
					{
						$dataToMySql['Provider'][$x]['auxiliar_provider_code'] = $dataSqlserver['Cwtauxi']['codaux'];
						$dataToMySql['Provider'][$x]['provider_dni'] = $dataSqlserver['Cwtauxi']['rutaux'];
						$dataToMySql['Provider'][$x]['provider_name'] = $this->formatNames($dataSqlserver['Cwtauxi']['nomaux']);
						$dataToMySql['Provider'][$x]['provider_address'] = $this->formatNames($dataSqlserver['Cwtauxi']['diraux'].' '.$dataSqlserver['Cwtauxi']['dirnum'].' '.$dataSqlserver['Cwtauxi']['ciuaux'].' '.$dataSqlserver['Cwtauxi']['comaux'].' '.$dataSqlserver['Cwtauxi']['paiaux']);
						$dataToMySql['Provider'][$x]['provider_email'] = $dataSqlserver['Cwtauxi']['EMail'];
						$x++;
					}
				}
				
				Cache::write('SoftlandProvider', $dataToMySql);
				
				$this->Provider->begin();
				
				if($this->Provider->saveAll($dataToMySql['Provider']))
				{
					$this->Provider->commit();
					$this->Session->setFlash('Importación completa.', 'flash_success');
				}
				else
				{
					$this->Provider->rollback();
					$this->Session->setFlash('Error al importar proveedores, intentalo nuevamente', 'flash_alert');
					$this->redirect(array('action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash('¿Que estas intentando hacer?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function importCostCentersFromSoftland()
		{
			if($this->Auth->user('admin') == 1)
			{
				$dataToSave = array();
				$x=0;
				$valid;
				
				ini_set('memory_limit', '20000M');

				$mysqlCostCenters = $this->CostCenter->find('all', array('fields' => array('CostCenter.cost_center_code', 'CostCenter.cost_center_name', 'CostCenter.level', 'CostCenter.valid')));
				$costCentersSoftland = $this->Cwtcco->find('all', array('fields' => array('Cwtcco.CodiCC', 'Cwtcco.DescCC', 'Cwtcco.NivelCC', 'Cwtcco.Activo')));
				
				foreach($costCentersSoftland as $sqlserver)
				{
					$toMysql = $this->CostCenter->find('first', array('conditions' => array('CostCenter.cost_center_code' => trim($sqlserver['Cwtcco']['CodiCC']))));
					
					if($toMysql != true)
					{
						$dataToSave[$x]['cost_center_code'] = $sqlserver['Cwtcco']['CodiCC'];
						$dataToSave[$x]['cost_center_name'] = $this->formatNames($sqlserver['Cwtcco']['DescCC']);
						$dataToSave[$x]['level'] = $sqlserver['Cwtcco']['NivelCC'];
						
						if($sqlserver['Cwtcco']['Activo'] == 'S')
							$valid = 1;
						else
							$valid = 0;
						
						$dataToSave[$x]['valid'] = $valid;
						$dataToSave[$x]['created_by_id'] = $this->Auth->user('id');
						
						$x++;
					}
				}
				
				if($dataToSave != false)
				{
					$this->CostCenter->begin();
					
					if($this->CostCenter->saveAll($dataToSave))
					{
						$this->CostCenter->commit();
						$this->Session->setFlash('Se importaron '.$x.' centros de costo.', 'flash_success');
					}
					else
					{
						$this->CostCenter->rollback();
						$this->Session->setFlash('Error al importar los centros de costo, intentalo nuevamente', 'flash_alert');
						$this->redirect(array('action' => 'index'));
					}
				}
				else
				{
					$this->Session->setFlash('No hay centros de costo por importar', 'flash_success');
					$this->redirect(array('action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash('¿Que estas intentando hacer?', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function formatNames($string)
		{
			$pieces = explode(' ', $string);
			
			for($p = 0; $p < count($pieces); $p++)
			{
				if(strlen($pieces[$p]) > 3)
					$pieces[$p] = ucwords(mb_strtolower($pieces[$p]));
			
				if(strlen($pieces[$p]) <= 3)
					$pieces[$p] = mb_strtolower($pieces[$p]);	
			}
		
			$string = implode(' ', $pieces);
			return $string;
		}
		
		function deleteOldSoftlandProviders()
		{	
			if($this->Auth->user('admin') == 1)
			{
				$softlandProviders = $this->SoftlandProvider->find('all');
				
				echo "<pre>";
				print_r($softlandProviders);
				echo "</pre>";
				
				$this->SoftlandProvider->begin();
				
				foreach($softlandProviders as $provider)
				{
					$this->SoftlandProvider->delete($provider['SoftlandProvider']['id']);
				}
				
				$this->SoftlandProvider->commit();
			}
		}
	}
?>