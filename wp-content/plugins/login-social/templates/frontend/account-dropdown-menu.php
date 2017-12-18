<?php
/**
 * Account dropdown menu template
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if( !isset( $menu_items ) || !is_array( $menu_items ) || empty( $menu_items ) ){
    return false;
}
?>
<?php do_action('hg_login_before_account_dropdown_menu',$menu_items); ?>
<div class="hg-login-account-dropdown-menu">
    <?php

    $count = count( $menu_items['titles'] );

    for( $i = 0; $i < $count; $i++ ){
        $html_class = '';
        $title = $menu_items['titles'][$i];
        $link = $menu_items['links'][$i];
        switch( $menu_items['links'][$i] ){
            case '{logout_link}':
                $html_class = 'hg-login-button-logout';
                $link = esc_url( wp_nonce_url( site_url( 'wp-login.php?action=logout' ),'log-out' ) );
                break;
            case '{profile_link}':
                $user_id      = get_current_user_id();
                if ( current_user_can( 'read' ) ) {
                    $link = get_edit_profile_url( $user_id );
                } elseif ( is_multisite() ) {
                    $link = get_dashboard_url( $user_id, 'profile.php' );
                } else {
                    $link = false;
                }
                break;
            case '':
                $link = '&#35;';
                break;
        }

        $class = 'class="' . $html_class . '"';
        echo "<div class='hg-login-dropdown-item' ><a href='". $link ."' $class >" . $title . "</a></div>";
    }

    ?>
</div>
<?php do_action('hg_login_after_account_dropdown_menu',$menu_items); ?>
