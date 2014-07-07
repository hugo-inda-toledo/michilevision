<?php
echo $this->Html->tag('h3', 'Inicio &gt; Página principal');
echo $this->Session->flash();
?>

<div class="dashboard-row-1">
	<div class="dashboard-sec sec-1">
		
		<?php echo $this->Html->para(null, ' Nuevas Notificaciones'); ?>
			
		<div class="scroll">
			<?php
			if($notifications != false){
			echo '<table>';	
			$x = 0; $y = 0;
				
				foreach($notifications as $notification){
					if($x <= 9){
					echo '<tr>
							<th>'.$this->Html->tag('span', $notification['Notification']['time_elapsed'], array('class' => 'tip_tip link_admin link_clock', 'title' => $notification['Notification']['created'])).'</th>
							<td>'.$this->Html->link($notification['Notification']['message_to_user'], '/notifications/markAsReaded/'.$notification['Notification']['id'], array('class' => 'tip_tip link_admin '.$notification['Notification']['css_class'], 'title' => 'Revisar la notificación del '.$notification['Notification']['created'])).'</td>
						</tr>';
					$x++;
					}
					
					else {
					break;
					}
				}
			echo '</table>';	
			}
			
			else
			{
				echo $this->Html->tag('span', 'No hay nuevas notificaciones', array('class' => 'link_admin link_ok'));
			}
			?>
			</table>
		</div>
		
		
		<div class="btn_links">
			<?php 
			if($notifications != false){
				echo $this->Html->link('Marcar todo como leido', '/notifications/markAllReaded/', array('class' => 'tip_tip_default show-all', 'title' => 'Marca todas las notificaciones como leidas.'));
			}
			
			echo $this->Html->link('Ver todas mis notificaciones', '/notifications/showAll/', array('class' => 'tip_tip_default show-all', 'title' => 'Revisar todas mis notificaciones '));
			?>
		</div>	
	
	</div>
	
	
	
	
	<div class="dashboard-sec sec-2">
		<?php echo $this->Html->para(null, 'Mi Información');?>
		
		<div class="scroll">
			<table>
				<tr>
					<td><?php echo $this->Html->tag('span', $userData['User']['name'].' '.$userData['User']['first_lastname'].' '.$userData['User']['second_lastname'], array('class' => 'tip_tip link_admin link_user', 'title' => 'Nombre Completo')); ?></td>
				</tr>
				<tr>
					<td><?php echo $this->Html->tag('span', $userData['User']['dni'], array('class' => 'tip_tip link_admin link_dni', 'title' => 'Rut')); ?></td>
				</tr>
				<tr>
					<td><?php echo $this->Html->tag('span', $userData['Position']['position'], array('class' => 'tip_tip link_admin link_position', 'title' => 'Cargo')); ?></td>
				</tr>
				<tr>
					<td><?php echo$this->Html->tag('span', $userData['Management']['management_name'], array('class' => 'tip_tip link_admin link_management', 'title' => 'Gerencia')); ?></td>
				</tr>
				<?php
				if($userData['Headquarter']['headquarter_name'] != ''){
				echo '<tr>
						<td>
							'.$this->Html->tag('span', $userData['Headquarter']['headquarter_name'], array('class' => 'tip_tip link_admin link_management', 'title' => 'Jefatura')).'
						</td>
					</tr>';
				}
				?>
				<tr>
					<td><?php echo $this->Html->tag('span', $userData['User']['email'], array('class' => 'tip_tip link_admin link_email', 'title' => 'Correo Electronico')); ?></td>
				</tr>
			</table>
		</div>
		
		
		<div class="btn_links">
			<?php echo $this->Html->link('Mostrar mi información', '/users/myData', array('class' => 'tip_tip_default show-all', 'title' => 'Muestra toda la información de la cuenta como los sistemas disponibles, tipos de permiso, etc'));?>
		</div>
		
	</div>
</div>


<div class="dashboard-row-2">
	<div class="dashboard-sec">
		<?php echo $this->Html->para(null, 'Mis Aplicaciones'); ?>
		<ul>
			<?php
			if($systems != false)
			{
				foreach($systems as $value)
				{
					// sistema habilitado
					if($value['System']['enabled'] == 1){
					echo '<li>'.$this->html->link($value['System']['system_name'], '/'.$value['System']['table_name'].'/mainMenu', array('class' => 'tip_tip_default link_admin32x32 '.$value['System']['css_class_url'], 'title' => $value['System']['system_description'])).'</li>';
					}
					
					// sistema NO habilitado
					if($value['System']['enabled'] == 0){
					echo '<li style="opacity:0.4;">
							'.$this->Html->tag('span', $value['System']['system_name'], array('class' => 'link_admin32x32 '.$value['System']['css_class_url'])).'
						</li>';
					}
				}
			}
			else
			{
				echo $this->Html->tag('h3', 'No tienes aplicaciones asignadas a tu cuenta', array('class' => 'link_admin link_alert'));
			}
			?>
		</ul>
	</div>
	
</div>
