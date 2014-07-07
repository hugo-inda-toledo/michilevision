<h3><?php echo $this->html->link('Reemplazos','/replacements/'); ?> &gt; Agregar reemplazo</h3>
<?php echo $this->Session->flash();  ?>


<div id="TitleView">
	<h2 class="link_admin link_add">Agregar reemplazo</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/replacements/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de asignaciones'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('Replacement');
			echo '<table>
				<tr>
					<td>';
			
			echo $this->form->input('replaced_user_id', array('label' => 'Usuario a reemplazar', 'id' => 'SelectUsuarioReemplazado','type' =>'select', 'options' => $usuarios, 'empty' => '', 'onChange' => 'Javascript:getOtherUsuarios();'));
			
			echo '</td>
					<td><div id="segundoSelect"></div></td>
				</tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>