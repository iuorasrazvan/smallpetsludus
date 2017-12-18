<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <title><?php wp_title(); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
        <?php wp_head(); ?>
		<script src='https://www.google.com/recaptcha/api.js'></script>

    </head>	


	<body> 
		<div id ="header">
			<h2><a href="<?php bloginfo('home');?>/home" title="<?php bloginfo('description');?>"><?php bloginfo('name');?></a></h2> 

			<?php 
				$args=array (
					'sort_column'=>'menu_order', 
					'title_li'=>'',
					'exclude'=>'188,192', 
				); 
			?>
				
				<ul class="pages"> 
				
					<?php wp_list_pages ($args);?> 
					
				</ul> 
				
		</div> 
		
		
