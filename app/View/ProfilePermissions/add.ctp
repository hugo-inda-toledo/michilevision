<h3><?php echo $this->html->link('Perfiles','/profiles/'); ?> &gt; <?php echo $this->html->link('Asignaciones de permisos','/profile_permissions/'); ?> &gt; Asignar permisos a un perfil</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_add_profile">Asignar permiso/s a un perfil</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/profile_permissions/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de asignaciones'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('ProfilePermission');
			echo '<table>
				<tr>
					<td>';
			
			echo $this->form->input('System.system_id', array('label' => 'Sistema', 'id' => 'SelectSistema', 'type' =>'select', 'onChange' => "Javascript:getProfilesAndPermisos();", 'options' => array('' => '', $sistemas), 'div' => 'null'));
			
			echo '</td>
					<td><div id="segundoSelect" ></div></td>
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>