<?php
wp_enqueue_script('jquery');

wp_enqueue_style('wpsp_bootstrap', WPUSER_PLUGIN_URL . 'assets/css/bootstrap.min.css');
wp_enqueue_style('wpdbadminltecss', WPUSER_PLUGIN_URL . "assets/dist/css/AdminLTE.css");
wp_enqueue_style('wpdbbootstrapcdncss', WPUSER_PLUGIN_URL . "assets/css/font-awesome.min.css");
wp_enqueue_style('wpdbbskinscss', WPUSER_PLUGIN_URL . "assets/dist/css/skins/_all-skins.min.css");
wp_enqueue_style('wpdbiCheckcss', WPUSER_PLUGIN_URL . "assets/plugins/iCheck/flat/red.css");
wp_enqueue_script('wpdbbootstrap', WPUSER_PLUGIN_URL . "assets/js/bootstrap.min.js");
wp_enqueue_script('wpdbapp', WPUSER_PLUGIN_URL . "assets/dist/js/app.min.js");
wp_enqueue_script('wpdbbootstrapconfirmbox', WPUSER_PLUGIN_URL . "assets/js/bootbox.js");
wp_enqueue_script('wpdbbootstraprecaptcha', "https://www.google.com/recaptcha/api.js");

wp_deregister_style('wpce_bootstrap');
wp_enqueue_media();



//wp_enqueue_script('wpuserajax', WPUSER_PLUGIN_URL . "assets/js/ajax.js");

$localize_script_data = array(
    'wpuser_ajax_url' => admin_url('admin-ajax.php'),
    'wpuser_update_setting' => wp_create_nonce('wpuser-update-setting'),
    'wpuser_site_url' => site_url(),
    'plugin_url' => WPUSER_PLUGIN_URL,
    'wpuser_templateUrl' => WPUSER_TEMPLETE_URL,
    'plugin_dir' => WPUSER_PLUGIN_DIR,
    'isUserLogged' => (is_user_logged_in()) ? 1 : 0,
    'wpuser_lang' => get_option('wp_user_language')
);
//wp_localize_script('wpuserajax', 'wpuser_link', $localize_script_data);

wp_deregister_style('wpce_bootstrap');

$isUserLogged = (is_user_logged_in()) ? 1 : 0;
$wp_user_appearance_skin = get_option('wp_user_appearance_skin') ? get_option('wp_user_appearance_skin') : 'default';