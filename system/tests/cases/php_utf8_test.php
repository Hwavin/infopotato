<?php
require_once dirname(__FILE__).'/../../php_utf8.php';

class PHP_UTF8_Test extends PHPUnit_Framework_TestCase {
    // Test is_valid_utf8()
	public function test_is_valid_utf8_utf8() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$this->assertTrue(PHP_UTF8::is_valid_utf8($str));
	}

    // Test is_valid_utf8()
	public function test_is_valid_utf8_ascii() {
		$str = 'ABC 123';
		$this->assertTrue(PHP_UTF8::is_valid_utf8($str));
	}

    // Test is_valid_utf8()
	public function test_is_valid_utf8_invalid_utf8() {
		$str = "Iñtërnâtiôn\xe9àlizætiøn";
		$this->assertFalse(PHP_UTF8::is_valid_utf8($str));
	}

    // Test is_valid_utf8()
	public function test_is_valid_utf8_invalid_utf8_ascii() {
		$str = "this is an invalid char '\xe9' here";
		$this->assertFalse(PHP_UTF8::is_valid_utf8($str));
	}

    // Test is_valid_utf8()
	public function test_is_valid_utf8_empty_string() {
		$str = '';
		$this->assertTrue(PHP_UTF8::is_valid_utf8($str));
	}

    // Test is_valid_utf8()
	public function test_is_valid_utf8_valid_two_octet_id() {
		$str = "\xc3\xb1";
		$this->assertTrue(PHP_UTF8::is_valid_utf8($str));
	}

    // Test is_valid_utf8()
	public function test_is_valid_utf8_invalid_two_octet_sequence() {
		$str = "Iñtërnâtiônàlizætiøn \xc3\x28 Iñtërnâtiônàlizætiøn";
		$this->assertFalse(PHP_UTF8::is_valid_utf8($str));
	}

    // Test is_valid_utf8()
	public function test_is_valid_utf8_invalid_id_between_twoAnd_three() {
		$str = "Iñtërnâtiônàlizætiøn\xa0\xa1Iñtërnâtiônàlizætiøn";
		$this->assertFalse(PHP_UTF8::is_valid_utf8($str));
	}

    // Test is_valid_utf8()
	public function test_is_valid_utf8_valid_three_octet_id() {
		$str = "Iñtërnâtiônàlizætiøn\xe2\x82\xa1Iñtërnâtiônàlizætiøn";
		$this->assertTrue(PHP_UTF8::is_valid_utf8($str));
	}

    // Test is_valid_utf8()
	public function test_is_valid_utf8_invalid_three_octet_sequence_second() {
		$str = "Iñtërnâtiônàlizætiøn\xe2\x28\xa1Iñtërnâtiônàlizætiøn";
		$this->assertFalse(PHP_UTF8::is_valid_utf8($str));
	}

    // Test is_valid_utf8()
	public function test_is_valid_utf8_invalid_three_octet_sequence_third() {
		$str = "Iñtërnâtiônàlizætiøn\xe2\x82\x28Iñtërnâtiônàlizætiøn";
		$this->assertFalse(PHP_UTF8::is_valid_utf8($str));
	}

    // Test is_valid_utf8()
	public function test_is_valid_utf8_valid_four_octet_id() {
		$str = "Iñtërnâtiônàlizætiøn\xf0\x90\x8c\xbcIñtërnâtiônàlizætiøn";
		$this->assertTrue(PHP_UTF8::is_valid_utf8($str));
	}

    // Test is_valid_utf8()
	public function test_is_valid_utf8_invalid_four_octet_sequence() {
		$str = "Iñtërnâtiônàlizætiøn\xf0\x28\x8c\xbcIñtërnâtiônàlizætiøn";
		$this->assertFalse(PHP_UTF8::is_valid_utf8($str));
	}

    // Test is_valid_utf8()
	public function test_is_valid_utf8_invalid_five_octet_sequence() {
		$str = "Iñtërnâtiônàlizætiøn\xf8\xa1\xa1\xa1\xa1Iñtërnâtiônàlizætiøn";
		$this->assertFalse(PHP_UTF8::is_valid_utf8($str));
	}

    // Test is_valid_utf8()
	public function test_is_valid_utf8_invalid_six_octet_sequence() {
		$str = "Iñtërnâtiônàlizætiøn\xfc\xa1\xa1\xa1\xa1\xa1Iñtërnâtiônàlizætiøn";
		$this->assertFalse(PHP_UTF8::is_valid_utf8($str));
	}

    // Test mirror_strlen()
	public function test_mirror_strlen_utf8() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals(20, PHP_UTF8::mirror_strlen($str));
    }

	// Test mirror_strlen()
	public function test_mirror_strlen_utf8_invalid() {
		$str = "Iñtërnâtiôn\xe9àlizætiøn";
		$this->assertEquals(20, PHP_UTF8::mirror_strlen($str));
	}

	// Test mirror_strlen()
	public function test_mirror_strlen_ascii() {
		$str = 'ABC 123';
		$this->assertEquals(7, PHP_UTF8::mirror_strlen($str));
	}

    // Test mirror_strlen()
	public function test_mirror_strlen_empty_str() {
		$str = '';
		$this->assertEquals(0, PHP_UTF8::mirror_strlen($str));
	}


    // Test mirror_strpos()
	public function test_mirror_strpos_utf8() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals(6, PHP_UTF8::mirror_strpos($str, 'â'));
	}

    // Test mirror_strpos()
	public function test_mirror_strpos_utf8_offset() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals(19, PHP_UTF8::mirror_strpos($str, 'n', 11));
	}

    // Test mirror_strpos()
	public function test_mirror_strpos_utf8_invalid() {
		$str = "Iñtërnâtiôn\xe9àlizætiøn";
		$this->assertEquals(15, PHP_UTF8::mirror_strpos($str, 'æ'));
	}

    // Test mirror_strpos()
	public function test_mirror_strpos_ascii() {
		$str = 'ABC 123';
		$this->assertEquals(1, PHP_UTF8::mirror_strpos($str, 'B'));
	}

    // Test mirror_strpos()
	public function test_mirror_strpos_vs_strpos() {
		$str = 'ABC 123 ABC';
		$this->assertEquals(strpos($str, 'B', 3), PHP_UTF8::mirror_strpos($str, 'B', 3));
	}

    // Test mirror_strpos()
	public function test_mirror_strpos_empty_str() {
		$str = '';
		$this->assertFalse(PHP_UTF8::mirror_strpos($str, 'x'));
	}

	// Test mirror_strrpos()
	public function test_mirror_strrpos_utf8() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals(17, PHP_UTF8::mirror_strrpos($str, 'i'));
	}

	// Test mirror_strrpos()
	public function test_mirror_strrpos_utf8_offset() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals(19, PHP_UTF8::mirror_strrpos($str, 'n', 11));
	}

	// Test mirror_strrpos()
	public function test_mirror_strrpos_utf8_invalid() {
		$str = "Iñtërnâtiôn\xe9àlizætiøn";
		$this->assertEquals(15, PHP_UTF8::mirror_strrpos($str, 'æ'));
	}

	// Test mirror_strrpos()
	public function test_mirror_strrpos_ascii() {
		$str = 'ABC ABC';
		$this->assertEquals(5, PHP_UTF8::mirror_strrpos($str, 'B'));
	}

	// Test mirror_strrpos()
	public function test_mirror_strrpos_vs_strpos() {
		$str = 'ABC 123 ABC';
		$this->assertEquals(strrpos($str, 'B'), PHP_UTF8::mirror_strrpos($str, 'B'));
	}

	// Test mirror_strrpos()
	public function test_mirror_strrpos_empty_str() {
		$str = '';
		$this->assertFalse(PHP_UTF8::mirror_strrpos($str, 'x'));
	}

	// Test mirror_strrpos()
	public function test_mirror_strrpos_linefeed() {
		$str = "Iñtërnâtiônàlizætiø\nn";
		$this->assertEquals(17, PHP_UTF8::mirror_strrpos($str, 'i'));
	}

	// Test mirror_strrpos()
	public function test_mirror_strrpos_linefeed_search() {
		$str = "Iñtërnâtiônàlizætiø\nn";
		$this->assertEquals(19, PHP_UTF8::mirror_strrpos($str, "\n"));
	}


    // Test mirror_substr()
	public function test_mirror_substr_utf8() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals('Iñ', PHP_UTF8::mirror_substr($str, 0, 2));
	}

    // Test mirror_substr()
	public function test_mirror_substr_utf8_two() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals('të', PHP_UTF8::mirror_substr($str, 2, 2));
	}

    // Test mirror_substr()
	public function test_mirror_substr_utf8_zero() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals('Iñtërnâtiônàlizætiøn', PHP_UTF8::mirror_substr($str, 0));
	}

    // Test mirror_substr()
	public function test_mirror_substr_utf8_zero_zero() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals('', PHP_UTF8::mirror_substr($str, 0, 0));
	}

    // Test mirror_substr()
	public function test_mirror_substr_start_great_than_length() {
		$str = 'Iñt';
		$this->assertEmpty(PHP_UTF8::mirror_substr($str, 4));
	}

    // Test mirror_substr()
	public function test_mirror_substr_compare_start_great_than_length() {
		$str = 'abc';
		$this->assertEquals(substr($str, 4), PHP_UTF8::mirror_substr($str, 4));
	}

    // Test mirror_substr()
	public function test_mirror_substr_length_beyond_string() {
		$str = 'Iñt';
		$this->assertEquals('ñt', PHP_UTF8::mirror_substr($str, 1, 5));
	}

    // Test mirror_substr()
	public function test_mirror_substr_compare_length_beyond_string() {
		$str = 'abc';
		$this->assertEquals(substr($str, 1, 5), PHP_UTF8::mirror_substr($str, 1, 5));
	}

    // Test mirror_substr()
	public function test_mirror_substr_start_negative() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals('tiøn', PHP_UTF8::mirror_substr($str, -4));
	}

    // Test mirror_substr()
	public function test_mirror_substr_length_negative() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals('nàlizæti', PHP_UTF8::mirror_substr($str, 10, -2));
	}

    // Test mirror_substr()
	public function test_mirror_substr_start_length_negative() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals('ti', PHP_UTF8::mirror_substr($str, -4, -2));
	}

    // Test mirror_substr()
	public function test_mirror_substr_linefeed() {
		$str = "Iñ\ntërnâtiônàlizætiøn";
		$this->assertEquals("ñ\ntër", PHP_UTF8::mirror_substr($str, 1, 5));
	}

    // Test mirror_substr()
	public function test_mirror_substr_long_length() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals('Iñtërnâtiônàlizætiøn', PHP_UTF8::mirror_substr($str, 0, 15536));
	}

	// Test mirror_strtolower()
	public function test_mirror_strtolower() {
		$str = 'IÑTËRNÂTIÔNÀLIZÆTIØN';
		$lower = 'iñtërnâtiônàlizætiøn';
		$this->assertEquals($lower, PHP_UTF8::mirror_strtolower($str));
	}
    
    // Test mirror_strtolower()
	public function test_mirror_strtolower_empty_string() {
		$str = '';
		$lower = '';
		$this->assertEquals($lower, PHP_UTF8::mirror_strtolower($str));
	}

	// Test mirror_strtoupper()
	public function test_mirror_strtoupper() {
		$str = 'iñtërnâtiônàlizætiøn';
		$upper = 'IÑTËRNÂTIÔNÀLIZÆTIØN';
		$this->assertEquals($upper, PHP_UTF8::mirror_strtoupper($str));
	}

	// Test mirror_strtoupper()
	public function test_mirror_strtoupper_empty_string() {
		$str = '';
		$upper = '';
		$this->assertEquals($upper, PHP_UTF8::mirror_strtoupper($str));
	}

	// Test mirror_ucwords()
	public function test_ucword() {
		$str = 'iñtërnâtiônàlizætiøn';
		$ucwords = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals($ucwords, PHP_UTF8::mirror_ucwords($str));
	}

	// Test mirror_ucwords()
	public function test_mirror_ucwords() {
		$str = 'iñt ërn âti ônà liz æti øn';
		$ucwords = 'Iñt Ërn Âti Ônà Liz Æti Øn';
		$this->assertEquals($ucwords, PHP_UTF8::mirror_ucwords($str));
	}

	// Test mirror_ucwords()
	public function test_mirror_ucwords_newline() {
		$str = "iñt ërn âti\n ônà liz æti  øn";
		$ucwords = "Iñt Ërn Âti\n Ônà Liz Æti  Øn";
		$this->assertEquals($ucwords, PHP_UTF8::mirror_ucwords($str));
	}

	// Test mirror_ucwords()
	public function test_mirror_ucwords_empty_string() {
		$str = '';
		$ucwords = '';
		$this->assertEquals($ucwords, PHP_UTF8::mirror_ucwords($str));
	}

	// Test mirror_ucwords()
	public function test_mirror_ucwords_one_char() {
		$str = 'ñ';
		$ucwords = 'Ñ';
		$this->assertEquals($ucwords, PHP_UTF8::mirror_ucwords($str));
	}

	// Test mirror_ucwords()
	public function test_mirror_ucwords_linefeed() {
		$str = "iñt ërn âti\n ônà liz æti øn";
		$ucwords = "Iñt Ërn Âti\n Ônà Liz Æti Øn";
		$this->assertEquals($ucwords, PHP_UTF8::mirror_ucwords($str));
	}

	
	// Test mirror_wordwrap()
	/**
     * Standard cut tests
     */
    public function test_mirror_wordwrap_cut_single_line() {
        $line = PHP_UTF8::mirror_wordwrap('äbüöcß', 2, ' ', TRUE);
        $this->assertEquals('äb üö cß', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_cut_multi_line() {
        $line = PHP_UTF8::mirror_wordwrap('äbüöc ß äbüöcß', 2, ' ', TRUE);
        $this->assertEquals('äb üö c ß äb üö cß', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_cut_multi_lineShortWords() {
        $line = PHP_UTF8::mirror_wordwrap('Ä very long wöööööööööööörd.', 8, "\n", TRUE);
        $this->assertEquals("Ä very\nlong\nwööööööö\nööööörd.", $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_cut_multi_line_with_previous_newlines() {
        $line = PHP_UTF8::mirror_wordwrap("Ä very\nlong wöööööööööööörd.", 8, "\n", FALSE);
        $this->assertEquals("Ä very\nlong\nwöööööööööööörd.", $line);
    }

	// Test mirror_wordwrap()
    /**
     * Long-Break tests
     */
    public function test_mirror_wordwrap_long_break() {
        $line = PHP_UTF8::mirror_wordwrap("Ä very<br>long wöö<br>öööööööö<br>öörd.", 8, '<br>', FALSE);
        $this->assertEquals("Ä very<br>long wöö<br>öööööööö<br>öörd.", $line);
    }

	// Test mirror_wordwrap()
    /**
     * Alternative cut tests
     */
    public function test_mirror_wordwrap_cut_beginning_single_space() {
        $line = PHP_UTF8::mirror_wordwrap(' äüöäöü', 3, ' ', TRUE);
        $this->assertEquals(' äüö äöü', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_cut_ending_single_space() {
        $line = PHP_UTF8::mirror_wordwrap('äüöäöü ', 3, ' ', TRUE);
        $this->assertEquals('äüö äöü ', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_cut_ending_single_space_with_non_space_divider() {
        $line = PHP_UTF8::mirror_wordwrap('äöüäöü ', 3, '-', TRUE);
        $this->assertEquals('äöü-äöü-', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_cut_ending_two_spaces() {
        $line = PHP_UTF8::mirror_wordwrap('äüöäöü  ', 3, ' ', TRUE);
        $this->assertEquals('äüö äöü  ', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_no_cut_ending_single_space() {
        $line = PHP_UTF8::mirror_wordwrap('12345 ', 5, '-', FALSE);
        $this->assertEquals('12345-', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_no_cut_ending_two_spaces() {
        $line = PHP_UTF8::mirror_wordwrap('12345  ', 5, '-', FALSE);
        $this->assertEquals('12345- ', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_cut_ending_three_spaces() {
        $line = PHP_UTF8::mirror_wordwrap('äüöäöü  ', 3, ' ', TRUE);
        $this->assertEquals('äüö äöü  ', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_cut_ending_two_breaks() {
        $line = PHP_UTF8::mirror_wordwrap('äüöäöü--', 3, '-', TRUE);
        $this->assertEquals('äüö-äöü--', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_cut_tab() {
        $line = PHP_UTF8::mirror_wordwrap("äbü\töcß", 3, ' ', TRUE);
        $this->assertEquals("äbü \töc ß", $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_cut_newline_with_space() {
        $line = PHP_UTF8::mirror_wordwrap("äbü\nößt", 3, ' ', TRUE);
        $this->assertEquals("äbü \nöß t", $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_cut_newline_with_newline() {
        $line = PHP_UTF8::mirror_wordwrap("äbü\nößte", 3, "\n", TRUE);
        $this->assertEquals("äbü\nößt\ne", $line);
    }

	// Test mirror_wordwrap()
    /**
     * Break cut tests
     */
    public function test_mirror_wordwrap_cut_break_before() {
        $line = PHP_UTF8::mirror_wordwrap('foobar-foofoofoo', 8, '-', TRUE);
        $this->assertEquals('foobar-foofoofo-o', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_cut_break_with() {
        $line = PHP_UTF8::mirror_wordwrap('foobar-foobar', 6, '-', TRUE);
        $this->assertEquals('foobar-foobar', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_cut_break_within() {
        $line = PHP_UTF8::mirror_wordwrap('foobar-foobar', 7, '-', TRUE);
        $this->assertEquals('foobar-foobar', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_cut_break_within_end() {
        $line = PHP_UTF8::mirror_wordwrap('foobar-', 7, '-', TRUE);
        $this->assertEquals('foobar-', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_cut_break_after() {
        $line = PHP_UTF8::mirror_wordwrap('foobar-foobar', 5, '-', TRUE);
        $this->assertEquals('fooba-r-fooba-r', $line);
    }

	// Test mirror_wordwrap()
    /**
     * Standard no-cut tests
     */
    public function test_mirror_wordwrap_no_cut_single_line() {
        $line = PHP_UTF8::mirror_wordwrap('äbüöcß', 2, ' ', FALSE);
        $this->assertEquals('äbüöcß', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_no_cut_multi_line() {
        $line = PHP_UTF8::mirror_wordwrap('äbüöc ß äbüöcß', 2, "\n", FALSE);
        $this->assertEquals("äbüöc\nß\näbüöcß", $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_no_cut_multi_word() {
        $line = PHP_UTF8::mirror_wordwrap('äöü äöü äöü', 5, "\n", FALSE);
        $this->assertEquals("äöü\näöü\näöü", $line);
    }

	// Test mirror_wordwrap()
    /**
     * Break no-cut tests
     */
    public function test_mirror_wordwrap_no_cut_break_before() {
        $line = PHP_UTF8::mirror_wordwrap('foobar-foofoofoo', 8, '-', FALSE);
        $this->assertEquals('foobar-foofoofoo', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_no_cut_break_with() {
        $line = PHP_UTF8::mirror_wordwrap('foobar-foobar', 6, '-', FALSE);
        $this->assertEquals('foobar-foobar', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_no_cut_break_within() {
        $line = PHP_UTF8::mirror_wordwrap('foobar-foobar', 7, '-', FALSE);
        $this->assertEquals('foobar-foobar', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_no_cut_break_within_end() {
        $line = PHP_UTF8::mirror_wordwrap('foobar-', 7, '-', FALSE);
        $this->assertEquals('foobar-', $line);
    }

	// Test mirror_wordwrap()
    public function test_mirror_wordwrap_no_cut_break_after() {
        $line = PHP_UTF8::mirror_wordwrap('foobar-foobar', 5, '-', FALSE);
        $this->assertEquals('foobar-foobar', $line);
    }

	
	
	
	// Test mirror_ucfirst()
    public function test_mirror_ucfirst() {
		$str = 'ñtërnâtiônàlizætiøn';
		$ucfirst = 'Ñtërnâtiônàlizætiøn';
		$this->assertEquals($ucfirst, PHP_UTF8::mirror_ucfirst($str));
	}

	// Test mirror_ucfirst()
	public function test_mirror_ucfirst_space() {
		$str = ' iñtërnâtiônàlizætiøn';
		$ucfirst = ' iñtërnâtiônàlizætiøn';
		$this->assertEquals($ucfirst, PHP_UTF8::mirror_ucfirst($str));
	}

	// Test mirror_ucfirst()
	public function test_mirror_ucfirst_upper() {
		$str = 'Ñtërnâtiônàlizætiøn';
		$ucfirst = 'Ñtërnâtiônàlizætiøn';
		$this->assertEquals($ucfirst, PHP_UTF8::mirror_ucfirst($str));
	}

	// Test mirror_ucfirst()
	public function test_mirror_ucfirst_empty_string() {
		$str = '';
		$this->assertEquals('', PHP_UTF8::mirror_ucfirst($str));
	}

	// Test mirror_ucfirst()
	public function test_mirror_ucfirst_one_char() {
		$str = 'ñ';
		$ucfirst = "Ñ";
		$this->assertEquals($ucfirst, PHP_UTF8::mirror_ucfirst($str));
	}

	// Test mirror_ucfirst()
	public function test_mirror_ucfirst_linefeed() {
		$str = "ñtërn\nâtiônàlizætiøn";
		$ucfirst = "Ñtërn\nâtiônàlizætiøn";
		$this->assertEquals($ucfirst, PHP_UTF8::mirror_ucfirst($str));
	}

    // Test mirror_rtrim()
    public function test_mirror_rtrim() {
		$str = 'Iñtërnâtiônàlizætiø';
		$trimmed = 'Iñtërnâtiônàlizæti';
		$this->assertEquals($trimmed, PHP_UTF8::mirror_rtrim($str, 'ø'));
	}

    // Test mirror_rtrim()
	public function test_no_trim() {
		$str = 'Iñtërnâtiônàlizætiøn ';
		$trimmed = 'Iñtërnâtiônàlizætiøn ';
		$this->assertEquals($trimmed, PHP_UTF8::mirror_rtrim($str, 'ø'));
	}

    // Test mirror_rtrim()
	public function test_mirror_rtrim_empty_string() {
		$str = '';
		$trimmed = '';
		$this->assertEquals($trimmed, PHP_UTF8::mirror_rtrim($str));
	}

    // Test mirror_rtrim()
	public function test_mirror_rtrim_linefeed() {
		$str = "Iñtërnâtiônàlizætiø\nø";
		$trimmed = "Iñtërnâtiônàlizætiø\n";
		$this->assertEquals($trimmed, PHP_UTF8::mirror_rtrim($str, 'ø'));
	}

    // Test mirror_rtrim()
	public function test_mirror_rtrim_linefeed_mask() {
		$str = "Iñtërnâtiônàlizætiø\nø";
		$trimmed = "Iñtërnâtiônàlizæti";
		$this->assertEquals($trimmed, PHP_UTF8::mirror_rtrim($str, "ø\n"));
	}

    // Test mirror_ltrim()
    public function test_mirror_ltrim() {
		$str = 'ñtërnâtiônàlizætiøn';
		$trimmed = 'tërnâtiônàlizætiøn';
		$this->assertEquals($trimmed, PHP_UTF8::mirror_ltrim($str, 'ñ'));
	}

    // Test mirror_ltrim()
	public function test_mirror_ltrim_no_trim() {
		$str = ' Iñtërnâtiônàlizætiøn';
		$trimmed = ' Iñtërnâtiônàlizætiøn';
		$this->assertEquals($trimmed, PHP_UTF8::mirror_ltrim($str, 'ñ'));
	}

    // Test mirror_ltrim()
	public function test_mirror_ltrim_empty_string() {
		$str = '';
		$trimmed = '';
		$this->assertEquals($trimmed, PHP_UTF8::mirror_ltrim($str));
	}

    // Test mirror_ltrim()
	public function test_mirror_ltrim_forward_slash() {
		$str = '/Iñtërnâtiônàlizætiøn';
		$trimmed = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals($trimmed, PHP_UTF8::mirror_ltrim($str, '/'));
	}

    // Test mirror_ltrim()
	public function test_mirror_ltrim_negate_char_class() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$trimmed = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals($trimmed, PHP_UTF8::mirror_ltrim($str, '^s'));
	}

    // Test mirror_ltrim()
	public function test_mirror_ltrim_linefeed() {
		$str = "ñ\nñtërnâtiônàlizætiøn";
		$trimmed = "\nñtërnâtiônàlizætiøn";
		$this->assertEquals($trimmed, PHP_UTF8::mirror_ltrim($str, 'ñ'));
	}

    // Test mirror_ltrim()
	public function test_mirror_ltrim_linefeed_mask() {
		$str = "ñ\nñtërnâtiônàlizætiøn";
		$trimmed = "tërnâtiônàlizætiøn";
		$this->assertEquals($trimmed, PHP_UTF8::mirror_ltrim($str, "ñ\n"));
	}

    // Test mirror_trim()
    public function test_mirror_trim() {
		$str = 'ñtërnâtiônàlizætiø';
		$trimmed = 'tërnâtiônàlizæti';
		$this->assertEquals($trimmed, PHP_UTF8::mirror_trim($str, 'ñø'));
	}

    // Test mirror_trim()
	public function test_mirror_trim_no_trim() {
		$str = ' Iñtërnâtiônàlizætiøn ';
		$trimmed = ' Iñtërnâtiônàlizætiøn ';
		$this->assertEquals($trimmed, PHP_UTF8::mirror_trim($str, 'ñø'));
	}

    // Test mirror_trim()
	public function test_mirror_trim_empty_string() {
		$str = '';
		$trimmed = '';
		$this->assertEquals($trimmed, PHP_UTF8::mirror_trim($str));
	}

    // Test mirror_substr_replace()
    public function test_mirror_substr_replace_replace_start() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$replaced = 'IñtërnâtX';
		$this->assertEquals($replaced, PHP_UTF8::mirror_substr_replace($str, 'X', 8));
	}

    // Test mirror_substr_replace()
	public function test_mirror_substr_replace_empty_string() {
		$str = '';
		$replaced = 'X';
		$this->assertEquals($replaced, PHP_UTF8::mirror_substr_replace($str, 'X', 8));
	}

    // Test mirror_substr_replace()
	public function test_mirror_substr_replace_negative() {
		$str = 'testing';
		$replaced = substr_replace($str, 'foo', -2, -2);
		$this->assertEquals($replaced, PHP_UTF8::mirror_substr_replace($str, 'foo', -2, -2));
	}

    // Test mirror_substr_replace()
	public function test_mirror_substr_replace_zero() {
		$str = 'testing';
		$replaced = substr_replace($str, 'foo', 0, 0);
		$this->assertEquals($replaced, PHP_UTF8::mirror_substr_replace($str, 'foo', 0, 0));
	}

    // Test mirror_substr_replace()
	public function test_mirror_substr_replace_linefeed() {
		$str = "Iñ\ntërnâtiônàlizætiøn";
		$replaced = "Iñ\ntërnâtX";
		$this->assertEquals($replaced, PHP_UTF8::mirror_substr_replace($str, 'X', 9));
	}

    // Test mirror_substr_replace()
	public function test_mirror_substr_replace_linefeed_replace() {
		$str = "Iñ\ntërnâtiônàlizætiøn";
		$replaced = "Iñ\ntërnâtX\nY";
		$this->assertEquals($replaced, PHP_UTF8::mirror_substr_replace($str, "X\nY", 9));
	}

    // Test mirror_strspn()
	public function test_mirror_strspn_match() {
		$str = 'iñtërnâtiônàlizætiøn';
		$this->assertEquals(11, PHP_UTF8::mirror_strspn($str, 'âëiônñrt'));
	}

    // Test mirror_strspn()
	public function test_mirror_strspnmatch_two() {
		$str = 'iñtërnâtiônàlizætiøn';
		$this->assertEquals(4, PHP_UTF8::mirror_strspn($str, 'iñtë'));
	}

    // Test mirror_strspn()
	public function test_mirror_strspncompare_strspn() {
		$str = 'aeioustr';
		$this->assertEquals(strspn($str, 'saeiou'), PHP_UTF8::mirror_strspn($str, 'saeiou'));
	}

    // Test mirror_strspn()
	public function test_mirror_strspnmatch_ascii() {
		$str = 'internationalization';
		$this->assertEquals(strspn($str, 'aeionrt'), PHP_UTF8::mirror_strspn($str, 'aeionrt'));
	}

    // Test mirror_strspn()
	public function test_mirror_strspnlinefeed() {
		$str = "iñtërnât\niônàlizætiøn";
		$this->assertEquals(8, PHP_UTF8::mirror_strspn($str, 'âëiônñrt'));
	}

    // Test mirror_strspn()
	public function test_mirror_strspnlinefeed_mask() {
		$str = "iñtërnât\niônàlizætiøn";
		$this->assertEquals(12, PHP_UTF8::mirror_strspn($str, "âëiônñrt\n"));
	}
	
    // Test mirror_strrev()
    public function test_mirror_strrev() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$rev = 'nøitæzilànôitânrëtñI';
		$this->assertEquals($rev, PHP_UTF8::mirror_strrev($str));
	}

    // Test mirror_strrev()
	public function test_mirror_strrevempty_str() {
		$str = '';
		$rev = '';
		$this->assertEquals($rev, PHP_UTF8::mirror_strrev($str));
	}

    // Test mirror_strrev()
	public function test_mirror_strrevlinefeed() {
		$str = "Iñtërnâtiôn\nàlizætiøn";
		$rev = "nøitæzilà\nnôitânrëtñI";
		$this->assertEquals($rev, PHP_UTF8::mirror_strrev($str));
	}

    // Test mirror_stristr()
    public function test_mirror_stristr_substr() {
		$str = 'iñtërnâtiônàlizætiøn';
		$search = 'NÂT';
		$this->assertEquals('nâtiônàlizætiøn', PHP_UTF8::mirror_stristr($str, $search));
	}

    // Test mirror_stristr()
	public function test_mirror_stristr_substr_no_match() {
		$str = 'iñtërnâtiônàlizætiøn';
		$search = 'foo';
		$this->assertFalse(PHP_UTF8::mirror_stristr($str, $search));
	}

    // Test mirror_stristr()
	public function test_mirror_stristr_empty_search() {
		$str = 'iñtërnâtiônàlizætiøn';
		$search = '';
		$this->assertEquals('iñtërnâtiônàlizætiøn', PHP_UTF8::mirror_stristr($str, $search));
	}

    // Test mirror_stristr()
	public function test_mirror_stristr_empty_str() {
		$str = '';
		$search = 'NÂT';
		$this->assertFalse(PHP_UTF8::mirror_stristr($str, $search));
	}

    // Test mirror_stristr()
	public function test_mirror_stristr_empty_both() {
		$str = '';
		$search = '';
		$this->assertEmpty(PHP_UTF8::mirror_stristr($str, $search));
	}

    // Test mirror_stristr()
	public function test_mirror_stristr_linefeed_str() {
		$str = "iñt\nërnâtiônàlizætiøn";
		$search = 'NÂT';
		$this->assertEquals('nâtiônàlizætiøn', PHP_UTF8::mirror_stristr($str, $search));
	}

    // Test mirror_stristr()
	public function test_mirror_stristr_linefeed_both() {
		$str = "iñtërn\nâtiônàlizætiøn";
		$search = "N\nÂT";
		$this->assertEquals("n\nâtiônàlizætiøn", PHP_UTF8::mirror_stristr($str, $search));
	}

    // Test mirror_strcspn()
	public function test_mirror_strcspn_no_match_single_byte_search() {
		$str = 'iñtërnâtiônàlizætiøn';
		$this->assertEquals(2, PHP_UTF8::mirror_strcspn($str, 't'));
	}

    // Test mirror_strcspn()
	public function test_mirror_strcspn_no_match_multi_byte_search() {
		$str = 'iñtërnâtiônàlizætiøn';
		$this->assertEquals(6, PHP_UTF8::mirror_strcspn($str, 'â'));
	}

    // Test mirror_strcspn()
	public function test_mirror_strcspn_compare_strspn() {
		$str = 'aeioustr';
		$this->assertEquals(strcspn($str, 'tr'), PHP_UTF8::mirror_strcspn($str, 'tr'));
	}

    // Test mirror_strcspn()
	public function test_mirror_strcspn_match_ascii() {
		$str = 'internationalization';
		$this->assertEquals(strcspn($str, 'a'), PHP_UTF8::mirror_strcspn($str, 'a'));
	}

    // Test mirror_strcspn()
	public function test_mirror_strcspn_linefeed() {
		$str = "i\nñtërnâtiônàlizætiøn";
		$this->assertEquals(3, PHP_UTF8::mirror_strcspn($str, 't'));
	}

    // Test mirror_strcspn()
	public function test_mirror_strcspn_linefeed_mask() {
		$str = "i\nñtërnâtiônàlizætiøn";
		$this->assertEquals(1, PHP_UTF8::mirror_strcspn($str, "\n"));
	}


    // Test mirror_str_ireplace()
    public function test_mirror_str_ireplace_replace() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$replaced = 'Iñtërnâtiônàlisetiøn';
		$this->assertEquals($replaced, PHP_UTF8::mirror_str_ireplace('lIzÆ', 'lise', $str));
	}

    // Test mirror_str_ireplace()
	public function test_mirror_str_ireplace_replace_no_match() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$replaced = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals($replaced, PHP_UTF8::mirror_str_ireplace('foo', 'bar', $str));
	}

    // Test mirror_str_ireplace()
	public function test_mirror_str_ireplace_empty_string() {
		$str = '';
		$replaced = '';
		$this->assertEquals($replaced, PHP_UTF8::mirror_str_ireplace('foo', 'bar', $str));
	}

    // Test mirror_str_ireplace()
	public function test_mirror_str_ireplace_empty_search() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$replaced = 'Iñtërnâtiônàlizætiøn';
		$this->assertEquals($replaced, PHP_UTF8::mirror_str_ireplace('', 'x', $str));
	}

    // Test mirror_str_ireplace()
	public function test_mirror_str_ireplace_replace_count() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$replaced = 'IñtërXâtiôXàlizætiøn';
		$this->assertEquals($replaced, PHP_UTF8::mirror_str_ireplace('n', 'X', $str, 2));
	}

    // Test mirror_str_ireplace()
	public function test_mirror_str_ireplace_replace_different_search_replace_length() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$replaced = 'IñtërXXXâtiôXXXàlizætiøXXX';
		$this->assertEquals($replaced, PHP_UTF8::mirror_str_ireplace('n', 'XXX', $str));
	}

    // Test mirror_str_ireplace()
	public function test_mirror_str_ireplace_replace_array_ascii_search() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$replaced = 'Iñyërxâyiôxàlizæyiøx';
		$this->assertEquals($replaced, PHP_UTF8::mirror_str_ireplace(array('n', 't'), array('x', 'y'), $str));
	}

    // Test mirror_str_ireplace()
	public function test_mirror_str_ireplace_replace_array_utf8_search() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$replaced = 'I?tërnâti??nàliz????ti???n';
		$this->assertEquals(
			PHP_UTF8::mirror_str_ireplace(
				array('Ñ', 'ô', 'ø', 'Æ'),
				array('?', '??', '???', '????'),
				$str),
			$replaced);
	}

    // Test mirror_str_ireplace()
	public function test_mirror_str_ireplace_replace_array_string_replace() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$replaced = 'I?tërnâti?nàliz?ti?n';
		$this->assertEquals(
			$replaced,
			PHP_UTF8::mirror_str_ireplace(
				array('Ñ', 'ô', 'ø', 'Æ'),
				'?',
				$str)
		);
	}

    // Test mirror_str_ireplace()
	public function test_mirror_str_ireplace_replace_array_single_array_replace() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$replaced = 'I?tërnâtinàliztin';
		$this->assertEquals(
			PHP_UTF8::mirror_str_ireplace(
				array('Ñ', 'ô', 'ø', 'Æ'),
				array('?'),
				$str),
			$replaced);
	}

    // Test mirror_str_ireplace()
	public function test_mirror_str_ireplace_replace_linefeed() {
		$str = "Iñtërnâti\nônàlizætiøn";
		$replaced = "Iñtërnâti\nônàlisetiøn";
		$this->assertEquals($replaced, PHP_UTF8::mirror_str_ireplace('lIzÆ', 'lise', $str));
	}

    // Test mirror_str_ireplace()
	public function test_mirror_str_ireplace_replace_linefeed_search() {
		$str = "Iñtërnâtiônàli\nzætiøn";
		$replaced = "Iñtërnâtiônàlisetiøn";
		$this->assertEquals($replaced, PHP_UTF8::mirror_str_ireplace("lI\nzÆ", 'lise', $str));
	}

    // Test mirror_str_split()
	public function test_mirror_str_split_split_one_char() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$array = array(
			'I','ñ','t','ë','r','n','â','t','i','ô','n','à','l','i',
			'z','æ','t','i','ø','n',
		);

		$this->assertEquals($array, PHP_UTF8::mirror_str_split($str));
	}

    // Test mirror_str_split()
	public function test_mirror_str_split_split_five_chars() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$array = array(
			'Iñtër','nâtiô','nàliz','ætiøn',
		);

		$this->assertEquals($array, PHP_UTF8::mirror_str_split($str, 5));
	}

    // Test mirror_str_split()
	public function test_mirror_str_split_split_six_chars() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$array = array(
			'Iñtërn','âtiônà', 'lizæti','øn',
		);

		$this->assertEquals($array, PHP_UTF8::mirror_str_split($str, 6));
	}

    // Test mirror_str_split()
	public function test_mirror_str_split_split_long() {
		$str = 'Iñtërnâtiônàlizætiøn';
		$array = array(
			'Iñtërnâtiônàlizætiøn',
		);

		$this->assertEquals($array, PHP_UTF8::mirror_str_split($str, 40));
	}

    // Test mirror_str_split()
	public function test_mirror_str_split_split_newline() {
		$str = "Iñtërn\nâtiônàl\nizætiøn\n";
		$array = array(
			'I','ñ','t','ë','r','n',"\n",'â','t','i','ô','n','à','l',"\n",'i',
			'z','æ','t','i','ø','n',"\n",
		);

		$this->assertEquals($array, PHP_UTF8::mirror_str_split($str));
	}
	
	// Test mirror_strcasecmp()
	public function test_mirror_strcasecmp_compare_equal() {
		$str_x = 'iñtërnâtiônàlizætiøn';
		$str_y = 'IÑTËRNÂTIÔNÀLIZÆTIØN';
		$this->assertEquals(0, PHP_UTF8::mirror_strcasecmp($str_x, $str_y));
	}

	// Test mirror_strcasecmp()
	public function test_mirror_strcasecmp_less() {
		$str_x = 'iñtërnâtiônàlizætiøn';
		$str_y = 'IÑTËRNÂTIÔÀLIZÆTIØN';
		$this->assertTrue(PHP_UTF8::mirror_strcasecmp($str_x, $str_y) < 0);
	}

	// Test mirror_strcasecmp()
	public function test_mirror_strcasecmp_greater() {
		$str_x = 'iñtërnâtiôàlizætiøn';
		$str_y = 'IÑTËRNÂTIÔNÀLIZÆTIØN';
		$this->assertTrue(PHP_UTF8::mirror_strcasecmp($str_x, $str_y) > 0);
	}

	// Test mirror_strcasecmp()
	public function test_mirror_strcasecmp_empty_x() {
		$str_x = '';
		$str_y = 'IÑTËRNÂTIÔNÀLIZÆTIØN';
		$this->assertTrue(PHP_UTF8::mirror_strcasecmp($str_x, $str_y) < 0);
	}

	// Test mirror_strcasecmp()
	public function test_mirror_strcasecmp_empty_y() {
		$str_x = 'iñtërnâtiôàlizætiøn';
		$str_y = '';
		$this->assertTrue(PHP_UTF8::mirror_strcasecmp($str_x, $str_y) > 0);
	}

	// Test mirror_strcasecmp()
	public function test_mirror_strcasecmp_empty_both() {
		$str_x = '';
		$str_y = '';
		$this->assertTrue(PHP_UTF8::mirror_strcasecmp($str_x, $str_y) == 0);
	}

	// Test mirror_strcasecmp()
	public function test_mirror_strcasecmp_linefeed() {
		$str_x = "iñtërnâtiôn\nàlizætiøn";
		$str_y = "IÑTËRNÂTIÔN\nÀLIZÆTIØN";
		$this->assertTrue(PHP_UTF8::mirror_strcasecmp($str_x, $str_y) == 0);
	}
	
	// Test mirror_ord()
	public function test_mirror_ord_empty_str() {
		$str = '';
		$this->assertEquals(0, PHP_UTF8::mirror_ord($str));
	}

	// Test mirror_ord()
	public function test_mirror_ord_ascii_char() {
		$str = 'a';
		$this->assertEquals(97, PHP_UTF8::mirror_ord($str));
	}

	// Test mirror_ord()
	public function test_mirror_ord_2_byte_char() {
		$str = 'ñ';
		$this->assertEquals(241, PHP_UTF8::mirror_ord($str));
	}

	// Test mirror_ord()
	public function test_mirror_ord_3_byte_char() {
		$str = '₧';
		$this->assertEquals(8359, PHP_UTF8::mirror_ord($str));
	}

	// Test mirror_ord()
	public function test_mirror_ord_4_byte_char() {
		$str = "\xf0\x90\x8c\xbc";
		$this->assertEquals(66364, PHP_UTF8::mirror_ord($str));
	}
	
}
