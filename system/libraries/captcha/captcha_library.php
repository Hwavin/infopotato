<?php
/**
 * Captcha Library
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class CAPTCHA_Library {
    /**
	 * Captcha image width
	 * 
	 * @var integer
	 */
	public $img_width = 150;

	/**
	 * Captcha image height
	 * 
	 * @var integer
	 */
	public $img_height = 30;
	
	/**
	 * Font path
	 * 
	 * @var string 
	 */
	public $font_path = '';
	
	/**
	 * Captcha type, 'text' or 'math'
	 * 
	 * @var string 
	 */
	public $type = 'text';

	/**
     * The background color of the captcha
     * @var string 
     */
    public $bg_color = '#ffffff';
	
	/**
     * The background color of the captcha
     * @var string 
     */
    public $border_color = '#ffffff';
	
    /**
     * The color of the captcha text
	 *
     * @var string 
     */
    public $text_color = '#707070';
	
    /**
     * The color of the lines over the captcha
	 *
     * @var string 
     */
    public $grid_color = '#707070';

	/**
	 * Constructor
	 *
	 * The constructor can be passed an array of config values
	 */
	public function __construct(array $config = NULL) {
		if (count($config) > 0) {
			foreach ($config as $key => $val) {
				if (isset($key)) {
					$this->$key = $val;
				}
			}
		}
	}
	
	// Check to see which captcha type to use
	private function _prepare_captcha() {
		// Content to be displayed in captcha image
		$display = '';
		// Captcha answer to be stored in SESSION
		$answer = '';
		
		// Create the random math question
		if ($this->type === 'math') {
			$signs = array('+', '-', 'x');
			$result = 0;
			do {
				$sign = $signs[mt_rand(0, 2)];
				$left = mt_rand(1, 10);
				$right = mt_rand(1, 5);

				switch($sign) {
					case 'x': 
						$result = $left * $right; 
						break;

					case '-': 
						$result = $left - $right; 
						break;
					
					default:  
						$result = $left + $right; 
						break;
				}
			} while ($result <= 0); // no negative #'s or 0

			$display = "$left $sign $right = ?";
			$answer = (string) $result;
		}
		
		// Create the random text
		if ($this->type === 'text') {
			$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			for ($i = 0, $mt_rand_max = strlen($pool) - 1; $i < 8; $i++) {
				$display .= $pool[mt_rand(0, $mt_rand_max)];
			}
			// Answer is the same as the display
			$answer = $display;
		}
		
		// Returns the content and correct answer of the captcha
		return array(
		    'display' => $display,
			'answer' => $answer
		);
	}
	
	/**
     * Used to serve a captcha image to the browser
     * @param string $background_image The path to the background image to use
     */
    public function create() {
        $captcha_info = $this->_prepare_captcha();

		$word = $captcha_info['display'];

		// Determine angle and position
		$length	= strlen($word);
		$angle = ($length >= 6) ? rand(-($length-6), ($length-6)) : 0;
		$x_axis	= rand(6, (360/$length)-16);
		$y_axis = ($angle >= 0 ) ? rand($this->img_height, $this->img_width) : rand(6, $this->img_height);

		// Create image
		// PHP.net recommends imagecreatetruecolor(), but it isn't always available
		$im = function_exists('imagecreatetruecolor')
				? imagecreatetruecolor($this->img_width, $this->img_height)
				: imagecreate($this->img_width, $this->img_height);

		// Assign colors
		$bg_color_rgb = $this->_hex_2_rgb($this->bg_color);
		$bg_color = imagecolorallocate($im, $bg_color_rgb['r'], $bg_color_rgb['g'], $bg_color_rgb['b']);
		
		$border_color_rgb = $this->_hex_2_rgb($this->border_color);
		$border_color = imagecolorallocate($im, $border_color_rgb['r'], $border_color_rgb['g'], $border_color_rgb['b']);
		
		$text_color_rgb = $this->_hex_2_rgb($this->text_color);
		$text_color = imagecolorallocate($im, $text_color_rgb['r'], $text_color_rgb['g'], $text_color_rgb['b']);
		
		$grid_color_rgb = $this->_hex_2_rgb($this->grid_color);
		$grid_color	= imagecolorallocate($im, $grid_color_rgb['r'], $grid_color_rgb['g'], $grid_color_rgb['b']);

		// Create the rectangle
		ImageFilledRectangle($im, 0, 0, $this->img_width, $this->img_height, $bg_color);

		// Create the spiral pattern
		$theta = 1;
		$thetac = 7;
		$radius = 16;
		$circles = 20;
		$points = 32;

		for ($i = 0, $cp = ($circles * $points) - 1; $i < $cp; $i++) {
			$theta += $thetac;
			$rad = $radius * ($i / $points);
			$x = ($rad * cos($theta)) + $x_axis;
			$y = ($rad * sin($theta)) + $y_axis;
			$theta += $thetac;
			$rad1 = $radius * (($i + 1) / $points);
			$x1 = ($rad1 * cos($theta)) + $x_axis;
			$y1 = ($rad1 * sin($theta)) + $y_axis;
			imageline($im, $x, $y, $x1, $y1, $grid_color);
			$theta -= $thetac;
		}

		// Write the text
		$use_font = ($this->font_path !== '' && file_exists($this->font_path) && function_exists('imagettftext'));

		if ($use_font === FALSE) {
			$font_size = 5;
			$x = rand(0, $this->img_width/($length/3));
			$y = 0;
		} else {
			$font_size	= 16;
			$x = rand(0, $this->img_width/($length/1.5));
			$y = $font_size + 2;
		}

		for ($i = 0; $i < $length; $i++) {
			if ($use_font === FALSE) {
				$y = rand(0 , $this->img_height/2);
				imagestring($im, $font_size, $x, $y, $word[$i], $text_color);
				$x += ($font_size * 2);
			} else {
				$y = rand($this->img_height/2, $this->img_height-3);
				imagettftext($im, $font_size, $angle, $x, $y, $text_color, $this->font_path, $word[$i]);
				$x += $font_size;
			}
		}


		// Create the border
		imagerectangle($im, 0, 0, $this->img_width-1, $this->img_height-1, $border_color);
		
		// Use output buffering to capture outputted image stream
		ob_start();
		// Generate and output the image to browser
		imagejpeg($im, NULL, 90);
		$image = ob_get_contents();
		ob_end_clean();
		// Free up memory
		imagedestroy($im);
		
		// You will need to send the header before outputing the image
		return array(
			'image' => $image,
			'answer' =>$captcha_info['answer'] // Both text and math answers are string
		);
    }
	
	
	/**
     * Construct from an html hex color code
	 *
     * @param string $color
     */
    private function _hex_2_rgb($color) {
        if (strlen($color) === 3) {
            $red = str_repeat(substr($color, 0, 1), 2);
            $green = str_repeat(substr($color, 1, 1), 2);
            $blue = str_repeat(substr($color, 2, 1), 2);
        } else {
            $red = substr($color, 0, 2);
            $green = substr($color, 2, 2);
            $blue = substr($color, 4, 2);
        }

		return array(
		    'r' => hexdec($red),
			'g' => hexdec($green),
			'b' => hexdec($blue)
		);
    }

}

/* End of file: ./system/libraries/captcha/captcha_library.php */