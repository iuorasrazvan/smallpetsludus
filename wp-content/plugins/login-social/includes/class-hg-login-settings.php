<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class HG_Login_Settings
 */
class HG_Login_Settings extends WPDEV_Settings_API {

	public $plugin_id = 'hg_login';

	/**
	 * HG_Login_Settings constructor.
	 */
	public function __construct() {
		$config = array(
			'menu_slug'   => 'hg_login_settings',
			'page_title'  => __( 'Settings', 'hg_login' ),
			'title'       => __( 'Login WP Settings', 'hg_login' ),
			'menu_title'  => __( 'Settings', 'hg_login' ),
			'parent_slug' => 'hg_login'
		);

		$this->init();
		$this->init_panels();
		$this->init_sections();
		$this->init_controls();

		parent::__construct( $config );


		$this->add_js(
			'hg_login_admin',
			HG_Login()->plugin_url() . '/assets/js/admin.js',
			array( 'jquery', 'jquery-ui-sortable' ),
			array(
				'key'  => 'hgLoginAdminL10n',
				'data' => array(
					'ajax_admin' => admin_url( 'admin-ajax.php' ),
					'nonce'      => wp_create_nonce( 'hg_login_acc_item' ),
				)
			)
		);

		$this->add_css( 'hg_login_admin', HG_Login()->plugin_url() . '/assets/css/admin.css' );

		$this->add_css( 'fontawesome', HG_Login()->plugin_url() . '/vendor/font-awesome-4.6.3/css/font-awesome.min.css' );
	}

	/**
	 * Initialize user defined variables
	 */
	public function init() {
		$this->login_button_text                = $this->get_option( "login_button_text", __( "Sign in", 'hg_login' ) );
		$this->signup_button_text               = $this->get_option( "signup_button_text", __( 'Sign Up' ) );
		$this->login_popup_title                = $this->get_option( "login_popup_title", __( "Login to your account", 'hg_login' ) );
		$this->login_popup_subtitle             = $this->get_option( "login_popup_subtitle", '' );
		$this->signup_popup_title               = $this->get_option( "signup_popup_title", __( "Join Us", 'hg_login' ) );
		$this->signup_popup_subtitle            = $this->get_option( "signup_popup_subtitle", '' );
		$this->forgotpass_popup_title           = $this->get_option( "forgotpass_popup_title", __( "Forgot Password", 'hg_login' ) );
		$this->resetpass_popup_title            = $this->get_option( "resetpass_popup_title", __( "Reset Password", 'hg_login' ) );
		$this->redirect_to_after_login          = $this->get_option( "redirect_to_after_login", '' );
		$this->redirect_to_after_signup         = $this->get_option( "redirect_to_after_signup", '' );
		$this->redirect_to_after_logout         = $this->get_option( "redirect_to_after_logout", '' );
		$this->terms_check_text                 = $this->get_option( "terms_check_text", __( 'I agree to your terms of use and privacy policy', 'hg_login' ) );
		$this->terms_check_enabled              = $this->get_option( "terms_check_enabled", 'yes' );
		$this->newsletter_check_enabled         = $this->get_option( "newsletter_check_enabled", 'no' ) == 'yes';
		$this->newsletter_check_text            = $this->get_option( "newsletter_check_text", __( 'Send me your Newsletter', 'hg_login' ) );
		$this->email_verify_required            = $this->get_option( "email_verify_required", 'no' );
		$this->email_verify_call_to_action_text = $this->get_option( "email_verify_call_to_action_text", __( 'Verification link has been sent to your email', 'hg_login' ) );
		$this->admin_email                      = $this->get_option( "admin_email", get_option( "admin_email" ) );
		$this->email_notify_admin               = $this->get_option( "email_notify_admin", 'yes' );
		$this->accept_weak_password             = $this->get_option( "accept_weak_password", "no" );
        $this->password_min_length              = $this->get_option( "password_min_length", "7" );
        $this->password_max_length              = $this->get_option( "password_max_length", "25" );
		$this->redirect_from_loginscreen        = $this->get_option( "redirect_from_loginscreen", "no" );

		$this->disable_default_css              = $this->get_option( 'disable_default_css', 'no' );
		$this->custom_css                       = $this->get_option( "custom_css", '' );

        $this->popup_border_radius              = $this->get_option( "popup_border_radius", "2" );
        $this->popup_border_width               = $this->get_option( "popup_border_width", "0" );
        $this->popup_border_style               = $this->get_option( "popup_border_style", "solid" );
        $this->popup_border_color               = $this->get_option( "popup_border_color", "solid" );
        $this->popup_border_opacity             = $this->get_option( "popup_border_opacity", "1" );
		$this->popup_bg_color                   = $this->get_option( "popup_bg_color", 'FFFFFF' );
        $this->popup_body_text_style            = $this->get_option("popup_body_text_style", 'Baloo, cursive');
		$this->popup_footer_bg_color            = $this->get_option( "popup_footer_bg_color", 'FFFFFF' );
		$this->popup_footer_text_color          = $this->get_option( "popup_footer_text_color", '333333' );
        $this->popup_footer_text_style          = $this->get_option("popup_footer_text_style", 'Baloo, cursive' );
		$this->popup_header_bg_color            = $this->get_option( "popup_header_bg_color", '3F51B5' );
		$this->popup_header_text_color          = $this->get_option( "popup_header_text_color", 'FFFFFF' );
		$this->popup_close_btn_color            = $this->get_option( "popup_close_btn_color", 'FFFFFF' );
		$this->popup_fb_btn_bg_color            = $this->get_option( "popup_fb_btn_bg_color", '3b5998' );
		$this->popup_fb_btn_text_color          = $this->get_option( "popup_fb_btn_text_color", 'FFFFFF' );
		$this->popup_input_text_color           = $this->get_option( "popup_input_text_color", '999999' );
        $this->popup_header_text_style          = $this->get_option("popup_header_text_style", 'Baloo, cursive');
		$this->popup_input_bg_color             = $this->get_option( "popup_input_bg_color", 'FFFFFF' );
		$this->popup_input_label_color          = $this->get_option( "popup_input_label_color", '999999' );
		$this->popup_input_focused_text_color   = $this->get_option( "popup_input_focused_text_color", '999999' );
		$this->popup_input_focused_bg_color     = $this->get_option( "popup_input_focused_bg_color", 'FFFFFF' );
        $this->popup_input_focused_border_color = $this->get_option("popup_input_focused_border_color", '3F51B5' );
		$this->popup_input_focused_label_color  = $this->get_option( "popup_input_focused_label_color", '444444' );
		$this->popup_input_error_color          = $this->get_option( "popup_input_error_color", 'E53935' );
		$this->popup_primary_btn_color          = $this->get_option( "popup_primary_btn_color", 'FFFFFF' );
		$this->popup_primary_btn_bg_color       = $this->get_option( "popup_primary_btn_bg_color", '1bbc45' );
		$this->popup_primary_btn_hover_color    = $this->get_option( "popup_primary_btn_hover_color", 'FFFFFF' );
		$this->popup_primary_btn_hover_bg_color = $this->get_option( "popup_primary_btn_hover_bg_color", '1bbc45' );
		$this->popup_secondary_btn_color        = $this->get_option( "popup_secondary_btn_color", '666666' );
		$this->popup_secondary_btn_hover_color  = $this->get_option( "popup_secondary_btn_hover_color", '808080' );

		$this->login_btn_bg_color               = $this->get_option( "login_btn_bg_color", '106cc8' );
		$this->login_btn_hover_bg_color         = $this->get_option( "login_btn_hover_bg_color", '0159a2' );
		$this->login_btn_text_color             = $this->get_option( "login_btn_text_color", 'ffffff' );
        	$this->login_btn_font_style             = $this->get_option("login_btn_font_style", 'Baloo, cursive' );
		$this->login_btn_hover_text_color       = $this->get_option( "login_btn_hover_text_color", 'ffffff' );
		$this->login_btn_text_size              = $this->get_option( "login_btn_text_size", '14' );

		$this->signup_btn_bg_color               = $this->get_option( "signup_btn_bg_color", '106cc8' );
		$this->signup_btn_hover_bg_color         = $this->get_option( "signup_btn_hover_bg_color", '0159a2' );
		$this->signup_btn_text_color             = $this->get_option( "signup_btn_text_color", 'ffffff' );
		$this->signup_btn_hover_text_color       = $this->get_option( "signup_btn_hover_text_color", 'ffffff' );
		$this->signup_btn_text_size              = $this->get_option( "signup_btn_text_size", '14' );
        	$this->signup_btn_font_style             = $this->get_option("signup_btn_font_style", 'Baloo, cursive' );

		$this->facebook_app_id     = $this->get_option( "facebook_app_id", "" );
		$this->facebook_app_secret = $this->get_option( "facebook_app_secret", "" );
		$this->facebook_enabled    = $this->get_option( "facebook_enabled", "no" );
        	$this->google_sign_in_id = $this->get_option( "google_sign_in_id", "" );
        	$this->google_sign_in_enabled = $this->get_option( "google_sign_in_enabled", "no" );

		$default_menu                = hg_login_get_default_acc_dropdown_menu();
		$this->account_dropdown_menu = $this->get_option( "account_dropdown_menu", $default_menu );


		$this->recaptcha_enabled    = $this->get_option( "recaptcha_enabled", 'no' );
		$this->recaptcha_key        = $this->get_option( "recaptcha_key", '' );
		$this->recaptcha_secret_key = $this->get_option( "recaptcha_secret_key", '' );
		$this->recaptcha_theme      = $this->get_option( "recaptcha_theme", 'light' );
	}

	public function init_panels() {
		$this->panels = array(
			'general'  => array(
				'title' => __( 'General', 'hg_login' ),
			),
			'social'   => array(
				'title' => __( 'Social', 'hg_login' ),
			),
			'design'   => array(
				'title' => __( 'Design', 'hg_login' ),
			),
			'security' => array(
				'title' => __( 'Security', 'hg_login' )
			)
		);
	}

	public function init_sections() {
		$this->sections = array(
			'global'             => array(
				'panel' => 'general',
				'title' => __( 'Global', 'hg_login' ),
			),
			'signup_options'     => array(
				'panel' => 'general',
				'title' => __( 'Signup', 'hg_login' ),
			),
			'login_options'      => array(
				'panel' => 'general',
				'title' => __( 'Login', 'hg_login' ),
			),
			'forgotpass_popup'   => array(
				'panel' => 'general',
				'title' => __( 'Forgot Password Popup', 'hg_login' ),
			),
			'resetpass_popup'    => array(
				'panel' => 'general',
				'title' => __( 'Reset Password Popup', 'hg_login' ),
			),
			'account_options'    => array(
				'panel'                => 'general',
				'title'                => __( 'Account Options', 'hg_login' ),
				'disabled'             => true,
				'disabled_description' => __( 'This section is available only for pro users.</br>Please, upgrade your profile.', 'hg_login' ),
				'disabled_button_text' => __( 'Go to Pro', 'hg_login' ),
				'disabled_link'        => 'http://huge-it.com/wordpress-login',
				'disabled_bg_color'    => '#b21919',
				'disabled_color'       => '#fff',
			),
			'custom_css_options' => array(
				'panel' => 'design',
				'title' => __( 'Styling', 'hg_login' ),
			),
            'login_btn_styles'   => array(
                'panel'                => 'design',
                'title'                => __( 'Login Button Styles', 'hg_login' ),
                'disabled'             => true,
                'disabled_description' => __( 'This section is available only for pro users.</br>Please, upgrade your profile.', 'hg_login' ),
                'disabled_button_text' => __( 'Go to Pro', 'hg_login' ),
                'disabled_link'        => 'http://huge-it.com/wordpress-login',
                'disabled_bg_color'    => '#b21919',
                'disabled_color'       => '#fff',
            ),
            'signup_btn_styles'  => array(
                'panel'                => 'design',
                'title'                => __( 'Signup Button Styles', 'hg_login' ),
                'disabled'             => true,
                'disabled_description' => __( 'This section is available only for pro users.</br>Please, upgrade your profile.', 'hg_login' ),
                'disabled_button_text' => __( 'Go to Pro', 'hg_login' ),
                'disabled_link'        => 'http://huge-it.com/wordpress-login',
                'disabled_bg_color'    => '#b21919',
                'disabled_color'       => '#fff',
            ),
            'popup_border_styles' => array(
                'panel' => 'design',
                'title' => __( 'Popup Border Styles', 'hg_login' ),
                'disabled'             => true,
                'disabled_description' => __( 'This section is available only for pro users.</br>Please, upgrade your profile.', 'hg_login' ),
                'disabled_button_text' => __( 'Go to Pro', 'hg_login' ),
                'disabled_link'        => 'http://huge-it.com/wordpress-login',
                'disabled_bg_color'    => '#b21919',
                'disabled_color'       => '#fff',
            ),
            'popup_container_styles' => array(
                'panel' => 'design',
                'title' => __( 'Popup Container Styles', 'hg_login' ),
                'disabled'             => true,
                'disabled_description' => __( 'This section is available only for pro users.</br>Please, upgrade your profile.', 'hg_login' ),
                'disabled_button_text' => __( 'Go to Pro', 'hg_login' ),
                'disabled_link'        => 'http://huge-it.com/wordpress-login',
                'disabled_bg_color'    => '#b21919',
                'disabled_color'       => '#fff',
            ),
            'popup_text_styles' => array(
                'panel' => 'design',
                'title' => __( 'Popup Text Styles', 'hg_login' ),
                'disabled'             => true,
                'disabled_description' => __( 'This section is available only for pro users.</br>Please, upgrade your profile.', 'hg_login' ),
                'disabled_button_text' => __( 'Go to Pro', 'hg_login' ),
                'disabled_link'        => 'http://huge-it.com/wordpress-login',
                'disabled_bg_color'    => '#b21919',
                'disabled_color'       => '#fff',
            ),
            'popup_button_styles' => array(
                'panel' => 'design',
                'title' => __('Popup Button Styles','hg_login'),
                'disabled'             => true,
                'disabled_description' => __( 'This section is available only for pro users.</br>Please, upgrade your profile.', 'hg_login' ),
                'disabled_button_text' => __( 'Go to Pro', 'hg_login' ),
                'disabled_link'        => 'http://huge-it.com/wordpress-login',
                'disabled_bg_color'    => '#b21919',
                'disabled_color'       => '#fff',
            ),
			'facebook_options'   => array(
				'panel' => 'social',
				'title' => __( 'Facebook', 'hg_login' )
			),
	            	'google_options' => array(
	                'panel' => 'social',
	                'title' => __( 'Google', 'hg_login' )
	            	),
			'recaptcha_options'  => array(
				'panel'                => 'security',
				'title'                => __( 'reCaptcha', 'hg_login' ),
				'disabled'             => true,
				'disabled_description' => __( 'This section is available only for pro users.</br>Please, upgrade your profile.', 'hg_login' ),
				'disabled_button_text' => __( 'Go to Pro', 'hg_login' ),
				'disabled_link'        => 'http://huge-it.com/wordpress-login',
				'disabled_bg_color'    => '#b21919',
				'disabled_color'       => '#fff',
			),
		);
	}

	private function get_global_controls() {
		return array(
			'redirect_from_loginscreen'        => array(
				'section' => 'global',
				'type'    => 'checkbox',
				'default' => $this->redirect_from_loginscreen,
				'label'   => __( 'Redirect From Login Page', 'hg_login' ),
				'help'    => __( 'When someone enters wp-login.php login screen redirect them to the homepage and open our login screen', 'hg_login' )
			),
			'email_notify_admin'               => array(
				'section' => 'global',
				'type'    => 'checkbox',
				'default' => $this->email_notify_admin,
				'label'   => __( 'Notify administrator', 'hg_login' )
			),
			'admin_email'                      => array(
				'section' => 'global',
				'type'    => 'text',
				'default' => $this->admin_email,
				'label'   => __( 'Admin Email', 'hg_login' )
			),
			'email_verify_required'            => array(
				'section' => 'global',
				'type'    => 'checkbox',
				'default' => $this->email_verify_required,
				'label'   => __( 'Email verification is required', 'hg_login' )
			),
			'email_verify_call_to_action_text' => array(
				'section' => 'global',
				'type'    => 'text',
				'default' => $this->email_verify_call_to_action_text,
				'label'   => __( 'Email Verification text', 'hg_login' )
			),
			'redirect_to_after_signup'         => array(
				'section'     => 'global',
				'type'        => 'text',
				'default'     => $this->redirect_to_after_signup,
				'label'       => __( 'Redirect to after sign up', 'hg_login' ),
				'placeholder' => __( 'http://...', 'hg_login' ),
				'help'        => __( 'Sepcify the url a user will be redirected to after signing up in your website. Leave blank to stay in the same page', 'hg_login' )
			),
			'redirect_to_after_login'          => array(
				'section'     => 'global',
				'type'        => 'text',
				'default'     => $this->redirect_to_after_login,
				'label'       => __( 'Redirect to after login', 'hg_login' ),
				'placeholder' => __( 'http://...', 'hg_login' ),
				'help'        => __( 'Sepcify the url a user will be redirected to after logging in. Leave blank to stay in the same page', 'hg_login' )
			),
			'redirect_to_after_logout'         => array(
				'section'     => 'global',
				'type'        => 'text',
				'default'     => $this->redirect_to_after_logout,
				'label'       => __( 'Redirect to after logout', 'hg_login' ),
				'placeholder' => __( 'http://...', 'hg_login' ),
				'help'        => __( 'Sepcify the url a user will be redirected to after logging out. Leave blank to stay in the same page', 'hg_login' )
			),
			'terms_check_enabled'              => array(
				'section' => 'global',
				'type'    => 'checkbox',
				'default' => $this->terms_check_enabled,
				'label'   => __( 'Enable terms checkbox', 'hg_login' )
			),
			'terms_check_text'                 => array(
				'section' => 'global',
				'type'    => 'text',
				'default' => $this->terms_check_text,
				'label'   => __( 'Terms checkbox label', 'hg_login' )
			)
		);
	}
    public static function get_fonts_choices() {
        return array(
            'Baloo, cursive' => 'Baloo',
            'Open Sans, sans-serif' => 'Open Sans',
            'Lato, sans-serif' => 'Lato',
            'Indie Flower, cursive' => 'Indie Flower',
            'Lobster, cursive' => 'Lobster',
            'Abril Fatface, cursive' => 'Abril Fatface',
            'Arvo, serif' => 'Arvo',
            'Droid Sans Mono, monospace' => 'Droid Sans Mono',
            'Josefin Slab, serif' => 'Josefin Slab',
            'Old Standard TT, serif' => 'Old Standard TT',
            'PT Sans, sans-serif' => 'PT Sans',
            'Ubuntu, sans-serif' => 'Ubuntu',
            'Vollkorn, serif' => 'Vollkorn',
            'Allerta Stencil, sans-serif' => 'Allerta Stencil',
            'Prociono, serif' => 'Prociono',
            'Rubik, sans-serif' => 'Rubik',
            'BioRhyme Expanded, serif' => 'BioRhyme Expanded',
            'Inconsolata, monospace' => 'Inconsolata',
            'Supermercado One, cursive' => 'Supermercado One',
            'Anonymous Pro, monospace' => 'Anonymous Pro',

        );
    }

	private function get_signup_options_controls() {
		return array(
			'accept_weak_password'  => array(
				'section' => 'signup_options',
				'type'    => 'checkbox',
				'default' => $this->accept_weak_password,
				'label'   => __( 'Accept weak password', 'hg_login' )
			),
            'password_min_length' => array(
                'section' => 'signup_options',
                'type' => 'number',
                'default' => $this->password_min_length,
                'label' => __('Minimum length of password','hg_login'),
            ),
            'password_max_length' => array(
                'section' => 'signup_options',
                'type' => 'number',
                'default' => $this->password_max_length,
                'label' => __('Maximum length of password','hg_login')
            ),
			'signup_button_text'    => array(
				'section' => 'signup_options',
				'type'    => 'text',
				'default' => $this->signup_button_text,
				'label'   => __( 'Signup button text', 'hg_login' ),
				'help'    => __( 'This is the default text that will appear when you place a signup button shortcode, you can change it by defining a custom text for each shortcode e.g. [hg-signup-button text="your custom text"]', 'hg_login' )
			),
			'signup_popup_title'    => array(
				'section' => 'signup_options',
				'type'    => 'text',
				'default' => $this->signup_popup_title,
				'label'   => __( 'Signup Popup Title', 'hg_login' )
			),
			'signup_popup_subtitle' => array(
				'section'     => 'signup_options',
				'type'        => 'text',
				'default'     => $this->signup_popup_subtitle,
				'label'       => __( 'Signup Popup Subtitle', 'hg_login' ),
				'placeholder' => __( 'Leave blank for no subtitle', 'hg_login' ),
				'help'        => __( 'You can leave this field blank to remove subtitle', 'hg_login' )
			),
		);
	}

	private function get_login_options_controls() {
		return array(
			'login_button_text'    => array(
				'section' => 'login_options',
				'type'    => 'text',
				'default' => $this->login_button_text,
				'label'   => __( 'Login button text', 'hg_login' ),
				'help'    => __( 'This is the default text that will appear when you place a login button shortcode, you can change it by defining a custom text for each shortcode e.g. [hg-login-button text="your custom text"]', 'hg_login' )
			),
			'login_popup_title'    => array(
				'section' => 'login_options',
				'type'    => 'text',
				'default' => $this->login_popup_title,
				'label'   => __( 'Login Popup Title', 'hg_login' ),
			),
			'login_popup_subtitle' => array(
				'section'     => 'login_options',
				'type'        => 'text',
				'default'     => $this->login_popup_subtitle,
				'label'       => __( 'Login Popup Subtitle', 'hg_login' ),
				'placeholder' => __( 'Leave blank for no subtitle', 'hg_login' ),
				'help'        => __( 'You can leave this field blank to remove subtitle', 'hg_login' )
			),
		);
	}

	private function get_forgotpass_popup_controls() {
		return array(
			'forgotpass_popup_title' => array(
				'section' => 'forgotpass_popup',
				'type'    => 'text',
				'default' => $this->forgotpass_popup_title,
				'label'   => __( 'Forgot password header text', 'hg_login' )
			),
		);
	}

	private function get_resetpass_popup_controls() {
		return array(
			'resetpass_popup_title' => array(
				'section' => 'resetpass_popup',
				'type'    => 'text',
				'default' => $this->resetpass_popup_title,
				'label'   => __( 'Reset Password header text', 'hg_login' )
			),
		);
	}

	private function get_account_options_controls() {
		return array(
			'account_dropdown_menu' => array(
				'section' => 'account_options',
				'type'    => 'acc_dropdown_menu',
				'default' => $this->account_dropdown_menu,
				'label'   => __( 'Dropdown Menu', 'hg_login' )
			),
		);
	}

	private function get_custom_css_options_controls() {
		return array(
			'disable_default_css' => array(
				'section' => 'custom_css_options',
				'type'    => 'checkbox',
				'default' => $this->disable_default_css,
				'label'   => __( 'Disable default CSS', 'hg_login' ),
				'help'    => __( 'Disable default styles of plugin, to implement your own custom css, this is NOT RECOMMENDED for non-developers.', 'hg_login' )
			),
			'custom_css'          => array(
				'section' => 'custom_css_options',
				'type'    => 'textarea',
				'default' => $this->custom_css,
				'label'   => __( 'Custom CSS', 'hg_login' )
			),
		);
	}

    private function get_popup_styles_controls(){
        return array(
            'popup_border_radius' => array(
                'section' => 'popup_border_styles',
                'type' => 'simple_slider',
                'default' => $this->popup_border_radius,
                'label' => __('Border Radius(px)','hg_login'),
                'choices' => range( 0, 50 )
            ),
            'popup_border_width' => array(
                'section' => 'popup_border_styles',
                'type' => 'simple_slider',
                'default' => $this->popup_border_width,
                'label' => __('Border Width(px)','hg_login'),
                'choices' => range( 0, 50 )
            ),
            'popup_border_style' => array(
                'section' => 'popup_border_styles',
                'type' => 'select',
                'default' => $this->popup_border_style,
                'label' => __('Border Style','hg_login'),
                'choices' => array(
                    'dotted' => 'dotted',
                    'dashed' => 'dashed',
                    'solid' => 'solid',
                    'double' => 'double',
                    'groove' => 'groove',
                    'ridge' => 'ridge',
                    'inset' => 'inset',
                    'outset' => 'outset',
                    'none' => 'none',
                    'hidden' => 'hidden',
                )
            ),
            'popup_border_color' => array(
                'section' => 'popup_border_styles',
                'type' => 'color',
                'default' => $this->popup_border_color,
                'label' => __('Border Color','hg_login')
            ),
            'popup_border_opacity' => array(
                'section' => 'popup_border_styles',
                'type' => 'simple_slider',
                'default' => $this->popup_border_opacity,
                'label' => __('Border Opacity','hg_login'),
                'choices' => array( 0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1 )
            ),
            'popup_bg_color' => array(
                'section' => 'popup_container_styles',
                'type' => 'color',
                'default' => $this->popup_bg_color,
                'label' => __('Background Color','hg_login')
            ),
            'popup_body_text_style' => array(
                'section' => 'popup_container_styles',
                'type' => 'select',
                'default' => $this->popup_body_text_style,
                'label' => __('Body Font Style','hg_login'),
                'choices' => HG_Login_Settings::get_fonts_choices()
            ),
            'popup_footer_bg_color' => array(
                'section' => 'popup_container_styles',
                'type' => 'color',
                'default' => $this->popup_footer_bg_color,
                'label' => __('Footer Background Color','hg_login')
            ),
            'popup_footer_text_color' => array(
                'section' => 'popup_container_styles',
                'type' => 'color',
                'default' => $this->popup_footer_text_color,
                'label' => __('Footer Text Color','hg_login')
            ),
            'popup_footer_text_style' => array(
                'section' => 'popup_container_styles',
                'type' => 'select',
                'default' => $this->popup_footer_text_style,
                'label' => __('Footer Font Style','hg_login'),
                'choices' => HG_Login_Settings::get_fonts_choices()
            ),
            'popup_header_bg_color' => array(
                'section' => 'popup_container_styles',
                'type' => 'color',
                'default' => $this->popup_header_bg_color,
                'label' => __('Header Background','hg_login')
            ),
            'popup_header_text_color' => array(
                'section' => 'popup_container_styles',
                'type' => 'color',
                'default' => $this->popup_header_text_color,
                'label' => __('Header Text Color','hg_login')
            ),
            'popup_header_text_style' => array(
                'section' => 'popup_container_styles',
                'type' => 'select',
                'default' => $this->popup_header_text_style,
                'label' => __('Header Font Style','hg_login'),
                'choices' => HG_Login_Settings::get_fonts_choices()
            ),
            'popup_close_btn_color' => array(
                'section' => 'popup_container_styles',
                'type' => 'color',
                'default' => $this->popup_close_btn_color,
                'label' => __('Close Button Color','hg_login')
            ),
            'popup_fb_btn_bg_color' => array(
                'section' => 'popup_button_styles',
                'type' => 'color',
                'default' => $this->popup_fb_btn_bg_color,
                'label' => __('Facebook Button Background','hg_login')
            ),
            'popup_fb_btn_text_color' => array(
                'section' => 'popup_button_styles',
                'type' => 'color',
                'default' => $this->popup_fb_btn_text_color,
                'label' => __('Facebook Button Text Color','hg_login')
            ),
            'popup_input_text_color' => array(
                'section' => 'popup_text_styles',
                'type' => 'color',
                'default' => $this->popup_input_text_color,
                'label' => __('Text Field Text Color','hg_login')
            ),
            'popup_input_focused_text_color' => array(
                'section' => 'popup_text_styles',
                'type' => 'color',
                'default' => $this->popup_input_focused_text_color,
                'label' => __('Text Field Focused Text Color','hg_login')
            ),
            'popup_input_bg_color' => array(
                'section' => 'popup_text_styles',
                'type' => 'color',
                'default' => $this->popup_input_bg_color,
                'label' => __('Text Field Background Color','hg_login')
            ),
            'popup_input_focused_bg_color' => array(
                'section' => 'popup_text_styles',
                'type' => 'color',
                'default' => $this->popup_input_focused_bg_color,
                'label' => __('Text Field Focused Background Color','hg_login')
            ),
            'popup_input_label_color' => array(
                'section' => 'popup_text_styles',
                'type' => 'color',
                'default' => $this->popup_input_label_color,
                'label' => __('Text Field Label Color','hg_login')
            ),
            'popup_input_focused_label_color' => array(
                'section' => 'popup_text_styles',
                'type' => 'color',
                'default' => $this->popup_input_focused_label_color,
                'label' => __('Text Field Focused Label Color','hg_login')
            ),
            'popup_input_focused_border_color' => array(
                'section' => 'popup_text_styles',
                'type' => 'color',
                'default' => $this->popup_input_focused_border_color,
                'label' => __('Text Field Focused Border Color','hg_login')
            ),
            'popup_input_error_color' => array(
                'section' => 'popup_text_styles',
                'type' => 'text',
                'html_class' => array('jscolor'),
                'default' => $this->popup_input_error_color,
                'label' => __('Text Field Error Color','hg_login')
            ),
            'popup_primary_btn_color' => array(
                'section' => 'popup_button_styles',
                'type' => 'color',
                'default' => $this->popup_primary_btn_color,
                'label' => __('Primary Button Color','hg_login')
            ),
            'popup_primary_btn_bg_color' => array(
                'section' => 'popup_button_styles',
                'type' => 'color',
                'default' => $this->popup_primary_btn_bg_color,
                'label' => __('Primary Button Background Color','hg_login')
            ),
            'popup_primary_btn_hover_color' => array(
                'section' => 'popup_button_styles',
                'type' => 'color',
                'default' => $this->popup_primary_btn_hover_color,
                'label' => __('Primary Button Hover Color','hg_login')
            ),
            'popup_primary_btn_hover_bg_color' => array(
                'section' => 'popup_button_styles',
                'type' => 'color',
                'default' => $this->popup_primary_btn_hover_bg_color,
                'label' => __('Primary Button Hover Background Color','hg_login')
            ),
            'popup_secondary_btn_color' => array(
                'section' => 'popup_button_styles',
                'type' => 'text',
                'html_class' => array('jscolor'),
                'default' => $this->popup_secondary_btn_color,
                'label' => __('Secondary Button Color','hg_login')
            ),
            'popup_secondary_btn_hover_color' => array(
                'section' => 'popup_button_styles',
                'type' => 'text',
                'html_class' => array('jscolor'),
                'default' => $this->popup_secondary_btn_hover_color,
                'label' => __('Secondary Button Hover Color','hg_login')
            ),
        );
    }

	private function get_login_btn_styles_controls() {
		return array(
			'login_btn_bg_color'         => array(
				'section'    => 'login_btn_styles',
				'type'       => 'text',
				'html_class' => array( 'jscolor' ),
				'default'    => $this->login_btn_bg_color,
				'label'      => __( 'Login Button Background', 'hg_login' )
			),
			'login_btn_hover_bg_color'   => array(
				'section'    => 'login_btn_styles',
				'type'       => 'text',
				'html_class' => array( 'jscolor' ),
				'default'    => $this->login_btn_hover_bg_color,
				'label'      => __( 'Login Button Hover Background', 'hg_login' )
			),
			'login_btn_text_size'        => array(
				'section' => 'login_btn_styles',
				'type'    => 'number',
				'default' => $this->login_btn_text_size,
				'label'   => __( 'Login Button Font Size(px)', 'hg_login' )
			),
			'login_btn_text_color'       => array(
				'section'    => 'login_btn_styles',
				'type'       => 'text',
				'html_class' => array( 'jscolor' ),
				'default'    => $this->login_btn_text_color,
				'label'      => __( 'Login Button Text Color', 'hg_login' )
			),
            'login_btn_font_style' => array(
                'section' => 'login_btn_styles',
                'type' => 'select',
                'default' => $this->login_btn_font_style,
                'label' => __('Login Button Font Style','hg_login'),
                'choices' => HG_Login_Settings::get_fonts_choices()
            ),
			'login_btn_hover_text_color' => array(
				'section'    => 'login_btn_styles',
				'type'       => 'text',
				'html_class' => array( 'jscolor' ),
				'default'    => $this->login_btn_hover_text_color,
				'label'      => __( 'Login Button Hover Text Color', 'hg_login' )
			),
		);
	}

	private function get_signup_btn_styles_controls() {
		return array(
			'signup_btn_bg_color'         => array(
				'section'    => 'signup_btn_styles',
				'type'       => 'text',
				'html_class' => array( 'jscolor' ),
				'default'    => $this->signup_btn_bg_color,
				'label'      => __( 'Signup Button Background', 'hg_login' )
			),
			'signup_btn_hover_bg_color'   => array(
				'section'    => 'signup_btn_styles',
				'type'       => 'text',
				'html_class' => array( 'jscolor' ),
				'default'    => $this->signup_btn_hover_bg_color,
				'label'      => __( 'Signup Button Hover Background', 'hg_login' )
			),
			'signup_btn_text_size'        => array(
				'section' => 'signup_btn_styles',
				'type'    => 'number',
				'default' => $this->signup_btn_text_size,
				'label'   => __( 'Signup Button Font Size(px)', 'hg_login' )
			),
			'signup_btn_text_color'       => array(
				'section'    => 'signup_btn_styles',
				'type'       => 'text',
				'html_class' => array( 'jscolor' ),
				'default'    => $this->signup_btn_text_color,
				'label'      => __( 'Signup Button Text Color', 'hg_login' )
			),
            'signup_btn_font_style' => array(
                'section' => 'signup_btn_styles',
                'type' => 'select',
                'default' => $this->signup_btn_font_style,
                'label' => __('Signup Button Font Style','hg_login'),
                'choices' => HG_Login_Settings::get_fonts_choices()
            ),
			'signup_btn_hover_text_color' => array(
				'section'    => 'signup_btn_styles',
				'type'       => 'text',
				'html_class' => array( 'jscolor' ),
				'default'    => $this->signup_btn_hover_text_color,
				'label'      => __( 'Signup Button Hover Text Color', 'hg_login' )
			),
		);
	}

	private function get_facebook_options_controls() {
		return array(
			'facebook_enabled'    => array(
				'section' => 'facebook_options',
				'type'    => 'checkbox',
				'default' => $this->facebook_enabled,
				'label'   => __( 'Enable Facebook Authentication', 'hg_login' )
			),
			'facebook_app_id'     => array(
				'section'     => 'facebook_options',
				'type'        => 'text',
				'default'     => $this->facebook_app_id,
				'label'       => __( 'Facebook App ID', 'hg_login' ),
				'description' => sprintf( __( '%s Get Your Facebook App ID %s', 'hg_login' ), '<a href="' . esc_url( 'https://developers.facebook.com/' ) . '" target="_blank" >', '</a>' )
			),
			'facebook_app_secret' => array(
				'section' => 'facebook_options',
				'type'    => 'text',
				'default' => $this->facebook_app_secret,
				'label'   => __( 'Facebook App Secret', 'hg_login' ),
            )
        );
    }
    private function get_google_sign_in_options_controls(){
        return array(
            'google_sign_in_enabled' => array(
                'section' => 'google_options',
                'type' => 'checkbox',
                'default' => $this->google_sign_in_enabled,
                'label' => __( 'Enable Google Authentication', 'hg_login' )
            ),
            'google_sign_in_id' => array(
                'section' => 'google_options',
                'type' => 'text',
                'default' => $this->google_sign_in_id,
                'label' => __('Google Client ID','hg_login'),
                'description' => sprintf( __('%s Get Your Google Cliend ID %s','hg_login'), '<a href="'.esc_url('https://developers.google.com/+/web/signin/').'" target="_blank" >', '</a>' )
			)
		);
	}

	private function get_recaptcha_options_controls() {
		return array(
			'recaptcha_enabled'    => array(
				'section' => 'recaptcha_options',
				'type'    => 'checkbox',
				'default' => $this->recaptcha_enabled,
				'label'   => __( 'Enable reCaptcha', 'hg_login' )
			),
			'recaptcha_key'        => array(
				'section'     => 'recaptcha_options',
				'type'        => 'text',
				'default'     => $this->recaptcha_key,
				'label'       => __( 'Your reCaptcha key', 'hg_login' ),
				'description' => sprintf( '<a href="https://www.google.com/recaptcha/admin" target="_blank">%s</a>', __( 'Get your recaptcha key', 'hg_login' ) ),
				'help'        => __( 'This key is needed to display the recaptcha widget', 'hg_login' )
			),
			'recaptcha_secret_key' => array(
				'section' => 'recaptcha_options',
				'type'    => 'text',
				'default' => $this->recaptcha_secret_key,
				'label'   => __( 'Your reCaptcha Secret key', 'hg_login' ),
				'help'    => __( 'This key is needed for communications between your site and Google', 'hg_login' )
			),
			'recaptcha_theme'      => array(
				'section' => 'recaptcha_options',
				'type'    => 'select',
				'default' => $this->recaptcha_theme,
				'label'   => __( 'reCaptcha theme', 'hg_login' ),
				'choices' => array(
					'light' => __( 'Light', 'hg_login' ),
					'dark'  => __( 'Dark', 'hg_login' )
				)
			),
		);
	}

	/**
	 * Display the admin page
	 */
	public function init_controls() {
		$global_controls            = $this->get_global_controls();
		$signup_controls            = $this->get_signup_options_controls();
		$login_controls             = $this->get_login_options_controls();
		$forgotpass_controls        = $this->get_forgotpass_popup_controls();
		$resetpass_controls         = $this->get_resetpass_popup_controls();
		$account_controls           = $this->get_account_options_controls();
		$custom_css_controls        = $this->get_custom_css_options_controls();
		$popup_styles_controls      = $this->get_popup_styles_controls();
		$login_btn_styles_controls  = $this->get_login_btn_styles_controls();
		$signup_btn_styles_controls = $this->get_signup_btn_styles_controls();
		$facebook_options_controls  = $this->get_facebook_options_controls();
        $google_options_controls = $this->get_google_sign_in_options_controls();
		$recaptcha_controls         = $this->get_recaptcha_options_controls();

		$controls = array_merge(
			$global_controls,
			$signup_controls,
			$login_controls,
			$forgotpass_controls,
			$resetpass_controls,
			$account_controls,
			$custom_css_controls,
			$popup_styles_controls,
			$login_btn_styles_controls,
			$signup_btn_styles_controls,
			$facebook_options_controls,
            $google_options_controls,
			$recaptcha_controls
		);

		foreach ( $controls as $control_id => $control ) {
			$this->controls[ $control_id ] = $control;
		}

	}

	public function control_acc_dropdown_menu( $id, $control ) {
		$items       = isset( $control['default'] ) ? $control['default'] : array();
		$items_count = count( $items['titles'] );
		echo '<p class="wpdev-label wpdev-left">' . __( 'Manage Account Menu Items', 'hg_login' ) . '</p>';
		?>
        <div>
            <button class="wpdev-mini-button wpdev-left hg_login_add_acc_menu">
                &#43;&nbsp;<?php _e( 'Add Menu Item' ) ?></button>
        </div>
        <ul class="hg_login_acc_menu_settings">
			<?php
			for ( $i = 0; $i < $items_count; $i ++ ) {
				echo hg_login_acc_menu_option_line( $items['titles'][ $i ], $items['links'][ $i ] );
			}
			?>
        </ul>
		<?php
	}

}