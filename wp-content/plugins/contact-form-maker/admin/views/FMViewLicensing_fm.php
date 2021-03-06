<?php

class FMViewLicensing_fmc {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;


  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display() {
    wp_register_style('fm_license', WD_FMC_URL . '/css/license.css', array(), get_option("wd_form_maker_version"));
    wp_print_styles('fm_license');
    wp_register_style('fm_style', WD_FMC_URL . '/css/style.css', array(), get_option("wd_form_maker_version"));
    wp_print_styles('fm_style');
    ?>
    <div style="width:99%">
    <div id="featurs_tables">
      <div id="featurs_table1">
        <span>File Upload Field</span>
        <span>Google Map</span>
        <span>PayPal Integration</span>
        <span>Front-End Submissions</span>
        <span>Multiple/Single Choice</span>
        <span>Survey Tools</span>
        <span>Time and Date Fields</span>
        <span>Select Box</span>
        <span>MySQL mapping</span>
      </div>
      <div id="featurs_table2">
        <span>Free</span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
      </div>
      <div id="featurs_table3">
        <span>Pro Version</span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
      </div>
    </div>
    <div style="float: left; clear: both;">
      <a href="https://web-dorado.com/files/fromFormMaker.php" class="button-primary" target="_blank">Purchase a
        License</a>
      <br/><br/>
      <p>After purchasing the commercial version follow these steps:</p>
      <ol>
        <li>Deactivate Form Maker Plugin.</li>
        <li>Delete Form Maker Plugin.</li>
        <li>Install the downloaded commercial version of the plugin.</li>
      </ol>
    </div>
    <?php
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}