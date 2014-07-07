<h3><?php echo $this->html->link('Sistemas','/systems/'); ?> &gt; <?php echo $this->html->link('Asignación de sistemas','/user_systems/'); ?> &gt; Ver ficha asignación</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_system">Datos de la asignación</h2>
	<ul>
		<li><?php echo $this->html->link('Editar asignación','/user_systems/edit/'.$usuarioSistema['UserSystem']['id'], array('class' => 'tip_tip_default link_admin link_edit','title' => 'Editar datos de la asignación'));?></li>
		<li><?php echo $this->html->link('Volver al listado', '/user_systems/', array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de asignaciones'));?></li>
	</ul>
</div>	



<div id="UserSystemViewHolder">
	
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $usuarioSistema['UserSystem']['id']; ?></td>
						</tr>
						<tr>
							<th>Nombre del usuario :</th>
							<td><?php echo $this->Html->tag('span', $usuarioSistema['User']['name'].' '.$usuarioSistema['User']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$usuarioSistema['User']['id'])));?></td>
						</tr>
						<tr>
							<th>Sistema asociado :</th>
							<td><?php echo $this->Html->tag('span', $usuarioSistema['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$usuarioSistema['System']['id'])));?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Usuario creado por :</th>
							<td><?php echo $this->Html->tag('span', $usuarioSistema['CreatedBy']['name'].' '.$usuarioSistema['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$usuarioSistema['CreatedBy']['id'])));?></td>
						</tr>
						<tr>
							<th>Fecha de creación :</th>
							<td><?php echo $usuarioSistema['UserSystem']['created']; ?></td>
						</tr>
						<tr>
							<th>Ultima modificación :</th>
							<td><?php echo $usuarioSistema['UserSystem']['modified']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
	</div>
	
</div>