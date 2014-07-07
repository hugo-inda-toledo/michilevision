<script>
$(function(){
	$('#sound').click(function(){
		$('embed').remove();
		$('body').append('<embed src="/michilevision/files/notification.mp3" autostart="true" hidden="true" loop="false">');     
	}); 
});
</script>

<div id="sound" class="hover">Hover here</div>