<h3><?php echo $this->html->link('Direcciones','/addresses/'); ?> &gt; Agregar Dirección</h3>
<?php echo $this->Session->flash();  ?>
<?php echo $this->Html->script('functions_admin');  ?>


<div id="TitleView">
	<h2 class="link_admin link_add">Agregar Dirección</h2>
	<ul>
		<li><?php echo $this->html->link('Volver al listado','/addresses/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de direcciones'));?></li>
	</ul>
</div>


<table>
	<tr>
		<td class="border_none">
			<?php
			echo $this->form->create('Address');
			echo $this->form->input('Option.for_user', array('label' => 'Esta direccion es para algun usuario en particular?', 'id' => 'for_user', 'type' =>'select', 'options'  => array(1 => 'Si', 0 => 'No'), 'default' => 0, 'onChange' => 'Javascript:validateAddressForUser();'));
			echo '<div id="users" style="display:none;">'.$this->form->input('User.id', array('label' => 'Para que usuario?', 'type' =>'select', 'options' => $users, 'empty' => 'Seleccione un usuario')).'</div>';
			echo '<table>
					<tr>
						<td>'.$this->form->input('Address.address_title', array('label' => 'Nombre de referencia/Lugar', 'type' =>'text')).'</td>
						<td>'.$this->form->input('Option.street_type', array('label' => 'Tipo de calle', 'type' => 'select', 'options' => array('Avenida' => 'Avenida', 'Calle' =>'Calle', 'Pasaje' => 'Pasaje', 'Camino' => 'Camino'), 'after' => $this->form->input('Address.address_name', array('label' => 'Nombre de la calle', 'type' =>'text')))).'</td>
					</tr>';
			echo '<tr>	
						<td>'.$this->form->input('Address.number', array('label' => 'Numero', 'type' =>'text')).'</td>
						<td>'.$this->form->input('Address.apart_block', array('label' => 'Departamento/Edificio/Oficina', 'type' =>'text')).'</td>
					</tr>';
			echo '<tr>
						<td>'.$this->form->input('Address.region_id', array('label' => 'Region', 'type' =>'select', 'id' => 'region', 'options' => $regions, 'empty' => 'Seleccione una región', 'onChange' => 'Javascript:getProvincesForAddress();')).'</td>
						<td><div id="loadingProvinces"></div><div id="provinces"></td>
					</tr>';
			echo '<tr>
						<td><div id="loadingCommunes"></div><div id="communes"></td>
						<td></td>
					</tr>';		

			echo '<br /><br /><tr><td>
					</td></tr>
				</table>
			</form>';
			
			?>
		</td>	
	</tr>
</table>