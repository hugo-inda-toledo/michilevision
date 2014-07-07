<?php
class Setting extends AppModel 
{
	var $name = 'Setting';
	var $hasMany = array('User');
}
?>