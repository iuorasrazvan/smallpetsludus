<?php
/**
 * Signup Button Template
 *
 * @var $text string
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

do_action( "hg_login_before_signup_button_wrapper" ); ?>

	<div class="hg_signup_button_wrapper -inline-block">

		<?php do_action( "hg_login_before_signup_button" ); ?>

		<?php if ( ! get_option( "users_can_register" ) ) {
			printf( '<span class="hg_login_notice">%s</span>', __( "Registration is disabled for this website", "hg_login" ) );
		} else { ?>
			<div id="hg_signup_primary_button" class="hg-signup-button -primary hg_login_open_signup_button"><?php echo esc_html($text); ?></div>
		<?php } ?>

		<?php do_action( "hg_login_after_signup_button" ); ?>

	</div>

<?php
do_action( "hg_login_after_signup_button_wrapper" );