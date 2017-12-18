<?php
/**
 * Template for manage email templates.
 *
 * @author 	Tech anker
 * @package 	contact-bank/views/email-templates
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
   } else if (email_templates_contact_bank == "1") {
      $email_templates_nonce = wp_create_nonce("email_templates_nonce");
      ?>
      <div class="page-bar">
         <ul class="page-breadcrumb">
            <li>
               <i class="icon-custom-home"></i>
               <a href="admin.php?page=contact_dashboard">
                  <?php echo $cb_contact_bank; ?>
               </a>
               <span>></span>
            </li>
            <li>
               <span><?php echo $cb_email_templates; ?></span>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="portlet box vivid-green">
               <div class="portlet-title">
                  <div class="caption">
                     <i class="icon-custom-grid"></i>
                     <?php echo $cb_email_templates; ?>
                  </div>
                  <p class="premium-editions">
                     <?php echo $cb_upgrade_need_help ?><a href="https://contact-bank.tech-banker.com/" target="_blank" class="premium-editions-documentation"><?php echo $cb_documentation ?></a><?php echo $cb_read_and_check; ?><a href="https://contact-bank.tech-banker.com/frontend-demos/" target="_blank" class="premium-editions-documentation"><?php echo $cb_demos_section; ?></a>
                  </p>
               </div>
               <div class="portlet-body form">
                  <form id="ux_frm_manage_email_templates">
                     <div class="form-body">
                        <div class="form-actions">
                           <div class="pull-right">
                              <button type="submit" class="btn vivid-green" name="ux_btn_send_request" id="ux_btn_send_request"><?php echo $cb_save_changes; ?></button>
                           </div>
                        </div>
                        <div class="line-separator"></div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="control-label">
                                    <?php echo $cb_select_form; ?> :
                                    <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_select_form_tooltip; ?>" data-placement="right"></i>
                                    <span class="required" aria-required="true">*</span>
                                 </label>
                                 <select id="ux_ddl_forms" name="ux_ddl_forms" class="form-control" onchange="email_templates_redirect_data();">
                                    <option value=""><?php echo $cb_choose_form; ?></option>
                                    <?php
                                    foreach ($unserialized_forms_data_array as $data) {
                                       ?>
                                       <option value="<?php echo intval($data["meta_id"]); ?>"><?php echo isset($data["form_title"]) && $data["form_title"] != "" ? esc_attr($data["form_title"]) : $cb_untitled_form; ?></option>
                                       <?php
                                    }
                                    ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="control-label">
                                    <?php echo $cb_template_for; ?> :
                                    <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_template_for_tooltip; ?>" data-placement="right"></i>
                                    <span class="required" aria-required="true">*</span>
                                 </label>
                                 <select id="ux_ddl_email_templates" name="ux_ddl_email_templates" class="form-control" onchange="email_templates_redirect_data();">
                                    <option value="form_admin_notification_email"><?php echo $cb_template_admin_notification_template; ?></option>
                                    <option value="form_client_notification_email"><?php echo $cb_template_client_notification_template; ?></option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div id="email_template_layout" style="display:none">
                           <div class="form-group">
                              <label class="control-label">
                                 <?php echo $cb_send_to; ?> :
                                 <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_send_to_tooltips; ?>" data-placement="right"></i>
                                 <span class="required" aria-required="true">*</span>
                              </label>
                              <select id="ux_ddl_send_to" name="ux_ddl_send_to" class="form-control" onchange="contact_bank_show_hide_layout()">
                                 <option value="send_to_email"><?php echo $cb_send_to_enter_email; ?></option>
                                 <option value="select_field"><?php echo $cb_send_to_select_field; ?></option>
                              </select>
                           </div>
                           <div class="form-group" id="ux_send_to_email" style="display:none">
                              <label class="control-label">
                                 <?php echo $cb_email; ?> :
                                 <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_email_tooltip; ?>" data-placement="right"></i>
                                 <span class="required" aria-required="true">*</span>
                              </label>
                              <input type="text" class="form-control" name="ux_txt_send_to_email" id="ux_txt_send_to_email" value="<?php echo isset($unserialized_data_forms[$template_type]["template_send_to_email"]) ? esc_attr($unserialized_data_forms[$template_type]["template_send_to_email"]) : "" ?>" placeholder="<?php echo $cb_email_placeholder; ?>">
                           </div>
                           <div class="row" id="ux_send_to_field" style="display:none">
                              <div class="col-md-9">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_send_to_field; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_send_to_field_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_send_to_field" id="ux_txt_send_to_field" value="<?php echo isset($unserialized_data_forms[$template_type]["template_send_to_field"]) ? esc_attr($unserialized_data_forms[$template_type]["template_send_to_field"]) : ""; ?>" placeholder="<?php echo $cb_send_to_field_placeholder; ?>">
                                 </div>
                              </div>
                              <div class="col-md-3 button-controls-contact-bank">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_send_to_select_field; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_feedback_email_tooltip; ?>" data-placement="right"></i>
                                    </label>
                                    <select id="ux_ddl_ux_txt_your_reply_to_with_fields" name="ux_ddl_ux_txt_your_reply_to_with_fields" class="form-control" onchange="append_control_shortcode_contact_bank('ux_ddl_ux_txt_your_reply_to_with_fields', 'ux_txt_send_to_field')">
                                       <option value=""><?php echo $cb_send_to_select_field; ?></option>
                                       <?php
                                       if (isset($unserialized_data_forms["controls"]) && count($unserialized_data_forms["controls"]) > 0) {
                                          foreach ($unserialized_data_forms["controls"] as $values) {
                                             if ($values["control_type"] == "email") {
                                                ?>
                                                <option value="<?php echo esc_attr($values["timestamp"]); ?>"><?php echo esc_attr($values["label_name"]); ?></option>
                                                <?php
                                             }
                                          }
                                       }
                                       ?>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-9">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_from_name_label; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_from_name_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_from_name" id="ux_txt_from_name" placeholder="<?php echo $cb_from_name_placeholder; ?>" value="<?php echo isset($unserialized_data_forms[$template_type]["template_from_name"]) ? esc_attr($unserialized_data_forms[$template_type]["template_from_name"]) : ""; ?>">
                                 </div>
                              </div>
                              <div class="col-md-3 button-controls-contact-bank">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_send_to_select_field; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_feedback_email_tooltip; ?>" data-placement="right"></i>
                                    </label>
                                    <select id="ux_ddl_from_name_fields" name="ux_ddl_from_name_fields" class="form-control" onchange="append_control_shortcode_contact_bank('ux_ddl_from_name_fields', 'ux_txt_from_name')">
                                       <option value=""><?php echo $cb_send_to_select_field; ?></option>
                                       <?php
                                       if (isset($unserialized_data_forms["controls"]) && count($unserialized_data_forms["controls"]) > 0) {
                                          foreach ($unserialized_data_forms["controls"] as $values) {
                                             $controls_data_array = array("credit_card", "html", "divider", "section_break", "recaptcha", "anti_spam", "logical-captcha", "star_rating");
                                             if (!in_array($values["control_type"], $controls_data_array)) {
                                                ?>
                                                <option value="<?php echo esc_attr($values["timestamp"]); ?>"><?php echo esc_attr($values["label_name"]); ?></option>
                                                <?php
                                             }
                                          }
                                       }
                                       ?>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-9">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_from_email_label; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_from_email_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_from_email" id="ux_txt_from_email" placeholder="<?php echo $cb_from_email_placeholder; ?>" value="<?php echo isset($unserialized_data_forms[$template_type]["template_from_email"]) ? esc_attr($unserialized_data_forms[$template_type]["template_from_email"]) : ""; ?>">
                                 </div>
                              </div>
                              <div class="col-md-3 button-controls-contact-bank">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_send_to_select_field; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_feedback_email_tooltip; ?>" data-placement="right"></i>
                                    </label>
                                    <select id="ux_ddl_from_email_fields" name="ux_ddl_from_email_fields" class="form-control" onchange="append_control_shortcode_contact_bank('ux_ddl_from_email_fields', 'ux_txt_from_email')">
                                       <option value=""><?php echo $cb_send_to_select_field; ?></option>
                                       <?php
                                       if (isset($unserialized_data_forms["controls"]) && count($unserialized_data_forms["controls"]) > 0) {
                                          foreach ($unserialized_data_forms["controls"] as $values) {
                                             if ($values["control_type"] == "email") {
                                                ?>
                                                <option value="<?php echo esc_attr($values["timestamp"]); ?>"><?php echo esc_attr($values["label_name"]); ?></option>
                                                <?php
                                             }
                                          }
                                       }
                                       ?>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-9">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_send_to_select_field_reply_to; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_send_to_select_field_reply_to_tooltip; ?>" data-placement="right"></i>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_your_reply_to" id="ux_txt_your_reply_to" value="<?php echo isset($unserialized_data_forms[$template_type]["template_reply_to"]) ? esc_attr($unserialized_data_forms[$template_type]["template_reply_to"]) : ""; ?>" placeholder="<?php echo $cb_send_to_field_placeholder; ?>">
                                 </div>
                              </div>
                              <div class="col-md-3 button-controls-contact-bank">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_send_to_select_field; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_feedback_email_tooltip; ?>" data-placement="right"></i>
                                    </label>
                                    <select id="ux_ddl_reply_to_fields" name="ux_ddl_reply_to_fields" class="form-control" onchange="append_control_shortcode_contact_bank('ux_ddl_reply_to_fields', 'ux_txt_your_reply_to')">
                                       <option value=""><?php echo $cb_send_to_select_field; ?></option>
                                       <?php
                                       if (isset($unserialized_data_forms["controls"]) && count($unserialized_data_forms["controls"]) > 0) {
                                          foreach ($unserialized_data_forms["controls"] as $values) {
                                             if ($values["control_type"] == "email") {
                                                ?>
                                                <option value="<?php echo esc_attr($values["timestamp"]); ?>"><?php echo esc_attr($values["label_name"]); ?></option>
                                                <?php
                                             }
                                          }
                                       }
                                       ?>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-9">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_send_to_select_field_cc; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_send_to_select_field_cc_tooltip; ?>" data-placement="right"></i>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_your_cc_field" id="ux_txt_your_cc_field" value="<?php echo isset($unserialized_data_forms[$template_type]["template_cc"]) ? esc_attr($unserialized_data_forms[$template_type]["template_cc"]) : ""; ?>" placeholder="<?php echo $cb_send_to_field_placeholder; ?>">
                                 </div>
                              </div>
                              <div class="col-md-3 button-controls-contact-bank">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_send_to_select_field; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_feedback_email_tooltip; ?>" data-placement="right"></i>
                                    </label>
                                    <select id="ux_ddl_cc_with_fields" name="ux_ddl_cc_with_fields" class="form-control" onchange="append_control_shortcode_contact_bank('ux_ddl_cc_with_fields', 'ux_txt_your_cc_field')">
                                       <option value=""><?php echo $cb_send_to_select_field; ?></option>
                                       <?php
                                       if (isset($unserialized_data_forms["controls"]) && count($unserialized_data_forms["controls"]) > 0) {
                                          foreach ($unserialized_data_forms["controls"] as $values) {
                                             if ($values["control_type"] == "email") {
                                                ?>
                                                <option value="<?php echo esc_attr($values["timestamp"]); ?>"><?php echo esc_attr($values["label_name"]); ?></option>
                                                <?php
                                             }
                                          }
                                       }
                                       ?>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-9">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_send_to_select_field_bcc; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_send_to_select_field_bcc_tooltip; ?>" data-placement="right"></i>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_bcc_field" id="ux_txt_bcc_field" value="<?php echo isset($unserialized_data_forms[$template_type]["template_bcc"]) ? esc_attr($unserialized_data_forms[$template_type]["template_bcc"]) : "" ?>" placeholder="<?php echo $cb_send_to_select_field_bcc_placeholder; ?>">
                                 </div>
                              </div>
                              <div class="col-md-3 button-controls-contact-bank">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_send_to_select_field; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_feedback_email_tooltip; ?>" data-placement="right"></i>
                                    </label>
                                    <select id="ux_ddl_bcc_with_field" name="ux_ddl_bcc_with_field" class="form-control" onchange="append_control_shortcode_contact_bank('ux_ddl_bcc_with_field', 'ux_txt_bcc_field')">
                                       <option value=""><?php echo $cb_send_to_select_field; ?></option>
                                       <?php
                                       if (isset($unserialized_data_forms["controls"]) && count($unserialized_data_forms["controls"]) > 0) {
                                          foreach ($unserialized_data_forms["controls"] as $values) {
                                             if ($values["control_type"] == "email") {
                                                ?>
                                                <option value="<?php echo esc_attr($values["timestamp"]); ?>"><?php echo esc_attr($values["label_name"]); ?></option>
                                                <?php
                                             }
                                          }
                                       }
                                       ?>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-9">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_send_to_select_field_subject; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_send_to_select_field_subject_tooltip; ?>" data-placement="right"></i>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_subject" id="ux_txt_subject" value="<?php echo isset($unserialized_data_forms[$template_type]["template_subject"]) ? esc_attr($unserialized_data_forms[$template_type]["template_subject"]) : "" ?>" placeholder="<?php echo $cb_send_to_select_field_subject_placeholder; ?>">
                                 </div>
                              </div>
                              <div class="col-md-3 button-controls-contact-bank">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_send_to_select_field; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_feedback_email_tooltip; ?>" data-placement="right"></i>
                                    </label>
                                    <select id="ux_ddl_subject_with_fields" name="ux_ddl_subject_with_fields" class="form-control" onchange="append_control_shortcode_contact_bank('ux_ddl_subject_with_fields', 'ux_txt_subject')">
                                       <option value=""><?php echo $cb_send_to_select_field; ?></option>
                                       <?php
                                       if (isset($unserialized_data_forms["controls"]) && count($unserialized_data_forms["controls"]) > 0) {
                                          foreach ($unserialized_data_forms["controls"] as $values) {
                                             $controls_data_array = array("credit_card", "html", "divider", "section_break", "recaptcha", "anti_spam", "logical-captcha", "star_rating");
                                             if (!in_array($values["control_type"], $controls_data_array)) {
                                                ?>
                                                <option value="<?php echo esc_attr($values["timestamp"]); ?>"><?php echo esc_attr($values["label_name"]); ?></option>
                                                <?php
                                             }
                                          }
                                       }
                                       ?>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-9">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_message; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_form_description_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <?php
                                    $template_message = isset($unserialized_data_forms[$template_type]["template_message"]) ? urldecode($unserialized_data_forms[$template_type]["template_message"]) : "";
                                    wp_editor($template_message, 'ux_heading_content', array('teeny' => TRUE, 'textarea_name' => 'description', 'media_buttons' => FALSE, 'textarea_rows' => 10));
                                    ?>
                                    <textarea  name="ux_txtarea_email_template_heading_content" id="ux_txtarea_email_template_heading_content" style="display:none;"><?php echo $template_message; ?></textarea>
                                 </div>
                              </div>
                              <div class="col-md-3 button-controls-contact-bank">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_send_to_select_field; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_feedback_email_tooltip; ?>" data-placement="right"></i>
                                    </label>
                                    <select id="ux_ddl_form_message_field" name="ux_ddl_form_message_field" class="form-control" onchange="append_form_message_field_control_contact_bank('ux_ddl_form_message_field')">
                                       <option value=""><?php echo $cb_send_to_select_field; ?></option>
                                       <?php
                                       if (isset($unserialized_data_forms["controls"]) && count($unserialized_data_forms["controls"]) > 0) {
                                          foreach ($unserialized_data_forms["controls"] as $values) {
                                             $controls_data_array = array("html", "divider", "section_break", "recaptcha", "anti_spam", "logical-captcha", "star_rating");
                                             if (!in_array($values["control_type"], $controls_data_array)) {
                                                if ($values["control_type"] == "credit_card") {
                                                   ?>
                                                   <option value="<?php echo esc_attr($values["timestamp"]); ?>"><?php echo "Credit Card"; ?></option>
                                                   <?php
                                                } else {
                                                   ?>
                                                   <option value="<?php echo esc_attr($values["timestamp"]); ?>"><?php echo esc_attr($values["label_name"]); ?></option>
                                                   <?php
                                                }
                                             }
                                          }
                                       }
                                       ?>
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="line-separator"></div>
                        <div class="form-actions">
                           <div class="pull-right">
                              <button type="submit" class="btn vivid-green" name="ux_btn_send_request" id="ux_btn_send_request"><?php echo $cb_save_changes; ?></button>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <?php
   } else {
      ?>
      <div class="page-bar">
         <ul class="page-breadcrumb">
            <li>
               <i class="icon-custom-home"></i>
               <a href="admin.php?page=contact_dashboard">
                  <?php echo $cb_contact_bank; ?>
               </a>
               <span>></span>
            </li>
            <li>
               <span> <?php echo $cb_email_templates; ?></span>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="portlet box vivid-green">
               <div class="portlet-title">
                  <div class="caption">
                     <i class="icon-custom-grid"></i>
                     <?php echo $cb_email_templates; ?>
                  </div>
               </div>
               <div class="portlet-body form">
                  <div class="form-body">
                     <strong><?php echo $cb_user_access_message; ?></strong>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php
   }
}