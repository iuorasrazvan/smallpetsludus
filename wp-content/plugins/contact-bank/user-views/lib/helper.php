<?php
/**
 * This file is used for creating user helper class.
 *
 * @author	Tech Banker
 * @package	contact-bank/user-views/lib
 * @version	3.0
 */
if (!defined("ABSPATH")) {
   exit;
} // Exit if accessed directly
/*
  Class Name: user_helper_contact_bank
  Parameters: No
  Description: This Class is used for return data in unserialize form and convert HEX-color into RGB values.
  Created On: 01-6-2017 09:00AM
  Created By: Tech Banker Team
 */
if (!class_exists("user_helper_contact_bank")) {

   class user_helper_contact_bank {
      /*
        Function Name: insertCommand
        Parameters: Yes($table_name,$data)
        Description: This Function is used for Insert data in database.
        Created On: 29-05-2017
        Created By: Tech Banker Team
       */
      function insertCommand($table_name, $data) {
         global $wpdb;
         $wpdb->insert($table_name, $data);
         return $wpdb->insert_id;
      }
      /*
        Function Name: hex2rgb_contact_bank
        Parameters: Yes($hex)
        Description: This function is used for convert a normal HEX-color into RGB values.
        Created On: 11-7-2017 11:17AM
        Created By: Tech Banker Team
       */
      public static function hex2rgb_contact_bank($hex) {
         $hex = str_replace("#", "", $hex);
         if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
         } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
         }
         $rgb = array($r, $g, $b);
         return $rgb;
      }
      /*
        Function Name: get_meta_value_contact_bank
        Parameters: Yes($meta_key)
        Description: This function is used for return data in unserialize form.
        Created On: 11-7-2017 11:17AM
        Created By: Tech Banker Team
       */
      public static function get_meta_value_contact_bank($meta_key) {
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
      /*
        Function Name: font_families_contact_bank
        Parameters: Yes($font_families)
        Description: This function is used for font-family.
        Created On: 11-7-2017 11:17AM
        Created By: Tech Banker Team
       */
      public static function font_families_contact_bank($font_families) {
         foreach ($font_families as $font_family) {
            if ($font_family != "inherit") {
               if (strpos($font_family, ":") != false) {
                  $position = strpos($font_family, ":");
                  $font_style = (substr($font_family, $position + 4, 6) == "italic") ? "\r\n\tfont-style: italic !important;" : "";
                  $font_family_name[] = "'" . substr($font_family, 0, $position) . "'" . " !important;\r\n\tfont-weight: " . substr($font_family, $position + 1, 3) . " !important;" . $font_style;
               } else {
                  $font_family_name[] = (strpos($font_family, "&") != false) ? "'" . strstr($font_family, "&", 1) . "' !important;" : "'" . $font_family . "' !important;";
               }
            } else {
               $font_family_name[] = "inherit";
            }
         }
         return $font_family_name;
      }
      /*
        Function Name: unique_font_families_contact_bank
        Parameters: Yes($unique_font_families,$import_font_family)
        Description: This function is used for font-family.
        Created On: 11-7-2017 11:17AM
        Created By: Tech Banker Team
       */
      public static function unique_font_families_contact_bank($unique_font_families) {
         $import_font_family = "";
         foreach ($unique_font_families as $font_family) {
            if ($font_family != "inherit") {
               $font_family = urlencode($font_family);
               if (is_ssl()) {
                  $import_font_family .= "@import url('https://fonts.googleapis.com/css?family=" . $font_family . "');\r\n";
               } else {
                  $import_font_family .= "@import url('http://fonts.googleapis.com/css?family=" . $font_family . "');\r\n";
               }
            }
         }
         return $import_font_family;
      }
   }
}