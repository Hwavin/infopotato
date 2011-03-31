<?php
/**
 * MySQL adapter
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

class MySQL_Adapter extends DB_Adapter{
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
	 * @var  string  Database table columns charset
	 */
	public $charset;

	/**
	 * @var  string  Database table columns collate
	 */
	public $collate;

	/**
	 * Constructor
	 * 
	 * Allow the user to perform a connect at the same time as initialising the this class
	 */
	public function __construct($config = array()) {
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
			$this->connect($this->dbuser, $this->dbpass, $this->dbname, $this->dbhost);
		}
	}
	
	/**
	 * Try to connect to MySQL database server
	 *
	 * @access	public
	 */
	public function connect($dbuser = '', $dbpass = '', $dbname = '', $dbhost = 'localhost') {
		$return_val = FALSE;

		// Only need to check $dbuser, because somethimes $dbpass = '' is permitted
		if ($dbuser === '') {
			Global_Functions::show_sys_error('An Error Was Encountered', 'Require username and password to connect to a database server', 'sys_error');		
		} elseif ($dbname === '') {
			Global_Functions::show_sys_error('An Error Was Encountered', 'Require database name to select a database', 'sys_error');		
		} elseif ( ! $this->dbh = mysql_connect($dbhost, $dbuser, $dbpass, TRUE)) {
			Global_Functions::show_sys_error('An Error Was Encountered', 'Error establishing MySQL database connection. Correct user/password? Correct hostname? Database server running?', 'sys_error');		
		} else {
			if (function_exists('mysql_set_charset')) { 
				// Set charset (mysql_set_charset(), PHP 5 >= 5.2.3)
				mysql_set_charset($this->charset, $this->dbh);
			} else {
				// Specify the client encoding per connection
				$collation_query = "SET NAMES '{$this->charset}'";
				if ( ! empty($this->collate)) {
					$collation_query .= " COLLATE '{$this->collate}'";
				}
				$this->query($collation_query);
			}
			
			if ( ! mysql_select_db($dbname, $this->dbh)) {
				// Try to get error supplied by mysql if not use our own
				if ( ! $err_msg = mysql_error($this->dbh)) {
					$err_msg = 'Unexpected error while trying to select database';
				}
				Global_Functions::show_sys_error('An Error Was Encountered', $err_msg, 'sys_error');		
			}
			
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
		if ($err_msg = mysql_error($this->dbh)) {
			$is_insert = TRUE;
			Global_Functions::show_sys_error('An Error Was Encountered', $err_msg, 'sys_error');		
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
		} else {
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
	 * Returns the current date and time, e.g., 2006-04-12 13:47:46
	 */
	public function now() {
		return 'NOW()';
	}

}

// End of file: ./system/core/mysql_adapter.php