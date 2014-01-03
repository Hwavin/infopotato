<?php
require_once dirname(__FILE__).'/../../libraries/password_hash/password_hash_library.php';

class Password_Hash_Library_Test extends PHPUnit_Framework_TestCase {

    // Template method - run once for each test method (and on fresh instances) of the test case class.
    protected function setUp() {
        $config = array( 
            'iteration_count_log2' => 8, 
            'portable_hashes' => FALSE 
        ); 
        $this->pass = new \InfoPotato\libraries\password_hash\Password_Hash_Library($config);
        $this->orig_pass = 'infopotato';
    }

    // Test hass_password() and check_password()
    public function test_hass_password() {
        $hashed_pass = $this->pass->hash_password($this->orig_pass); 
        $this->assertTrue($this->pass->check_password($this->orig_pass, $hashed_pass));
    }

}