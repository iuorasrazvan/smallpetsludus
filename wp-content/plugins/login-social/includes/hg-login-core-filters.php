<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


add_action( 'hg_login_register_new_user', 'hg_login_new_user_notifications', 10, 2 );

add_action( 'login_form_hg_login_activate_acc', 'hg_login_user_activation' );

add_action( 'login_form_hg_login_reset_password', 'hg_login_user_reset_password' );

//add_action( 'login_form_hg_login_retrieve_fb_access_token', 'hg_login_retrieve_fb_access_token' );

add_action( 'login_form_hg_login_fb_login', 'hg_login_fb_access_login' );


add_action( 'login_form_hg_login_fb_signup', 'hg_login_fb_signup' );

add_action( 'login_form_register', 'hg_login_maybe_redirect_from_loginscreen' );

add_action( 'login_form_login', 'hg_login_maybe_redirect_from_loginscreen' );

add_action( 'login_form_lostpassword', 'hg_login_maybe_redirect_from_loginscreen' );

add_action( 'login_form_logout', 'hg_login_logout_and_redirect' );
