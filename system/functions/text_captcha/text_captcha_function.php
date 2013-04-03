<?php
/**
 * Simply generate a pair of question and answer array
 * Can refer to http://textcaptcha.com/
 *
 * @return array
 */
function text_captcha_function() {
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
	$result_captcha = $captcha_text[$random_key];

	return $result_captcha;
}

// End of file: ./system/functions/text_capthca/text_capthca_function.php