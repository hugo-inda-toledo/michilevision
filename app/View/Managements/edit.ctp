<h3><?php echo $this->html->link('Gerencias','/managements/'); ?> &gt; Editar Gerencia Existente</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_edit">Editar <?php echo $this->data['Management']['management_name'];?></h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/managements/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de gerencias'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('Management');
			echo '<table>
				<tr>
					<td>';
			
			echo $this->form->input('management_name', array('label' => 'Nombre Gerencia'));
			echo $this->form->input('user_id', array('label' => 'Gerente', 'type'=>'select', 'options'=>$selectUsuariosGerente, 'div' => array('class'=>'select'), 'empty' => ''));
			echo '</td>
				<td>';
			
			echo $this->form->input('authorization_id', array('label' => 'Tipo de autorización', 'type'=>'select', 'options'=>$selectAutorizacion, 'div'=>array('class'=>'select')));
			echo $this->form->input('cost_center_father_code', array('label' => 'Centro de costo padre'));
			echo '</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" value="Guardar Gerencia"></input>
						</td>
					</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>