<?php
/**
 * Convert hex code (e.g. #eeeeee) into RGB values.
 *
 * @param array $color hex color
 * @return void
 */
function hex2rgb($color) {
	if ($color[0] == '#') {
		$color = substr($color, 1);
	}
	if (strlen($color) == 6) {
		list($r, $g, $b) = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
	} elseif (strlen( $color ) == 3) {
		list($r, $g, $b) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
	} else {
		return FALSE;
	}
	$r = hexdec($r);
	$g = hexdec($g);
	$b = hexdec($b);
	return array('red' => $r, 'green' => $g, 'blue' => $b);
}

/* End of file: ./system/scripts/hex2rgb_script.php */
