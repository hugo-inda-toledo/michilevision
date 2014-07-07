<h3><?php echo $this->html->link('Secciones de Sistemas','/system_sections/'); ?> &gt; Detalles</h3>
<?php echo $this->Session->flash();  ?>

<div id="TitleView">
	<h2 class="link_admin link_system">Datos de la seccion <?php echo $section['SystemSection']['section_name'].' para el sistema '.$section['System']['system_name'];?></h2>
	<ul>
		<li><?php echo $this->html->link('Editar datos de la seccion', '/system_sections/edit/'.$section['SystemSection']['id'], array('class' => 'tip_tip_default link_admin link_edit','title' => 'Editar datos de la seccion '.$section['SystemSection']['section_name']));?></li>
		<li><?php echo $this->html->link('Volver al listado', '/system_sections/', array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de secciones de sistemas'));?></li>
	</ul>
</div>	

<div id="SystemViewHolder">
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $section['SystemSection']['id']; ?></td>
						</tr>
						<tr>
							<th>Nombre de la seccion :</th>
							<td><?php echo $section['SystemSection']['section_name']; ?></td>
						</tr>
						<tr>
							<th>Sistema asociado :</th>
							<td><?php echo $section['System']['system_name']; ?></td>
						</tr>
						<tr>
							<th>Funcion PHP :</th>
							<td><?php echo $section['SystemSection']['section_function']; ?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Fecha de creaci&oacute;n:</th>
							<td><?php echo $section['SystemSection']['created']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
	</div>
</div>