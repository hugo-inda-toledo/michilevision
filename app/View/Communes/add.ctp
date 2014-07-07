<h3><?php echo $this->html->link('Comunas','/communes/'); ?> &gt; Agregar Comuna</h3>
<?php echo $this->Session->flash();  ?>
<?php echo $this->Html->script('functions_admin');  ?>


<div id="TitleView">
	<h2 class="link_admin link_add">Agregar Comuna</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/communes/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de comunas'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('Commune');
			echo '<table>
					<tr>
						<td>';
			
			echo $this->form->input('Commune.region_id', array('label' => 'Región Asociada', 'id' => 'region', 'type' =>'select', 'options' => $regions, 'empty' => 'Seleccione una región', 'onChange' => 'Javascript:getProvinces();'));
			echo '</td>
				<td><div id="provinces"></div></td>';

				echo  '</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>