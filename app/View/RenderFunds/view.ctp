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


//echo $this->Html->tag('h3',$this->html->link('Fondos por rendir','/render_funds/mainMenu').' &gt; Ver detalle fondo');
echo $this->Html->tag('h2','Detalle fondo por rendir : '.$data['RenderFund']['render_fund_title'], array('class' => 'link_admin link_render_fund', 'style' => 'color:#CCCCCC;'));
echo $this->Session->flash();
echo $this->form->create('RenderFund', array('class' => 'form-request'));
?>


	<table>
		<tr>
			<td>
				<table>
					<tr>
						<td><?php
						echo $this->form->input('title', array('label' => 'Titulo breve para el fondo a rendir', 'type' => 'text', 'class' => 'readonly', 'readonly' => 'readonly', 'default' => $data['RenderFund']['render_fund_title']));
						echo $this->form->input('user', array('label' => 'Solicitante', 'type' => 'text', 'class' => 'readonly', 'readonly' => 'readonly', 'default' => $data['User']['name'].' '.$data['User']['first_lastname']));
						echo $this->form->input('dni_user', array('label' => 'Rut solicitante', 'type' => 'text', 'class' => 'readonly', 'readonly' => 'readonly', 'default' => $data['User']['dni']));
						echo $this->form->input('used_by_name', array('label' => 'Utilizado por', 'type' => 'text', 'class' => 'readonly', 'readonly' => 'readonly', 'default' => $data['User']['name'].' '.$data['User']['first_lastname']));
						echo $this->form->input('used_by_dni', array('label' => 'Rut utilizante', 'type' => 'text', 'class' => 'readonly', 'readonly' => 'readonly', 'default' => $data['User']['dni']));
						?></td>
						<td class="left_separator">
						<?php
						echo $this->form->input('management', array('label' => 'Área', 'type' => 'text', 'class' => 'readonly', 'readonly' => 'readonly', 'default' => $data['Management']['management_name']));
						echo $this->form->input('authorization', array('label' => 'Autorización', 'type' => 'text', 'class' => 'readonly', 'readonly' => 'readonly', 'default' => $data['Authorization']['name']));
						echo $this->form->input('badge', array('label' => 'Tipo de moneda', 'type' => 'text', 'class' => 'readonly', 'readonly' => 'readonly', 'default' => $data['Badge']['badge']));
						echo $this->form->input('cost_center', array('label' => 'Centro de costo', 'type' => 'text', 'class' => 'readonly', 'readonly' => 'readonly', 'default' => $data['CostCenter']['cost_center_name'].' ('.$data['CostCenter']['cost_center_code'].')'));
						echo $this->form->input('reason', array('label' => 'Motivo del gasto', 'type' => 'textarea', 'class' => 'readonly', 'readonly' => 'readonly', 'default' => '...'));
						?></td>
						<td valign="top">
						<?php
						if(trim($data['RenderFund']['fund_number']) != '')
						{
							echo $this->Html->para('link_admin link_ticket', 'N° de fondo: '.$data['RenderFund']['fund_number']);
						}
										
						echo $this->Html->para('req-date', 'Fecha solicitud : <strong>'.substr($data['RenderFund']['created'], 8, 2).'/'.substr($data['RenderFund']['created'], 5, 2) .'/'.substr($data['RenderFund']['created'], 0, 4).'</strong>');
						
						if($data['State']['id'] == 1)
							$status_class = 'requestStatusPending';
						if($data['State']['id'] == 2)
							$status_class = 'requestStatusApproved';	
						if($data['State']['id'] == 3)
							$status_class = 'requestStatusRejected';
							
						echo $this->Html->para('req-status '.$status_class,'Estado : <strong>'.$data['State']['state'].'</strong>');	
							
						
						if($this->RequestAction('/external_functions/getIdDataSession') == $this->RequestAction('/attribute_tables/validateTreasurer') && $data['RenderFund']['state_id'] == 2 && $data['RenderFund']['comments_finance'] != '')
						{
							echo $this->Html->para('link_admin link_email', 'Mensaje de finanzas: '.$data['RenderFund']['comments_finance']);
						}
						
						if($this->RequestAction('/external_functions/getIdDataSession') == $data['RenderFund']['user_id'] && $data['RenderFund']['state_id'] == 2 && $data['RenderFund']['comments'] != '')
						{
							echo $this->Html->para('link_admin link_chat', 'Mensaje de tesoreria: '.$data['RenderFund']['comments']);
						}
						
						if($data['RenderFund']['state_id'] == 3 && $data['RenderFund']['user_id'] == $this->RequestAction('/external_functions/getIdDataSession'))
						{
							echo $this->Html->para(null, $this->html->link('Reenviar fondo','/render_funds/resendFund/'.$data['RenderFund']['id'],array('class'=>'a_button')));
						}
						
						if($data['RenderFund']['state_id'] == 2 && $this->RequestAction('/external_functions/getFinanceManagementId') == $this->RequestAction('/external_functions/getIdDataSession') && $data['RenderFund']['comments_finance'] == '')
						{
							echo $this->Html->para(null, $this->html->link('Obs. Tesoreria','#',array('class'=>'link_admin a_button tip_tip_click','title' => $this->RequestAction('/render_funds/financeCommentForm/'.$data['RenderFund']['id']))));
						}
			
						if($data['RenderFund']['state_id'] == 2 && $this->RequestAction('/attribute_tables/validateTreasurer') == $this->RequestAction('/external_functions/getIdDataSession') && $data['RenderFund']['comments'] == '' && $data['RenderFund']['comments_finance'] != '')
						{
							echo $this->Html->para(null, $this->html->link('Obs. Solicitante','#',array('class'=>'link_admin a_button tip_tip_click','title' => $this->RequestAction('/render_funds/CommentForm/'.$data['RenderFund']['id']))));
						}
							
						if($data['RenderFund']['render_date_start'] == NULL && $data['RenderFund']['render_date_end'] == NULL && $this->RequestAction('/external_functions/getIdDataSession') == $this->RequestAction('/attribute_tables/validateTreasurer') && $data['RenderFund']['state_id'] == 2 && $data['RenderFund']['comments_finance'] != '' && $data['RenderFund']['comments'] != '')
						{
							echo $this->Html->script('functions_admin');
							echo $this->Html->para(null, $this->form->input('render_date_end', array('label' => 'Selecciona fecha tope para rendir', 'id' => 'datepickerUsuario', 'readOnly' => 'readOnly', 'type' => 'text')));
							echo $this->Html->para(null, $this->form->button('Guardar Hora', array('type' => 'button','class'=>'link_admin a_button', 'onclick' => 'javascript:validateEndDate('.$data['RenderFund']['id'].');')));
						}
			
						else
						{
							if($data['RenderFund']['render_date_start'] != NULL && $data['RenderFund']['render_date_end'] != NULL && $data['RenderFund']['state_id'] == 2 &&  $this->RequestAction('/external_functions/getIdDataSession') == $data['RenderFund']['user_id'] && $data['RenderFund']['render'] == 1)
							{
								echo $this->Html->para('link_admin link_ok', 'Fecha de tope para rendir: '.$this->RequestAction('/external_functions/setDate/'.$data['RenderFund']['render_date_end']), array('class'  => 'link_admin link_ok'));
							}
							else
							{
								if($data['RenderFund']['render_date_start'] != NULL && $data['RenderFund']['render_date_end'] != NULL && $data['RenderFund']['state_id'] == 2 &&  $this->RequestAction('/external_functions/getIdDataSession') == $data['RenderFund']['user_id'])
								{
									echo $this->Html->para('link_admin link_calendar', 'Fecha de tope para rendir: '.$this->RequestAction('/external_functions/setDate/'.$data['RenderFund']['render_date_end']));
								}
							}
						}
						
						
						if($data['RenderFund']['render_date_start'] != NULL && $data['RenderFund']['render_date_end'] != NULL && $this->RequestAction('/external_functions/getIdDataSession') == $this->RequestAction('/attribute_tables/validateTreasurer') && $data['RenderFund']['state_id'] == 2 && $data['RenderFund']['comments_finance'] != '' && $data['RenderFund']['comments'] != '' && $data['RenderFund']['deliver'] == 1)
						{
							echo $this->Html->para(null, $this->form->create('RenderFund', array('enctype' =>'multipart/form-data')).
							$this->form->file('render_fund_data', array ('label' => 'Selecciona las boletas a subir')).
							$this->form->input('id', array ('type' => 'hidden', 'default' => $data['RenderFund']['id'])).
							$this->form->end('Subir archivos', array('class' => 'a_button')));
						}
						?></td>		
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
							<th width="180">Precio unitario</th>
						</tr>
						
						<?php
						foreach($data['RenderFundRequest'] as $value)
						{
							echo '<tr>
									<td>'.$value['description'].'</td>
									<td>'.$data['Badge']['symbol'].number_format($value['price'], 0, null, '.').'</td>
								</tr>';
						}
						?>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td><strong>Precio Total :</strong></td>
							<td><strong class="redText"><?php echo $data['Badge']['symbol'].number_format($data['RenderFund']['total_price'], 0, null, '.'); ?></strong></td>
						</tr>
					</table>
				</div>
				
			</td>
		</tr>
	</table>
</form>	

<br /><br />
		
		
		
<div class="firmas">

<?php
echo '<div class="sign">
		<div class="approval_link">
			<span class="link_admin link_circuit_approved st_approved">Solicitante</span>
		</div>
							
		<strong>Solicitante</strong><br />
		'.$this->RequestAction('/external_functions/formatNames/'.$data['User']['name'].' '.$data['User']['first_lastname']).'
		<p class="fr_date">'.
		substr($data['RenderFund']['created'], 8, 2).'-'.
		substr($data['RenderFund']['created'], 5, 2).'-'.
		substr($data['RenderFund']['created'], 0, 4).' '.
		substr($data['RenderFund']['created'], 11, 8).'</p>
	</div>';
			
	
	$cont = 0;
	$stopSignCircuit = 0;
	$z = 0;
			
	for($x=0; $x < count($data['Sign']); $x++)
	{
			
		if($data['Sign'][$x]['state_id'] == 1) // Esperando firmas...
		{
			$cont++;
			
			if($this->RequestAction('/external_functions/getIdDataSession') == $data['Sign'][$x]['user_id'] && $cont == 1)
			{
				if($stopSignCircuit == 0)
				{
					
					if($data['Sign'][$x]['replacement_sign'] == 1) //Firma de reemplazante
					{
						echo '<div class="sign">
								<div class="approval_link">
									'.$this->html->link('Acciones','#',array('class'=>'link_admin link_actions tip_tip_click','title' => $this->RequestAction('/signs/formSign/'.$data['Sign'][$x]['id']))).'
								</div>	
								<strong class="link_admin link_alert">Debes firmar!</strong><br />'.
								$data['Sign'][$x]['signer_name']
								.'<p>Reemplazante</p>
							</div>';
					}
					
					else{
						echo '<div class="sign">
								<div class="approval_link">
									'.$this->html->link('Acciones','#',array('class'=>'link_admin link_actions tip_tip_click','title' => $this->RequestAction('/signs/formSign/'.$data['Sign'][$x]['id']))).'
								</div>	
								<strong class="link_admin link_alert">Debes firmar!</strong><br />'.
								$data['Sign'][$x]['signer_name']
								.'<p class="fr_date">&nbsp;</p>
							</div>';
					}
					
				}
				
				else
				{
					echo '<div class="sign">
							<div class="approval_link">
								'.$this->html->link('Sin acciones','#',array('class'=>'link_admin link_actions tip_tip_default','title' => 'Sin acciones')).'
							</div>	
							<strong>Sin Acciones</strong><br />'.
							$data['Sign'][$x]['signer_name']
							.'<p class="fr_date">&nbsp;</p>
						</div>';
				}
			}
			
			
			else
			{
				if($data['Sign'][$x]['replacement_sign'] == 1) //Firma de reemplazante
				{
					echo '<div class="sign">
							<div class="approval_link">
								'.$this->html->link('En espera','#',array('class'=>'link_admin link_actions tip_tip_default','title' => 'En espera')).'
							</div>	
							<strong>En espera</strong><br />'.
							$data['Sign'][$x]['signer_name']
							.'<p class="fr_date">Reemplazante</p>
						</div>';
				}
				
				else
				{
					echo '<div class="sign">
							<div class="approval_link">
								'.$this->html->link('En espera','#',array('class'=>'link_admin link_actions tip_tip_default','title' => 'En espera')).'
							</div>	
							<strong>En espera</strong><br />'.
							$data['Sign'][$x]['signer_name']
							.'<p class="fr_date"></p>
						</div>';
				}
			
			}
			
			
			
			
		}
		
		
		

		if($data['Sign'][$x]['state_id'] == 2) // Aprobado...
		{
			if($data['Sign'][$x]['replacement_sign'] == 1) //Firma de reemplazante
			{
				echo '<div class="sign">
						<div class="approval_link">
							<span class="link_admin link_circuit_approved st_approved" title="'.$data['Sign'][$x]['comments'].'">Aprobado</span>
						</div>	
						<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$data['Sign'][$x]['position']).'</strong><br />'.
						@$this->RequestAction('/external_functions/formatNames/'.$data['Sign'][$x]['signer_name']).'<br />(Reemplazo)
						<p class="fr_date">'.
						substr($data['Sign'][$x]['modified'], 8, 2).'-'.
						substr($data['Sign'][$x]['modified'], 5, 2).'-'.
						substr($data['Sign'][$x]['modified'], 0, 4).' '.
						substr($data['Sign'][$x]['modified'], 11, 8).'</p>
						<span class="tip_tip_default link_admin link_comments" title="'.$data['Sign'][$x]['comments'].'">Comentarios</span>
					</div>';
			}
			
			else
			{
				echo '<div class="sign">
						<div class="approval_link">
							<span class="link_admin link_circuit_approved st_approved" title="'.$data['Sign'][$x]['comments'].'">Aprobado</span>
						</div>	
						<strong>'.@$this->RequestAction('/external_functions/formatNames/'.$data['Sign'][$x]['position']).'</strong><br />'.
						@$this->RequestAction('/external_functions/formatNames/'.$data['Sign'][$x]['signer_name'])
						.'<p class="fr_date">'.
						substr($data['Sign'][$x]['modified'], 8, 2).'-'.
						substr($data['Sign'][$x]['modified'], 5, 2).'-'.
						substr($data['Sign'][$x]['modified'], 0, 4).' '.
						substr($data['Sign'][$x]['modified'], 11, 8).'</p>
						<span class="tip_tip_default link_admin link_comments" title="'.$data['Sign'][$x]['comments'].'">Comentarios</span>
					</div>';
			}
		}


		
		
		
		if($data['Sign'][$x]['state_id'] == 3) // Rechazado...
		{
			echo '<div class="sign">
				<div class="approval_link">
					<span class="link_admin link_circuit_rejected st_rejected" title="'.$data['Sign'][$x]['comments'].'">Rechazado</span>
				</div>	
				<strong>Solicitante</strong><br />'.
			 	$data['Sign'][$x]['signer_name']
				.'<p class="fr_date">'.$data['RenderFund']['created'].'<br /><strong>'.$data['Sign'][$x]['comments'].'</strong></p>
				</div>';
			
			$stopSignCircuit = 1;
		}
		
		
		
	$z++;
	}
			
			
?>

</div>