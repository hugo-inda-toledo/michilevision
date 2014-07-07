<h3><?php echo $this->html->link('Permisos','/permissions/'); ?> &gt; Ver ficha</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_system">Datos del permiso <?php echo $permiso['Permission']['type_permission'];?></h2>
	<ul>
		<li><?php echo $this->html->link('Editar permiso', '/permissions/edit/'.$permiso['Permission']['id'], array('class' => 'tip_tip_default link_admin link_edit','title' => 'Editar datos del permiso'));?></li>
		<li><?php echo $this->html->link('Volver al listado','/permissions/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de permisos'));?></li>
	</ul>
</div>	



<div id="PermissionsViewHolder">
	
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $permiso['Permission']['id']; ?></td>
						</tr>
						<tr>
							<th>Tipo de permiso :</th>
							<td><?php echo $permiso['Permission']['type_permission']; ?></td>
						</tr>
						<tr>
							<th>Sistema asociado :</th>
							<td><?php echo $this->Html->tag('span', $permiso['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$permiso['System']['id'])));?></td>
						</tr>
						<tr>
							<th>Descripción :</th>
							<td><?php echo $permiso['Permission']['description']; ?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Nivel :</th>
							<td><?php echo $permiso['Permission']['level']; ?></td>
						</tr>
						<tr>
							<th>Permiso creado por :</th>
							<td><?php echo $this->Html->tag('span', $permiso['CreatedBy']['name'].' '.$permiso['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$permiso['CreatedBy']['id'])));?></td>
						</tr>
						<tr>
							<th>Fecha de creación :</th>
							<td><?php echo $permiso['Permission']['created']; ?></td>
						</tr>
						<tr>
							<th>Ultima modificación :</th>
							<td><?php echo $permiso['Permission']['modified']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
				
		
	</div>
	
</div>