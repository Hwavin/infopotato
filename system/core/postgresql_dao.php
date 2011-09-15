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
			if ( ! $this->dbh = pg_connect("host=$config['host'] user=$config['user'] password=$config['pass'] dbname=$config['name']", TRUE)) {
				halt('An Error Was Encountered', 'Could not connect: '.pg_last_error($this->dbh), 'sys_error');
			} 
			
			// Specify the client encoding per connection
			$collation_query = "SET NAMES '{$config['charset']}'";
			$this->query($collation_query);
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
	 * That will result safe query to MySQL with escaped $login and $password. 
	 */ 
	public function prepare($query, array $params = NULL) { 
		if (count($params) > 0) { 			
			foreach ($params as $v) { 
				if (isset($this->dbh)) {
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
	 * Perform MySQL query and try to determine result value
	 *
	 * @return int Number of rows affected/selected
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

		// A query result resource on success or FALSE on failure.
		$result = pg_query($this->dbh, $query);

		// If there is an error then take note of it.
		if ($err_msg = pg_last_error($this->dbh)) {
			halt('An Error Was Encountered', $err_msg, 'sys_error');		
		}

		// Query was an insert, delete, update, replace
		if (preg_match("/^(insert|delete|update|replace)\s+/i", $query)) {
			$this->rows_affected = pg_affected_rows($result);

			// Take note of the last_insert_id
			if (preg_match("/^(insert|replace)\s+/i", $query)) {
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