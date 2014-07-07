<h3><?php echo $this->html->link('Circuitos','/circuits/'); ?> &gt; Editar circuito existente<h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_add">Editar Circuito de Autorización</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/circuits/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de circuitos', 'style' => 'font-weight:normal;'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('Circuit');
			echo '<table>
				<tr>
					<td>';
			
			echo $this->form->input('user_id', array('label' => 'Usuario a firmar', 'type' =>'select', 'options' => $users, 'empty' => ''));
			echo $this->form->input('system_id', array('label' => 'Sistema a firmar', 'type' =>'select', 'options' => $systems, 'empty' => ''));
			
			echo '</td>
				<td>';
					
			echo $this->form->input('authorization_id', array('label' =>'Tipo de autorización', 'type' =>'select', 'options' => $authorizations, ));
			echo $this->form->input('level', array('label' =>'Nivel', 'type' =>'select', 'options' => array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5','6' => '6','7' => '7', '8' => '8', '9' => '9', '10' => '10')));
			
			echo '<input type="submit" value="Guardar Circuito"></input></td>
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>