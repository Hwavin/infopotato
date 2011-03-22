<?php
class Emailto_Presenter extends Presenter {
	public function index($params = array()) {
		$email = count($params) > 0 ? urldecode($params[0]) : '';
		header("Location: mailto:$email");
	}
}

/* End of file: ./application/presenters/emailto_presenter.php */
