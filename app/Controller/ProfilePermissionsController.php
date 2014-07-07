<?php
class ProfilePermissionsController extends AppController 
{
	//Declaracion de variables con llamada a helpers, clases externas
    var $name = 'ProfilePermissions';
	var $helpers = array('Session', 'Html', 'Form','Time');
    var $uses = array('ProfilePermission','Permission','Profile', 'User', 'System');
	var $scaffold;
	var $paginate = array();
	
	
	//Lista todos los usuarios de la DB
	function index() 
	{
		if($this->Auth->user('admin') == 1)
		{
			//Se envia arreglo con datos de todos los usuarios
			$this->paginate = array('ProfilePermission' => array('limit' => 15, 'order' => array('Profile.profile_name ASC', 'Permission.type_permission ASC')));
			
			$dataProfilePermisos = $this->paginate();
			
			for($x=0; $x < count($dataProfilePermisos); $x++)
			{
				$systemArray = $this->System->find('first', array('conditions' => array('System.id' => $dataProfilePermisos[$x]['Permission']['system_id'])));
				$dataProfilePermisos[$x]['System'] =$systemArray['System'];
			}

			$this->set('profilePermisos', $dataProfilePermisos);
			$this->set('title_for_layout', 'Asignaciones de permisos a perfiles');
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
    }
	
	
	
	
	
	
	//Muestra los datos de un usuario en particular
	function view($id = null) 
	{
		if($this->Auth->user('admin') == 1)
		{
			//Se obtiene el id del usuario
			$this->ProfilePermission->id = $id;
			
			//Se lee la info del usuario de la DB
			$dataProfilePermiso = $this->ProfilePermission->read();
			
			//Se obtiene arreglo con la info. del sistema asociado
			$dataSistema = $this->System->find('first', array('conditions' => array('System.id' => $dataProfilePermiso['Profile']['system_id'])));
			
			//Se devuelve arreglos a la vista
			$this->set('profilePermiso', $dataProfilePermiso);
			$this->set('sistemaAsociado', $dataSistema);
			$this->set('title_for_layout', 'Datos de la asignación');
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
    }
	
	
	
	
	
	
	//Funcion para agregar un nuevo usuario
	function add() 
	{	
		if($this->Auth->user('admin') == 1)
		{
			$dataSistemas = $this->RequestAction('/external_functions/showSistemasForSelect/');
			$this->set('sistemas', $dataSistemas);
			$this->set('title_for_layout', 'Nueva asignación');
			
			
			//Si hay informacion
			if (!empty($this->data)) 
			{
				//Se encapsula esa info. en un arreglo.
				$dataAsignacion = $this->data;
				$dataAsignacion['ProfilePermission']['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');;

				$dataMultiSelect = $this->data['ProfilePermission']['permission_id'];
				
				$tmp2 = array();
				$cont = 0;
				
				
				if($dataAsignacion['ProfilePermission']['profile_id'] == ''){
					$this->Session->setFlash('Debe seleccionar al menos un perfil del listado.', 'flash_alert');
					$this->redirect(array('action' => 'add'));
				}

				if($dataAsignacion['ProfilePermission']['permission_id'] == ''){
					$this->Session->setFlash('Debe seleccionar al menos un permiso del listado.', 'flash_alert');
					$this->redirect(array('action' => 'add'));
				}
				
				foreach($dataMultiSelect as $dataSelect)
				{
					$tmp['profile_id'] = $this->data['ProfilePermission']['profile_id'];
					$tmp['permission_id'] = $dataSelect;
					$tmp['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');

					array_push($tmp2, $tmp);
					$cont = $cont +1;
				}
				
				$this->data= $tmp2;
				$dataAsignaciones = $this->data;
				
				$this->ProfilePermission->begin();
				
				//Si la info. se guardo
				if ($this->ProfilePermission->saveAll($dataAsignaciones)) 
				{
					$this->ProfilePermission->commit();
					
					if($cont == 1)
						$this->Session->setFlash('Se ha creado correctamente una nueva asignación.', 'flash_success');
					else
						$this->Session->setFlash('Se han creado correctamente nuevas asignaciones.', 'flash_success');
						
					$this->redirect(array('action' => 'index'));
				}
				
				$this->ProfilePermission->rollback();
			}
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
    }
	
	
	
	
	
	//borrado de una asignacion en particular
	function delete($id = null) 
	{
		if($this->Auth->user('admin') == 1)
		{
			$this->ProfilePermission->begin();
			
			if ($this->ProfilePermission->delete($id))
			{
				$this->Session->setFlash('Se ha eliminado correctamente la asignación seleccionada.', 'flash_success');
				$this->redirect(array('action'=>'index'));
			}
			
			$this->ProfilePermission->rollback();
		}
		else
		{
			$this->Session->setFlash('¿Estas seguro que puedes ejecutar esta accion?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	}
}

?>