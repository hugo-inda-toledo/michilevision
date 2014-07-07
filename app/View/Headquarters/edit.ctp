<h3><?php echo $this->html->link('Gerencias','/managements/'); ?> &gt; <?php echo $this->html->link('Jefaturas','/headquarters/'); ?> &gt; Editar jefatura</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_edit">Editar Jefatura Existente</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/headquarters/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de jefaturas'));?></li>
	</ul>
</div>



<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('Headquarter');
			echo '<table>
				<tr>
					<td>';
			
			echo $this->form->input('headquarter_name', array('label' => 'Nombre de la jefatura'));
			echo $this->form->input('cost_center_code', array('label' => 'Centro de costo'));
			echo $this->form->input('user_id', array('label' => 'Usuario','type' => 'select','options' => $selectUsuarios, 'div' => array('class'=>'select'), 'empty' => ''));
			echo '</td>
				<td>';
					
			echo $this->form->input('management_id', array('label' => 'Gerencia','type' => 'select', 'options' => $selectGerencias, 'div' => array('class'=>'select'), 'empty' => ''));
			echo $this->form->input('active', array('label' => 'Activo', 'type' => 'select', 'options' => array(1 => 'Si', 0 => 'No')));
			echo '<br /><br />
				<input type="submit" value="Editar Jefatura"></input></td>
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>