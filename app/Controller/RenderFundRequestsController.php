<?php
class RenderFundRequestsController extends AppController 
{
	//Declaracion de variables con llamada a helpers, clases externas
    var $name = 'RenderFundRequests';
	var $helpers = array('Ajax', 'Session', 'Html', 'Form','Time');
    var $uses = array('RenderFundRequest','RenderFund');
	var $scaffold;

	
	//Lista todos los usuarios de la DB
	/*function index() 
	{
        //Se envia arreglo con datos de todos los usuarios
		$dataProfiles = $this->Profile->find('all');
		
		echo '<pre>';
		print_r($dataProfiles);
		echo '</pre>';
		
		$this->set('profiles', $dataProfiles);
    }
	

	
	//Muestra los datos de un usuario en particular
	function view($id = null) 
	{
        //Se obtiene el id del usuario
		$this->Profile->id = $id;
		
		//Se lee la info del usuario de la DB
		$dataProfile = $this->Profile->read();	

		//Se obtiene arreglo con la info. del creador del registro.
		$dataCreador = $this->User->find(array('User.id' => $dataProfile['Profile']['created_by_id']));
		
		/* echo '<pre>';
		print_r($dataProfile);
		echo '</pre>'; 
		
		//Se devuelve arreglos a la vista
        $this->set('profile', $dataProfile);
		$this->set('usuarioCreador', $dataCreador);
    }
	
	
	 
	//Funcion para agregar un nuevo usuario
	function add() 
	{
		$dataSistemas = $this->System->find('list', array('fields' => array('System.id', 'System.system_name')));
		$this->set('sistemas', $dataSistemas);
	
		//Si hay informacion
        if (!empty($this->data)) 
		{
			//Se encapsula esa info. en un arreglo.
			$dataProfile = $this->data;
			$dataProfile['Profile']['created_by_id'] = $this->RequestAction('/external_functions/getIdDataSession/');
			
			
			//Si la info. se guardo
			if ($this->Profile->save($dataProfile)) 
			{
				//Envia un flash con mensaje de confrimacion y se redirecciona al index
				$this->Session->setFlash('El perfil ha sido guardado exitosamente.', 'flash_success');
                $this->redirect(array('action' => 'index'));
            }
        }
		
    }
	
	

	//Edicion de un usuario en particular
	function edit($id) 
	{
		//Obtengo el id a consulltar
		$this->Profile->id = $id;
		
		//Si no hay informacion
		if (empty($this->data)) 
		{
			//Obtengo la info. a partir del id en cuestión
			$dataSistemas = $this->System->find('list', array('fields' => array('System.id', 'System.system_name')));
			
			$this->data = $this->Profile->read();
			$dataProfile = $this->data;
			
			/*echo '<pre>';
			print_r($dataProfile);
			echo '</pre>';
			
			//Envio la informacion del usuario a consultar y para los selects
			$this->set('profile', $dataProfile);
			$this->set('sistemas', $dataSistemas);
			
		} 
		
		//Si hay infomacion (pasado por post)
		else 
		{
			//Se encapsula esa info. en un arreglo.
			$dataProfile = $this->data;
			
			/*echo '<pre>';
			print_r($dataProfile);
			echo '</pre>';
			
			//Si la info. se guardo
			if ($this->Profile->save($dataProfile))
			{
				//Envia un flash con mensaje de confrimacion y se redirecciona al index
				$this->Session->setFlash('Este perfil ha sido editado con exito.', 'flash_success');
				$this->redirect(array('action' => 'index'));
			}
		}
	}

	
	
	//borrado de un usuario en particular
	function delete($id) 
	{
		if ($this->Profile->delete($id))
		{
			$this->Session->setFlash('Este perfil ha sido borrado de la base de datos.', 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
	}*/

}
?>