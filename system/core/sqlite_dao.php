<?php
/**
 * SQLite Data Access Object
 * The username/password is not supported by the sqlite/sqlite3 package
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

class SQLite_DAO extends Base_DAO {
	/**
	 * Database connection handler
	 *
	 * @var  resource  
	 */
	public $dbh;
	
	/**
	 * Constructor
	 * 
	 * Allow the user to perform a connect at the same time as initialising the class
	 */
	public function __construct(array $config = NULL) {
		// If there is no existing database connection then try to connect
		if ( ! $this->dbh) {
			// No username and password required
			if ($config['path'] === '') {
				halt('An Error Was Encountered', 'Require $dbpath to open an SQLite database', 'sys_error');		
			} 
			if ( ! $this->dbh = sqlite_open($config['path'])) {
				halt('An Error Was Encountered', 'Could not connect: '.sqlite_error_string(sqlite_last_error($this->dbh)), 'sys_error');		
			}
		}
	}
	

	/** 
	 * USAGE: prepare( string $query [, array $params ] ) 
	 * The following directives can be used in the query format string:
	 * %d (decimal number)
	 * %s (string)
	 *
	 * Both %d and %s are to be left unquoted in the query string and they need an argument passed for them.
	 */ 
	public function prepare($query, array $params = NULL) { 
		if (count($params) > 0) { 			
			foreach ($params as $k => $v) { 
				// Only quote and escape string
				if (is_string($v)) {
				    if (isset($this->dbh)) {
					    $params[$k] = "'".sqlite_escape_string($v)."'"; 
				    } else {
					    // addslashes() should NOT be used to quote your strings for SQLite queries; 
					    // it will lead to strange results when retrieving your data.
					    //$params[$k] = addslashes($v);
				    }
				}
			} 	
			// vsprintf - replacing all %d and %s to parameters 
			$query = vsprintf($query, $params);    
		} 
		return $query; 
	} 
	
	/**
	 * Perform a unique query (multiple queries are not supported) and try to determine result value
	 *
	 * @return int Number of rows affected/selected
	 */
	public function exec_query($query) {
		// For reg exp
		$query = str_replace("/[\n\r]/", '', trim($query)); 

		// Initialize return flag
		$return_val = 0;

		// Reset stored query result
		$this->query_result = array();

		// Executes a query against a given database and returns a result handle (resource)
		$result = sqlite_query($this->dbh, $query);

		// If there is an error then take note of it.
		if (sqlite_last_error($this->dbh)) {
			$err_msg = sqlite_error_string(sqlite_last_error($this->dbh));
			halt('An Error Was Encountered', $err_msg, 'sys_error');		
		}
		
		// Query was an insert, delete, drop, update, replace
		if (preg_match("/^(insert|delete|drop|update|replace)\s+/i", $query)) {
			$rows_affected = sqlite_changes($this->dbh);
			
			// Take note of the last_insert_id
			// REPLACE works exactly like INSERT, except that if an old row in the table has the same value 
			// as a new row for a PRIMARY KEY or a UNIQUE index, the old row is deleted before the new row is inserted.
			if (preg_match("/^(insert|replace)\s+/i", $query)) {
				$this->last_insert_id = sqlite_last_insert_rowid($this->dbh);	
			}
			
			// Return number fo rows affected
			$return_val = $rows_affected;
		} else {
			// Store Query Results
			$num_rows = 0;
			while ($row = sqlite_fetch_array($result, SQLITE_ASSOC)) {
				// Store relults as an objects within main array
				$obj = (object) $row; //convert to object
				$this->query_result[$num_rows] = $obj;
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
	 * Begin Transaction using standard sql
	 *
	 * @return	bool
	 */
	public function trans_begin() {
		sqlite_query($this->dbh, 'BEGIN TRANSACTION');
	}
	
	/**
	 * Commit Transaction using standard sql
	 *
	 * @return	bool
	 */
	public function trans_commit() {
		sqlite_query($this->dbh, 'COMMIT');
	}
	
	/**
	 * Rollback Transaction using standard sql
	 *
	 * @return	bool
	 */
	public function trans_rollback() {
		sqlite_query($this->dbh, 'ROLLBACK');
	}

}

// End of file: ./system/core/sqlite_dao.php