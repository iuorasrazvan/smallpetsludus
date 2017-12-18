<?php
/**
 * This file is used for show frontend data save in database.
 *
 * @author 	Tech Banker
 * @package 	contact-bank/views/submissions
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
   } else if (submissions_contact_bank == "1") {
      $cb_contact_bank_delete_nonce = wp_create_nonce("cb_submission_single_delete");
      $form_id = isset($_REQUEST["form_id"]) ? intval($_REQUEST["form_id"]) : "";
      $timestamp = CONTACT_BANK_LOCAL_TIME;
      $start_date = $timestamp - 2592000;
      ?>
      <style>
         div.dataTables_wrapper div.dataTables_filter label {
            float: right;
            margin-bottom: 20px;
            margin-top: -6%;
            margin-right: 22%;
         }
      </style>

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
               <span>
                  <?php echo $cb_submissions; ?>
               </span>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="portlet box vivid-green">
               <div class="portlet-title">
                  <div class="caption">
                     <i class="icon-custom-note"></i>
                     <?php echo $cb_submissions; ?>
                  </div>
                  <p class="premium-editions">
                     <?php echo $cb_upgrade_need_help ?><a href="https://contact-bank.tech-banker.com/" target="_blank" class="premium-editions-documentation"><?php echo $cb_documentation ?></a><?php echo $cb_read_and_check; ?><a href="https://contact-bank.tech-banker.com/frontend-demos/" target="_blank" class="premium-editions-documentation"><?php echo $cb_demos_section; ?></a>
                  </p>
               </div>
               <div class="portlet-body form">
                  <form id="ux_frm_submissions_layout">
                     <div class="form-body">
                        <div class="form-group">
                           <label class="control-label">
                              <?php echo $cb_select_form; ?> :
                              <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_select_form_tooltip; ?>" data-placement="right"></i>
                              <span class="required" aria-required="true">*</span>
                           </label>
                           <select id="ux_ddl_manage_email" name="ux_ddl_manage_email" class="form-control" onchange="submissions_redirect_data_contact_bank();">
                              <option value=""><?php echo $cb_choose_form; ?></option>
                              <?php
                              foreach ($unserialized_forms_data_array as $data) {
                                 ?>
                                 <option value="<?php echo intval($data["old_form_id"]); ?>"><?php echo isset($data["form_title"]) && $data["form_title"] != "" ? esc_attr($data["form_title"]) : $cb_untitled_form; ?></option>
                                 <?php
                              }
                              ?>
                           </select>
                        </div>
                        <div id="ux_div_submissions" style="display: none;">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_start_date; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_submissions_start_date_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">* <?php echo " (" . $cb_premium_edition . " )" ?></span>
                                    </label>
                                    <input type="text" class="form-control" onkeypress="prevent_datepicker_contact_bank('#ux_txt_start_date');" name="ux_txt_start_date" id="ux_txt_start_date" value="<?php echo date("m/d/Y", $start_date); ?>" placeholder="<?php echo $cb_start_date_placeholder; ?>">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cb_end_date; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_submissions_end_date_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">* <?php echo " (" . $cb_premium_edition . " )" ?></span>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_end_date" onkeypress="prevent_datepicker_contact_bank('#ux_txt_end_date');" id="ux_txt_end_date" value="<?php echo date("m/d/Y"); ?>" placeholder="<?php echo $cb_end_date_placeholder; ?>">
                                 </div>
                              </div>
                           </div>
                           <div class="form-actions">
                              <div class="pull-right">
                                 <input type="submit" class="btn vivid-green" id="btn_save_start_end_date" name="btn_save_start_end_date" value="<?php echo $cb_submit; ?>">
                              </div>
                           </div>
                           <div class="line-separator"></div>
                           <div class="table-top-margin">
                              <select name="ux_ddl_manage_tags" id="ux_ddl_manage_tags">
                                 <option value=""><?php echo $cb_bulk_action; ?></option>
                                 <option value="delete" style="color:red;"><?php echo $cb_add_delete . " (" . $cb_premium_edition . " )"; ?></option>
                              </select>
                              <input type="button" class="btn vivid-green" name="ux_btn_apply_manage_forms" id="ux_btn_apply_manage_forms" value="<?php echo $cb_apply; ?>" onclick="premium_edition_notification_contact_bank();">
                           </div>
                           <div class="contact-form-scroll">
                              <table class="table table-striped table-bordered table-hover" id="ux_table_submissions">
                                 <thead class="align-thead-left">
                                    <tr>
                                       <th style="width: 4%; text-align:center;" class="chk-action">
                                          <input type="checkbox" name="ux_chk_all_forms" id="ux_chk_all_forms">
                                       </th>
                                       <?php
                                       if (isset($details_form_controls["controls"]) && count($details_form_controls["controls"]) > 0) {
                                          foreach ($details_form_controls["controls"] as $values) {
                                             $label_name = isset($values["admin_label"]) && $values["admin_label"] != "" ? esc_attr($values["admin_label"]) : ($values["label_name"] != "" ? esc_attr($values["label_name"]) : "Untitled");
                                             $control_name = isset($values["control_type"]) ? esc_attr($values["control_type"]) : "Untitled";
                                             if ($control_name == "credit_card") {
                                                ?>
                                                <th><?php echo $cb_general_credit_card_number; ?></th>
                                                <th><?php echo $cb_general_credit_card_expiry_date; ?></th>
                                                <th><?php echo $cb_general_credit_cvv_number; ?></th>
                                                <?php
                                             } else if ($control_name != "html" && $control_name != "divider" && $control_name != "recaptcha" && $control_name != "logical-captcha" && $control_name != "section_break" && $control_name != "credit_card" && $control_name != "anti_spam") {
                                                ?>
                                                <th><?php echo $label_name; ?></th>
                                                <?php
                                             }
                                          }
                                       }
                                       ?>
                                       <th>
                                          <?php echo $cb_date_time; ?>
                                       </th>
                                       <th class="chk-action">
                                          <?php echo $cb_actions; ?>
                                       </th>
                                    </tr>
                                 </thead>
                                 <tbody id="dynamic_table_filter">
                                    <?php
                                    for ($flag = 0; $flag < count($details_form_submissions); $flag++) {
                                       $detail_submission_data_array = $details_form_submissions[$flag];
                                       $submission_data_id = $detail_submission_data_array["id"];
                                       ?>
                                       <tr>
                                          <td class="chk-action">
                                             <input type="checkbox" name="ux_chk_submisions_<?php echo $submission_data_id; ?>" id="ux_chk_submisions_<?php echo $submission_data_id; ?>" onclick="check_all_contact_bank('#ux_chk_all_forms');" value="<?php echo $submission_data_id; ?>">
                                          </td>
                                          <?php
                                          foreach ($details_form_controls["controls"] as $values) {
                                             $label_name = isset($values["label_name"]) && $values["label_name"] != "" ? esc_attr($values["label_name"]) : "Untitled";
                                             $timestamp = isset($values["timestamp"]) && $values["timestamp"] != "" ? esc_attr($values["timestamp"]) : "0";
                                             $control_name = isset($values["control_type"]) ? esc_attr($values["control_type"]) : "Untitled";
                                             $detail_submission_array = $details_form_submissions[$flag];
                                             $submission_data = isset($detail_submission_array[$timestamp]) ? $detail_submission_array[$timestamp] : "";
                                             if ($control_name == "credit_card") {
                                                ?>
                                                <td> <?php echo isset($detail_submission_array["Credit Card Number"]) && $detail_submission_array["Credit Card Number"] != "" ? $detail_submission_array["Credit Card Number"] : $cb_not_available; ?></td>
                                                <td> <?php echo isset($detail_submission_array["Card Expiry Date"]) && $detail_submission_array["Card Expiry Date"] != "" ? $detail_submission_array["Card Expiry Date"] : $cb_not_available; ?></td>
                                                <td> <?php echo isset($detail_submission_array["CVV Number"]) && $detail_submission_array["CVV Number"] != "" ? $detail_submission_array["CVV Number"] : $cb_not_available; ?></td>
                                                <?php
                                             } else if ($control_name != "html" && $control_name != "divider" && $control_name != "recaptcha" && $control_name != "logical-captcha" && $control_name != "section_break" && $control_name != "anti_spam") {
                                                if ($control_name == "select" || $control_name == "radio-list") {
                                                   $unmatched_data = 0;
                                                   if ($submission_data != "" && $submission_data != "Untitled") {
                                                      if (isset($values["drop_down_option_values"]) && count($values["drop_down_option_values"]) > 0) {
                                                         foreach ($values["drop_down_option_values"] as $key => $value) {
                                                            if ($value->value == $submission_data) {
                                                               $unmatched_data = 1;
                                                               ?>
                                                               <td><?php echo $value->text; ?></td>
                                                               <?php
                                                            }
                                                         }
                                                      }
                                                      if ($unmatched_data == 0) {
                                                         ?>
                                                         <td><?php echo $submission_data; ?></td>
                                                         <?php
                                                      }
                                                   } else {
                                                      ?>
                                                      <td><?php echo $cb_not_available; ?></td>
                                                      <?php
                                                   }
                                                } else if ($control_name == "checkbox-list" || $control_name == "multi-select") {
                                                   $selected_values = explode(",", $submission_data);
                                                   $option_values = "";
                                                   if (isset($values["drop_down_option_values"]) && count($values["drop_down_option_values"]) > 0) {
                                                      foreach ($values["drop_down_option_values"] as $key => $value) {
                                                         if (in_array($value->value, $selected_values)) {
                                                            $option_values .= $value->text . ",";
                                                         }
                                                      }
                                                   }
                                                   ?>
                                                   <td><?php echo $option_values != "" ? rtrim($option_values, ",") : $cb_not_available; ?></td>
                                                   <?php
                                                } else if ($control_name == "checkbox") {
                                                   ?>
                                                   <td> <?php echo $submission_data != "" ? $submission_data : $cb_not_available; ?></td>
                                                   <?php
                                                } else if ($control_name == "date") {
                                                   ?>
                                                   <td><?php echo $submission_data != "" ? $submission_data : $cb_not_available; ?></td>
                                                   <?php
                                                } else if ($control_name == "star_rating") {
                                                   ?>
                                                   <td><?php echo $submission_data != "" ? $submission_data . " / " . intval($values["number_of_stars"]) : $cb_not_available; ?></td>
                                                   <?php
                                                } else if ($control_name == "file_upload") {
                                                   $files_uploaded = $submission_data != "" ? explode(",", $submission_data) : "";
                                                   if ($submission_data != "") {
                                                      $uploaded_files = "";
                                                      foreach ($files_uploaded as $files) {
                                                         $cb_uploaded_file_path = dirname(plugins_url()) . "/contact-bank/" . $files;
                                                         $uploaded_files .= "<a href='$cb_uploaded_file_path' style='text-decoration:none;' target='blank'>" . $files . "</a><br/>";
                                                      }
                                                   }
                                                   ?>
                                                   <td><?php echo $submission_data != "" ? $uploaded_files : $cb_not_available; ?></td>
                                                   <?php
                                                } else {
                                                   ?>
                                                   <td><?php echo $submission_data != "" ? $submission_data : $cb_not_available; ?></td>
                                                   <?php
                                                }
                                             }
                                          }
                                          ?>
                                          <td>
                                             <?php echo date_i18n("d M Y h:i A", $details_form_submissions[$flag]["timestamp"]); ?>
                                          </td>
                                          <td class="custom-alternative">
                                             <a href="javascript:void(0);" data-placement="right">
                                                <i class="icon-custom-trash tooltips" data-original-title="<?php echo $cb_add_delete; ?>" onclick="confirm_delete_contact_bank('<?php echo $submission_data_id; ?>', '<?php echo $cb_submission_single_delete_message; ?>', 'cb_submissions&form_id=<?php echo $form_id; ?>', 'delete_submission_module');"></i>
                                             </a>
                                          </td>
                                       </tr>
                                       <?php
                                    }
                                    ?>
                                 </tbody>
                              </table>
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
               <span>
                  <?php echo $cb_submissions; ?>
               </span>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="portlet box vivid-green">
               <div class="portlet-title">
                  <div class="caption">
                     <i class="icon-custom-note"></i>
                     <?php echo $cb_submissions; ?>
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