<h3><?php echo $this->html->link('Perfiles','/profiles/'); ?> &gt; <?php echo $this->html->link('Asignaciones a usuarios','/user_profiles/'); ?> &gt; Asignar perfil</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_add_profile">Asignar perfil a usuario</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/user_profiles/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de asignaciones'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('UserProfile');
			echo '<table>
				<tr>
					<td>';
			
			echo $this->form->input('UserProfile.user_id', array('label' => 'Usuario', 'id' => 'SelectUsuario', 'empty' => '', 'type' =>'select', 'options' => $usuarios, 'onChange' => 'Javascript:getSistemasForProfiles();'));
			
			echo '</td>
					<td><div id="sistemas"></div></td>
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>