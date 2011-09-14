<?php
/**
 * Base Database Access Object
 * Members must be public in order to be called in data object
 * The following databases are supported:
 *
 *  - [http://mysql.com MySQL]
 *  - [http://postgresql.org PostgreSQL]
 *  - [http://sqlite.org SQLite]
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * Original code from {@link http://php.justinvincent.com Justin Vincent (justin@visunet.ie)}
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
 
define('OBJECT', 'OBJECT');
define('ARRAY_A', 'ARRAY_A');
define('ARRAY_N', 'ARRAY_N');

class Base_DAO {
	/**
	 * Saved result of the last query made
	 *
	 * @var  string  
	 */
	public $last_query;

	/**
	 * last query result
	 *
	 * @var  array  
	 */
	public $last_result = array();
	
	/**
	 * Target dir for cache files
	 *
	 * @var  string 
	 */
	public $cache_dir = '';
	
	/**
	 * Begin to cache database queries
	 *
	 * @var  boolean 
	 */
	public $cache_queries = FALSE;
	
	/**
	 * Begin to cache database queries
	 *
	 * @var  boolean 
	 */
	public $cache_inserts = FALSE;
	
	/**
	 * Whether to use disk cache
	 *
	 * @var  boolean  
	 */
	public $use_disk_cache = FALSE;
	
	/**
	 * Lifespan of chached files in seconds
	 *
	 * @var  integer  
	 */
	public $cache_timeout = 3600;
	
	/**
	 * Database connection handler
	 *
	 * @var  resource  
	 */
	public $dbh;

	/**
	 * Gets the ID generated by the previous INSERT query
	 *
	 * @var  integer  
	 */
	public $last_insert_id;

	/**
	 * Constructor
	 * 
	 * Closing isn't usually necessary, as non-persistent open links are automatically closed at the end of the script's execution.
	 * Allow the user to perform a connect at the same time as initialising the this class
	 */
	public function __construct(array $config = NULL) {}
	
	/** 
	 * Overridden by specific DB class
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
	public function prepare($query, array $params = NULL) {} 
	
	/**
	 * Perform MySQL query and try to detirmin result value
	 * Overridden by specific DB class
	 *
	 * @return int|FALSE Number of rows affected/selected or false on error
	 */
	public function query($query) {}

	/**
	 * Returns the current date and time, e.g., 2006-04-12 13:47:46
	 * Overridden by specific DB class
	 */
	public function now() {}

	/**
	 * Kill cached query results.
	 */
	public function flush() {
		$this->last_result = NULL;
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
		// If there is a query then perform it if not then use cached results.
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
		// If there is a query then perform it if not then use cached results.
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
			halt('A System Error Was Encountered', " \$db->get_row(string query, output type, int offset) -- Output type must be one of: OBJECT, ARRAY_A, ARRAY_N", 'sys_error');
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
		// If there is a query then perform it if not then use cached results.
		if ($query) {
			$this->query($query);
		} else {
			return NULL;
		}
		// Send back array of objects. Each row is an object
		if ($output == OBJECT) {
			return $this->last_result;
		} elseif ($output == ARRAY_A || $output == ARRAY_N) {
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
	 * store_cache
	 */
	public function store_cache($query, $is_insert) {
		// The would be cache file for this query
		$cache_file = $this->cache_dir.'/'.md5($query);

		// Disk caching of queries
		if ($this->use_disk_cache && ($this->cache_queries && ! $is_insert) || ($this->cache_inserts && $is_insert)) {
			if ( ! is_dir($this->cache_dir)) {
				halt('A System Error Was Encountered', "Could not open cache dir: {$this->cache_dir}", 'sys_error');
			} else {
				// Cache all result values
				$result_cache = array(
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
				$this->last_result = $result_cache['last_result'];
				$this->num_rows = $result_cache['num_rows'];
				$this->from_disk_cache = TRUE;
				return $result_cache['return_value'];
			}
		}

	}

	/**
	 * Begin Transaction
	 *
	 * @return	bool
	 */
	public function trans_begin() {}
	
	/**
	 * Commit Transaction
	 *
	 * @return	bool
	 */
	public function trans_commit() {}
	
	/**
	 * Rollback Transaction
	 *
	 * @return	bool
	 */
	public function trans_rollback() {}
	
	/**
	 * Dumps the contents of any input variable to screen in a nicely
	 * formatted and easy to understand way - any type: Object, Var or Array
	 */
	public function vardump($mixed = '') {
		// Start outup buffering
		ob_start();
		require_once SYS_CORE_DIR.'sys_templates'.DS.'data_vardump.php';
		// Stop output buffering and capture HTML
		$html = ob_get_contents();
		ob_end_clean();

		echo $html;
	}

}

// End of file: ./system/core/base_dao.php