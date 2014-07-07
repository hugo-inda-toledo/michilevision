
	<h3><?php echo $this->html->link('Fondos por rendir','/render_funds/'); ?> &gt; Reenvio de solicitud</h3>

	<?php
	$descriptionInput =  $this->form->input('RenderFundRequest.description', array('name' => 'data[RenderFundRequest][description][]', 'label' => 'Descripci&oacute;n', 'type' => 'text', 'maxlength' => 45, 'class' => 'f_descripcion'));
	$priceInput = $this->form->input('RenderFundRequest.price', array('name' => 'data[RenderFundRequest][price][]', 'label' => 'Precio', 'type' => 'text',  'maxlength' => 45, 'class' => 'f_precio'));
	$deleteRowBtn = $this->html->link('Eliminar fila','javascript:deleteRow(\'[ID_ITEM]\');',array('class' => 'tip_tip_default app_buttons delete_item','title' => 'Eliminar este ítem'));
	?>

	<script type="text/javascript">

	function addRow()
	{
		var numRows = ($('#detallesFondo tr').length - 1);
		var idRow = 'tr_' + numRows + 1 + '_' + Math.floor(Math.random() * (99999-999+1)) + 999;
		var btnEliminar = '<?php echo $deleteRowBtn;?>';
		btnEliminar = btnEliminar.replace('[ID_ITEM]',idRow);
		
		var nuevaFila = '<tr id="' + idRow + '">';
		nuevaFila += '<td><?php echo $descriptionInput;?></td>';
		nuevaFila += '<td><?php echo $priceInput;?></td>';
		nuevaFila += '<td>' + btnEliminar + '</td>';
		nuevaFila += '</tr>';

		$('#detallesFondo').append(nuevaFila);
		inicializarTipTip('default');
	}

	function deleteRow(idRow){
	$('#detallesFondo tr#' + idRow).remove();
	}

	</script>

	<h2 class="link_admin link_resend">Reingresar <?php echo $data['RenderFund']['render_fund_title']?></h2>

	<?php echo $this->Session->flash(); ?>

	<?php echo $this->form->create('RenderFund', array('class' => 'form_usr'));?>

	<table>
		<tr>
			<td>
			<?php
			echo $this->form->input('render_fund_title', array('label' => 'Titulo breve para el fondo a rendir', 'type' => 'text', 'default' => $data['RenderFund']['render_fund_title']));
			echo $this->form->input('user_name', array('label' => 'Usuario solicitante', 'title' => 'Solicitante', 'class' => 'link_admin link_user',  'disabled' => 'disabled', 'type' => 'text', 'default' => $data['User']['name'].' '.$data['User']['first_lastname']));
			echo $this->form->input('user_id', array('type' => 'hidden', 'default' => $data['RenderFund']['user_id']));
			echo $this->form->input('dni_user', array('label' => 'Rut solicitante',  'title' => 'Rut solicitante', 'class' => 'link_admin link_dni', 'disabled' => 'disabled', 'type' => 'text', 'default' => $data['User']['dni']));
			echo $this->form->input('used_by_name', array('label' => 'Utilizado por', 'type' => 'text', 'default' => $data['User']['name'].' '.$data['User']['first_lastname']));
			echo $this->form->input('used_by_dni', array('label' => 'Rut utilizante', 'type' => 'text', 'default' => $data['User']['dni']));
			?></td>
			<td class="left_separator">
			<?php 
			echo $this->form->input('management_name', array('label' => '&Aacute;rea',  'title' => 'Gerencia', 'class' => 'link_admin link_management', 'disabled' => 'disabled', 'type' => 'text', 'default' => $data['Management']['management_name'], 'size' => 30));
			echo $this->form->input('management_id', array('type' => 'hidden', 'default' => $data['RenderFund']['management_id']));
			echo $this->form->input('authorization_id', array('type' => 'hidden', 'default' => $data['Management']['authorization_id']));
			echo $this->form->input('badge_name', array('label' => 'Tipo de moneda', 'type'=>'select', 'options' => $badges, '' => $data['RenderFund']['badge_id'],'div'=>array('class'=>'select')));
			echo $this->form->input('badge_id', array('type' => 'hidden', 'default' => $data['RenderFund']['badge_id']));
			echo $this->form->input('cost_center_id', array('label' => 'Centro de costo', 'type'=>'select', 'options' => $cost_centers, 'default' => $data['RenderFund']['cost_center_id'], 'div'=>array('class'=>'select')));
			echo $this->form->input('reason', array('label' =>'Motivo del gasto', 'type' => 'textarea', 'default' => $data['RenderFund']['reason'], array('rows' => '2', 'cols' => '10')));
			?></td>
			<td>
				<span class="current_date">Fecha solicitud :<br /><strong><?php echo date('d-m-Y');?></strong></span>
			</td>		
		</tr>
		
		<tr>
			<td colspan="3">
				<table id='detallesFondo' cellspacing="0">
					<tr>
						<th width="680">Descripci&oacute;n</th>
						<th width="160">Precio ($)</th>
						<th>&nbsp;</th>
					</tr>
						<?php
							$x=0;
							foreach($data['RenderFundRequest'] as $value)
							{
								$id_row = 'tr_'.$x.'_'.rand(0,9999);
								echo "<tr id='".$id_row."'>";
								echo '<td>'.$this->form->input('RenderFundRequest.description', array('name' => 'data[RenderFundRequest][description][]', 'label' => 'Descripci&oacute;n', 'type' => 'text', 'maxlength' => 45, 'class' => 'f_descripcion', 'default' => $value['description'])).'</td>';
								echo '<td>'.$this->form->input('RenderFundRequest.price', array('name' => 'data[RenderFundRequest][price][]', 'label' => 'Precio', 'type' => 'text',  'maxlength' => 45, 'class' => 'f_precio', 'default' => $value['price'])).'</td>';
								if($x != 0)
									echo '<td>'.$this->html->link('Eliminar fila','javascript:deleteRow(\''.$id_row.'\');',array('class' => 'tip_tip_default app_buttons delete_item','title' => 'Eliminar este ítem')).'</td>';
								echo "</tr>";
								
								$x++;
							}
						?>
				</table>
			</td>	
		</tr>
		
		<tr>
			<td colspan="3">
			<?php echo $this->html->link('Agregar fila','javascript:addRow();',array('class' => 'link_admin link_add')); ?>
			</td>
		</tr>
	</table>

	<?php echo $this->form->end('Reenviar Solicitud');?>

	<div class="ViewTitleOptions">
		<br /><br />
		<?php echo $this->html->link('Volver al listado','/render_funds/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de solicitudes'));?>
	</div>