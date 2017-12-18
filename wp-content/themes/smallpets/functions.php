<?php 
	 
	
	function smallpets_enqueue_script_style () {
		wp_enqueue_style ('style',get_stylesheet_uri()); 
		
	    if(is_page('FAQ')) {
			wp_enqueue_script('script1',get_stylesheet_directory_uri().'/js/script1.js');
		}
		
	}

	add_action ('wp_enqueue_scripts', 'smallpets_enqueue_script_style'); 
	

    add_filter( 'register_url', 'my_register_page' );
	
	
	function my_register_page( $register_url ) {
	
		return site_url ().'/register';
	}
	
	
	add_filter ('small_pets_register_filter', 'function_small_pets_register_filter',1,1); 
	
	function function_small_pets_register_filter  ($args) { 
		
		
		$error=new WP_Error ();
	
		
		if ( empty( $_POST['user_login'] ) || ! empty( $_POST['user_login'] ) && trim( $_POST['user_login'] ) == '' ) {
		   

			$error->add( 'error_user_login','<strong>ERROR</strong>: You must include a username.');
			
		}
		
		if ( empty( $_POST['user_pass'] ) || ! empty( $_POST['user_pass'] ) && trim( $_POST['user_pass'] ) == '' ) {

			$error->add( 'error_user_pass','<strong>ERROR</strong>: You must include a password.');
			
		}
	
		if ( empty( $_POST['user_email'] ) || ! empty( $_POST['user_email'] ) && trim( $_POST['user_email'] ) == '' ) {

			$error->add( 'error_user_email','<strong>ERROR</strong>: You must include an email.');
			
		}
		
			
		if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {

			$error->add( 'error_first_name','<strong>ERROR</strong>: You must include a name.');
			
		}
		
				
			
		if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['last_name'] ) == '' ) {

			$error->add( 'error_last_name','<strong>ERROR</strong>: You must include a name.');
			
		}
		
			
		
			
		if    ($_POST['bdate']=='' )  {
		

			$error->add( 'error_bdate','<strong>ERROR</strong>: You must include a date.');
			
		}
		
		
	

		
		return $error; 

	}
	
	
	

	
	
	
	