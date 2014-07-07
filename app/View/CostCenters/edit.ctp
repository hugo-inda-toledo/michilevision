<h3><?php echo $this->html->link('Centro de costos','/cost_centers/'); ?> &gt; Editar centro de costos</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_edit">Editar Centro de Costos</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/cost_centers/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de centros de costos'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('CostCenter');
			echo '<table>
				<tr>
					<td>';
			
			echo $this->form->input('cost_center_name', array('label' => 'Nombre del centro de costo'));
			echo $this->form->input('cost_center_code', array('label' => 'Código del centro de costo'));
			echo $this->form->input('management_id', array('label' => 'Gerencia Asociada', 'type' =>'select', 'options' => array($selectManagements)));
			echo '</td>
				<td>';
					
			echo $this->form->input('level', array('label' => 'Nivel', 'type'=>'select', 'options'=> array('1' => '1', '2' => '2', '3' => '3'), 'div'=>array('class'=>'select')));
			echo $this->form->input('valid', array('label' => 'Vigente', 'type'=>'select', 'options'=>array('1' => 'Si', '0' => 'No'), 'div'=>array('class'=>'select')));
			
			echo '<br /><br />
				<input type="submit" value="Guardar Centro de Costo"></input></td>
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>