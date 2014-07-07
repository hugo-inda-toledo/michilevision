
<div name="timediv" id="timediv">
<?php
	foreach($notifications as $notification)
	{
		echo "Mensaje: ".$notification['Notification']['message_to_user'];
		echo $html->link("Ver",$notification['Notification']['url']);
	}
?>
</div>
