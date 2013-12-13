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
        $this->assertFalse(\InfoPotato\core\Validator::is_date('2013-12-32'));
    }
    
    // Test is_date()
    public function test_is_date_format1() {
        $this->assertTrue(\InfoPotato\core\Validator::is_date('2013/12/30', 'YYYY/MM/DD'));
    }
    
    // Test is_date()
    public function test_is_date_format2() {
        $this->assertFalse(\InfoPotato\core\Validator::is_date('2013/12/30', 'YYYY/DD/MM'));
    }
    
    // Test is_date()
    public function test_is_date_format3() {
        $this->assertTrue(\InfoPotato\core\Validator::is_date('20131230', 'YYYYMMDD'));
    }
    
    // Test is_date()
    public function test_is_date_format4() {
        $this->assertFalse(\InfoPotato\core\Validator::is_date('20131233', 'YYYYMMDD'));
    }
    
    // Test is_ip()
    public function test_is_ip() {
        $this->assertTrue(\InfoPotato\core\Validator::is_ip('172.162.54.1'));
    }
    
    // Test is_ip()
    public function test_is_ip_invalid() {
        $this->assertFalse(\InfoPotato\core\Validator::is_ip('a172.b162.c54.d1'));
    }
    
    // Test is_ip()
    public function test_is_ip_v4() {
        $this->assertTrue(\InfoPotato\core\Validator::is_ip('172.162.54.1', 'v4'));
    }
    
    // Test is_ip()
    public function test_is_ip_v6() {
        $this->assertFalse(\InfoPotato\core\Validator::is_ip('172.162.54.1', 'v6'));
    }

    // Test is_alpha()
    public function test_is_alpha() {
        $this->assertTrue(\InfoPotato\core\Validator::is_alpha('abcdefghizklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'));
    }
    
    // Test is_alpha()
    public function test_is_alpha_no() {
        $this->assertFalse(\InfoPotato\core\Validator::is_alpha('1abcdefghizklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'));
    }
    
    // Test is_alpha()
    public function test_is_alpha_utf8() {
        $this->assertFalse(\InfoPotato\core\Validator::is_alpha('Iñtërnâtiônàlizætiøn'));
    }
    
    // Test is_greater_than()
    public function test_is_greater_than() {
        $this->assertTrue(\InfoPotato\core\Validator::is_greater_than(3, 2));
    }
    
    // Test is_greater_than()
    public function test_is_greater_than_string() {
        $this->assertTrue(\InfoPotato\core\Validator::is_greater_than('3', 2));
    }
    
    // Test is_greater_than()
    public function test_is_greater_than_string2() {
        $this->assertTrue(\InfoPotato\core\Validator::is_greater_than('3', '2'));
    }
    
    // Test is_greater_than()
    public function test_is_greater_than_float() {
        $this->assertTrue(\InfoPotato\core\Validator::is_greater_than(3.222, 2));
    }
    
    // Test is_greater_than()
    public function test_is_greater_than_float_string() {
        $this->assertTrue(\InfoPotato\core\Validator::is_greater_than(3.222, '2'));
    }
    
    // Test is_greater_than()
    public function test_is_greater_than_float_float() {
        $this->assertTrue(\InfoPotato\core\Validator::is_greater_than(3.222, 3.221));
    }
    
    // Test is_greater_than()
    public function test_is_greater_than_equal() {
        $this->assertFalse(\InfoPotato\core\Validator::is_greater_than(3.222, 3.222));
    }
}
