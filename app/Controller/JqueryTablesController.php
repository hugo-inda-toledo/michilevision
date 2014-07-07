<?php

class JqueryTablesController extends AppController 
{
	var $name = 'JqueryTables';
	var $helpers = array('Ajax', 'Session', 'Html', 'Form','Time');
    var $uses = array('UserPermission','User','Permission', 'Management', 'Profile', 
							'System', 'UserSystem', 'CostCenter', 'UserProfile',
							'TypeReplacement', 'Replacement', 'Position');
	var $scaffold;
	
	
	function showManagementTable($id = null)
	{
		$dataManagements = $this->Management->find('all',array('conditions' => array('Management.id' => $id)));
		
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
					</table>
					<a href='/michilevision/managements/view/".$dataManagement['Management']['id']."' class='link_admin tip_tip link_zoom'>Ver ficha gerencia</a>";
		}
						
		return $table;
	}
	
	
	
	
	
	function showUserTable($id = null)
	{
		$dataUsuarios = $this->User->find('all',array('conditions' => array('User.id' => $id)));
		
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
						</table>
						<a href='/michilevision/users/view/".$dataUsuario['User']['id']."' class=\"link_admin link_user\">Ver ficha usuario</a>";
		}				
		return $table;
	}
	
	
	
	
	
	function showSystemTable($id = 0)
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
							</tr>
							<tr>
								<th>Url :</th>
								<td><a href='".$sistema['System']['url']."' class='link_admin link_system'>".substr($sistema['System']['url'],0,25)."...</a></td>
							</tr>
							</table>
							<a href='/michilevision/systems/view/".$sistema['System']['id']."' class=\"link_admin link_system\">Ver ficha sistema</a>";
		}				
		return $table;
	}
	
	
	
	function showPermissionTable($id = 0)
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
								<td><a href='".$permiso['System']['url']."' class='link_admin link_system' target='_blank'>".$permiso['System']['system_name']."</a></td>
							</tr>
							</table>
							<a href='/michilevision/permissions/view/".$permiso['Permission']['id']."' class=\"link_admin link_permission\">Ver ficha permiso</a>";
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
								<td><a href='".$profile['System']['url']."' class='link_admin link_system' target='_blank'>".$profile['System']['system_name']."</a></td>
							</tr>
							</table>
							<a href='/michilevision/profiles/view/".$profile['Profile']['id']."' class=\"link_admin link_add_profile\">Ver ficha perfil</a>";
		}				
		return $table;
	}
	
	
	
	
	
	function showCostCenterTable($id = 0)
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
							</table>
							<a href='/michilevision/cost_centers/view/".$centroCosto['CostCenter']['id']."' class=\"link_admin link_add_profile\">Ver ficha</a>";
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
							</table>
							<a href='/michilevision/cost_centers/view/".$centroCosto['CostCenter']['id']."' class=\"link_admin link_add_profile\">Ver ficha</a>";
			}
		}		
		return $table;
	}
}
?>