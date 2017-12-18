<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class WPUserMenu
{

    public function __construct()
    {
        add_action('init', array('WPUserMenu', 'init'));
        add_action('after_setup_theme', array($this, 'remove_admin_bar'));
    }

    static function remove_admin_bar()
    {
        $wp_user_disable_admin_bar = get_option('wp_user_disable_admin_bar');
        if (!empty($wp_user_disable_admin_bar) && $wp_user_disable_admin_bar == 1) {
            if (!current_user_can('administrator') && !is_admin()) {
                show_admin_bar(false);
            }
        }
    }


    static function init()
    {
        add_action('admin_menu', array('WPUserMenu', 'adminPage'));
    }

    static function adminPage()
    {
        add_menu_page('WP Users', 'WP Users', 'manage_options', 'wp-user-setting', array('WPUserMenu', 'renderAdminPage'), 'dashicons-admin-users');
        add_submenu_page('wp-user-setting', 'Users', 'Users', 'manage_options', 'wp-user-list', array('WPUserMenu', 'renderUserPage'));
        add_submenu_page('wp-user-setting', 'Add-ons', 'Add-ons', 'manage_options', 'wp-user-addons', array('WPUserMenu', 'renderAddonPage'));
        do_action("add_wpuser_submenu");

    }

    static function renderAdminPage()
    {
        include('view/setting.php');
    }

    static function renderUserPage()
    {
        include('view/view-user-list.php');
    }

    static function renderAddonPage()
    {
        include('view/view-addon.php');
    }
}

$WPUserMenu = new WPUserMenu();

