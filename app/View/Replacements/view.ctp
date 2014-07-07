<h3><?php echo $this->html->link('Reemplazos','/replacements/'); ?> &gt; Ver ficha</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_system">Datos del reemplazo</h2>
	<ul>
		<li>
		<?php
			if($reemplazo['Replacement']['active'] == 'Si')
				echo $this->Html->link('Deshabilitar', '/replacements/disable/'.$reemplazo['Replacement']['id'], array('title' => 'Deshabilita esta asignacion', 'class' => 'tip_tip_default link_admin link_disable'));
			else
				echo $this->Html->link('Habilitar', '/replacements/enable/'.$reemplazo['Replacement']['id'], array('title' => 'Habilita esta asignacion', 'class' => 'tip_tip_default link_admin link_enable'));
		?>		
		</li>
		<li><?php echo $this->html->link('Volver al listado', '/replacements/', array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de reemplazos'));?></li>
	</ul>
</div>	



<div id="ReplacementViewHolder">
	
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $reemplazo['Replacement']['id']; ?></td>
						</tr>
						<tr>
							<th>Tipo de reemplazo :</th>
							<td><?php echo $reemplazo['TypeReplacement']['type_replacement']; ?></td>
						</tr>
						<tr>
							<th>Usuario reemplazado :</th>
							<td><?php echo $this->Html->tag('span', $reemplazo['replaced_user']['name'].' '.$reemplazo['replaced_user']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$reemplazo['Replacement']['replaced_user_id'])));?></td>
						</tr>
						<tr>
							<th>Usuario reemplazante :</th>
							<td><?php echo $this->Html->tag('span', $reemplazo['replacing_user']['name'].' '.$reemplazo['replacing_user']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$reemplazo['Replacement']['replacing_user_id'])));?></td>
						</tr>
						<tr>
							<th>Gerencia asociada :</th>
					   		<td><?php echo $this->Html->tag('span', $reemplazo['Management']['management_name'], array('class' => 'link_admin tip_tip link_management pointer','title' => $this->RequestAction('/external_functions/showGerenciaTable/'.$reemplazo['Management']['id'])));?></td>
						</tr>
						<tr>
							<th>Activo :</th>
							<td>
							<?php 
								if($reemplazo['Replacement']['active'] == 'Si')
									echo $this->Html->tag('span', 'Habilitado', array('class' => 'link_admin link_circuit_approved'));
								else
									echo $this->Html->tag('span', 'Deshabilitado', array('class' => 'link_admin link_circuit_rejected'));
							?>
							</td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Fecha de inicio :</th>
							<td><?php echo $this->RequestAction('/external_functions/setDate/'.$reemplazo['Replacement']['start_date']); ?></td>
						</tr>
						<tr>
							<th>Fecha de término :</th>
							<td><?php echo $this->RequestAction('/external_functions/setDate/'.$reemplazo['Replacement']['end_date']); ?></td>
						</tr>
						<tr>
							<th>Reemplazo creado por :</th>
							<td><?php echo $this->Html->tag('span', $reemplazo['CreatedBy']['name'].' '.$reemplazo['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer','title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$reemplazo['CreatedBy']['id'])));?></td>
						</tr>
						<tr>
							<th>Fecha de creación :</th>
							<td><?php echo $reemplazo['Replacement']['created']; ?></td>
						</tr>
						<tr>
							<th>Ultima modificación :</th>
							<td><?php echo $reemplazo['Replacement']['modified']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
				
		
	</div>
	
</div>