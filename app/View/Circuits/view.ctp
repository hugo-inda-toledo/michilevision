<h3><?php echo $this->html->link('Circuitos','/circuits/'); ?> &gt; Ver ficha del circuito</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_system">Datos del circuito</h2>
	<ul>
		<li><?php echo $this->html->link('Editar circuito','/circuits/edit/'.$circuit['Circuit']['id'], array('class' => 'tip_tip_default link_admin link_edit','title' => 'Editar datos del circuito'));?></li>
		<li><?php echo $this->html->link('Volver al listado', '/circuits/', array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de circuitos'));?></li>
	</ul>
</div>	

<div id="CircuitViewHolder">
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $circuit['Circuit']['id']; ?></td>
						</tr>
						<tr>
							<th>Usuario firmante :</th>
							<td><?php echo $this->Html->tag('span', $circuit['User']['name'].' '.$circuit['User']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$circuit['User']['id'])));?></td>
						</tr>
						<tr>
							<th>Cargo del firmante :</th>
							<td><?php echo $circuit['Circuit']['position'];?></td>
						</tr>
						<tr>
							<th>Sistema Asociado :</th>
							<td><?php echo $this->Html->tag('span', $circuit['System']['system_name'], array('class' => 'link_admin tip_tip link_system pointer', 'title' => $this->RequestAction('/external_functions/showSistemaTable/'.$circuit['Circuit']['system_id'])));?></td>
						</tr>
						<tr>
							<th>Tipo de autorización :</th>
							<td><?php echo $circuit['Authorization']['name'];?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Nivel :</th>
							<td><?php echo $circuit['Circuit']['level'];?></td>
						</tr>
						<tr>
							<th>Circuito creado por :</th>
							<td><?php echo $this->Html->tag('span', $circuit['CreatedBy']['name'].' '.$circuit['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$circuit['CreatedBy']['id'])));?></td>
						</tr>
						<tr>
							<th>Fecha de creación :</th>
							<td><?php echo $circuit['Circuit']['created']; ?></td>
						</tr>
						<tr>
							<th>Ultima modificación :</th>
							<td><?php echo $circuit['Circuit']['modified']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
	</div>
</div>