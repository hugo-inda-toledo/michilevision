<h3><?php echo $html->link('Reemplazos','/replacements/'); ?> &gt; Editar reemplazo existente</h3>
<?php echo $this->Session->flash(); ?>

<div class="ViewTitleOptions">
	<h2 class="link_admin link_edit">Editar Reemplazo Existente</h2>
</div>

<div id="ViewTitleOptions">
<table>
	<tr>
		<td class="border_none">
		<?php
		echo $form->create('Replacement');
		echo $form->input('id', 				 array('type' => 'hidden') ); 
		echo $form->input('replaced_user_id',  	 array('label' => 'Usuario a reemplazar', 'id' => 'SelectUsuarioReemplazado', 'type' =>'select', 'options' => $usuarios, 'default' => $reemplazo['Replacement']['replaced_user_id']));
		echo $form->input('replacing_user_id',	 array('label' => 'Reemplazante', 'id' => 'SelectUsuarioReemplazante', 'type' =>'select', 'options' => $usuarios, 'default' => $reemplazo['Replacement']['replacing_user_id']));
		echo $form->input('type_replacement_id', array('label' => 'Tipo de reemplazo', 'id' => 'SelectTipoReemplazos', 'type' =>'select', 'options' => $selectTipoReemplazos, 'default' => $reemplazo['Replacement']['type_replacement_id']));
		echo $form->input('management_id', array('label' => 'Gerencia Asociada', 'id' => 'SelectGerencias', 'type' =>'select', 'options' => $gerencias, 'default' => $reemplazo['Replacement']['management_id']));
		echo $form->input('start_date', 		 array('label' => 'Fecha de inicio', 'id'=>'datepicker-desde', 'type'=>'text', 'readonly' => 'readonly'));
		echo $form->input('end_date', 			 array('label' => 'Fecha de termino', 'id'=>'datepicker-hasta', 'type'=>'text', 'readonly' => 'readonly'));
		echo $form->input('active', 			 array('label' => 'Reemplazo activo','id' => 'activo', 'type' => 'select', 'options' => array('1' => 'Si', '0' => 'No')));
		echo $form->end('Editar el reemplazo');
		?>
		</td>	
	</tr>
</table>
</div>

<div class="ViewTitleOptions">
	<br /><br />
	<?php
		echo $html->link('Volver al listado','/replacements/',array('class' => 'link_admin link_back','title' => 'Volver al listado de reemplazos'));
	?>
</div>