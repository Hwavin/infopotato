<?php
/**
 * Create logic question image CAPTCHA
 *
 * @param	array	array of data for the CAPTCHA
 * @return	array 
 */
function captcha_function(array $data = NULL) {
	if ( ! extension_loaded('gd')) {
		exit('You need to enable GD library to use this function!');
	}

	// The captcha function requires the GD image library.
	// If you do not specify a path to a TRUE TYPE font, the native ugly GD font will be used.
	$defaults = array(
		'img_width' => 200, 
		'img_height' => 50, 
		'font_path' => '', 
		'type' => 'text'
	);

	foreach ($defaults as $key => $val) {
		$$key = isset($data[$key]) ? $data[$key] : $val;
	}
	
	// Check to see which captcha type to use
	
	// Content to be displayed in captcha image
	$display = '';
	// Captcha answer to be stored in SESSION
	$answer = '';
	
	// Create the random math question
	if ($type === 'math') {
		$signs = array('+', '-', 'x');
		
		do {
			$sign = $signs[mt_rand(0, 2)];
			$left = mt_rand(1, 10);
			$right = mt_rand(1, 5);

			switch($sign) {
				case 'x': 
					$answer = $left * $right; 
					break;

				case '-': 
					$answer = $left - $right; 
					break;
				
				default:  
					$answer = $left + $right; 
					break;
			}
		} while ($answer <= 0); // no negative #'s or 0

		$display = "$left $sign $right = ?";
	}
	
	// Create the random text
	if ($type === 'text') {
	    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		for ($i = 0, $mt_rand_max = strlen($pool) - 1; $i < 8; $i++) {
			$display .= $pool[mt_rand(0, $mt_rand_max)];
		}
		$answer = $display;
	}

	// Math question to be displayed on captcha image
	$word = $display;

	// Determine angle and position
	$length	= strlen($word);
	$angle = ($length >= 6) ? rand(-($length-6), ($length-6)) : 0;
	$x_axis	= rand(6, (360/$length)-16);
	$y_axis = ($angle >= 0 ) ? rand($img_height, $img_width) : rand(6, $img_height);

	// Create image
	// PHP.net recommends imagecreatetruecolor(), but it isn't always available
	$im = function_exists('imagecreatetruecolor')
			? imagecreatetruecolor($img_width, $img_height)
			: imagecreate($img_width, $img_height);

	// Assign colors
	$bg_color = imagecolorallocate($im, 255, 255, 255);
	$border_color = imagecolorallocate($im, 153, 102, 102);
	$text_color = imagecolorallocate($im, 204, 153, 153);
	$grid_color	= imagecolorallocate($im, 255, 182, 182);

	// Create the rectangle
	ImageFilledRectangle($im, 0, 0, $img_width, $img_height, $bg_color);

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
	$use_font = ($font_path !== '' && file_exists($font_path) && function_exists('imagettftext'));

	if ($use_font === FALSE) {
		$font_size = 5;
		$x = rand(0, $img_width/($length/3));
		$y = 0;
	} else {
		$font_size	= 16;
		$x = rand(0, $img_width/($length/1.5));
		$y = $font_size + 2;
	}

	for ($i = 0; $i < $length; $i++) {
		if ($use_font === FALSE) {
			$y = rand(0 , $img_height/2);
			imagestring($im, $font_size, $x, $y, $word[$i], $text_color);
			$x += ($font_size * 2);
		} else {
			$y = rand($img_height/2, $img_height-3);
			imagettftext($im, $font_size, $angle, $x, $y, $text_color, $font_path, $word[$i]);
			$x += $font_size;
		}
	}


	// Create the border
	imagerectangle($im, 0, 0, $img_width-1, $img_height-1, $border_color);
	
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
		'answer' => (string) $answer // Convert math answer to string too
	);
}

/* End of file: ./system/functions/captcha/captcha_function.php */