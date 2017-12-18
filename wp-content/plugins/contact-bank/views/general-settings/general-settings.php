<?php
/**
 * Template for general settings.
 *
 * @author 	Tech Banker
 * @package 	contact-bank/views/general-settings
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
   } else if (general_settings_contact_bank == "1") {
      $general_settings_nonce = wp_create_nonce("general_settings_nonce");
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
                  <?php echo $cb_general_settings; ?>
               </span>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="portlet box vivid-green">
               <div class="portlet-title">
                  <div class="caption">
                     <i class="icon-custom-frame"></i>
                     <?php echo $cb_general_settings; ?>
                  </div>
                  <p class="premium-editions">
                     <?php echo $cb_upgrade_need_help ?><a href="https://contact-bank.tech-banker.com/" target="_blank" class="premium-editions-documentation"><?php echo $cb_documentation ?></a><?php echo $cb_read_and_check; ?><a href="https://contact-bank.tech-banker.com/frontend-demos/" target="_blank" class="premium-editions-documentation"><?php echo $cb_demos_section; ?></a>
                  </p>
               </div>
               <div class="portlet-body form">
                  <form id="ux_frm_general_settings">
                     <div class="form-body">
                        <div class="form-group">
                           <label class="control-label">
                              <?php echo $cb_general_settings_remove_table_at_uninstall_title; ?> :
                              <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_general_settings_remove_table_at_uninstall_tooltip; ?>" data-placement="right"></i>
                              <span class="required" aria-required="true">*</span>
                           </label>
                           <select id="ux_ddl_remove_table" name="ux_ddl_remove_table" class="form-control">
                              <option value="enable"><?php echo $cb_enable; ?></option>
                              <option value="disable"><?php echo $cb_disable; ?></option>
                           </select>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="control-label">
                                    <?php echo $cb_default_currency; ?> :
                                    <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_default_currency_tooltip; ?>" data-placement="right"></i>
                                    <span class="required" aria-required="true">*</span>
                                 </label>
                                 <select id="ux_ddl_default_currency" name="ux_ddl_default_currency" class="form-control">
                                    <option value="USD"><?php echo $cb_us_dollars; ?></option>

                                    <option value="AUD"><?php echo $cb_australian_dollars; ?></option>

                                    <option value="CAD"><?php echo $cb_canadian_dollars; ?></option>

                                    <option value="CZK"><?php echo $cb_crech_koruna; ?></option>

                                    <option value="DKK"><?php echo $cb_danish_krone; ?></option>

                                    <option value="EUR"><?php echo $cb_euros; ?></option>

                                    <option value="HKD"><?php echo $cb_hong_kong_dollars; ?></option>

                                    <option value="HUF"><?php echo $cb_hungarian_forints; ?></option>

                                    <option value="ILS"><?php echo $cb_israeli_new_sheqels; ?></option>

                                    <option value="JPY"><?php echo $cb_japanese_yen; ?></option>

                                    <option value="MXN"><?php echo $cb_mexican_pesos; ?></option>

                                    <option value="NOK"><?php echo $cb_norwegian_krone; ?></option>

                                    <option value="NZD"><?php echo $cb_new_zealanddollars; ?></option>

                                    <option value="PHP"><?php echo $cb_philippine_pesos; ?></option>

                                    <option value="PLN"><?php echo $cb_polish_zloty; ?></option>

                                    <option value="GBP"><?php echo $cb_british_pounds_sterling; ?></option>

                                    <option value="SGD"><?php echo $cb_singapore_dollars; ?></option>

                                    <option value="SEK"><?php echo $cb_swedish_krona; ?></option>

                                    <option value="CHF"><?php echo $cb_swiss_franc; ?></option>

                                    <option value="TWD"><?php echo $cb_taiwan_new_dollars; ?></option>

                                    <option value="THB"><?php echo $cb_thai_baht; ?></option>

                                    <option value="INR"><?php echo $cb_indian_rupee; ?></option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="control-label">
                                    <?php echo $cb_language_direction; ?> :
                                    <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_language_direction_tooltip; ?>" data-placement="right"></i>
                                    <span class="required" aria-required="true">*</span>
                                 </label>
                                 <select id="ux_ddl_language_direction" name="ux_ddl_language_direction" class="form-control">
                                    <option value="right_to_left"><?php echo $cb_right_to_left; ?></option>
                                    <option value="left_to_right"><?php echo $cb_left_to_right; ?></option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="control-label">
                                    <?php echo $cb_recaptcha_public_key; ?> :
                                    <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_recaptcha_public_key_tooltip; ?>" data-placement="right"></i>
                                    <span class="required" aria-required="true">* <?php echo "( " . $cb_premium_edition . " )"; ?></span>
                                 </label>
                                 <input type="text" name="ux_txt_recaptcha_public_key" id="ux_txt_recaptcha_public_key" placeholder="<?php echo $cb_recaptcha_public_key_placeholder; ?>" class="form-control" value="<?php echo isset($details_general_settings["recaptcha_public_key"]) ? esc_attr($details_general_settings["recaptcha_public_key"]) : ""; ?>">
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="control-label">
                                    <?php echo $cb_recaptcha_private_key; ?> :
                                    <i class="icon-custom-question tooltips" data-original-title="<?php echo $cb_recaptcha_private_key_tooltip; ?>" data-placement="right"></i>
                                    <span class="required" aria-required="true">* <?php echo "( " . $cb_premium_edition . " )"; ?></span>
                                 </label>
                                 <input type="text" name="ux_txt_recaptcha_private_key" id="ux_txt_recaptcha_private_key" placeholder="<?php echo $cb_recaptcha_private_key_placeholder; ?>" class="form-control"  value="<?php echo isset($details_general_settings["recaptcha_private_key"]) ? esc_attr($details_general_settings["recaptcha_private_key"]) : ""; ?>">
                              </div>
                           </div>
                        </div>
                        <div class="line-separator"></div>
                        <div class="form-actions">
                           <div class="pull-right">
                              <input type="submit" class="btn vivid-green" name="ux_btn_save_changes" id="ux_btn_save_changes" value="<?php echo $cb_save_changes; ?>">
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
                  <?php echo $cb_general_settings; ?>
               </span>
            </li>
         </ul>
      </div>

      <div class="row">
         <div class="col-md-12">
            <div class="portlet box vivid-green">
               <div class="portlet-title">
                  <div class="caption">
                     <i class="icon-custom-frame"></i>
                     <?php echo $cb_general_settings; ?>
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