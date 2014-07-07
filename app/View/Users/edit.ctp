<h3><?php echo $this->html->link('Usuarios','/users/'); ?> &gt; Editar Usuario Existente</h3>
<?php echo $this->Session->flash(); ?>



<div id="TitleView">
	<h2 class="link_admin link_edit">Editar datos del usuario <?php echo $this->request->data['User']['name'].' '.$this->request->data['User']['first_lastname'];?></h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/users/',array('title' => 'Volver al listado de usuarios','class' => 'tip_tip_default link_admin link_back'));?></li>
	</ul>
</div>	



<table>
	<tr>
		<td>
		<?php
		echo $this->form->create('User', array('enctype' => 'multipart/form-data'));
		echo '<table>
				<tr>
					<td>';
			
		//Se crean los inputs dinamicos de acuerdo al tipo de columna de la tabla
		echo $this->form->input('name', array('label' => 'Nombre'));
		echo $this->form->input('first_lastname', array('label' => 'Apellido Paterno'));
		echo $this->form->input('second_lastname', array('label' => 'Apellido Materno'));
		echo $this->form->input('dni', array('label' => 'Rut'));
		echo $this->form->input('token', array('label' => 'Ficha'));
		echo $this->form->input('birthday', array('label' => 'Fecha de nacimiento', 'type'=>'text', 'id'=>'datepickerUsuario', 'readonly' => 'readonly'));
			
		echo '</td>
				<td>';	
			
		echo $this->form->input('management_id', array('label' => 'Gerencia', 'type'=>'select', 'options'=>$selectManagements, 'div'=>array('class'=>'select')));
		echo $this->form->input('headquarter_id', array('label' => 'Jefatura (Si aplica)', 'type'=>'select', 'options' => array(" " => " ", $selectHeadquarters)));
		echo $this->form->input('position_id', array('label' => 'Cargo', 'type'=>'select', 'options'=>$selectPositions, 'div'=>array('class'=>'select')));
		echo $this->form->input('email');
		echo $this->form->input('plant', array('label' => 'Usuario de planta', 'type' => 'select', 'options' => array(1 => 'Si', 0 => 'No')));
		echo $this->form->input('active', array('label' => 'Usuario en actividad', 'type' => 'checkbox'));
		echo $this->form->file('file', array('between'=>'<br />', 'label' => 'Foto de perfil'));
		echo $this->form->input('admin', array('label' => 'Super administrador', 'type' => 'select', 'options' => array('1' => 'Si', '0' => 'No'), 'default' => $this->request->data['User']['admin']));
			
		echo '</td>
					</tr>
					<tr>
						<td colspan="2">'.
							$this->form->end('Guardar')
						.'</td>
					</tr>
				</table>
			</form>';	
		?>
		</td>	
	</tr>
</table>