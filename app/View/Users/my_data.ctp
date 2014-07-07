<style>
/*#thumb_user{position:absolute; z-index:1; width:250px; height:200px;} */
</style>

<style type="text/css" >
      .map_canvas	  {
              width:325px;
            height:100px;
     }
</style>


<?php
	$api_key = "AIzaSyCpD-hr32Wmr7AG0C0Q0kXX7gF1aXZDYfs";
	
	echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.$api_key.'&sensor=false');
?>

<script type="text/javascript">
	function initialize(latitude, longitude, full_address, canvas) 
	{
		var latlng = new google.maps.LatLng(latitude,  longitude);
		
		var myOptions = {
										zoom: 16,
										center: latlng,
										mapTypeId: google.maps.MapTypeId.ROADMAP
									};
		
		var marker = new google.maps.Marker({
																position: latlng,
																title: full_address,
															});

									
		var map = new google.maps.Map(document.getElementById(canvas), myOptions);
		
		marker.setMap(map);
	}
	
	function callMap(latitude, longitude, full_address, canvas)
	{
		google.maps.event.addDomListener(window, "load", initialize(latitude, longitude, full_address, canvas));
	}
</script>

<h3><?php echo $this->html->link('Usuarios','/users/'); ?> &gt; Ver Ficha Usuario  &gt; <?php echo $this->request->data['User']['name'].' '.$this->request->data['User']['first_lastname'];?></h3>

<div id="userViewHolder">
	
	<div id="TitleView">
		<h2 class="link_admin link_user">Datos Personales del usuario <?php echo $this->request->data['User']['name'].' '.$this->request->data['User']['first_lastname'];?></h2>
		<ul>
			<li><?php echo $this->html->link('Volver al dashboard','/dashboard/',array('title' => 'Volver al dashboard','class' => 'tip_tip_default link_admin link_menu'));?></li>
		</ul>
	</div>	
	
	<div class="userViewHolderRow">
		
		<div id="TableView" class="personal_info">
			<table>
				<tr>
					<th>Identificador :</th>
					<td><?php echo $this->request->data['User']['id']; ?></td>
				</tr>
				<tr>
					<th>Nombres :</th>
					<td><?php echo $this->request->data['User']['name']; ?></td>
				</tr>
				<tr>
					<th>Apellidos :</th>
					<td><?php echo $this->request->data['User']['first_lastname'].' '.$this->request->data['User']['second_lastname']; ?></td>
				</tr>
				<tr>
					<th>Rut :</th>
					<td><?php echo $this->request->data['User']['dni']; ?></td>
				</tr>
				<tr>
					<th>F. Nacimiento :</th>
					<td><?php echo $this->request->data['User']['birthday']; ?></td>
				</tr>
				<tr>
					<th>Email :</th>
					<td><?php echo $this->html->link($this->RequestAction('/external_functions/recortar_texto_simple/'.$this->request->data['User']['email'].'/20'),'mailto:'.$this->request->data['User']['email'], array('title' => 'Enviar correo electrónico a '.$this->request->data['User']['name'].' '.$this->request->data['User']['first_lastname'].' '.$this->request->data['User']['second_lastname'],'class' => 'tip_tip_default link_admin link_email'));?></td>
				</tr>
				<tr>
					<th>Usuario planta :</th>
					<td><?php echo $this->request->data['User']['plant'];?></td>
				</tr>
			</table>
		</div>
		
		<div id="TableView" class="personal_info">
			<table>
				<tr>
					<th>Activo :</th>
					<td><?php echo $this->request->data['User']['active'];?></td>
				</tr>
				<tr>
				<th>Cargo :</th>
					<td><?php echo $this->request->data['Position']['position']; ?></td>
				</tr>
				<tr>
					<th>Gerencia :</th>
					<td><?php echo $this->Html->tag('span', $this->request->data['Management']['management_name'], array('class' => 'link_admin tip_tip link_management pointer','title' => $this->RequestAction('/external_functions/showGerenciaTable/'.$this->request->data['Management']['id'])));?></td>
				</tr>
				<tr>
					<th>Ficha :</th>
					<td><?php echo $this->request->data['User']['token']; ?></td>
				</tr>
				<tr>
					<th>Usuario creado por :</th>
					<td><?php echo $this->Html->tag('span', $this->request->data['CreatedBy']['name'].' '.$this->request->data['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$this->request->data['CreatedBy']['id'])));?></td>
				</tr>
				<tr>
					<th>Fecha de creación :</th>
					<td><?php echo  $this->request->data['User']['created']; ?></td>
				</tr>
				<tr>
					<th>Ultima modificación :</th>
					<td><?php echo  $this->request->data['User']['modified']; ?></td>
				</tr>
			</table>
		</div>
		
		<?php 
			if($this->request->data['User']['picture'] != '')
				echo $this->html->image($this->request->data['User']['picture'], array('id' => 'thumb_user','alt' => $this->request->data['User']['name'].' '.$this->request->data['User']['first_lastname'].' '.$this->request->data['User']['second_lastname'], 'class' => 'profilePic'));
			else
				echo $this->html->image('user_200x200.png', array('id' => 'thumb_user','alt' => $this->request->data['User']['name'].' '.$this->request->data['User']['first_lastname'].' '.$this->request->data['User']['second_lastname'], 'class' => 'profilePic'));
		?>
	</div>
	
	<!-- ////////////////////////////////////////////////////////////////////-->
	<?php
			if(isset($this->request->data['Address']))
			{
				if($this->request->data['Address'] != false)
				{
	?>
					<div id="TitleView">
						<h2 class="link_admin link_user">Direcciones de <?php echo $this->request->data['User']['name'].' '.$this->request->data['User']['first_lastname'];?></h2>
					</div>
	<?php
					$x=0;
					foreach($this->request->data['Address'] as $address)
					{
						$canvas = "map_canvas_".$x;
	?>
					<div class="userViewHolderRow">

						<div id="TableView" class="personal_info">
							<table>
								<tr>
									<th>Nombre de referencia :</th>
									<td><?php echo $address['address_title'];?></td>
								</tr>
								<tr>
									<th>Dirección :</th>
									<td><?php echo $address['full_address'];?></td>
								</tr>
							</table>
						</div>
						
						<div id="TableView" class="personal_info">
							<?php 
								echo "<div id='".$canvas."' class='map_canvas'></div>";
								echo '<script type="text/javascript">$(document).ready(callMap("'.$address['latitude'].'", "'.$address['longitude'].'", "'.$address['full_address'].'", "'.$canvas.'"));</script>';
							?>
					</div>
					</div>
	<?php
						$x++;
					}
				}
			}
	?>
	
	
	<!-- ////////////////////////////////////////////////////////////////////-->
	
	
	<div class="userViewHolderRow">
		
		<h2 class="link_admin link_system">Sistemas asociados al usuario <?php echo $this->request->data['User']['name'].' '.$this->request->data['User']['first_lastname'].' '.$this->request->data['User']['second_lastname'];?></h2>
		
		<div id="TableView" class="associated_systems">
			
			<table>
			<?php
			if(count($this->request->data['UserSystem']) != 0){
				
				echo $this->Html->tableHeaders(array('Perfil/es', 'Sistema', 'Centros de costo'));
				
				foreach ($this->request->data['UserSystem'] as $system){
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
					if (count($this->request->data['CostCenterUser']) != 0){
					$c_costos = '<ul>';
					
						foreach($this->request->data['CostCenterUser'] as $cost_center){
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
						<td class="td_profile">'.$perfiles.'</td>
						<td class="td_system">'.$this->Html->tag('strong', $system['system_name'], array('class' => 'link_admin tip_tip '.$system['css_class_url'].' pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$system['system_id']))).'</td>
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

<div class="dashboard-row-2">
	<div class="dashboard-sec">
		<?php echo $this->Html->para(null, 'Acciones'); ?>
		<ul align="center">
			<li>
				<?php echo $this->Html->link('Ir al Dashboard', '/dashboard', array('class' => 'tip_tip_default link_admin32x32 link_menu', 'title' => 'Ir a Mis Datos en la Plataforma'))?>
			</li>
			<li>
				<?php echo $this->Html->link('Ir a Mis Notificaciones', '/notifications/showAll', array('class' => 'tip_tip_default link_admin32x32 link_notification', 'title' => 'Ir a Todas Mis Notificaciones'))?>
			</li>
			<li>
				<?php echo $this->Html->link('Ir a Mis Preferencias', '/users/settings', array('class' => 'tip_tip_default link_admin32x32 link_settings', 'title' => 'Ir a Mis Preferencias'))?>
			</li>
			<li>
				<?php echo $this->Html->link('Cerrar Sesión', '/users/logout', array('class' => 'tip_tip_default link_admin32x32 link_padlock', 'title' => 'Cierra esta sesión de usuario'))?>
			</li>
		</ul>
	</div>
</div>


