<?php echo $this->Session->flash();?>


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
<h2><?php echo $this->html->link('Agregar usuario','/users/add/',array('class' => 'link_admin link_add')); ?></h2>


<!-- Buscador de centros de costo con botones-->
<div class="acc_detail">
	<div class="fr_info">
		<div class="info_01">
			<?php echo $this->form->create('User', array('action' => 'find', 'inputDefaults' => array ('fieldset' => false, 'legend' => false), 'onkeypress' => 'return pulsar(event)')); ?>
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
		</div>
	</div>
</div>


<!-- Fin del buscador -->

<div class="paging_results">
	<?php echo $this->paginator->counter(array('format' => 'Pagina %page% de %pages% (Mostrando %current% usuarios de un total de %count% usuarios).'));?>
</div>

<table>
    <tr>
        <th><?php echo $this->paginator->sort('id', 'Id', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por id'));?></th>
        <th><?php echo $this->paginator->sort('name', 'Nombre', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por nombre'));?></th>
        <th><?php echo $this->paginator->sort('first_lastname', 'Apellido P.', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por Apellido Paterno'));?></th>
        <th><?php echo $this->paginator->sort('second_lastname', 'Apellido M.', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por Apellido Materno'));?></th>
		<th><?php echo $this->paginator->sort('dni', 'Rut', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por dni'));?></th>
		<th><?php echo $this->paginator->sort('email', 'Email', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por email'));?></th>
		<th><?php echo $this->paginator->sort('email_confirm', 'Cuenta validada', array('class' => 'tip_tip_default', 'title' => 'Ordenar resultados por confirmacion de email'));?></th>
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
		<td><?php echo $user['User']['email']; ?></td>
		<td><?php echo $user['User']['email_confirm']; ?></td>
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