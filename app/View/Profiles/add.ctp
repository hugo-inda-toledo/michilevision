<h3><?php echo $this->html->link('Perfiles','/profiles/'); ?> &gt; Agregar nuevo perfil</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_add">Agregar Nuevo Perfil</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/profiles/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de perfiles'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('Profile');
			echo '<table>
				<tr>
					<td>';
			
			echo $this->form->input('system_id', array('label' => 'Sistema para el perfil', 'type' => 'select', 'options' => $sistemas, 'empty' => ''));
			echo $this->form->input('profile_name', array('label' => 'Nombre del perfil'));
			
			echo '</td>
				<td>';
					
			echo $this->form->input('description', array('label' => 'Descripcion', 'rows' => '3'));
			
			echo '<input type="submit" value="Guardar Perfil"></input></td>
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>