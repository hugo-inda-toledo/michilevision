<h3><?php echo $this->html->link('Permisos','/permissions/'); ?> &gt; Editar permiso</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_edit">Editar Permiso Existente</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/permissions/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de permisos'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('Permission');
			echo '<table>
				<tr>
					<td>';
			
			echo $this->form->input('type_permission', array('label' => 'Nombre del permiso'));
			echo $this->form->input('system_id', array('label' => 'Sistema Asociado', 'type' => 'select', 'options' => $sistemas));
			echo $this->form->input('level', array('label' => 'Nivel', 'type' => 'select', 'options' => array('1' => '1', '2' => '2', '3' => '3')));
			
			echo '</td>
				<td>';
					
			echo $this->form->input('description', array('label' => 'Descripcion del permiso', 'rows' => '3'));
			
			echo '<input type="submit" value="Guardar Permiso"></input></td>
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>