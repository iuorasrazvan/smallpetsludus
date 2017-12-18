<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * HG_Login_Admin_Assets Class.
 */
class HG_Login_Admin_Assets {

    /**
     * Hook in tabs.
     */
    public function __construct(){
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
    }

    /**
     * @param $hook string
     */
    public function admin_scripts( $hook ){
        if( $hook === HG_Login()->admin->pages['featured-plugins'] ){
            wp_enqueue_script('hg-login-admin', HG_Login()->plugin_url().'/assets/js/admin.js', array('jquery'), false, true);
        }
    }

    /**
     * @param $hook string
     */
    public function admin_styles( $hook ){

        /**
         * Common css for admin
         */
        wp_enqueue_style('hg-login-admin-common', HG_Login()->plugin_url() . '/assets/css/admin-common.css');

        /**
         * Featured Plugins
         */
        if( $hook === HG_Login()->admin->pages['featured-plugins'] ){

            wp_enqueue_style('hg-login-featured-plugins', HG_Login()->plugin_url().'/assets/css/featured-plugins.css');
            wp_enqueue_style('hg-login-admin', HG_Login()->plugin_url().'/assets/css/admin.css');


        }

        /**
         * Dashboard
         */
        if( $hook === HG_Login()->admin->pages['dashboard'] ){
            wp_enqueue_style('hg-login-admin', HG_Login()->plugin_url().'/assets/css/admin.css');
            wp_enqueue_style('hg-login-dashboard', HG_Login()->plugin_url().'/assets/css/dashboard.css');
            wp_enqueue_style('animate-css', HG_Login()->plugin_url().'/vendor/animate-css/animate.css');
            wp_enqueue_style('open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700');
        }


        if( $hook === HG_Login()->admin->pages['licensing'] ){
	        wp_enqueue_style('licensing', HG_Login()->plugin_url().'/assets/css/licensing.css');
        }
    }
}
return new HG_Login_Admin_Assets();