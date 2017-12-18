<?php 

	get_header(); 
	
	echo '<div id="content-page">'; 
	
	global $wpdb; 
	
	if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))  {
		
		$url='https://www.google.com/recaptcha/api/siteverify';
		$args=array (
			'method'=>'POST', 
			'secret'=>'6LddbjwUAAAAAHwp7ebmZNES6KXmzqVmcPJOYsns', 
			'response'=>$_POST['g-recaptcha-response'],
			'httpversion'=>'1.1',

		); 
		
		$response=wp_remote_post ($url, $args);
		
		if (!is_wp_error ($response))	{ 
		    
		
			$data=array (
				'id'=>'',
				'contact_name'=>esc_html(trim($_POST['contact_name'])), 
				'contact_email'=>esc_html(trim($_POST['contact_email'])),
				'contact_tel'=>esc_html(trim($_POST['contact_tel'])), 
				'contact_comment'=>esc_html(trim($_POST['contact_comment']))
			);
			

			
            $result=$wpdb->insert('wp_contact_form',$data); 
			
			if ($result) {
				echo '<h3>Message succesfully transmited.</h3>'; 
				exit (); 
			}
			
			
			
		}
	} 
	
	
	
?>
		<h2>Contact Us</h2> 
		<form method="post" class="contact-form" action="">


			Name:*<br/><input type="text" name="contact_name" minlength="3" maxlength="30" required/> 
			<br/> 
			Email:*<br/><input type="email" name="contact_email" minlength="3" maxlength="30" required/> 
			<br/> 
			Telephone:<br/><input type="text" name="contact_tel"/> 
			<br/> 
			<br/> 
			Comment:<br/><textarea cols="20" rows="5" name="contact_comment"></textarea> 
			<br/> 
			
			<div class="g-recaptcha" data-sitekey="6LddbjwUAAAAADU9F8yFvqe1X_JkucSHxXHDLwKl"></div>
			<br/>

			<input type="submit" value="send"/>
			

		
		
		</form> 
		
		
		<div class="contact-info">
			<address> 
				Ludus, str Mihai Eminescu, nr 70</br>
				jud Mures</br> 
				Tel: 0740 555 222
			
			
			</address>
		
		</div> 









<?php 
	

	echo '</div>'; 
	
	