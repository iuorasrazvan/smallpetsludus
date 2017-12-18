<?php 

	get_header ();
	
	echo '<div id="content-page">'; 

		if (isset($_POST) &&!empty($_POST))  {
           		
			
			$error=apply_filters ('small_pets_register_filter',$_POST);
			
			if (($error->errors!=null)) {
				
				$error_user_login=$error->get_error_message ('error_user_login'); 
				
				$error_user_pass=$error->get_error_message ('error_user_pass'); 
				
				$error_user_email=$error->get_error_message ('error_user_email'); 
				
				$error_first_name=$error->get_error_message ('error_first_name'); 
				
				$error_last_name=$error->get_error_message ('error_last_name'); 
				
				$error_bdate=$error->get_error_message ('error_bdate'); 
				
		
			
				
			}
			
			else  {

				
				$args=array (
					'ID'=>get_current_user_id(), 
					'user_login'=>$_POST['user_login'], 
					'user_pass'=>wp_hash_password($_POST['user_pass']), 
					'user_email'=>$_POST['user_email'], 
					'first_name'=>$_POST['first_name'],
					'last_name'=>$_POST['last_name']
				
				
				
				);
				
			
				$result=wp_insert_user ( $args ); 
				
				if(is_wp_error($result)) {
					
					foreach ($result as $key=>$val) {
						foreach ($val as $key1=>$val1) {
							echo $key1; 
						} 
					}
					
				
				}
				
				else {
					update_user_meta ($result,'bdate',$_POST['bdate']); 
					update_user_meta($result,'password',$_POST['user_pass']); 
					echo 'user updated'; 
					
					echo '<a href="'.site_url ().'">Home</a>'; 
				
					exit ; 
					
				} 
				
			}
		}
				
	
	     $user=get_userdata (get_current_user_id());
	
	?> 
		<h3> Update fields </h3> 
		<form action="" method="POST">
		
			New username:<input type="text" name="user_login" value="<?php echo $user->user_login;?>"/>
			<?php if(isset($error_user_login)) echo $error_user_login; ?>
			<br/>
			
			New password:<input type="password" name="user_pass" value="<?php echo $user->password;?>"/>
			<?php if(isset($error_user_pass)) echo $error_user_pass; ?>
			<br/> 
			
			New Email: <input type="email" name="user_email" value="<?php echo $user->user_email; ?>"/>
			<?php if(isset($error_user_email)) echo $error_user_email; ?>
			<br/> 
			
			New first name:<input type="text" name="first_name" value="<?php echo $user->first_name;?>"/>
			<?php if(isset($error_first_name)) echo $error_first_name; ?>
			<br/> 
			
			New last name:<input type="text" name="last_name" value="<?php echo $user->last_name;?>"/>
			<?php if(isset($error_last_name)) echo $error_last_name;?> 
			<br/>
			
				      
			Birthdate:<input name='bdate' type="date" value="<?php echo $user->bdate;?>"/> 
			<?php if (isset($error_bdate)) echo $error_bdate; ?> 
			<br/> 
			
			<input type="submit" value="update"/> 
			

		
		
		</form>
	
	
	
	<?php 
	
	
	
	
	
	echo '</div>'; 
