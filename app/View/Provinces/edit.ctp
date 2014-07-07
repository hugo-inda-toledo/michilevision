<h3><?php echo $this->html->link('Provincias','/provinces/'); ?> &gt; <?php echo $this->html->link('Editar Provinciar','/provinces/'); ?> &gt; Editando Provincia</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_edit">Editando Provincia</h2>
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
			
			echo $this->form->input('region_id', array('label' => 'Región Asociada', 'type' =>'select', 'options' => $regions, 'empty' => 'Seleccione una región', 'default' => $this->request->data['Province']['region_id']));
			echo '</td>
				<td>';
			echo $this->form->input('province_name', array('label' => 'Nombre de la región', 'type' =>'text')).'</td>';
			
			echo '<br /><br />
				'.$this->form->end('Editar').'
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>