<?php
/**
 * This file is used for fetching data from database.
 *
 * @author	Tech Banker
 * @package	contact-bank/includes
 * @version	3.0
 */
if (!defined("ABSPATH")) {
   exit;
} // Exit if accessed directly
if (!is_user_logged_in()) {
   return;
} else {
   $access_granted = false;
   foreach ($user_role_permission as $permission) {
      if (current_user_can($permission)) {
         $access_granted = true;
         break;
      }
   }
   if (!$access_granted) {
      return;
   } else {
      function get_meta_value_contact_bank($meta_key) {
         global $wpdb;
         $meta_value = $wpdb->get_var
             (
             $wpdb->prepare
                 (
                 "SELECT meta_value FROM " . contact_bank_meta() . " WHERE meta_key = %s", $meta_key
             )
         );
         return maybe_unserialize($meta_value);
      }
      if (isset($_REQUEST["cb_preview_form"])) {
         $id = intval($_REQUEST["cb_preview_form"]);
         $selected_form_value = $wpdb->get_var
             (
             $wpdb->prepare
                 (
                 "SELECT meta_value FROM " . contact_bank_meta() . " WHERE old_form_id = %d and meta_key = %s", $id, "form_data"
             )
         );
         $selected_general_setting_value = $wpdb->get_var
             (
             $wpdb->prepare
                 (
                 "SELECT meta_value FROM " . contact_bank_meta() . " WHERE meta_key = %s", 'general_settings'
             )
         );
         $id_count = $wpdb->get_var(
             $wpdb->prepare("SELECT count(meta_id) FROM " . contact_bank_meta() . " WHERE meta_key = %s and meta_id = %d", "submission_form_data", $id)
         );
         $selected_general_setting_unserialize = maybe_unserialize($selected_general_setting_value);
         $form_unserialized_meta_value = maybe_unserialize($selected_form_value);
         $layout_setting_form_unserialize_data = $wpdb->get_var(
             $wpdb->prepare("SELECT meta_value FROM " . contact_bank_meta() . " WHERE meta_key = %s and old_form_id = %d", "layout_settings", $id)
         );
         $layout_setting_form_data = maybe_unserialize($layout_setting_form_unserialize_data);
         $custom_css = get_meta_value_contact_bank("custom_css");
      }
      function get_contact_bank_form_data($id) {
         global $wpdb;
         $selected_form_value = $wpdb->get_var
             (
             $wpdb->prepare
                 (
                 "SELECT meta_value FROM " . contact_bank_meta() . " WHERE old_form_id = %d && meta_key = %s", $id, "form_data"
             )
         );
         $contact_bank_unserialized_meta_value = maybe_unserialize($selected_form_value);
         return $contact_bank_unserialized_meta_value;
      }
      function get_contact_dashboard_bank_data($meta_key, $meta_id) {
         global $wpdb;
         $get_contact_bank = $wpdb->get_results
             (
             $wpdb->prepare
                 (
                 "SELECT *  FROM " . contact_bank_meta() . "
                                    INNER JOIN " . contact_bank() . " ON " . contact_bank_meta() . ".meta_id = " . contact_bank() . ".id
                                    WHERE " . contact_bank() . ".type = %s and " . contact_bank_meta() . ".meta_key = %s ORDER BY meta_id DESC", "form", "form_data"
             )
         );
         $unserialized_forms_data_array = array();
         foreach ($get_contact_bank as $key) {
            $unserialized_data = array();
            $unserialized_data = maybe_unserialize($key->meta_value);
            $unserialized_data["old_form_id"] = $key->old_form_id;
            $unserialized_data["id"] = $key->id;
            $unserialized_data["meta_key"] = $key->meta_key;
            $unserialized_data["meta_id"] = $key->meta_id;
            array_push($unserialized_forms_data_array, $unserialized_data);
         }
         return $unserialized_forms_data_array;
      }
      $check_contact_bank_wizard = get_option("contact-bank-wizard-set-up");
      $check_url = $check_contact_bank_wizard == "" ? "cb_wizard_contact_bank" : (isset($_GET["page"]) ? esc_attr($_GET["page"]) : "");
      if (isset($_GET["page"])) {
         switch ($check_url) {
            case "contact_dashboard" :
               $unserialized_forms_data_array = get_contact_dashboard_bank_data("form", "form_data");
               break;
            case "cb_add_new_form":
               $publish_pages = $wpdb->get_results
                   (
                   "SELECT ID,post_name FROM " . $wpdb->posts . " WHERE (post_type = \"page\" OR post_type=\"post\") AND post_status = \"publish\""
               );
               if (isset($_REQUEST["form_id"])) {
                  $id = intval($_REQUEST["form_id"]);
                  $form_unserialized_meta_value = get_contact_bank_form_data($id);
               }
               break;
            case "cb_layout_settings":
               if (isset($_REQUEST["form_id"])) {
                  $form_id = $_REQUEST["form_id"];
                  global $wpdb;
                  $unserialized_data_forms = $wpdb->get_var
                      (
                      $wpdb->prepare
                          (
                          "SELECT meta_value FROM " . contact_bank_meta() . " WHERE old_form_id = %d and meta_key = %s", $form_id, "layout_settings"
                      )
                  );
                  $layout_settings_data = unserialize($unserialized_data_forms);
               }
               $unserialized_layouts_forms_data_array = get_contact_dashboard_bank_data("form", "form_data");
               break;
            case "cb_custom_css":
               $details_custom_css = get_meta_value_contact_bank("custom_css");
               break;
            case "cb_email_templates":
               $unserialized_forms_data_array = get_contact_dashboard_bank_data("form", "form_data");
               $template_type = isset($_REQUEST["template_type"]) ? esc_attr($_REQUEST["template_type"]) : "form_admin_notification_email";
               if (isset($_REQUEST["form_id"])) {
                  $form_id = intval($_REQUEST["form_id"]);
                  $contact_forms_data = $wpdb->get_row
                      (
                      $wpdb->prepare
                          (
                          "SELECT *  FROM " . contact_bank_meta() . " WHERE meta_id = %d and meta_key = %s", $form_id, "form_data"
                      )
                  );
                  $unserialized_data_forms = unserialize($contact_forms_data->meta_value);
               }
               break;
            case "cb_general_settings":
               $details_general_settings = get_meta_value_contact_bank("general_settings");
               break;
            case "cb_submissions":
               $details_form_controls = array();
               $details_form_submissions = array();
               if (isset($_REQUEST["form_id"])) {
                  $end_date = CONTACT_BANK_LOCAL_TIME;
                  $date2 = $end_date + 86400;
                  $date1 = $date2 - 2678340;
                  $id = intval($_REQUEST["form_id"]);
                  $details_form_controls = get_contact_bank_form_data($id);
                  $get_contact_bank = $wpdb->get_results
                      (
                      $wpdb->prepare
                          (
                          "SELECT *  FROM " . contact_bank_meta() . " WHERE meta_key = %s  and old_form_id = %d ORDER BY id DESC", "submission_form_data", $id
                      )
                  );
                  foreach ($get_contact_bank as $key) {
                     $unserialized_data = array();
                     $unserialized_data = maybe_unserialize($key->meta_value);
                     $unserialized_data["id"] = $key->id;
                     $unserialized_data["meta_key"] = $key->meta_key;
                     $unserialized_data["meta_id"] = $key->meta_id;
                     if ($unserialized_data["timestamp"] >= $date1 && $unserialized_data["timestamp"] <= $date2) {
                        array_push($details_form_submissions, $unserialized_data);
                     }
                  }
               }
               $unserialized_forms_data_array = get_contact_dashboard_bank_data("form", "form_data");
               break;

            case "cb_roles_and_capabilities":
               $details_roles_capabilities = get_meta_value_contact_bank("roles_and_capabilities");
               $other_roles_array = $details_roles_capabilities["capabilities"];
               break;
         }
      }
   }
}