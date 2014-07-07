<h3><?php echo $this->html->link('Permisos','/permissions/'); ?> &gt; <?php echo $this->html->link('Asignaciones a usuarios','/user_permissions/'); ?> &gt; Agregar asignación</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_add">Asignar permiso temporal a usuario</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/user_permissions/', array('class' => 'tip_tip_default link_admin link_back', 'title' => 'Volver al listado de asignaciones'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('UserPermission');
			echo '<table>
				<tr>
					<td>';
			
			echo $this->form->input('user_id', array('label' => 'Usuario a asignar', 'type' =>'select', 'options' => $usuarios, 'onChange' => 'javascript:getSistemasPermisos();', 'id' => 'select_usuarios', 'empty' => ' '));
			
			echo '</td>
					<td><div id="permisos"></div></td>
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>