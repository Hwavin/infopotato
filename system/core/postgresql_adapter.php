<?php
/**
 * PostgreSQL adapter
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class PostgreSQL_Adapter extends DB_Adapter {
	/**
	 * Database host
	 *
	 * @var  string  
	 */
	public $dbhost = '';
	
	/**
	 * Database username
	 *
	 * @var  string  
	 */
	public $dbuser = '';
	
	/**
	 * Database user password
	 *
	 * @var  string  
	 */
	public $dbpass = '';
	
	/**
	 * Database to be used
	 *
	 * @var  string  
	 */
	public $dbname = '';
	
	/**
	 * Database table columns charset
	 *
	 * @var  string  
	 */
	public $charset;

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
		}
		
		// If there is no existing database connection then try to connect
		if ( ! $this->dbh) {
			$this->connect($this->dbuser, $this->dbpass, $this->dbname, $this->dbhost);
		}
	}
	
	/**
	 * Try to connect to PostgreSQL database server
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
		} elseif ( ! $this->dbh = pg_connect("host=$dbhost user=$dbuser password=$dbpass dbname=$dbname", TRUE)) {
			Global_Functions::show_sys_error('An Error Was Encountered', 'Error establishing PostgreSQL database connection. Correct user/password? Correct hostname? Correct database name? Database server running?', 'sys_error');
		} else {
			// Specify the client encoding per connection
			$collation_query = "SET NAMES '{$this->charset}'";
			$this->query($collation_query);

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
					$v = pg_escape_string($this->dbh, $v); 
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

		// Keep track of the last query for debug.
		$this->last_query = $query;

		// Use core file cache function
		if ($cache = $this->get_cache($query)) {
			return $cache;
		}
		// Perform the query via std mysql_query function.
		$result = pg_query($this->dbh, $query);

		// If there is an error then take note of it.
		if ($err_msg = pg_last_error($this->dbh)) {
			$is_insert = TRUE;
			Global_Functions::show_sys_error('An Error Was Encountered', $err_msg, 'sys_error');		
			return FALSE;
		}

		// Query was an insert, delete, update, replace
		$is_insert = FALSE;
		if (preg_match('/^(insert|delete|update|replace)\s+/i', $query)) {
			$this->rows_affected = pg_affected_rows($result);

			// Take note of the last_insert_id
			if (preg_match('/^(insert|replace)\s+/i', $query)) {
				$this->last_insert_id = pg_last_oid($result);
			}
			// Return number fo rows affected
			$return_val = $this->rows_affected;
		} else {
			// Store Query Results
			$num_rows = 0;
			while ($row = pg_fetch_object($result)) {
				// Store relults as an objects within main array
				$this->last_result[$num_rows] = $row;
				$num_rows++;
			}

			pg_free_result($result);

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
		return 'now()';
	}

	/**
	 * Begin Transaction using standard sql
	 *
	 * @access	public
	 * @return	bool
	 */
	public function trans_begin() {
		$this->query('begin');
	}
	
	/**
	 * Commit Transaction using standard sql
	 *
	 * @access	public
	 * @return	bool
	 */
	public function trans_commit() {
		$this->query('commit');
	}
	
	/**
	 * Rollback Transaction using standard sql
	 *
	 * @access	public
	 * @return	bool
	 */
	public function trans_rollback() {
		$this->query('rollback');
	}
	
}

// End of file: ./system/core/postgresql_adapter.php