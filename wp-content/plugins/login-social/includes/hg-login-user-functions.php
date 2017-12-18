<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function hg_login_wp_signon( $info, $secure_cookie ){

    $user = get_user_by( 'login', $info['user_login'] );

    /**
     * Check if user is activated,
     * in the case that BuddyPress is activated let the big brother handle user activation
     */
    $is_activated = get_user_option('hg_login_user_activated',$user->ID);


    /**
     * If there is no information about user activation that can mean that user was registered before this plugin was activated,
     * that's why we will suppose that user has already been avtivated
     */
    if( !$is_activated ){
        update_user_option( $user->ID, 'hg_login_activation_key', '', true );

        update_user_option( $user->ID, 'hg_login_user_activated', 'yes', true );

        $is_activated = true;
    }else{
        $is_activated = $is_activated == 'yes';
    }


    $is_bp_active_user = ( function_exists( 'buddypress' ) && bp_is_user_active( $user->ID ) );

    if( HG_Login()->settings->email_verify_required !== 'yes' || $is_bp_active_user ){

        $is_activated = true;

        update_user_option( $user->ID, 'hg_login_activation_key', '', true );

        update_user_option( $user->ID, 'hg_login_user_activated', 'yes', true );

    }

    if( $is_activated ){

        $user = wp_signon( $info, $secure_cookie );

        return $user;

    }else{

        return new WP_Error('user_not_activated', __('User is not activated, check your email for activation link','hg_login'));

    }
}

function hg_login_resetpassword_notification( $user_id ){
    global $wp_hasher;

    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $user = get_userdata( $user_id );

    // Generate something random for a password reset key.
    $key = wp_generate_password( 20, false );

    do_action( 'hg_login_retrieve_password_key', $user->user_login, $key );

    // Now insert the key, hashed, into the DB.
    if ( empty( $wp_hasher ) ) {
        require_once ABSPATH . WPINC . '/class-phpass.php';
        $wp_hasher = new PasswordHash( 8, true );
    }
    $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
    update_user_option( $user->ID, 'hg_login_password_reset_key', $hashed, true );

    $message = sprintf(__('Username: %s','hg_login'), $user->user_login) . "\r\n\r\n";
    $message .= __('To reset your password, visit the following address:','hg_login') . "\r\n\r\n";
    $message .= '<' . network_site_url("wp-login.php?action=hg_login_reset_password&hg_login_key=$key&hg_login_user=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";

    wp_mail($user->user_email, sprintf(__('[%s] Reset Password','hg_login'), $blogname), $message);
}

function hg_login_new_user_notifications($user_id, $type = ''){
    if( HG_Login()->settings->email_notify_admin === 'yes' ){
        hg_login_new_user_notification( $user_id, 'admin' );
    }

	if( $type === 'facebook' )
		return;

    hg_login_new_user_notification( $user_id, '' );
}

if ( !function_exists('hg_login_new_user_notification') ) :
	/**
	 * Email login credentials to a newly-registered user.
	 *
	 * A new user registration notification is also sent to admin email.
	 *
	 * @global wpdb         $wpdb      WordPress database object for queries.
	 * @global PasswordHash $wp_hasher Portable PHP password hashing framework instance.
	 *
	 * @param int    $user_id    User ID.
	 * @param string $notify     Optional. Type of notification that should happen. Accepts 'admin' or an empty
	 *                           string (admin only), or 'both' (admin and user). Default empty.
	 */

    /**
     * @param $user_id
     * @param string $notify
     * @return bool
     */
	function hg_login_new_user_notification( $user_id, $notify = '' ) {
		global $wp_hasher;
        $user = get_userdata( $user_id );
        // The blogname option is escaped with esc_html on the way into the database in sanitize_option
        // we want to reverse this for the plain text arena of emails.
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        if( 'admin' === $notify  ){
            $message  = sprintf(__('New user registration on your site %s:','hg_login'), $blogname) . "\r\n\r\n";
            $message .= sprintf(__('Username: %s','hg_login'), $user->user_login) . "\r\n\r\n";
            $message .= sprintf(__('Email: %s','hg_login'), $user->user_email) . "\r\n";

            @wp_mail(HG_Login()->settings->admin_email, sprintf(__('[%s] New User Registration','hg_login'), $blogname), $message);
        }

        if ( 'admin' === $notify ) {
            return true;
        }

		// Generate something random for a password reset key.
		$key = wp_generate_password( 20, false );

		do_action( 'hg_login_retrieve_password_key', $user->user_login, $key );

		// Now insert the key, hashed, into the DB.
		if ( empty( $wp_hasher ) ) {
			require_once ABSPATH . WPINC . '/class-phpass.php';
			$wp_hasher = new PasswordHash( 8, true );
		}
		$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
		update_user_option( $user->ID, 'hg_login_activation_key', $hashed, true );
		update_user_option( $user->ID, 'hg_login_user_activated', 'no', true );

		$message = sprintf(__('Username: %s','hg_login'), $user->user_login) . "\r\n\r\n";
        $subject = sprintf(__('[%s] Your account info','hg_login'), $blogname);

        /* Check if email verification is not required, then a new registered user is set as verified to avoid messes later on */
        if( HG_Login()->settings->email_verify_required !== 'yes' ){
            update_user_option( $user->ID, 'hg_login_activation_key', '', true );
            update_user_option( $user->ID, 'hg_login_user_activated', 'yes', true );
        }else{
            $message .= __('To activate your account, visit the following address:','hg_login') . "\r\n\r\n";
            $message .= '<' . network_site_url("wp-login.php?action=hg_login_activate_acc&hg_login_key=$key&hg_login_user=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";
            $subject = sprintf(__('[%s] Verify your account','hg_login'), $blogname);
        }

		if(wp_mail($user->user_email, $subject, $message)){
		    return true;
        }else{
            update_user_option( $user->ID, 'hg_login_activation_key', '', true );
            update_user_option( $user->ID, 'hg_login_user_activated', 'yes', true );
            return false;
        }
	}
endif;

function hg_login_user_reset_password(){
    $secure = ( 'https' === parse_url( wp_login_url(), PHP_URL_SCHEME ) );
    list( $rp_path ) = explode( '?', wp_unslash( $_SERVER['REQUEST_URI'] ) );

    $rp_cookie = 'hg-login-resetpass-' . COOKIEHASH;
    if ( isset( $_GET['hg_login_key'] ) ) {
        $value = sprintf( '%s:%s', wp_unslash( $_GET['hg_login_user'] ), wp_unslash( $_GET['hg_login_key'] ) );
        setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
        wp_safe_redirect( remove_query_arg( array( 'hg_login_key', 'hg_login_user' ) ) );
        exit;
    }

    if ( isset( $_COOKIE[ $rp_cookie ] ) && 0 < strpos( $_COOKIE[ $rp_cookie ], ':' ) ) {
        list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ $rp_cookie ] ), 2 );
        $user = hg_login_check_password_reset_key( $rp_key, $rp_login,'hg_login_password_reset_key' );
        if ( isset( $_POST['pass1'] ) && ! hash_equals( $rp_key, $_POST['rp_key'] ) ) {
            $user = false;
        }
    } else {
        $user = false;
    }

    if ( ! $user || is_wp_error( $user ) ) {
        setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, $secure );
        if ( $user && $user->get_error_code() === 'expired_key' ){
            setcookie( 'hg_login_action', 'expiredkey', 0, SITECOOKIEPATH, COOKIE_DOMAIN, $secure );
            wp_redirect( site_url() );
        }else{
            setcookie( 'hg_login_action', 'invalidkey', 0, SITECOOKIEPATH, COOKIE_DOMAIN, $secure );
            wp_redirect( site_url() );
        }
        exit;
    }

    update_user_option( $user->ID, 'hg_login_activation_key', '', true );
    update_user_option( $user->ID, 'hg_login_user_activated', 'yes', true );
    update_user_option( $user->ID, 'hg_login_password_reset_key', '', true );
    setcookie( 'hg_login_action', 'do_reset_pass', 0, SITECOOKIEPATH, COOKIE_DOMAIN, $secure );
    setcookie( 'hg_login_user', $rp_login, 0, SITECOOKIEPATH, COOKIE_DOMAIN, $secure );
    wp_redirect( site_url() );
    exit;
}

if ( !function_exists( 'hg_login_user_activation' ) ):
	function hg_login_user_activation(){
        $secure = ( 'https' === parse_url( wp_login_url(), PHP_URL_SCHEME ) );
		list( $rp_path ) = explode( '?', wp_unslash( $_SERVER['REQUEST_URI'] ) );

		$rp_cookie = 'hg-login-activate-acc-' . COOKIEHASH;
		if ( isset( $_GET['hg_login_key'] ) ) {
			$value = sprintf( '%s:%s', wp_unslash( $_GET['hg_login_user'] ), wp_unslash( $_GET['hg_login_key'] ) );
			setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
			wp_safe_redirect( remove_query_arg( array( 'hg_login_key', 'hg_login_user' ) ) );
			exit;
		}

		if ( isset( $_COOKIE[ $rp_cookie ] ) && 0 < strpos( $_COOKIE[ $rp_cookie ], ':' ) ) {
			list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ $rp_cookie ] ), 2 );
			$user = hg_login_check_password_reset_key( $rp_key, $rp_login );
			if ( isset( $_POST['pass1'] ) && ! hash_equals( $rp_key, $_POST['rp_key'] ) ) {
				$user = false;
			}
		} else {
			$user = false;
		}

		if ( ! $user || is_wp_error( $user ) ) {
			setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, $secure );
			if ( $user && $user->get_error_code() === 'expired_key' ){
                setcookie( 'hg_login_action', 'expiredkey', 0, SITECOOKIEPATH, COOKIE_DOMAIN, $secure );
                wp_redirect( site_url() );
            }else{
                setcookie( 'hg_login_action', 'invalidkey', 0, SITECOOKIEPATH, COOKIE_DOMAIN, $secure );
                wp_redirect( site_url() );
            }
			exit;
		}

		update_user_option( $user->ID, 'hg_login_activation_key', '', true );
		update_user_option( $user->ID, 'hg_login_user_activated', 'yes', true );
        setcookie( 'hg_login_action', 'user_activated', 0, SITECOOKIEPATH, COOKIE_DOMAIN, $secure );
        wp_redirect( site_url() );
        exit;
	}
endif;


/**
 * Retrieves a user row based on password reset key and login
 *
 * A key is considered 'expired' if it exactly matches the value of the
 * user_activation_key field, rather than being matched after going through the
 * hashing process. This field is now hashed; old values are no longer accepted
 * but have a different WP_Error code so good user feedback can be provided.
 *
 * @global wpdb         $wpdb      WordPress database object for queries.
 * @global PasswordHash $wp_hasher Portable PHP password hashing framework instance.
 *
 * @param string $key       Hash to validate sending user's password.
 * @param string $login     The user login.
 * @return WP_User|WP_Error WP_User object on success, WP_Error object for invalid or expired keys.
 */
function hg_login_check_password_reset_key($key, $login, $db_option_name = 'hg_login_activation_key') {
	global $wpdb, $wp_hasher;

	$key = preg_replace('/[^a-z0-9]/i', '', $key);

	if ( empty( $key ) || !is_string( $key ) )
		return new WP_Error('invalid_key', __('Invalid key'));

	if ( empty($login) || !is_string($login) )
		return new WP_Error('invalid_key', __('Invalid key'));

	$user = get_user_by( 'login', $login );

	if ( ! $user )
		return new WP_Error('invalid_key', __('Invalid key'));

	$user_key=  get_user_option($db_option_name,$user->ID);

	if ( empty( $wp_hasher ) ) {
		require_once ABSPATH . WPINC . '/class-phpass.php';
		$wp_hasher = new PasswordHash( 8, true );
	}

	/**
	 * Filter the expiration time of password reset keys.
	 *
	 * @param int $expiration The expiration time in seconds.
	 */
	$expiration_duration = apply_filters( 'password_reset_expiration', DAY_IN_SECONDS );

	if ( false !== strpos( $user_key, ':' ) ) {
		list( $pass_request_time, $pass_key ) = explode( ':', $user_key, 2 );
		$expiration_time = $pass_request_time + $expiration_duration;
	} else {
		$pass_key = $user_key;
		$expiration_time = false;
	}

	if ( ! $pass_key ) {
		return new WP_Error( 'invalid_key', __( 'Invalid key' ) );
	}

	$hash_is_correct = $wp_hasher->CheckPassword( $key, $pass_key );

	if ( $hash_is_correct && $expiration_time && time() < $expiration_time ) {
		return get_userdata( $user->ID );
	} elseif ( $hash_is_correct && $expiration_time ) {
		// Key has an expiration time that's passed
		return new WP_Error( 'expired_key', __( 'Invalid key' ) );
	}

	if ( hash_equals( $user_key, $key ) || ( $hash_is_correct && ! $expiration_time ) ) {
		$return = new WP_Error( 'expired_key', __( 'Invalid key' ) );
		$user_id = $user->ID;

		/**
		 * Filter the return value of check_password_reset_key() when an
		 * old-style key is used.
		 *
		 * @param WP_Error $return  A WP_Error object denoting an expired key.
		 *                          Return a WP_User object to validate the key.
		 * @param int      $user_id The matched user ID.
		 */
		return apply_filters( 'password_reset_key_expired', $return, $user_id );
	}

	return new WP_Error( 'invalid_key', __( 'Invalid key' ) );
}