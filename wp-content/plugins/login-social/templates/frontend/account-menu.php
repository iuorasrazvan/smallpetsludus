<?php
/**
 * Account menu template
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$user_id      = get_current_user_id();
$current_user = wp_get_current_user();
$avatar = get_avatar( $user_id, 48 );
$howdy  = sprintf( __('Hey, %1$s'), $current_user->display_name );
if ( current_user_can( 'read' ) ) {
    $profile_url = get_edit_profile_url( $user_id );
} elseif ( is_multisite() ) {
    $profile_url = get_dashboard_url( $user_id, 'profile.php' );
} else {
    $profile_url = false;
}
?>

<?php do_action( 'hg_login_before_account_menu' ); ?>

<div class="hg-login-account-menu">
    <div class="hg-login-account-info">
        <?php
        $notify = '';
        if( function_exists( 'buddypress' ) ){

            $notifications_count = bp_notifications_get_unread_notification_count();

            $notifications_text = sprintf( __('You have %s unread notifications','hg_login'), $notifications_count );

            $notify = '<span class="hg-login-user-notifications-info"><span title="'.$notifications_text.'" class="hg-login-user-notifications-info-count">'.$notifications_count.'</span></span>';
        }
        ?>
        <a class="hg-login-account-main" href="<?php echo $profile_url; ?>"><span class="hg-login-account-main-howdy" ><?php echo $howdy; ?></span><?php echo $avatar.$notify; ?></a>
        <?php do_action('hg_login_after_account_maininfo',$user_id); ?>
        <?php hg_login_current_user_dropdown_menu(); ?>
    </div>
</div>

<?php do_action( 'hg_login_after_account_menu' ); ?>
