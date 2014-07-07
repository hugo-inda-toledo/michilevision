<h3><?php echo $this->html->link('Perfiles','/profiles/'); ?> &gt; Ver perfil</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_add_profile">Datos del perfil <?php echo $profile['Profile']['profile_name'];?></h2>
	<ul>
		<li><?php echo $this->html->link('Editar perfil', '/profiles/edit/'.$profile['Profile']['id'], array('class' => 'tip_tip_default link_admin link_edit','title' => 'Editar datos del perfil'));?></li>
		<li><?php echo $this->html->link('Volver al listado','/profiles/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de perfiles'));?></li>
	</ul>
</div>	

<div id="ProfilesViewHolder">
	
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $profile['Profile']['id']; ?></td>
						</tr>
						<tr>
							<th>Nombre perfil :</th>
							<td><?php echo $profile['Profile']['profile_name']; ?></td>
						</tr>
						<tr>
							<th>Sistema asociado :</th>
							<td><?php echo $this->Html->tag('span', $profile['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$profile['System']['id'])));?></td>
						</tr>
						<tr>
							<th>Descripción :</th>
							<td><?php echo $profile['Profile']['description']; ?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Perfil creado por :</th>
							<td><?php echo $this->Html->tag('span', $profile['CreatedBy']['name'].' '.$profile['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$profile['CreatedBy']['id'])));?></td>
						</tr>
						<tr>
							<th>Fecha de creación :</th>
							<td><?php echo $profile['Profile']['created']; ?></td>
						</tr>
						<tr>
							<th>Ultima modificación :</th>
							<td><?php echo $profile['Profile']['modified']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
	</div>
</div>
<br>
<h2 class="link_admin link_permission">Permisos del perfil <?php echo $profile['Profile']['profile_name'];?></h2>
<div id="TableView">
	<table>
		<tr>
			<td>
				<table>
					<?php
						$x=0;
						foreach($profile['Permission'] as $permission)
						{
							if($x < 5)
							{
								if($x == 0)
									echo "<tr>";
									
								echo '<td>'.$this->Html->tag('span', $permission['type_permission'], array('class' => 'link_admin tip_tip link_permission pointer', 'title' => $permission['description'])).'</td>';
								$x++;
							}
							else
							{
								echo '<td>'.$this->Html->tag('span', $permission['type_permission'], array('class' => 'link_admin tip_tip link_permission pointer', 'title' => $permission['description'])).'</td>';
								echo "</tr>";
								$x=0;
							}
						}
					?>
				</table>
			</td>
		</tr>
	</table>	
</div>