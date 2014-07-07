<h3><?php echo $this->html->link('Regiones','/regions/'); ?> &gt; <?php echo $this->html->link('Editar Región','/regions/'); ?> &gt; Editar región</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_add">Editar Región</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/regions/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de regiones'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('Region');
			echo '<table>
				<tr>
					<td>';
			
			echo $this->form->input('region_name', array('label' => 'Nombre de la región', 'type' =>'text'));
			echo '</td>
				<td>';
			
			echo '<br /><br />
				'.$this->form->end('Editar').'
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>