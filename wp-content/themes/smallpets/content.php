<?php 


	
	if (is_page('FAQ')) {
				echo '<div id="content-page-faq">'; 		
			 
				the_post(); 
				the_title ('<h2>','</h2>'); 
				the_content ();
			
	}
		
	if (is_page('About Us')) { 
	
				echo '<div id="content-page">'; 		
			 
				the_post(); 
				the_title ('<h2 id="aboutus">','</h2>'); 
				the_content ();
		
				echo '</div>'; 
		
				
		?>
			<div id="returndiv"> 
				<a href="#aboutus"><button id='buttonas'>Return at the TOP of the page</button></a> 
			</div> 
			
		<?php 
	}

	
	if (is_page('Home')) {
	
		echo '<div id="content-page">'; 		
		
		the_title (); 
		
		if (!is_user_logged_in()) { 
		
			wp_login_form ();
			
			echo '<h4>OR</h4>'; 
			
			wp_register ('',''); 
		
	
			

		}
		else  { 
		
			$user=new WP_User(get_current_user_id());
			
			echo ' Welcome ' .$user->first_name. ' '. $user->last_name; 
			
			echo '<br/>';
			
			if ($user->bdate) echo 'Your birthday is :'. $user->bdate.'<br/>'; 
			
			echo '<a href="'.home_url().'/updateuser'.'">Update your profile</a>'; 
			
			echo '<br/>'; 
		
		?>
		<a href="<?php echo wp_logout_url(home_url()); ?>">Logout</a>
		
		<?php
		
		}

		
		echo '</div>';  
			


	}
	
	
	if (is_page('Contact us')) {
	
		echo '<div id="content-page">'; 		
	 
		the_post(); 
		the_title ('<h2>','</h2>'); 
	?> 
	
		
		
	<?php 
		
	
		
		echo '</div>';  
			


	}
	
	
	if (is_home ())  {
		
		echo '<div id="content-home">'; 
			
		if (have_posts()) {
			
			while (have_posts())  {
				the_post(); 
			?> 
				<a href="<?php the_permalink();?>"><?php esc_html(the_title('<h2>','</h2>')); ?> </a> 
			<?php 
			

			}
			
			
		}
		echo '</div>'; 
	}
	
	
	
	if (is_single ()) {  
		echo '<div id="content-single">';
		

		    the_post ();
		
		?>
	
		<h2 <?php post_class();?>><?php the_title ();?></h2> 
			
		<?php
			
				
			the_content ();
			
	
			
			if (get_post_type()=='post' && get_post_gallery()==null) {
				echo '<h3>this Album has no images yet</h3>'; 
			}
			
			
			
		echo '</div>'; 
		
	
	}
	
	