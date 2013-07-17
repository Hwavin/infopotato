<?php
/**
 * MySQL Data Access Object
 *
 * If you are using MySQL versions 4.1.3 or later it is strongly recommended that you use the mysqli extension instead.
 * 
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
 
class MySQL_DAO extends Base_DAO {
    /**
     * Database connection handler
     *
     * @var  resource  
     */
    private $dbh;
    
    /**
     * Constructor
     * 
     * Allow the user to perform a connect at the same time as initialising the this class
     */
    public function __construct(array $config = NULL) {
        // If there is no existing database connection then try to connect
        if ( ! is_resource($this->dbh)) {
            if ( ! $this->dbh = mysql_connect($config['host'].':'.$config['port'], $config['user'], $config['pass'], TRUE)) {
                halt('An Error Was Encountered', 'Could not connect: '.mysql_error($this->dbh), 'sys_error');        
            } 
            
            // Use utf8mb4 as the character set and utf8mb4_general_ci as the collation if MySQL > 5.5
            // Use utf8 as the character set and utf8_unicode_ci as the collation if MySQL < 5.5
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
                $this->exec_query($collation_query);
            }
            
            if ( ! mysql_select_db($config['name'], $this->dbh)) {
                halt('An Error Was Encountered', 'Can not select database', 'sys_error');        
            }
        }
    }
	
    /** 
     * Escapes special characters in a string for use in an SQL statement, 
     * taking into account the current charset of the connection
     */ 
    public function escape($str) { 
        // Only escape string
        // is_string() will take '' as string
        if (is_string($str)) {
            $str = mysql_real_escape_string($str, $this->dbh); 
        }
        return $str; 
    }
    
    /** 
     * USAGE: prepare( string $query [, array $params ] ) 
     * The following directives can be used in the query format string:
     * %i (integer)
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
			
            $bind_types = array('%s', '%i', '%f');
			
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
            
            // By default $pos_list is ordered by the position of %s, %i, %f in the query
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
                } elseif ($type === '%i') {
                    // 32 bit systems have a maximum signed integer range of -2147483648 to 2147483647. 
                    // So is_int(2147483648) will return FALSE in 32 bit systems.
                    // 64 bit systems have a maximum signed integer range of -9223372036854775808 to 9223372036854775807. 
                    // So is_int(9223372036854775808) will return FALSE in 64 bit systems.
                    if ( ! is_int($arg)) {
                        halt('An Error Was Encountered', 'The binding value for %i must be an integer', 'sys_error');
                    }
                } elseif ($type === '%f') {
                    if (is_float($arg)) {
                        // E.g., is_float(1e7) returns TRUE since 1e7 is a float in Scientific Notation
                        // We need to use floatval() to get the float value of the given variable
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
        $return_val = 0;
		
        // Reset stored query result
        $this->query_result = array();
		
        // For reg expressions
        $query = trim($query);
		
        // For SELECT, SHOW, DESCRIBE, EXPLAIN and other statements returning resultset, 
        // mysql_query() returns a resource on success, or FALSE on error.
        // For INSERT, UPDATE, DELETE, DROP, etc, mysql_query() returns TRUE on success or FALSE on error.
        $result = mysql_query($query, $this->dbh);
		
        // If there is an error then take note of it.
        if ($err_msg = mysql_error($this->dbh)) {
            halt('An Error Was Encountered', $err_msg, 'sys_error');        
        }
		
        // Query was an insert, delete, drop, update, replace, alter
        // mysql_query() returns TRUE on success or FALSE on error
        if (preg_match("/^(insert|delete|truncate|drop|update|replace|alter)\s+/i", $query)) {
            // Use mysql_affected_rows() to find out how many rows were affected 
            // by a DELETE, INSERT, REPLACE, or UPDATE statement
            // When using UPDATE, MySQL will not update columns where the new value is the same as the old value. 
            // This creates the possibility that mysql_affected_rows() may not actually equal the number of rows matched, 
            // only the number of rows that were literally affected by the query.
            $rows_affected = mysql_affected_rows($this->dbh);
			
            // Take note of the last_insert_id
            // REPLACE works exactly like INSERT, except that if an old row in the table has the same value 
            // as a new row for a PRIMARY KEY or a UNIQUE index, the old row is deleted before the new row is inserted.
            if (preg_match("/^(insert|replace)\s+/i", $query)) {
                $this->last_insert_id = mysql_insert_id($this->dbh);
            }
            // Return number fo rows affected
            $return_val = $rows_affected;
        } elseif (preg_match("/^(select|describe|desc|show|explain)\s+/i", $query)) {
            // Store Query Results
            $num_rows = 0;
            // $result must be a resource type
            while ($row = mysql_fetch_object($result)) {
                // Store relults as an objects within main array
                $this->query_result[$num_rows] = $row;
                $num_rows++;
            }
			
            mysql_free_result($result);
			
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
     * MySQL MyISAM tables do not support transactions and will auto-commit even if a transaction has been started
     * 
     * @return    bool
     */
    public function trans_begin() {
        mysql_query('SET AUTOCOMMIT=0', $this->dbh);
        mysql_query('START TRANSACTION', $this->dbh);// can also be BEGIN or BEGIN WORK
    }
    
    /**
     * Commit Transaction using standard sql
     *
     * @return    bool
     */
    public function trans_commit() {
        mysql_query('COMMIT', $this->dbh);
        mysql_query('SET AUTOCOMMIT=1', $this->dbh);
    }
    
    /**
     * Rollback Transaction using standard sql
     *
     * @return    bool
     */
    public function trans_rollback() {
        mysql_query('ROLLBACK', $this->dbh);
        mysql_query('SET AUTOCOMMIT=1', $this->dbh);
    }
	
}

// End of file: ./system/core/mysql_dao.php