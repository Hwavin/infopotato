<?php
/**
 * SQLite adapter
 * The username/password is not supported by the sqlite/sqlite3 package
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

class SQLite_Adapter extends DB_adapter {
	/**
	 * Database file path
	 *
	 * @var  string  
	 */
	public $dbpath = '';

	/**
	 * Constructor
	 * 
	 * Allow the user to perform a connect at the same time as initialising the this class
	 */
	public function __construct($config = array()) {
		if (is_array($config) && isset($config['path'])) {
			$this->dbpath = $config['path'];
		}
		
		// If there is no existing database connection then try to connect
		if ( ! $this->dbh) {
			$this->connect($this->dbpath);
		}
	}
	
	/**
	 * Try to open database file 
	 */
	public function connect($dbpath = '') {
		$return_val = FALSE;
		
		// No username and a password required
		if ($dbpath === '') {
			Global_Functions::show_sys_error('An Error Was Encountered', 'Require $dbpath to open an SQLite database', 'sys_error');		
		} else if ( ! $this->dbh = sqlite_open($dbpath)) {
			Global_Functions::show_sys_error('An Error Was Encountered', 'Can not find SQLite database file', 'sys_error');		
		} else {
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
	 * That will result safe query to SQLite with escaped $login and $password. 
	 */ 
	public function prepare($query, $params = array()) { 
		if (count($params) > 0) { 			
			foreach ($params as $v) { 
				if ($this->dbh) {
					$v = sqlite_escape_string($v); 
				} else {
					// addslashes() should NOT be used to quote your strings for SQLite queries; 
					// it will lead to strange results when retrieving your data.
					//$v = addslashes($v);
				}
			} 	
			// In case someone mistakenly already singlequoted it
			$query = str_replace("'?'", '?', $query); 
			// In case someone mistakenly already doublequoted it
			$query = str_replace('"?"', '?', $query); 
			// Quote the strings and replacing ? -> %s
			$query = str_replace('?', "'%s'", $query); 
			// vsprintf - replacing all %s to parameters 
			$query = vsprintf($query, $params);    
		} 
		return $query; 
	} 
	
	/**
	 * Perform query and try to detirmin result value
	 *
	 * @return int|FALSE Number of rows affected/selected or false on error
	 */
	public function query($query) {
		// For reg exp
		$query = str_replace("/[\n\r]/", '', trim($query)); 

		// Initialize return flag
		$return_val = 0;

		// Flush cached values..
		$this->flush();

		// Keep track of the last query for debug.
		$this->last_query = $query;

		// Executes a query against a given database and returns a result handle (resource)
		$result = sqlite_query($this->dbh, $query);

		// If there is an error then take note of it.
		if (sqlite_last_error($this->dbh)) {
			$err_msg = sqlite_error_string(sqlite_last_error($this->dbh));
			Global_Functions::show_sys_error('An Error Was Encountered', $err_msg, 'sys_error');		
			return FALSE;
		}
		
		// Query was an insert, delete, update, replace
		if (preg_match("/^(insert|delete|update|replace)\s+/i", $query)) {
			$this->rows_affected = sqlite_changes($this->dbh);
			
			// Take note of the last_insert_id
			if (preg_match("/^(insert|replace)\s+/i", $query)) {
				$this->last_insert_id = sqlite_last_insert_rowid($this->dbh);	
			}
			
			// Return number fo rows affected
			$return_val = $this->rows_affected;
		} else {
			// Store Query Results
			$num_rows = 0;
			while ($row = sqlite_fetch_array($result, SQLITE_ASSOC)) {
				// Store relults as an objects within main array
				$obj= (object) $row; //convert to object
				$this->last_result[$num_rows] = $obj;
				$num_rows++;
			}

			// Log number of rows the query returned
			$this->num_rows = $num_rows;
			
			// Return number of rows selected
			$return_val = $this->num_rows;
		}

		return $return_val;
	}

	/**
	 * Returns the current date and time, e.g., 2006-04-12 13:47:46
	 */
	public function now() {
		return 'now';
	}
	
	/**
	 * Begin Transaction using standard sql
	 *
	 * @access	public
	 * @return	bool
	 */
	public function trans_begin() {
		$this->query('BEGIN TRANSACTION');
	}
	
	/**
	 * Commit Transaction using standard sql
	 *
	 * @access	public
	 * @return	bool
	 */
	public function trans_commit() {
		$this->query('COMMIT');
	}
	
	/**
	 * Rollback Transaction using standard sql
	 *
	 * @access	public
	 * @return	bool
	 */
	public function trans_rollback() {
		$this->query('ROLLBACK');
	}

}

// End of file: ./system/core/sqlite_adapter.php