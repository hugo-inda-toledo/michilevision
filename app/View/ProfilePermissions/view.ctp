<h3><?php echo $this->html->link('Perfiles','/profiles/'); ?> &gt; <?php echo $this->html->link('Asignaciones de permisos','/profile_permissions/'); ?> &gt; Ver ficha asignación</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_permission">Datos de la asignación</h2>
	<ul>
		<li><?php echo $this->Html->link('Eliminar Asignación', array('action' => 'delete', $profilePermiso['ProfilePermission']['id']), array('title' => 'Eliminar Asignación', 'class' => 'tip_tip_default link_admin link_delete'), 'Usted está a punto de eliminar la siguiente asignación :\n\nPerfil: '.$profilePermiso['Profile']['profile_name'].'\nPermiso Asignado: '.$profilePermiso['Permission']['type_permission'].'\n\nDesea continuar...?');?></li>
		<li><?php echo $this->html->link('Volver al listado', '/profile_permissions/', array('class' => 'tip_tip_default tip_tip_default link_admin link_back','title' => 'Volver al listado de asignaciones'));?></li>
	</ul>
</div>	



<div id="ProfilePermissionViewHolder">
	
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $profilePermiso['ProfilePermission']['id']; ?></td>
						</tr>
						<tr>
							<th>Perfil :</th>
					      	<td><?php echo $this->Html->tag('span', $profilePermiso['Profile']['profile_name'], array('class' => 'link_admin tip_tip link_add_profile pointer', 'title' => $this->RequestAction('/external_functions/showProfileTable/'.$profilePermiso['Profile']['id'])));?></td>
						</tr>
						<tr>
							<th>Sistema asociado :</th>
							<td><?php echo $this->Html->tag('span', $sistemaAsociado['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$profilePermiso['Permission']['system_id'])));?></td>
						</tr>
						<tr>
							<th>Permiso asociado :</th>
							<td><?php echo $this->Html->tag('span', $profilePermiso['Permission']['type_permission'], array('class' => 'link_admin tip_tip link_permission pointer', 'title' => $this->RequestAction('/external_functions/showPermisoTable/'.$profilePermiso['Permission']['id'])));?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Usuario creado por :</th>
							<td><?php echo $this->Html->tag('span', $profilePermiso['CreatedBy']['name'].' '.$profilePermiso['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$profilePermiso['CreatedBy']['id'])));?></td>
						</tr>
						<tr>
							<th>Fecha de creación :</th>
							<td><?php echo $profilePermiso['ProfilePermission']['created']; ?></td>
						</tr>
						<tr>
							<th>Ultima modificación :</th>
							<td><?php echo $profilePermiso['ProfilePermission']['modified']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
				
		
	</div>
	
</div>