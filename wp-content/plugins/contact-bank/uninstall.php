<?php
/**
 * This file is used for plugin uninstall.
 *
 * @author   Tech Banker
 * @package  contact-bank
 * @version  3.0
 */
if (!defined('WP_UNINSTALL_PLUGIN')) {
   die;
}
if (!current_user_can("manage_options")) {
   return;
} else {
   global $wpdb;
    if (is_multisite()) {
        $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
        foreach ($blog_ids as $blog_id) {
            switch_to_blog($blog_id);
            $contact_bank_version_number = get_option("contact-bank-version-number");
            if ($contact_bank_version_number != "") {
               global $wpdb;

               $get_other_settings = $wpdb->get_var
                   (
                   $wpdb->prepare
                       ("SELECT meta_value from " . $wpdb->prefix . "contact_bank_meta WHERE meta_key = %s", "general_settings")
               );
               $get_other_settings_data = maybe_unserialize($get_other_settings);
               if ($get_other_settings_data["remove_tables_at_uninstall"] == "enable") {
                  $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "contact_bank");
                  $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "contact_bank_meta");

                  delete_option("contact-bank-version-number");
                  delete_option("contact-bank-wizard-set-up");
                  delete_option("cb_admin_notice");
               }
            }
            restore_current_blog();
        }
    }
    else
    {
        $contact_bank_version_number = get_option("contact-bank-version-number");
        if ($contact_bank_version_number != "") {
           global $wpdb;

           $get_other_settings = $wpdb->get_var
               (
               $wpdb->prepare
                   ("SELECT meta_value from " . $wpdb->prefix . "contact_bank_meta WHERE meta_key = %s", "general_settings")
           );
           $get_other_settings_data = maybe_unserialize($get_other_settings);
           if ($get_other_settings_data["remove_tables_at_uninstall"] == "enable") {
              $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "contact_bank");
              $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "contact_bank_meta");

              delete_option("contact-bank-version-number");
              delete_option("contact-bank-wizard-set-up");
              delete_option("cb_admin_notice");
           }
        }
    }
}
