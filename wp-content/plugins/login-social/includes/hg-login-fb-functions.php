<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Retrieve the url for redirecting to login a user
 *
 * @return string
 */
function hg_login_get_fb_login_link() {
	global $wp;
	$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
	$url         = esc_url( add_query_arg( array(
		'client_id'    => HG_Login()->settings->facebook_app_id,
		'redirect_uri' => esc_url( site_url( 'wp-login.php?action=hg_login_fb_login&rdr=' . $current_url ) ),
		'fields'       => 'first_name,last_name,id,name,gender,email',
		'scope'        => 'public_profile,email,user_about_me',
	),
		'https://www.facebook.com/dialog/oauth'
	) );

	return $url;
}

/**
 * Login user with facebook authentication
 */
function hg_login_fb_access_login() {
	if ( isset( $_GET['code'] ) && ! empty( $_GET['code'] ) ) {
		$me_info = hg_login_fb_retrieve_current_user_data();
		$secure  = ( 'https' === parse_url( wp_login_url(), PHP_URL_SCHEME ) );
        $user    = get_user_by( 'login', strtolower($me_info->first_name).'.'.strtolower($me_info->last_name) );

        $redirect_to = esc_url( HG_Login()->settings->redirect_to_after_login );

        if ( $redirect_to == '' ) {
            if ( isset( $_GET['rdr'] ) ) {
                $redirect_to = $_GET['rdr'];
            } else {
                $redirect_to = site_url();
            }
        }

		if ( ! $user && isset( $me_info->email ) ) {
			$user = get_user_by( 'email', $me_info->email );
		}

		if ( ! $user ) {
		    $user = հg_login_register_fb_account( $me_info );

		}

        wp_set_auth_cookie( $user->ID, true );

        if(!ctype_alpha($me_info->first_name ) || !ctype_alpha($me_info->first_name)){
            setcookie( 'hg_login_action', 'latin_letters');
            return wp_redirect( $redirect_to );

        }

		if ( ! is_wp_error( $user ) ) {
            setcookie( 'hg_login_action', 'need_refresh');
			wp_redirect( $redirect_to );
		} else {
			setcookie( 'hg_login_action', 'login_failed');
			wp_redirect( $redirect_to );
		}
	}
}

function հg_login_register_fb_account( $me_info ){
    $user_pass = wp_generate_password( 12, false );

    $login = strtolower($me_info->first_name).'.'.strtolower($me_info->last_name);

    $errors = new WP_Error();

    if ( ! isset( $me_info->email ) ) {
        $errors->add( 'no_email', __( 'You have not provided a public email, in order to register you need to have an email address, Alternatively you can register with Signup form', 'hg_login' ) );
        return $errors;
    }

    if ( username_exists( $login ) ) {
        $errors->add( 'user_exists', __( 'Username Already exists', 'hg_login' ) );
        return $errors;
    }

    if ( email_exists( $me_info->email ) ) {
        $errors->add( 'user_exists', __( 'Your email address is already registered', 'hg_login' ) );
        return $errors;
    }

    $data = array(
        'user_pass'    => $user_pass,
        'user_login'   => $login,
        'user_email'   => $me_info->email,
        'display_name' => $me_info->name,
        'first_name'   => $me_info->first_name,
        'last_name'    => $me_info->last_name,
    );

    $new_user = wp_insert_user( $data );

    if ( is_wp_error( $new_user ) || !intval( $new_user ) ) {
        $errors->add( 'user_exists', __( 'Could not create a user', 'hg_login' ) );
        return $errors;
    }

    update_user_option( $new_user, 'send_newsletter', 'yes', true );

    /**
     * Fires after a new user registration has been recorded.
     *
     * @param int $user_id ID of the newly registered user.
     */
	do_action( 'hg_login_register_new_user', $new_user, 'facebook' );

	update_user_option( $new_user, 'hg_login_activation_key', '', true );
	update_user_option( $new_user, 'hg_login_user_activated', 'yes', true );

    $user          = get_user_by( 'ID', $new_user );
    
    return $user;
}

/**
 * Retrieve current facebook user info by using graph api from /me endpoint
 *
 * @return array|bool|mixed|object|WP_Error
 */
function hg_login_fb_retrieve_current_user_data() {
	if ( isset( $_GET['code'] ) ) {
		$code = $_GET['code'];

		$remote = wp_remote_get(
			add_query_arg(
				array(
					'client_id'     => HG_Login()->settings->facebook_app_id,
					'client_secret' => HG_Login()->settings->facebook_app_secret,
					'code'          => $code,
					'redirect_uri'  => esc_url( site_url( 'wp-login.php?action=hg_login_fb_login' ) ),

				),
				'https://graph.facebook.com/oauth/access_token'
			)
		);

		if ( is_wp_error( $remote ) ) {
			wp_die( $remote->get_error_message() );
		}


		$body   = $remote['body'];
		$params = null;
		parse_str( $body, $params );
		$params = json_decode(key($params));

        if( !isset( $params->access_token ) ){
            var_dump($params);
            wp_die( 'Something went wrong', 'hg_login' );
        }

		$access_token = $params->access_token;

		$me_info = wp_remote_get(
			add_query_arg(
				array(
					'fields'       => 'first_name,last_name,id,name,email',
					'access_token' => $access_token
				),
				'https://graph.facebook.com/me'
			)
		);

		if ( is_wp_error( $me_info ) ) {
			wp_die( $me_info->get_error_message() );
		}

		if ( ! isset( $me_info['body'] ) ) {
			wp_die( __( 'Facebook API returned wrong request, please try again', 'hg_login' ) );
		}

		$me_info = json_decode( $me_info['body'] );

		return $me_info;
	}

	return false;
}