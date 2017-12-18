<?php
/**
 * This file is used for creating dbHelper class.
 *
 * @author  Tech Banker
 * @package contact-bank/lib
 * @version 3.0
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
      /*
        Class Name: dbHelper_contact_bank
        Parameters: No
        Description: This Class is used for Insert, Update and Delete operations.
        Created On: 18-feb-2016
        Created By: Tech Banker Team
       */
      class dbHelper_contact_bank {
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
           Function Name: updateCommand
           Parameters: Yes($table_name,$data,$where)
           Description: This function is used for Update data.
           Created On: 29-05-2017
           Created By: Tech Banker Team
          */
         function updateCommand($table_name, $data, $where) {
            global $wpdb;
            $wpdb->update($table_name, $data, $where);
         }
         /*
           Function Name: deleteCommand
           Parameters: Yes($table_name,$where)
           Description: This function is used for delete data.
           Created On: 29-05-2017
           Created By: Tech Banker Team
          */
         function deleteCommand($table_name, $where) {
            global $wpdb;
            $wpdb->delete($table_name, $where);
         }
      }
      /*
        Class Name: plugin_info_contact_bank
        Parameters: No
        Description: This Class is used to get the the information about plugins.
        Created On: 06-09-2017 05:30
        Created By: Tech Banker Team
       */
      class plugin_info_contact_bank {
         /*
           Function Name: get_plugin_info_contact_bank
           Parameters: No
           Decription: This function is used to return the information about plugins.
           Created On: 06-09-2017 05:30
           Created By: Tech Banker Team
          */
         function get_plugin_info_contact_bank() {
            $active_plugins = (array) get_option("active_plugins", array());
            if (is_multisite()) {
               $active_plugins = array_merge($active_plugins, get_site_option("active_sitewide_plugins", array()));
            }
            $plugins = array();
            if (count($active_plugins) > 0) {
               $get_plugins = array();
               foreach ($active_plugins as $plugin) {
                  $plugin_data = @get_plugin_data(WP_PLUGIN_DIR . "/" . $plugin);

                  $get_plugins["plugin_name"] = strip_tags($plugin_data["Name"]);
                  $get_plugins["plugin_author"] = strip_tags($plugin_data["Author"]);
                  $get_plugins["plugin_version"] = strip_tags($plugin_data["Version"]);
                  array_push($plugins, $get_plugins);
               }
               return $plugins;
            }
         }
      }
   }
}