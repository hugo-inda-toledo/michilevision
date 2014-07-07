<h3><?php echo $html->link('Centro de costos','/cost_centers/'); ?> &gt; <?php echo $html->link('Asignaciones a usuarios','/cost_center_users/'); ?> &gt; Editar asignación existente</h3>

<div class="ViewTitleOptions">
	<h2 class="link_admin link_add">Editar asignación centro de costo a usuario</h2>
</div>
<div class="ViewTitleOptions">
	<table>
		<tr>
			<td class="border_none">
			<?php
			/*
				//Se abre el formulario con helper $form
				echo $form->create('CentroCostoUsuario');
				
				//Se crean los inputs dinamicos de acuerdo al tipo de columna de la tabla
				echo $form->input('CentroCostoUsuario.usuario_id', array( 'type'=>'select', 'options'=> $resultUsuarios, 'default' => $dataCentroCostoUsuario['CentroCostoUsuario']['usuario_id'], 'div'=>array('class'=>'select')));
				echo $form->input('CentroCostoUsuario.centro_costo_id', array( 'type'=>'select', 'options'=>$resultCentroCostos, 'default' => $dataCentroCostoUsuario['CentroCostoUsuario']['centro_costo_id'], 'div'=>array('class'=>'select')));
			
				//Se cierra el formulario con helper $form
				echo $form->end('Modificar Asignacion');
			*/
			?>
			</td>	
		</tr>
	</table>
</div>

<div class="ViewTitleOptions">
	<br /><br />
	<?php
		echo $html->link('Volver al listado','/cost_center_users/',
			array('class' => 'tip_tip_default link_admin link_back',
			'title' => 'Volver al listado de asignaciones de centro de costos'
			)
		);
	?>
</div>