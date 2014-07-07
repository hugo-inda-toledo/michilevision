<h3><?php echo $this->html->link('Autorizaciones','/authorizations/'); ?> &gt; Listar Circuitos</h3>
<?php echo $this->Session->flash();?>

<h2><?php echo $this->html->link('Crear Autorización','/authorizations/add/',array('class' => 'link_admin link_add')); ?></h2>

<table>
    <tr>
        <th>Id</th>
        <th>Autorización</th>
		<th>Activo</th>
		<th>Detalle</th>
		<th>Habilitar/Deshabilitar</th>
    </tr>


 <?php foreach ($authorizations as $authorization): ?>
    <tr>
        <td><?php echo $authorization['Authorization']['id']; ?></td>
		<td><?php echo $authorization['Authorization']['name']; ?></td>
		<td><?php 
					if($authorization['Authorization']['active'] == 1)
						echo "Si";
					else
						echo "No";
				?>
		</td>
		 <td><?php echo $this->html->link('Ver Ficha','/authorizations/view/'.$authorization['Authorization']['id'],array('title' => 'Ver Autorización', 'class' => 'tip_tip_default link_admin link_zoom'));?></td>
		<td><?php 
					if($authorization['Authorization']['active'] == 1)
						echo $this->Html->link('Deshabilitar', '/authorizations/disable/'.$authorization['Authorization']['id'].'/index', array('title' => 'Deshabilita esta autorización', 'class' => 'tip_tip_default link_admin link_disable'));
					else
						echo $this->Html->link('Habilitar', '/authorizations/enable/'.$authorization['Authorization']['id'].'/index', array('title' => 'Habilita esta autorización', 'class' => 'tip_tip_default link_admin link_enable'));
				?>
		</td>
    </tr>
    <?php endforeach; ?>
</table>

