<?php
class PermissionsController extends AppController 
{
	//Declaracion de variables con llamada a helpers, clases externas
    var $name = 'Permissions';
	var $helpers = array('Session', 'Html', 'Form','Time');
    var $uses = array('Permission', 'System', 'User', 'CostCenter');
	var $scaffold;
	var $paginate = array();

	//Lista todos los usuarios de la DB
	function index() 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->paginate = array('Permission' => array('limit' => 25, 'order' => array('System.system_name ASC', 'Permission.type_permission ASC')));
			$dataPermisos = $this->paginate();
			$this->set('permisos', $dataPermisos);
			$this->set('title_for_layout', 'Permisos');
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
			//Se obtiene el id del usuario
			$this->Permission->id = $id;
			
			//Se lee la info del usuario de la DB
			$dataPermiso = $this->Permission->read();	
			
			//Se devuelve arreglos a la vista
			$this->set('permiso',   $dataPermiso);
			$this->set('title_for_layout', 'Datos del permiso '.$dataPermiso['Permission']['type_permission']);
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
			$dataSistemas = $this->RequestAction('/external_functions/showSistemasForSelect');
			$this->set('sistemas', $dataSistemas);
			$this->set('title_for_layout', 'Nuevo Permiso');
			
			//Si hay información...
			if (!empty($this->data)) 
			{
				//Se encapsula esa info. en un arreglo.
				$dataPermiso = $this->data;
				
				$dataPermiso['Permission']['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');
				
				$this->Permission->begin();
				
				//Si la info. se guardo
				if ($this->Permission->save($dataPermiso)) 
				{
					$this->Permission->commit();
					
					$this->Session->setFlash('Se ha agregado correctamente un nuevo permiso.', 'flash_success');
					$this->redirect(array('action' => 'index'));
				}
				
				$this->Permission->rollback();
			}
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
    }	
	
	function edit($id = null) 
	{
		if($this->Auth->user('admin') == 1)
		{
			//Obtengo el id a consultar
			$this->Permission->id = $id;
			$dataSistemas = $this->RequestAction('/external_functions/showSistemasForSelect');
			$this->set('sistemas', $dataSistemas);
			
			//Si no hay información...
			if (empty($this->data)) 
			{
				//Obtengo la info. a partir del id en cuestión
				$this->data = $this->Permission->read();
				
				//Envio la informacion del usuario a consultar y para los selects
				$this->set('permiso', $this->data);
				$this->set('title_for_layout', 'Editando el permiso '.$this->data['Permission']['type_permission']);
			} 
			
			
			//Si hay infomacion (pasado por post)
			else 
			{
				//Se encapsula esa info. en un arreglo.
				$dataPermiso = $this->data;
				
				$this->Permission->begin();
				
				//Si la info. se guardo OK...
				if ($this->Permission->save($dataPermiso))
				{
					$this->Permission->commit();
					
					$this->Session->setFlash('Se ha editado correctamente un permiso existente.', 'flash_success');
					$this->redirect(array('action' => 'index'));
				}
				
				$this->Permission->rollback();
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
			$this->Permission->begin();
			
			if ($this->Permission->delete($id))
			{
				$this->Permission->commit();
				
				$this->Session->setFlash('Se ha eliminado correctamente el permiso seleccionado.', 'flash_success');
				$this->redirect(array('action'=>'index'));
			}
				
			$this->Permission->rollback();
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}

}
?>