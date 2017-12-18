<?php
/**
 * This file is used for frontend ajax.
 *
 * @author	Tech Banker
 * @package	contact-bank/user-views/lib
 * @version	3.0
 */
if (!defined("ABSPATH")) {
   exit;
}
if (isset($_REQUEST["param"])) {
   $obj_user_helper_contact_bank = new user_helper_contact_bank();
   switch (esc_attr($_REQUEST["param"])) {
      case "frontend_form_module":
         parse_str(isset($_REQUEST["data"]) ? base64_decode($_REQUEST["data"]) : "", $form_data);
         $form_id = isset($_REQUEST["form_id"]) ? intval($_REQUEST["form_id"]) : "";
         $controls_ids = isset($_REQUEST["controls_ids"]) ? array_map("esc_attr", is_array(json_decode(stripslashes($_REQUEST["controls_ids"]))) ? json_decode(stripslashes($_REQUEST["controls_ids"])) : array()) : array();
         $submission_limit_number = isset($_REQUEST["submission_limit"]) ? intval($_REQUEST["submission_limit"]) : "";
         $submission_limit = isset($_REQUEST["submission_enable_disable"]) ? sanitize_text_field($_REQUEST["submission_enable_disable"]) : "";
         $form_record = array();
         $id_count = $wpdb->get_var(
             $wpdb->prepare("SELECT count(meta_id) FROM " . contact_bank_meta() . " WHERE meta_key = %s and old_form_id = %d", "submission_form_data", $form_id)
         );
         $selected_form_value = $wpdb->get_var
             (
             $wpdb->prepare("SELECT meta_value FROM " . contact_bank_meta() . " WHERE old_form_id = %d and meta_key = %s", $form_id, "form_data")
         );
         $contact_bank_meta_value = maybe_unserialize($selected_form_value);
         $selected_general_setting_value = $wpdb->get_var
             (
             $wpdb->prepare
                 (
                 "SELECT meta_value FROM " . contact_bank_meta() . " WHERE meta_key = %s", 'general_settings'
             )
         );
         $selected_general_setting_unserialize = maybe_unserialize($selected_general_setting_value);

         // Admin Notification
         $form_admin_notification_email = $contact_bank_meta_value["form_admin_notification_email"];
         $admin_email_to = $form_admin_notification_email["template_send_to"] == "send_to_email" ? $form_admin_notification_email["template_send_to_email"] : $form_admin_notification_email["template_send_to_field"];
         $admin_email_from_name = $form_admin_notification_email["template_from_name"];
         $admin_email_from = $form_admin_notification_email["template_from_email"];
         $admin_email_cc = $form_admin_notification_email["template_cc"];
         $admin_email_bcc = $form_admin_notification_email["template_bcc"];
         $admin_email_reply_to = $form_admin_notification_email["template_reply_to"];
         $admin_email_subject = htmlspecialchars_decode($form_admin_notification_email["template_subject"], ENT_QUOTES);
         $admin_email_message = $form_admin_notification_email["template_message"];

         // Client Notification
         $form_client_notification_email = $contact_bank_meta_value["form_client_notification_email"];
         $client_email_to = $form_client_notification_email["template_send_to"] == "send_to_email" ? $form_client_notification_email["template_send_to_email"] : $form_client_notification_email["template_send_to_field"];
         $client_email_from_name = $form_client_notification_email["template_from_name"];
         $client_email_from = $form_client_notification_email["template_from_email"];
         $client_email_cc = $form_client_notification_email["template_cc"];
         $client_email_bcc = $form_client_notification_email["template_bcc"];
         $client_email_reply_to = $form_client_notification_email["template_reply_to"];
         $client_email_subject = htmlspecialchars_decode($form_client_notification_email["template_subject"], ENT_QUOTES);
         $client_email_message = $form_client_notification_email["template_message"];
         $attachment_file = array();
         foreach ($controls_ids as $control_id) {
            if ($form_data["ux_txt_hidden_control_type_" . $control_id] == "checkbox") {
               $label_name = $control_id;
               $form_record[$label_name] = isset($form_data["ux_txt_singal_line_text_" . $control_id]) ? "checked" : "unchecked";
               // Admin Notification
               $admin_email_to = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $admin_email_to);
               $admin_email_from_name = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $admin_email_from_name);
               $admin_email_from = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $admin_email_from);
               $admin_email_cc = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $admin_email_cc);
               $admin_email_bcc = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $admin_email_bcc);
               $admin_email_reply_to = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $admin_email_reply_to);
               $admin_email_subject = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $admin_email_subject);
               $admin_email_message = str_replace("[control_" . $control_id . "]", $form_record[$label_name] == "" ? "N/A" : $form_record[$label_name], $admin_email_message);

               // Client Notification
               $client_email_to = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $client_email_to);
               $client_email_from_name = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $client_email_from_name);
               $client_email_from = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $client_email_from);
               $client_email_cc = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $client_email_cc);
               $client_email_bcc = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $client_email_bcc);
               $client_email_reply_to = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $client_email_reply_to);
               $client_email_subject = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $client_email_subject);
               $client_email_message = str_replace("[control_" . $control_id . "]", $form_record[$label_name] == "" ? "N/A" : $form_record[$label_name], $client_email_message);
            } else if ($form_data["ux_txt_hidden_control_type_" . $control_id] == "checkbox-list" || $form_data["ux_txt_hidden_control_type_" . $control_id] == "radio-list") {
               $label_name = $control_id;
               if ($form_data["ux_txt_hidden_control_type_" . $control_id] == "checkbox-list") {
                  $form_record[$label_name] = isset($form_data["ux_txt_check_box_lists_" . $control_id]) ? implode(",", $form_data["ux_txt_check_box_lists_" . $control_id]) : "";
               } else {
                  $form_record[$label_name] = isset($form_data["ux_txt_check_box_lists_" . $control_id]) ? sanitize_text_field($form_data["ux_txt_check_box_lists_" . $control_id]) : "";
               }
               // Admin Notification
               $admin_email_to = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $admin_email_to);
               $admin_email_from_name = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $admin_email_from_name);
               $admin_email_from = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $admin_email_from);
               $admin_email_cc = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $admin_email_cc);
               $admin_email_bcc = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $admin_email_bcc);
               $admin_email_reply_to = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $admin_email_reply_to);
               $admin_email_subject = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $admin_email_subject);
               $admin_email_message = str_replace("[control_" . $control_id . "]", $form_record[$label_name] == "" ? "N/A" : $form_record[$label_name], $admin_email_message);

               // Client Notification
               $client_email_to = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $client_email_to);
               $client_email_from_name = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $client_email_from_name);
               $client_email_from = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $client_email_from);
               $client_email_cc = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $client_email_cc);
               $client_email_bcc = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $client_email_bcc);
               $client_email_reply_to = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $client_email_reply_to);
               $client_email_subject = str_replace("[control_" . $control_id . "]", $form_record[$label_name], $client_email_subject);
               $client_email_message = str_replace("[control_" . $control_id . "]", $form_record[$label_name] == "" ? "N/A" : $form_record[$label_name], $client_email_message);
            } else if (isset($form_data["ux_txt_singal_line_text_" . $control_id])) {
               $label_name = $control_id;
               $form_record[$label_name] = $form_data["ux_txt_singal_line_text_" . $control_id];
               $email_content = $form_record[$label_name];

               // Admin Notification
               $admin_email_to = str_replace("[control_" . $control_id . "]", $email_content, $admin_email_to);
               $admin_email_from_name = str_replace("[control_" . $control_id . "]", $email_content, $admin_email_from_name);
               $admin_email_from = str_replace("[control_" . $control_id . "]", $email_content, $admin_email_from);
               $admin_email_cc = str_replace("[control_" . $control_id . "]", $email_content, $admin_email_cc);
               $admin_email_bcc = str_replace("[control_" . $control_id . "]", $email_content, $admin_email_bcc);
               $admin_email_reply_to = str_replace("[control_" . $control_id . "]", $email_content, $admin_email_reply_to);
               $admin_email_subject = str_replace("[control_" . $control_id . "]", $email_content, $admin_email_subject);
               $admin_email_message = str_replace("[control_" . $control_id . "]", $email_content == "" ? "N/A" : $email_content, $admin_email_message);

               // Client Notification
               $client_email_to = str_replace("[control_" . $control_id . "]", $email_content, $client_email_to);
               $client_email_from_name = str_replace("[control_" . $control_id . "]", $email_content, $client_email_from_name);
               $client_email_from = str_replace("[control_" . $control_id . "]", $email_content, $client_email_from);
               $client_email_cc = str_replace("[control_" . $control_id . "]", $email_content, $client_email_cc);
               $client_email_bcc = str_replace("[control_" . $control_id . "]", $email_content, $client_email_bcc);
               $client_email_reply_to = str_replace("[control_" . $control_id . "]", $email_content, $client_email_reply_to);
               $client_email_subject = str_replace("[control_" . $control_id . "]", $email_content, $client_email_subject);
               $client_email_message = str_replace("[control_" . $control_id . "]", $form_record[$label_name] == "" ? "N/A" : $form_record[$label_name], $client_email_message);
            }
         }
         // Admin Notification
         $admin_body_content = "";
         $admin_body_content .= "<div lang=\"ar\"> $admin_email_message</div>";
         $admin_headers = "";
         $admin_headers .= "Content-Type: text/html; charset= utf-8" . "\r\n";
         if ($admin_email_from_name != "" && $admin_email_from != "") {
            $admin_headers .= "From: " . $admin_email_from_name . " <" . $admin_email_from . ">" . "\r\n";
         }
         if ($admin_email_reply_to != "") {
            $admin_headers .= "Reply-To: " . $admin_email_reply_to . "\r\n";
         }
         if ($admin_email_cc != "") {
            $admin_headers .= "Cc: " . $admin_email_cc . "\r\n";
         }
         if ($admin_email_bcc != "") {
            $admin_headers .= "Bcc: " . $admin_email_bcc . "\r\n";
         }

         // Client Notification
         $client_body_content = "";
         $client_body_content .= "<div lang=\"ar\"> $client_email_message</div>";
         $client_header = "";
         $client_header .= "Content-Type: text/html; charset= utf-8" . "\r\n";
         if ($client_email_from_name != "" && $client_email_from != "") {
            $client_header .= "From: " . $client_email_from_name . " <" . $client_email_from . ">" . "\r\n";
         }
         if ($client_email_reply_to != "") {
            $client_header .= "Reply-To: " . $client_email_reply_to . "\r\n";
         }
         if ($client_email_cc != "") {
            $client_header .= "Cc: " . $client_email_cc . "\r\n";
         }
         if ($client_email_bcc != "") {
            $client_header .= "Bcc: " . $client_email_bcc . "\r\n";
         }
         wp_mail($admin_email_to, $admin_email_subject, $admin_body_content, $admin_headers, $attachment_file);
         wp_mail($client_email_to, $client_email_subject, $client_body_content, $client_header);
         if (isset($_REQUEST["save_submssion"]) && sanitize_text_field($_REQUEST["save_submssion"]) == "enable") {
            if (($submission_limit == "enable" && $id_count < $submission_limit_number) || $submission_limit == "disable") {
               if (count($form_record) > 0) {
                  $form_record["timestamp"] = time();
                  $form_data = array();
                  $form_data["old_form_id"] = $form_id;
                  $form_data["meta_id"] = $form_id;
                  $form_data["meta_key"] = "submission_form_data";
                  $form_data["meta_value"] = maybe_serialize($form_record);
                  $obj_user_helper_contact_bank->insertCommand(contact_bank_meta(), $form_data);
               }
            }
         }
         break;
   }
   die();
}