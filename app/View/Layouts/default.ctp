<?php $cakeDescription = __d('cake_dev', 'Mi Chilevisión');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $cakeDescription." :: ".$title_for_layout; ?></title>
<?php echo $this->Html->charset();?>
<link rel="icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<?php echo $this->Html->css('reset');?>
<?php echo $this->Html->css('cake.generic');?>
<?php echo $this->Html->css('jquery-ui-1.10.0.custom');?>
<?php echo $this->Html->css('jquery-menus');?>
<?php echo $this->Html->css('jquery.tipTip');?>
<?php echo $this->Html->css('shadowbox');?>
<?php echo $this->Html->script('shadowbox-3.0.3/shadowbox');?>
<?php echo $this->Html->script('jquery-1.9.0.js');?>
<?php echo $this->Html->script('jquery-ui-1.10.0.custom.js');?>
<?php echo $this->Html->script('jquery.tipTip');?>
<?php echo $this->Html->script('functions_system');?>
<?php echo $this->Html->script('init');

if(!empty($user)){
	
	/*if($user['User']['tour_validated'] == 0){
	echo $this->Html->script('jquery.tour');
	echo $this->Html->script('tourSteps');
	echo $this->Html->css('jQueryTour');
	}*/
	?>
	<script>
			function show5(){
			if (!document.layers&&!document.all&&!document.getElementById)
			return

			 var Digital=new Date()
			 var hours=Digital.getHours()
			 var minutes=Digital.getMinutes()
			 var seconds=Digital.getSeconds()

			var dn="pm"
			if (hours<12)
			dn="am"
			if (hours>12)
			hours=hours-12
			if (hours==0)
			hours=12

			 if (minutes<=9)
			 minutes="0"+minutes
			 if (seconds<=9)
			 seconds="0"+seconds
			//change font size here to your desire
			myclock="<font size='3' face='arial' color='#b40505'><b>"+hours+":"+minutes+":"
			 +seconds+" "+dn+"</b></font>"
			if (document.layers){
			document.layers.liveclock.document.write(myclock)
			document.layers.liveclock.document.close()
			}
			else if (document.all)
			liveclock.innerHTML=myclock
			else if (document.getElementById)
			document.getElementById("liveclock").innerHTML=myclock
			setTimeout("show5()",1000)
			 }


			window.onload=show5
			 //-->
	</script>
	<?php
	
	if($user['User']['admin'] == 1){
	echo $this->Html->script('functions_admin');
	}
	
	echo $this->Html->css('cake.admin');
	
	if($user['User']['email_confirm'] == 0){
	echo $this->Html->script('shadowbox_change_password');
	}
}

echo $this->Html->css('cake.systems');
echo $scripts_for_layout;
?>

</head>
<body>	
	<header>
		<h1><?php echo $this->Html->link(__('Sistema Global Chilevisión', true), '/');?></h1>
		<?php
		if(!empty($user)){
		

		// Imprimir Buscador...	
		echo '<div id="search_holder">
				'.$this->form->create('Search',array('inputDefaults'=>array('div'=>false))).
				$this->form->input('keyword',array('div'=>false,'label'=>false)).'
			</form>
		</div>';
				
		
		// Imprimir informacion de usuario...
		if($user['User']['picture'] != NULL)
			$profilePic_url = $user['User']['picture'];

		if($user['User']['picture'] == NULL)
			$profilePic_url = 'user_head.png';
		
		$style = '';
		
		if($countNotifications == 0)
		{
			$style = 'setting';
		}
		else
		{
			$style = 'settings';
		}


		echo '<div id="user_info">
				'.$this->Html->link(
				$this->Html->image($profilePic_url, array('id' => 'profileLittle','alt' => $user['User']['name'].' '.$user['User']['first_lastname'].' '.$user['User']['second_lastname'], 'class' => 'profilePic')),
				'/users/myData/',
				array('escape' => false)).'
				
				<h5>'.$this->html->link($user['User']['name'].' '.$user['User']['first_lastname'], '/users/myData', array('class' => 'tip_tip_default', 'title' => 'Revisa tu información')).'</h5>
				<ul>
					<li>'.$this->html->link('Home', '/dashboard',array('title' => 'Ir a página de inicio','class' => 'step1 home tip_tip_default')) . '</li>
					<li>'.$this->html->link('Preferencias', '/users/settings/',array('title' => 'Editar mis preferencias','class' => 'step2 settings tip_tip_default')) . '</li>
					<li>'.$this->Html->link('Cerrar sesión', array('plugin'=>null, 'admin'=>false, 'controller'=>'users', 'action'=>'logout'), array('title'=>'Cerrar mi sesión de usuario', 'class'=>'step3 logout tip_tip_default')) . '</li>';
		}
		
		if(!empty($user))
		{	
			echo '<li>'.$this->Html->tag('span', '', array('id' => 'liveclock', 'style' => 'position:absolute; margin: 9px 0px 0px 15px;', 'class' => 'link_admin link_clock')).'</li>';
		}
		
		echo "</ul></div>";
		?>
	</header>

	<?php
	if(!empty($user))
	{
		echo '<nav id="systems">
				<ul class="menu-systems step4">';
				
		/*echo "<pre>";
		print_r($mySystems);
		echo "</pre>";*/
		
						
		foreach($mySystems as $system)
		{
			/*echo '<li>'.$this->html->link($system['System']['system_name'],'#').'
						<ul>
							<li>'.$this->html->link('Menú principal', '/'.$system['System']['table_name'].'/mainMenu').'</li>
							<li>'.$this->html->link('Listado de solicitudes', '/'.$system['System']['table_name'].'/').'</li>
							<li>'.$this->html->link('Ingresar Solicitud', '/'.$system['System']['table_name'].'/add').'</li>
							<li>'.$this->html->link('Informes y estadísticas', '/'.$system['System']['table_name'].'/statsMenu').'</li>
						</ul>
					</li>';*/
					
			
			echo '<li>'.$this->html->link($system['System']['system_name'],'#');
			
			if($system['SystemSection'] != null)
			{
				echo "<ul>";
				
				foreach($system['SystemSection'] as $section)
				{
					echo '<li>'.$this->html->link($section['section_name'], '/'.$system['System']['table_name'].'/'.$section['section_function']).'</li>';
				}
				
				echo "</ul>";
			}
			
			echo "</li>";
		}
						
		echo '</ul>
			</nav>';
	}

	if(!empty($user)){
		if($user['User']['admin'] == 1){
		echo '<nav id="admin">
					<ul class="menu-admin">
						<li>
							'.$this->html->link('Usuarios','#').'
							<ul>
								<li>'.$this->html->link('Listar Usuarios', '/users/').'</li>
								<li>'.$this->html->link('Agregar Usuario', '/users/add').'</li>
							</ul>
						</li>
						<li>
							'.$this->html->link('Gerencias/Jefaturas','#').'
							<ul>
								<li>'.$this->html->link('Listar Gerencias', '/managements/').'</li>
								<li>'.$this->html->link('Agregar Gerencia', '/managements/add').'</li>
								<li>'.$this->html->link('Listar Jefaturas', '/headquarters/').'</li>
								<li>'.$this->html->link('Agregar Jefatura', '/headquarters/add').'</li>
							</ul>
						</li>
						<li>
							'.$this->html->link('Centros de costo','#').'
							<ul>
								<li>'.$this->html->link('Listar Centros De Costo', '/cost_centers/').'</li>
								<li>'.$this->html->link('Agregar Centro De Costo', '/cost_centers/add').'</li>
								<li>'.$this->html->link('Listar Asignaciones', '/cost_center_users/').'</li>
								<li>'.$this->html->link('Asignar Centro De Costo a Usuario', '/cost_center_users/add').'</li>
							</ul>
						</li>
						<li>
							'.$this->html->link('Sistemas','#').'
							<ul>
								<li>'.$this->html->link('Listar Sistemas', '/systems/').'</li>
								<li>'.$this->html->link('Agregar Sistema', '/systems/add').'</li>
								<li>'.$this->html->link('Listar Secciones de los sistemas', '/system_sections/').'</li>
								<li>'.$this->html->link('Agregar Seccion a un Sistema', '/system_sections/add').'</li>
								<li>'.$this->html->link('Listar Asignaciones de sistemas a usuarios', '/user_systems/').'</li>
								<li>'.$this->html->link('Asignar Sistema a Usuario', '/user_systems/add').'</li>
							</ul>
						</li>
						<li>
							'.$this->html->link('Permisos','#').'
							<ul>
								<li>'.$this->html->link('Listar Permisos', '/permissions/').'</li>
								<li>'.$this->html->link('Agregar Permiso', '/permissions/add').'</li>
								<li>'.$this->html->link('Listar Asignaciones', '/user_permissions/').'</li>
								<li>'.$this->html->link('Asignar Permiso Temporal', '/user_permissions/add').'</li>
							</ul>
						</li>
						<li>
							'.$this->html->link('Perfiles','#').'
							<ul>
								<li>'.$this->html->link('Listar Perfiles', '/profiles/').'</li>
								<li>'.$this->html->link('Agregar Perfil', '/profiles/add').'</li>
								<li>'.$this->html->link('Listar Asignaciones de Usuarios', '/user_profiles/').'</li>
								<li>'.$this->html->link('Asignar Perfil a Usuario', '/user_profiles/add').'</li>
								<li>'.$this->html->link('Listar Asignaciones de Permisos', '/profile_permissions/').'</li>
								<li>'.$this->html->link('Asignar Permisos a un Perfil', '/profile_permissions/add').'</li>
							</ul>
						</li>
						
						<li>
							'.$this->html->link('Reemplazos','#').'
							<ul>
								<li>'.$this->html->link('Listar Reemplazos', '/replacements/').'</li>
								<li>'.$this->html->link('Agregar Reemplazo', '/replacements/add').'</li>
							</ul>
						</li>
						
						<li>
							'.$this->html->link('Circuitos','#').'
							<ul>
								<li>'.$this->html->link('Listar Circuitos', '/circuits/').'</li>
								<li>'.$this->html->link('Crear Circuito', '/circuits/add').'</li>
							</ul>
						</li>
						<li>
							'.$this->html->link('Autorizaciones','#').'
							<ul>
								<li>'.$this->html->link('Listar Autorizaciones', '/authorizations/').'</li>
								<li>'.$this->html->link('Crear Autorizacion', '/authorizations/add').'</li>
							</ul>
						</li>
						<li>
							'.$this->html->link('Territorios','#').'
							<ul>
								<li>'.$this->html->link('Listar Regiones', '/regions/').'</li>
								<li>'.$this->html->link('Listar Provincias', '/provinces/').'</li>
								<li>'.$this->html->link('Listar Comunas', '/communes/').'</li>
								<li>'.$this->html->link('Listar Direcciones', '/addresses/').'</li>
							</ul>
						</li>
						<li>
							'.$this->Html->link('Ajustes', '#').'
							<ul>
								<li>'.$this->html->link('Conexiones SQL Server','/sqlserver_links/').'</li>
								<li>'.$this->html->link('Limpiar Cache','/external_functions/clear_cache').'</li>
							</ul>
						</li>
					</ul>
				</nav>';
		}
	}
	?>
	
	<div id="main_content" onclick="hide_tipTip(); hide_flash();">
		<?php echo $this->Session->flash();?>
		<?php echo $this->Session->flash('auth');?>
		<?php echo $content_for_layout;?>
	</div>
	<footer>
		<p>&copy; 2013 Desarrollado por el departamento de Tecnologías de información, Red Televisiva Chilevisión.</p>
	</footer>
</body>
</html>