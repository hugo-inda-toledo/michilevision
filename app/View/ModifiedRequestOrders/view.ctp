<h3><?php echo $this->html->link('Solicitud de modificación de order de compra', '/modified_request_orders/'); ?> &gt; Ver Ficha Solicitud</h3>
<?php echo $this->Session->flash();  ?>

<div id="TitleView">
	<h2 class="link_admin link_invoice">Solicitud de modificación</h2>
	<ul>
			<?php 
				if($this->request->data['ModifiedRequestOrder']['can_modify'] == 0)
				{
					echo '<li>'.$this->html->link('Autorizar', '#',array('class' => 'tip_tip_click link_admin link_edit', 'title' => $this->RequestAction('/modified_request_orders/authorizeForm/'.$this->request->data['ModifiedRequestOrder']['id']))).'</li>';
				}
				else
				{
					echo '<li>'.$this->html->tag('span', 'Autorizado', array('class' => 'link_admin link_ok')).'</li>';
				}
			?>
		<li><?php echo $this->html->link('Volver al listado', '/modified_request_orders/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de solicitudes'));?></li>
	</ul>
</div>	

<div id="ManagementViewHolder">
	
	<div id="TableView">
		<table>
			<tr>
				<td>
					<table>
						<tr>
							<th>Id :</th>
							<td><?php echo $this->request->data['ModifiedRequestOrder']['id']; ?></td>
						</tr>
						<tr>
							<th>Numero de la orden de compra :</th>
							<td><?php echo $this->request->data['PurchaseOrder']['order_number']; ?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<th>Solicitante :</th>
							<td><?php echo $this->Html->tag('span', $this->request->data['User']['name'].' '.$this->request->data['User']['first_lastname'], array('class' => 'link_admin tip_tip link_user pointer', 'title' => $this->RequestAction('/external_functions/showUsuarioTable/'.$this->request->data['User']['id'])));?></td>
						</tr>
						<tr>
							<th>Solicitud Habilitada?</th>
							<td>
								<?php
									$response = '';
						
									if($this->request->data['ModifiedRequestOrder']['can_modify'] == 0)
									{
										echo 'No';
									}
									else
									{
										echo 'Si';
									}
								?>
							</td>
						</tr>
						<tr>
							<th>Mensaje del solicitante :</th>
							<td><?php echo $this->request->data['ModifiedRequestOrder']['request_message'];?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>	
	</div>
</div>