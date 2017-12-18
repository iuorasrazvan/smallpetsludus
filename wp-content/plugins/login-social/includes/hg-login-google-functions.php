<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
add_action('wp_ajax_hg_g_signin_ajax','hg_g_signin_ajax');
add_action('wp_ajax_nopriv_hg_g_signin_ajax','hg_g_signin_ajax');

function hg_g_signin_ajax() {

    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'],$_REQUEST['nonceName']  ) ) {
        die( __( 'Security check failed', 'hg_login' ) );
    }
    $gInfo = $_REQUEST['gData'];
    $secure  = ( 'https' === parse_url( wp_login_url(), PHP_URL_SCHEME ) );
    $gLogin  = substr($gInfo['email'],0,strpos($gInfo['email'],"@"));
    $user    = get_user_by( 'login', $gLogin );
    $redirect_to = esc_url( HG_Login()->settings->redirect_to_after_login );
    if ( $redirect_to == '' ) {
        if ( isset( $_GET['rdr'] ) ) {
            $redirect_to = $_GET['rdr'];
        } else {
            $redirect_to = site_url();
        }
    }

    if ( ! $user && isset( $gInfo['email'] ) ) {
        $user = get_user_by( 'email', $gInfo['email'] );
    }

    if ( ! $user ) {
        $user = hg_login_register_google_account( $gInfo, $gLogin );

    }

    wp_set_auth_cookie( $user->ID, true );

    if(!ctype_alpha($gInfo['name'] ) || !ctype_alpha($gInfo['name'])){
        setcookie( 'hg_login_action', 'latin_letters');
        $gResponse = array(
            "redirect"=>$redirect_to,
            "status"  =>400,
            "message"=> "Bad Request"
        );
        echo json_encode($gResponse);
        wp_die();
    }

    if ( ! is_wp_error( $user ) ) {
        setcookie( 'hg_login_action', 'need_refresh');

          $gResponse = array(
          "redirect"=>$redirect_to,
          "status"  =>200,
          "message"=> "OK"
        );
          echo json_encode($gResponse);
        wp_die();
    } else {

        setcookie( 'hg_login_action', 'login_failed');
        $gResponse = array(
            "redirect"=>$redirect_to,
            "status"  =>400,
            "message"=> "Bad Request"
        );
        echo json_encode($gResponse);
        wp_die();
    }

}

function hg_login_register_google_account($gInfo,$gLogin){
    $user_pass = wp_generate_password( 12, false );

    $login = $gLogin;

    $errors = new WP_Error();

    if ( ! isset( $gInfo['email'] ) ) {
        $errors->add( 'no_email', __( 'You have not provided a public email, in order to register you need to have an email address, Alternatively you can register with Signup form', 'hg_login' ) );
        return $errors;
    }

    if ( username_exists( $login ) ) {
        $errors->add( 'user_exists', __( 'Username Already exists', 'hg_login' ) );
        return $errors;
    }

    if ( email_exists( $gInfo['email'] ) ) {
        $errors->add( 'user_exists', __( 'Your email address is already registered', 'hg_login' ) );
        return $errors;
    }

    $data = array(
        'user_pass'    => $user_pass,
        'user_login'   => $login,
        'user_email'   => $gInfo['email'],
        'display_name' => $gInfo['displayName'],
        'first_name'   => $gInfo['name'],
        'last_name'    => $gInfo['lastName'],
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
    do_action( 'hg_login_register_new_user', $new_user, 'google' );

    update_user_option( $new_user, 'hg_login_activation_key', '', true );
    update_user_option( $new_user, 'hg_login_user_activated', 'yes', true );

    $user          = get_user_by( 'ID', $new_user );

    return $user;
}


