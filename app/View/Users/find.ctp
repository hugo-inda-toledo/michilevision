<script>
	
	function pulsar(e) 
	{
		tecla = (document.all) ? e.keyCode : e.which;
		return (tecla != 13);
	}
	
	function validateSearch()
	{
		var texto = $('#UserUserParam').val();
		
		if(texto == '')
		{
			$('#alertMessage').show('fast');
			return false;
		}
		else
		{
			$('#alertMessage').hide('fast');
		}
			
		if($("#UserFindForm input[name='data[User][user_type_search]']:radio").is(':checked')) 
		{
			$('#alertMessage2').hide('fast');
			$('#UserFindForm').submit();
		}
		else 
		{
			$('#alertMessage2').show('fast');
			return false;
		}
	}
</script>


<h3><?php echo $this->html->link('Usuarios','/users/'); ?> &gt; Listar usuarios</h3>

<!-- Buscador de centros de costo con botones-->

<?php echo $this->form->create('User', array('action' => 'find', 'inputDefaults' => array ('fieldset' => false, 'legend' => false), 'onkeypress' => 'return pulsar(event)')); ?>
<table border='0'>
	<tr>
		<td colspan='2'><h2><?php echo $this->html->link('Agregar usuario','/users/add/',array('class' => 'link_admin link_add')); ?></h2></td>
	</tr>
	<tr>
		<td><?php echo $this->form->input('user_param',array('label' => '', 'type'=>'text', 'placeholder' => 'Buscar...'))."<br><div id='alertMessage' style='display:none;'><h3>El texto de busqueda no puede estar vacío.</h3></div>"; ?></td>
		<td><?php echo $this->form->input('user_type_search', array(
																				'type' => 'radio', 
																				'separator' => '', 
																				'options' => array(
																											'name' => 'Nombre', 
																											'first_lastname' => 'Apellido Paterno',
																											'email' => 'Email', 
																											),
																				'checked' => 'name'
																				))."<br><div id='alertMessage2' style='display:none;'><h3>Debes seleccionar un parametro de busqueda.</h3></div>"; ?>
		</td>
		<td><?php echo $this->form->button('Buscar', array('type' => 'button', 'onclick' => 'Javascript:validateSearch();',  'class' => 'a_button')); ?></td>
	</tr>
</table>
</form>

<!-- Fin del buscador -->

<?php 
	echo $this->Session->flash();

	if(!empty($users))
	{
?>


<p align='center'><strong><u><font color='#CC2424'>Resultado de la busqueda.</font></u></strong></p>
<p align='center'><strong><font color='#008141' size="1">Parametro de busqueda: 
	<?php 
		if($dataSearch['User']['user_type_search'] == 'name')
			echo 'Nombre';
 		if($dataSearch['User']['user_type_search'] == 'first_lastname')
			echo 'Apellido';
		if($dataSearch['User']['user_type_search'] == 'email')
			echo 'Email';
		
		echo ', texto a buscar: '.$dataSearch['User']['user_param'];?></font></strong>
</p>

<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% usuarios de un total de busqueda de %count% usuarios).'));?>
</div>


<table>
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
        <th><?php echo $this->paginator->sort('name', 'Nombre', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre'));?></th>
        <th><?php echo $this->paginator->sort('first_lastname', 'Apellido P.', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por Apellido Paterno'));?></th>
        <th><?php echo $this->paginator->sort('second_lastname', 'Apellido M.', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por Apellido Materno'));?></th>
		<th><?php echo $this->paginator->sort('dni', 'Rut', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por dni'));?></th>
		<th><?php echo $this->paginator->sort('email_confirm', 'Email confirmado', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por confirmacion de email'));?></th>
		<th>Vista completa</th>
		<th>Editar</th>
		<th>Reenviar Correo</th>
    </tr>


 <?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo $user['User']['id'];?></td>
		<td><?php echo $user['User']['name'];?></td>
		<td><?php echo $user['User']['first_lastname'];?></td>
		<td><?php echo $user['User']['second_lastname'];?></td>
		<td><?php echo $user['User']['dni'];?></td>
		<?php
			$nombres = explode(' ', $user['User']['name']);
			$nombre_menu = $nombres[0].' '.$user['User']['first_lastname']; 
		?>
		<td>
			<?php 
				if($user['User']['email_confirm'] == 1)
					echo "Si";
				else
					echo "No"
			?>
		</td>
        <td>
            <?php echo $this->html->link('Ver Ficha','/users/view/'.$user['User']['id'],array('title' => 'Ver ficha de '.$nombre_menu, 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		<td>
            <?php echo $this->html->link('Editar','/users/edit/'.$user['User']['id'],array('title' => 'Editar ficha de '.$nombre_menu, 'class' => 'tip_tip_default link_admin link_edit')); ?>
        </td>
		<?php
			if($user['User']['email_confirm'] == 'No')
			{
				echo '<td>'
						.$this->html->link('Reenviar clave', '/email_senders/sendWelcomeMail/'.$user['User']['name'].'/'.$user['User']['first_lastname'].'/'.$user['User']['username'].'/'.$user['Pass']['pass_withoutHash'].'/'.$user['User']['email'].'/1', array('title' => 'Reenviar email a '.$nombre_menu, 'class' => 'tip_tip_default link_admin link_email_unconfirmed')).
					'</td>';
			}
			else
			{
				echo '<td>'
						.$this->Html->tag('span', 'Email confirmado', array('class' => 'tip_tip_default link_admin link_email_confirmed pointer','title' => $nombre_menu.' con email confirmado')).
					'</td>';
			}
		?>
    </tr>
    <?php endforeach; ?>
</table>

<br>
<div class="paging">
	<?php echo $this->paginator->prev('Anterior ', null, null, array('class' => 'disabled_prev'));?>
	<?php echo $this->paginator->numbers(array('separator' => '')); ?>
	<?php echo $this->paginator->next(' Siguiente', null, null, array('class' => 'disabled_next'));?>
</div>

<?php
	}
	else
	{
		echo "<br><br><br>";
		echo "<h3><p align='center'>La busqueda no arrojo resultados.</p></h3>";
	}
?>

<ul>
		<li><p align='center'><?php echo $this->html->link('Volver al listado','/cost_centers/',array('class' => 'tip_tip_default link_admin link_back','title' => 'Volver al listado de centros de costos'));?></p></li>
</ul>