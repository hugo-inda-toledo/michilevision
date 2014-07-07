<?php
App::import('Sanitize');


class ExternalFunctionsController extends AppController 
{
	var $name = 'ExternalFunctions';
	var $helpers = array('Session', 'Html', 'Form', 'Time');
    var $uses = array('UserPermission','User','Permission', 'Management', 'Profile', 
							'System', 'UserSystem', 'CostCenter', 'UserProfile',
							'TypeReplacement', 'Replacement', 'Position','RenderFund', 'Headquarter', 'AttributeTable');
	var $scaffold;
	
	/***************************************************************
	* PARA LLAMAR A UNA DE ESTAS FUNCIONES DESDE OTRO CONTROLADOR*
	******************SE USA LA SIGUIENTE SINTEXIS*******************
	****************************************************************
	*																									      *
	*	$this->requestAction('/controlador/metodo/parametro1/parametro2/..');  *
	*	
	* 																										  
	****************************************************************
	****************************************************************/
	
	/*****************************************************************
	***************Funciones de ayuda para tabla USUARIOS****************
	******************************************************************/



	function clear_cache() 
	{ 
		if($this->Auth->user('admin') == 1)
		{
			$cachePaths = array('views','persistent','models');
	  
			foreach($cachePaths AS $config)
			{
				Cache::config($config, array('path' => CACHE.$config.DS, 'prefix'=>'', 'engine'=>'File'));
				Cache::clear(false, $config);
			} 
			
			$this->Session->setFlash('Limpieza de cache finalizada.', 'flash_success');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
		else
		{
			$this->Session->setFlash('Ups!, ¿Que tratas de hacer?', 'flash_alert');
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		}
	} 
	
	/*public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('showManagements');
		$this->Auth->allow('showPositions');
	}*/
	
	//Retorna todos los usuarios
	function getUsuarios()
	{
		return $this->Usuario->find('all');
	}


	
	//Retorna un usuario por id
	function getUsuarioById($id = null)
	{ 
		return $this->Usuario->find('all',array('conditions' => array('Usuario.id' => $id)));
	}
	
	
	
	//Retorna todos los usuarios activos
	function getActiveUsuarios()
	{
		return $this->Usuario->find('all',array('conditions' => array('Usuario.active' => 1)));
	}
	
	
	
	//Retorna todos los usuarios no activos
	function getNotActiveUsuarios()
	{
		return $this->Usuario->find('all',array('conditions' => array('Usuario.active' => 0)));
	}
	
	
	
	//Retorna todos los usuarios de planta
	function getPlantaUsuarios()
	{
		return $this->Usuario->find('all',array('conditions' => array('Usuario.planta' => 1)));
	}
	
	//Retorna todos los usuarios que no son de planta
	function getNotPlantaUsuarios()
	{
		return $this->Usuario->find('all',array('conditions' => array('Usuario.planta' => 0)));
	}
	
	function getDniUser($id)
	{
		$data = $this->User->find('first',array('conditions' => array('User.id' => $id)));
		return $data['User']['dni'];
	}
	
	
	
	/*****************************************************************
	******************************************************************/
	/*****************************************************************
	***************Funciones de ayuda para tabla Systems (Sistemas)****************
	*****************************************************************/
	
	
	function getSistemaById($id = null)
	{ 
		return $this->System->find('all',array('conditions' => array('System.id' => $id)));
	}
	
	function getFinanceManagementId()
	{ 
		$financeManagement =  $this->Management->find('first',array('conditions' => array('Management.id' => 9)));
		return $financeManagement['Management']['user_id'];
	}
	
	function getAdministrationManagementId()
	{
		$administrationManagement =  $this->Management->find('first',array('conditions' => array('Management.id' => 14)));
		return $administrationManagement['Management']['user_id'];
	}
	
	function verifiedAdministrationUserLogged($id)
	{
		$administrationManagement =  $this->Management->find('first',array('conditions' => array('Management.id' => 14)));
		
		if($id == $administrationManagement['Management']['user_id'])
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	/**************Verifica el acceso a alguna funcion de algun sistema***********/
	
	function verifiedAccess($userId = null, $systemId = null, $stringPermission = null)
	{
		$dataUser = $this->User->find('first', array('conditions' => array('User.id' => $userId)));
		
		foreach($dataUser['Profile'] as $userProfile)
		{
			if($userProfile['system_id'] == $systemId)
			{
				$dataProfile = $this->Profile->find('first', array('conditions' => array('Profile.id' => $userProfile['id'])));
				
				foreach($dataProfile['Permission'] as $permission)
				{
					if($stringPermission == $permission['type_permission'])
					{
						return true;
					}
				}
			}
		}
		return false;
	}
	
	/****************************************************************/
	
	/****************************************************************
	*****************************************************************
	*****************************************************************/

	/*****************************************************************
	***************Funciones de ayuda para tabla Managements (Gerencias)****************
	*****************************************************************/
	
	
	//Muestra todas las gerencias
	function showManagements()  
	{
		$valuesManagements = $this->Management->find('all', array('order' => 'Management.management_name ASC'));
		$x=0;
		foreach ($valuesManagements as $value)
		{
			if($x==0)
				$resultadoManagements[0] = 'Seleccione una gerencia';
			else
				$resultadoManagements[$value['Management']['id']]= $value['Management']['management_name'];
				
			$x++;
		}
		
		return $resultadoManagements;
	}
	
	//Muestra todas las gerencias
	function showHeaquarters()  
	{
		$valuesHeadquarters = $this->Headquarter->find('all', array('order' => 'Headquarter.headquarter_name ASC'));
		$x=0;
		foreach ($valuesHeadquarters as $value)
		{
			if($x==0)
				$resultadoHeadquarters[0] = 'Seleccione una jefatura';
			else
				$resultadoHeadquarters[$value['Headquarter']['id']]= $value['Headquarter']['headquarter_name'];
				
			$x++;
		}
		
		return $resultadoHeadquarters;
	}
	
	
	/****************************************************************
	*****************************************************************
	*****************************************************************/

	/*****************************************************************
	*************** Funciones de ayuda para tabla Positions (Cargos) ****************
	*****************************************************************/
	
	
	//Muestra todos los cargos
	function showPositions()
	{
		$valuesPositions = $this->Position->find('all', array('conditions' => 'ORDER BY position ASC'));
		
		foreach ($valuesPositions as $value)
		{
			$resultadoPositions[$value['Position']['id']]= mb_convert_case($value['Position']['position'],MB_CASE_TITLE);
		}
		
		return $resultadoPositions;
	}
	
	
	/* ****************************************************************
	***********************Funciones de ayuda general******************
	**************************************************************** */
	
	
	// Elimina todo tipo de caracteres especiales y reemplaza vocales acentuadas...
	
	function removeAccents($string = null)
	{
	
	    $string = str_replace(
	    	array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
	        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
	        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
	        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
	        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
	        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
	        $string
	    );
	 
	    $string = str_replace(
	        array('ñ', 'Ñ', 'ç', 'Ç'),
	        array('n', 'N', 'c', 'C',),
	        $string
	    );
	 
	    $string = str_replace(
	        array("\\", "¨", "º", "-", "~",
	             "#", "@", "|", "!", "\"",
	             "·", "$", "%", "&", "/",
	             "(", ")", "?", "'", "¡",
	             "¿", "[", "^", "`", "]",
	             "+", "}", "{", "¨", "´",
	             ">", "<", ";", ",", ":",
	             ".", " "),
	        '',
	        $string
	    );
	 
	 
		$string = trim(strtolower($string));   	
		return $string;		
	}
	
	
	// Recortar un texto a un largo x, sin dejar truncada la última palabra...
	
	function recortar_texto($texto,$numero_caracteres){
		if(strlen($texto) > $numero_caracteres){
		$array_texto = explode(' ', $texto);
		$ncaracteres = 0;
		$result = '';
			foreach($array_texto as $palabra){
				if(($ncaracteres + strlen($palabra) > $numero_caracteres)){
				$result .= '...';
				break;
				}
				else{
				$ncaracteres += strlen($palabra) + 1;
					if($result != ''){
					$result .= ' ';
					}
				$result .= $palabra;
				}
			}
		return $result;
		}
		else{
		return $texto;
		}
	}
	
	
	function recortar_texto_simple($texto,$numero_caracteres){
		if(strlen($texto) > $numero_caracteres){
			return substr($texto, 0, $numero_caracteres).'...';
		}
		else{
			return $texto;
		}
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
	
	//Setea la fecha enviada a formato chileno
	function setDate($date = null)
	{	
		$day = substr($date ,8, 2);
		$month = substr($date ,5, 2);
		$year = substr($date ,0, 4);
		
		$newDate = $day.'-'.$month.'-'.$year;
		
		return $newDate;
	}

	//Genera lista de usuarios con id, nombre y apellido para selects...
	function showUsuarios()
	{
		$valuesUsuarios = $this->User->find('all', array('conditions' => array('User.active' => 1),'order' => 'User.first_lastname ASC'));
		
		foreach ($valuesUsuarios as $value)
		{
			$resultadosUsuarios[$value['User']['id']]= $value['User']['first_lastname'].' '.$value['User']['second_lastname'].', '.$value['User']['name'];
		}
		
		return $resultadosUsuarios;
	}
	
	function showAllUsuarios()
	{
		$valuesUsuarios = $this->User->find('all', array('order' => 'User.first_lastname ASC'));
		$cont=0;
		
		foreach ($valuesUsuarios as $value)
		{
			if($cont==0)
				$resultadosUsuarios[0] = 'Seleccione un usuario';
			else
				$resultadosUsuarios[$value['User']['id']]= $value['User']['first_lastname'].' '.$value['User']['second_lastname'].', '.$value['User']['name'];
			
			$cont++;
		}
		
		return $resultadosUsuarios;
	}
	
	//Genera lista de permisos con sistema concatenado para selects
	function showPermisosWithSistema()
	{
		$valuesPermisos = $this->Permiso->find('all');
		
		foreach ($valuesPermisos as $value)
		{
			$resultadoPermisos[$value['Permiso']['id']]= "Permiso: ".$value['Permiso']['tipo_permiso'].".  Sistema: ".$value['Sistema']['nombre_sistema'] ;
		}
			
		return $resultadoPermisos;
	}
	
	function showProfilesForSelect()
	{
		$valuesProfiles = $this->Profile->find('all');
		
		foreach ($valuesProfiles as $value)
		{
			$resultadoProfile[$value['Profile']['id']]= "Perfil: ".$value['Profile']['nombre_perfil'].".  Sistema: ".$value['Sistema']['nombre_sistema'] ;
		}
			
		return $resultadoProfile;
	}
	
	
	function showSistemasForSelect()
	{
		return $this->System->find('list', array('fields' => array('System.id', 'System.system_name'), 'order' => 'System.system_name ASC'));
	}
	
	function ProfileAndPermisoSelect($sistemaId = 0)	
	{
		$arrayProfile =  $this->Profile->find('list', array('conditions' => array('Profile.sistema_id' => $sistemaId),  'fields' => array('Profile.id', 'Profile.nombre_perfil')));
		$arrayPermiso =  $this->Permiso->find('list', array('conditions' => array('Permiso.sistema_id' => $sistemaId), 'fields' => array('Permiso.id', 'Permiso.tipo_permiso')));
		
		$this->set('perfiles', $arrayProfile);
		$this->set('permisos', $arrayPermiso);
		
	}
	
	function SistemasSelectForId($usuarioId = 0)
	{
		$arraySistemas =  $this->UsuarioSistema->find('list', array('conditions' => array('UsuarioSistema.usuario_id' => $usuarioId), 'fields' =>array('UsuarioSistema.id', 'UsuarioSistema.sistema_id')));
		$select = array();
		
		foreach($arraySistemas as $key => $value)
		{
			$nombreSistema =  $this->Sistema->find('all', array('conditions' => array('Sistema.id' => $value), 'fields' =>array('Sistema.id', 'Sistema.nombre_sistema')));
			$select[$value] = $nombreSistema[0]['Sistema']['nombre_sistema'];
		}
		
		$this->set('selectSistemas', $select);
	}
	
	function getSistemasForProfiles($usuarioId = 0)
	{
		$arraySistemas =  $this->UserSystem->find('list', array('conditions' => array('UserSystem.user_id' => $usuarioId), 'fields' =>array('UserSystem.id', 'UserSystem.system_id')));
		$select = array();
		
		foreach($arraySistemas as $key => $value)
		{
			$nombreSistema =  $this->System->find('all', array('conditions' => array('System.id' => $value), 'fields' =>array('System.id', 'System.system_name')));
			$select[$value] = $nombreSistema[0]['System']['system_name'];
		}
		
		$this->set('selectSistemas', $select);
	}

	function SystemsAndCostCentersSelectForId($usuarioId = 0)
	{
		$arraySistemas =  $this->UserSystem->find('list', array('conditions' => array('UserSystem.user_id' => $usuarioId), 'fields' =>array('UserSystem.id', 'UserSystem.system_id')));
		
		$select = array();
		$center = array();
		
		foreach($arraySistemas as $key => $value)
		{
			$nombreSistema =  $this->System->find('first', array('conditions' => array('System.id' => $value), 'fields' =>array('System.id', 'System.system_name')));
			
			$select[$value] = $nombreSistema['System']['system_name'];
		}
		
		$centrosDeCosto = $this->CostCenter->find('all', array('fields' =>array('CostCenter.id', 'CostCenter.cost_center_name', 'CostCenter.cost_center_code'), 'conditions' => array('CostCenter.valid' => 1, 'CostCenter.level' => 3),'order'=>'cost_center_code ASC'));
		
		for($x=0; $x < count($centrosDeCosto); $x++)
		{
			$center[$centrosDeCosto[$x]['CostCenter']['id']] = $centrosDeCosto[$x]['CostCenter']['cost_center_code'].' |||| '.$centrosDeCosto[$x]['CostCenter']['cost_center_name'];
		}
		
		$this->set('selectSistemas', $select);
		$this->set('resultCentroCostos', $center);
	}
	
	function SistemasForPermisos($usuarioId = 0)
	{
		$arraySistemas =  $this->UserSystem->find('list', array('conditions' => array('UserSystem.user_id' => $usuarioId), 'fields' =>array('UserSystem.id', 'UserSystem.system_id')));
		$select = array();
		
		foreach($arraySistemas as $key => $value)
		{
			$nombreSistema =  $this->System->find('all', array('conditions' => array('System.id' => $value), 'fields' =>array('System.id', 'System.system_name')));
			
			$select[$value] = $nombreSistema[0]['System']['system_name'];
		}
		
		$this->set('selectSistemas', $select);
	}

	function PermisosForPermisos($sistemaId = 0){
		$arrayPermisos =  $this->Permission->find('list', array('conditions' => array('Permission.system_id' => $sistemaId), 'fields' =>array('Permission.id', 'Permission.type_permission')));
		$this->set('permisos', $arrayPermisos);
		
	}
	
	function ProfilesSelectForId($sistemaId = 0)
	{
		$arrayProfiles =  $this->Profile->find('list', array('conditions' => array('Profile.system_id' => $sistemaId), 'fields' =>array('Profile.id', 'Profile.profile_name')));
	
		$this->set('selectProfiles', $arrayProfiles);
	}

	function PermisosAndProfilesSelectForId($sistemaId = 0)
	{
		
		$arrayProfiles =  $this->Profile->find('list', array('conditions' => array('Profile.system_id' => $sistemaId), 'fields' =>array('Profile.id', 'Profile.profile_name')));
		$arrayPermisos =  $this->Permission->find('list', array('conditions' => array('Permission.system_id' => $sistemaId), 'fields' =>array('Permission.id', 'Permission.type_permission')));

		
		$this->set('selectProfiles', $arrayProfiles);
		$this->set('selectPermisos', $arrayPermisos);
	}

	function ReemplazoDiv($usuarioId = 0)
	{
		$usuarios =  $this->User->find('all', array('conditions' => array('User.id <>' => $usuarioId), 'order' => 'User.first_lastname ASC'));
		
		foreach ($usuarios as $value)
		{
			$arrayUsuarios[$value['User']['id']]= $value['User']['first_lastname'].' '.$value['User']['second_lastname'].', '.$value['User']['name'] ;
		}
		
		$arrayTipoReemplazos = $this->TypeReplacement->find('list', array('fields' => array('TypeReplacement.id', 'TypeReplacement.type_replacement')));
		$arrayGerencias = $this->Management->find('list', array('fields' => array('Management.id', 'Management.management_name'), 'order' => 'Management.management_name ASC'));
		
		
		$this->set('selectUsuarios', $arrayUsuarios);
		$this->set('selectTipoReemplazos', $arrayTipoReemplazos);
		$this->set('selectGerencias', $arrayGerencias);
	}
	
	function getCostCenterSelect()
	{
		$centrosDeCosto = $this->CostCenter->find('all', array('fields' =>array('CostCenter.id', 'CostCenter.cost_center_name', 'CostCenter.cost_center_code'), 'conditions' => array('CostCenter.valid' => 1, 'CostCenter.level' => 3, 'CostCenter.cost_center_name <>' => '.'),'order'=>'cost_center_code ASC'));
		
		for($x=0; $x < count($centrosDeCosto); $x++)
		{
			if($x==0)
				$center[0] = "Seleccione un centro de costo";
			else
				$center[$centrosDeCosto[$x]['CostCenter']['id']] = $centrosDeCosto[$x]['CostCenter']['cost_center_code'].' - '.$centrosDeCosto[$x]['CostCenter']['cost_center_name'];
		}
		
		return $center;
	}

	
	/****************************************************************
	*****************************************************************
	*****************************************************************/
	
	/****************************************************************
	*********************Obtencion de datos se la sesion******************
	*****************************************************************/
	
	
	function getDataSession()
	{
		$id = $this->Auth->user('id');
		$dataUser = $this->User->find('first', array('conditions' => array('User.id' => $id)));
		
		return $dataUser;
	}
	
	function getIdDataSession()
	{
		return $this->Auth->user('id');
	}
	
	function getEmailConfirmation()
	{
		$user;
		$user = $this->Auth->user('id');
		$arrayUser = $this->User->find('first', array('conditions' => array('User.id' => $user), 'fields' => array('User.id', 'User.email_confirm')));
		
		if($arrayUser['User']['email_confirm'] == 0)
			return false;
		else
			return true;
		
	}
	
	function getAcquisitionUser()
	{
		$data = $this->AttributeTable->find('first', array('conditions' => array('AttributeTable.key' => 'acquisition_id')));
		
		return $data['AttributeTable']['value'];
	}
	
	function validateTour()
	{	
		$user = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id'))));
		
		$user['User']['tour_validated'] = 1;
		
		if($this->User->save($user))
			return true;
	}
	
	/****************************************************************
	*****************************************************************
	*****************************************************************/
	
	/*****************************************************************
	*****************Funciones para tablas emergentes********************
	*****************************************************************/

	function showGerenciaTable($id = null)
	{
		$dataManagements = $this->Management->find('all',array('conditions' => array('Management.id' => $id)));
		
		$table;
		
		foreach ($dataManagements as $dataManagement)
		{
			$table = "<table>
					<tr>
						<th>Gerencia :</th>
						<td>".$dataManagement['Management']['management_name']."</td>
					</tr>
					<tr>
						<th>Gerente :</th>
						<td>".$dataManagement['User']['name']." ".$dataManagement['User']['first_lastname']."</td>
					</tr>
					<tr>
						<th>Tipo autorización :</th>
						<td>".$dataManagement['Authorization']['name']."</td>
					</tr>
					</table>";
				if($this->Auth->user('admin') == 1)
					$table .="<a href='/michilevision/managements/view/".$dataManagement['Management']['id']."' class='link_admin tip_tip link_zoom'>Ver ficha gerencia</a>";
		}
						
		return $table;
	}
	
	function showUsuarioTable($id = null)
	{
		$dataUsuarios = $this->User->find('all', array('conditions' => array('User.id' => $id)));
		
		$table;
		foreach ($dataUsuarios as $dataUsuario)
		{
			$table = "<table>
						<tr>
							<th>Nombre :</th>
							<td>".$dataUsuario['User']['name']." ".$dataUsuario['User']['first_lastname']."</td>
						</tr>
						<tr>
							<th>Cargo :</th>
							<td>".ucfirst($dataUsuario['Position']['position'])."</td>
						</tr>
						<tr>
							<th>Email :</th>
							<td><a href='mailto:".$dataUsuario['User']['email']."' class='link_admin link_email'>".$dataUsuario['User']['email']."</a></td>
						</tr>
						</table>";
			if($this->Auth->user('admin') == 1)
				$table .= "<a href='/michilevision/users/view/".$dataUsuario['User']['id']."' class=\"link_admin link_user\">Ver ficha usuario</a>";
		}				
		return $table;
	}
	
	function showSistemaTable($id = 0)
	{
		$dataSistema = $this->System->find('all',array('conditions' => array('System.id' => $id)));
		
		foreach ($dataSistema as $sistema)
		{
			$table = "<table>
							<tr>
								<th>Nombre :</th>
								<td>".$sistema['System']['system_name']."</td>
							</tr>
							<tr>
								<th>Descripción :</th>
								<td>".$sistema['System']['system_description']."</td>
							</tr>";
			if($this->Auth->user('admin') == 1)
			{
				$table .="<tr>
								<th>Nombre de la tabla :</th>
								<td>".$sistema['System']['table_name']."</td>
							</tr>
							</table>";
			}
			
			if($this->Auth->user('admin') == 1)
				$table .= "<a href='/michilevision/systems/view/".$sistema['System']['id']."' class=\"link_admin link_system\">Ver ficha sistema</a>";
		}				
		return $table;
	}
	
	function showPermisoTable($id = 0)
	{
		$dataPermiso = $this->Permission->find('all',array('conditions' => array('Permission.id' => $id)));
		
		foreach ($dataPermiso as $permiso)
		{
			$table = "<table>
							<tr>
								<th>Permiso :</th>
								<td>".$permiso['Permission']['type_permission']."</td>
							</tr>
							<tr>
								<th>Descripción :</th>
								<td>".$permiso['Permission']['description']."</td>
							</tr>
							<tr>
								<th>Sistema asociado :</th>
								<td><a href='/michilevision/".$permiso['System']['table_name']."' class='link_admin link_system'>".$permiso['System']['system_name']."</a></td>
							</tr>
							</table>";
				if($this->Auth->user('admin') == 1)				
					$table .= "<a href='/michilevision/permissions/view/".$permiso['Permission']['id']."' class=\"link_admin link_permission\">Ver ficha permiso</a>";
		}				
		return $table;
		
	}
	
	function showProfileTable($id = 0)
	{
		$dataProfile = $this->Profile->find('all',array('conditions' => array('Profile.id' => $id)));
		
		foreach ($dataProfile as $profile)
		{
			$table = "<table>
							<tr>
								<th>Perfil :</th>
								<td>".$profile['Profile']['profile_name']."</td>
							</tr>
							<tr>
								<th>Descripción :</th>
								<td>".$profile['Profile']['description']."</td>
							</tr>
							<tr>
								<th>Sistema asociado :</th>
								<td><a href='/michilevision/".$profile['System']['table_name']."' class='link_admin link_system' target='_blank'>".$profile['System']['system_name']."</a></td>
							</tr>
							</table>";
			if($this->Auth->user('admin') == 1)
				$table .= "<a href='/michilevision/profiles/view/".$profile['Profile']['id']."' class=\"link_admin link_add_profile\">Ver ficha perfil</a>";
		}				
		return $table;
	}
	
	function showReemplazoTable($id =0)
	{
		$dataReplacement = $this->Replacement->find('all',array('conditions' => array('Replacement.id' => $id)));
		
		foreach ($dataReplacement as $replacement)
		{
			$table = "<table>
							<tr>
								<th>Usuario Reemplazado:</th>
								<td>".$replacement['replaced_user']['name']." ".$replacement['replaced_user']['first_lastname']."</td>
							</tr>
							<tr>
								<th>Usuario Reemplazante:</th>
								<td>".$replacement['replacing_user']['name']." ".$replacement['replacing_user']['first_lastname']."</td>
							</tr>
							<tr>
								<th>Motivo :</th>
								<td>".$replacement['TypeReplacement']['type_replacement']."</td>
							</tr>
							<tr>
								<th>Fecha del reemplazo :</th>
								<td>Desde ".$replacement['Replacement']['start_date']." Hasta ".$replacement['Replacement']['end_date']."</td>
							</tr>
							</table>";
			if($this->Auth->user('admin') == 1)
				$table .= "<a href='/michilevision/replacements/view/".$replacement['Replacement']['id']."' class=\"link_admin link_zoom\">Ver ficha del reemplazo</a>";
		}				
		return $table;
	}
	
	function showCentroCostoTable ($id = 0)
	{
		$dataCentroCosto = $this->CostCenter->find('all',array('conditions' => array('CostCenter.id' => $id)));
		
		foreach ($dataCentroCosto as $centroCosto)
		{
			if($dataCentroCosto['CostCenter']['valid'] = 1)
			{
				$centroCosto['CostCenter']['valid'] = "Si";
			}
			else
			{
				$centroCosto['CostCenter']['valid'] = "No";
			}
			
			if($dataCentroCosto['CostCenter']['management_id'] = " ")
			{
				$table = "<table>
							<tr>
								<th>Centro de costo :</th>
								<td>".$centroCosto['CostCenter']['cost_center_name']."</td>
							</tr>
							<tr>
								<th>Código :</th>
								<td>".$centroCosto['CostCenter']['cost_center_code']."</td>
							</tr>
							<tr>
								<th>Nivel :</th>
								<td>".$centroCosto['CostCenter']['level']."</a></td>
							</tr>
							<tr>
								<th>Vigente :</th>
								<td>".$centroCosto['CostCenter']['valid']."</td>
							</tr>
							</table>";
				if($this->Auth->user('admin') == 1)
					$table .= "<a href='/michilevision/cost_centers/view/".$centroCosto['CostCenter']['id']."' class=\"link_admin link_add_profile\">Ver ficha</a>";
			}
			else
			{
				$table = "<table>
							<tr>
								<th>Centro de costo :</th>
								<td>".$centroCosto['CostCenter']['cost_center_name']."</td>
							</tr>
							<tr>
								<th>Código :</th>
								<td>".$centroCosto['CostCenter']['cost_center_code']."</td>
							</tr>
							<tr>
								<th>Gerencia :</th>
								<td><a href='".$centroCosto['Management']['id']."' class='link_admin link_zoom'>".$centroCosto['Management']['management_name']."</a></td>
							</tr>
							<tr>
								<th>Nivel :</th>
								<td>".$centroCosto['CostCenter']['level']."</a></td>
							</tr>
							<tr>
								<th>Vigente :</th>
								<td>".$centroCosto['CostCenter']['valid']."</td>
							</tr>
							</table>";
				if($this->Auth->user('admin') == 1)
					$table .= "<a href='/michilevision/cost_centers/view/".$centroCosto['CostCenter']['id']."' class=\"link_admin link_add_profile\">Ver ficha</a>";
			}
		}		
		return $table;
	}
	
	function showHeadquarterTable($id = null)
	{
		if($id != null)
		{
			$dataHeadquarter = $this->Headquarter->find('first', array('conditions' => array('Headquarter.id' => $id)));
			
			if($dataHeadquarter['Headquarter']['active'] == 1)
				$activity = "Si";
			else
				$activity = "No";
			
			$table;
			
			$table = "<table>
							<tr>
								<th>Jefatura :</th>
								<td>".$dataHeadquarter['Headquarter']['headquarter_name']."</td>
							</tr>
							<tr>
								<th>Gerencia asociada :</th>
								<td>".$dataHeadquarter['Management']['management_name']."</td>
							</tr>
							<tr>
								<th>Centro de costo de la jefatura :</th>
								<td>".$dataHeadquarter['Headquarter']['cost_center_code']."</td>
							</tr>
							<tr>
								<th>Jefe de la jefatura :</th>
								<td>".$dataHeadquarter['User']['name']." ".$dataHeadquarter['User']['first_lastname']."</td>
							</tr>
							<tr>
								<th>Activo :</th>
								<td>".$activity."</td>
							</tr>
							</table>";
			if($this->Auth->user('admin') == 1)
				$table .= "<a href='/michilevision/headquarters/view/".$dataHeadquarter['Headquarter']['id']."' class=\"link_admin link_zoom\">Ver ficha de la jefatura</a>";
			
			return $table;
		}
	}

	function uploadFile()
	{	
		$uploaddir =  'files/purchase_orders/budgets/';
		// defino el nombre del archivo
		$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
		// Lo mueve a la carpeta elegida
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
		{
			echo "success";
		} 
		else 
		{
			echo "error";
		}
	}
}
?>