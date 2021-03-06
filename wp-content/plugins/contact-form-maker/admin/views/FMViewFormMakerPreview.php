<?php

class FMViewFormMakerPreview_fmc {

  private $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function display() {
    $form_id = ((isset($_GET['form_id'])) ? esc_html(stripslashes($_GET['form_id'])) : 0);
    $form = (($form_id) ? $this->model->get_form($form_id) : '');
    $ver = rand();
    wp_print_scripts('jquery');
    wp_print_scripts('jquery-ui-widget');
    wp_print_scripts('jquery-ui-slider');
    wp_print_scripts('jquery-ui-spinner');
    wp_print_scripts('jquery-effects-shake');
    wp_print_scripts('jquery-ui-datepicker');

    wp_register_style('fm-jquery-ui', WD_FMC_URL . '/css/jquery-ui-1.10.3.custom.css', array(), WD_FMC_VERSION);
    wp_print_styles('fm-jquery-ui');
    wp_register_style('fm-jquery-ui-spinner', WD_FMC_URL . '/css/jquery-ui-spinner.css', array(), WD_FMC_VERSION);
    wp_print_styles('fm-jquery-ui-spinner');

    wp_register_script('gmap_form', WD_FMC_URL . '/js/if_gmap_front_end.js', array(), WD_FMC_VERSION);
    wp_print_scripts('gmap_form');
    wp_register_script('phone_field', WD_FMC_URL . '/js/intlTelInput.js', array(), WD_FMC_VERSION);
    wp_print_scripts('phone_field');

    wp_register_script('fm-Calendar', WD_FMC_URL . '/js/calendar/calendar.js', array(), WD_FMC_VERSION);
    wp_print_scripts('fm-Calendar');
    wp_register_script('calendar_function', WD_FMC_URL . '/js/calendar/calendar_function.js', array(), WD_FMC_VERSION);
    wp_print_scripts('calendar_function');

    wp_register_style('form_maker_calendar-jos', WD_FMC_URL . '/css/calendar-jos.css', array(), WD_FMC_VERSION);
    wp_print_styles('form_maker_calendar-jos');
    wp_register_style('phone_field_css', WD_FMC_URL . '/css/intlTelInput.css', array(), WD_FMC_VERSION);
    wp_print_styles('phone_field_css');
    wp_register_style('form_maker_frontend', WD_FMC_URL . '/css/form_maker_frontend.css', array(), WD_FMC_VERSION);
    wp_print_styles('form_maker_frontend');
    wp_register_style('style_submissions', WD_FMC_URL . '/css/style_submissions.css', array(), WD_FMC_VERSION);
    wp_print_styles('style_submissions');

    wp_register_script('main_div_front_end', WD_FMC_URL . '/js/main_div_front_end.js', array(), WD_FMC_VERSION);
    wp_print_scripts('main_div_front_end');
    wp_localize_script('main_div_front_end', 'fm_objectL10n', array(
      'plugin_url' => WD_FMC_URL,
      'fm_file_type_error' => addslashes(__('Can not upload this type of file', 'form_maker')),
      'fm_field_is_required' => addslashes(__('Field is required', 'form_maker')),
      'fm_min_max_check_1' => addslashes((__('The ', 'form_maker'))),
      'fm_min_max_check_2' => addslashes((__(' value must be between ', 'form_maker'))),
      'fm_spinner_check' => addslashes((__('Value must be between ', 'form_maker'))),
    ));

    require_once(WD_FMC_DIR . '/framework/WDW_FM_Library.php');
    $google_fonts = WDW_FMC_Library::get_google_fonts();
    $fonts = implode("|", str_replace(' ', '+', $google_fonts));
    wp_register_style('fm_googlefonts', 'https://fonts.googleapis.com/css?family=' . $fonts . '&subset=greek,latin,greek-ext,vietnamese,cyrillic-ext,latin-ext,cyrillic', null, null);
    wp_print_styles('fm_googlefonts');

    wp_register_style('fm-animate', WD_FMC_URL . '/css/frontend/fm-animate.css', array(), WD_FMC_VERSION);
    wp_print_styles('fm-animate');
    wp_register_style('fm-font-awesome', WD_FMC_URL . '/css/frontend/font-awesome/font-awesome.css', array(), WD_FMC_VERSION);
    wp_print_styles('fm-font-awesome');

    $fm_settings = get_option('fmc_settings');
    $map_key = isset($fm_settings['map_key']) ? $fm_settings['map_key'] : '';
    ?>
    <script src="https://maps.google.com/maps/api/js?v=3.exp&key=<?php echo $map_key ?>" type="text/javascript"></script>
    <?php
    require_once(WD_FMC_DIR . '/frontend/controllers/FMControllerForm_maker.php');
    $controller = new FMControllerForm_maker_fmc();
    echo $controller->execute($form_id);
    die();
  }

}
