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
					'user_login'=>$_POST['user_login'], 
					'user_pass'=>$_POST['user_pass'], 
					'user_email'=>$_POST['user_email'], 
					'first_name'=>$_POST['first_name'],
					'last_name'=>$_POST['last_name']
					
				
				);
				
				
				$result=wp_insert_user ($args); 
				
				if(is_wp_error($result)) {
					
					foreach ($result as $key=>$val) {
						foreach ($val as $key1=>$val1) {
							echo $key1; 
						} 
					}
					
				
				}
				
				else { 
					add_user_meta($result,'bdate',$_POST['bdate'],true);
					add_user_meta($result,'password',$_POST['user_pass']); 
					echo 'user created'; 

					echo '<a href="'.site_url ().'">Home</a>'; 
				
					exit ; 
					
				}
			
			
			}
		}
			
		
		
		
		
	

	?>
	
		<form method="post" action=""/> 
		
			Username:<input type="text" name="user_login" value="<?php if (isset($_POST['user_login'])) echo $_POST['user_login'];?>"minlength="5" maxlength="30"/>		
			<?php if(isset($error_user_login)) echo $error_user_login; ?>
			</br>
			
			Password:<input type="password" name="user_pass" value="<?php if (isset($_POST['user_pass'])) echo  $_POST['user_pass'];?>"minlength="5" maxlenght="30"/>
			<?php if(isset($error_user_pass)) echo $error_user_pass; ?>  
			</br> 
			
			Email: <input type="email" name="user_email" value="<?php if (isset($_POST['user_email'])) echo $_POST['user_email'];?>" minlength="5" maxlength="30"/> 
			<?php if(isset($error_user_email)) echo $error_user_email; ?>
			</br> 
			
			First Name: <input type="text" name="first_name" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name'];?>" minlength="5" maxlength="30"/> 
			<?php if(isset($error_first_name)) echo $error_first_name; ?>
			</br>
			
			Last Name: <input type="text" name="last_name" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name'];?>" minlength="5" maxlength="30"/> 
			<?php if(isset($error_last_name)) echo $error_last_name; ?>
			</br>
			
	      
			Birthdate:<input name='bdate' type="date" value="<?php if(isset($_POST['bdate'])) echo $_POST['bdate'];?>"/> 
			<?php if (isset($error_bdate)) echo $error_bdate; ?> 
			<br/> 
			
			<input type="submit" value="Register"/> 
			
		</form>
		
	
	
	
	</div>
	
	