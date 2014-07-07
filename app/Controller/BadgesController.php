<?php
class BadgesController extends AppController
{
	var $name = 'Badges';
	var $helpers = array('Session', 'Html', 'Form','Time');
	var $uses = array('Badge','RenderFund','User');
	var $components = array('Password', 'Email', 'Auth');
	var $scaffold;
	
}
	
?>