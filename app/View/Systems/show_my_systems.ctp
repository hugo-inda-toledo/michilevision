<br><br>

<table border=0>
	<tr>
		<th colspan=3><p align='center'>Menu Principal &gt;  Mis Sistemas</p></th>
	</tr>
	<?php
		foreach($systems as $value)
		{
			echo "<tr>";
			echo "<td>".$this->html->link($value['system_name'], '/'.$value['table_name'].'/mainMenu', array('class' => 'tip_tip_default link_admin '.$value['css_class_url'], 'title' => 'Entrar a '.$value['system_name']))."</td>";
			echo "<td>".$value['system_description']."</td>";
			echo "</tr>";
		}
	?>
</table>

<p align='center'><?php echo $this->html->link('Cerrar Sesion','/users/logout/', array('class' => 'link_admin link_padlock tip_tip_default', 'title' => 'Cierra la sesion de usuario.'));?></p>