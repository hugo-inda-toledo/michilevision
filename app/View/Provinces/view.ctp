<h3><?php echo $this->html->link('Comunas','/communes/'); ?> &gt; Ver Ficha Comuna</h3>
<?php echo $this->Session->flash();  ?>

<div id="TitleView">
	<h2 class="link_admin link_region"><?php echo $this->request->data['Province']['province_name'];?></h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/provinces/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de provincias'));?></li>
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
							<td><?php echo $this->request->data['Province']['id']; ?></td>
						</tr>
						<tr>
							<th>Nombre de la provincia :</th>
							<td><?php echo $this->request->data['Province']['province_name']; ?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Región Asociada :</th>
							<td><?php echo $this->Html->link($this->request->data['Region']['region_name'], '/regions/view/'.$this->request->data['Region']['id'])?></td>
						</tr>
						<tr>
							<th></th>
							<td></td>
						</tr>
						<tr>
							<th>Comunas Asociadas :</th>
							<td></td>
						</tr>
						<?php
							foreach($this->request->data['Commune'] as $commune)
							{
						?>
						<tr>
							<th></th>
							<td><?php echo $this->Html->link($commune['commune_name'], '/communes/view/'.$commune['id'])?></td>
						</tr>
						<?php
							}
						?>
					</table>
				</td>
			</tr>
		</table>	
	</div>
</div>