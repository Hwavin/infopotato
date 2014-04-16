<?php
namespace app\managers;

use InfoPotato\core\Manager;

class Home_Manager extends Manager {
    public function get_index() {
        $layout_data = array(
            'page_title' => 'Home',
            'content' => $this->render_template('pages/home'),
        );

        $response_data = array(
            'content' => $this->render_template('layouts/default', $layout_data),
            'type' => 'text/html; charset=utf-8'
        );
        $this->response($response_data);
    }
    
}

// End of file: ./application/managers/home_manager.php
