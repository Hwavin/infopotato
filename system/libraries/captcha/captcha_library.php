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
	 * The width of the captcha image
	 * 
	 * @var int
	 */
	protected $img_width = 215;

	/**
	 * The height of the captcha image
	 * 
	 * @var int
	 */
	protected $img_height = 80;
	
	/**
	 * The path to the TrueType font file to use to draw the captcha code
	 * This is a required option, otherwise you will see an error message on the generated image.
	 *
	 * @var string 
	 */
	protected $ttf_path = '';
	
	/**
	 * The type of captcha to create, either alphanumeric or a math question
	 * 
	 * @var string 'text' or 'math'
	 */
	protected $type = 'text';

	/**
     * The background color of the captcha
     * @var string 
     */
    protected $bg_color = '#ffffff';

    /**
     * The color of the captcha text
	 *
     * @var string 
     */
    protected $text_color = '#3388FF';

	
	/**
     * The color of the noise that is drawn
	 *
     * @var string
     */
    protected $noise_color = '#3388FF';
	
	/**
     * How many lines to draw over the captcha code to increase security
	 *
     * @var int
     */
    protected $num_lines = 5;

	/**
     * The color of the lines over the captcha
	 *
     * @var string
     */
    protected $line_color = '#707070';
	
	/**
     * The level of distortion, 0.75 = normal, 1.0 = very high distortion
	 *
     * @var double
     */
    protected $perturbation = 0.85;
	
	/**
     * Internal scale factor for antialias
	 *
     * @var int
     */
	protected $iscale = 5;
	
	/**
     * The level of noise (random dots) to place on the image, 0-10
	 *
     * @var int
     */
    protected $noise_level = 2;
	
	/**
     * Source image resource identifier
	 *
     * @var resource
     */
	protected $im;
	
	/**
     * Temp image resource identifier
	 *
     * @var resource
     */
    protected $tmpimg;
	
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

	
	/**
     * Generate the captcha text or math question based on the captcha type
	 *
	 * @return array Array that contains the captcha text or math question and the answer
     */
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
			// Generate 6 letters
			for ($i = 0, $mt_rand_max = strlen($pool) - 1; $i < 6; $i++) {
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
	 *
     * @param string $background_image The path to the background image to use
	 * @return array Array that contains the image stream and the answer string
     */
    public function create() {
        // Generate the captcha and its answer
		$captcha_info = $this->_prepare_captcha();

		// Captcha letters or math question to display
		$word = $captcha_info['display'];

		// Create the source image
		// Returns an image identifier representing a black image of the specified size
		$this->im = function_exists('imagecreatetruecolor')
				? imagecreatetruecolor($this->img_width, $this->img_height)
				: imagecreate($this->img_width, $this->img_height);

		// Create the temp image
		// Returns an image identifier representing a black image of the specified size
		$this->tmpimg = function_exists('imagecreatetruecolor')
				? imagecreatetruecolor($this->img_width * $this->iscale, $this->img_height * $this->iscale)
				: imagecreate($this->img_width * $this->iscale, $this->img_height * $this->iscale);
		
		// Allocate the background color to be used for the image
		$bg_color_rgb = $this->_hex_2_rgb($this->bg_color);
		$bg_color = imagecolorallocate($this->im, $bg_color_rgb['r'], $bg_color_rgb['g'], $bg_color_rgb['b']);
		
		// Create a rectangle filled with background color
		imagefilledrectangle($this->im, 0, 0, $this->img_width, $this->img_height, $bg_color);
        
		// Draw random noise spot on the image
		if ($this->noise_level > 0) {
            $this->_draw_noise();
        }

		// Draw distorted lines on the source image
		if ($this->num_lines > 0) {
            $this->_draw_lines();
        }
		
		// Draw the actual captcha letters or math question on the source image
		$this->_draw_word($word);
		
		// Use output buffering to capture outputted image stream
		ob_start();
		// Generate and output the image to browser
		imagejpeg($this->im, NULL, 90);
		$image = ob_get_contents();
		ob_end_clean();
		// Free up memory
		imagedestroy($this->im);
		
		// You will need to send the header before outputing the image
		return array(
			'image' => $image,
			'answer' =>$captcha_info['answer'] // Both text and math answers are string
		);
    }
	

	/**
     * Draws random noise spots on the temp image
	 *
	 * @return void
     */
    private function _draw_noise() {
        $noise_level = ($this->noise_level > 10) ? 10 : $this->noise_level;

		// An arbitrary number that works well on a 1-10 scale
        $noise_level *= 125; 

		// Allocate the colors to be used for the image
		$noise_color_rgb = $this->_hex_2_rgb($this->noise_color);
		$noise_color = imagecolorallocate($this->im, $noise_color_rgb['r'], $noise_color_rgb['g'], $noise_color_rgb['b']);
		
		for ($i = 0; $i < $noise_level; ++$i) {
            $x = mt_rand(10, $this->img_width * $this->iscale);
            $y = mt_rand(10, $this->img_height * $this->iscale);
            $size = mt_rand(7, 10);
            if ($x - $size <= 0 && $y - $size <= 0) {
			    // Dont cover 0, 0 since it is used by imagedistortedcopy
				continue; 
            }
			// Draw a partial arc and fill it
			imagefilledarc($this->tmpimg, $x, $y, $size, $size, 0, 360, $noise_color, IMG_ARC_PIE);
        }
    }
	
	/**
     * Draws wiggly random lines on the source image
	 *
	 * @return void
     */
    private function _draw_lines() {
        // Allocate the colors to be used for the image
		$line_color_rgb = $this->_hex_2_rgb($this->line_color);
		$line_color	= imagecolorallocate($this->im, $line_color_rgb['r'], $line_color_rgb['g'], $line_color_rgb['b']);
		
		for ($line = 0; $line < $this->num_lines; ++ $line) {
            $x = $this->img_width * (1 + $line) / ($this->num_lines + 1);
            $x += (0.5 - $this->_frand()) * $this->img_width / $this->num_lines;
            $y = mt_rand($this->img_height * 0.1, $this->img_height * 0.9);

            $theta = ($this->_frand() - 0.5) * pi() * 0.7;
            $w = $this->img_width;
            $len = mt_rand($w * 0.4, $w * 0.7);
            $lwid = mt_rand(0, 2);

            $k = $this->_frand() * 0.6 + 0.2;
            $k = $k * $k * 0.5;
            $phi = $this->_frand() * 6.28;
            $step = 0.5;
            $dx = $step * cos($theta);
            $dy = $step * sin($theta);
            $n = $len / $step;
            $amp = 1.5 * $this->_frand() / ($k + 5.0 / $len);
            $x0 = $x - 0.5 * $len * cos($theta);
            $y0 = $y - 0.5 * $len * sin($theta);

            $ldx = round(- $dy * $lwid);
            $ldy = round($dx * $lwid);

            for ($i = 0; $i < $n; ++ $i) {
                $x = $x0 + $i * $dx + $amp * $dy * sin($k * $i * $step + $phi);
                $y = $y0 + $i * $dy - $amp * $dx * sin($k * $i * $step + $phi);
                // Draw a filled rectangle with the distorted line on the source image
				imagefilledrectangle($this->im, $x, $y, $x + $lwid, $y + $lwid, $line_color);
            }
        }
    }
	
	/**
     * Draws the captcha code on the image
	 * 
	 * @return void
     */
    private function _draw_word($word) {
		// Allocate the colors to be used for the image
		$text_color_rgb = $this->_hex_2_rgb($this->text_color);
		$text_color = imagecolorallocate($this->im, $text_color_rgb['r'], $text_color_rgb['g'], $text_color_rgb['b']);
		
        if ( ! is_readable($this->ttf_path)) {
            imagestring($this->im, 4, 10, ($this->img_height / 2) - 5, 'Failed to load TTF file!', $text_color);
        } else {
			if ($this->perturbation > 0) {
                $width2 = $this->img_width * $this->iscale;
                $height2 = $this->img_height * $this->iscale;
				
				$font_size = $height2 * .4;
                $bb = imageftbbox($font_size, 0, $this->ttf_path, $word);
                $tx = $bb[4] - $bb[0];
                $ty = $bb[5] - $bb[1];
                $x = floor($width2 / 2 - $tx / 2 - $bb[0]);
                $y = round($height2 / 2 - $ty / 2 - $bb[1]);

				// Write the captcha letters or math question to the temp image using TrueType fonts
                imagettftext($this->tmpimg, $font_size, 0, $x, $y, $text_color, $this->ttf_path, $word);
				
				$this->_distorted_copy();	
            } else {
                $font_size = $this->img_height * .4;
				$bb = imageftbbox($font_size, 0, $this->ttf_path, $word);
				$tx = $bb[4] - $bb[0];
				$ty = $bb[5] - $bb[1];
				$x = floor($this->img_width / 2 - $tx / 2 - $bb[0]);
				$y = round($this->img_height / 2 - $ty / 2 - $bb[1]);

				// Write the captcha letters or math question to the source image using TrueType fonts
				imagettftext($this->im, $font_size, 0, $x, $y, $text_color, $this->ttf_path, $word);
            }
        }
    }
	
	/**
     * Copies the temp image to the source image with distortion applied
	 * 
	 * Based on http://www.lagom.nl/linux/hkcaptcha/
	 * @return void
     */
    private function _distorted_copy() {
        $numpoles = 3; // Distortion factor
        // Make an array of poles AKA attractor points
        for ($i = 0; $i < $numpoles; ++$i) {
            $px[$i] = mt_rand($this->img_width * 0.3, $this->img_width * 0.7);
            $py[$i] = mt_rand($this->img_height * 0.3, $this->img_height * 0.7);
            $rad[$i] = mt_rand($this->img_height * 0.4, $this->img_height * 0.8);
            $tmp = ((- $this->_frand()) * 0.15) - 0.15;
            $amp[$i] = $this->perturbation * $tmp;
        }

        $bg_c = imagecolorat($this->tmpimg, 0, 0);
        $width2 = $this->iscale * $this->img_width;
        $height2 = $this->iscale * $this->img_height;
        
		// Copy palette from the temp image to the source image so text colors come across
		//imagepalettecopy($this->im, $this->tmpimg); 
        
		// Loop over img pixels, take pixels from $tmpimg with distortion field
        for ($ix = 0; $ix < $this->img_width; ++ $ix) {
            for ($iy = 0; $iy < $this->img_height; ++ $iy) {
                $x = $ix;
                $y = $iy;
                for ($i = 0; $i < $numpoles; ++ $i) {
                    $dx = $ix - $px[$i];
                    $dy = $iy - $py[$i];
                    if ($dx === 0 && $dy === 0) {
                        continue;
                    }
                    $r = sqrt($dx * $dx + $dy * $dy);
                    if ($r > $rad[$i]) {
                        continue;
                    }
                    $rscale = $amp[$i] * sin(3.14 * $r / $rad[$i]);
                    $x += $dx * $rscale;
                    $y += $dy * $rscale;
                }
                $c = $bg_c;
                $x *= $this->iscale;
                $y *= $this->iscale;
                if ($x >= 0 && $x < $width2 && $y >= 0 && $y < $height2) {
                    $c = imagecolorat($this->tmpimg, $x, $y);
                }
                if ($c !== $bg_c) { 
                    // Only copy pixels of letters to preserve any background image
					imagesetpixel($this->im, $ix, $iy, $c);
                }
            }
        }
    }
	
	/**
     * Return a random float between 0 and 0.9999
     *
     * @return float Random float between 0 and 0.9999
     */
    private function _frand() {
        return 0.0001 * mt_rand(0, 9999);
    }

	/**
     * Construct from an html hex color code
	 *
     * @param string hex color code
	 * @return array Array of RGB color
     */
    private function _hex_2_rgb($hex) {
		$hex = str_replace('#', '', $hex);

		if (strlen($hex) === 3) {
			$r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
			$g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
			$b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
		} else {
			$r = hexdec(substr($hex, 0, 2));
			$g = hexdec(substr($hex, 2, 2));
			$b = hexdec(substr($hex, 4, 2));
		}
		
		// Returns an array with the rgb values
		return array(
		    'r' => $r, 
			'g' => $g, 
			'b' => $b
		);
	}

}

/* End of file: ./system/libraries/captcha/captcha_library.php */