<h3><?php echo $this->html->link('Autorizaciones','/authorizations/'); ?> &gt; Crear Nueva Autorización<h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_add">Crear Nueva Autorización</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/authorizations/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de autorizaciones', 'style' => 'font-weight:normal;'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('Authorization', array('class' => 'form'));
			echo '<table>
				<tr>
					<td>';
						echo $this->form->input('name', array('label' => 'Nombre de la Autorización', 'type' =>'text'));
						echo $this->form->input('active', array('label' => 'Activo', 'type' =>'select', 'options' => array(1 => 'Si', 2 => 'No'), 'default' => 1));
			echo '</td>
					<td>';
					echo $this->form->end('Crear Autorización').'</td>
				</tr>
				</table>';
			?>
		</td>	
	</tr>
</table>