<?php
	echo $this->layout = null;
	echo $this->form->input('Commune.province_id', array('label' => 'Provincia Asociada', 'type' =>'select', 'options' => $provinces, 'empty' => 'Seleccione una provincia'));
	echo $this->form->input('Commune.commune_name', array('label' => 'Nombre de la Comuna', 'type' =>'text'));
	echo $this->form->end('Agregar Comuna');
?>