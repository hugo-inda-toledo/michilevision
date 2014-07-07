$(document).ready(function(){
		
		var opt_vars = {
				modal:				true,
				displayNav:			false,						
				overlayColor:		'#000000',
				overlayOpacity:		0.9,
				skipSetup:			false 				
			}
		
		setTimeout(function(){
			Shadowbox.open({  
				content:    '/michilevision/users/change_password',  
				player:     'iframe',
				title:      'Cambio obligatorio de contraseña',  
				options:	opt_vars,
				width:      500,  
				height:     450
			});
		}, 50);  
	});