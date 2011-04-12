<?php
/**
 * Encoded Mailto Link
 *
 * Create a spam-protected mailto link written in Javascript
 *
 * @param	string	the email address
 * @param	string	the link title
 * @param	mixed 	any attributes
 * @return	string
 */

function mailto($email, $title = '', $attributes = '') {
	$title = (string) $title;

	if ($title == '') {
		$title = $email;
	}

	for ($i = 0; $i < 16; $i++) {
		$x[] = substr('<a href="mailto:', $i, 1);
	}

	for ($i = 0; $i < strlen($email); $i++) {
		$x[] = "|".ord(substr($email, $i, 1));
	}

	$x[] = '"';

	if ($attributes != '') {
		if (is_array($attributes)) {
			foreach ($attributes as $key => $val) {
				$x[] =  ' '.$key.'="';
				for ($i = 0; $i < strlen($val); $i++) {
					$x[] = "|".ord(substr($val, $i, 1));
				}
				$x[] = '"';
			}
		} else {
			for ($i = 0; $i < strlen($attributes); $i++) {
				$x[] = substr($attributes, $i, 1);
			}
		}
	}

	$x[] = '>';

	$temp = array();
	for ($i = 0; $i < strlen($title); $i++) {
		$ordinal = ord($title[$i]);

		if ($ordinal < 128) {
			$x[] = "|".$ordinal;
		} else {
			if (count($temp) == 0) {
				$count = ($ordinal < 224) ? 2 : 3;
			}

			$temp[] = $ordinal;
			if (count($temp) == $count) {
				$number = ($count == 3) ? (($temp['0'] % 16) * 4096) + (($temp['1'] % 64) * 64) + ($temp['2'] % 64) : (($temp['0'] % 32) * 64) + ($temp['1'] % 64);
				$x[] = "|".$number;
				$count = 1;
				$temp = array();
			}
		}
	}

	$x[] = '<'; $x[] = '/'; $x[] = 'a'; $x[] = '>';

	$x = array_reverse($x);
	ob_start();

?><script type="text/javascript">
//<![CDATA[
var l=new Array();
<?php
$i = 0;
foreach ($x as $val) { ?>l[<?php echo $i++; ?>]='<?php echo $val; ?>';<?php } ?>

for (var i = l.length-1; i >= 0; i=i-1){
if (l[i].substring(0, 1) == '|') document.write("&#"+unescape(l[i].substring(1))+";");
else document.write(unescape(l[i]));}
//]]>
</script><?php

	$buffer = ob_get_contents();
	ob_end_clean();
	return $buffer;
}


/* End of file: ./system/scripts/mailto/mailto_script.php */