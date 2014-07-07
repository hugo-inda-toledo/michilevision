<h3><?php echo $this->html->link('Autorizaciones','/authorizations/'); ?> &gt; Ver ficha del circuito</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_system">Datos de la autorización</h2>
	<ul>
		<li>
			<?php 
					if($authorization['Authorization']['active'] == 1)
						echo $this->Html->link('Deshabilitar', '/authorizations/disable/'.$authorization['Authorization']['id'].'/view', array('title' => 'Deshabilita esta autorización', 'class' => 'tip_tip_default link_admin link_disable'));
					else
						echo $this->Html->link('Habilitar', '/authorizations/enable/'.$authorization['Authorization']['id'].'/view', array('title' => 'Habilita esta autorización', 'class' => 'tip_tip_default link_admin link_enable'));
				?>
		</li>
		<li>
			<?php echo $this->html->link('Volver al listado', '/authorizations/', array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de autorizaciones'));?>
		</li>
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
							<td><?php echo $authorization['Authorization']['id']; ?></td>
						</tr>
						<tr>
							<th>Nombre :</th>
							<td><?php echo $authorization['Authorization']['name']; ?></td>
						</tr>
						<tr>
							<th>Activo :</th>
							<td><?php 
										if($authorization['Authorization']['active'] == 1)
											echo "Si";
										else
											echo "No";
									?>
							</td>
						</tr>
						<tr>
							<th>Autorización creada por :</th>
							<td><?php echo $this->Html->tag('span', $authorization['CreatedBy']['name'].' '.$authorization['CreatedBy']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$authorization['CreatedBy']['id'])));?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
	</div>
</div>