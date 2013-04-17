<?php
require_once dirname(__FILE__).'/../../libraries/encrypt/encrypt_library.php';

class Encrypt_Library_Test extends PHPUnit_Framework_TestCase {

    // Template method - run once for each test method (and on fresh instances) of the test case class.
    protected function setUp() {
		$config = array('encryption_key' => "tdasn'gl#ike@.tcss!");
		$this->encrypt = new Encrypt_Library($config);
		$this->msg = 'A message to be encoded';
	}

	// Test decode() with user's encryption key
	public function test_encode_decode() {
		$encoded_msg = $this->encrypt->encode($this->msg);
		$this->assertEquals($this->msg, $this->encrypt->decode($encoded_msg));
	}

	// Test the lengths of two encoded strings are the same
	public function test_encodeded_msg_same_length() {
		$encoded_msg_1 = $this->encrypt->encode($this->msg);
		$encoded_msg_2 = $this->encrypt->encode($this->msg);
		$this->assertEquals(strlen($encoded_msg_1), strlen($encoded_msg_2));
	}
	
	// Test two encoded strings can't be the same
	public function test_encodeded_msg_different() {
		$encoded_msg_1 = $this->encrypt->encode($this->msg);
		$encoded_msg_2 = $this->encrypt->encode($this->msg);
		$this->assertTrue($encoded_msg_1 !== $encoded_msg_2);
	}
	
	// Test decode() with a different encryption key as the second parameter
	public function test_optional_key() {
		$key = 'Ohai!ù0129°03182%HD1892P0';
		$encoded_msg = $this->encrypt->encode($this->msg, $key);
		$this->assertEquals($this->msg, $this->encrypt->decode($encoded_msg, $key));
	}

}