<?php
class EmailComponent extends Object 
{  
	var $components = array('Email');
	
   function welcome ($name = " ", $first_lastname = " ", $email = " ", $username = " ", $password = " ")
   {  
		$this->Email->to = $email;
		$this->Email->from = 'noreply@michilevision.cl';
		$this->Email->sendAs = 'text';
		/*$this->Email->template = 'welcome';*/
		$this->Email->subject = $name.", Bienvenido a MiChilevision.cl";
		
		$this->Email->body = "Estimado ".$name." ".$first_lastname.", MiChilevision.cl te informa que
						te han creado una cuenta de usuario a nuestra plataforma. Tu nombre de usuario
						es: ".$username." y tu clave de acceso es: ".$password."
						Te recomendamos que en tu primera seccion cambies esta clave autogenerada. Saludos cordiales.";
		
		/*echo "<pre>";
		print_r($this->Email);
		echo "</pre>";*/
		
		if($this->Email->send($body))
		{
			return true;
		}
		else
		{
			return false;
		}
   }
}   
?>