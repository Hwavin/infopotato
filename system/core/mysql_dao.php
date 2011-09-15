<?php
/**
 * MySQL Data Access Object
 *
 * If you are using MySQL versions 4.1.3 or later it is strongly recommended that you use the mysqli extension instead.
 * 
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

class MySQL_DAO extends Base_DAO {
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
	public function __construct(array $config = NULL) {
		// If there is no existing database connection then try to connect
		if ( ! $this->dbh) {
			if ( ! $this->dbh = mysql_connect($config['host'], $config['user'], $config['pass'], TRUE)) {
				halt('An Error Was Encountered', 'Could not connect: '.mysql_error($this->dbh), 'sys_error');		
			} 
			
			if (function_exists('mysql_set_charset')) { 
				// Set charset (mysql_set_charset(), PHP 5 >= 5.2.3)
				// This function requires MySQL 5.0.7 or later.
				mysql_set_charset($config['charset'], $this->dbh);
			} else {
				// Specify the client encoding per connection
				$collation_query = "SET NAMES '{$config['charset']}'";
				if ( ! empty($config['collate'])) {
					$collation_query .= " COLLATE '{$config['collate']}'";
				}
				$this->query($collation_query);
			}
			
			if ( ! mysql_select_db($config['name'], $this->dbh)) {
				halt('An Error Was Encountered', 'Can not select database', 'sys_error');		
			}
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
	 * Perform MySQL query and try to determine result value
	 *
	 * @return int Number of rows affected/selected
	 */
	public function query($query) {
		$return_val = 0;

		// Flush cached values.
		$this->flush();

		// For reg expressions
		$query = trim($query);

		// Keep track of the last query for debug
		$this->last_query = $query;

		// For SELECT, SHOW, DESCRIBE, EXPLAIN and other statements returning resultset, 
		// mysql_query() returns a resource on success, or FALSE on error.
		// For INSERT, UPDATE, DELETE, DROP, etc, mysql_query() returns TRUE on success or FALSE on error.
		$result = mysql_query($query, $this->dbh);
		
		// If there is an error then take note of it.
		if ($err_msg = mysql_error($this->dbh)) {
			halt('An Error Was Encountered', $err_msg, 'sys_error');		
		}

		// Query was an insert, delete, update, replace
		if (preg_match("/^(insert|delete|update|replace)\s+/i", $query)) {
			// Use mysql_affected_rows() to find out how many rows were affected 
			// by a DELETE, INSERT, REPLACE, or UPDATE statement
			$this->rows_affected = mysql_affected_rows($this->dbh);

			// Take note of the last_insert_id
			if (preg_match("/^(insert|replace)\s+/i", $query)) {
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

		return $return_val;
	}

	/**
	 * Returns the current date and time, e.g., 2006-04-12 13:47:46
	 */
	public function now() {
		return 'NOW()';
	}
	
	/**
	 * Begin Transaction using standard sql
	 * MySQL MyISAM tables do not support transactions and will auto-commit even if a transaction has been started
	 * 
	 * @access	public
	 * @return	bool
	 */
	public function trans_begin() {
		mysql_query('SET AUTOCOMMIT=0', $this->dbh);
		mysql_query('START TRANSACTION', $this->dbh);// can also be BEGIN or BEGIN WORK
	}
	
	/**
	 * Commit Transaction using standard sql
	 *
	 * @access	public
	 * @return	bool
	 */
	public function trans_commit() {
		mysql_query('COMMIT', $this->dbh);
		mysql_query('SET AUTOCOMMIT=1', $this->dbh);
	}
	
	/**
	 * Rollback Transaction using standard sql
	 *
	 * @access	public
	 * @return	bool
	 */
	public function trans_rollback() {
		mysql_query('ROLLBACK', $this->dbh);
		mysql_query('SET AUTOCOMMIT=1', $this->dbh);
	}

}

// End of file: ./system/core/mysql_dao.php