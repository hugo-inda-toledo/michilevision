<?php
class AddressUser extends AppModel 
{
    var $name = 'AddressUser';
	
	var $belongsTo = array('User', 'Address');
}
?>