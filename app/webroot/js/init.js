<!--

document.createElement('header');
document.createElement('footer');
document.createElement('nav');
document.createElement('aside');
document.createElement('article');
document.createElement('section');
document.createElement('hgroup');



$(document).ready(function(){
	
	/* Inicializar menú Administración... */
	$('.menu-systems').menuHorizontal();
	$('.menu-admin').menuHorizontal();
	
	
	/* Inicializar Shadowbox... */
	Shadowbox.init({
	    displayNav: true,
	    continuous: false,
	    enableKeys: true,
	    overlayOpacity: 0.90,
	    language:'es'
	});
	
	/* Inicializar Calendarios desplegables... */
	inicializarDatePicker('default');
	inicializarDatePicker('range');
	
		
	/* Inicializar textos emergentes... */
	inicializarTipTip('default');
	inicializarTipTip('table');
	inicializarTipTip('table_click');
	
	
	$('li.clearCache').click(function(){
		$('li.clearCache').css('background-position', '0px -25px');
	});
	
});



-->