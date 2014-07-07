<h3><?php echo $html->link('Permisos','/permissions/'); ?> &gt; <?php echo $html->link('Asignaciones a usuarios','/user_permissions/'); ?> &gt; Editar asignación</h3>

<div class="ViewTitleOptions">
	<h2 class="link_admin link_add">Editar asignación</h2>
</div>
<div class="ViewTitleOptions">
	<table>
		<tr>
			<td class="border_none">
				<?php
				/*
				echo $form->create('UsuarioPermiso');
				echo $form->input('Usuario.UsuarioAsociado', array('type' =>'select', 'options' => $usuarios, 'default' => $usuarioPermiso['UsuarioPermiso']['usuario_id']));
				echo $form->input('Permiso.PermisoAsociado', array('type' =>'select', 'options' => $permisos, 'default' => $usuarioPermiso['UsuarioPermiso']['permiso_id']));
				echo $html->link('Su sistema no tiene permiso?', "/permisos/add/", array('class' => 'link_admin link_add', 'title' => 'Asignalo!'));
				echo $form->input('fecha_inicio', array( 'type'=>'text', 'id'=>'datepicker-desde', 'readonly' => 'readonly'));
				echo $form->input('fecha_termino', array( 'type'=>'text', 'id'=>'datepicker-hasta', 'readonly' => 'readonly'));
				echo $form->end('Guardar Asignacion');
				*/
				?>
			</td>	
		</tr>
	</table>
</div>

<div class="ViewTitleOptions">
	<br /><br />
	<?php
		echo $html->link('Volver al listado','/user_permissions/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de asignaciones'));
	?>
</div>