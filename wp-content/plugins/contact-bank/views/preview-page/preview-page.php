<?php
/**
 * This file is used for preview form in post.
 *
 * @author	Tech Banker
 * @package	contact-bank/views/preview-page
 * @version	3.0
 */
if (!defined("ABSPATH")) {
   exit;
}
if (count($form_unserialized_meta_value["controls"]) > 0) {
   function get_currency_symbol_contact_bank($country_code) {
      $country_code = strtoupper($country_code);
      $currency = array(
          "USD" => "&#36;", //U.S. Dollar
          "AUD" => "&#36;", //Australian Dollar
          "CAD" => "C&#36;", //Canadian Dollar
          "CZK" => "K&#269;", //Czech Koruna
          "DKK" => "kr", //Danish Krone
          "EUR" => "&euro;", //Euro
          "HKD" => "&#36", //Hong Kong Dollar
          "HUF" => "Ft", //Hungarian Forint
          "ILS" => "&#x20aa;", //Israeli New Sheqel
          "JPY" => "&yen;", //also use &#165; Japanese Yen
          "MXN" => "&#36", //Mexican Peso
          "NOK" => "kr", //Norwegian Krone
          "NZD" => "&#36", //New Zealand Dollar
          "PHP" => "&#x20b1;", //Philippine Peso
          "PLN" => "&#122;&#322;", //Polish Zloty
          "GBP" => "&pound;", //Pound Sterling
          "SGD" => "&#36;", //Singapore Dollar 
          "SEK" => "kr", //Swedish Krona 
          "CHF" => "Fr", //Swiss Franc
          "TWD" => "&#36;", //Taiwan New Dollar
          "THB" => "&#3647;", //Thai Baht
          "INR" => "&#8377;", //Indian Rupee
      );
      if (array_key_exists($country_code, $currency)) {
         return $currency[$country_code];
      }
   }
   $country_code = $selected_general_setting_unserialize["default_currency"];
   $contact_bank_currency_code = get_currency_symbol_contact_bank($country_code);
   ?>
   <div class="main_container_contact_bank_<?php echo $random; ?> form-layout-main-container-contact-bank_<?php echo $random; ?> language_direction_contact_bank_<?php echo $random; ?>">
      <form name="ux_frm_main_container_<?php echo $random; ?>" id="ux_frm_main_container_<?php echo $random; ?>">
         <div id="form_error_message_frontend_<?php echo $random; ?>" class="custom-message error-message" style="display: none; margin-bottom: 10px;">
            <span class="error_message_text">
               <strong><?php echo $form_unserialized_meta_value["form_submission_message"]; ?></strong>
            </span>
         </div>
         <div id="form_success_message_frontend_<?php echo $random; ?>" class="custom-message message-layout-contact-bank_<?php echo $random; ?>" style="display: none; margin-bottom: 10px;">
            <div id="success_message_text_<?php echo $random; ?>"></div>
         </div>
         <?php
         if (htmlspecialchars_decode($form_unserialized_meta_value["form_title"]) != "") {
            ?>
            <div class="form-layout-title-contact-bank_<?php echo $random; ?>">
               <<?php echo $layout_settings_form_design_title_html_tag; ?>><?php echo isset($form_unserialized_meta_value["form_title"]) ? htmlspecialchars_decode($form_unserialized_meta_value["form_title"]) : "Untitled Form"; ?></<?php echo $layout_settings_form_design_title_html_tag; ?>>
            </div>
            <?php
         }
         if (htmlspecialchars_decode($form_unserialized_meta_value["form_description"]) != "") {
            ?>
            <div class="form-layout-description-contact-bank_<?php echo $random; ?>">
               <<?php echo $layout_settings_form_design_description_html_tag; ?>><?php echo isset($form_unserialized_meta_value["form_description"]) ? htmlspecialchars_decode($form_unserialized_meta_value["form_description"]) : ""; ?> </<?php echo $layout_settings_form_design_description_html_tag; ?>>
            </div>
            <?php
         }
         $controls_ids = array();
         if (isset($form_unserialized_meta_value) && isset($form_unserialized_meta_value["controls"])) {
            if (count($form_unserialized_meta_value["controls"]) > 0) {
               foreach ($form_unserialized_meta_value["controls"] as $values) {
                  $timestamp = doubleval($values["timestamp"]);
                  array_push($controls_ids, $timestamp);
                  $label_placement = isset($values["label_placement"]) ? esc_attr($values["label_placement"]) : "above";
                  $max_limit = isset($values["input_limit_number"]) ? esc_attr($values["input_limit_number"]) : 50;
                  $text_appear = isset($values["text_appear"]) ? esc_attr($values["text_appear"]) : 'Characters Left';
                  $input_validation_type = isset($values["input_validation_type"]) && esc_attr($values["input_validation_type"]) == "characters" ? "onkeyup=\"only_characters_contact_bank_$random(event,$timestamp,'$max_limit','$text_appear');\"" : (isset($values["input_validation_type"]) && esc_attr($values["input_validation_type"]) == "digits" ? "onkeypress=\"only_digits_contact_bank_$random(event,$timestamp,'$max_limit','$text_appear')\"" : "");
                  $container_class = isset($values["container_class"]) && $values["container_class"] != "" ? esc_attr($values["container_class"]) : "";
                  $element_class = isset($values["element_class"]) && $values["element_class"] != "" ? esc_attr($values["element_class"]) : "";
                  $input_class = $label_placement == "left" ? "class='left-placement-input-contact-bank_$random untitled_control $element_class'" : ($label_placement == "right" ? "class='right-placement-input-contact-bank_$random untitled_control $element_class'" : "class='input-layout-field-contact-bank_$random untitled_control $element_class'");
                  $control_class = "class='sub_div $container_class'";
                  $text_appear_after_counter_class = "position:relative;";
                  $label_class = $label_placement == "left" ? "class='label_left_placement_$random control-label field_label'" : ($label_placement == "right" ? "class='label_left_placement_credit_card_$random control-label'" : "class='label-layout-field-contact-bank_$random control-label field_label'");
                  $right_label_placement = $label_placement == "right" ? "class='radio_list_label_right_placement_$random'" : "";
                  $control_name = isset($values["control_type"]) ? esc_attr($values["control_type"]) : "Text";
                  $label_style_class = "";
                  $input_style_class = "";
                  $onfocus_event = "";
                  $onkeyup_event = "";
                  $radio_button_alignment = "";
                  $checkbox_list_alignment = "";
                  $min_number = isset($values["min_number"]) ? esc_attr($values["min_number"]) : "";
                  $max_number = isset($values["max_number"]) ? esc_attr($values["max_number"]) : "";
                  $step_number_cb = isset($values["step"]) ? esc_attr($values["step"]) : "";
                  switch ($control_name) {
                     case "number":
                        $onkeyup_event = "onkeyup=number_settings_contact_bank_$random($timestamp,event,$step_number_cb,$min_number,$max_number);";
                        break;
                     case "checkbox":
                        $label_class = $label_placement == "left" ? "class='label_left_placement_$random control-label field_label'" : ($label_placement == "right" ? "class='checkbox_label_right_placement_$random control-label field_label'" : "class='label-layout-field-contact-bank_$random control-label field_label'");
                        $input_class = $label_placement == "right" ? "class = 'checkbox_input_right_placement_$random'" : "class='input-layout-field-contact-bank_$random checkbox_class $element_class'";
                        break;
                     case "checkbox-list":
                        if ($layout_settings_input_field_checkbox_alignment == "single_row" && $label_placement == "left") {
                           $checkbox_list_alignment = "float:left,width:60%;";
                        } else if ($layout_settings_input_field_radio_button_alignment == "multiple_row" && $label_placement == "left") {
                           $checkbox_list_alignment = "float:left";
                        }
                        $input_class = "class='checkbox_class_$timestamp $element_class'";
                        $label_class = $label_placement == "left" ? "class='label_left_placement_$random control-label field_label'" : ($label_placement == "right" ? "class='radio_list_label_$random control-label field_label'" : "class='label-layout-field-contact-bank_$random control-label field_label'");
                        break;
                     case "radio-list":
                        if ($layout_settings_input_field_radio_button_alignment == "single_row" && $label_placement == "left") {
                           $radio_button_alignment = "float:left,width:60%;";
                        } else if ($layout_settings_input_field_radio_button_alignment == "multiple_row" && $label_placement == "left") {
                           $radio_button_alignment = "float:left";
                        }
                        $input_class = "class='checkbox_class_$timestamp $element_class'";
                        $label_class = $label_placement == "left" ? "class='label_left_placement_$random control-label field_label'" : ($label_placement == "right" ? "class='radio_list_label_$random control-label field_label'" : "class='label-layout-field-contact-bank_$random control-label field_label'");
                        break;
                     case "email":
                        $input_validation_type = "";
                        break;
                  }
                  ?>
                  <div style="clear:both;"<?php echo isset($control_class) ? $control_class : ""; ?> name="ux_sub_div_<?php echo $timestamp; ?>" id="ux_sub_div_<?php echo $timestamp; ?>">
                     <label style="<?php echo isset($label_style_class) ? $label_style_class : ""; ?>" <?php echo isset($label_class) ? $label_class : ""; ?> name="field_label_<?php echo $timestamp; ?>" id="field_label_<?php echo $timestamp; ?>">
                        <span  name="ux_label_title_<?php echo $timestamp; ?>" id="ux_label_title_<?php echo $timestamp; ?>" value="<?php echo isset($values['label_name']) ? esc_attr($values['label_name']) : ''; ?>"><?php echo isset($values["label_name"]) && esc_attr($values["label_name"]) != "" ? esc_attr($values["label_name"]) : "Untitled"; ?></span> :
                        <?php
                        if (isset($form_unserialized_meta_value["form_enable_tooltip"]) && $form_unserialized_meta_value["form_enable_tooltip"] == "show") {
                           ?>
                           <i class="icon-custom-question tooltips label_tooltip_contact_bank" name="ux_tooltip_title_<?php echo $timestamp; ?>" id="ux_tooltip_title_<?php echo $timestamp; ?>" data-original-title="<?php echo isset($values["label_tooltip"]) ? esc_attr($values["label_tooltip"]) : ""; ?>" data-placement="right"></i>
                           <?php
                        }
                        ?>
                        <span class="required" style="<?php echo isset($values["required_type"]) && esc_attr($values["required_type"]) == "enable" ? "display:" : "display:none"; ?>" aria-required="true" name="ux_required_<?php echo $timestamp; ?>" id="ux_required_<?php echo $timestamp; ?>">*</span>
                     </label>
                     <?php
                     if ($control_name == "paragraph") {
                        ?>
                        <textarea rows="<?php echo isset($values["rows_number"]) ? intval($values["rows_number"]) : ""; ?>" style="<?php echo $input_style_class; ?>" placeholder="<?php echo isset($values["placeholder"]) ? esc_attr($values["placeholder"]) : ""; ?>" name="ux_txt_singal_line_text_<?php echo $timestamp; ?>" <?php echo $input_validation_type; ?> id="ux_txt_singal_line_text_<?php echo $timestamp; ?>"  <?php echo $input_class; ?> type="text" autocomplete="<?php echo isset($values["autocomplete_type"]) && esc_attr($values["autocomplete_type"]) == "enable" ? "off" : "on"; ?>" <?php echo isset($values["disable_input"]) && esc_attr($values["disable_input"]) == "enable" ? "disabled=disabled" : ""; ?>><?php echo isset($values["default_value"]) ? esc_attr($values["default_value"]) : ""; ?></textarea>
                        <?php
                     } else if ($control_name == "select") {
                        ?>
                        <select style="<?php echo $input_style_class; ?>" name="ux_txt_singal_line_text_<?php echo $timestamp; ?>" id="ux_txt_singal_line_text_<?php echo $timestamp; ?>" <?php echo $input_class; ?> type="text" autocomplete="<?php echo isset($values["autocomplete_type"]) && esc_attr($values["autocomplete_type"]) == "enable" ? "off" : "on"; ?>" <?php echo isset($values["disable_input"]) && esc_attr($values["disable_input"]) == "enable" ? "disabled=disabled" : ""; ?>>
                           <?php
                           if (isset($values["drop_down_option_values"]) && count($values["drop_down_option_values"]) > 0) {
                              foreach ($values["drop_down_option_values"] as $key => $value) {
                                 $option_value = esc_attr($values["default_value"]) == $value->value ? "selected=selected" : "";
                                 ?>
                                 <option <?php echo $option_value; ?> value="<?php echo $value->value; ?>"><?php echo $value->text; ?></option>
                                 <?php
                              }
                           }
                           ?>
                        </select>
                        <?php
                     } else if ($control_name == "email") {
                        ?>
                        <input style="<?php echo $input_style_class; ?>" <?php echo $onfocus_event; ?> placeholder="<?php echo isset($values["placeholder"]) ? esc_attr($values["placeholder"]) : ""; ?>" name="ux_txt_singal_line_text_<?php echo $timestamp; ?>" <?php echo $input_validation_type; ?> id="ux_txt_singal_line_text_<?php echo $timestamp; ?>" <?php echo $input_class; ?> type="email" value="<?php echo isset($values["default_value"]) ? esc_attr($values["default_value"]) : ""; ?>" autocomplete="<?php echo isset($values["autocomplete_type"]) && esc_attr($values["autocomplete_type"]) == "enable" ? "off" : "on"; ?>" <?php echo isset($values["disable_input"]) && esc_attr($values["disable_input"]) == "enable" ? "disabled=disabled" : ""; ?> <?php echo $onkeyup_event; ?>>
                        <?php
                     } else if ($control_name == "checkbox") {
                        ?>
                        <input type="checkbox"  class="checkbox_class" name="ux_txt_singal_line_text_<?php echo $timestamp; ?>" <?php echo $input_validation_type; ?> id="ux_txt_singal_line_text_<?php echo $timestamp; ?>" <?php echo $input_class; ?> type="text"  autocomplete="<?php echo isset($values["autocomplete_type"]) && esc_attr($values["autocomplete_type"]) == "enable" ? "off" : "on"; ?>" <?php echo isset($values["disable_input"]) && esc_attr($values["disable_input"]) == "enable" ? "disabled=disabled" : ""; ?>><?php echo isset($values["default_value"]) ? esc_attr($values["default_value"]) : ""; ?>
                        <?php
                     } else if ($control_name == "checkbox-list") {
                        ?>
                        <span name="ux_txt_check_box_<?php echo $timestamp; ?>" id="ux_txt_check_box_<?php echo $timestamp; ?>" class="checkbox-label">
                           <label id="field_labels_<?php echo $timestamp; ?>" name="field_labels_<?php echo $timestamp; ?>" <?php echo $right_label_placement; ?> style="<?php echo $checkbox_list_alignment; ?>">
                              <?php
                              if (isset($values["drop_down_option_values"]) && count($values["drop_down_option_values"]) > 0) {
                                 foreach ($values["drop_down_option_values"] as $key => $value) {
                                    ?>
                                    <input class="input_chk_button_contact_bank_<?php echo $random; ?>" type="checkbox" name="ux_txt_check_box_lists_<?php echo $timestamp; ?>[]" id="ux_txt_check_box_lists_<?php echo $timestamp; ?>"<?php echo $input_validation_type; ?> value="<?php echo $value->value; ?>" <?php echo $input_class; ?>><label name="ux_chk_label_lists_<?php echo $timestamp; ?>" class="input_chk_button_label_contact_bank_<?php echo $random; ?>" id="ux_chk_label_lists_<?php echo $timestamp; ?>" value="<?php echo $value->value; ?>"><?php echo $value->text; ?></label>
                                    <?php
                                 }
                              }
                              ?>
                           </label>
                        </span>
                        <?php
                     } else if ($control_name == "radio-list") {
                        ?>
                        <span name="ux_txt_check_box_<?php echo $timestamp; ?>" id="ux_txt_check_box_<?php echo $timestamp; ?>" class= "checkbox-label">
                           <label id="field_labels_<?php echo $timestamp; ?>" name="field_labels_<?php echo $timestamp; ?>" <?php echo $right_label_placement; ?> style="<?php echo $radio_button_alignment; ?>">
                              <?php
                              if (isset($values["drop_down_option_values"]) && count($values["drop_down_option_values"]) > 0) {
                                 foreach ($values["drop_down_option_values"] as $key => $value) {
                                    ?>
                                    <input class="input_radio_button_contact_bank_<?php echo $random; ?>" type="radio" name="ux_txt_check_box_lists_<?php echo $timestamp; ?>" id="ux_txt_check_box_lists_<?php echo $timestamp; ?>" value="<?php echo $value->value; ?>" checked=checked  <?php echo $input_class; ?>><label name="ux_chk_label_lists_<?php echo $timestamp; ?>" class="input_radio_button_label_contact_bank_<?php echo $random; ?>" id="ux_chk_label_lists_<?php echo $timestamp; ?>" value="<?php echo $value->value; ?>"><?php echo $value->text; ?></label>
                                    <?php
                                 }
                              }
                              ?>
                           </label>
                        </span>
                        <?php
                     } else {
                        ?>
                        <input style="<?php echo $input_style_class; ?>" <?php echo $onfocus_event; ?> placeholder="<?php echo isset($values["placeholder"]) ? esc_attr($values["placeholder"]) : ""; ?>" name="ux_txt_singal_line_text_<?php echo $timestamp; ?>" <?php echo $input_validation_type; ?> id="ux_txt_singal_line_text_<?php echo $timestamp; ?>" <?php echo $input_class; ?> type="text" value="<?php echo isset($values["default_value"]) ? esc_attr($values["default_value"]) : ""; ?>" autocomplete="<?php echo isset($values["autocomplete_type"]) && esc_attr($values["autocomplete_type"]) == "enable" ? "off" : "on"; ?>" <?php echo isset($values["disable_input"]) && esc_attr($values["disable_input"]) == "enable" ? "disabled=disabled" : ""; ?> <?php echo $onkeyup_event; ?>>
                        <?php
                     }
                     ?>
                     <input type="hidden" name="ux_txt_hidden_label_<?php echo $timestamp; ?>" id="ux_txt_hidden_label_<?php echo $timestamp; ?>" value="<?php echo isset($values['label_name']) ? esc_attr($values['label_name']) : ''; ?>">
                     <span class="cb-limit-input control-label field_label" style="display:none;<?php echo $text_appear_after_counter_class; ?>" name="ux_text_appear_after_counter_<?php echo $timestamp; ?>" id="ux_text_appear_after_counter_<?php echo $timestamp; ?>"><?php echo isset($values["text_appear"]) ? esc_attr($values["text_appear"]) : "Characters Left"; ?></span>
                  </div>
                  <input type="hidden" name="ux_txt_hidden_control_type_<?php echo $timestamp; ?>" id="ux_txt_hidden_control_type_<?php echo $timestamp; ?>" value="<?php echo isset($values["control_type"]) ? esc_attr($values["control_type"]) : ""; ?>">
                  <?php
               }
            }
         }
         ?>
         <div style="clear:both;">
            <input type="submit" class="button-layout-contact-bank_<?php echo $random; ?>" name="ux_btn_save_changes" id="ux_btn_save_changes" onclick="submit_form_contact_bank_<?php echo $random; ?>(<?php echo json_encode($controls_ids); ?>, '#ux_frm_main_container_<?php echo $random; ?>', '<?php echo $form_unserialized_meta_value["form_save_submission_to_db"]; ?>', '<?php echo $form_unserialized_meta_value["form_redirect_page_url"]; ?>', '<?php echo $form_unserialized_meta_value["form_submission_limit_message"]; ?>', '<?php echo $form_unserialized_meta_value["form_submission_limit"]; ?>', '<?php echo $form_unserialized_meta_value["form_redirect"]; ?>', '<?php echo $form_unserialized_meta_value["form_redirect_url"]; ?>', '<?php echo sanitize_text_field($form_unserialized_meta_value["form_success_message"]); ?>')" value="<?php echo $layout_settings_button_text; ?>">
         </div>
      </form>
   </div>
   <?php
}