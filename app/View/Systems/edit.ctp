<h3><?php echo $this->html->link('Sistemas','/systems/'); ?> &gt; Editar ficha sistema</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_edit">Editar Sistema</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/systems/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de sistemas'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('System');
			echo '<table>
				<tr>
					<td>';
			
			echo $this->form->input('id', array('label' => 'id', 'type' => 'hidden'));
			echo $this->form->input('system_name', array('label' => 'Nombre del sistema'));
			echo $this->form->input('system_description', array('label' => 'Descripción de la utilidad del sistema', 'rows' => '3'));
			echo '</td>
				<td>';
					
			echo $this->form->input('table_name', array('label' => 'Nombre de la tabla'));
			echo $this->form->input('css_class_url', array('label' => 'Clase CSS de la tabla'));
			echo $this->form->input('use_cost_center', array('label' => 'Usa centro de costos', 'type' => 'select', 'options' => array(1 => 'Si', 0 => 'No')));
			
			echo '<br /><br />
				<input type="submit" value="Guardar Sistema"></input></td>
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>