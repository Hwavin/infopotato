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
		if ( ! is_resource($this->dbh)) {
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
	 * Escapes special characters in a string for use in an SQL statement, 
	 * taking into account the current charset of the connection
	 */ 
	public function escape($string) { 
		// Only escape string
		// is_string() will take '' as string
		if (is_string($string)) {
			$string = sqlite_escape_string($string); 
		}
		return $string; 
	}

	/** 
	 * USAGE: prepare( string $query [, array $params ] ) 
	 * The following directives can be used in the query format string:
	 * %d (decimal integer)
	 * %s (string)
	 * %f (float)
	 * 
	 * @return string the prepared SQL query
	 */ 
	public function prepare($query, array $params = NULL) { 
		// All variables in $params must be set before being passed to this function
		// if any variables are not set (will be NULL) will cause error in SQL
		if (count($params) > 0) { 			
			$pos_list = array();
			$pos_adj = 0;

			$bind_types = array('%s', '%d', '%f');

			foreach ($bind_types as $type) {
				$last_pos = 0;
				while (($pos = strpos($query, $type, $last_pos)) !== FALSE) {
					$last_pos = $pos + 1;
					if (isset($pos_list[$pos]) && strlen($pos_list[$pos]) > strlen($type)) {
						continue;
					}
					$pos_list[$pos] = $type;
				}
			}
			
			// By default $pos_list is ordered by the position of %s, %d, %f in the query
			// We need to reorder $pos_list so that it will be ordered by the key (position) from small to big
			ksort($pos_list);

			foreach ($pos_list as $pos => $type) {
				$type_length = strlen($type);

				$arg = array_shift($params);

				if ($type === '%s') {
					// Only single quote and escape string
				    // is_string() will take '' as string
					if (is_string($arg)) {
						$arg = "'".$this->escape($arg)."'"; 
					} else {
						halt('An Error Was Encountered', 'The binding value for %s must be a string', 'sys_error');
					}
				} elseif ($type === '%d') {
					if (is_int($arg)) {
						// Format the variable into a valid integer
						intval($arg);
					} else {
						halt('An Error Was Encountered', 'The binding value for %d must be an integer', 'sys_error');
					}
				} elseif ($type === '%f') {
					if (is_float($arg)) {
						// Format the variable into a valid float
						floatval($arg);
					} else {
						halt('An Error Was Encountered', 'The binding value for %f must be a float', 'sys_error');
					}
				} else {
					halt('An Error Was Encountered', "Unknown binding marker in: $query", 'sys_error');
				}

				$query = substr_replace($query, $arg, $pos + $pos_adj, $type_length);
				// Adjust the start offset for next replace
				$pos_adj += strlen($arg) - ($type_length);
			}
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
		
		// Query was an insert, delete, drop, update, replace, alter
		if (preg_match("/^(insert|delete|drop|update|replace|alter)\s+/i", $query)) {
			$rows_affected = sqlite_changes($this->dbh);
			
			// Take note of the last_insert_id
			// REPLACE works exactly like INSERT, except that if an old row in the table has the same value 
			// as a new row for a PRIMARY KEY or a UNIQUE index, the old row is deleted before the new row is inserted.
			if (preg_match("/^(insert|replace)\s+/i", $query)) {
				$this->last_insert_id = sqlite_last_insert_rowid($this->dbh);	
			}
			
			// Return number fo rows affected
			$return_val = $rows_affected;
		} elseif (preg_match("/^(select|describe|desc|show|explain)\s+/i", $query)) {
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
		} elseif (preg_match("/^create\s+/i", $query)) {
		    // Table creation returns TRUE on success, or FALSE on error.
			$return_val = $result;
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