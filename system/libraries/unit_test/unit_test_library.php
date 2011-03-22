<?php
/**
 * Simple Unit Testing Class
 */
class Unit_Test_Library {

	private $_results = array();
	private $_msg = array();
	private $_active = TRUE;
	private $_strict = FALSE;
	private $_template = NULL;
	private $_template_rows	= NULL;
	
	public function __construct($config = array()) {
		$this->_msg = array(
			'ut_test_name' => 'Test Name',
			'ut_test_datatype' => 'Test Datatype',
			'ut_res_datatype' => 'Expected Datatype',
			'ut_result' => 'Result',
			'ut_undefined' => 'Undefined Test Name',
			'ut_file' => 'File Name',
			'ut_line' => 'Line Number',
			'ut_passed' => 'Passed',
			'ut_failed' => 'Failed',
			'ut_boolean' => 'Boolean',
			'ut_integer' => 'Integer',
			'ut_float' => 'Float',
			'ut_double' => 'Float', // can be the same as float
			'ut_string' => 'String',
			'ut_array' => 'Array',
			'ut_object' => 'Object',
			'ut_resource' => 'Resource',
			'ut_null' => 'Null',
		);
		
		if (count($config) > 0) {
			$this->initialize($config);
		}
	}	
	
	/**
	 * Initialize the user preferences
	 *
	 * Accepts an associative array as input
	 *
	 * @param	array	config preferences
	 * @return	void
	 */	
	public function initialize($config = array()) {
		// Enables/disables unit testing
		if (isset($config['active'])) {
			$this->_active = $config['active'];
		}
		
		// Causes the evaluation to use === rather than ==
		if (isset($config['strict'])) {
			$this->_strict = $config['strict'];
		}
		
		// This lets us set the template to be used to display results
		if (isset($config['template'])) {
			$this->_template = $config['template'];
			
			/*
			$str = '
				<table border="0" cellpadding="4" cellspacing="1">
				{rows}<tr><td>{item}</td><td>{result}</td></tr>{/rows}
				</table>';
			*/
		}
	}
	
	/**
	 * Run the tests
	 *
	 * Runs the supplied tests
	 *
	 * @param	mixed
	 * @param	mixed
	 * @param	string
	 * @return	string
	 */	
	public function run($test, $expected = TRUE, $test_name = 'undefined') {
		// If you would like to leave some testing in place in your scripts, 
		// but not have it run unless you need it, you can disable unit testing
		if ($this->_active == FALSE) {
			return FALSE;
		}
	
		if (in_array($expected, array('is_string', 'is_bool', 'is_true', 'is_false', 'is_int', 'is_numeric', 'is_float', 'is_double', 'is_array', 'is_null'), TRUE)) {
			$expected = str_replace('is_float', 'is_double', $expected);
			$result = ($expected($test)) ? TRUE : FALSE;	
			$extype = str_replace(array('true', 'false'), 'bool', str_replace('is_', '', $expected));
		} else {
			if ($this->_strict == TRUE) {
				$result = ($test === $expected) ? TRUE : FALSE;	
			} else {
				$result = ($test == $expected) ? TRUE : FALSE;	
			}
			$extype = gettype($expected);
		}
				
		$back = $this->_backtrace();
	
		$report[] = array (
			'test_name'			=> $test_name,
			'test_datatype'		=> gettype($test),
			'res_datatype'		=> $extype,
			'result'			=> ($result === TRUE) ? 'passed' : 'failed',
			'file'				=> $back['file'],
			'line'				=> $back['line']
		);

		$this->_results[] = $report;		
				
		return($this->report($this->result($report)));
	}

	/**
	 * Generate a report
	 *
	 * Run several tests and generate a report which formatted in an HTML table for viewingat the end
	 *
	 * @return	string
	 */
	public function report($result = array()) {
		if (count($result) == 0) {
			$result = $this->result();
		}

		$this->_parse_template();

		$r = '';
		foreach ($result as $res) {
			$table = '';
			foreach ($res as $key => $val) {
				if ($key == $this->_line('ut_result')) {
					if ($val == $this->_line('ut_passed')) {
						$val = '<span style="color: #0C0;">'.$val.'</span>';
					} elseif ($val == $this->_line('ut_failed')) {
						$val = '<span style="color: #C00;">'.$val.'</span>';
					}
				}

				$temp = $this->_template_rows;
				$temp = str_replace('{item}', $key, $temp);
				$temp = str_replace('{result}', $val, $temp);
				$table .= $temp;
			}

			$r .= str_replace('{rows}', $table, $this->_template);
		}

		return $r;
	}

	/**
	 * Result Array
	 *
	 * Returns the raw result data, print_r() to display returned data
	 *
	 * @return	array
	 */
	public function result($results = array()) {	
		if (count($results) == 0) {
			$results = $this->_results;
		}
		
		$retval = array();
		foreach ($results as $result) {
			$temp = array();
			foreach ($result as $key => $val) {
				if (is_array($val)) {
					foreach ($val as $k => $v) {
						if (FALSE !== ($line = $this->_line(strtolower('ut_'.$v)))) {
							$v = $line;
						}				
						$temp[$this->_line('ut_'.$k)] = $v;					
					}
				} else {
					if (FALSE !== ($line = $this->_line(strtolower('ut_'.$val)))) {
						$val = $line;
					}				
					$temp[$this->_line('ut_'.$key)] = $val;
				}
			}
			
			$retval[] = $temp;
		}
	
		return $retval;
	}

	/**
	 * Generate a backtrace
	 *
	 * This lets us show file names and line numbers
	 *
	 * @return	array
	 */
	private function _backtrace() {
		if (function_exists('debug_backtrace')) {
			$back = debug_backtrace();
			
			$file = isset($back['1']['file']) ? $back['1']['file'] : '';
			$line = isset($back['1']['line']) ? $back['1']['line'] : '';
						
			return array('file' => $file, 'line' => $line);
		}
		return array('file' => 'Unknown', 'line' => 'Unknown');
	}

	/**
	 * Get Default Template
	 *
	 * @return	string
	 */
	private function _default_template() {	
		$this->_template = "\n".'<table style="width:100%; margin:10px 0; border-collapse:collapse; border:1px solid #ccc;">';
		$this->_template .= '{rows}';
		$this->_template .= "\n".'</table>';
		
		$this->_template_rows = "\n\t".'<tr>';
		$this->_template_rows .= "\n\t\t".'<td style="text-align: left; font-weight:700; border-bottom:1px solid #ccc;">{item}</td>';
		$this->_template_rows .= "\n\t\t".'<td style="border-bottom:1px solid #ccc;">{result}</td>';
		$this->_template_rows .= "\n\t".'</tr>';	
	}

	/**
	 * Parse Template
	 *
	 * Harvests the data within the template {pseudo-variables}
	 *
	 * @return	void
	 */
 	private function _parse_template() {
 		if ( ! is_null($this->_template_rows)) {
 			return;
 		}
 		
 		if (is_null($this->_template)) {
 			$this->_default_template();
 			return;
 		}
 		
		if ( ! preg_match("/\{rows\}(.*?)\{\/rows\}/si", $this->_template, $match)) {
 			$this->_default_template();
 			return;
		}

		$this->_template_rows = $match['1'];
		$this->_template = str_replace($match['0'], '{rows}', $this->_template); 	
 	}
	
	/**
	 * Fetch a single line of text from the msg array
	 *
	 * @param	string	$line 	the msg line
	 * @return	string
	 */
	private function _line($line = '') {
		$line = ($line == '' OR ! isset($this->_msg[$line])) ? FALSE : $this->_msg[$line];
		return $line;
	}
 	
}

/**
 * Helper functions to test boolean true/false
 *
 * @return	bool
 */
function is_true($test) {
	return (is_bool($test) AND $test === TRUE) ? TRUE : FALSE;
}

function is_false($test) {
	return (is_bool($test) AND $test === FALSE) ? TRUE : FALSE;
}


/* End of file: ./system/libraries/unit_test/unit_test_library.php */