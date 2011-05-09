<?php

include("profile_library.php");

/**
 * Test class just for the purposes of demonstration
 */
class Test {
	public function add($a, $b) {
		// add the numbers together 100 times
		for($i = 0; $i < 100; $i++) {
			$c = $a + $b;
		}
	}
}

$p = new Profile_Library();
$p->profile("Test", "add", array(1, 2), 1000);
$p->print_details();

?>
