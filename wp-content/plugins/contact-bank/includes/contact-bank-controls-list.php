<?php
/**
 * Template for add new form.
 *
 * @author 	Tech Banker
 * @package 	contact-bank/includes
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
      global $wpdb;
      $form_id = isset($_REQUEST["form_id"]) ? intval($_REQUEST["form_id"]) : "";
      $meta_value = $wpdb->get_var
          (
          $wpdb->prepare
              (
              "SELECT meta_value  FROM " . contact_bank_meta() . " WHERE meta_id = %d and meta_key = %s", $form_id, "form_data"
          )
      );
      $unserialize_meta_value = maybe_unserialize($meta_value);
      ?>
      <div id="ux_div_single_line_text" style="display: none;cursor:pointer;" class="result_hover">
         <div class="main_div">
            <div class="header_title" style="visibility: hidden;">
               <div class="header_title_left" ><b>Single Line Text</b></div>
               <div class="header_title_rigth" style="float: right;margin-right: 10px;">
                  <a title="<?php echo $cb_expand_field; ?>"><i class="icon-custom-note"></i></a>
                  <a title="<?php echo $cb_duplicate_field; ?>" ><i class="icon-custom-docs"></i></a>
                  <a title="<?php echo $cb_delete_field; ?>"><i class="icon-custom-trash"></i></a>
               </div>
               <input type="hidden">
            </div>
            <div class="sub_div" style="padding-bottom:20px;">
               <label class="control-label field_label" style="display:block;">
                  <span> <?php echo $cb_untitled_control; ?></span> :
                  <i class="icon-custom-question tooltips" data-original-title="" data-placement="right"></i>
                  <span class="required" aria-required="true">*</span>
               </label>
               <input name="ux_txt_singal_line_text" class="untitled_control" type="text" value="" autocomplete="off">
               <div class="form-group default_text" style="display:none; padding: 10px 10px 0px 10px;">
                  It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
               </div>
               <select name="ux_ddl_us_states" id="ux_ddl_us_states" class="untitled_control" style="display: none;">
               </select>
               <select id="ux_ddl_country" class="untitled_control" style="display:none;">
               </select>
               <span></span>
            </div>
            <div class="sub_div_section_break" style="padding-bottom:30px; display: none;">
               <label class="control-label field_label">
                  <span> <?php echo $cb_untitled_control; ?></span>
               </label>
               <input name="ux_txt_singal_line_text" class="untitled_control" type="text" value="" autocomplete="off">
               <span></span>
            </div>
         </div>
         <div class="ux_div_widget_content" >
            <div class="tabbable-form-custom">
               <ul class="nav nav-tabs ">
                  <li class="active general_settings">
                     <a aria-expanded="true" data-toggle="tab">
                        <?php echo $cb_general_tab; ?>
                     </a>
                  </li>
                  <li class="options_settings">
                     <a aria-expanded="false" data-toggle="tab">
                        <?php echo $cb_options_tab; ?>
                     </a>
                  </li>
                  <li class="appearance_settings">
                     <a aria-expanded="false" data-toggle="tab">
                        <?php echo $cb_appearance_tab; ?>
                     </a>
                  </li>
                  <li class="restrictions_settings">
                     <a aria-expanded="false" data-toggle="tab">
                        <?php echo $cb_restrictions_tab; ?>
                     </a>
                  </li>
                  <li class="advanced_settings">
                     <a aria-expanded="false" data-toggle="tab">
                        <?php echo $cb_advanced_tab; ?>
                     </a>
                  </li>
               </ul>
               <div class="tab-content">
                  <div class="tab-pane active general_settings" id="general">
                     <div class="form-group label_settings">
                        <label class="control-label">
                           <?php echo $cb_label_control; ?> :
                           <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_label_control_tooltip; ?>" data-placement="right"></i>
                           <span class="required" aria-required="true">*</span>
                        </label>
                        <input type="text" class="form-control" name="ux_txt_label_field"  placeholder="<?php echo $cb_label_general_placeholder; ?>" value="Untitled">
                     </div>
                     <div class="form-group tooltip_settings">
                        <label class="control-label">
                           <?php echo $cb_tooltip_control; ?> :
                           <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_general_tooltip_description; ?>" data-placement="right"></i>
                           <span class="required" aria-required="true">*</span>
                        </label>
                        <input type="text" class="form-control" name="ux_txt_description_field"  placeholder="<?php echo $cb_general_tootltip_placeholder; ?>">
                     </div>
                     <div class="form-group label_placement_settings">
                        <label class="control-label">
                           <?php echo $cb_label_placement_control; ?> :
                           <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_general_label_placement; ?>" data-placement="right"></i>
                           <span class="required" aria-required="true">*</span>
                        </label>
                        <select class="form-control">
                           <option value="above"><?php echo $cb_label_placement_above; ?></option>
                           <option value="below"><?php echo $cb_label_placement_below; ?></option>
                           <option value="left"><?php echo $cb_label_placement_left; ?></option>
                           <option value="right"><?php echo $cb_label_placement_right; ?></option>
                           <option value="hidden"><?php echo $cb_label_placement_hidden; ?></option>
                        </select>
                     </div>
                     <div class="number_settings">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="control-label">
                                    <?php echo $cb_general_min_number; ?> :
                                    <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_general_min_number_tooltip; ?>" data-placement="right"></i>
                                    <span class="required" aria-required="true">*</span>
                                 </label>
                                 <input type="text" class="form-control" name="ux_txt_min_number" onkeypress="enter_only_digits_for_price(event);" placeholder="<?php echo $cb_general_min_number_placeholder; ?>">
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="control-label">
                                    <?php echo $cb_general_max_number; ?> :
                                    <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_general_max_number_tooltip; ?>" data-placement="right"></i>
                                    <span class="required" aria-required="true">*</span>
                                 </label>
                                 <input type="text" class="form-control" name="ux_txt_max_number" onkeypress="enter_only_digits_for_price(event);" placeholder="<?php echo $cb_general_max_number_placeholder; ?>">
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="control-label">
                              <?php echo $cb_general_step; ?> :
                              <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_general_step_tooltip; ?>" data-placement="right"></i>
                              <span class="required" aria-required="true">*</span>
                           </label>
                           <input type="text" class="form-control" name="ux_txt_number_step" onkeypress="enter_only_digits_for_price(event);" placeholder="<?php echo $cb_general_step_placeholder; ?>">
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane options_settings" id="option">
                     <div class="row">
                        <div class="col-md-6">
                           <?php echo $cb_options_control; ?>
                           <input type="text" class="form-control" name="ux_txt_add_form_option" placeholder="<?php echo $cb_options_control_placeholder; ?>">
                        </div>
                        <div class="col-md-6">
                           <?php echo $cb_options_value; ?>
                           <input type="text" class="form-control" name="ux_txt_add_form_values" placeholder="<?php echo $cb_options_value_placeholder; ?>">
                        </div>
                        <div class="pull-right" style="margin-right:10px;margin-top:5px;">
                           <input type="button" class="btn vivid-green" name="ux_btn_add_option" id="ux_btn_add_options" name="ux_btn_add_options" value="<?php echo $cb_add_option; ?>">
                           <input type="button" class="btn vivid-green" name="ux_btn_add_option" id="ux_btn_add_import" value="<?php echo $cb_add_import; ?>" data-popup-open="ux_open_popup_translator">
                           <input type="hidden" class="form-control select-hidden" name="ux_hidden_options_values">
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="control-label" style="display:block;">
                           <?php echo $cb_options; ?> :
                           <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_options_tooltip; ?>" data-placement="right"></i>
                           <span class="required" aria-required="true">*</span>
                        </label>
                        <select class="form-control custom-drop-down input-inline" name="ux_ddl_required">
                        </select>
                        <input type="button" class="btn vivid-green pull-right"  style="margin-top:8px" name="ux_btn_delete_option"  value="<?php echo $cb_add_delete; ?>">
                     </div>
                     <div class="popup" data-popup="ux_open_popup_translator" id="open_popup">
                        <div class="popup-inner">
                           <div class="portlet box vivid-green" style="margin-bottom:0px;">
                              <div class="portlet-title">
                                 <div class="caption" id="ux_div_action">
                                    <?php echo $cb_textarea; ?>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <div id="ux_div_popup_header">
                                    <div class="form-body">
                                       <div class="form-group">
                                          <label class="control-label">
                                             <?php echo $cb_textarea; ?> :
                                             <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_textarea; ?>" data-placement="right"></i>
                                             <span class="required" aria-required="true">*</span>
                                          </label>
                                          <textarea class="form-control" rows="7" placeholder="<?php echo $cb_popup_query_placeholder; ?>"></textarea>
                                       </div>
                                    </div>
                                    <div class="modal-footer">
                                       <div class="form-actions">
                                          <div class="pull-right">
                                             <input type="button"  class="btn vivid-green" name="ux_send_query" value="<?php echo $cb_add_import; ?>" data-popup-close-translator="ux_open_popup_translator"> 
                                             <input type="button" data-popup-close-translator="ux_open_popup_translator" class="btn vivid-green" id="ux_btn_close" value="<?php echo $cb_manage_backups_close; ?>">
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane appearance_settings" id="appearance">
                     <div class="form-group placeholder_settings">
                        <label class="control-label">
                           <?php echo $cb_appearance_placeholder_label; ?> :
                           <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_appearance_placeholder_tooltip; ?>" data-placement="right"></i>
                           <span class="required" aria-required="true">*</span>
                        </label>
                        <input type="text" class="form-control" name="ux_txt_placeholder_field" placeholder="<?php echo $cb_appearance_placeholder; ?>">
                     </div>
                     <div class="form-group custom_validation_settings">
                        <label class="control-label">
                           <?php echo $cb_custom_validation_message; ?> :
                           <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_custom_validation_message_tooltip; ?>" data-placement="right"></i>
                           <span class="required" aria-required="true">*</span>
                        </label>
                        <input type="text" class="form-control" placeholder="<?php echo $cb_custom_validation_message_placeholder; ?>" value="This field is required">
                     </div>
                     <div class="form-group rows_number">
                        <label class="control-label">
                           <?php echo $cb_appearance_rows; ?> :
                           <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_appearance_rows_tooltip; ?>" data-placement="right"></i>
                           <span class="required" aria-required="true">*</span>
                        </label>
                        <input type="text" class="form-control" onkeypress="enter_only_digits_contact_bank(event);" maxlength="3" placeholder="<?php echo $cb_appearance_rows_placeholder; ?>">
                     </div>
                     <div class="row class_settings">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="control-label">
                                 <?php echo $cb_appearance_container_class; ?> :
                                 <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_appearance_container_class_tooltip; ?>" data-placement="right"></i>
                                 <span class="required" aria-required="true">*</span>
                              </label>
                              <input type="text" class="form-control" placeholder="<?php echo $cb_appearance_container_class_placeholder; ?>">
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="control-label">
                                 <?php echo $cb_appearance_element_class; ?> :
                                 <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_appearance_element_class_tooltip; ?>" data-placement="right"></i>
                                 <span class="required" aria-required="true">*</span>
                              </label>
                              <input type="text" class="form-control" placeholder="<?php echo $cb_appearance_element_class_placeholder; ?>">
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane restrictions_settings" id="restriction">
                     <div class="form-group required_settings">
                        <label class="control-label">
                           <?php echo $cb_restrictions_required; ?> :
                           <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_restrictions_required_tooltip; ?>" data-placement="right"></i>
                           <span class="required" aria-required="true">*</span>
                        </label>
                        <select class="form-control">
                           <option value="enable"><?php echo $cb_enable; ?></option>
                           <option value="disable"><?php echo $cb_disable; ?></option>
                        </select>
                     </div>
                     <div class="form-group limit_input_number_settings">
                        <label class="control-label">
                           <?php echo $cb_restrictions_limit_input_number; ?> :
                           <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_restrictions_limit_input_number_tooltip; ?>" data-placement="right"></i>
                           <span class="required" aria-required="true">*</span>
                        </label>
                        <div class="row">
                           <div class="col-md-6">
                              <input type="text" class="form-control" value="50" placeholder="<?php echo $cb_restrictions_limit_input_number_placeholder; ?>">
                           </div>
                           <div class="col-md-6">
                              <select class="form-control">
                                 <option value="characters"><?php echo $cb_restrictions_characters; ?></option>
                                 <option value="digits"><?php echo $cb_restrictions_words; ?></option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="form-group text_appear_settings">
                        <label class="control-label">
                           <?php echo $cb_restrictions_text_appear_after_counter; ?> :
                           <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_restrictions_text_appear_after_counter_tooltip; ?>" data-placement="right"></i>
                           <span class="required" aria-required="true">*</span>
                        </label>
                        <input type="text" class="form-control" value="Characters Left" placeholder="<?php echo $cb_restrictions_text_appear_after_counter_placeholder; ?>">
                     </div>
                     <div class="row autocomplete_settings">
                        <div class="col-md-6 enable_autocomplete">
                           <div class="form-group">
                              <label class="control-label">
                                 <?php echo $cb_restrictions_required_disabled_autocomplete; ?> :
                                 <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_restrictions_required_disabled_autocomplete_tooltip; ?>" data-placement="right"></i>
                                 <span class="required" aria-required="true">*</span>
                              </label>
                              <select class="form-control">
                                 <option value="enable"><?php echo $cb_enable; ?></option>
                                 <option value="disable"><?php echo $cb_disable; ?></option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-6 enable_disable_input">
                           <div class="form-group disable_input_settings">
                              <label class="control-label">
                                 <?php echo $cb_restrictions_required_disabled_input; ?> :
                                 <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_restrictions_required_disabled_input_tooltip; ?>" data-placement="right"></i>
                                 <span class="required" aria-required="true">*</span>
                              </label>
                              <select class="form-control" >
                                 <option value="disable"><?php echo $cb_disable; ?></option>
                                 <option value="enable"><?php echo $cb_enable; ?></option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="form-group input_mask_settings">
                        <label class="control-label">
                           <?php echo $cb_restrictions_input_masking; ?> :
                           <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_restrictions_input_masking_tooltip; ?>" data-placement="right"></i>
                           <span class="required" aria-required="true">*</span>
                        </label>
                        <select class="form-control" >
                           <option value="none"><?php echo $cb_restriction_none; ?></option>
                           <option value="us_phone"><?php echo $cb_restriction_us_phone; ?></option>
                           <option value="date"><?php echo $cb_restriction_date; ?></option>
                           <option value="custom"><?php echo $cb_restriction_custom; ?></option>
                        </select>
                     </div>
                     <div class="form-group custom_mask_settings">
                        <label class="control-label">
                           <?php echo $cb_restrictions_custom_masking; ?> :
                           <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_restrictions_custom_masking_tooltip; ?>" data-placement="right"></i>
                           <span class="required" aria-required="true">*</span>
                        </label>
                        <input type="text" class="form-control"  value="999,999,999,999" placeholder="<?php echo $cb_restrictions_custom_masking_placeholder; ?>">
                     </div>
                  </div>
                  <div class="tab-pane advanced_settings" id="advanced">
                     <div class="form-group">
                        <label class="control-label">
                           <?php echo $cb_advanced_field_key; ?> :
                           <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_advanced_field_key_tooltip; ?>" data-placement="right"></i>
                           <span class="required" aria-required="true">*</span>
                        </label>
                        <input type="text" class="form-control" placeholder="<?php echo $cb_advanced_field_key_placeholder; ?>">
                     </div>
                     <div class="row">
                        <div class="col-md-6 default_value_settings">
                           <div class="form-group">
                              <label class="control-label">
                                 <?php echo $cb_advanced_default_value; ?> :
                                 <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_advanced_default_value_tooltip; ?>" data-placement="right"></i>
                                 <span class="required" aria-required="true">*</span>
                              </label>
                              <input type="text" class="form-control" placeholder="<?php echo $cb_advanced_default_value_placeholder; ?>">
                              <select id="ux_default_state" class="form-control us-states" style="display: none;">
                              </select>
                              <select id="ux_default_country" class="form-control countries-list" style="display:none;">
                              </select>
                           </div>
                        </div>
                        <div class="col-md-6 admin_label_settings">
                           <div class="form-group">
                              <label class="control-label">
                                 <?php echo $cb_advanced_admin_label; ?> :
                                 <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_advanced_admin_label_tooltip; ?>" data-placement="right"></i>
                                 <span class="required" aria-required="true">*</span>
                              </label>
                              <input type="text" class="form-control" placeholder="<?php echo $cb_advanced_admin_label_placeholder; ?>">
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php
   }
}