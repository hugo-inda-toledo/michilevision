<h3><?php echo $html->link('Asignaciones de perfiles','/user_profiles/'); ?> &gt; Editar asignación</h3>

<div class="ViewTitleOptions">
	<h2 class="link_admin link_add_profile">Asignar perfil a usuario</h2>
</div>
<div class="ViewTitleOptions">
	<table>
		<tr>
			<td class="border_none">
				<?php
					echo $form->create('UsuarioProfile');
					echo $form->input('Usuario.UsuarioAsociado', array('type' =>'select', 'options' => $usuarios, 'default' => $usuarioProfile['UsuarioProfile']['usuario_id']));
					echo $form->input('Profile.ProfileAsociado', array('type' =>'select', 'options' => $profiles, 'default' => $usuarioProfile['UsuarioProfile']['profile_id']));
					echo $html->link('Su perfil no tiene un sistema?', "/profiles/add/", array('class' => 'link_admin link_add ', 'title' => 'Asocialo!'));
					echo $form->end('Guardar Asignacion');
				?>
			</td>	
		</tr>
	</table>
</div>

<div class="ViewTitleOptions">
	<br /><br />
	<?php
		echo $html->link('Volver al listado','/usuario_profiles/',array('class' => 'link_admin link_back','title' => 'Volver al listado de asignaciones'));
	?>
</div>