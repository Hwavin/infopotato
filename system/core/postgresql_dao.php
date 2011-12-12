<?php
/**
 * PostgreSQL Data Access Object
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class PostgreSQL_DAO extends Base_DAO {
	/**
	 * Database connection handler
	 *
	 * @var  resource  
	 */
	public $dbh;
	
	/**
	 * Constructor
	 * 
	 * Allow the user to perform a connect at the same time as initialising the this class
	 */
	public function __construct(array $config = array()) {
		// If there is no existing database connection then try to connect
		if ( ! $this->dbh) {
			if ( ! $this->dbh = pg_connect("host=$config['host'] port=$config['port'] user=$config['user'] password=$config['pass'] dbname=$config['name']", TRUE)) {
				halt('An Error Was Encountered', 'Could not connect: '.pg_last_error($this->dbh), 'sys_error');
			} 
			
			// Specify the client encoding per connection
			$collation_query = "SET NAMES '{$config['charset']}'";
			$this->exec_query($collation_query);
		}
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
	 * That will result safe query to PostgreSQL with escaped $login and $password. 
	 */ 
	public function prepare($query, array $params = NULL) { 
		if (count($params) > 0) { 			
			foreach ($params as $k => $v) { 
				$params[$k] = isset($this->dbh) ? pg_escape_string($this->dbh, $v) : addslashes($v); 
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

		// A query result resource on success or FALSE on failure.
		$result = pg_query($this->dbh, $query);

		// If there is an error then take note of it.
		if ($err_msg = pg_last_error($this->dbh)) {
			halt('An Error Was Encountered', $err_msg, 'sys_error');		
		}

		// Query was an insert, delete, update, replace
		if (preg_match("/^(insert|delete|update|replace)\s+/i", $query)) {
			$rows_affected = pg_affected_rows($result);

			// Take note of the last_insert_id
			// REPLACE works exactly like INSERT, except that if an old row in the table has the same value 
			// as a new row for a PRIMARY KEY or a UNIQUE index, the old row is deleted before the new row is inserted.
			if (preg_match("/^(insert|replace)\s+/i", $query)) {
				$this->last_insert_id = pg_last_oid($result);
			}
			// Return number fo rows affected
			$return_val = $rows_affected;
		} else {
			// Store Query Results
			$num_rows = 0;
			while ($row = pg_fetch_object($result)) {
				// Store relults as an objects within main array
				$this->query_result[$num_rows] = $row;
				$num_rows++;
			}

			pg_free_result($result);

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
	 * @access	public
	 * @return	bool
	 */
	public function trans_begin() {
		pg_query($this->dbh, 'begin');
	}
	
	/**
	 * Commit Transaction using standard sql
	 *
	 * @access	public
	 * @return	bool
	 */
	public function trans_commit() {
		pg_query($this->dbh, 'commit');
	}
	
	/**
	 * Rollback Transaction using standard sql
	 *
	 * @access	public
	 * @return	bool
	 */
	public function trans_rollback() {
		pg_query($this->dbh, 'rollback');
	}
	
}

// End of file: ./system/core/postgresql_dao.php