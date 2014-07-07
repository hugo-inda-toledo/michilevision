<?php echo $this->Html->css('reset');?>
<?php echo $this->Html->css('cake.generic');?>
<?php echo $this->Html->css('cake.systems');?>
<?php echo $this->Html->css('jquery-ui-1.10.0.custom');?>
<?php echo $this->Html->css('jquery-menus');?>
<?php echo $this->Html->css('jquery.tipTip');?>
<?php echo $this->Html->css('shadowbox');?>
<?php echo $this->Html->script('shadowbox-3.0.3/shadowbox');?>
<?php echo $this->Html->script('jquery-1.9.0.js');?>
<?php echo $this->Html->script('jquery-ui-1.10.0.custom.js');?>
<?php echo $this->Html->script('jquery.tipTip');?>
<?php echo $this->Html->script('functions_system');?>
<?php echo $this->Html->script('init');

$this->layout = null;

if(count($costCenters) != null)
{

$descriptionInput =  $this->form->input('RenderFundRequest.description', array('name' => 'data[RenderFundRequest][description][]', 'label' => 'Descripción', 'type' => 'text', 'maxlength' => 45));
$priceInput = $this->form->input('RenderFundRequest.price', array('name' => 'data[RenderFundRequest][price][]', 'label' => 'Precio', 'type' => 'text',  'maxlength' => 45, 'onkeypress' => 'return onlyNumbers(event)'));
$deleteRowBtn = $this->html->link('Eliminar fila','javascript:deleteRow(\'[ID_ITEM]\');',array('class' => 'tip_tip_default app_buttons delete_item','title' => 'Eliminar este ítem'));
?>

<script type="text/javascript">

	function addRow()
	{
		var numRows = ($('#detail-request tr').length - 1);
		var idRow = 'tr_' + numRows + 1 + '_' + Math.floor(Math.random() * (99999-999+1)) + 999;
		var btnEliminar = '<?php echo $deleteRowBtn;?>';
		btnEliminar = btnEliminar.replace('[ID_ITEM]',idRow);
		
		var nuevaFila = '<tr id="' + idRow + '">';
		nuevaFila += '<td><?php echo $descriptionInput;?></td>';
		nuevaFila += '<td><?php echo $priceInput;?></td>';
		nuevaFila += '<td>' + btnEliminar + '</td>';
		nuevaFila += '</tr>';

		$('#detail-request').append(nuevaFila);
		inicializarTipTip('default');
	}




	function deleteRow(idRow){
	$('#detail-request tr#' + idRow).remove();
	}

</script>

<?php
echo $this->Html->tag('h2','Ingresar nueva solicitud', array('class' => 'link_admin link_add', 'style' => 'color:#CCCCCC;'));
echo $this->Session->flash(); 
echo $this->form->create('RenderFund', array('class' => 'form-request'));
?>

<table>
	<tr>
		<td>
			<table>
				<tr>
					<td>
					<?php
					echo $this->form->input('render_fund_title', array('label' => 'Titulo breve para el fondo a rendir', 'type' => 'text'));
					echo $this->form->input('user_id', array('label' => 'Usuario solicitante', 'title' => 'Solicitante', 'class' => 'readonly','readonly' => 'readonly', 'type' => 'text', 'default' => $data['User']['name'].' '.$data['User']['first_lastname']));
					echo $this->form->input('dni_user', array('label' => 'Rut solicitante',  'title' => 'Rut solicitante', 'class' => 'readonly', 'readonly' => 'readonly', 'type' => 'text', 'default' => $data['User']['dni']));
					echo $this->form->input('used_by_name', array('label' => 'Utilizado por', 'type' => 'text', 'default' => $data['User']['name'].' '.$data['User']['first_lastname']));
					echo $this->form->input('used_by_dni', array('label' => 'Rut utilizante', 'type' => 'text', 'default' => $data['User']['dni']));
					?></td>
					<td class="left_separator">
					<?php
					echo $this->form->input('management_id', array('label' => 'Area',  'title' => 'Gerencia', 'class' => 'readonly', 'readonly' => 'readonly', 'type' => 'text', 'default' => $data['Management']['management_name'], 'size' => 30));
					echo $this->form->input('authorization_id', array('type' => 'hidden', 'default' => $data['Management']['authorization_id']));
					echo $this->form->input('badge_id', array('label' => 'Tipo de moneda', 'type'=>'select', 'options' => $badges, 'div'=>array('class'=>'select')));
					echo $this->form->input('cost_center_id', array('label' => 'Centro de costo', 'type'=>'select', 'options' => $costCenters, 'div'=>array('class'=>'select')));
					echo $this->form->input('reason', array('label' =>'Motivo del gasto', 'type' => 'textarea'));
					?></td>
					<td valign="top">
						<p class="req-date">Fecha solicitud : <strong><?php echo date('d-m-Y');?></p>
					</td>		
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<div class="items-request">
				<table id="detail-request" cellspacing="0">
					<tr>
						<th width="760">Descripción</th>
						<th width="160">Precio unitario</th>
						<th>&nbsp;</th>
					</tr>
					<tr>
						<td><?php echo $descriptionInput;?></td>
						<td><?php echo $priceInput;?></td>
						<td>&nbsp;</td>
					</tr>
				</table>
			</div>
			
			<?php echo $this->html->link('Agregar fila','javascript:addRow();',array('class' => 'link_admin link_add add-row')); ?>
		</td>
	</tr>
</table>

<?php
echo $this->form->end('Enviar Solicitud');

}

else
{
	echo "<p align='center'><font color='gray'><b><u>No tienes centros de costos asociados a este sistema para generar la solicitud.</b></u></font></p>";
	echo "<p align='center'><b>".$this->html->link('Volver al listado', 'javascript:history.back()')."</b></p>";
}
?>