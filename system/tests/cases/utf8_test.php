<?php
require_once dirname(__FILE__).'/../../core/utf8.php';

class UTF8_Test extends PHPUnit_Framework_TestCase {

    // Test is_valid_utf8()
    public function test_is_valid_utf8_utf8() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $this->assertTrue(\InfoPotato\core\UTF8::is_valid_utf8($str));
    }

    // Test is_valid_utf8()
    public function test_is_valid_utf8_ascii() {
        $str = 'ABC 123';
        $this->assertTrue(\InfoPotato\core\UTF8::is_valid_utf8($str));
    }

    // Test is_valid_utf8()
    public function test_is_valid_utf8_invalid_utf8() {
        $str = "Iñtërnâtiôn\xe9àlizætiøn";
        $this->assertFalse(\InfoPotato\core\UTF8::is_valid_utf8($str));
    }

    // Test is_valid_utf8()
    public function test_is_valid_utf8_invalid_utf8_ascii() {
        $str = "this is an invalid char '\xe9' here";
        $this->assertFalse(\InfoPotato\core\UTF8::is_valid_utf8($str));
    }

    // Test is_valid_utf8()
    public function test_is_valid_utf8_empty_string() {
        $str = '';
        $this->assertTrue(\InfoPotato\core\UTF8::is_valid_utf8($str));
    }

    // Test is_valid_utf8()
    public function test_is_valid_utf8_valid_two_octet_id() {
        $str = "\xc3\xb1";
        $this->assertTrue(\InfoPotato\core\UTF8::is_valid_utf8($str));
    }

    // Test is_valid_utf8()
    public function test_is_valid_utf8_invalid_two_octet_sequence() {
        $str = "Iñtërnâtiônàlizætiøn \xc3\x28 Iñtërnâtiônàlizætiøn";
        $this->assertFalse(\InfoPotato\core\UTF8::is_valid_utf8($str));
    }

    // Test is_valid_utf8()
    public function test_is_valid_utf8_invalid_id_between_twoAnd_three() {
        $str = "Iñtërnâtiônàlizætiøn\xa0\xa1Iñtërnâtiônàlizætiøn";
        $this->assertFalse(\InfoPotato\core\UTF8::is_valid_utf8($str));
    }

    // Test is_valid_utf8()
    public function test_is_valid_utf8_valid_three_octet_id() {
        $str = "Iñtërnâtiônàlizætiøn\xe2\x82\xa1Iñtërnâtiônàlizætiøn";
        $this->assertTrue(\InfoPotato\core\UTF8::is_valid_utf8($str));
    }

    // Test is_valid_utf8()
    public function test_is_valid_utf8_invalid_three_octet_sequence_second() {
        $str = "Iñtërnâtiônàlizætiøn\xe2\x28\xa1Iñtërnâtiônàlizætiøn";
        $this->assertFalse(\InfoPotato\core\UTF8::is_valid_utf8($str));
    }

    // Test is_valid_utf8()
    public function test_is_valid_utf8_invalid_three_octet_sequence_third() {
        $str = "Iñtërnâtiônàlizætiøn\xe2\x82\x28Iñtërnâtiônàlizætiøn";
        $this->assertFalse(\InfoPotato\core\UTF8::is_valid_utf8($str));
    }

    // Test is_valid_utf8()
    public function test_is_valid_utf8_valid_four_octet_id() {
        $str = "Iñtërnâtiônàlizætiøn\xf0\x90\x8c\xbcIñtërnâtiônàlizætiøn";
        $this->assertTrue(\InfoPotato\core\UTF8::is_valid_utf8($str));
    }

    // Test is_valid_utf8()
    public function test_is_valid_utf8_invalid_four_octet_sequence() {
        $str = "Iñtërnâtiônàlizætiøn\xf0\x28\x8c\xbcIñtërnâtiônàlizætiøn";
        $this->assertFalse(\InfoPotato\core\UTF8::is_valid_utf8($str));
    }

    // Test is_valid_utf8()
    public function test_is_valid_utf8_invalid_five_octet_sequence() {
        $str = "Iñtërnâtiônàlizætiøn\xf8\xa1\xa1\xa1\xa1Iñtërnâtiônàlizætiøn";
        $this->assertFalse(\InfoPotato\core\UTF8::is_valid_utf8($str));
    }

    // Test is_valid_utf8()
    public function test_is_valid_utf8_invalid_six_octet_sequence() {
        $str = "Iñtërnâtiônàlizætiøn\xfc\xa1\xa1\xa1\xa1\xa1Iñtërnâtiônàlizætiøn";
        $this->assertFalse(\InfoPotato\core\UTF8::is_valid_utf8($str));
    }

    // Test strlen()
    public function test_strlen_utf8() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals(20, \InfoPotato\core\UTF8::strlen($str));
    }

    // Test strlen()
    public function test_strlen_utf8_invalid() {
        $str = "Iñtërnâtiôn\xe9àlizætiøn";
        $this->assertEquals(20, \InfoPotato\core\UTF8::strlen($str));
    }

    // Test strlen()
    public function test_strlen_ascii() {
        $str = 'ABC 123';
        $this->assertEquals(7, \InfoPotato\core\UTF8::strlen($str));
    }

    // Test strlen()
    public function test_strlen_empty_str() {
        $str = '';
        $this->assertEquals(0, \InfoPotato\core\UTF8::strlen($str));
    }


    // Test strpos()
    public function test_strpos_utf8() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals(6, \InfoPotato\core\UTF8::strpos($str, 'â'));
    }

    // Test strpos()
    public function test_strpos_utf8_offset() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals(16, \InfoPotato\core\UTF8::strpos($str, 'n', 11));
    }

    // Test strpos()
    public function test_strpos_utf8_invalid() {
        $str = "Iñtërnâtiôn\xe9àlizætiøn";
        $this->assertEquals(15, \InfoPotato\core\UTF8::strpos($str, 'æ'));
    }

    // Test strpos()
    public function test_strpos_ascii() {
        $str = 'ABC 123';
        $this->assertEquals(2, \InfoPotato\core\UTF8::strpos($str, 'B'));
    }

    // Test strpos()
    public function test_strpos_vs_strpos() {
        $str = 'ABC 123 ABC';
        $this->assertEquals(strpos($str, 'B', 2), \InfoPotato\core\UTF8::strpos($str, 'B', 2));
    }

    // Test strpos()
    public function test_strpos_empty_str() {
        $str = '';
        $this->assertFalse(\InfoPotato\core\UTF8::strpos($str, 'x'));
    }

    // Test strrpos()
    public function test_strrpos_utf8() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals(17, \InfoPotato\core\UTF8::strrpos($str, 'i'));
    }

    // Test strrpos()
    public function test_strrpos_utf8_offset() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals(19, \InfoPotato\core\UTF8::strrpos($str, 'n', 11));
    }

    // Test strrpos()
    public function test_strrpos_utf8_invalid() {
        $str = "Iñtërnâtiôn\xe9àlizætiøn";
        $this->assertEquals(15, \InfoPotato\core\UTF8::strrpos($str, 'æ'));
    }

    // Test strrpos()
    public function test_strrpos_ascii() {
        $str = 'ABC ABC';
        $this->assertEquals(5, \InfoPotato\core\UTF8::strrpos($str, 'B'));
    }

    // Test strrpos()
    public function test_strrpos_vs_strpos() {
        $str = 'ABC 123 ABC';
        $this->assertEquals(strrpos($str, 'B'), \InfoPotato\core\UTF8::strrpos($str, 'B'));
    }

    // Test strrpos()
    public function test_strrpos_empty_str() {
        $str = '';
        $this->assertFalse(\InfoPotato\core\UTF8::strrpos($str, 'x'));
    }

    // Test strrpos()
    public function test_strrpos_linefeed() {
        $str = "Iñtërnâtiônàlizætiø\nn";
        $this->assertEquals(17, \InfoPotato\core\UTF8::strrpos($str, 'i'));
    }

    // Test strrpos()
    public function test_strrpos_linefeed_search() {
        $str = "Iñtërnâtiônàlizætiø\nn";
        $this->assertEquals(19, \InfoPotato\core\UTF8::strrpos($str, "\n"));
    }


    // Test substr()
    public function test_substr_utf8() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals('Iñ', \InfoPotato\core\UTF8::substr($str, 0, 2));
    }

    // Test substr()
    public function test_substr_utf8_two() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals('të', \InfoPotato\core\UTF8::substr($str, 2, 2));
    }

    // Test substr()
    public function test_substr_utf8_zero() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals('Iñtërnâtiônàlizætiøn', \InfoPotato\core\UTF8::substr($str, 0));
    }

    // Test substr()
    public function test_substr_utf8_zero_zero() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals('', \InfoPotato\core\UTF8::substr($str, 0, 0));
    }

    // Test substr()
    public function test_substr_start_great_than_length() {
        $str = 'Iñt';
        $this->assertEmpty(\InfoPotato\core\UTF8::substr($str, 4));
    }

    // Test substr()
    public function test_substr_compare_start_great_than_length() {
        $str = 'abc';
        $this->assertEquals(substr($str, 4), \InfoPotato\core\UTF8::substr($str, 4));
    }

    // Test substr()
    public function test_substr_length_beyond_string() {
        $str = 'Iñt';
        $this->assertEquals('ñt', \InfoPotato\core\UTF8::substr($str, 1, 5));
    }

    // Test substr()
    public function test_substr_compare_length_beyond_string() {
        $str = 'abc';
        $this->assertEquals(substr($str, 1, 5), \InfoPotato\core\UTF8::substr($str, 1, 5));
    }

    // Test substr()
    public function test_substr_start_negative() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals('tiøn', \InfoPotato\core\UTF8::substr($str, -4));
    }

    // Test substr()
    public function test_substr_length_negative() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals('nàlizæti', \InfoPotato\core\UTF8::substr($str, 10, -2));
    }

    // Test substr()
    public function test_substr_start_length_negative() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals('ti', \InfoPotato\core\UTF8::substr($str, -4, -2));
    }

    // Test substr()
    public function test_substr_linefeed() {
        $str = "Iñ\ntërnâtiônàlizætiøn";
        $this->assertEquals("ñ\ntër", \InfoPotato\core\UTF8::substr($str, 1, 5));
    }

    // Test substr()
    public function test_substr_long_length() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals('Iñtërnâtiônàlizætiøn', \InfoPotato\core\UTF8::substr($str, 0, 15536));
    }

    // Test strtolower()
    public function test_strtolower() {
        $str = 'IÑTËRNÂTIÔNÀLIZÆTIØN';
        $lower = 'iñtërnâtiônàlizætiøn';
        $this->assertEquals($lower, \InfoPotato\core\UTF8::strtolower($str));
    }
    
    // Test strtolower()
    public function test_strtolower_empty_string() {
        $str = '';
        $lower = '';
        $this->assertEquals($lower, \InfoPotato\core\UTF8::strtolower($str));
    }

    // Test strtoupper()
    public function test_strtoupper() {
        $str = 'iñtërnâtiônàlizætiøn';
        $upper = 'IÑTËRNÂTIÔNÀLIZÆTIØN';
        $this->assertEquals($upper, \InfoPotato\core\UTF8::strtoupper($str));
    }

    // Test strtoupper()
    public function test_strtoupper_empty_string() {
        $str = '';
        $upper = '';
        $this->assertEquals($upper, \InfoPotato\core\UTF8::strtoupper($str));
    }

    // Test ucwords()
    public function test_ucword() {
        $str = 'iñtërnâtiônàlizætiøn';
        $ucwords = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals($ucwords, \InfoPotato\core\UTF8::ucwords($str));
    }

    // Test ucwords()
    public function test_ucwords() {
        $str = 'iñt ërn âti ônà liz æti øn';
        $ucwords = 'Iñt Ërn Âti Ônà Liz Æti Øn';
        $this->assertEquals($ucwords, \InfoPotato\core\UTF8::ucwords($str));
    }

    // Test ucwords()
    public function test_ucwords_newline() {
        $str = "iñt ërn âti\n ônà liz æti  øn";
        $ucwords = "Iñt Ërn Âti\n Ônà Liz Æti  Øn";
        $this->assertEquals($ucwords, \InfoPotato\core\UTF8::ucwords($str));
    }

    // Test ucwords()
    public function test_ucwords_empty_string() {
        $str = '';
        $ucwords = '';
        $this->assertEquals($ucwords, \InfoPotato\core\UTF8::ucwords($str));
    }

    // Test ucwords()
    public function test_ucwords_one_char() {
        $str = 'ñ';
        $ucwords = 'Ñ';
        $this->assertEquals($ucwords, \InfoPotato\core\UTF8::ucwords($str));
    }

    // Test ucwords()
    public function test_ucwords_linefeed() {
        $str = "iñt ërn âti\n ônà liz æti øn";
        $ucwords = "Iñt Ërn Âti\n Ônà Liz Æti Øn";
        $this->assertEquals($ucwords, \InfoPotato\core\UTF8::ucwords($str));
    }

    
    // Test wordwrap()
    /**
     * Standard cut tests
     */
    public function test_wordwrap_cut_single_line() {
        $line = \InfoPotato\core\UTF8::wordwrap('äbüöcß', 2, ' ', TRUE);
        $this->assertEquals('äb üö cß', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_cut_multi_line() {
        $line = \InfoPotato\core\UTF8::wordwrap('äbüöc ß äbüöcß', 2, ' ', TRUE);
        $this->assertEquals('äb üö c ß äb üö cß', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_cut_multi_lineShortWords() {
        $line = \InfoPotato\core\UTF8::wordwrap('Ä very long wöööööööööööörd.', 8, "\n", TRUE);
        $this->assertEquals("Ä very\nlong\nwööööööö\nööööörd.", $line);
    }

    // Test wordwrap()
    public function test_wordwrap_cut_multi_line_with_previous_newlines() {
        $line = \InfoPotato\core\UTF8::wordwrap("Ä very\nlong wöööööööööööörd.", 8, "\n", FALSE);
        $this->assertEquals("Ä very\nlong\nwöööööööööööörd.", $line);
    }

    // Test wordwrap()
    /**
     * Long-Break tests
     */
    public function test_wordwrap_long_break() {
        $line = \InfoPotato\core\UTF8::wordwrap("Ä very<br>long wöö<br>öööööööö<br>öörd.", 8, '<br>', FALSE);
        $this->assertEquals("Ä very<br>long wöö<br>öööööööö<br>öörd.", $line);
    }

    // Test wordwrap()
    /**
     * Alternative cut tests
     */
    public function test_wordwrap_cut_beginning_single_space() {
        $line = \InfoPotato\core\UTF8::wordwrap(' äüöäöü', 3, ' ', TRUE);
        $this->assertEquals(' äüö äöü', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_cut_ending_single_space() {
        $line = \InfoPotato\core\UTF8::wordwrap('äüöäöü ', 3, ' ', TRUE);
        $this->assertEquals('äüö äöü ', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_cut_ending_single_space_with_non_space_divider() {
        $line = \InfoPotato\core\UTF8::wordwrap('äöüäöü ', 3, '-', TRUE);
        $this->assertEquals('äöü-äöü-', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_cut_ending_two_spaces() {
        $line = \InfoPotato\core\UTF8::wordwrap('äüöäöü  ', 3, ' ', TRUE);
        $this->assertEquals('äüö äöü  ', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_no_cut_ending_single_space() {
        $line = \InfoPotato\core\UTF8::wordwrap('12345 ', 5, '-', FALSE);
        $this->assertEquals('12345-', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_no_cut_ending_two_spaces() {
        $line = \InfoPotato\core\UTF8::wordwrap('12345  ', 5, '-', FALSE);
        $this->assertEquals('12345- ', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_cut_ending_three_spaces() {
        $line = \InfoPotato\core\UTF8::wordwrap('äüöäöü  ', 3, ' ', TRUE);
        $this->assertEquals('äüö äöü  ', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_cut_ending_two_breaks() {
        $line = \InfoPotato\core\UTF8::wordwrap('äüöäöü--', 3, '-', TRUE);
        $this->assertEquals('äüö-äöü--', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_cut_tab() {
        $line = \InfoPotato\core\UTF8::wordwrap("äbü\töcß", 3, ' ', TRUE);
        $this->assertEquals("äbü \töc ß", $line);
    }

    // Test wordwrap()
    public function test_wordwrap_cut_newline_with_space() {
        $line = \InfoPotato\core\UTF8::wordwrap("äbü\nößt", 3, ' ', TRUE);
        $this->assertEquals("äbü \nöß t", $line);
    }

    // Test wordwrap()
    public function test_wordwrap_cut_newline_with_newline() {
        $line = \InfoPotato\core\UTF8::wordwrap("äbü\nößte", 3, "\n", TRUE);
        $this->assertEquals("äbü\nößt\ne", $line);
    }

    // Test wordwrap()
    /**
     * Break cut tests
     */
    public function test_wordwrap_cut_break_before() {
        $line = \InfoPotato\core\UTF8::wordwrap('foobar-foofoofoo', 8, '-', TRUE);
        $this->assertEquals('foobar-foofoofo-o', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_cut_break_with() {
        $line = \InfoPotato\core\UTF8::wordwrap('foobar-foobar', 6, '-', TRUE);
        $this->assertEquals('foobar-foobar', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_cut_break_within() {
        $line = \InfoPotato\core\UTF8::wordwrap('foobar-foobar', 7, '-', TRUE);
        $this->assertEquals('foobar-foobar', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_cut_break_within_end() {
        $line = \InfoPotato\core\UTF8::wordwrap('foobar-', 7, '-', TRUE);
        $this->assertEquals('foobar-', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_cut_break_after() {
        $line = \InfoPotato\core\UTF8::wordwrap('foobar-foobar', 5, '-', TRUE);
        $this->assertEquals('fooba-r-fooba-r', $line);
    }

    // Test wordwrap()
    /**
     * Standard no-cut tests
     */
    public function test_wordwrap_no_cut_single_line() {
        $line = \InfoPotato\core\UTF8::wordwrap('äbüöcß', 2, ' ', FALSE);
        $this->assertEquals('äbüöcß', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_no_cut_multi_line() {
        $line = \InfoPotato\core\UTF8::wordwrap('äbüöc ß äbüöcß', 2, "\n", FALSE);
        $this->assertEquals("äbüöc\nß\näbüöcß", $line);
    }

    // Test wordwrap()
    public function test_wordwrap_no_cut_multi_word() {
        $line = \InfoPotato\core\UTF8::wordwrap('äöü äöü äöü', 5, "\n", FALSE);
        $this->assertEquals("äöü\näöü\näöü", $line);
    }

    // Test wordwrap()
    /**
     * Break no-cut tests
     */
    public function test_wordwrap_no_cut_break_before() {
        $line = \InfoPotato\core\UTF8::wordwrap('foobar-foofoofoo', 8, '-', FALSE);
        $this->assertEquals('foobar-foofoofoo', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_no_cut_break_with() {
        $line = \InfoPotato\core\UTF8::wordwrap('foobar-foobar', 6, '-', FALSE);
        $this->assertEquals('foobar-foobar', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_no_cut_break_within() {
        $line = \InfoPotato\core\UTF8::wordwrap('foobar-foobar', 7, '-', FALSE);
        $this->assertEquals('foobar-foobar', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_no_cut_break_within_end() {
        $line = \InfoPotato\core\UTF8::wordwrap('foobar-', 7, '-', FALSE);
        $this->assertEquals('foobar-', $line);
    }

    // Test wordwrap()
    public function test_wordwrap_no_cut_break_after() {
        $line = \InfoPotato\core\UTF8::wordwrap('foobar-foobar', 5, '-', FALSE);
        $this->assertEquals('foobar-foobar', $line);
    }

    
    
    
    // Test ucfirst()
    public function test_ucfirst() {
        $str = 'ñtërnâtiônàlizætiøn';
        $ucfirst = 'Ñtërnâtiônàlizætiøn';
        $this->assertEquals($ucfirst, \InfoPotato\core\UTF8::ucfirst($str));
    }

    // Test ucfirst()
    public function test_ucfirst_space() {
        $str = ' iñtërnâtiônàlizætiøn';
        $ucfirst = ' iñtërnâtiônàlizætiøn';
        $this->assertEquals($ucfirst, \InfoPotato\core\UTF8::ucfirst($str));
    }

    // Test ucfirst()
    public function test_ucfirst_upper() {
        $str = 'Ñtërnâtiônàlizætiøn';
        $ucfirst = 'Ñtërnâtiônàlizætiøn';
        $this->assertEquals($ucfirst, \InfoPotato\core\UTF8::ucfirst($str));
    }

    // Test ucfirst()
    public function test_ucfirst_empty_string() {
        $str = '';
        $this->assertEquals('', \InfoPotato\core\UTF8::ucfirst($str));
    }

    // Test ucfirst()
    public function test_ucfirst_one_char() {
        $str = 'ñ';
        $ucfirst = "Ñ";
        $this->assertEquals($ucfirst, \InfoPotato\core\UTF8::ucfirst($str));
    }

    // Test ucfirst()
    public function test_ucfirst_linefeed() {
        $str = "ñtërn\nâtiônàlizætiøn";
        $ucfirst = "Ñtërn\nâtiônàlizætiøn";
        $this->assertEquals($ucfirst, \InfoPotato\core\UTF8::ucfirst($str));
    }

    // Test rtrim()
    public function test_rtrim() {
        $str = 'Iñtërnâtiônàlizætiø';
        $trimmed = 'Iñtërnâtiônàlizæti';
        $this->assertEquals($trimmed, \InfoPotato\core\UTF8::rtrim($str, 'ø'));
    }

    // Test rtrim()
    public function test_no_trim() {
        $str = 'Iñtërnâtiônàlizætiøn ';
        $trimmed = 'Iñtërnâtiônàlizætiøn ';
        $this->assertEquals($trimmed, \InfoPotato\core\UTF8::rtrim($str, 'ø'));
    }

    // Test rtrim()
    public function test_rtrim_empty_string() {
        $str = '';
        $trimmed = '';
        $this->assertEquals($trimmed, \InfoPotato\core\UTF8::rtrim($str));
    }

    // Test rtrim()
    public function test_rtrim_linefeed() {
        $str = "Iñtërnâtiônàlizætiø\nø";
        $trimmed = "Iñtërnâtiônàlizætiø\n";
        $this->assertEquals($trimmed, \InfoPotato\core\UTF8::rtrim($str, 'ø'));
    }

    // Test rtrim()
    public function test_rtrim_linefeed_mask() {
        $str = "Iñtërnâtiônàlizætiø\nø";
        $trimmed = "Iñtërnâtiônàlizæti";
        $this->assertEquals($trimmed, \InfoPotato\core\UTF8::rtrim($str, "ø\n"));
    }

    // Test ltrim()
    public function test_ltrim() {
        $str = 'ñtërnâtiônàlizætiøn';
        $trimmed = 'tërnâtiônàlizætiøn';
        $this->assertEquals($trimmed, \InfoPotato\core\UTF8::ltrim($str, 'ñ'));
    }

    // Test ltrim()
    public function test_ltrim_no_trim() {
        $str = ' Iñtërnâtiônàlizætiøn';
        $trimmed = ' Iñtërnâtiônàlizætiøn';
        $this->assertEquals($trimmed, \InfoPotato\core\UTF8::ltrim($str, 'ñ'));
    }

    // Test ltrim()
    public function test_ltrim_empty_string() {
        $str = '';
        $trimmed = '';
        $this->assertEquals($trimmed, \InfoPotato\core\UTF8::ltrim($str));
    }

    // Test ltrim()
    public function test_ltrim_forward_slash() {
        $str = '/Iñtërnâtiônàlizætiøn';
        $trimmed = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals($trimmed, \InfoPotato\core\UTF8::ltrim($str, '/'));
    }

    // Test ltrim()
    public function test_ltrim_negate_char_class() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $trimmed = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals($trimmed, \InfoPotato\core\UTF8::ltrim($str, '^s'));
    }

    // Test ltrim()
    public function test_ltrim_linefeed() {
        $str = "ñ\nñtërnâtiônàlizætiøn";
        $trimmed = "\nñtërnâtiônàlizætiøn";
        $this->assertEquals($trimmed, \InfoPotato\core\UTF8::ltrim($str, 'ñ'));
    }

    // Test ltrim()
    public function test_ltrim_linefeed_mask() {
        $str = "ñ\nñtërnâtiônàlizætiøn";
        $trimmed = "tërnâtiônàlizætiøn";
        $this->assertEquals($trimmed, \InfoPotato\core\UTF8::ltrim($str, "ñ\n"));
    }

    // Test trim()
    public function test_trim() {
        $str = 'ñtërnâtiônàlizætiø';
        $trimmed = 'tërnâtiônàlizæti';
        $this->assertEquals($trimmed, \InfoPotato\core\UTF8::trim($str, 'ñø'));
    }

    // Test trim()
    public function test_trim_no_trim() {
        $str = ' Iñtërnâtiônàlizætiøn ';
        $trimmed = ' Iñtërnâtiônàlizætiøn ';
        $this->assertEquals($trimmed, \InfoPotato\core\UTF8::trim($str, 'ñø'));
    }

    // Test trim()
    public function test_trim_empty_string() {
        $str = '';
        $trimmed = '';
        $this->assertEquals($trimmed, \InfoPotato\core\UTF8::trim($str));
    }

    // Test substr_replace()
    public function test_substr_replace_replace_start() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $replaced = 'IñtërnâtX';
        $this->assertEquals($replaced, \InfoPotato\core\UTF8::substr_replace($str, 'X', 8));
    }

    // Test substr_replace()
    public function test_substr_replace_empty_string() {
        $str = '';
        $replaced = 'X';
        $this->assertEquals($replaced, \InfoPotato\core\UTF8::substr_replace($str, 'X', 8));
    }

    // Test substr_replace()
    public function test_substr_replace_negative() {
        $str = 'testing';
        $replaced = substr_replace($str, 'foo', -2, -2);
        $this->assertEquals($replaced, \InfoPotato\core\UTF8::substr_replace($str, 'foo', -2, -2));
    }

    // Test substr_replace()
    public function test_substr_replace_zero() {
        $str = 'testing';
        $replaced = substr_replace($str, 'foo', 0, 0);
        $this->assertEquals($replaced, \InfoPotato\core\UTF8::substr_replace($str, 'foo', 0, 0));
    }

    // Test substr_replace()
    public function test_substr_replace_linefeed() {
        $str = "Iñ\ntërnâtiônàlizætiøn";
        $replaced = "Iñ\ntërnâtX";
        $this->assertEquals($replaced, \InfoPotato\core\UTF8::substr_replace($str, 'X', 9));
    }

    // Test substr_replace()
    public function test_substr_replace_linefeed_replace() {
        $str = "Iñ\ntërnâtiônàlizætiøn";
        $replaced = "Iñ\ntërnâtX\nY";
        $this->assertEquals($replaced, \InfoPotato\core\UTF8::substr_replace($str, "X\nY", 9));
    }

    // Test strspn()
    public function test_strspn_match() {
        $str = 'iñtërnâtiônàlizætiøn';
        $this->assertEquals(11, \InfoPotato\core\UTF8::strspn($str, 'âëiônñrt'));
    }

    // Test strspn()
    public function test_strspnmatch_two() {
        $str = 'iñtërnâtiônàlizætiøn';
        $this->assertEquals(4, \InfoPotato\core\UTF8::strspn($str, 'iñtë'));
    }

    // Test strspn()
    public function test_strspncompare_strspn() {
        $str = 'aeioustr';
        $this->assertEquals(strspn($str, 'saeiou'), \InfoPotato\core\UTF8::strspn($str, 'saeiou'));
    }

    // Test strspn()
    public function test_strspnmatch_ascii() {
        $str = 'internationalization';
        $this->assertEquals(strspn($str, 'aeionrt'), \InfoPotato\core\UTF8::strspn($str, 'aeionrt'));
    }

    // Test strspn()
    public function test_strspnlinefeed() {
        $str = "iñtërnât\niônàlizætiøn";
        $this->assertEquals(8, \InfoPotato\core\UTF8::strspn($str, 'âëiônñrt'));
    }

    // Test strspn()
    public function test_strspnlinefeed_mask() {
        $str = "iñtërnât\niônàlizætiøn";
        $this->assertEquals(12, \InfoPotato\core\UTF8::strspn($str, "âëiônñrt\n"));
    }
    
    // Test strrev()
    public function test_strrev() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $rev = 'nøitæzilànôitânrëtñI';
        $this->assertEquals($rev, \InfoPotato\core\UTF8::strrev($str));
    }

    // Test strrev()
    public function test_strrevempty_str() {
        $str = '';
        $rev = '';
        $this->assertEquals($rev, \InfoPotato\core\UTF8::strrev($str));
    }

    // Test strrev()
    public function test_strrevlinefeed() {
        $str = "Iñtërnâtiôn\nàlizætiøn";
        $rev = "nøitæzilà\nnôitânrëtñI";
        $this->assertEquals($rev, \InfoPotato\core\UTF8::strrev($str));
    }

    // Test stristr()
    public function test_stristr_substr() {
        $str = 'iñtërnâtiônàlizætiøn';
        $search = 'NÂT';
        $this->assertEquals('nâtiônàlizætiøn', \InfoPotato\core\UTF8::stristr($str, $search));
    }

    // Test stristr()
    public function test_stristr_substr_no_match() {
        $str = 'iñtërnâtiônàlizætiøn';
        $search = 'foo';
        $this->assertFalse(\InfoPotato\core\UTF8::stristr($str, $search));
    }

    // Test stristr()
    public function test_stristr_empty_search() {
        $str = 'iñtërnâtiônàlizætiøn';
        $search = '';
        $this->assertEquals('iñtërnâtiônàlizætiøn', \InfoPotato\core\UTF8::stristr($str, $search));
    }

    // Test stristr()
    public function test_stristr_empty_str() {
        $str = '';
        $search = 'NÂT';
        $this->assertFalse(\InfoPotato\core\UTF8::stristr($str, $search));
    }

    // Test stristr()
    public function test_stristr_empty_both() {
        $str = '';
        $search = '';
        $this->assertEmpty(\InfoPotato\core\UTF8::stristr($str, $search));
    }

    // Test stristr()
    public function test_stristr_linefeed_str() {
        $str = "iñt\nërnâtiônàlizætiøn";
        $search = 'NÂT';
        $this->assertEquals('nâtiônàlizætiøn', \InfoPotato\core\UTF8::stristr($str, $search));
    }

    // Test stristr()
    public function test_stristr_linefeed_both() {
        $str = "iñtërn\nâtiônàlizætiøn";
        $search = "N\nÂT";
        $this->assertEquals("n\nâtiônàlizætiøn", \InfoPotato\core\UTF8::stristr($str, $search));
    }

    // Test strcspn()
    public function test_strcspn_no_match_single_byte_search() {
        $str = 'iñtërnâtiônàlizætiøn';
        $this->assertEquals(2, \InfoPotato\core\UTF8::strcspn($str, 't'));
    }

    // Test strcspn()
    public function test_strcspn_no_match_multi_byte_search() {
        $str = 'iñtërnâtiônàlizætiøn';
        $this->assertEquals(6, \InfoPotato\core\UTF8::strcspn($str, 'â'));
    }

    // Test strcspn()
    public function test_strcspn_compare_strspn() {
        $str = 'aeioustr';
        $this->assertEquals(strcspn($str, 'tr'), \InfoPotato\core\UTF8::strcspn($str, 'tr'));
    }

    // Test strcspn()
    public function test_strcspn_match_ascii() {
        $str = 'internationalization';
        $this->assertEquals(strcspn($str, 'a'), \InfoPotato\core\UTF8::strcspn($str, 'a'));
    }

    // Test strcspn()
    public function test_strcspn_linefeed() {
        $str = "i\nñtërnâtiônàlizætiøn";
        $this->assertEquals(3, \InfoPotato\core\UTF8::strcspn($str, 't'));
    }

    // Test strcspn()
    public function test_strcspn_linefeed_mask() {
        $str = "i\nñtërnâtiônàlizætiøn";
        $this->assertEquals(1, \InfoPotato\core\UTF8::strcspn($str, "\n"));
    }


    // Test str_ireplace()
    public function test_str_ireplace_replace() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $replaced = 'Iñtërnâtiônàlisetiøn';
        $this->assertEquals($replaced, \InfoPotato\core\UTF8::str_ireplace('lIzÆ', 'lise', $str));
    }

    // Test str_ireplace()
    public function test_str_ireplace_replace_no_match() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $replaced = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals($replaced, \InfoPotato\core\UTF8::str_ireplace('foo', 'bar', $str));
    }

    // Test str_ireplace()
    public function test_str_ireplace_empty_string() {
        $str = '';
        $replaced = '';
        $this->assertEquals($replaced, \InfoPotato\core\UTF8::str_ireplace('foo', 'bar', $str));
    }

    // Test str_ireplace()
    public function test_str_ireplace_empty_search() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $replaced = 'Iñtërnâtiônàlizætiøn';
        $this->assertEquals($replaced, \InfoPotato\core\UTF8::str_ireplace('', 'x', $str));
    }

    // Test str_ireplace()
    public function test_str_ireplace_replace_count() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $replaced = 'IñtërXâtiôXàlizætiøn';
        $this->assertEquals($replaced, \InfoPotato\core\UTF8::str_ireplace('n', 'X', $str, 2));
    }

    // Test str_ireplace()
    public function test_str_ireplace_replace_different_search_replace_length() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $replaced = 'IñtërXXXâtiôXXXàlizætiøXXX';
        $this->assertEquals($replaced, \InfoPotato\core\UTF8::str_ireplace('n', 'XXX', $str));
    }

    // Test str_ireplace()
    public function test_str_ireplace_replace_array_ascii_search() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $replaced = 'Iñyërxâyiôxàlizæyiøx';
        $this->assertEquals($replaced, \InfoPotato\core\UTF8::str_ireplace(array('n', 't'), array('x', 'y'), $str));
    }

    // Test str_ireplace()
    public function test_str_ireplace_replace_array_utf8_search() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $replaced = 'I?tërnâti??nàliz????ti???n';
        $this->assertEquals(
            \InfoPotato\core\UTF8::str_ireplace(
                array('Ñ', 'ô', 'ø', 'Æ'),
                array('?', '??', '???', '????'),
                $str),
            $replaced);
    }

    // Test str_ireplace()
    public function test_str_ireplace_replace_array_string_replace() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $replaced = 'I?tërnâti?nàliz?ti?n';
        $this->assertEquals(
            $replaced,
            \InfoPotato\core\UTF8::str_ireplace(
                array('Ñ', 'ô', 'ø', 'Æ'),
                '?',
                $str)
        );
    }

    // Test str_ireplace()
    public function test_str_ireplace_replace_array_single_array_replace() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $replaced = 'I?tërnâtinàliztin';
        $this->assertEquals(
            \InfoPotato\core\UTF8::str_ireplace(
                array('Ñ', 'ô', 'ø', 'Æ'),
                array('?'),
                $str),
            $replaced);
    }

    // Test str_ireplace()
    public function test_str_ireplace_replace_linefeed() {
        $str = "Iñtërnâti\nônàlizætiøn";
        $replaced = "Iñtërnâti\nônàlisetiøn";
        $this->assertEquals($replaced, \InfoPotato\core\UTF8::str_ireplace('lIzÆ', 'lise', $str));
    }

    // Test str_ireplace()
    public function test_str_ireplace_replace_linefeed_search() {
        $str = "Iñtërnâtiônàli\nzætiøn";
        $replaced = "Iñtërnâtiônàlisetiøn";
        $this->assertEquals($replaced, \InfoPotato\core\UTF8::str_ireplace("lI\nzÆ", 'lise', $str));
    }

    // Test str_split()
    public function test_str_split_split_one_char() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $array = array(
            'I','ñ','t','ë','r','n','â','t','i','ô','n','à','l','i',
            'z','æ','t','i','ø','n',
        );

        $this->assertEquals($array, \InfoPotato\core\UTF8::str_split($str));
    }

    // Test str_split()
    public function test_str_split_split_five_chars() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $array = array(
            'Iñtër','nâtiô','nàliz','ætiøn',
        );

        $this->assertEquals($array, \InfoPotato\core\UTF8::str_split($str, 5));
    }

    // Test str_split()
    public function test_str_split_split_six_chars() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $array = array(
            'Iñtërn','âtiônà', 'lizæti','øn',
        );

        $this->assertEquals($array, \InfoPotato\core\UTF8::str_split($str, 6));
    }

    // Test str_split()
    public function test_str_split_split_long() {
        $str = 'Iñtërnâtiônàlizætiøn';
        $array = array(
            'Iñtërnâtiônàlizætiøn',
        );

        $this->assertEquals($array, \InfoPotato\core\UTF8::str_split($str, 40));
    }

    // Test str_split()
    public function test_str_split_split_newline() {
        $str = "Iñtërn\nâtiônàl\nizætiøn\n";
        $array = array(
            'I','ñ','t','ë','r','n',"\n",'â','t','i','ô','n','à','l',"\n",'i',
            'z','æ','t','i','ø','n',"\n",
        );

        $this->assertEquals($array, \InfoPotato\core\UTF8::str_split($str));
    }
    
    // Test strcasecmp()
    public function test_strcasecmp_compare_equal() {
        $str_x = 'iñtërnâtiônàlizætiøn';
        $str_y = 'IÑTËRNÂTIÔNÀLIZÆTIØN';
        $this->assertEquals(0, \InfoPotato\core\UTF8::strcasecmp($str_x, $str_y));
    }

    // Test strcasecmp()
    public function test_strcasecmp_less() {
        $str_x = 'iñtërnâtiônàlizætiøn';
        $str_y = 'IÑTËRNÂTIÔÀLIZÆTIØN';
        $this->assertTrue(\InfoPotato\core\UTF8::strcasecmp($str_x, $str_y) < 0);
    }

    // Test strcasecmp()
    public function test_strcasecmp_greater() {
        $str_x = 'iñtërnâtiôàlizætiøn';
        $str_y = 'IÑTËRNÂTIÔNÀLIZÆTIØN';
        $this->assertTrue(\InfoPotato\core\UTF8::strcasecmp($str_x, $str_y) > 0);
    }

    // Test strcasecmp()
    public function test_strcasecmp_empty_x() {
        $str_x = '';
        $str_y = 'IÑTËRNÂTIÔNÀLIZÆTIØN';
        $this->assertTrue(\InfoPotato\core\UTF8::strcasecmp($str_x, $str_y) < 0);
    }

    // Test strcasecmp()
    public function test_strcasecmp_empty_y() {
        $str_x = 'iñtërnâtiôàlizætiøn';
        $str_y = '';
        $this->assertTrue(\InfoPotato\core\UTF8::strcasecmp($str_x, $str_y) > 0);
    }

    // Test strcasecmp()
    public function test_strcasecmp_empty_both() {
        $str_x = '';
        $str_y = '';
        $this->assertTrue(\InfoPotato\core\UTF8::strcasecmp($str_x, $str_y) == 0);
    }

    // Test strcasecmp()
    public function test_strcasecmp_linefeed() {
        $str_x = "iñtërnâtiôn\nàlizætiøn";
        $str_y = "IÑTËRNÂTIÔN\nÀLIZÆTIØN";
        $this->assertTrue(\InfoPotato\core\UTF8::strcasecmp($str_x, $str_y) == 0);
    }
    
    // Test ord()
    public function test_ord_empty_str() {
        $str = '';
        $this->assertEquals(0, \InfoPotato\core\UTF8::ord($str));
    }

    // Test ord()
    public function test_ord_ascii_char() {
        $str = 'a';
        $this->assertEquals(97, \InfoPotato\core\UTF8::ord($str));
    }

    // Test ord()
    public function test_ord_2_byte_char() {
        $str = 'ñ';
        $this->assertEquals(241, \InfoPotato\core\UTF8::ord($str));
    }

    // Test ord()
    public function test_ord_3_byte_char() {
        $str = '₧';
        $this->assertEquals(8359, \InfoPotato\core\UTF8::ord($str));
    }

    // Test ord()
    public function test_ord_4_byte_char() {
        $str = "\xf0\x90\x8c\xbc";
        $this->assertEquals(66364, \InfoPotato\core\UTF8::ord($str));
    }
    
}
