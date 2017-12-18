<?php 

	get_header ();

	echo '<div id="content-attachment">';
		

		    the_post ();
		
		?>
	
		<h2 <?php post_class();?>><?php the_title ();?></h2> 
			
		<?php
			
				
			the_content ();
			
	

			
			
	echo '</div>'; 

