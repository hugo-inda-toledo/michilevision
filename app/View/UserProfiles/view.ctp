<h3><?php echo $this->html->link('Perfiles','/profiles/'); ?> &gt; <?php echo $this->html->link('Asignaciones a usuarios','/user_profiles/'); ?> &gt; Ver ficha asignación</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_user_profile">Datos de la asignación de perfil</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado', '/user_profiles/', array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de asignaciones'));?></li>
	</ul>
</div>	



<div id="UserProfilesViewHolder">
	
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $usuarioProfile['UserProfile']['id']; ?></td>
						</tr>
						<tr>
							<th>Nombre usuario :</th>
							<td><?php echo $this->Html->tag('span', $usuarioProfile['User']['name'].' '.$usuarioProfile['User']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => @$this->RequestAction('/external_functions/showUsuarioTable/'.$usuarioProfile['User']['id'])));?></td>
						</tr>
						<tr>
							<th>Perfil asociado :</th>
							<td><?php echo $this->Html->tag('span', $usuarioProfile['Profile']['profile_name'], array('class' => 'link_admin tip_tip link_profile pointer', 'title' => @$this->RequestAction('/external_functions/showProfileTable/'.$usuarioProfile['Profile']['id'])));?></td>
						</tr>
						<tr>
							<th>Sistema asociado :</th>
							<td><?php echo $this->Html->tag('span', $usuarioProfile['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => @$this->RequestAction('/external_functions/showSistemaTable/'.$usuarioProfile['Profile']['system_id'])));?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Usuario creado por :</th>
							<td><?php echo $this->Html->tag('span', $usuarioProfile['CreatedBy']['name'].' '.$usuarioProfile['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$usuarioProfile['CreatedBy']['id'])));?></td>
						</tr>
						<tr>
							<th>Fecha de creación :</th>
							<td><?php echo $usuarioProfile['UserProfile']['created']; ?></td>
						</tr>
						<tr>
							<th>Ultima modificación :</th>
							<td><?php echo $usuarioProfile['UserProfile']['modified']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
	</div>
	
</div>