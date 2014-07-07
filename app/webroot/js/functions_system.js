/* Menús horizontales desplegables... */
(function($){

	$.fn.menuHorizontal = function(options){
		
		var defaults = {};
		var settings = $.extend({}, defaults, options);

		return this.each(function(){
			var obj = $(this);

			// Que ocupe todo el contenedor en IE
			obj.after('<div style="clear:both; width:0; height:0;"></div>');
			// Agrego clases para compatibilidad con IE en el CSS
			$('> li', obj).addClass('first-level').find('ul').addClass('.submenu');
			$('> li:first', obj).addClass('first');
			$('> li:last', obj).addClass('last');
			// Agrego los eventos para desplegar el menu
			$('> li', obj).mouseenter(function(){
				$(this).addClass('active').find('ul').stop(true, true).slideDown();
			}).mouseleave(function(){
				$(this).removeClass('active').find('ul').stop(true, true).slideUp();
			});

		});

	}

})(jQuery);





/* Inicializar Plugin Jquery DatePicker (selector de fechas)... */
function inicializarDatePicker(tipo){
	
	if(tipo == 'default'){
		$( '#datepickerUsuario' ).datepicker({
			changeMonth: true,
			changeYear: true,
			minDate: '-12M -70Y ', maxDate: '+1Y +12M +30D',
			firstDay: 1,
			yearRange: 'c-70:c+70',
			dateFormat: 'yy-mm-dd'
	    });
	}
	
	
	if(tipo == 'range'){
		$( '#datepicker-desde' ).datepicker({
	      defaultDate: "+1w",
	      changeMonth: true,
	      changeYear: true,
		  firstDay: 1,
		  dateFormat: 'yy-mm-dd'
	    });
	   
	   $( '#datepicker-hasta' ).datepicker({
	      defaultDate: "+1w",
	      changeMonth: true,
	      changeYear: true,
		  firstDay: 1,
		  dateFormat: 'yy-mm-dd'
	    });
	}
}






/* Inicializar Plugin Jquery TipTip (textos emergentes)... */

function inicializarTipTip(tipo){
	
	if(tipo == 'default'){
		$(".tip_tip_default").tipTip({
			maxWidth: 'auto',
			edgeOffset: 6,
			defaultPosition: 'bottom',
			delay: 100,
			fadeIn: 300,
			fadeOut: 300
		});
	}
	
	if(tipo == 'table'){
		$(".tip_tip").tipTip({
			maxWidth: 'auto',
			edgeOffset: 6,
			defaultPosition: 'right',
			delay: 100,
			fadeIn: 300,
			fadeOut: 600,
			
			exit: function(){
				setTimeout("$('#tiptip_holder').hide();", 3000);
			},
			
			keepAlive: true
		});
	}
	

	if(tipo == 'table_click'){
		$(".tip_tip_click").tipTip({
			activation: 'click',
			maxWidth: 'auto',
			edgeOffset: 6,
			defaultPosition: 'right',
			delay: 100,
			fadeIn: 300,
			fadeOut: 600,
			
			exit: function(){
				setTimeout("$('#tiptip_holder').hide();", 3000);
			},
			
			keepAlive: true
		});
	}
}




/* Esconder mensajes emergentes... */

function hide_tipTip(){
	setTimeout("$('#tiptip_holder').hide();",500);
}


function hide_flash(){
	$('.flash').delay(300).fadeOut(300).slideUp(500);
	$('.message').delay(300).fadeOut(300).slideUp(500);
}






/* Mostrar opciones de los menus de inicio para los sistemas... */

function menuSystemShow(id_div){
var speed = 350;
	$('div._menu div:nth-child(1)').slideDown(speed);
	$('div._menu div:nth-child(2)').slideUp(speed);
	$('div#' + id_div + ' div:nth-child(1)').slideUp(speed);
	$('div#' + id_div + ' div:nth-child(2)').slideDown(speed);
}



/* Permitir sólo valores numericos en inputs */

function onlyNumbers(e){
    var tecla= document.all ? tecla = e.keyCode : tecla = e.which;
    return ((tecla > 47 && tecla < 58) || tecla == 46);
}