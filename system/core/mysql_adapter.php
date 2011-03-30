<?php
/**
 * MySQL adapter
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
 
define('OBJECT', 'OBJECT');
define('ARRAY_A', 'ARRAY_A');
define('ARRAY_N', 'ARRAY_N');

/**
 * Database Access Abstraction Object
 *
 * Original code from {@link http://php.justinvincent.com Justin Vincent (justin@visunet.ie)}
 */
class MySQL_Adapter {
	/**
	 * @var  bool  Whether to show SQL/DB errors defined in error_messages
	 */
	public $show_errors = TRUE;
	
	/**
	 * @var  integer  Amount of queries made
	 */
	public $num_queries = 0;
	
	/**
	 * @var  string  Saved result of the last query made
	 */
	public $last_query;
	
	/**
	 * @var  integer  Gets the ID generated for an AUTO_INCREMENT column by the previous INSERT query
	 */
	public $last_insert_id;
	
	/**
	 * @var  string  The last error during query.
	 */
	public $last_error;
	
	/**
	 * @var  string  Saved info on the table column
	 */
	public $col_info;
	
	/**
	 * @var  array  errors captured
	 */
	public $captured_errors = array();
	
	/**
	 * @var  string Target dir for cache files
	 */
	public $cache_dir = '';
	
	/**
	 * @var  boolean Begin to cache database queries
	 */
	public $cache_queries = FALSE;
	
	/**
	 * @var  boolean Begin to cache database queries
	 */
	public $cache_inserts = FALSE;
	
	/**
	 * @var  boolean  Whether to use disk cache
	 */
	public $use_disk_cache = FALSE;
	
	/**
	 * @var  integer  Lifespan og chached files
	 */
	public $cache_timeout = 3600; // Seconds
	
	/**
	 * @var  array  Database error message
	 */
	public $error_messages = array();
	
	/**
	 * @var  string  Database table columns charset
	 */
	public $charset;

	/**
	 * @var  string  Database table columns collate
	 */
	public $collate;
	
	/**
	 * @var  string  Database host
	 */
	public $dbhost = '';
	
	/**
	 * @var  string  Database username
	 */
	public $dbuser = '';
	
	/**
	 * @var  string  Database user password
	 */
	public $dbpass = '';
	
	/**
	 * @var  string  Database to be used
	 */
	public $dbname = '';
	
	/**
	 * @var  resource  Database connection handler
	 */
	public $dbh;
	
	/**
	 * @var  string  Log how the function was called for debugging
	 */
	public $func_call = '';


	/**
	 * Constructor
	 * 
	 * Allow the user to perform a connect at the same time as initialising the this class
	 */
	public function __construct($config = array()) {
		$this->error_messages = array(
			1 => 'Require username and password to connect to a database server',
			2 => 'Error establishing MySQL database connection. Correct user/password? Correct hostname? Database server running?',
			3 => 'Require database name to select a database',
			4 => 'MySQL database connection is not active',
			5 => 'Unexpected error while trying to select database'
		);
			
		if (is_array($config) && count($config) > 0) {
			$this->dbuser = $config['user'];
			$this->dbpass = $config['pass'];
			$this->dbname = $config['name'];
			$this->dbhost = $config['host'];
			$this->charset = $config['charset'];
			$this->collate = $config['collate'];
		}
		
		// If there is no existing database connection then try to connect
		if ( ! $this->dbh) {
			$this->connect($this->dbuser, $this->dbpass, $this->dbhost);
			$this->select($this->dbname);
		}
	}
	
	/**
	 * Try to connect to MySQL database server
	 *
	 * @access	public
	 */
	public function connect($dbuser = '', $dbpass = '', $dbhost = 'localhost') {
		$return_val = FALSE;

		// Only need to check $dbuser, because somethimes $dbpass = '' is permitted
		if ( ! $dbuser) {
			$this->register_error($this->error_messages[1].' in '.__FILE__.' on line '.__LINE__);
			$this->show_errors ? trigger_error($this->error_messages[1], E_USER_WARNING) : NULL;
		} elseif ( ! $this->dbh = mysql_connect($dbhost, $dbuser, $dbpass, TRUE)) {
			$this->register_error($this->error_messages[2].' in '.__FILE__.' on line '.__LINE__);
			$this->show_errors ? trigger_error($this->error_messages[2], E_USER_WARNING) : NULL;
		} else {
			if (function_exists('mysql_set_charset')) { 
				// Set charset
				mysql_set_charset($this->charset, $this->dbh);
			} else {
				$collation_query = "SET NAMES '{$this->charset}'";
				if ( ! empty($this->collate)) {
					$collation_query .= " COLLATE '{$this->collate}'";
				}
				$this->query($collation_query);
			}
			$return_val = TRUE;
		}
		
		return $return_val;
	}

	/**
	 * Try to select a MySQL database
	 *
	 * @param string $dbname
	 */
	public function select($dbname = '') {
		$return_val = FALSE;
		
		// Must have a database name
		if ( ! $dbname) {
			$this->register_error($this->error_messages[3].' in '.__FILE__.' on line '.__LINE__);
			$this->show_errors ? trigger_error($this->error_messages[3], E_USER_WARNING) : NULL;
		}
		// Must have an active database connection
		elseif ( ! $this->dbh) {
			$this->register_error($this->error_messages[4].' in '.__FILE__.' on line '.__LINE__);
			$this->show_errors ? trigger_error($this->error_messages[4], E_USER_WARNING) : NULL;
		}
		// Try to connect to the database
		elseif ( ! mysql_select_db($dbname, $this->dbh)) {
			// Try to get error supplied by mysql if not use our own
			if ( ! $str = mysql_error($this->dbh)) {
				$str = $this->error_messages[5];
			}
			$this->register_error($str.' in '.__FILE__.' on line '.__LINE__);
			$this->show_errors ? trigger_error($str, E_USER_WARNING) : NULL;
		} else {
			$this->dbname = $dbname;
			$return_val = TRUE;
		}
		return $return_val;
	}
	
	/** 
	 * USAGE: prepare( string $query [, array $params ] ) 
	 * $query - SQL query WITHOUT any user-entered parameters. Replace parameters with "?" 
	 *     e.g. $query = "SELECT date from history WHERE login = ?" 
	 * $params - array of parameters 
	 * 
	 * Example: 
	 *    prepare( "SELECT secret FROM db WHERE login = ?", array($login) );  
	 *    prepare( "SELECT secret FROM db WHERE login = ? AND password = ?", array($login, $password) );  
	 * That will result safe query to MySQL with escaped $login and $password. 
	 */ 
	public function prepare($query, $params = array()) { 
		if (count($params) > 0) { 			
			foreach ($params as $v) { 
				if ($this->dbh) {
					$v = mysql_real_escape_string($v, $this->dbh); 
				} else {
					$v = addslashes($v);
				}
			} 	
			// in case someone mistakenly already singlequoted it
			$query = str_replace("'?'", '?', $query); 
			// in case someone mistakenly already doublequoted it
			$query = str_replace('"?"', '?', $query); 
			// quote the strings and replacing ? -> %s
			$query = str_replace('?', "'%s'", $query); 
			// vsprintf - replacing all %s to parameters 
			$query = vsprintf($query, $params);    
		} 
		return $query; 
	} 
	
	/**
	 * Perform MySQL query and try to detirmin result value
	 *
	 * @return int|FALSE Number of rows affected/selected or false on error
	 */
	public function query($query) {
		// Initialise return
		$return_val = 0;

		// Flush cached values.
		$this->flush();

		// For reg expressions
		$query = trim($query);

		// Log how the function was called
		$this->func_call = "\$db->query(\"$query\")";

		// Keep track of the last query for debug.
		$this->last_query = $query;

		// Count how many queries there have been
		$this->num_queries++;

		// Use core file cache function
		if ($cache = $this->get_cache($query)) {
			return $cache;
		}
		// Perform the query via std mysql_query function.
		$result = mysql_query($query, $this->dbh);

		// If there is an error then take note of it.
		if ($str = mysql_error($this->dbh)) {
			$is_insert = TRUE;
			$this->register_error($str);
			$this->show_errors ? trigger_error($str, E_USER_WARNING) : NULL;
			return FALSE;
		}

		// Query was an insert, delete, update, replace
		$is_insert = FALSE;
		if (preg_match('/^(insert|delete|update|replace)\s+/i', $query)) {
			$this->rows_affected = mysql_affected_rows();

			// Take note of the last_insert_id
			if (preg_match('/^(insert|replace)\s+/i', $query)) {
				$this->last_insert_id = mysql_insert_id($this->dbh);
			}
			// Return number fo rows affected
			$return_val = $this->rows_affected;
		} elseif (preg_match('/^(select|SHOW|DESCRIBE|EXPLAIN)\s+/i', $query)) {
			// Take note of column info
			$i = 0;
			while ($i < mysql_num_fields($result)) {
				$this->col_info[$i] = mysql_fetch_field($result);
				$i++;
			}

			// Store Query Results
			$num_rows = 0;
			while ($row = mysql_fetch_object($result)) {
				// Store relults as an objects within main array
				$this->last_result[$num_rows] = $row;
				$num_rows++;
			}

			mysql_free_result($result);

			// Log number of rows the query returned
			$this->num_rows = $num_rows;

			// Return number of rows selected
			$return_val = $this->num_rows;
		}

		// disk caching of queries
		$this->store_cache($query, $is_insert);

		return $return_val;
	}

	/**
	 * Return MySQL specific system date syntax i.e. Oracle: SYSDATE Mysql: NOW()
	 */
	public function sysdate() {
		return 'NOW()';
	}

	/**
	 * Print SQL/DB error - over-ridden by specific DB class
	 */
	public function register_error($err_str) {
		// Keep track of last error
		$this->last_error = $err_str;

		// Capture all errors to an error array no matter what happens
		$this->captured_errors[] = array(
			'error_str' => $err_str,
			'query'     => $this->last_query
		);
	}

	/**
	 * Turn error handling on or off..
	 */
	public function show_errors() {
		$this->show_errors = TRUE;
	}

	public function hide_errors() {
		$this->show_errors = FALSE;
	}

	/**
	 * Kill cached query results.
	 */
	public function flush() {
		// Get rid of these
		$this->last_result = NULL;
		$this->col_info = NULL;
		$this->last_query = NULL;
		$this->from_disk_cache = FALSE;
	}

	/**
	 * Gets one single variable from the database or previously cached results.
	 *
	 * This function is very useful for evaluating query results within logic statements such as if or switch. 
	 * If the query generates more than one row the first row will always be used by default.
	 * If the query generates more than one column the leftmost column will always be used by default.
	 * Even so, the full results set will be available within the array $db->last_results should you wish to use them.
	 *
	 * @param string|null $query SQL query. If null, use the result from the previous query.
	 * @param int $x (optional) Column of value to return.  Indexed from 0.
	 * @param int $y (optional) Row of value to return.  Indexed from 0.
	 * @return string Database query result
	 */
	public function get_var($query = NULL, $x = 0, $y = 0) {
		// Log how the function was called
		$this->func_call = "\$db->get_var(\"$query\", $x, $y)";

		// If there is a query then perform it if not then use cached results..
		if ($query) {
			$this->query($query);
		}
		// Extract var out of cached results based x,y vals
		if ($this->last_result[$y]) {
			$values = array_values(get_object_vars($this->last_result[$y]));
		}
		// If there is a value return it else return NULL
		return (isset($values[$x]) && $values[$x] !== '') ? $values[$x] : NULL;
	}

	/**
	 * Gets a single row from the database or cached results.
	 * If the query returns more than one row and no row offset is supplied the first row within the results set will be returned by default.
	 * Even so, the full results will be cached should you wish to use them with another ezSQL query.
	 *
	 *
	 * @param string|null $query SQL query.
	 * @param string $output (optional) one of ARRAY_A | ARRAY_N | OBJECT constants.  Return an associative array (column => value, ...), a numerically indexed array (0 => value, ...) or an object ( ->column = value ), respectively.
	 * @param int $y (optional) Row to return.  Indexed from 0.
	 * @return mixed Database query result in format specifed by $output
	 */
	public function get_row($query = NULL, $output = OBJECT, $y = 0) {
		// Log how the function was called
		$this->func_call = "\$db->get_row(\"$query\", $output, $y)";

		// If there is a query then perform it if not then use cached results..
		if ($query) {
			$this->query($query);
		} else {
			return NULL;
		}
		
		if ($output == OBJECT) {
			return $this->last_result[$y] ? $this->last_result[$y] : NULL;
		} elseif ($output == ARRAY_A) {
			return $this->last_result[$y] ? get_object_vars($this->last_result[$y]) : NULL;
		} elseif ($output == ARRAY_N) {
			return $this->last_result[$y] ? array_values(get_object_vars($this->last_result[$y])) : NULL;
		} else {
			$this->print_error(" \$db->get_row(string query, output type, int offset) -- Output type must be one of: OBJECT, ARRAY_A, ARRAY_N");
		}
	}

	/**
	 * Extracts one column as one dimensional array based on a column offset.
	 *
	 * If no offset is supplied the offset will defualt to column 0. I.E the first column.
	 * If a null query is supplied the previous query results are used.
	 *
	 * @param string|null $query SQL query.  If null, use the result from the previous query.
	 * @param int $x Column to return.  Indexed from 0.
	 * @return array Database query result.  Array indexed from 0 by SQL result row number.
	 */
	public function get_col($query = NULL, $x = 0) {
		// If there is a query then perform it if not then use cached results..
		if ($query) {
			$this->query($query);
		}
		$new_array = array();
		// Extract the column values
		for ($i = 0; $i < count($this->last_result); $i++) {
			$new_array[$i] = $this->get_var(NULL, $x, $i);
		}
		return $new_array;
	}


	/**
	 * Gets multiple rows of results from the database based on query and returns them as a multi dimensional array.
	 *
	 * Each element of the array contains one row of results and can be specified to be either an object, associative array or numerical array.
	 * If no results are found then the function returns false enabling you to use the function within logic statements such as if.
	 *
	 * @param string $query SQL query.
	 * @param string $output (optional) ane of ARRAY_A | ARRAY_N | OBJECT constants.  With one of the first three, return an array of rows indexed from 0 by SQL result row number.  Each row is an associative array (column => value, ...), a numerically indexed array (0 => value, ...), or an object. ( ->column = value ), respectively.  With OBJECT_K, return an associative array of row objects keyed by the value of each row's first column's value.  Duplicate keys are discarded.
	 * @return mixed Database query results
	 */
	public function get_results($query = NULL, $output = OBJECT) {
		// Log how the function was called
		$this->func_call = "\$db->get_results(\"$query\", $output)";

		// If there is a query then perform it if not then use cached results.
		if ($query) {
			$this->query($query);
		} else {
			return NULL;
		}
		// Send back array of objects. Each row is an object
		if ($output == OBJECT) {
			return $this->last_result;
		} elseif ($output == ARRAY_A OR $output == ARRAY_N) {
			if ($this->last_result) {
				$i = 0;
				foreach((array)$this->last_result as $row) {
					$new_array[$i] = get_object_vars($row);

					if ($output == ARRAY_N) {
						$new_array[$i] = array_values($new_array[$i]);
					}
					$i++;
				}
				return $new_array;
			} else {
				return NULL;
			}
		}
	}


	/**
	 * Returns meta information about one or all columns such as column name or type.
	 *
	 * If no information type is supplied then the default information type of name is used. 
	 * If no column offset is supplied then a one dimensional array is returned with the information type for 'all columns'.
	 * For access to the full meta information for all columns you can use the cached variable $db->col_info
	 * 
	 * @param string $info_type one of name, table, def, max_length, not_null, primary_key, multiple_key, unique_key, numeric, blob, type, unsigned, zerofill
	 * @param int $col_offset 0: col name. 1: which table the col's in. 2: col's max length. 3: if the col is numeric. 4: col's type
	 * @return mixed Column Results
	 */
	public function get_col_info($info_type = 'name', $col_offset = -1) {
		if ($this->col_info) {
			if ($col_offset == -1) {
				$i = 0;
				foreach ((array)$this->col_info as $col) {
					$new_array[$i] = $col->{$info_type};
					$i++;
				}
				return $new_array;
			} else {
				return $this->col_info[$col_offset]->{$info_type};
			}
		}

	}
	
	/**
	 * store_cache
	 */
	public function store_cache($query, $is_insert) {
		// The would be cache file for this query
		$cache_file = $this->cache_dir.'/'.md5($query);

		// Disk caching of queries
		if ($this->use_disk_cache && ($this->cache_queries && ! $is_insert) OR ($this->cache_inserts && $is_insert)) {
			if ( ! is_dir($this->cache_dir)) {
				$this->register_error("Could not open cache dir: $this->cache_dir");
				$this->show_errors ? trigger_error("Could not open cache dir: $this->cache_dir", E_USER_WARNING) : NULL;
			} else {
				// Cache all result values
				$result_cache = array(
					'col_info' => $this->col_info,
					'last_result' => $this->last_result,
					'num_rows' => $this->num_rows,
					'return_value' => $this->num_rows,
				);
				// Result datd is appended to the cache file
				error_log(serialize($result_cache), 3, $cache_file);
			}
		}

	}

	/**
	 * get_cache
	 */
	public function get_cache($query) {
		// The would be cache file for this query
		$cache_file = $this->cache_dir.'/'.md5($query);

		// Try to get previously cached version
		if ($this->use_disk_cache && file_exists($cache_file)) {
			// Only use this cache file if less than 'cache_timeout' (seconds)
			if ((time() - filemtime($cache_file)) > ($this->cache_timeout)) {
				unlink($cache_file);
			} else {
				$result_cache = unserialize(file_get_contents($cache_file));

				$this->col_info = $result_cache['col_info'];
				$this->last_result = $result_cache['last_result'];
				$this->num_rows = $result_cache['num_rows'];

				$this->from_disk_cache = TRUE;

				return $result_cache['return_value'];
			}
		}

	}

	/**
	 * Dumps the contents of any input variable to screen in a nicely
	 * formatted and easy to understand way - any type: Object, Var or Array
	 */
	public function vardump($mixed = '') {
		// Start outup buffering
		ob_start();
		include(SYS_DIR.'core'.DS.'sys_templates'.DS.'data_vardump.php');
		// Stop output buffering and capture HTML
		$html = ob_get_contents();
		ob_end_clean();

		echo $html;
	}

}

// End of file: ./system/core/mysql_adapter.php