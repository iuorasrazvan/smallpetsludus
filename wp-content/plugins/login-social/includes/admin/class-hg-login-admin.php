<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class hg_login_Admin {

    /**
     * Array of pages in admin
     * @var array
     */
    public $pages = array();

    /**
     * Instance of hg_login_Settings class
     *
     * @var hg_login_Settings
     */
    public $featured_plugins = null;

    /**
     * hg_login_Admin constructor.
     */
    public function __construct(){
        $this->init();
        add_action('admin_menu',array($this,'admin_menu'));
        add_action( 'wpdev_settings_hg_login_admin_menu', array( $this, 'admin_menu_after_settings' ) );
        add_action( 'wpdev_settings_hg_login_header', array( $this, 'print_banners' ) );
        add_action( 'hg_login_featured_plugins_header', array( $this, 'print_banners' ) );
        add_action( 'hg_login_dashboard_head', array( $this, 'print_banners' ) );

    }

    /**
     * Initialize Plugin's admin
     */
    protected function init(){
        $this->featured_plugins = new hg_login_Featured_Plugins();
    }

    /**
     * Creates Admin Menu Pages
     */
    public function admin_menu(){
        $this->pages['main_page'] = add_menu_page( __( 'Login WP', 'hg_login' ),  __( 'Login WP', 'hg_login' ), 'manage_options', 'hg_login', '', HG_LOGIN_IMAGES_URL."/logo.svg" );
        $this->pages['dashboard'] = add_submenu_page('hg_login', __('Login WP Dashboard', 'hg_login'), 'Dashboard', 'manage_options', 'hg_login', array($this, 'init_dashboard'));
    }

    /**
     * Creates Admin Menu Pages after the settings page is created by WPDEV_Settings_API
     */
    public function admin_menu_after_settings(){
        $this->pages['featured-plugins'] = add_submenu_page('hg_login', __('Featured Plugins', 'hg_login'), __('Featured Plugins', 'hg_login'), 'manage_options', 'hg_login_featured_plugins', array(HG_Login()->admin->featured_plugins, 'init_admin'));
        $this->pages['licensing'] = add_submenu_page('hg_login', __('Licensing', 'hg_login'), __('Licensing', 'hg_login'), 'manage_options', 'hg_login_licensing', array($this, 'licensing_page'));
    }

    /**
     * Print banners for admin
     */
    public function print_banners(){
        HG_Login_Template_Loader::get_template('admin/free-banner.php');
    }

    /**
     * Initialize dashboard page
     */
    public function init_dashboard(){
        HG_Login_Template_Loader::get_template('admin/dashboard.php');
    }

	/**
	 * Licensing page
	 */
    public function licensing_page(){
	    HG_Login_Template_Loader::get_template('admin/licensing.php');
    }


}

