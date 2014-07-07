<h3><?php echo $this->html->link('Regiones','/regions/'); ?> &gt; Ver Ficha Región</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_region"><?php echo $this->request->data['Region']['region_name'];?></h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/regions/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de regiones'));?></li>
	</ul>
</div>	




<div id="ManagementViewHolder">
	
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $this->request->data['Region']['id']; ?></td>
						</tr>
						<tr>
							<th>Nombre de la región :</th>
							<td><?php echo $this->request->data['Region']['region_name']; ?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Provincias de <?php echo $this->request->data['Region']['region_name']?></th>
							<td></td>
						</tr>
						<?php
							foreach($this->request->data['Province'] as $province)
							{
								echo "<tr></tr><th></th><td>".$this->Html->link($province['province_name'], '/provinces/view/'.$province['id'])."</td>";
							}
						?>
					</table>
				</td>
			</tr>
		</table>	
	</div>
</div>