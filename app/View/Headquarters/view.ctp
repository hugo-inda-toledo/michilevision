<h3><?php echo $this->html->link('Gerencias','/managements/'); ?> &gt; <?php echo $this->html->link('Jefaturas','/headquarters/'); ?> &gt; Ver ficha</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_headquarter">Datos de la jefatura</h2>
	<ul>
		<li><?php echo $this->html->link('Editar esta jefatura','/headquarters/edit/'.$jefatura['Headquarter']['id'], array('class' => 'tip_tip_default link_admin link_edit','title' => 'Editar ficha de esta jefatura'));?></li>
		<li><?php echo $this->html->link('Volver al listado','/headquarters/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de jefaturas'));?></li>
	</ul>
</div>	




<div id="HeadquarterViewHolder">
	
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $jefatura['Headquarter']['id']; ?></td>
						</tr>
						<tr>
							<th>Nombre de la gerencia :</th>
							<td><?php echo $jefatura['Headquarter']['headquarter_name']; ?></td>
						</tr>
						<tr>
							<th>Centro de costo :</th>
							<td><?php echo $jefatura['Headquarter']['cost_center_code']; ?></td>
						</tr>
						<tr>
							<th>Jefe de la Jefatura :</th>
							<td><?php echo $this->Html->tag('span', $jefatura['User']['name'].' '.$jefatura['User']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$jefatura['User']['id'])));?></td>
						</tr>
						<tr>
							<th>Gerencia :</th>
							<td><?php echo $this->Html->tag('span', $jefatura['Management']['management_name'], array('class' => 'link_admin tip_tip link_management pointer','title' => $this->RequestAction('/external_functions/showGerenciaTable/'.$jefatura['Management']['id'])));?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Activo :</th>
							<td><?php echo $jefatura['Headquarter']['active']; ?></td>
						</tr>
						<tr>
							<th>Jefatura creada por :</th>
							<td><?php echo $this->Html->tag('span', $jefatura['CreatedBy']['name'].' '.$jefatura['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$jefatura['CreatedBy']['id'])));?></td>
						</tr>
						<tr>
							<th>Fecha de creación :</th>
							<td><?php echo $jefatura['Headquarter']['created']; ?></td>
						</tr>
						<tr>
							<th>Ultima modificación :</th>
							<td><?php echo $jefatura['Headquarter']['modified']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
				
		
	</div>
	
</div>