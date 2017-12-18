<?php

/*
Plugin Name: Huge IT Login
Plugin URI: https://huge-it.com/wordpress-login/
Description: Login plugin allows to create easy custom login and registration. With this WordPress login plugin use reCaptcha, pop-up and advanced design options.
Version:     1.0.4
Author:      Huge-IT
Author URI:  http://huge-it.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: hg_login
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'HG_Login' ) ) :

    final class HG_Login {

        /**
         * Version of plugin
         * @var string
         */
        public $version = "1.0.4";

        /**
         * Instance of hg_login_Admin class to manage admin
         * @var hg_login_Admin instance
         */
        public $admin;

        /**
         * @var HG_Login_Settings
         */
        public $settings;

        /**
         * The single instance of the class.
         *
         * @var hg_login
         */
        protected static $_instance = null;

        /**
         * Main hg_login Instance.
         *
         * Ensures only one instance of hg_login is loaded or can be loaded.
         *
         * @static
         * @see hg_login()
         * @return hg_login - Main instance.
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        private function __clone() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'hg_login' ), '2.1' );
        }

        /**
         * Unserializing instances of this class is forbidden.
         */
        private function __wakeup() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'hg_login' ), '2.1' );
        }

        /**
         * hg_login Constructor.
         */
        private function __construct() {
            $this->define_constants();
            $this->includes();
            $this->init_hooks();
            do_action( 'hg_login_loaded' );
        }

        /**
         * Hook into actions and filters.
         */
        private function init_hooks() {
            add_action( 'init', array( $this, 'init' ), 0 );
            add_action( 'plugins_loaded', array($this,'load_plugin_textdomain') );
            add_action( 'init', array($this,'register_shortcodes') );

        }

        /**
         * Define hg_login Constants.
         */
        private function define_constants() {
            $this->define( 'HG_LOGIN_PLUGIN_FILE', __FILE__ );
            $this->define( 'HG_LOGIN_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
            $this->define( 'HG_LOGIN_VERSION', $this->version );
            $this->define( 'HG_LOGIN_IMAGES_PATH', $this->plugin_path(). DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR );
            $this->define( 'HG_LOGIN_IMAGES_URL', untrailingslashit($this->plugin_url() . '/assets/images/' ));
            $this->define( 'HG_LOGIN_TEMPLATES_PATH', $this->plugin_path() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR);
            $this->define( 'hg_login_TEMPLATES_URL', untrailingslashit($this->plugin_url()) . '/templates/');
        }

        /**
         * Define constant if not already set.
         *
         * @param  string $name
         * @param  string|bool $value
         */
        private function define( $name, $value ) {
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
        }

        /**
         * What type of request is this?
         * string $type ajax, frontend or admin.
         *
         * @return bool
         */
        public function is_request( $type ) {
            switch ( $type ) {
                case 'admin' :
                    return is_admin();
                case 'ajax' :
                    return defined( 'DOING_AJAX' );
                case 'cron' :
                    return defined( 'DOING_CRON' );
                case 'frontend' :
                    return  ! is_admin() && ! defined( 'DOING_CRON' );
            }
        }

        /**
         * Include required core files used in admin and on the frontend.
         */
        public function includes() {
            include_once( 'includes/hg-login-core-functions.php' );
            include_once( 'includes/hg-login-user-functions.php' );
            include_once('includes/hg-login-fb-functions.php');
            include_once('includes/hg-login-google-functions.php');
            include_once( 'includes/hg-login-core-filters.php' );

            include_once( 'vendor/wpdev-settings-api/class-wpdev-settings-api.php' );

            include_once( 'includes/class-hg-login-widgets.php' );
            include_once( 'includes/class-hg-login-template-loader.php' );
            include_once( 'includes/class-hg-login-shortcodes.php' );
            include_once('includes/class-hg-login-settings.php');

            include_once('includes/ajax.php');

           if ( $this->is_request( 'admin' ) ) {
                include_once( 'includes/admin/class-hg-login-admin.php' );
                include_once( 'includes/admin/class-hg-login-admin-assets.php' );
                include_once( 'includes/admin/class-hg-login-featured-plugins.php' );
           }

           if( $this->is_request( 'frontend' ) ){
               $this->frontend_includes();
           }

        }

        /**
         * Include required core files used in front end
         */
        public function frontend_includes(){
            include_once( 'includes/class-hg-login-frontend-scripts.php' );
        }

        /**
         * Load plugin text domain
         */
        public function load_plugin_textdomain(){
            load_plugin_textdomain( 'hg_login', false, $this->plugin_path() . '/languages/' );
        }

        /**
         * Init Login WP when WordPress `initialises.
         */
        public function init() {
            // Before init action.
            do_action( 'before_hg_login_init' );

            if ( $this->is_request( 'admin' ) ) {
                $this->admin = new hg_login_Admin();
            }

            $this->settings = new HG_Login_Settings();

            add_action( 'widgets_init', array($this,'register_widgets') );
            add_filter( 'bp_core_signup_send_activation_key', array( $this, 'bp_core_signup_send_activation_key' ) );
            // Init action.
            do_action( 'hg_login_init' );
        }

        /**
         * Tell the big brother if we want to send activation emails or not
         * @return bool
         */
        public function bp_core_signup_send_activation_key(){
            return HG_Login()->settings->email_verify_required === 'yes';
        }

        /**
         * Register our shortcodes
         */
        public function register_shortcodes(){
            add_shortcode( 'hg-login-button', array("HG_Login_Button_Shortcode",'do_shortcode') );
            add_shortcode( 'hg-signup-button', array("HG_Signup_Button_Shortcode",'do_shortcode') );
        }

        /**
         * Register our widgets
         */
        public function register_widgets(){
            /**
             * Login Widget
             */
            register_widget( 'HG_Login_Widget' );
            /**
             * Sign Up Widget
             */
            register_widget( 'HG_Login_Signup_Widget' );
        }

        /**
         * Get Ajax URL.
         * @return string
         */
        public function ajax_url() {
            return admin_url( 'admin-ajax.php', 'relative' );
        }

        /**
         * Easy Login Plugin Path.
         *
         * @var string
         * @return string
         */
        public function plugin_path(){
            return untrailingslashit( plugin_dir_path( __FILE__ ) );
        }

        public function template_path(){
            return apply_filters( 'hugeit_maps_template_path', 'hg-login/' );
        }

        /**
         * Easy Login Plugin Url.
         * @return string
         */
        public function plugin_url(){
            return plugins_url('', __FILE__ );
        }
    }

endif;

function HG_Login(){
    return HG_Login::instance();
}

$GLOBALS['hg_login'] = HG_Login();