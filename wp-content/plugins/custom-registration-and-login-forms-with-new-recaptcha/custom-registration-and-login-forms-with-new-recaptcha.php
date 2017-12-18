<?php
/*
Plugin Name: Custom Registration And Login Forms With New Recaptcha
Plugin URI: http://example.com/
Description: Provides simple frontend registration and login forms with new recaptcha
Version: 1.1
Author: Commercepundit
Author URI: http://www.commercepundit.com/
*/
  session_start();

  // Create a helper function for easy SDK access.
function cralfwnr_fs() {
    global $cralfwnr_fs;

    if ( ! isset( $cralfwnr_fs ) ) {
        // Include Freemius SDK.
        require_once dirname(__FILE__) . '/freemius/start.php';

        $cralfwnr_fs = fs_dynamic_init( array(
            'id'                => '484',
            'slug'              => 'custom-registration-and-login-forms-with-new-recaptcha',
            'type'              => 'plugin',
            'public_key'        => 'pk_7e04ea6ce2e0e08559fb3a125fb32',
            'is_premium'        => false,
            'has_addons'        => false,
            'has_paid_plans'    => false,
            'menu'              => array(
                'slug'       => 'custom-registration-and-login-forms-with-new-recaptcha',
                'contact'    => false,
                'support'    => false,
            ),
        ) );
    }

    return $cralfwnr_fs;
}

// Init Freemius.
cralfwnr_fs();
add_action('admin_menu', 'crlfwnr_plugin_setup_menu');
function crlfwnr_plugin_setup_menu(){
       // add_menu_page( 'Register Plugin Page', 'Register Plugin', 'manage_options', 'crlfwnr_plugin','crlfwnr_init', 'crlfwnr_custom_registration_form' );
        add_menu_page(  'Register Plugin Page', 'Register Plugin', 'manage_options', 'crlfwnr_plugin', 'crlfwnr_init' );
}
function crlfwnr_init(){

        $reg_options = get_option( 'reg_options' ); //captcha_login
        $site_key    = isset( $reg_options['site_key'] ) ? $reg_options['site_key'] : '';

        $secrete_key = isset( $reg_options['secrete_key'] ) ? $reg_options['secrete_key'] : '';

        $captcha_login        = isset( $reg_options['captcha_login'] ) ? $reg_options['captcha_login'] : '';
        $captcha_registration = isset( $reg_options['captcha_registration'] ) ? $reg_options['captcha_registration'] : '';
        $captcha_comment      = isset( $reg_options['captcha_comment'] ) ? $reg_options['captcha_comment'] : '';

        $theme         = isset( $reg_options['theme'] ) ? $reg_options['theme'] : '';
        $language      = isset( $reg_options['language'] ) ? $reg_options['language'] : '';
        $error_message = isset( $reg_options['error_message'] ) ? $reg_options['error_message'] : '';

        // call to save the setting options
        crlfwnr_save_options();

      ?>

       <div class="wrap">

        <div id="icon-options-general" class="icon32"></div>
        <h2>New reCAPTCHA for Custom Registeration and Custom Login</h2>

        <p>Protect WordPress Custom login, Custom registration form with the new reCAPTCHA</p>

        <?php
        if ( isset( $_GET['settings-updated'] ) && ( $_GET['settings-updated'] ) ) {
            echo '<div id="message" class="updated"><p><strong>Settings saved. </strong></p></div>';
        }
        ?>
        <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

        <!-- main content -->
        <div id="post-body-content">

        <div class="meta-box-sortables ui-sortable">

        <form method="post">
          <?php wp_nonce_field('update-options') ?>

        <div class="postbox">

            <div title="Click to toggle" class="handlediv"><br></div>
            <h3 class="hndle"><span><?php _e( 'reCAPTCHA Keys', 'reg-captcha' ); ?></span></h3>

            <div class="inside">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label
                                for="site-key"><?php _e( 'Site key', 'reg-captcha' ); ?></label>
                        </th>
                        <td>

                            <input id="site-key" type="text" name="reg_options[site_key]"
                                   value="<?php echo $site_key; ?>">

                            <p class="description">
                                <?php _e( 'Used for displaying the CAPTCHA. Grab it <a href="https://www.google.com/recaptcha/admin" target="_blank">Here</a>', 'reg-captcha' ); ?>

                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label
                                for="secrete-key"><?php _e( 'Secret key', 'reg-captcha' ); ?></label>
                        </th>
                        <td>
                            <input id="secrete-key" type="text" name="reg_options[secrete_key]"
                                   value="<?php echo $secrete_key; ?>">

                            <p class="description">
                                <?php _e( 'Used for communication between your site and Google. Grab it <a href="https://www.google.com/recaptcha/admin" target="_blank">Here</a>', 'reg-captcha' ); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                <p>
                    <?php wp_nonce_field( 'reg_settings_nonce' ); ?>
                    <input class="button-primary" type="submit" name="settings_submit"
                           value="Save All Changes">
                </p>
            </div>
        </div>

        <div class="postbox">

            <div title="Click to toggle" class="handlediv"><br></div>
            <h3 class="hndle"><span><?php _e( 'Display Settings', 'reg-captcha' ); ?></span></h3>

            <div class="inside">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="login"><?php _e( 'Custom Login Form', 'reg-captcha' ); ?></label>
                        </th>
                        <td>
                            <input id="login" type="checkbox" name="reg_options[captcha_login]"
                                   value="yes" <?php checked( $captcha_login, 'yes' ) ?>>

                            <p class="description">
                                <?php _e( 'Check to enable CAPTCHA in custom login form', 'reg-captcha' ); ?>

                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label
                                for="registration"><?php _e( 'Custom Registration Form', 'reg-captcha' ); ?></label>
                        </th>
                        <td>
                            <input id="registration" type="checkbox" name="reg_options[captcha_registration]"
                                   value="yes" <?php checked( $captcha_registration, 'yes' ) ?>>

                            <p class="description">
                                <?php _e( 'Check to enable CAPTCHA in WordPress custom registration form', 'reg-captcha' ); ?>
                            </p>
                        </td>
                    </tr>
                                    </table>
                <p>
                    <?php wp_nonce_field( 'reg_settings_nonce' ); ?>
                    <input class="button-primary" type="submit" name="settings_submit"
                           value="Save All Changes">
                </p>
            </div>
        </div>


        <div class="postbox">

            <div class="handlediv"><br></div>
            <h3 class="hndle"><span><?php _e( 'General Settings', 'reg-captcha' ); ?></span>
            </h3>

            <div class="inside">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label
                                for="theme"><?php _e( 'Theme', 'reg-captcha' ); ?></label></th>
                        <td>
                            <select id="theme" name="reg_options[theme]">
                                <option value="light" <?php selected( 'light', $theme ); ?>>Light</option>
                                <option value="dark" <?php selected( 'dark', $theme ); ?>>Dark</option>
                            </select>

                            <p class="description">
                                <?php _e( 'The theme colour of the widget.', 'reg-captcha' ); ?>
                            </p>
                        </td>
                    </tr>
                </table>

                <table class="form-table">
                    <tr>
                        <th scope="row"><label
                                for="theme"><?php _e( 'Language', 'reg-captcha' ); ?></label>
                        </th>
                        <td>
                            <select id="theme" name="reg_options[language]">
                                <?php
                                $languages = array(
                                    __( 'Auto Detect', 'reg-captcha' )         => '',
                                    __( 'English', 'reg-captcha' )             => 'en',
                                    __( 'Arabic', 'reg-captcha' )              => 'ar',
                                    __( 'Bulgarian', 'reg-captcha' )           => 'bg',
                                    __( 'Catalan Valencian', 'reg-captcha' )   => 'ca',
                                    __( 'Czech', 'reg-captcha' )               => 'cs',
                                    __( 'Danish', 'reg-captcha' )              => 'da',
                                    __( 'German', 'reg-captcha' )              => 'de',
                                    __( 'Greek', 'reg-captcha' )               => 'el',
                                    __( 'British English', 'reg-captcha' )     => 'en_gb',
                                    __( 'Spanish', 'reg-captcha' )             => 'es',
                                    __( 'Persian', 'reg-captcha' )             => 'fa',
                                    __( 'French', 'reg-captcha' )              => 'fr',
                                    __( 'Canadian French', 'reg-captcha' )     => 'fr_ca',
                                    __( 'Hindi', 'reg-captcha' )               => 'hi',
                                    __( 'Croatian', 'reg-captcha' )            => 'hr',
                                    __( 'Hungarian', 'reg-captcha' )           => 'hu',
                                    __( 'Indonesian', 'reg-captcha' )          => 'id',
                                    __( 'Italian', 'reg-captcha' )             => 'it',
                                    __( 'Hebrew', 'reg-captcha' )              => 'iw',
                                    __( 'Jananese', 'reg-captcha' )            => 'ja',
                                    __( 'Korean', 'reg-captcha' )              => 'ko',
                                    __( 'Lithuanian', 'reg-captcha' )          => 'lt',
                                    __( 'Latvian', 'reg-captcha' )             => 'lv',
                                    __( 'Dutch', 'reg-captcha' )               => 'nl',
                                    __( 'Norwegian', 'reg-captcha' )           => 'no',
                                    __( 'Polish', 'reg-captcha' )              => 'pl',
                                    __( 'Portuguese', 'reg-captcha' )          => 'pt',
                                    __( 'Romanian', 'reg-captcha' )            => 'ro',
                                    __( 'Russian', 'reg-captcha' )             => 'ru',
                                    __( 'Slovak', 'reg-captcha' )              => 'sk',
                                    __( 'Slovene', 'reg-captcha' )             => 'sl',
                                    __( 'Serbian', 'reg-captcha' )             => 'sr',
                                    __( 'Swedish', 'reg-captcha' )             => 'sv',
                                    __( 'Thai', 'reg-captcha' )                => 'th',
                                    __( 'Turkish', 'reg-captcha' )             => 'tr',
                                    __( 'Ukrainian', 'reg-captcha' )           => 'uk',
                                    __( 'Vietnamese', 'reg-captcha' )          => 'vi',
                                    __( 'Simplified Chinese', 'reg-captcha' )  => 'zh_cn',
                                    __( 'Traditional Chinese', 'reg-captcha' ) => 'zh_tw'
                                );

                                foreach ( $languages as $key => $value ) {
                                    echo "<option value='$value'" . selected( $value, $language, true ) . ">$key</option>";
                                }
                                ?>
                            </select>

                            <p class="description">
                                <?php _e( 'Forces the widget to render in a specific language', 'reg-captcha' ); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label
                                for="message"><?php _e( 'Error Message', 'reg-captcha' ); ?></label>
                        </th>
                        <td>
                            <input id="message" type="text" name="reg_options[error_message]"
                                   value="<?php echo $error_message; ?>">

                            <p class="description">
                                <?php _e( 'Message or text to display when CAPTCHA is ignored or the test is failed.', 'reg-captcha' ); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                <p>
                    <?php wp_nonce_field( 'settings_nonce' ); ?>
                    <input class="button-primary" type="submit" name="settings_submit"
                           value="Save All Changes">
                </p>
            </div>
        </div>
        </form>
        </div>
        </div>

        </div>
        <br class="clear">
        </div>
        </div>
      <?php
       // html_form_code();
}

 function crlfwnr_save_options() {
        if ( isset( $_POST['settings_submit'] ) && check_admin_referer( 'settings_nonce', '_wpnonce' ) ) {

            $saved_options = $_POST['reg_options'];
            update_option( 'reg_options', $saved_options );
          //  crlfwnr_custom_registration_form();
            wp_redirect( '?page=crlfwnr_plugin&settings-updated=true' );
        }
    }

// user registration login form
function crlfwnr_custom_registration_form() {

    // only show the registration form to non-logged-in members
    if(!is_user_logged_in()) {

        global $custom_load_css;

        // set this to true so the CSS is loaded
        $custom_load_css = true;

        // check to make sure user registration is enabled
        $registration_enabled = get_option('users_can_register');

        // only show the registration form if allowed
        if($registration_enabled) {
            $output = crlfwnr_custom_registration_form_fields();
        } else {
            $output = __('User registration is not enabled');
        }
        return $output;
    }
}
add_shortcode('crlfwnr_register_form', 'crlfwnr_custom_registration_form');

// user login form
function crlfwnr_custom_login_form() {

    if(!is_user_logged_in()) {

        global $custom_load_css;

        // set this to true so the CSS is loaded
        $custom_load_css = true;

        $output = crlfwnr_custom_login_form_fields();
    } else {
        // could show some logged in user info here
        // $output = 'user info here';
    }
    return $output;
}
add_shortcode('crlfwnr_login_form', 'crlfwnr_custom_login_form');

// registration form fields
function crlfwnr_custom_registration_form_fields() {

    ob_start(); ?>
        <h3 class="custom_header"><?php _e('Register New Account'); ?></h3>

        <?php
        // show any error messages after form submission
        crlfwnr_custom_show_error_messages();
        $reg_options = get_option( 'reg_options' );
        //echo "------------<pre>";
        //print_r($reg_options);
        ?>
       <script src='https://www.google.com/recaptcha/api.js'></script>
       <form id="registerform"  name="registerform" class="crlfwnr_custom_form" action="" method="POST">
            <fieldset>
                <p>
                    <label for="custom_user_Login"><?php _e('Username'); ?><span class="crlfwnr_required">*</span></label>
                    <input name="custom_user_login" id="custom_user_login" class="required" type="text" value="<?php echo $_POST['custom_user_login']; ?>"/>
                </p>
                <p>
                    <label for="custom_user_email"><?php _e('Email'); ?><span class="crlfwnr_required">*</span></label>
                    <input name="custom_user_email" id="custom_user_email" class="required" type="email" value="<?php echo $_POST['custom_user_email']; ?>"/>
                </p>
                <p>
                    <label for="custom_user_first"><?php _e('First Name'); ?></label>
                    <input name="custom_user_first" id="custom_user_first" type="text" value="<?php echo $_POST['custom_user_first']; ?>"/>
                </p>
                <p>
                    <label for="custom_user_last"><?php _e('Last Name'); ?></label>
                    <input name="custom_user_last" id="custom_user_last" type="text" value="<?php echo $_POST['custom_user_last']; ?>"/>
                </p>
                <p>
                    <label for="password"><?php _e('Password'); ?><span class="crlfwnr_required">*</span></label>
                    <input name="custom_user_pass" id="password" class="required" type="password" />
                </p>
                <p>
                    <label for="password_again"><?php _e('Password Again'); ?></label>
                    <input name="custom_user_pass_confirm" id="password_again" class="required" type="password"/>
                </p>
                <p>

                <?php if($reg_options['captcha_registration']){
                   $captcha_theme = $reg_options['theme'];
                ?>
                <p><div class="g-recaptcha" data-theme="<?php echo $captcha_theme; ?>" data-sitekey="<?php echo $reg_options['site_key']; ?>"></div></p>
                <?php } ?>

                <p>
                    <input type="hidden" name="custom_register_nonce" value="<?php echo wp_create_nonce('custom-register-nonce'); ?>"/>
                    <input type="submit" value="<?php _e('Register Your Account'); ?>"/>
                </p>
            </fieldset>
        </form>
            <?php
    return ob_get_clean();
}

// login form fields
function crlfwnr_custom_login_form_fields() {

    ob_start(); ?>
        <h3 class="custom_header"><?php _e('Login'); ?></h3>

        <?php
        // show any error messages after form submission
        crlfwnr_custom_show_error_messages();
         $reg_options = get_option( 'reg_options' );
         ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <form id="crlfwnr_custom_login_form"  class="crlfwnr_custom_form" action="" method="post">
            <fieldset>
                <p>
                    <label for="custom_user_Login">Username <span class="crlfwnr_required">*</span></label>
                    <input name="custom_user_login" id="custom_user_login" class="required" type="text"/>
                </p>
                <p>
                    <label for="custom_user_pass">Password <span class="crlfwnr_required">*</span></label>
                    <input name="custom_user_pass" id="custom_user_pass" class="required" type="password"/>
                </p>

                <?php if($reg_options['captcha_login']){
                   $captcha_theme = $reg_options['theme'];
                ?>
                <p><div class="g-recaptcha" data-theme="<?php echo $captcha_theme; ?>" data-sitekey="<?php echo $reg_options['site_key']; ?>"></div></p>
                <?php } ?>
                <p>
                    <input type="hidden" name="custom_login_nonce" value="<?php echo wp_create_nonce('custom-login-nonce'); ?>"/>
                    <input id="custom_login_submit" type="submit" value="Login"/>
                </p>
            </fieldset>
        </form>
    <?php
    return ob_get_clean();
}

// logs a member in after submitting a form
function crlfwnr_custom_login_member() {

    if(isset($_POST['custom_user_login']) && wp_verify_nonce($_POST['custom_login_nonce'], 'custom-login-nonce')) {

        // this returns the user ID and other info from the user name
        $user = get_userdatabylogin($_POST['custom_user_login']);

        if(!$user) {
            // if the user name doesn't exist
            crlfwnr_custom_errors()->add('empty_username', __('Invalid username'));
        }

        if(!isset($_POST['custom_user_pass']) || $_POST['custom_user_pass'] == '') {
            // if no password was entered
            crlfwnr_custom_errors()->add('empty_password', __('Please enter a password'));
        }

        // check the user's login with their password
        if(!wp_check_password($_POST['custom_user_pass'], $user->user_pass, $user->ID)) {
            // if the password is incorrect for the specified user
            crlfwnr_custom_errors()->add('empty_password', __('Incorrect password'));
        }

        $reg_options = get_option( 'reg_options' );
        $captcha_error = $reg_options['error_message'];
        $recaptcha=$_POST['g-recaptcha-response'];

        /*if(!empty($recaptcha))
        {

            $google_url="https://www.google.com/recaptcha/api/siteverify";
            $secret=$reg_options['secrete_key'];
            $ip=$_SERVER['REMOTE_ADDR'];
            $url=$google_url."?secret=".$secret."&response=".$recaptcha."&remoteip=".$ip;
            $res=getCurlData($url);
            $res= json_decode($res, true);
            if($res['success'])
            {}else{
             crlfwnr_custom_errors()->add('g-recaptcha-response', __($captcha_error));
            }
        }else{
          crlfwnr_custom_errors()->add('g-recaptcha-response', __($captcha_error));
        }*/
       if($reg_options['captcha_login']) {
            if (isset($_POST['g-recaptcha-response'])) {
            $recaptcha_secret = $reg_options['secrete_key'];
            $response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=". $recaptcha_secret ."&response=". $_POST['g-recaptcha-response']);
            $response = json_decode($response["body"], true);
            //echo "<pre>";
            //print_r($response);    exit;
            if (true == $response["success"]) {
               // return true;
            } else {
                 crlfwnr_custom_errors()->add('g-recaptcha-response', __($captcha_error));
                //return null;
            }
            } else {
                  crlfwnr_custom_errors()->add('g-recaptcha-response', __($captcha_error));
                //return null;
            }
       }


        // retrieve all error messages
        $errors = crlfwnr_custom_errors()->get_error_messages();

        // only log the user in if there are no errors
        if(empty($errors)) {

            wp_setcookie($_POST['custom_user_login'], $_POST['custom_user_pass'], true);
            wp_set_current_user($user->ID, $_POST['custom_user_login']);
            do_action('wp_login', $_POST['custom_user_login']);

            wp_redirect(home_url()); exit;
        }
    }
}
add_action('init', 'crlfwnr_custom_login_member');

// register a new user
function crlfwnr_custom_add_new_member() {
      if (isset( $_POST["custom_user_login"] ) && wp_verify_nonce($_POST['custom_register_nonce'], 'custom-register-nonce')) {
        $user_login        = $_POST["custom_user_login"];
        $user_email        = $_POST["custom_user_email"];
        $user_first     = $_POST["custom_user_first"];
        $user_last         = $_POST["custom_user_last"];
        $user_pass        = $_POST["custom_user_pass"];
        $pass_confirm     = $_POST["custom_user_pass_confirm"];

        // this is required for username checks
        require_once(ABSPATH . WPINC . '/registration.php');

        if(username_exists($user_login)) {
            // Username already registered
            crlfwnr_custom_errors()->add('username_unavailable', __('Username already taken'));
        }
        if(!validate_username($user_login)) {
            // invalid username
            crlfwnr_custom_errors()->add('username_invalid', __('Invalid username'));
        }
        if($user_login == '') {
            // empty username
            crlfwnr_custom_errors()->add('username_empty', __('Please enter a username'));
        }
        if(!is_email($user_email)) {
            //invalid email
            crlfwnr_custom_errors()->add('email_invalid', __('Invalid email'));
        }
        if(email_exists($user_email)) {
            //Email address already registered
            crlfwnr_custom_errors()->add('email_used', __('Email already registered'));
        }
        if($user_pass == '') {
            // passwords do not match
            crlfwnr_custom_errors()->add('password_empty', __('Please enter a password'));
        }
        if($user_pass != $pass_confirm) {
            // passwords do not match
            crlfwnr_custom_errors()->add('password_mismatch', __('Passwords do not match'));
        }

        $reg_options = get_option( 'reg_options' );
        $captcha_error = $reg_options['error_message'];
        $recaptcha=$_POST['g-recaptcha-response'];
          $flag=0;
        /*if(!empty($recaptcha))
        {
            $google_url="https://www.google.com/recaptcha/api/siteverify";
            $secret=$reg_options['secrete_key'];
            $ip=$_SERVER['REMOTE_ADDR'];
            $url=$google_url."?secret=".$secret."&response=".$recaptcha."&remoteip=".$ip;
            $res=getCurlData($url);
            //$res= json_decode($res, true);
            echo "<pre>";
            print_r($res);
            if($res)
            {
                $flag=1;
            }else{
                echo "hello11111";  exit;
             crlfwnr_custom_errors()->add('g-recaptcha-response', __($captcha_error));
            }
        }elseif($flag!=1){
            echo $flag."hello222222"; exit;
            crlfwnr_custom_errors()->add('g-recaptcha-response', __($captcha_error));
        } */
       if($reg_options['captcha_registration']) {
            if (isset($_POST['g-recaptcha-response'])) {
            $recaptcha_secret = $reg_options['secrete_key'];
            $response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=". $recaptcha_secret ."&response=". $_POST['g-recaptcha-response']);
            $response = json_decode($response["body"], true);
            //echo "<pre>";
            //print_r($response);    exit;
            if (true == $response["success"]) {
               // return true;
            } else {
                 crlfwnr_custom_errors()->add('g-recaptcha-response', __($captcha_error));
                //return null;
            }
            } else {
                  crlfwnr_custom_errors()->add('g-recaptcha-response', __($captcha_error));
                //return null;
            }
       }

        //echo $msg;

        $errors = crlfwnr_custom_errors()->get_error_messages();

        // only create the user in if there are no errors
        if(empty($errors)) {

            $new_user_id = wp_insert_user(array(
                    'user_login'        => $user_login,
                    'user_pass'             => $user_pass,
                    'user_email'        => $user_email,
                    'first_name'        => $user_first,
                    'last_name'            => $user_last,
                    'user_registered'    => date('Y-m-d H:i:s'),
                    'role'                => 'subscriber'
                )
            );
            if($new_user_id) {
                // send an email to the admin alerting them of the registration
                wp_new_user_notification($new_user_id);

                // log the new user in
                wp_setcookie($user_login, $user_pass, true);
                wp_set_current_user($new_user_id, $user_login);
                do_action('wp_login', $user_login);

                // send the newly created user to the home page after logging them in
                wp_redirect(home_url()); exit;
            }

        }

    }
}
add_action('init', 'crlfwnr_custom_add_new_member');

// used for tracking error messages
function crlfwnr_custom_errors(){
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function crlfwnr_custom_show_error_messages() {
    if($codes = crlfwnr_custom_errors()->get_error_codes()) {
        echo '<div class="crlfwnr_custom_errors">';
            // Loop error codes and display errors
           foreach($codes as $code){
                $message = crlfwnr_custom_errors()->get_error_message($code);
                echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
            }
        echo '</div>';
    }
}

// register our form css
function crlfwnr_custom_register_css() {
    wp_register_style('custom-form-css', plugin_dir_url( __FILE__ ) . '/css/forms.css');
}
add_action('init', 'crlfwnr_custom_register_css');

// load our form css
function crlfwnr_custom_print_css() {
    global $custom_load_css;

    // this variable is set to TRUE if the short code is used on a page/post
    if ( ! $custom_load_css )
        return; // this means that neither short code is present, so we get out of here

    wp_print_styles('custom-form-css');
}
add_action('wp_footer', 'crlfwnr_custom_print_css');
