<?php do_action('hg_login_dashboard_head'); ?>
    <h1><?php _e('Dashboard','hg_login'); ?></h1>
    <h2 class="hg-mui-notice"><?php _e('This is the dashboard of your Login plugin, where you can find additional information for Login and Sign Up button and their shortcodes as well as use customizer. ','hg_login') ?></h2>
    <div class="hg-mui-cluster">
        <div class="hg-mui-card animated zoomIn">
            <div class="hg-mui-card-wrap">
                <div class="hg-mui-card-text-field">
                    <h3 class="hg-mui-card-title"><?php _e('Login Button', 'hg_login'); ?></h3>
                    <div class="hg-mui-card-content">
                        <p><?php _e( 'This is the shortcode to display the login button in posts/pages', 'hg_login' ); ?></p>
                        <p><code class="hg-mui-card-outlined">[hg-login-button]</code></p>
                        <p><b><?php _e( 'Parameters', 'hg_login' ); ?></b></p>
                        <p><i>text</i> - <?php _e( 'This is the text that shall be displayed inside your login button (default value is defined in settings of your plugin)', 'hg_login' ); ?></p>
                        <p><i>show_menu</i> - <?php _e( 'Whether to show account specific menu instead of login button when user is logged in, default value is "no"', 'hg_login' ); ?></p>
                        <p><b>Example</b></p>
                        <p><?php _e('Copy & paste the shortcode directly into any WordPress post or page', 'hg_login' ); ?></p>
                        <p><code>[hg-login-button text="Login" show_menu="yes"]</code></p>
                        <p><?php _e( 'Or copy & paste this code into a template file', 'hg_login' ); ?></p>
                        <p><code>&lt;&#63;php echo do_shortcode('[hg-login-button text="Login" show_menu="yes"]'); &#63;&gt;</code></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="hg-mui-card animated zoomIn">
            <div class="hg-mui-card-wrap">
                <div class="hg-mui-card-text-field">
                    <h3 class="hg-mui-card-title"><?php _e('Sign Up Button', 'hg_login'); ?></h3>
                    <div class="hg-mui-card-content">
                        <p><?php _e( 'This is the shortcode to display the sign up button in posts/pages', 'hg_login' ); ?></p>
                        <p><code class="hg-mui-card-outlined">[hg-signup-button]</code></p>
                        <p><b><?php _e( 'Parameters', 'hg_login' ); ?></b></p>
                        <p><i>text</i> - <?php _e( 'This is the text that shall be displayed inside your sign up button (default value is defined in settings of your plugin)', 'hg_login' ); ?></p>
                        <p><i>show_menu</i> - <?php _e( 'Whether to show account specific menu instead of sign up button when user is logged in, default value is "no"', 'hg_login' ); ?></p>
                        <p><b>Example</b></p>
                        <p><?php _e('Copy & paste the shortcode directly into any WordPress post or page', 'hg_login' ); ?></p>
                        <p><code>[hg-signup-button text="Sign up" show_menu="yes"]</code></p>
                        <p><?php _e( 'Or copy & paste this code into a template file', 'hg_login' ); ?></p>
                        <p><code>&lt;&#63;php echo do_shortcode('[hg-signup-button text="Sign up" show_menu="yes"]'); &#63;&gt;</code></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="hg-mui-card animated zoomIn">
            <div class="hg-mui-card-wrap">
                <div class="hg-mui-card-text-field">
                    <h3 class="hg-mui-card-title"><?php _e('Customize', 'hg_login'); ?></h3>
                    <div class="hg-mui-card-content">
                        <p><?php _e('You may customize the login plugin in order to adjust its components to your needs.','hg_login'); ?></p>
                        <p><?php _e('You can create your own design, activate social media accounts, make further security configurations and more, using settings of the plugin.', 'hg_login'); ?></p>
                        <a class="button-primary" href="<?php echo admin_url( 'admin.php?page=hg_login_settings' ); ?>"><?php _e('Go To Settings','hg_login'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php do_action('hg_login_dashboard_footer'); ?>