<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class HG_Login_Button_Shortcode {

    public function __construct(){

    }

    public static function do_shortcode($atts){

        if(isset($GLOBALS['hg_login_my_account_printed']) && $GLOBALS['hg_login_my_account_printed']){
            return false;
        }

        $atts = shortcode_atts( array(
            'text' => HG_Login()->settings->login_button_text,
            'show_menu' => 'no'
        ), $atts );

        do_action("hg_login_frontend_scripts");

        ob_start();

        if( !is_user_logged_in() ){

            hg_login_login_button($atts);

        }elseif( $atts['show_menu'] === 'yes' ){

            echo hg_login_get_account_menu();
        }

        return ob_get_clean();
    }
}

class HG_Signup_Button_Shortcode {

    public function __construct($atts){

    }

    public static function do_shortcode( $atts ){

        $atts = shortcode_atts( array(
            'text' => HG_Login()->settings->signup_button_text,
            'show_menu' => 'no'
        ), $atts );

        do_action("hg_login_frontend_scripts");

        ob_start();
        if( !is_user_logged_in() ){

            hg_login_signup_button($atts);

        }elseif( $atts['show_menu'] === 'yes' ){

            echo hg_login_get_account_menu();
        }

        return ob_get_clean();

    }
}