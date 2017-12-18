<?php
/**
 * This file is used for Widget Form Layout.
 *
 * @author 	Tech Banker
 * @package 	contact-bank/user-views/includes
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
      $unserialized_forms_data_array = get_contact_dashboard_bank_data("form", "form_data");
      ?>
      <div style="margin-top: 10px;">
         <div class="form-group">
            <label class="control-label" title="form_name"> <?php echo $cb_shortcode_form_name; ?>: </label>
            <select id='<?php echo $this->get_field_id("ux_ddl_shortcode_form_name"); ?>'  name="<?php echo $this->get_field_name("ux_ddl_shortcode_form_name"); ?>" class="form-control">
               <option value=""><?php echo $cb_choose_form; ?></option>
               <?php
               foreach ($unserialized_forms_data_array as $data) {
                  ?>
                  <option <?php echo isset($instance["form_name"]) && $instance["form_name"] || isset($instance["form_id"]) == $data["old_form_id"] ? "selected=selected" : ""; ?> value="<?php echo $data["old_form_id"]; ?>"><?php echo isset($data["form_title"]) && $data["form_title"] != "" ? esc_attr($data["form_title"]) : $cb_untitled_form; ?></option>
                  <?php
               }
               ?>
            </select>
         </div>
         <div class="form-group">
            <label class="control-label" title="form_title"> <?php echo $cb_shortcode_show_form_title; ?> : </label>
            <select id='<?php echo $this->get_field_id("ux_ddl_shortcode_form_title"); ?>' name="<?php echo $this->get_field_name("ux_ddl_shortcode_form_title"); ?>" class="form-control">
               <option <?php echo isset($instance["form_title"]) && $instance["form_title"] == "show" ? "selected=selected" : ""; ?> value="show"><?php echo $cb_shortcode_button_show; ?></option>
               <option <?php echo isset($instance["form_title"]) && $instance["form_title"] == "hide" ? "selected=selected" : ""; ?> value="hide"><?php echo $cb_shortcode_button_hide; ?></option>
            </select>
         </div>
         <div class="form-group">
            <label class="control-label" title="form_description"> <?php echo $cb_shortcode_show_form_description; ?> :</label>
            <select id="<?php echo $this->get_field_id("ux_ddl_shortcode_form_description"); ?>" name="<?php echo $this->get_field_name("ux_ddl_shortcode_form_description"); ?>" class="form-control">
               <option <?php echo isset($instance["form_description"]) && $instance["form_description"] == "show" ? "selected=selected" : ""; ?> value="show"><?php echo $cb_shortcode_button_show; ?></option>
               <option <?php echo isset($instance["form_description"]) && $instance["form_description"] == "hide" ? "selected=selected" : ""; ?> value="hide"><?php echo $cb_shortcode_button_hide; ?></option>
            </select>
         </div>
      </div>
      <?php
   }
}