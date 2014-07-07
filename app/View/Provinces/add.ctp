<h3><?php echo $this->html->link('Provincias','/provinces/'); ?> &gt; <?php echo $this->html->link('Agregar Provincia','/provinces/'); ?> &gt; Agregar Provincia</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_add">Agregar Provincia</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/provinces/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de provincias'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('Province');
			echo '<table>
					<tr>
						<td>';
			
			echo $this->form->input('region_id', array('label' => 'Región Asociada', 'type' =>'select', 'options' => $regions, 'empty' => 'Seleccione una región'));
			echo '</td>
				<td>';
			echo $this->form->input('province_name', array('label' => 'Nombre de la región', 'type' =>'text')).'</td>';
			
			echo '<br /><br />
				'.$this->form->end('Agregar').'
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>