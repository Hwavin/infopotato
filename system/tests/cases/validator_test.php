<?php
require_once dirname(__FILE__).'/../../core/validator.php';

class Validator_Test extends PHPUnit_Framework_TestCase {

    // Test equals()
    public function test_equals() {
        $this->assertTrue(\InfoPotato\core\Validator::equals('test', 'test'));
    }

    // Test equals()
    public function test_equals_whitespace() {
        $this->assertTrue(\InfoPotato\core\Validator::equals(' test', '   test    '));
    }
    
    // Test equals()
    public function test_not_equals() {
        $str = "Iñtërnâtiôn\xe9àlizætiøn";
        $this->assertFalse(\InfoPotato\core\Validator::equals('3', 3));
    }
    
    // Test not_empty()
    public function test_not_equals_yes() {
        $this->assertTrue(\InfoPotato\core\Validator::not_empty('test'));
    }

    // Test not_empty(), whitespaces will be trimmed before test
    public function test_not_empty_yes2() {
        $this->assertFalse(\InfoPotato\core\Validator::not_empty(' '));
    }
    
    // Test not_empty()
    public function test_not_empty_no() {
        $this->assertFalse(\InfoPotato\core\Validator::not_empty(''));
    }

    // Test min_length()
    public function test_min_length() {
        $this->assertTrue(\InfoPotato\core\Validator::min_length('abc', 3));
    }
    
    // Test min_length()
    public function test_min_length_with_whitespace() {
        $this->assertFalse(\InfoPotato\core\Validator::min_length(' abc ', 5));
    }
    
    // Test min_length()
    public function test_min_length_utf8() {
        $this->assertTrue(\InfoPotato\core\Validator::min_length('Iñtërnâtiônàlizætiøn', 20));
    }
    
    // Test max_length()
    public function test_max_length() {
        $this->assertTrue(\InfoPotato\core\Validator::max_length('abc', 3));
    }
    
    // Test max_length()
    public function test_max_length_with_whitespace() {
        $this->assertTrue(\InfoPotato\core\Validator::max_length(' abc ', 3));
    }
    
    // Test max_length()
    public function test_max_length_utf8() {
        $this->assertTrue(\InfoPotato\core\Validator::max_length('Iñtërnâtiônàlizætiøn', 20));
    }
    
    // Test exact_length()
    public function test_exact_length() {
        $this->assertTrue(\InfoPotato\core\Validator::exact_length('abc', 3));
    }
    
    // Test exact_length()
    public function test_exact_length_whitespace() {
        $this->assertTrue(\InfoPotato\core\Validator::exact_length('   abc    ', 3));
    }
    
    // Test exact_length()
    public function test_exact_length_utf8() {
        $this->assertTrue(\InfoPotato\core\Validator::exact_length('Iñtërnâtiônàlizætiøn', 20));
    }
    
    // Test is_email()
    public function test_is_email() {
        $this->assertFalse(\InfoPotato\core\Validator::is_email('#$3testgmail.com'));
    }
    
    // Test is_email()
    public function test_is_email_whitespace() {
        $this->assertTrue(\InfoPotato\core\Validator::is_email('   test@gmail.com   '));
    }
    
    // Test is_url()
    public function test_is_url() {
        $this->assertTrue(\InfoPotato\core\Validator::is_url('http://www.php.net/manual/en/function.filter-var.php'));
    }
    
    // Test is_url()
    public function test_is_url_invalid_domain() {
        $this->assertTrue(\InfoPotato\core\Validator::is_url('http://www.php.nettt'));
    }
    
    // Test is_url()
    public function test_is_url_invalid_protocol() {
        $this->assertTrue(\InfoPotato\core\Validator::is_url('htt://www.php.net'));
    }
    
    // Test is_url()
    public function test_is_url_space() {
        $this->assertFalse(\InfoPotato\core\Validator::is_url('http://www.example.com/space here.html'));
    }
    
    // Test is_date()
    public function test_is_date() {
        $this->assertFalse(\InfoPotato\core\Validator::is_date('2013-12-32', 'YYYY-MM-DD'));
    }
    
}
