<?php echo $this->Session->flash();?>

<table>
    <tr>
        <th>Id</th>
        <th>Nombre</th>
        <th>Apellido P.</th>
        <th>Apellido M.</th>
		<th>Rut</th>
		<th>Vista completa</th>
		<th>Editar</th>
    </tr>


 <?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo $user['User']['id']; ?></td>
		<td><?php echo $user['User']['name']; ?></td>
		<td><?php echo $user['User']['first_lastname']; ?></td>
		<td><?php echo $user['User']['second_lastname']; ?></td>
		<td><?php echo $user['User']['dni']; ?></td>
		<?php
			$nombres = explode(" ", $user['User']['name']);
			$nombre_menu = $nombres[0]." ".$user['User']['first_lastname']; 
		?>
        <td>
            <?php echo $html->link('Ver Ficha','/users/view/'.$user['User']['id'],array('title' => 'Ver ficha de '.$nombre_menu, 'class' => 'tip_tip_default link_admin link_zoom')); ?>
        </td>
		<td>
            <?php echo $html->link('Editar','/users/edit/'.$user['User']['id'],array('title' => 'Editar ficha de '.$nombre_menu, 'class' => 'tip_tip_default link_admin link_edit')); ?>
        </td>
    </tr>
    <?php endforeach; ?>

</table>