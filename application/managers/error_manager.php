<?php
namespace app\managers;

use InfoPotato\core\Manager;

class Error_Manager extends Manager {
    public function get_404() {
        $layout_data = array(
            'page_title' => 'Page Not Found',
            'content' => $this->render_template('pages/error_404'),
        );
        
        $response_data = array(
            'status' => 404,
			'content' => $this->render_template('layouts/default', $layout_data),
            'type' => 'text/html; charset=utf-8',
        );
        $this->response($response_data);
    }

}

// End of file: ./application/managers/error_manager.php
