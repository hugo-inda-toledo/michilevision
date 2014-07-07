<h3><?php echo $this->html->link('Centro de costos','/cost_centers/'); ?> &gt; Ver ficha centro de costos</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_system">Datos de <?php echo $cost_center['CostCenter']['cost_center_name']; ?></h2>
	<ul>
		<li><?php echo $this->html->link('Editar esta ficha','/cost_centers/edit/'.$cost_center['CostCenter']['id'], array('class' => 'tip_tip_default link_admin link_edit','title' => 'Editar centro de costos '.$cost_center['CostCenter']['cost_center_name']));?></li>
		<li><?php echo $this->html->link('Volver al listado','/cost_centers/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de centros de costos'));?></li>
	</ul>
</div>	



<div id="CostCenterViewHolder">
	
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $cost_center['CostCenter']['id']; ?></td>
						</tr>
						<tr>
							<th>Nombre centro costo :</th>
							<td><?php echo $cost_center['CostCenter']['cost_center_name']; ?></td>
						</tr>
						<tr>
							<th>Código :</th>
							<td><?php echo $cost_center['CostCenter']['cost_center_code']; ?></td>
						</tr>
						<tr>
							<th>Gerencia asociada :</th>
							<td>
								<?php
								if($cost_center['CostCenter']['management_id'] != NULL || $cost_center['CostCenter']['management_id'] != 0)
									echo $this->html->link($cost_center['Management']['management_name'], '#', array('title' => $this->RequestAction('/external_functions/showGerenciaTable/'.$cost_center['Management']['id']), 'class' => 'link_admin tip_tip link_zoom'));	
								else
									echo 'N/A';
								?></td>
						</tr>
						<tr>
							<th>Nivel :</th>
							<td><?php echo $cost_center['CostCenter']['level']; ?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Vigente :</th>
							<td><?php echo $cost_center['CostCenter']['valid']; ?></td>
						</tr>
						<tr>
							<th>Creado por :</th>
					   		<td><?php echo $this->Html->tag('span', $cost_center['CreatedBy']['name'].' '.$cost_center['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$cost_center['CreatedBy']['id'])));?></td>
						</tr>
						<tr>
							<th>Fecha de creación :</th>
							<td><?php echo $cost_center['CostCenter']['created']; ?></td>
						</tr>
						<tr>
							<th>Ultima modificación :</th>
							<td><?php echo $cost_center['CostCenter']['modified']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
				
		
	</div>
	
</div>