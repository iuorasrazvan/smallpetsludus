<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class HG_Login_Frontend_Scripts {

    public function __construct(){
        add_action('wp_footer', array($this,'settings_styles'));
        add_filter( 'hg_login_user_options_style' , array( $this, 'write_custom_css' ) );
        add_action('wp_enqueue_scripts',array($this,'common_scripts'));
        add_action('wp_enqueue_scripts',array($this,'common_styles'));
    }

    public function common_styles(){
        wp_register_style( 'hg_login_common_css', HG_Login()->plugin_url().'/assets/css/common.css' );
        if( HG_Login()->settings->disable_default_css !== 'yes' ){
            wp_enqueue_style( 'hg_login_common_css' );
        }

        wp_add_inline_style( 'hg_login_common_css', apply_filters( 'hg_login_user_options_style' , '' ) );

    }

    public function common_scripts(){
        if( !wp_script_is('jquery') ){
            wp_enqueue_script('jquery');
        }

        wp_enqueue_script( 'hg_login_main_js', HG_Login()->plugin_url().'/assets/js/main.js',array(),false,true );
        wp_localize_script( 'hg_login_main_js', 'hgLoginMainL10n', array(
            'ajax_admin' => admin_url('admin-ajax.php'),
            'userActivated' => __("Your Account has been successfully activated","hg_login"),
            'expiredKey' =>__( "Your account activation link has expired. Please request a new link.", "hg_login" ),
            'invalidKey' =>__( "Your account activation link appears to be invalid. Please request a new link.", "hg_login" ),
            'logoutNonce' => wp_create_nonce('hg_login_logout'),
            'activationLinkSent' => __('Your account activation link has been sent to your email, please check your inbox','hg_login'),
            'fbNoEmail' => __( 'Registration by Facebook was not successful as we could not retrieve your email which is require for registration. We suggest you to register via Signup form.', 'hg_login' ),
            'userExists' => __( 'This username is already registered', 'hg_login' ),
            'emailExists' => __( 'This email is already registered', 'hg_login' ),
            'loginFailed' => __( 'Failed to login', 'hg_login' ),
            'latinLetters' => __( 'Only latin letters allowed', 'hg_login' ),
            'userNotExists' => __( 'User does not exist', 'hg_login' )
        ) );

        wp_enqueue_script( 'hg_popup_login_js', HG_Login()->plugin_url().'/assets/js/popup-login.js',array(),false,true );
        wp_localize_script('hg_popup_login_js','hgLoginPopupL10n',array(
            'ajax_admin' => admin_url('admin-ajax.php'),
            'loginError' => __('This field is required','hg_login'),
            'nonce' => wp_create_nonce('hg_login'),
            'requiredField' => __( "required field", "hg_login" ),
            'loadPopupNonce' => wp_create_nonce( 'load_popup_nonce' ),
            'recaptchaErrorMsg' => __( 'Please verify the recaptcha', 'hg_login' ),
            'hg_gPlusUserIN'  => __('You do not have a G+ account'),
            'googleClientID' => HG_Login()->settings->google_sign_in_id,
        ));

        wp_enqueue_script( 'hg_login_popup_signup_js', HG_Login()->plugin_url().'/assets/js/popup-signup.js',array(),false,true );
        wp_localize_script('hg_login_popup_signup_js','hgSignupPopupL10n',array(
            'ajax_admin' => admin_url('admin-ajax.php'),
            'loginError' => __('This field is required','hg_login'),
            'nonce' => wp_create_nonce('hg_signup'),
            'invalidEmail' => __("Invalid Email Format","hg_login"),
            'emailInUse' => __("This Email is already registered","hg_login"),
            'usernameInUse' => __("Username already in use","hg_login"),
            'requiredField' => __( "required field", "hg_login" ),
            'passTooWeak' => __('Password is too weak','hg_login'),
            'recaptchaErrorMsg' => __('Please verify the recaptcha','hg_login'),
            'passwordMinLength' => HG_Login()->settings->password_min_length,
            'passwordMaxLength' => HG_Login()->settings->password_max_length,
            'minSymbols' => sprintf( __( 'Min %s symbols', 'hg_login' ), HG_Login()->settings->password_min_length ),
            'maxSymbols' => sprintf( __( 'Max %s symbols', 'hg_login' ), HG_Login()->settings->password_max_length ),
            'onlyLatinAndNumbers' => __('Only latin letters and numbers are allowed!','hg_login'),
            'noWhiteSpaces' => __('Whitespaces are not allowed!','hg_login'),
            'hg_gPlusUserUP'  => __('You do not have a G+ account'),
            'googleClientID' => HG_Login()->settings->google_sign_in_id,
        ));

        wp_enqueue_script( 'hg_login_popup_forgotpass_js', HG_Login()->plugin_url().'/assets/js/popup-forgot-password.js',array(),false,true );
        wp_localize_script('hg_login_popup_forgotpass_js','hgForgotPassPopupL10n',array(
            'ajax_admin' => admin_url('admin-ajax.php'),
            'fillLogin' => __( "Please fill in the username field", "hg_login" ),
            'nonce' => wp_create_nonce('hg_login_forgotpass'),
        ));

        wp_enqueue_script( 'hg_login_popup_resetpass_js', HG_Login()->plugin_url().'/assets/js/popup-reset-password.js',array(),false,true );
        wp_localize_script('hg_login_popup_resetpass_js','hgResetPassPopupL10n',array(
            'ajax_admin' => admin_url('admin-ajax.php'),
            'passwordsDoNotMatch' => __( "Passwords do not match", "hg_login" ),
            'nonce' => wp_create_nonce('hg_login_resetpass'),
            'passTooWeak' => __('Password is too weak','hg_login'),
            'passwordMinLength' => HG_Login()->settings->password_min_length,
            'passwordMaxLength' => HG_Login()->settings->password_max_length,
            'minSymbols' => sprintf( __( 'Min %s symbols', 'hg_login' ), HG_Login()->settings->password_min_length ),
            'maxSymbols' => sprintf( __( 'Max %s symbols', 'hg_login' ), HG_Login()->settings->password_max_length ),
            'onlyLatinAndNumbers' => __('Only latin letters and numbers are allowed!','hg_login'),
            'noWhiteSpaces' => __('Whitespaces are not allowed!','hg_login')
        ));

        wp_enqueue_script( 'zxcvbn-async',array( 'jquery' ),false,true );

        wp_enqueue_script( 'password-strength-meter',array( 'jquery','zxcvbn-async' ),false,true );

        if(HG_Login()->settings->recaptcha_enabled === 'yes'){
            wp_enqueue_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js?render=explicit', array(), false, true );
        }
        if(HG_Login()->settings->google_sign_in_enabled === 'yes'){
            wp_enqueue_script('google-client-api', 'https://apis.google.com/js/api:client.js');
        }

    }

    public function settings_styles(){
        if( HG_Login()->settings->disable_default_css !== 'yes' ){
            include( HG_LOGIN_TEMPLATES_PATH.'styles/settings-styles.php' );
        }

    }

    public function write_custom_css($css){
        $_css               = isset($_css) ? $_css : '';
        $custom_css      = esc_html( HG_Login()->settings->custom_css );
        if ( ! isset($custom_css) || empty($custom_css) )
            return $_css;
        return apply_filters( 'hg_login_write_custom_css',
            $_css . "\n" . html_entity_decode( $custom_css ),
            $_css,
            HG_Login()->settings->custom_css
        );
    }

}

new HG_Login_Frontend_Scripts();