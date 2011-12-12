<?php
/**
 * MySQLi Data Access Object
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

class MySQLi_DAO extends Base_DAO {
	/**
	 * An object which represents the connection to a MySQL Server
	 *
	 * @var  object
	 */
	public $mysqli;
	
	/**
	 * Constructor
	 * 
	 * Allow the user to perform a connect at the same time as initialising the this class
	 */
	public function __construct(array $config = NULL) {
		// If there is no existing database connection then try to connect
		if ( ! is_object($this->mysqli)) {
			$this->mysqli = new mysqli($config['host'], $config['user'], $config['pass'], $config['name'], $config['port']);

			// Use this instead of $connect_error if you need to ensure
			// compatibility with PHP versions prior to 5.2.9 and 5.3.0.
			if (mysqli_connect_error()) {
				halt('An Error Was Encountered', 'Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error(), 'sys_error');		
			}

			if (method_exists($this->mysqli, 'set_charset')) { 
				// Set charset, (PHP 5 >= 5.0.5)
				$this->mysqli->set_charset($config['charset']);
			} else {
				// Specify the client encoding per connection
				$collation_query = "SET NAMES '{$config['charset']}'";
				if ( ! empty($config['collate'])) {
					$collation_query .= " COLLATE '{$config['collate']}'";
				}
				$this->exec_query($collation_query);
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
				    $params[$k] = isset($this->mysqli) ? "'".$this->mysqli->real_escape_string($v)."'" : "'".addslashes($v)."'"; 
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
		// Initialise return
		$return_val = 0;

		// Reset stored query result
		$this->query_result = array();

		// For reg expressions
		$query = trim($query);

		// Perform the query via std mysqli_query() function.
		// Returns FALSE on failure. For successful SELECT, SHOW, DESCRIBE or EXPLAIN 
		// queries mysqli_query() will return a result object. 
		// For other successful queries mysqli_query() will return TRUE.
		$result = $this->mysqli->query($query);

		// If there is an error then take note of it.
		if ($err_msg = $this->mysqli->error) {
			halt('An Error Was Encountered', $err_msg, 'sys_error');		
		}

		// Query was an insert, delete, update, replace
		if (preg_match("/^(insert|delete|update|replace)\s+/i", $query)) {
			// When using UPDATE, MySQL will not update columns where the new value is the same as the old value. 
			// This creates the possibility that $affected_rows may not actually equal the number of rows matched, 
			// only the number of rows that were literally affected by the query.
			$rows_affected = $this->mysqli->affected_rows;

			// Take note of the last_insert_id
			// REPLACE works exactly like INSERT, except that if an old row in the table has the same value 
			// as a new row for a PRIMARY KEY or a UNIQUE index, the old row is deleted before the new row is inserted.
			if (preg_match("/^(insert|replace)\s+/i", $query)) {
				$this->last_insert_id = $this->mysqli->insert_id;
			}
			// Return number fo rows affected
			$return_val = $rows_affected;
		} else {
			// Store Query Results
			$num_rows = 0;
			while ($row = $result->fetch_object()) {
				// Store relults as an objects within main array
				$this->query_result[$num_rows] = $row;
				$num_rows++;
			}

			$result->free_result();

			// Log number of rows the query returned
			$this->num_rows = $num_rows;

			// Return number of rows selected
			$return_val = $this->num_rows;
		}

		return $return_val;
	}

	/**
	 * Disable autocommit to begin transaction
	 * MySQL MyISAM tables do not support transactions and will auto-commit even if a transaction has been started
	 * 
	 * @access	public
	 * @return	bool
	 */
	public function trans_begin() {
		$this->mysqli->autocommit(FALSE);
	}
	
	/**
	 * Commit Transaction
	 *
	 * @access	public
	 * @return	bool
	 */
	public function trans_commit() {
		$this->mysqli->commit();
	}
	
	/**
	 * Rollback Transaction
	 *
	 * @access	public
	 * @return	bool
	 */
	public function trans_rollback() {
		$this->mysqli->rollback();
	}
	
}

// End of file: ./system/core/mysqli_dao.php