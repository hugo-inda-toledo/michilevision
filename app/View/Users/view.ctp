<style>
/*#thumb_user{position:absolute; z-index:1; width:250px; height:200px;} */
</style>

<h3><?php echo $this->html->link('Usuarios','/users/'); ?> &gt; Ver Ficha Usuario  &gt; <?php echo $this->data['User']['name'].' '.$this->data['User']['first_lastname'];?></h3>


<div id="userViewHolder">
	
	
	<div id="TitleView">
		<h2 class="link_admin link_user">Datos Personales del usuario <?php echo $this->data['User']['name'].' '.$this->data['User']['first_lastname'];?></h2>
		<ul>
			<li><?php echo $this->html->link('Editar usuario', '/users/edit/'. $this->data['User']['id'], array('title' => 'Editar ficha de '.$this->data['User']['name'].' '.$this->data['User']['first_lastname'].' '.$this->data['User']['second_lastname'],'class' => 'tip_tip_default link_admin link_edit'));?></li>
			<li><?php echo $this->html->link('Volver al listado','/users/',array('title' => 'Volver al listado de usuarios','class' => 'tip_tip_default link_admin link_back'));?></li>
		</ul>
	</div>	
	
	
	
	<div class="userViewHolderRow">
		
		<div id="TableView" class="personal_info">
			<table>
				<tr>
					<th>Identificador :</th>
					<td><?php echo $this->data['User']['id']; ?></td>
				</tr>
				<tr>
					<th>Nombres :</th>
					<td><?php echo $this->data['User']['name']; ?></td>
				</tr>
				<tr>
					<th>Apellidos :</th>
					<td><?php echo $this->data['User']['first_lastname'].' '.$this->data['User']['second_lastname']; ?></td>
				</tr>
				<tr>
					<th>Rut :</th>
					<td><?php echo $this->data['User']['dni']; ?></td>
				</tr>
				<tr>
					<th>F. Nacimiento :</th>
					<td><?php echo $this->data['User']['birthday']; ?></td>
				</tr>
				<tr>
					<th>Email :</th>
					<td><?php echo $this->html->link($this->RequestAction('/external_functions/recortar_texto_simple/'.$this->data['User']['email'].'/20'),'mailto:'.$this->data['User']['email'], array('title' => 'Enviar correo electrónico a '.$this->data['User']['name'].' '.$this->data['User']['first_lastname'].' '.$this->data['User']['second_lastname'],'class' => 'tip_tip_default link_admin link_email'));?></td>
				</tr>
				<tr>
					<th>Usuario planta :</th>
					<td><?php echo $this->data['User']['plant'];?></td>
				</tr>
				<tr>
					<th>Activo :</th>
					<td><?php echo $this->data['User']['active'];?></td>
				</tr>
			</table>
		</div>
		
		
		<div id="TableView" class="personal_info">
			<table>
				<tr>
				<th>Cargo :</th>
					<td><?php echo $this->data['Position']['position']; ?></td>
				</tr>
				<tr>
					<th>Gerencia :</th>
					<td><?php echo $this->Html->tag('span', $this->data['Management']['management_name'], array('class' => 'link_admin tip_tip link_management pointer','title' => $this->RequestAction('/external_functions/showGerenciaTable/'.$this->data['Management']['id'])));?></td>
				</tr>
				<tr>
					<th>Jefatura :</th>
					<td>
						<?php 
							if($this->data['Headquarter']['headquarter_name'] != '' || $this->data['Headquarter']['headquarter_name'] != null)
								echo $this->Html->tag('span', $this->data['Headquarter']['headquarter_name'], array('class' => 'link_admin tip_tip link_headquarter pointer','title' => $this->RequestAction('/external_functions/showHeadquarterTable/'.$this->data['Headquarter']['id'])));
							else
								echo "N/A";
						?>
					</td>
				</tr>
				<tr>
					<th>Username :</th>
					<td><?php echo $this->data['User']['username']; ?></td>
				</tr>
				<tr>
					<th>Ficha :</th>
					<td><?php echo $this->data['User']['token']; ?></td>
				</tr>
				<tr>
					<th>Usuario creado por :</th>
					<td><?php echo $this->Html->tag('span', $this->data['CreatedBy']['name'].' '.$this->data['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$this->data['CreatedBy']['id'])));?></td>
				</tr>
				<tr>
					<th>Fecha de creación :</th>
					<td><?php echo  $this->data['User']['created']; ?></td>
				</tr>
				<tr>
					<th>Ultima modificación :</th>
					<td><?php echo  $this->data['User']['modified']; ?></td>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<td>&nbsp;</td>
				</tr>
			</table>
		</div>
		
		
		<?php 
			if($this->data['User']['picture'] != '')
				echo $this->html->image($this->data['User']['picture'], array('id' => 'thumb_user','alt' => $this->data['User']['name'].' '.$this->data['User']['first_lastname'].' '.$this->data['User']['second_lastname'], 'class' => 'profilePic'));
			else
				echo $this->html->image('user_200x200.png', array('id' => 'thumb_user','alt' => $this->data['User']['name'].' '.$this->data['User']['first_lastname'].' '.$this->data['User']['second_lastname'], 'class' => 'profilePic'));
		?>

		
	</div>



	
	
	<div class="userViewHolderRow">
		
		<h2 class="link_admin link_system">Sistemas asociados al usuario <?php echo $this->data['User']['name'].' '.$this->data['User']['first_lastname'].' '.$this->data['User']['second_lastname'];?></h2>
		
		<div id="TableView" class="associated_systems">
			
			<table>
			<?php
			if(count($this->data['UserSystem']) != 0){
				
				foreach ($this->data['UserSystem'] as $system){
				$s = 0; $c_costos = ''; $profiles = array(); $p = 0;
				$perfiles = '<ul>';
					
					foreach($this->data['UserProfile'] as $profile){
						if($profile['System']['id'] == $system['system_id']){
							if($profile['user_id'] == $system['user_id']){
								$perfiles .= '<li>'.$profile['profile_name'].'</li>';
								$p = $p + 1;
							}
						}
					}

				$perfiles .= '</ul>';


					// Centros de costos asociados al sistema...
					if (count($this->data['CostCenterUser']) != 0){
					$c_costos = '<ul>';
					
						foreach($this->data['CostCenterUser'] as $cost_center){
							if($cost_center['system_id'] == $system['system_id']){
								$c_costos .= '<li>
										'.$this->Html->tag('span', $cost_center['cost_center_code'].' | '.ucwords(mb_strtolower($cost_center['cost_center_name'])), array('class' => 'link_admin tip_tip link_cost_center pointer','title' => $this->RequestAction('/external_functions/showCentroCostoTable/'.$cost_center['cost_center_id']))).'
									</li>';
								$s = $s + 1;
							}
						}
						
						if($s == 0){
							$c_costos .= '<li class="link_admin link_alert">Este sistema no tiene centros de costos asociados.</li>';
						}
					
					$c_costos .= '</ul>';	
					}

				
				echo '<tr>
						<td class="td_system">'.$this->Html->tag('strong', $system['system_name'], array('class' => 'link_admin tip_tip '.$system['css_class_url'].' pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$system['system_id']))).'</td>
						<td class="td_profile">'.$perfiles.'</td>
						<td class="td_costcenter">'.$c_costos.'</td>
					</tr>';
				
				}
			}
						
			else {
			echo '<tr>
					<td>Este usuario no tiene sistemas asociados.</td>
				</tr>';
			}
			
			?>
			</table>	
		</div>
	</div>	
	

</div>



