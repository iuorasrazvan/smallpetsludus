<?php

class FMControllerForm_maker_fmc {

  public function execute($id, $type = 'embedded') {
    return $this->display($id, $type);
  }

  public function display($id, $type) {
		if (session_id() == '' || (function_exists('session_status') && (session_status() == PHP_SESSION_NONE))) {
			@session_start();
		}
		$form_preview = isset($_GET['form_preview']) ? $_GET['form_preview'] : '';
		$type = $form_preview == 1 ? 'embedded' : $type;

		require_once WD_FMC_DIR . "/frontend/models/FMModelForm_maker.php";
    $model = new FMModelForm_maker_fmc();

    require_once WD_FMC_DIR . "/frontend/views/FMViewForm_maker.php";
    $view = new FMViewForm_maker_fmc($model);

		if ($type == 'embedded'){
			return $view->display((int)$id, $type);
		}
		else {
			return $view->autoload_form();
		}
	}

}
