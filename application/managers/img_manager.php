<?php
final class Img_Manager extends Manager {
    public function get_resize() { 
 
        $config['source_image']	= APP_DIR.'t.jpg'; 
        $config['create_thumb'] = TRUE; 
        $config['maintain_ratio'] = TRUE; 
        $config['width'] = 120; 
        $config['height'] = 90; 
		
        $this->load_library('SYS', 'image/image_library', 'img', $config); 
 
         if ( ! $this->img->resize()) { 
            echo $this->img->display_errors(); 
        } 
    } 
	
	public function get_crop() { 
 
        $config['image_library_to_use'] = 'gd2'; 
        $config['source_image']	= APP_DIR.'t.jpg'; 
        $config['x_axis'] = 500; 
        $config['y_axis'] = 200; 
        $config['width']  = 205; 
        $config['height'] = 200; 
		
        $this->load_library('SYS', 'image/image_library', 'img', $config); 
 
         if ( ! $this->img->crop()) { 
            echo $this->img->display_errors(); 
        } 
    }
	
	public function get_rotate() { 
 
        $config['source_image']	= APP_DIR.'t.jpg'; 
        $config['rotation_angle'] = 'hor'; 
		
        $this->load_library('SYS', 'image/image_library', 'img', $config); 
 
         if ( ! $this->img->rotate()) { 
            echo $this->img->display_errors(); 
        } 
    } 
	
	public function get_watermark() { 
 
        $config['source_image']	= APP_DIR.'t.jpg'; 
        $config['wm_text'] = 'Copyright 2006 - John Doe'; 
        $config['wm_type'] = 'text'; 
        $config['wm_font_path'] = APP_DIR.'times.ttf'; 
        $config['wm_font_size']	= '16'; 
        $config['wm_font_color'] = '000000'; 
        $config['wm_vrt_alignment'] = 'middle'; 
        $config['wm_hor_alignment'] = 'center'; 
        $config['wm_padding'] = 20; 
		
        $this->load_library('SYS', 'image/image_library', 'img', $config); 
 
         if ( ! $this->img->watermark()) { 
            echo $this->img->display_errors(); 
        } 
    } 

}

// End of file: ./application/managers/img_manager.php
