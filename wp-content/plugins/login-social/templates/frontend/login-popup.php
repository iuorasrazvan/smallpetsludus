<?php
/**
 * Login Popup Template
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="hg-login-modal-content hg-login-layout hg-login-vertical">
    <div class="hg-login-modal-header hg-login-layout">
        <h4 class="hg-login-modal-title bold"><?php echo HG_Login()->settings->login_popup_title; ?></h4>
        <?php if( HG_Login()->settings->login_popup_subtitle != '' ): ?>
            <h3 class="hg-login-modal-subtitle" ><?php echo HG_Login()->settings->login_popup_subtitle; ?></h3>
        <?php endif; ?>
        <div class="hg-login-modal-close">
            <div class="type-button type-light view-flat shape-circle">
                <div class="button--content -layout -horizontal -center-center -space-h-8">
                    <span class="icon">
                        <svg width="24" height="24" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path></svg>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="hg-login-modal-body">
        <div class="hg-login-modal-body-content">
            <div class="hg-login-modal-inputs">
                <div class="hg-login-modal-input">
                    <input type="text" name="log" id="hg-login-modal-user-login" required="required"/>
                    <label for="hg-login-modal-user-login"><?php _e( "Username or Email", 'hg_login' ); ?></label>
                    <span></span>
                </div>
                <div class="hg-login-modal-input">
                    <input type="password" name="pwd" id="hg-login-modal-user-pass" required="required"/>
                    <label for="hg-login-modal-user-pass"><?php _e( "Password", 'hg_login' ); ?></label>
                    <span></span>
                    <i></i>
                </div>
                <div class="--flex -justify-between">
                    <div class="hg-login-modal-input -inline-flex">
                        <input type="checkbox" name="rememberme" id="hg-login-modal-rememberme" value="true" />
                        <label for="hg-login-modal-rememberme"><span></span><?php _e( "Remember me", 'hg_login' ); ?></label>
                    </div>
                    <span class="modal-auth--forgot-password"><?php _e( 'Forgot Password', 'hg_login' ); ?></span>
                </div>

            </div>
            <?php if(HG_Login()->settings->recaptcha_enabled === 'yes'): ?>
                <div class="hg-login-modal-recaptcha-wrap">
                    <div class="hg-login-modal-recaptcha" data-sitekey="<?php echo HG_Login()->settings->recaptcha_key; ?>" data-theme="<?php echo HG_Login()->settings->recaptcha_theme; ?>"></div>
                </div>
            <?php endif; ?>
            <div class="-center-center -layout">
                <div id="hg-login-button-login-submit" class="-button -type-primary -size-32 -view-flat">
                    <div class="button--content -layout -horizontal -center-center -space-h-8"><?php _e( "Log In", "hg_login" ); ?></div>
                </div>
            </div>
            <?php
            $social_enable_both=false;
            if(HG_Login()->settings->facebook_enabled === 'yes' && HG_Login()->settings->google_sign_in_enabled === 'yes'){
                $social_title = __('Sign up via Facebook or G+','hg_login');
                $social_enable_both = true;
            }elseif(HG_Login()->settings->facebook_enabled === 'yes'){
                $social_title = __('Sign up via Facebook','hg_login');
            }elseif(HG_Login()->settings->google_sign_in_enabled === 'yes'){
                $social_title = __('Sign up via G+','hg_login');
            }else{
                $social_title ='';
            }

            if(!empty($social_title)):
                ?>
                <div class="hg-login-social-buttons <?php if($social_enable_both) echo 'hg_enabl_both'; ?>">
                    <div class="hg-login-via-social"><?php echo $social_title; ?></div>
                    <?php if(HG_Login()->settings->facebook_enabled === 'yes'): ?>
                        <div class="-layout login">
                            <div class="modal-auth--social-button -flex ">
                                <a href="<?php echo hg_login_get_fb_login_link(); ?>" class="-facebook center-center">
                                    <span class="icon -size-24">
                                        <svg width="24" height="24" viewBox="0 0 48 48"><path d="M19.135 44.392V24.97H14v-6.994h5.135v-5.973C19.135 7.31 22.17 3 29.16 3c2.83 0 4.922.27 4.922.27l-.165 6.532s-2.134-.02-4.463-.02c-2.52 0-2.925 1.16-2.925 3.09v5.105h7.587l-.33 6.993H26.53v19.422h-7.395z"></path></svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    <?php endif;
                    if(HG_Login()->settings->google_sign_in_enabled === 'yes'): ?>
                        <div class="-layout login">
                            <div class="modal-auth--social-button -flex ">
                                <div class="modal-auth--social-button -flex ">
                                    <div class="hg-login-google-button">
                                        <div class="hg-g-icon"></div>
                                        <div class="hg-g-text"><?php _e('Sign in','hg_login'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if( get_option( 'users_can_register' ) ): ?>
        <div class="hg-login-modal-footer">
            <div class="-layout -flex -center">
                <span><?php _e( 'Don\'t you have an account yet?', 'hg_login' ); ?></span>
                <div id="hg-login-button-open-signup-popup" class="-button -size-24 -view-flat hg_login_open_signup_button">
                    <div class="button--content"><?php _e( "Sign up now!", "hg_login" ); ?></div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
