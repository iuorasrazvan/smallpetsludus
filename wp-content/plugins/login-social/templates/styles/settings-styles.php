<?php
/**
 * Style options
 */
?>
<style>
    <?php
      $google_fonts = array(
              HG_Login()->settings->popup_body_text_style,
              HG_Login()->settings->popup_header_text_style,
              HG_Login()->settings->popup_footer_text_style,
              HG_Login()->settings->login_btn_font_style,
              HG_Login()->settings->signup_btn_font_style
          );
      $import_fonts=array();

      foreach ($google_fonts as $val){

          $val = trim($val);
          $val = (strpos($val,' sans-serif'))?str_ireplace(' sans-serif','',$val):$val;
          $val = (strpos($val,' serif'))?str_ireplace(' serif','',$val):$val;
          $val = (strpos($val,' cursive'))?str_ireplace(' cursive','',$val):$val;
          $val = (strpos($val,','))?str_ireplace(',','',$val):$val;
          $val = (strpos($val,' '))?str_ireplace(' ','+',$val):$val;


          $import_fonts[]=$val;


      }


      ?>
    @import url('https://fonts.googleapis.com/css?family=<?php echo implode('|',$import_fonts); ?>');



    .hg-login-modal-container {
        background-color: #<?php echo HG_Login()->settings->popup_bg_color; ?>;
        border-radius: <?php echo HG_Login()->settings->popup_border_radius; ?>px;
        border-width: <?php echo HG_Login()->settings->popup_border_width; ?>px;
        border-style: <?php echo HG_Login()->settings->popup_border_style; ?>;
        border-color: <?php echo hg_login_hex2rgba( HG_Login()->settings->popup_border_color, HG_Login()->settings->popup_border_opacity ); ?>;
    }
    .hg-login-modal-container, label {
        font-family: <?php echo HG_Login()->settings->popup_body_text_style; ?>;

    }

    .hg-login-modal-header {
        background-color: #<?php echo HG_Login()->settings->popup_header_bg_color; ?>;
    }

    .hg-login-modal-header,
    .hg-login-modal-header h4,
    .hg-login-modal-header h3 {
        color: #<?php echo HG_Login()->settings->popup_header_text_color; ?>;
        font-family: <?php echo HG_Login()->settings->popup_header_text_style; ?>;
    }

    .view-flat.type-light {
        color: #<?php echo HG_Login()->settings->popup_close_btn_color; ?>;
    }

    .modal-auth--social-button .-facebook {
        color: #<?php echo HG_Login()->settings->popup_fb_btn_text_color; ?>;
        background-color: #<?php echo HG_Login()->settings->popup_fb_btn_bg_color; ?>;
    }

    .hg-login-modal-footer {
        background-color: #<?php echo HG_Login()->settings->popup_footer_bg_color; ?>;
        color: #<?php echo HG_Login()->settings->popup_footer_text_color; ?>
        font-family: <?php echo HG_Login()->settings->popup_footer_text_style; ?>;
    }

    .hg-login-modal-input input[type="color"],
    .hg-login-modal-input input[type="date"],
    .hg-login-modal-input input[type="datetime"],
    .hg-login-modal-input input[type="datetime-local"],
    .hg-login-modal-input input[type="email"],
    .hg-login-modal-input input[type="month"],
    .hg-login-modal-input input[type="number"],
    .hg-login-modal-input input[type="password"],
    .hg-login-modal-input input[type="search"],
    .hg-login-modal-input input[type="tel"],
    .hg-login-modal-input input[type="text"],
    .hg-login-modal-input input[type="time"],
    .hg-login-modal-input input[type="url"],
    .hg-login-modal-input input[type="week"],
    .hg-login-modal-input textarea {
        background-color: #<?php echo HG_Login()->settings->popup_input_bg_color; ?>;
        box-shadow:0 0 0 1000px #<?php echo HG_Login()->settings->popup_input_bg_color; ?> inset;
        border-color: #<?php echo HG_Login()->settings->popup_input_text_color; ?>;
        color: #<?php echo HG_Login()->settings->popup_input_text_color; ?>;
    }

    .hg-login-modal-input input:-webkit-autofill,
    .hg-login-modal-input textarea:-webkit-autofill,
    .hg-login-modal-input select:-webkit-autofill {
        background-color: #<?php echo HG_Login()->settings->popup_input_bg_color; ?>;
        box-shadow:0 0 0 1000px #<?php echo HG_Login()->settings->popup_input_bg_color; ?> inset;
    }

    .hg-login-modal-input input[type="color"]:focus,
    .hg-login-modal-input input[type="color"]:valid,
    .hg-login-modal-input input[type="date"]:focus,
    .hg-login-modal-input input[type="date"]:valid,
    .hg-login-modal-input input[type="datetime"]:focus,
    .hg-login-modal-input input[type="datetime"]:valid,
    .hg-login-modal-input input[type="datetime-local"]:focus,
    .hg-login-modal-input input[type="datetime-local"]:valid,
    .hg-login-modal-input input[type="email"]:focus,
    .hg-login-modal-input input[type="email"]:valid,
    .hg-login-modal-input input[type="month"]:focus,
    .hg-login-modal-input input[type="month"]:valid,
    .hg-login-modal-input input[type="number"]:focus,
    .hg-login-modal-input input[type="number"]:valid,
    .hg-login-modal-input input[type="password"]:focus,
    .hg-login-modal-input input[type="password"]:valid,
    .hg-login-modal-input input[type="search"]:focus,
    .hg-login-modal-input input[type="search"]:valid,
    .hg-login-modal-input input[type="tel"]:focus,
    .hg-login-modal-input input[type="tel"]:valid,
    .hg-login-modal-input input[type="text"]:focus,
    .hg-login-modal-input input[type="text"]:valid,
    .hg-login-modal-input input[type="time"]:focus,
    .hg-login-modal-input input[type="time"]:valid,
    .hg-login-modal-input input[type="url"]:focus,
    .hg-login-modal-input input[type="url"]:valid,
    .hg-login-modal-input input[type="week"]:focus,
    .hg-login-modal-input input[type="week"]:valid,
    .hg-login-modal-input textarea:focus,
    .hg-login-modal-input textarea:valid {
        box-shadow:0 0 0 1000px #<?php echo HG_Login()->settings->popup_input_focused_bg_color; ?> inset;

    }

    .hg-login-modal-input input[type="color"]:focus,
    .hg-login-modal-input input[type="date"]:focus,
    .hg-login-modal-input input[type="datetime"]:focus,
    .hg-login-modal-input input[type="datetime-local"]:focus,
    .hg-login-modal-input input[type="email"]:focus,
    .hg-login-modal-input input[type="month"]:focus,
    .hg-login-modal-input input[type="number"]:focus,
    .hg-login-modal-input input[type="password"]:focus,
    .hg-login-modal-input input[type="search"]:focus,
    .hg-login-modal-input input[type="tel"]:focus,
    .hg-login-modal-input input[type="text"]:focus,
    .hg-login-modal-input input[type="time"]:focus,
    .hg-login-modal-input input[type="url"]:focus,
    .hg-login-modal-input input[type="week"]:focus,
    .hg-login-modal-input textarea:focus {
        color:#<?php echo HG_Login()->settings->popup_input_focused_text_color; ?>;
    }


    .hg-login-modal-input input[type="color"] ~ label,
    .hg-login-modal-input input[type="date"] ~ label,
    .hg-login-modal-input input[type="datetime"] ~ label,
    .hg-login-modal-input input[type="datetime-local"] ~ label,
    .hg-login-modal-input input[type="email"] ~ label,
    .hg-login-modal-input input[type="month"] ~ label,
    .hg-login-modal-input input[type="number"] ~ label,
    .hg-login-modal-input input[type="password"] ~ label,
    .hg-login-modal-input input[type="search"] ~ label,
    .hg-login-modal-input input[type="tel"] ~ label,
    .hg-login-modal-input input[type="text"] ~ label,
    .hg-login-modal-input input[type="time"] ~ label,
    .hg-login-modal-input input[type="url"] ~ label,
    .hg-login-modal-input input[type="week"] ~ label,
    .hg-login-modal-input textarea ~ label {
        color: #<?php echo HG_Login()->settings->popup_input_label_color; ?>;
    }

    .hg-login-modal-input input[type="color"]:focus ~ label,
    .hg-login-modal-input input[type="color"]:valid ~ label,
    .hg-login-modal-input input[type="date"]:focus ~ label,
    .hg-login-modal-input input[type="date"]:valid ~ label,
    .hg-login-modal-input input[type="datetime"]:focus ~ label,
    .hg-login-modal-input input[type="datetime"]:valid ~ label,
    .hg-login-modal-input input[type="datetime-local"]:focus ~ label,
    .hg-login-modal-input input[type="datetime-local"]:valid ~ label,
    .hg-login-modal-input input[type="email"]:focus ~ label,
    .hg-login-modal-input input[type="email"]:valid ~ label,
    .hg-login-modal-input input[type="month"]:focus ~ label,
    .hg-login-modal-input input[type="month"]:valid ~ label,
    .hg-login-modal-input input[type="number"]:focus ~ label,
    .hg-login-modal-input input[type="number"]:valid ~ label,
    .hg-login-modal-input input[type="password"]:focus ~ label,
    .hg-login-modal-input input[type="password"]:valid ~ label,
    .hg-login-modal-input input[type="search"]:focus ~ label,
    .hg-login-modal-input input[type="search"]:valid ~ label,
    .hg-login-modal-input input[type="tel"]:focus ~ label,
    .hg-login-modal-input input[type="tel"]:valid ~ label,
    .hg-login-modal-input input[type="text"]:focus ~ label,
    .hg-login-modal-input input[type="text"]:valid ~ label,
    .hg-login-modal-input input[type="time"]:focus ~ label,
    .hg-login-modal-input input[type="time"]:valid ~ label,
    .hg-login-modal-input input[type="url"]:focus ~ label,
    .hg-login-modal-input input[type="url"]:valid ~ label,
    .hg-login-modal-input input[type="week"]:focus ~ label,
    .hg-login-modal-input input[type="week"]:valid ~ label,
    .hg-login-modal-input textarea:focus ~ label,
    .hg-login-modal-input textarea:valid ~ label {
        color: #<?php echo HG_Login()->settings->popup_input_focused_label_color; ?>;
    }

    .hg-login-modal-input input[type="color"] ~ label sup,
    .hg-login-modal-input input[type="date"].invalid ~ label sup,
    .hg-login-modal-input input[type="datetime"].invalid ~ label sup,
    .hg-login-modal-input input[type="datetime-local"].invalid ~ label sup,
    .hg-login-modal-input input[type="email"].invalid ~ label sup,
    .hg-login-modal-input input[type="month"].invalid ~ label sup,
    .hg-login-modal-input input[type="number"].invalid ~ label sup,
    .hg-login-modal-input input[type="password"].invalid ~ label sup,
    .hg-login-modal-input input[type="search"].invalid ~ label sup,
    .hg-login-modal-input input[type="tel"].invalid ~ label sup,
    .hg-login-modal-input input[type="text"].invalid ~ label sup,
    .hg-login-modal-input input[type="time"].invalid ~ label sup,
    .hg-login-modal-input input[type="url"].invalid ~ label sup,
    .hg-login-modal-input input[type="week"].invalid ~ label sup,
    .hg-login-modal-input input[type="checkbox"].invalid ~ label sup,
    .hg-login-modal-input textarea:focus ~ label {
        color: #<?php echo HG_Login()->settings->popup_input_error_color; ?>;
    }

    .hg-login-modal-input input[type="color"] ~ span,
    .hg-login-modal-input input[type="date"] ~ span,
    .hg-login-modal-input input[type="datetime"] ~ span,
    .hg-login-modal-input input[type="datetime-local"] ~ span,
    .hg-login-modal-input input[type="email"] ~ span,
    .hg-login-modal-input input[type="month"] ~ span,
    .hg-login-modal-input input[type="number"] ~ span,
    .hg-login-modal-input input[type="password"] ~ span,
    .hg-login-modal-input input[type="search"] ~ span,
    .hg-login-modal-input input[type="tel"] ~ span,
    .hg-login-modal-input input[type="text"] ~ span,
    .hg-login-modal-input input[type="time"] ~ span,
    .hg-login-modal-input input[type="url"] ~ span,
    .hg-login-modal-input input[type="week"] ~ span,
    .hg-login-modal-input textarea ~ span {
        background-color: #<?php echo HG_Login()->settings->popup_input_focused_border_color; ?>;
    }

    .-button.-view-flat.-type-primary {
        color: #<?php echo HG_Login()->settings->popup_primary_btn_color; ?>;
        background-color: #<?php echo HG_Login()->settings->popup_primary_btn_bg_color; ?>;
    }

    .-button.-view-flat.-type-primary:hover {
        color: #<?php echo HG_Login()->settings->popup_primary_btn_hover_color; ?>;
        background-color: #<?php echo HG_Login()->settings->popup_primary_btn_hover_bg_color; ?>;
    }

    .-button.-view-flat {
        color: #<?php echo HG_Login()->settings->popup_secondary_btn_color; ?>;
    }

    .-button.-view-flat:hover {
        color: #<?php echo HG_Login()->settings->popup_secondary_btn_hover_color; ?>;
    }

    #hg_login_primary_button {
        background-color: #<?php echo HG_Login()->settings->login_btn_bg_color; ?>;
        color: #<?php echo HG_Login()->settings->login_btn_text_color; ?>;
        font-size: <?php echo HG_Login()->settings->login_btn_text_size; ?>px;
        font-family: <?php echo HG_Login()->settings->login_btn_font_style; ?>;
    }


    #hg_login_primary_button:hover {
        background-color: #<?php echo HG_Login()->settings->login_btn_hover_bg_color; ?>;
        color: #<?php echo HG_Login()->settings->login_btn_hover_text_color; ?>;
    }

    #hg_signup_primary_button {
        background-color: #<?php echo HG_Login()->settings->signup_btn_bg_color; ?>;
        color: #<?php echo HG_Login()->settings->signup_btn_text_color; ?>;
        font-size: <?php echo HG_Login()->settings->signup_btn_text_size; ?>px;
        font-family: <?php echo HG_Login()->settings->signup_btn_font_style; ?>;
    }

    #hg_signup_primary_button:hover {
        background-color: #<?php echo HG_Login()->settings->signup_btn_hover_bg_color; ?>;
        color: #<?php echo HG_Login()->settings->signup_btn_hover_text_color; ?>;
    }
    /* Check FB and G+ enabled */
    <?php if( HG_Login()->settings->facebook_enabled === 'yes' && HG_Login()->settings->google_sign_in_enabled === 'yes'): ?>
    @import url('https://fonts.googleapis.com/css?family=Roboto');
    .hg_enabl_both {
        text-align: center;
    }
    .hg_enabl_both .signup-social,.login{
        display: inline-block;
    }
    .hg_enabl_both .modal-auth--social-button {
        width:112px;
    }
    .hg_enabl_both .-facebook {
        height: 31px;
    }
    .hg_enabl_both .-size-24 {
        margin-bottom: 8px;
    }
    .hg_enabl_both .hg-login-via-social {
        margin-bottom: 20px;
        margin-top: 15px;
    }

    .hg_enabl_both .hg-g-icon {
        background: no-repeat white center url("<?php echo HG_LOGIN_IMAGES_URL."/social/gicon.png"; ?>");
        height: 32px;
        margin-top: -1px;
        width: 32px;
        border-color: rgba(0,0,0,.25);
        border-right-width: 1px;
        -webkit-background-size: 18px 18px;
        background-size: 18px 18px;
        margin-top: -1px;
        border-style: solid;
        border-width: 0;
        display: inline-block;
        vertical-align: top;
    }
    .hg_enabl_both .hg-g-text {
        box-shadow: 0 1px 0 rgba(0,0,0,0.10);
        padding: 0 18px;
        -webkit-border-radius: 0 3px 3px 0;
        border-radius: 0 3px 3px 0;
        display: inline-block;
    }
    .hg_enabl_both .hg-login-google-button {
        cursor: pointer;
        font-size: 12px;
        height: 31px;
        line-height: 29px;
        font-weight: bold;
        background-color: #4285f4;
        border: 1px solid #4285f4;
        color: #fff;
        -webkit-box-shadow: 0 1px 0 rgba(0,0,0,0.10);
        box-shadow: 0 1px 0 rgba(0,0,0,0.10);
        -webkit-border-radius: 3px;
        border-radius: 3px;
        -webkit-user-select: none;
        -webkit-font-smoothing: antialiased;
        display: inline-block;
        font-family: Roboto,arial,sans-serif;
        outline: none;
        overflow: hidden;
        position: relative;
        text-decoration: none;
        white-space: nowrap;
    }
    .modal-auth--social-button {
     height:31px;
    }
    <?php endif; ?>
    /* Check FB and G+ enabled */
</style>

