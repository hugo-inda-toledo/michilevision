<h3><?php echo $this->html->link('Sistemas','/systems/'); ?> &gt; Ver Ficha</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_system">Datos del sistema <?php echo $sistema['System']['system_name'];?></h2>
	<ul>
		<li><?php echo $this->html->link('Editar ficha sistema', '/systems/edit/'.$sistema['System']['id'], array('class' => 'tip_tip_default link_admin link_edit','title' => 'Editar ficha '.$sistema['System']['system_name']));?></li>
		<li><?php echo $this->html->link('Volver al listado', '/systems/', array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de sistemas'));?></li>
	</ul>
</div>	



<div id="SystemViewHolder">
	
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $sistema['System']['id']; ?></td>
						</tr>
						<tr>
							<th>Nombre del sistema :</th>
							<td><?php echo $sistema['System']['system_name']; ?></td>
						</tr>
						<tr>
							<th>Descripción :</th>
							<td><?php echo $sistema['System']['system_description']; ?></td>
						</tr>
						<tr>
							<th>Nombre de la tabla :</th>
							<td><?php echo $sistema['System']['table_name']; ?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Clase CSS del sistema :</th>
							<td><?php echo $sistema['System']['css_class_url']; ?></td>
						</tr>
						<tr>
							<th>Usa centro de costo :</th>
							<td><?php if($sistema['System']['use_cost_center'] == 1) echo 'Si'; else echo "No"; ?></td>
						</tr>
						<tr>
							<th>Sistema creado por :</th>
							<td><?php echo $this->Html->tag('span', $sistema['CreatedBy']['name'].' '.$sistema['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$sistema['CreatedBy']['id'])));?></td>
						</tr>
						<tr>
							<th>Fecha de creación :</th>
							<td><?php echo $sistema['System']['created']; ?></td>
						</tr>
						<tr>
							<th>Ultima modificación :</th>
							<td><?php echo $sistema['System']['modified']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
				
		
	</div>
	
</div>


<h2 class="link_admin link_permission">Perfiles del sistema <?php echo $sistema['System']['system_name']; ?></h2>
<div id="TableView">
		<?php
			$x=0;
			foreach($data as $profile)
			{
				echo $this->Html->tag('span', 'Perfil '.$profile['Profile']['profile_name'], array('class' => 'link_admin tip_tip link_profile pointer', 'title' => $profile['Profile']['description']));
				
				if(isset($profile['Permission']))
				{
					echo "Permisos del perfil ".$profile['Profile']['profile_name']."<br>";
					
					foreach($profile['Permission'] as $permission)
					{
						echo $this->Html->tag('span', $permission['type_permission'], array('class' => 'link_admin tip_tip link_permission pointer', 'title' => $permission['description'])).'<br>';
					}
				}
				else
				{
					echo $this->Html->tag('span', 'Este perfil no tiene permisos asociados', array('class' => 'link_admin tip_tip link_fr_rejected pointer')).'<br>';
				}
				
				echo "<br><br><br>";
			}
		?>
</div>