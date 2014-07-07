<h3><?php echo $this->html->link('Gerencias','/managements/'); ?> &gt; Ver Ficha Gerencia</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_user"><?php echo $gerencia['Management']['management_name'];?></h2>
	<ul>
		<li><?php echo $this->html->link('Editar esta gerencia','/managements/edit/'.$gerencia['Management']['id'], array('class' => 'tip_tip_default link_admin link_edit','title' => 'Editar ficha de '.$gerencia['Management']['management_name']));?></li>
		<li><?php echo $this->html->link('Volver al listado','/managements/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de gerencias'));?></li>
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
							<td><?php echo $gerencia['Management']['id']; ?></td>
						</tr>
						<tr>
							<th>Nombre de la gerencia :</th>
							<td><?php echo $gerencia['Management']['management_name']; ?></td>
						</tr>
						<tr>
							<th>Gerente :</th>
							<td><?php echo $this->Html->tag('span', $gerencia['User']['name'].' '.$gerencia['User']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$gerencia['User']['id'])));?></td>
						</tr>
						<tr>
							<th>Tipo de autorización :</th>
							<td><?php echo $gerencia['Authorization']['name'];?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Centro de costo padre :</th>
							<td><?php echo $gerencia['Management']['cost_center_father_code']; ?></td>
						</tr>
						<tr>
							<th>Gerencia creada por :</th>
							<td><?php echo $this->Html->tag('span', $gerencia['CreatedBy']['name'].' '.$gerencia['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$gerencia['CreatedBy']['id'])));?></td>
						</tr>
						<tr>
							<th>Fecha de creación :</th>
							<td><?php echo $gerencia['Management']['created']; ?></td>
						</tr>
						<tr>
							<th>Ultima modificación :</th>
							<td><?php echo $gerencia['Management']['modified']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
				
		
	</div>
	
</div>