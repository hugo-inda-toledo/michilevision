<?php foreach ($sistemas as $key=>$value):?>
    <option value="<?php echo $key;?>"><?php echo $value;?></option>
<?php endforeach;


echo $form->input(
			'CentroCostoUsuario.CentroDeCostoAsociado',
			array (
				'type'=>'select',
				'options'=>$resultCentroCostos,
				'multiple' => 'multiple',
				'div'=>array('class'=>'select')
				)
			);

echo $form->end('Guardar Asignacion');

?>