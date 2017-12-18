<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class hg_login_Featured_Plugins extends WPDEV_Settings_API {

    public function __construct(){
        $this->init();
    }

    public function init(){

    }

    public function display(){
        HG_Login_Template_Loader::get_template('admin/featured-plugins.php');
    }
}