<h3><?php echo $this->html->link('Centro de costos','/cost_centers/'); ?> &gt; <?php echo $this->html->link('Asignaciones a usuarios','/cost_center_users/'); ?> &gt; Ver ficha asignación</h3>
<?php echo $this->Session->flash();?>


<div id="TitleView">
	<h2 class="link_admin link_connect">Detalles de la asignación</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/cost_center_users/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de permisos'));?></li>
	</ul>
</div>	



<div id="CostCenterUserViewHolder">
	
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $asignacion['CostCenterUser']['id']; ?></td>
						</tr>
						<tr>
							<th>Centro de costo :</th>
							<td><?php echo $this->Html->tag('span', $asignacion['CostCenter']['cost_center_name'], array('class' => 'link_admin tip_tip link_cost_center pointer','title' => $this->RequestAction('/external_functions/showCentroCostoTable/'.$asignacion['CostCenterUser']['cost_center_id'])));?></td>
						</tr>
						<tr>
							<th>Usuario asignado :</th>
							<td><?php echo $this->Html->tag('span', $asignacion['User']['name'].' '.$asignacion['User']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$asignacion['CostCenterUser']['user_id'])));?></td>
						</tr>
						<tr>
							<th>Sistema asociado :</th>
							<td><?php echo $this->Html->tag('span', $asignacion['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer','title' => $this->RequestAction('/external_functions/showSistemaTable/'.$asignacion['System']['id'])));?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Centro de costo vigente? :</th>
							<td><?php echo $asignacion['CostCenter']['valid']; ?></td>
						</tr>
						<tr>
							<th>Creado por :</th>
							<td><?php echo $this->Html->tag('span', $asignacion['CreatedBy']['name'].' '.$asignacion['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$asignacion['CreatedBy']['id'])));?></td>
						</tr>
						<tr>
							<th>Fecha de creación :</th>
							<td><?php echo $asignacion['CostCenterUser']['created']; ?></td>
						</tr>
						<tr>
							<th>Ultima modificación :</th>
							<td><?php echo $asignacion['CostCenterUser']['modified']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
				
		
	</div>
	
</div>