<h3><?php echo $this->html->link('Sistemas','/systems/'); ?> &gt; <?php echo $this->html->link('Asignaci�n de sistemas','/user_systems/'); ?> &gt; Agregar asignaci�n</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_add">Agregar Asignaci�n a Sistema</h2>
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
			
			echo $this->form->input('system_id', array('label' => 'Sistema para asignar', 'type' =>'select', 'options' => $sistemas, 'empty' => ''));
			echo '</td>
				<td>';
					
			echo $this->form->input('user_id', array('label' =>'Usuario asociado', 'type' =>'select', 'options' => $usuarios, 'empty' => ''));
			
			echo '<br /><br />
				<input type="submit" value="Guardar Asignaci�n"></input></td>
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>