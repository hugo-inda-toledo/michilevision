<h3><?php echo $this->html->link('Sistemas','/systems/'); ?> &gt; <?php echo $this->html->link('Asignación de sistemas','/user_systems/'); ?> &gt; Editar asignación</h3>
<?php echo $this->Session->flash();  ?>

<div id="TitleView">
	<h2 class="link_admin link_edit">Editar Asignación Existente</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/user_systems/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de asignaciones'));?></li>
	</ul>
</div>

<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('UserSystem');
			echo '<table>
				<tr>
					<td>';
			
			echo $this->form->input('system_id', array('label' => 'Sistema para asignar', 'type' =>'select', 'options' => $sistemas, 'default' => $usuarioSistema['UserSystem']['system_id']));
			echo '</td>
				<td>';
			
			echo $this->form->input('user_id', array('label' =>'Usuario asociado', 'type' =>'select', 'options' => $usuarios, 'default' => $usuarioSistema['UserSystem']['user_id']));		
			
			echo '<br /><br />
				<input type="submit" value="Guardar Asignación"></input></td>
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>