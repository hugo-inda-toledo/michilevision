<!DOCTYPE html>
<style type="text/css" >
      #map_canvas {
              width:970px;
            height:300px;
     }
</style>



<?php
	$api_key = "AIzaSyCpD-hr32Wmr7AG0C0Q0kXX7gF1aXZDYfs";
	
	echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.$api_key.'&sensor=false');
?>

<script type="text/javascript">
	function initialize() 
	{
		var latlng = new google.maps.LatLng(<?php echo $this->request->data['Address']['latitude']; ?>,  <?php echo $this->request->data['Address']['longitude'];?>);
		
		var myOptions = {
										zoom: 16,
										center: latlng,
										mapTypeId: google.maps.MapTypeId.ROADMAP
									};
		
		var marker = new google.maps.Marker({
																position: latlng,
																title:"<?php echo $this->request->data['Address']['full_address']; ?>",
															});

									
		var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		
		marker.setMap(map);
	}
	google.maps.event.addDomListener(window, "load", initialize);
</script>

<h3><?php echo $this->html->link('Direcciones','/addresses/'); ?> &gt; Ver Detalles de la dirección.</h3>
<?php echo $this->Session->flash();  ?>

<div id="TitleView">
	<h2 class="link_admin link_region"><?php echo $this->request->data['Address']['address_title'];?></h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/addresses/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de direcciones.'));?></li>
	</ul>
</div>	

<div id="ManagementViewHolder" onload="initialize()">
	
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $this->request->data['Address']['id']; ?></td>
						</tr>
						<tr>
							<th>Calle :</th>
							<td><?php echo $this->request->data['Address']['address_name']; ?></td>
						</tr>
						<tr>
							<th>Número :</th>
							<td><?php echo $this->request->data['Address']['number']; ?></td>
						</tr>
						<?php
						if($this->request->data['Address']['apart_block'] != null || $this->request->data['Address']['apart_block'] != "")
						{
						?>
						<tr>
							<th>Depto / Oficina / Casa :</th>
							<td><?php echo $this->request->data['Address']['apart_block']; ?></td>
						</tr>
						<?php
						}
						?>
						<tr>
							<th>Comuna :</th>
							<td><?php echo $this->request->data['Commune']['commune_name']?></td>
						</tr>					
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Provincia :</th>
							<td><?php echo $this->request->data['Province']['province_name']?></td>
						</tr>
						<tr>
							<th>Región :</th>
							<td><?php echo $this->request->data['Region']['region_name']?></td>
						</tr>
						<tr>
							<th>Latitud :</th>
							<td><?php echo $this->request->data['Address']['latitude']?></td>
						</tr>
						<tr>
							<th>Longitud :</th>
							<td><?php echo $this->request->data['Address']['longitude']?></td>
						</tr>					
					</table>
				</td>
			</tr>
			<?php
				if($this->request->data['User'] != false)
				{
			?>
					<tr>
						<td>
							Usuarios Asociados a esta dirección 
							<table>
								<?php
								foreach($this->request->data['User'] as $user)
								{
									echo "<tr></tr><th>".$user['name']." ".$user['first_lastname']." ".$user['second_lastname']."</th><td>".$this->Html->link('Ver Perfil', '/users/view/'.$user['id'])."</td>";
								}
								?>
							</table>
						</td>
					</tr>
			<?php
				}
			?>
		</table>
	</div>
</div>
<div id="map_canvas"></div>