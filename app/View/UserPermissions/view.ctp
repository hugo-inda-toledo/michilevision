<h3><?php echo $this->html->link('Permisos','/permissions/'); ?> &gt; <?php echo $this->html->link('Asignaciones a usuarios','/user_permissions/'); ?> &gt; Ver ficha asignación</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_user_permission">Datos de la asignación temporal</h2>
	<ul>
		<li>
			<?php
			if($usuarioPermiso['UserPermission']['active'] == 'Si')
				echo $this->Html->link('Deshabilitar', '/user_permissions/disable/'.$usuarioPermiso['UserPermission']['id'], array('title' => 'Deshabilita esta asignacion', 'class' => 'tip_tip_default link_admin link_disable'));
			else
				echo $this->Html->link('Habilitar', '/user_permissions/enable/'.$usuarioPermiso['UserPermission']['id'], array('title' => 'Habilita esta asignacion', 'class' => 'tip_tip_default link_admin link_enable'));
			?>
		</li>
		<li><?php echo $this->html->link('Volver al listado', '/user_permissions/', array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de asignaciones'));?></li>
	</ul>
</div>	



<div id="UserPermissionsViewHolder">
	
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $usuarioPermiso['UserPermission']['id']; ?></td>
						</tr>
						<tr>
							<th>Usuario asignado :</th>
							<td><?php echo $this->Html->tag('span', $usuarioPermiso['User']['name'].' '.$usuarioPermiso['User']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$usuarioPermiso['User']['id'])));?></td>
						</tr>
						<tr>
							<th>Permiso asociado :</th>
							<td><?php echo $this->Html->tag('span', $usuarioPermiso['Permission']['type_permission'], array('class' => 'link_admin tip_tip link_permission pointer', 'title' => $this->RequestAction('/external_functions/showPermisoTable/'.$usuarioPermiso['Permission']['id'])));?></td>
						</tr>
						<tr>
							<th>Sistema asociado :</th>
							<td><?php echo $this->Html->tag('span', $usuarioPermiso['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$usuarioPermiso['System']['id'])));?></td>
						</tr>
						<tr>
							<th>Reemplazo Asociado :</th>
							<td>
								<?php 
									if($usuarioPermiso['UserPermission']['replacement_id'] == null)
										echo $this->Html->tag('span', 'No', array('class' => 'link_admin link_circuit_rejected'));
									else
										echo $this->Html->tag('span', 'Si', array('class' => 'link_admin tip_tip link_circuit_approved pointer', 'title' => $this->RequestAction('/external_functions/showReemplazoTable/'.$usuarioPermiso['UserPermission']['replacement_id'])));
								?>
							</td>
						</tr>
						<tr>
							<th>Activo :</th>
							<td>
							<?php 
								if($usuarioPermiso['UserPermission']['active'] == 'Si')
									echo $this->Html->tag('span', 'Habilitado', array('class' => 'link_admin link_circuit_approved'));
								else
									echo $this->Html->tag('span', 'Deshabilitado', array('class' => 'link_admin link_circuit_rejected'));
							?>
							</td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Permiso desde :</th>
							<td><?php echo $usuarioPermiso['UserPermission']['start_date']; ?></td>
						</tr>
						<tr>
							<th>Permiso hasta :</th>
							<td><?php echo $usuarioPermiso['UserPermission']['end_date']; ?></td>
						</tr>
						<tr>
							<th>Usuario creado por :</th>
							<td><?php echo $this->Html->tag('span', $usuarioPermiso['CreatedBy']['name'].' '.$usuarioPermiso['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$usuarioPermiso['CreatedBy']['id'])));?></td>
						</tr>
						<tr>
							<th>Fecha de creación :</th>
							<td><?php echo $usuarioPermiso['UserPermission']['created']; ?></td>
						</tr>
						<tr>
							<th>Ultima modificación :</th>
							<td><?php echo $usuarioPermiso['UserPermission']['modified']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
				
		
	</div>
	
</div>