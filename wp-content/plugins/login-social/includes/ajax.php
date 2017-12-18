<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( "wp_ajax_hg_login_get_acc_menu_item", "hg_login_get_acc_menu_item_callback" );

function hg_login_get_acc_menu_item_callback() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'hg_login_acc_item' ) ) {
		die( __( 'Security check failed', 'hg_login' ) );
	}

	$html = hg_login_acc_menu_option_line( '', '' );

	echo json_encode( array( 'success' => 1, 'html' => $html ) );
	die;
}

add_action( "wp_ajax_hg_login_popup_ajax", 'hg_login_popup_ajax_callback' );
add_action( "wp_ajax_nopriv_hg_login_popup_ajax", 'hg_login_popup_ajax_callback' );

function hg_login_popup_ajax_callback() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'load_popup_nonce' ) ) {
		die( __( 'Security check failed', 'hg_login' ) );
	}

	$html = hg_login_get_login_popup_content();

	echo json_encode( array( "success" => 1, 'return' => $html ) );
	die();
}

add_action( "wp_ajax_hg_signup_popup_ajax", 'hg_login_signup_popup_ajax_callback' );
add_action( "wp_ajax_nopriv_hg_signup_popup_ajax", 'hg_login_signup_popup_ajax_callback' );

function hg_login_signup_popup_ajax_callback() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'hg_signup' ) ) {
        die( __( 'Security check failed', 'hg_login' ) );
    }
	$html = hg_login_get_signup_popup_content();

	echo json_encode( array( "success" => 1, 'return' => $html ) );
	die();
}


add_action( "wp_ajax_hg_forgotpass_popup_ajax", "hg_login_forgotpass_popup_ajax_callback" );
add_action( "wp_ajax_nopriv_hg_forgotpass_popup_ajax", "hg_login_forgotpass_popup_ajax_callback" );

function hg_login_forgotpass_popup_ajax_callback() {
	$html = hg_login_get_forgotpass_popup_content();

	echo json_encode( array( "success" => 1, 'return' => $html ) );
	die();
}

add_action( "wp_ajax_hg_resetpass_popup_ajax", "hg_login_resetpass_popup_ajax_callback" );
add_action( "wp_ajax_nopriv_hg_resetpass_popup_ajax", "hg_login_resetpass_popup_ajax_callback" );

function hg_login_resetpass_popup_ajax_callback() {
	$html = hg_login_get_resetpass_popup_content();

	echo json_encode( array( "success" => 1, 'return' => $html ) );
	die();
}


add_action( 'wp_ajax_hg_login_do_signup', 'hg_login_do_signup_callback' );
add_action( 'wp_ajax_nopriv_hg_login_do_signup', 'hg_login_do_signup_callback' );

function hg_login_do_signup_callback() {
	$http_post = ( 'POST' == $_SERVER['REQUEST_METHOD'] );

	if ( ! get_option( "users_can_register" ) ) {
		echo json_encode( array( 'error' => 'signup_closed' ) );
		die();
	}

	if ( ! isset( $_POST['nonce'] ) || empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'hg_signup' ) ) {
		echo json_encode( array( 'error' => 'wrong_nonce' ) );
		die();
	}

    if( HG_Login()->settings->recaptcha_enabled === 'yes' ):
        /**
         * If recaptcha is enabled from settings we need to verify it
         */
        if( !isset( $_REQUEST['recaptchaResponse'] ) || empty( $_REQUEST['recaptchaResponse'] ) ){
            echo json_encode( array( "errorMsg" => __( 'Missing recaptcha response', 'hg_login' ) ) );
            die();
        }

        $secret_key = HG_Login()->settings->recaptcha_secret_key;

        $url = add_query_arg(
            array(
                'secret' => $secret_key,
                'response' => $_REQUEST['recaptchaResponse']
            ),
            'https://www.google.com/recaptcha/api/siteverify'
        );

        $recaptcha_api_response = wp_remote_post( $url );

        $recaptcha_api_response = json_decode(wp_remote_retrieve_body($recaptcha_api_response),true);

        if(!$recaptcha_api_response['success']){
            echo json_encode( array( "errorMsg" => __( 'Recaptcha verification failed with error code '.$recaptcha_api_response['error-codes'], 'hg_login' ) ) );
            die();
        }

    endif;

	if ( $http_post ) {
		$user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : '';
		$user_email = isset( $_POST['user_email'] ) ? $_POST['user_email'] : '';


		$sanitized_user_login = sanitize_user( $user_login );

		if ( $sanitized_user_login == '' ) {
			echo json_encode( array( 'error' => 'empty_username' ) );
			die();
		} elseif ( ! validate_username( $user_login ) ) {
			echo json_encode( array( 'error' => 'invalid_username' ) );
			die();
		} elseif ( username_exists( $sanitized_user_login ) ) {
			echo json_encode( array( 'error' => 'username_exists' ) );
			die();
		} else {
			/** This filter is documented in wp-includes/user.php */
			$illegal_user_logins = array_map( 'strtolower', (array) apply_filters( 'illegal_user_logins', array() ) );
			if ( in_array( strtolower( $sanitized_user_login ), $illegal_user_logins ) ) {
				echo json_encode( array( 'error' => 'username_not_allowed' ) );
				die();
			}
		}

		if ( $user_email == '' ) {
			echo json_encode( array( 'error' => 'empty_email' ) );
			die();
		} elseif ( ! is_email( $user_email ) ) {
			echo json_encode( array( 'error' => 'invalid_email' ) );
			die();
		} elseif ( email_exists( $user_email ) ) {
			echo json_encode( array( 'error' => 'email_exists' ) );
			die();
		}


		$user_pass       = isset( $_POST['user_pass'] ) ? $_POST['user_pass'] : '';

		$user_data = array(
			'user_login' => wp_slash( $sanitized_user_login ),
			'user_email' => wp_slash( $user_email ),
			'user_pass'  => $user_pass,
		);

        // Hash and store the password.
        $usermeta['password'] = wp_hash_password( $user_pass );

        $redirect_to = esc_url( HG_Login()->settings->redirect_to_after_signup );

        if( function_exists( 'buddypress' ) ){
            $user_id = bp_core_signup_user( $user_data['user_login'], $user_data['user_pass'], $user_data['user_email'], $usermeta );
            if( !$user_id ){
                echo json_encode( array( 'error' => 'signup_failed', 'error_message' => __( 'Failed to Sign Up' ) ) );
                exit();
            }
            echo json_encode( array(
                'success'         => true,
                'redirect_to'     => $redirect_to,
                'verify_required' => HG_Login()->settings->email_verify_required == 'yes',
                'verify_msg'      => HG_Login()->settings->email_verify_call_to_action_text,
            ) );
            exit();
        }


		$new_user = wp_insert_user( $user_data );

		if ( ! is_wp_error( $new_user ) && intval( $new_user ) ) {
			update_user_option( $new_user, 'send_newsletter', ( $_POST['user_newsletter'] ? 'yes' : 'no' ), true );

			/**
			 * Fires after a new user registration has been recorded.
			 *
			 * @param int $user_id ID of the newly registered user.
			 */
			do_action( 'hg_login_register_new_user', $new_user );

			echo json_encode( array(
				'success'         => true,
				'redirect_to'     => $redirect_to,
				'verify_required' => HG_Login()->settings->email_verify_required == 'yes',
				'verify_msg'      => HG_Login()->settings->email_verify_call_to_action_text,
			) );
			exit();
		} else {
			echo json_encode( array( 'error' => 'signup_failed', 'error_message' => $new_user->get_error_message() ) );
			exit();
		}
	}
}

add_action( "wp_ajax_hg_login_check_unqiue_login", "hg_login_check_unqiue_login_callback" );
add_action( "wp_ajax_nopriv_hg_login_check_unqiue_login", "hg_login_check_unqiue_login_callback" );

function hg_login_check_unqiue_login_callback() {
	if ( ! isset( $_POST['login'] ) || $_POST['login'] == "" ) {
		echo json_encode( array( 'error' => 'empty_login' ) );
		die();
	}

	if ( ! isset( $_POST['nonce'] ) || empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'hg_signup' ) ) {
		echo json_encode( array( 'error' => 'wrong_nonce' ) );
		die();
	}

	$username = sanitize_user( $_POST['login'] );

	$exists = username_exists( $username );


	if ( $exists ) {
		echo json_encode( array( "error" => "username_exists" ) );
	} else {
		echo json_encode( array( "success" => "ok" ) );
	}
	die();
}

add_action( "wp_ajax_hg_login_check_unqiue_email", "hg_login_check_unqiue_email_callback" );
add_action( "wp_ajax_nopriv_hg_login_check_unqiue_email", "hg_login_check_unqiue_email_callback" );

function hg_login_check_unqiue_email_callback() {

	if ( ! isset( $_POST['email'] ) || $_POST['email'] == "" ) {
		echo json_encode( array( 'error' => 'empty_email' ) );
		die();
	}

	if ( ! isset( $_POST['nonce'] ) || empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'hg_signup' ) ) {
		echo json_encode( array( 'error' => 'wrong_nonce' ) );
		die();
	}

	$email = sanitize_email( $_POST['email'] );

	$exists = email_exists( $email );


	if ( $exists ) {
		echo json_encode( array( "error" => "email_exists" ) );
	} else {
		echo json_encode( array( "success" => "ok" ) );
	}
	die();
}


add_action( "wp_ajax_hg_login_do_login", "hg_login_do_login_callback" );
add_action( "wp_ajax_nopriv_hg_login_do_login", "hg_login_do_login_callback" );

function hg_login_do_login_callback() {

	if ( ! isset( $_POST['nonce'] ) || empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'hg_login' ) ) {
		echo json_encode( array( 'errorMsg' => __( 'Wrong Nonce Parameter', 'hg_login' ) ) );
		die();
	}

	if ( ! isset( $_REQUEST['login'] ) ) {
		echo json_encode( array( "errorMsg" => __( 'Invalid Username or Password', 'hg_login' ) ) );
		die();
	}

	$user_name = sanitize_user( $_REQUEST['login'] );

	if ( ! isset( $_REQUEST['pass'] ) ) {
		echo json_encode( array( "errorMsg" => __( 'Username or password is incorrect', 'hg_login' ) ) );
		die();
	}

	if( HG_Login()->settings->recaptcha_enabled === 'yes' ):
        /**
         * If recaptcha is enabled from settings we need to verify it
         */
        if( !isset( $_REQUEST['recaptchaResponse'] ) || empty( $_REQUEST['recaptchaResponse'] ) ){
            echo json_encode( array( "errorMsg" => __( 'Missing recaptcha response', 'hg_login' ) ) );
            die();
        }

        $secret_key = HG_Login()->settings->recaptcha_secret_key;

        $url = add_query_arg(
            array(
                'secret' => $secret_key,
                'response' => $_REQUEST['recaptchaResponse']
            ),
            'https://www.google.com/recaptcha/api/siteverify'
        );


        $recaptcha_api_response = wp_remote_post( $url );

        $recaptcha_api_response = json_decode(wp_remote_retrieve_body($recaptcha_api_response),true);

        if(!$recaptcha_api_response['success']){
            echo json_encode( array( "errorMsg" => __( 'Recaptcha verification failed with error code '.$recaptcha_api_response['error-codes'], 'hg_login' ) ) );
            die();
        }

    endif;

	$secure_cookie = '';
	$pass          = sanitize_text_field( $_REQUEST['pass'] );

	$user = get_user_by( 'login', $user_name );

	if ( ! $user && strpos( $user_name, '@' ) ) {
		$user = get_user_by( 'email', $user_name );
	}

	if ( $user ) {
		if ( get_user_option( 'use_ssl', $user->ID ) ) {
			$secure_cookie = true;
			force_ssl_admin( true );
		}
	}

	if ( $user && ! wp_check_password( $pass, $user->data->user_pass, $user->ID ) ) {
		echo json_encode( array( "errorMsg" => __( 'Username or password is incorrect', 'hg_login' ) ) );
		die();
	}

	$redirect_to = esc_url( HG_Login()->settings->redirect_to_after_login );

	$info = array(
		'user_login'    => $user_name,
		'user_password' => $pass,
		'remember'      => false
	);
	$user = hg_login_wp_signon( $info, $secure_cookie );

	if ( ! is_wp_error( $user ) ) {
		echo json_encode( array( "success" => 1, 'user' => $user, 'redirect_to' => $redirect_to ) );
		die();
	} else {
		echo json_encode( array( "errorMsg" => $user->get_error_message() ) );
		die();
	}

}


add_action( "wp_ajax_hg_login_do_send_forgotpassword_email", "hg_login_do_send_forgotpassword_email_callback" );
add_action( "wp_ajax_nopriv_hg_login_do_send_forgotpassword_email", "hg_login_do_send_forgotpassword_email_callback" );

function hg_login_do_send_forgotpassword_email_callback() {
	if ( ! isset( $_POST['nonce'] ) || empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'hg_login_forgotpass' ) ) {
		echo json_encode( array( 'errorMsg' => __( 'Wrong Nonce Parameter', 'hg_login' ) ) );
		die();
	}

	if ( ! isset( $_REQUEST['login'] ) ) {
		echo json_encode( array( "errorMsg" => __( 'Invalid Username or Password', 'hg_login' ) ) );
		die();
	}

    if( HG_Login()->settings->recaptcha_enabled === 'yes' ):
        /**
         * If recaptcha is enabled from settings we need to verify it
         */
        if( !isset( $_REQUEST['recaptchaResponse'] ) || empty( $_REQUEST['recaptchaResponse'] ) ){
            echo json_encode( array( "errorMsg" => __( 'Missing recaptcha response', 'hg_login' ) ) );
            die();
        }

        $secret_key = HG_Login()->settings->recaptcha_secret_key;

        $url = add_query_arg(
            array(
                'secret' => $secret_key,
                'response' => $_REQUEST['recaptchaResponse']
            ),
            'https://www.google.com/recaptcha/api/siteverify'
        );


        $recaptcha_api_response = wp_remote_post( $url );

        $recaptcha_api_response = json_decode(wp_remote_retrieve_body($recaptcha_api_response),true);

        if(!$recaptcha_api_response['success']){
            echo json_encode( array( "errorMsg" => __( 'Recaptcha verification failed with error code '.$recaptcha_api_response['error-codes'], 'hg_login' ) ) );
            die();
        }

    endif;

	$user_name = sanitize_user( $_REQUEST['login'] );

	$user = get_user_by( 'login', $user_name );

	if ( ! $user && strpos( $user_name, '@' ) ) {
		$user = get_user_by( 'email', $user_name );
	}

	if ( ! $user || is_wp_error( $user ) ) {
		echo json_encode( array( "errorMsg" => __( 'The User does not exist', 'hg_login' ) ) );
		die();
	}

	hg_login_resetpassword_notification( $user->ID );

	echo json_encode( array( "successMsg" => __( 'Please check your email for password reset link', 'hg_login' ) ) );
	die();
}

add_action( "wp_ajax_hg_login_do_resettpassword", "hg_login_do_resettpassword_callback" );
add_action( "wp_ajax_nopriv_hg_login_do_resettpassword", "hg_login_do_resettpassword_callback" );

function hg_login_do_resettpassword_callback() {
	if ( ! isset( $_POST['nonce'] ) || empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'hg_login_resetpass' ) ) {
		echo json_encode( array( 'errorMsg' => __( 'Wrong Nonce Parameter', 'hg_login' ) ) );
		die();
	}

	if ( ! isset( $_REQUEST['login'] ) ) {
		echo json_encode( array( "errorMsg" => __( 'Invalid Username or Password', 'hg_login' ) ) );
		die();
	}

	$login = sanitize_user( $_REQUEST['login'] );

	$user = get_user_by( 'login', $login );

	if ( ! $user || is_wp_error( $user ) ) {
		$user = get_user_by( 'email', $login );
	}

	if ( ! $user ) {
		echo json_encode( array( "errorMsg" => __( 'User not found', 'hg_login' ) ) );
		die();
	}

	$pass1 = isset( $_REQUEST['pass1'] ) ? sanitize_text_field( $_REQUEST['pass1'] ) : '';
	$pass2 = isset( $_REQUEST['pass2'] ) ? sanitize_text_field( $_REQUEST['pass2'] ) : '';

	if ( $pass1 == '' || $pass2 == '' ) {
		echo json_encode( array( "errorMsg" => __( 'An empty password is provided', 'hg_login' ) ) );
		die();
	}

	if ( $pass1 !== $pass2 ) {
		echo json_encode( array( "errorMsg" => __( 'Passwords do not match', 'hg_login' ) ) );
		die();
	}

	reset_password( $user, $pass1 );
	echo json_encode( array( "successMsg" => __( 'Password reseted successfully', 'hg_login' ) ) );
	die();
}

add_action( "wp_ajax_hg_login_do_logout", "hg_login_do_logout_callback" );
add_action( "wp_ajax_nopriv_hg_login_do_logout", "hg_login_do_logout_callback" );

function hg_login_do_logout_callback() {
	if ( ! isset( $_POST['nonce'] ) || empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'hg_login_logout' ) ) {
		echo json_encode( array( 'errorMsg' => __( 'Wrong Nonce Parameter', 'hg_login' ) ) );
		die();
	}

	wp_logout();
	echo json_encode( array( 'success' => 1, 'redirect_to' => HG_Login()->settings->redirect_to_after_logout ) );
	die();

}