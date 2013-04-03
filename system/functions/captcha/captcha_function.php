<?php
/**
 * Create logic question image CAPTCHA
 *
 * @access	public
 * @param	array	array of data for the CAPTCHA
 * @param	string	path to create the image in
 * @param	string	URL to the CAPTCHA image folder
 * @param	string	server path to font
 * @return	array or FALSE on failure
 */
function captcha_function(array $data = NULL) {
	$captcha_text = array(
	    array(
		    'question' => '2 + 2 = ?',
			'answer' => '4'
		),
		array(
		    'question' => '5 + 3 = ?',
			'answer' => '8'
		),
		array(
		    'question' => '8 + 1 = ?',
			'answer' => '9'
		),
		array(
		    'question' => 'What is the sum of 2 and 2 ?',
			'answer' => '4'
		),
		array(
		    'question' => 'The last letter in "rocket" is?',
			'answer' => 't'
		),
		array(
		    'question' => 'The last letter in "computer" is?',
			'answer' => 'r'
		),
		array(
		    'question' => 'The first letter in "morning" is?',
			'answer' => 'm'
		),
		array(
		    'question' => 'In the number 73627, what is the 3rd digit?',
			'answer' => '6'
		),
		array(
		    'question' => 'In the number 73628, what is the 4th digit?',
			'answer' => '2'
		),
		array(
		    'question' => 'If tomorrow is Monday what day is today?',
			'answer' => 'sunday'
		),
		array(
		    'question' => 'The color of a red rose is?',
			'answer' => 'red'
		),
		array(
		    'question' => 'If the cat is black, what color is it?',
			'answer' => 'black'
		),
		array(
		    'question' => '2, 4, 8, 10 : which of these is the largest?',
			'answer' => '10'
		),
		array(
		    'question' => '4, 5, 6, 7 : the 2nd number is?',
			'answer' => '5'
		),
		array(
		    'question' => 'What is "one hundred" as a number?',
			'answer' => '100'
		),
		array(
		    'question' => 'How many letters in "jilting"?',
			'answer' => '7'
		),
		array(
		    'question' => 'Twelve minus 6 = ?',
			'answer' => '6'
		),
		array(
		    'question' => 'What is eighteen minus 3?',
			'answer' => '15'
		),
	);
	
	$random_key = array_rand($captcha_text, 1);
	$result_captcha_arr = $captcha_text[$random_key];
	
	
	
	// The captcha function requires the GD image library.
	// Only the img_dir and img_url are required.
	// If you do not specify a path to a TRUE TYPE font, the native ugly GD font will be used.
	// The "captcha" folder must be writable (666, or 777)
	// The "expiration" (in seconds) signifies how long an image will remain in the captcha folder before it will be deleted. The default is two hours.
	$defaults = array(
		'img_dir' => '', 
		'img_width' => 150, 
		'img_height' => 30, 
		'font_path' => '', 
		'expiration' => 7200
	);

	foreach ($defaults as $key => $val) {
		$$key = isset($data[$key]) ? $data[$key] : $val;
	}

	if (! @is_dir($img_dir) || ! is_writable($img_dir) || ! extension_loaded('gd')) {
		return FALSE;
	}

	// Remove old images
	$now = microtime(TRUE);
	$current_dir = @opendir($img_dir);
	while ($filename = @readdir($current_dir)) {
		if (substr($filename, -4) === '.jpg' && (str_replace('.jpg', '', $filename) + $expiration) < $now) {
			@unlink($img_dir.$filename);
		}
	}

	@closedir($current_dir);

	// Text question to be displayed on captcha image
	$word = $result_captcha_arr['question'];
	
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

	//  Assign colors
	$bg_color = imagecolorallocate ($im, 255, 255, 255);
	$border_color = imagecolorallocate ($im, 153, 102, 102);
	$text_color = imagecolorallocate ($im, 204, 153, 153);
	$grid_color	= imagecolorallocate($im, 255, 182, 182);
	$shadow_color = imagecolorallocate($im, 255, 240, 240);

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

	// Generate the image
	$img_name = $now.'.jpg';
	imagejpeg($im, $img_dir.$img_name);
	imagedestroy($im);

	return array(
		'answer' => $result_captcha_arr['answer'], 
		'time' => $now, 
		'img_name' => $img_name,
		'img_width' => $img_width,
		'img_height' => $img_height
	);
}

/* End of file: ./system/functions/captcha/captcha_function.php */