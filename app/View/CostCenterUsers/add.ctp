<h3><?php echo $this->html->link('Centro de costos','/cost_centers/'); ?> &gt; <?php echo $this->html->link('Asignaciones a usuarios','/cost_center_users/'); ?> &gt; Crear nueva asignación</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_add">Asignar centro de costo a usuario</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/cost_center_users/', array('class' => 'tip_tip_default link_admin link_back', 'title' => 'Volver al listado de asignaciones de centro de costos'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('CostCenterUser');
			echo '<table>
				<tr>
					<td>';
			
			echo $this->form->input('user_id', array('type'=>'select', 'options'=> $resultUsuarios, 'empty' => '', 'id' => 'SelectUsuario', 'label' => 'Usuario Asociado', 'onChange' => 'Javascript:getUsuarios();'));
			
			echo '</td>
					<td><div id="sistemas"></div></td>
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>




