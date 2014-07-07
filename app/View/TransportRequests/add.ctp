<?php
if(count($costCenters) != null)
{
echo $this->Html->script('functions_admin'); 
echo $this->Html->tag('h3',$this->html->link('Movilización','/transport_requests/mainMenu/').' &gt; Nuevo Ticket');
echo $this->Html->tag('h2','Ingresar nuevo ticket', array('class' => 'link_admin link_add'));
echo $this->Session->flash(); 
echo $this->form->create('TransportRequest', array('class' => 'form-request'));
?>


<table>
	<tr>
		<td>
			<table>
				<tr>
					<td>
					<?php
					echo $this->Html->tag('h2','Descripción del Ticket', array('class' => 'link_admin link_ticket'));
					echo $this->form->input('TransportRequest.user_name', array('label' => 'Solicitante', 'title' => 'Solicitante', 'class' => 'readonly','readonly' => 'readonly', 'type' => 'text', 'default' => $dataUser['User']['name'].' '.$dataUser['User']['first_lastname']));
					echo $this->form->input('TransportRequest.user_id', array('type' => 'hidden', 'value' => $dataUser['User']['id']));
					echo $this->form->input('TransportRequest.management_name', array('label' => 'Area',  'title' => 'Gerencia', 'class' => 'readonly', 'readonly' => 'readonly', 'type' => 'text', 'default' => $dataUser['Management']['management_name'], 'size' => 30));
					echo $this->form->input('TransportRequest.management_id', array('type' => 'hidden', 'value' => $dataUser['Management']['id']));
					?></td>
					<td class="left_separator">
					<?php
						echo $this->form->input('TransportRequest.cost_center_id', array('label' => 'Centro de costo', 'type'=>'select', 'options' => $costCenters, 'div'=>array('class'=>'select')));
						echo $this->form->input('TransportRequest.transport_type_id', array('label' => 'Tipo de transporte', 'type'=>'select', 'id' => 'transport', 'options' => $transports, 'div'=>array('class'=>'select'), 'empty' => 'Seleccione un transporte', 'onChange' => 'Javascript:getCompanies();', 'default' => ''));
						echo '<div id="loadingCompanies"></div><div id="companies"></div>';
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
			<hr>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td>
					<?php
					echo $this->Html->tag('h2','Datos del Ticket', array('class' => 'link_admin link_taxi'));
					echo $this->form->input('Option.trayectory', array('label' => 'Tipo de trayecto', 'type'=>'select', 'id' => 'trayectory', 'options' => array('chilevision_to_address' =>'De Chilevisión a Dirección', 'address_to_chilevision' => 'De Dirección a Chilevisión'), 'div'=>array('class'=>'select'), 'empty' => 'Seleccione el trayecto'));
					echo $this->Form->input('Option.type_direction', array('legend' => 'Utilizado por ', 'type' => 'radio', 'options' => array('me' => 'Mi', 'management_users' => 'Un Usuario De Mi Gerencia', 'external' => 'Persona Externa'), 'onClick' => 'Javascript:detectOption();'));
					?></td>
					<td class="left_separator">
					<?php
						
					?></td>	
				</tr>
				<tr>
					<td valign="top" id="me" style="display:none;">
						<?php echo $this->form->input('TransportRequest.management_address', array('label' => 'Selecciona la direccion', 'type'=>'select', 'id' => 'management_address', 'options' => $managementAddresses, 'div'=>array('class'=>'select')));?>
					</td>
				</tr>
				<tr>
					<td valign="top" id="management_users" style="display:none;">
						<?php echo $this->form->input('TransportRequest.management_user_address', array('label' => 'Selecciona la direccion', 'type'=>'select', 'id' => 'management_user_address', 'options' => $bookAddress, 'div'=>array('class'=>'select')));?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<hr>
		</td>
	</tr>
	<tr id="external" style="display: none;">
		<td>
			<table>
				<tr>
					<td>
					<?php
					echo $this->Html->tag('h2','Ingreso de nueva dirección para invitado', array('class' => 'link_admin link_region'));
					echo $this->form->input('TransportRequest.used_by_name', array('label' => 'Nombre y apellido del utilizante', 'type' =>'text'));
					echo $this->form->input('TransportRequest.used_by_dni', array('label' => 'Rut del utilizante', 'type' =>'text'));
					echo $this->form->input('Address.address_title', array('label' => 'Nombre de referencia/Lugar', 'type' =>'text'));	
					?></td>
					<td class="left_separator">
					<?php
						echo $this->form->input('Option.street_type', array('label' => 'Tipo de calle', 'type' => 'select', 'options' => array('Avenida' => 'Avenida', 'Calle' =>'Calle', 'Pasaje' => 'Pasaje', 'Camino' => 'Camino'), 'after' => $this->form->input('Address.address_name', array('label' => 'Nombre de la calle', 'type' =>'text'))));
						echo $this->form->input('Address.number', array('label' => 'Numero', 'type' =>'text'));
						echo $this->form->input('Address.apart_block', array('label' => 'Departamento/Edificio/Oficina', 'type' =>'text'));
						echo $this->form->input('Address.region_id', array('label' => 'Region', 'type' =>'select', 'id' => 'region', 'options' => $regions, 'empty' => 'Seleccione una región', 'onChange' => 'Javascript:getProvincesForAddress();'));
						echo '<div id="loadingProvinces"></div><div id="provinces">';
						echo '<div id="loadingCommunes"></div><div id="communes">';
					?></td>	
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top" id="ticketSubmit" style="display:none;">
			<?php echo $this->form->end('Generar Ticket'); ?>
		</td>
	</tr>
</table>

<?php


}

else
{
	echo "<p align='center'><font color='gray'><b><u>No tienes centros de costos asociados a este sistema para generar un ticket.</b></u></font></p>";
	echo "<p align='center'><b>".$this->html->link('Volver al listado', 'javascript:history.back()')."</b></p>";
}
?>