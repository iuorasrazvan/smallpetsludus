<?php
/**
 * Password Lost - Full Width Page Template
 * @since 1.0
 */

// If accessed directly, abort
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_head();
?>

<body class="yikes-custom-page-template">
	<div id="yikes-custom-user-registration-template" class="yikes-custom-page-template-interior">

		<?php do_action( 'yikes-custom-login-password-lost-page-top' ); ?>

		<div class="page-container">

			<?php do_action( 'yikes-custom-login-branding' ); ?>

			<div class="interior yikes-animated yikes-fadeIn">
				<!-- Display the lost password form -->
				<?php
					do_action( 'yikes-custom-login-password-lost-page-before-form' );
					echo do_shortcode( '[custom-password-lost-form]' );
					do_action( 'yikes-custom-login-password-lost-page-after-form' );
				?>

				<!-- Preloader -->
				<div class="preloader-container">
					<img src="<?php echo apply_filters( 'yikes-custom-login-preloader', esc_url( admin_url( 'images/wpspin_light.gif' ) ) ); ?>" title="preloader" class="login-preloader" />
				</div>
			</div>

		</div>

		<?php do_action( 'yikes-custom-login-password-lost-page-bottom' ); ?>

	</div>
</body>

<?php wp_footer(); ?>
