<h3><?php echo $this->html->link('Secciones de sistemas','/system_sections/'); ?> &gt; Agregar sección</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_add">Agregar Sección a un sistema</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/system_sections/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de secciones'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php	echo $this->form->create('SystemSection'); ?>
			<table>
				<tr>
					<td>
						<?php echo $this->form->input('system_id', array('label' => 'Sistema asociado a la seccion', 'type' =>'select', 'options' => $systems, 'empty' => '')); ?>
					</td>
					<td>
						<?php echo $this->form->input('section_name', array('label' => 'Nombre de la seccion', 'type' =>'text')); ?>
					</td>
					<td>
						<?php echo $this->form->input('section_function', array('label' =>'Nombre de la funcion PHP', 'type' => 'text')); ?>
					</td>
				</tr>
				<tr>
					<td><?php echo $this->form->end('Agregar Seccion'); ?></td>
				</tr>
			</table>
		</td>	
	</tr>
</table>