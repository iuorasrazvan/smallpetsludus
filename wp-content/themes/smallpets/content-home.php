<?php 
	session_start (); 

	get_template_part('login'); 
	
	get_template_part('connect1'); 
	
	get_template_part('filter'); 
	
	
	$login=new Login ();


	if ($login->login)  {

		echo 'Welcome   ', $_SESSION['name'].'&nbsp&nbsp; '; 
		$login->anchorLogout (); 
	}
	
	