<?php
/**
 * Form Validation Library
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class Form_Validation_Library {
	/**
	 * Key-value array of HTTP POST parameters to be validated
	 * 
	 * @var array
	 */
	private $_post = array();	
	
	private $_field_data = array();	
	private $_error_array = array();
	private $_error_messages = array();	
	private $_error_prefix = '<div>';
	private $_error_suffix = '</div>';
	private $_safe_form_data = FALSE;
	
	/**
	 * Constructor
	 *
	 * $config must contain ['post']
	 */	
	public function __construct(array $config = NULL) { 
		// Assign the $this->_POST_DATA array
		$this->_post = $config['post'];
		
		// Set the character encoding in MB.
		if (function_exists('mb_internal_encoding')){
			mb_internal_encoding('UTF-8');
		}

		$this->_error_messages = array(
			'required' => "The %s field is required.",
			'isset' => "The %s field must have a value.",
			'valid_email' => "The %s field must contain a valid email address.",
			'valid_emails' => "The %s field must contain a valid email address.",
			'min_length' => "The %s field must be at least %s characters in length.",
			'max_length' => "The %s field can not exceed %s characters in length.",
			'exact_length' => "The %s field must be exactly %s characters in length.",
			'equals' => "The %s field must equal the value designed.",
			'form_token' => "Please do not resubmit the form data.",
			'alpha' => "The %s field may only contain alphabetical characters.",
			'alpha_numeric' => "The %s field may only contain alpha-numeric characters.",
			'alpha_dash' => "The %s field may only contain alpha-numeric characters, underscores, and dashes.",
			'numeric' => "The %s field must contain only numbers.",
			'is_numeric' => "The %s field must contain only numeric characters.",
			'is_integer' => "The %s field must contain an integer.",
			'matches' => "The %s field does not match the %s field.",
			'decimal' => "The %s field must contain a decimal number.",
			'less_than' => "The %s field must contain a number less than %s.",
			'greater_than' => "The %s field must contain a number greater than %s.",
			'is_natural' => "The %s field must contain only positive numbers.",
			'is_natural_no_zero' => "The %s field must contain a number greater than zero.",
		);	
	}

	/**
	 * Set Rules
	 *
	 * This function takes an array of field names and validation
	 * rules as input, validates the info, and stores it
	 *
	 * @param	string  the field name
	 * @param	string  
	 * @param	string  
	 * @return	void
	 */
	public function set_rules($field = '', $label = '', $rules = '') {
		// No reason to set rules if we have no POST data
		if (count($this->_post) == 0) {
			return;
		}

		// If the field label wasn't passed we use the field name
		$label = ($label == '') ? $field : $label;

		// Is the field name an array?  We test for the existence of a bracket "[" in
		// the field name to determine this.  If it is an array, we break it apart
		// into its components so that we can fetch the corresponding POST data later		
		if (strpos($field, '[') !== FALSE && preg_match_all('/\[(.*?)\]/', $field, $matches)) {	
			// Note: Due to a bug in current() that affects some versions
			// of PHP we can not pass function call directly into it
			$x = explode('[', $field);
			$indexes[] = current($x);

			for ($i = 0; $i < count($matches['0']); $i++) {
				if ($matches['1'][$i] != '') {
					$indexes[] = $matches['1'][$i];
				}
			}
			
			$is_array = TRUE;
		} else {
			$indexes = array();
			$is_array = FALSE;		
		}
		
		// Build our master array		
		$this->_field_data[$field] = array(
			'field'	=> $field, 
			'label' => $label, 
			'rules' => $rules,
			'is_array' => $is_array,
			'keys' => $indexes,
			'postdata' => NULL,
			'error' => ''
		);
	}


	/**
	 * Set The Error Delimiter
	 *
	 * Permits a prefix/suffix to be added to each error message
	 *
	 * @param	string
	 * @param	string
	 * @return	void
	 */	
	public function set_error_delimiters($prefix = '<div>', $suffix = '</div>') {
		$this->_error_prefix = $prefix;
		$this->_error_suffix = $suffix;
	}


	/**
	 * Get Single Field Error Message
	 *
	 * Gets the error message associated with a particular field
	 *
	 * @param	string	the field name
	 * @param	string
	 * @param	string
	 * @return	string
	 */	
	public function field_error($field = '', $prefix = '', $suffix = '') {	
		if ( ! isset($this->_field_data[$field]['error']) || $this->_field_data[$field]['error'] == '') {
			return '';
		}
		
		if ($prefix == '') {
			$prefix = $this->_error_prefix;
		}

		if ($suffix == '') {
			$suffix = $this->_error_suffix;
		}

		return $prefix.$this->_field_data[$field]['error'].$suffix;
	}


	/**
	 * Error String
	 *
	 * Returns the error messages as a string, wrapped in the error delimiters
	 * If there are no error messages it returns an empty string.
	 *
	 * @param	string
	 * @param	string
	 * @return	string 
	 */	
	public function form_errors($prefix = '', $suffix = '') {
		// No errrors, validation passes!
		if (count($this->_error_array) === 0) {
			return '';
		}
		
		if ($prefix == '') {
			$prefix = $this->_error_prefix;
		}

		if ($suffix == '') {
			$suffix = $this->_error_suffix;
		}
		
		// Generate the error string
		$str = '';
		foreach ($this->_error_array as $val) {
			if ($val != '') {
				$str .= $prefix.$val.$suffix."\n";
			}
		}
		return $str;
	}


	/**
	 * Run the Validator
	 *
	 * This function does all the work.
	 *
	 * @return	bool
	 */		
	public function run() {
		// Do we even have any data to process?
		if (count($this->_post) == 0) {
			return FALSE;
		}
		
		// Does the _field_data array containing the validation rules exist?
		if (count($this->_field_data) == 0) {
			return FALSE;
		}

		// Cycle through the rules for each field, match the 
		// corresponding $this->_post item and test for errors
		foreach ($this->_field_data as $field => $row) {		
			// Fetch the data from the corresponding $this->_post array and cache it in the _field_data array.
			// Depending on whether the field name is an array or a string will determine where we get it from.
			if ($row['is_array'] == TRUE) {
				$this->_field_data[$field]['postdata'] = $this->_reduce_array($this->_post, $row['keys']);
			} else {
				if (isset($this->_post[$field]) && $this->_post[$field] != '') {
					$this->_field_data[$field]['postdata'] = $this->_post[$field];
				}
			}
		
			$this->_execute($row, explode('|', $row['rules']), $this->_field_data[$field]['postdata']);		
		}

		// Did we end up with any errors?
		$total_errors = count($this->_error_array);

		if ($total_errors > 0) {
			$this->_safe_form_data = TRUE;
		}

		// Now we need to re-set the POST data with the new, processed data
		$this->_reset_post_array();
		
		// No errors, validation passes!
		if ($total_errors == 0) {
			return TRUE;
		}

		// Validation fails
		return FALSE;
	}


	/**
	 * Traverse a multidimensional $this->_post array index until the data is found
	 *
	 * @param	array
	 * @param	array
	 * @param	integer
	 * @return	mixed
	 */		
	private function _reduce_array($array, $keys, $i = 0) {
		if (is_array($array)) {
			if (isset($keys[$i])) {
				if (isset($array[$keys[$i]])) {
					$array = $this->_reduce_array($array[$keys[$i]], $keys, ($i+1));
				} else {
					return NULL;
				}
			} else {
				return $array;
			}
		}
		return $array;
	}


	/**
	 * Re-populate the $this->_post data array with our finalized and processed data
	 *
	 * @return	null
	 */		
	private function _reset_post_array() {
		foreach ($this->_field_data as $field => $row) {
			if ( ! is_null($row['postdata'])) {
				if ($row['is_array'] == FALSE) {
					if (isset($this->_post[$row['field']])) {
						$this->_post[$row['field']] = $this->prep_for_form($row['postdata']);
					}
				} else {
					// start with a reference
					$post_ref =& $this->_post;
					
					// before we assign values, make a reference to the right POST key
					if (count($row['keys']) == 1) {
						$post_ref =& $post_ref[current($row['keys'])];
					} else {
						foreach ($row['keys'] as $val) {
							$post_ref =& $post_ref[$val];
						}
					}

					if (is_array($row['postdata'])) {
						$array = array();
						foreach ($row['postdata'] as $k => $v) {
							$array[$k] = $this->prep_for_form($v);
						}

						$post_ref = $array;
					} else {
						$post_ref = $this->prep_for_form($row['postdata']);
					}
				}
			}
		}
	}


	/**
	 * Executes the Validation routines
	 *
	 * @param	array
	 * @param	array
	 * @param	mixed
	 * @param	integer
	 * @return	mixed
	 */	
	private function _execute($row, $rules, $postdata = NULL, $cycles = 0) {
		// If the $this->_post data is an array we will run a recursive call
		if (is_array($postdata)) { 
			foreach ($postdata as $key => $val) {
				$this->_execute($row, $rules, $val, $cycles);
				$cycles++;
			}
			return;
		}
		
		// If the field is blank, but NOT required, no further tests are necessary
		if ( ! in_array('required', $rules) && is_null($postdata)) {
			return;
		}

		// Isset Test. Typically this rule will only apply to checkboxes.
		if (is_null($postdata)) {
			if (in_array('isset', $rules, TRUE) || in_array('required', $rules)) {
				// Set the message type
				$type = (in_array('required', $rules)) ? 'required' : 'isset';
			
				if ( ! isset($this->_error_messages[$type])) {
					$line = 'The error message field was not set';						
				} else {
					$line = $this->_error_messages[$type];
				}
				
				// Build the error message
				$message = sprintf($line, $row['label']);

				// Save the error message
				$this->_field_data[$row['field']]['error'] = $message;
				
				if ( ! isset($this->_error_array[$row['field']])) {
					$this->_error_array[$row['field']] = $message;
				}
			}	
			return;
		}

		// Cycle through each rule and run it
		foreach ($rules as $rule) {
			$_in_array = FALSE;
			
			// We set the $postdata variable with the current data in our master array so that
			// each cycle of the loop is dealing with the processed data from the last cycle
			if ($row['is_array'] == TRUE && is_array($this->_field_data[$row['field']]['postdata'])) {
				// We shouldn't need this safety, but just in case there isn't an array index
				// associated with this cycle we'll bail out
				if ( ! isset($this->_field_data[$row['field']]['postdata'][$cycles])) {
					continue;
				}
			
				$postdata = $this->_field_data[$row['field']]['postdata'][$cycles];
				$_in_array = TRUE;
			} else {
				$postdata = $this->_field_data[$row['field']]['postdata'];
			}

			// Strip the parameter (if exists) from the rule
			// Rules can contain a parameter: max_length[5]
			$param = FALSE;
			if (preg_match("/(.*?)\[(.*?)\]/", $rule, $match)) {
				$rule = $match[1];
				$param = $match[2];
			}
			
			// Call the function that corresponds to the rule		
			if ( ! method_exists($this, $rule)) {
				// If our own wrapper function doesn't exist we see if a native PHP function does. 
				// Users can use any native PHP function call that has one param.
				if (function_exists($rule)) {
					$result = $rule($postdata);
										
					if ($_in_array == TRUE) {
						$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
					} else {
						$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
					}
				}
									
				continue;
			}

			$result = $this->$rule($postdata, $param);

			if ($_in_array == TRUE) {
				$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
			} else {
				$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
			}
							
			// Did the rule test negatively?  If so, grab the error.
			if ($result === FALSE) {			
				if ( ! isset($this->_error_messages[$rule])) {
					$line = 'Unable to access an error message corresponding to your field name.';						
				} else {
					$line = $this->_error_messages[$rule];
				}
				
				// Is the parameter we are inserting into the error message the name
				// of another field?  If so we need to grab its "field label"
				if (isset($this->_field_data[$param]) && isset($this->_field_data[$param]['label'])) {
					$param = $this->_field_data[$param]['label'];
				}
				
				// Build the error message
				$message = sprintf($line, $row['label'], $param);

				// Save the error message
				$this->_field_data[$row['field']]['error'] = $message;
				
				if ( ! isset($this->_error_array[$row['field']])) {
					$this->_error_array[$row['field']] = $message;
				}
				
				return;
			}
		}
	}

	
	/**
	 * Get the value from a form
	 *
	 * Permits you to repopulate a form field with the value it was submitted
	 * with, or, if that value doesn't exist, with the default
	 *
	 * @param	string	the field name
	 * @param	string
	 * @return	void
	 */	
	public function set_value($field = '', $default = '') {
		if ( ! isset($this->_field_data[$field])) {
			return $default;
		}
		
		return $this->_field_data[$field]['postdata'];
	}
	

	/**
	 * Set Select
	 *
	 * Enables pull-down lists to be set to the value the user
	 * selected in the event of an error
	 *
	 * @param	string
	 * @param	string
	 * @return	string
	 */	
	public function set_select($field = '', $value = '', $default = FALSE) {		
		if ( ! isset($this->_field_data[$field]) || ! isset($this->_field_data[$field]['postdata'])) {
			if ($default === TRUE && count($this->_field_data) === 0) {
				return ' selected="selected"';
			}
			return '';
		}
	
		$field = $this->_field_data[$field]['postdata'];
		
		if (is_array($field)) {
			if ( ! in_array($value, $field)) {
				return '';
			}
		} else {
			if (($field == '' || $value == '') || ($field != $value)) {
				return '';
			}
		}
			
		return ' selected="selected"';
	}
	

	/**
	 * Set Radio
	 *
	 * Enables radio buttons to be set to the value the user
	 * selected in the event of an error
	 *
	 * @param	string
	 * @param	string
	 * @return	string
	 */	
	public function set_radio($field = '', $value = '', $default = FALSE) {
		if ( ! isset($this->_field_data[$field]) || ! isset($this->_field_data[$field]['postdata'])) {
			if ($default === TRUE && count($this->_field_data) === 0) {
				return ' checked="checked"';
			}
			return '';
		}
	
		$field = $this->_field_data[$field]['postdata'];
		
		if (is_array($field)) {
			if ( ! in_array($value, $field)) {
				return '';
			}
		} else {
			if (($field == '' || $value == '') || ($field != $value)) {
				return '';
			}
		}
			
		return ' checked="checked"';
	}
	

	/**
	 * Set Checkbox
	 *
	 * Enables checkboxes to be set to the value the user
	 * selected in the event of an error
	 *
	 * @param	string
	 * @param	string
	 * @return	string
	 */	
	public function set_checkbox($field = '', $value = '', $default = FALSE) {
		if ( ! isset($this->_field_data[$field]) || ! isset($this->_field_data[$field]['postdata'])) {
			if ($default === TRUE && count($this->_field_data) === 0) {
				return ' checked="checked"';
			}
			return '';
		}
	
		$field = $this->_field_data[$field]['postdata'];
		
		if (is_array($field)) {
			if ( ! in_array($value, $field)) {
				return '';
			}
		} else {
			if (($field == '' || $value == '') || ($field != $value)) {
				return '';
			}
		}
			
		return ' checked="checked"';
	}
	

	/**
	 * Required
	 *
	 * @param	string
	 * @return	bool
	 */
	public function required($str) {
		if ( ! is_array($str)) {
			return (trim($str) == '') ? FALSE : TRUE;
		} else {
			return ( ! empty($str));
		}
	}
	

	/**
	 * Match one field to another
	 *
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
	public function matches($str, $field) {
		if ( ! isset($this->_post[$field])) {
			return FALSE;				
		}
		
		$field = $this->_post[$field];

		return ($str !== $field) ? FALSE : TRUE;
	}
	

	/**
	 * Minimum Length
	 *
	 * @param	string
	 * @param	value
	 * @return	bool
	 */	
	public function min_length($str, $val) {
		if (preg_match("/[^0-9]/", $val)) {
			return FALSE;
		}

		if (function_exists('mb_strlen')) {
			return (mb_strlen($str) < $val) ? FALSE : TRUE;		
		}
	
		return (strlen(utf8_decode($str)) < $val) ? FALSE : TRUE;
	}
	

	/**
	 * Max Length
	 *
	 * @param	string
	 * @param	value
	 * @return	bool
	 */	
	public function max_length($str, $val) {
		if (preg_match("/[^0-9]/", $val)) {
			return FALSE;
		}

		if (function_exists('mb_strlen')) {
			return (mb_strlen($str) > $val) ? FALSE : TRUE;		
		}
	
		return (strlen(utf8_decode($str)) > $val) ? FALSE : TRUE;
	}
	

	/**
	 * Exact Length
	 *
	 * @param	string
	 * @param	value
	 * @return	bool
	 */	
	public function exact_length($str, $val) {
		if (preg_match("/[^0-9]/", $val)) {
			return FALSE;
		}

		if (function_exists('mb_strlen')) {
			return (mb_strlen($str) != $val) ? FALSE : TRUE;		
		}
	
		return (strlen(utf8_decode($str)) != $val) ? FALSE : TRUE;
	}
	
	/**
	 * Form Token check
	 *
	 * @param	string
	 * @param	value
	 * @return	bool
	 */	
	public function form_token($str, $val) {
		return ($str !== $val) ? FALSE : TRUE;
	}
	
	/**
	 * Value equals $val
	 *
	 * @param	string
	 * @param	value
	 * @return	bool
	 */	
	public function equals($str, $val) {
		return ($str !== $val) ? FALSE : TRUE;
	}

	/**
	 * Valid Email
	 *
	 * The local-part of the email address may use any of these ASCII characters RFC 5322 Section 3.2.3, 
	 * RFC 6531 permits Unicode beyond the ASCII range, UTF8 charcters can be used but 
	 * many of the current generation of email servers and clients won't work with that
	 * @param	string
	 * @return	bool
	 */	
	public function valid_email($str) {
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
	}


	/**
	 * Valid Emails
	 *
	 * @param	string
	 * @return	bool
	 */	
	public function valid_emails($str) {
		if (strpos($str, ',') === FALSE) {
			return $this->valid_email(trim($str));
		}
		
		foreach (explode(',', $str) as $email) {
			if (trim($email) != '' && $this->valid_email(trim($email)) === FALSE) {
				return FALSE;
			}
		}
		
		return TRUE;
	}

	
	/**
	 * Alpha
	 *
	 * @param	string
	 * @return	bool
	 */		
	public function alpha($str) {
		return ( ! preg_match("/^([a-z])+$/i", $str)) ? FALSE : TRUE;
	}


	/**
	 * Alpha-numeric
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */	
	public function alpha_numeric($str) {
		return ( ! preg_match("/^([a-z0-9])+$/i", $str)) ? FALSE : TRUE;
	}
	

	/**
	 * Alpha-numeric with underscores and dashes
	 *
	 * @param	string
	 * @return	bool
	 */	
	public function alpha_dash($str) {
		return ( ! preg_match("/^([-a-z0-9_-])+$/i", $str)) ? FALSE : TRUE;
	}
	

	/**
	 * Numeric
	 *
	 * @param	string
	 * @return	bool
	 */	
	public function numeric($str) {
		return (bool)preg_match( '/^[\-+]?[0-9]*\.?[0-9]+$/', $str);
	}


    /**
     * Finds whether a variable is a number or a numeric string
     *
     * @param    string
     * @return    bool
     */
    public function is_numeric($str) {
        return ( ! is_numeric($str)) ? FALSE : TRUE;
    } 


	/**
	 * Integer
	 *
	 * @param	string
	 * @return	bool
	 */	
	public function is_integer($str) {
		return (bool)preg_match( '/^[\-+]?[0-9]+$/', $str);
	}

	/**
	 * Decimal number
	 *
	 * @param	string
	 * @return	bool
	 */
	public function decimal($str) {
		return (bool) preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $str);
	}
	
	/**
	 * Greather than
	 *
	 * @param	string
	 * @return	bool
	 */
	public function greater_than($str, $min) {
		if ( ! is_numeric($str)) {
			return FALSE;
		}
		return $str > $min;
	}


	/**
	 * Less than
	 *
	 * @param	string
	 * @return	bool
	 */
	public function less_than($str, $max) {
		if ( ! is_numeric($str)) {
			return FALSE;
		}
		return $str < $max;
	}
	
    /**
     * Is a Natural number  (0,1,2,3, etc.)
     *
     * @param	string
     * @return	bool
     */
    public function is_natural($str) {   
   		return (bool)preg_match( '/^[0-9]+$/', $str);
    }


    /**
     * Is a Natural number, but not a zero  (1,2,3, etc.)
     *
     * @param	string
     * @return	bool
     */
	public function is_natural_no_zero($str) {
    	if ( ! preg_match( '/^[0-9]+$/', $str)) {
    		return FALSE;
    	}
    	
    	if ($str == 0) {
    		return FALSE;
    	}
   		return TRUE;
    }
	

	/**
	 * Valid Base64
	 *
	 * Tests a string for characters outside of the Base64 alphabet
	 * as defined by RFC 2045 http://www.faqs.org/rfcs/rfc2045
	 *
	 * @param	string
	 * @return	bool
	 */
	public function valid_base64($str) {
		return (bool) ! preg_match('/[^a-zA-Z0-9\/\+=]/', $str);
	}
	

	/**
	 * Prep data for form
	 *
	 * This function allows HTML to be safely shown in a form.
	 * Special characters are converted.
	 *
	 * @param	string
	 * @return	string
	 */
	public function prep_for_form($data = '') {
		if (is_array($data)) {
			foreach ($data as $key => $val) {
				$data[$key] = $this->prep_for_form($val);
			}
			return $data;
		}
		
		if ($this->_safe_form_data == FALSE || $data === '') {
			return $data;
		}

		return str_replace(array("'", '"', '<', '>'), array("&#39;", "&quot;", '&lt;', '&gt;'), stripslashes($data));
	}

	
	/**
	 * Convert PHP tags to entities
	 *
	 * @param	string
	 * @return	string
	 */	
	public function encode_php_tags($str) {
		return str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
	}

}

/* End of file: ./system/libraries/form_validation/form_validation_library.php */