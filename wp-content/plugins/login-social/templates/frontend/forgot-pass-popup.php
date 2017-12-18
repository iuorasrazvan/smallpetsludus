<?php
/**
 * Forgot Password Popup Template
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="hg-login-modal-content hg-login-layout hg-login-vertical">
    <div class="hg-login-modal-header hg-login-layout">
        <h4 class="bold"><?php echo HG_Login()->settings->forgotpass_popup_title; ?></h4>
        <div class="hg-login-modal-close">
            <div class="type-button type-light view-flat shape-circle">
                <div class="button--content -layout -horizontal -center-center -space-h-8">
                        <span class="icon">
                            <svg width="24" height="24" viewBox="0 0 24 24"><path
                                    d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path></svg>
                        </span>
                </div>
            </div>
        </div>
    </div>
    <div class="hg-login-modal-body">
        <div class="hg-login-modal-body-content">
            <div class="hg-login-modal-inputs">
                <div class="hg-login-modal-input">
                    <input type="text" name="login" data-type="login" id="hg-login-modal-user-login" required="required" autocomplete="off"/>
                    <label
                        for="hg-login-modal-user-login"><?php _e( "Username or Email", 'hg_login' ); ?></label>
                    <span></span>
                </div>
            </div>
            <?php if(HG_Login()->settings->recaptcha_enabled === 'yes'): ?>
                <div class="hg-login-modal-recaptcha-wrap">
                    <div class="hg-login-modal-recaptcha" data-sitekey="<?php echo HG_Login()->settings->recaptcha_key; ?>" data-theme="<?php echo HG_Login()->settings->recaptcha_theme; ?>"></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="hg-login-modal-footer">
        <div class="-layout -flex -center">
            <div id="hg-login-button-open-login-popup" class="-button -size-24 -view-flat hg_login_open_login_button forgot-login">
                <div class="button--content"><?php _e( "Login", "hg_login" ); ?></div>
            </div>
            <div class="-flex"></div>
            <div id="hg-login-button-forgotpass-submit" class="-button -type-primary -size-32 -view-flat">
                <div class="button--content -layout -horizontal -center-center -space-h-8"><?php _e( "Send", "hg_login" ); ?></div>
            </div>
        </div>
    </div>
</div>