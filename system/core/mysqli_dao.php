<?php
/**
 * MySQLi Data Access Object
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2014 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

namespace InfoPotato\core;

class MySQLi_DAO extends Base_DAO {
    /**
     * An object which represents the connection to a MySQL Server
     *
     * @var object
     */
    private $mysqli;
    
    /**
     * Constructor
     * 
     * Allow the user to perform a connect at the same time as initialising the this class
     */
    public function __construct(array $config = NULL) {
        // If there is no existing database connection then try to connect
        if ( ! is_object($this->mysqli)) {
            $this->mysqli = new \mysqli($config['host'], $config['user'], $config['pass'], $config['name'], $config['port']);

            // Check connection
            if ($this->mysqli->connect_error) {
                Common::halt('An Error Was Encountered', 'Connect Error ('.$this->mysqli->connect_errno.') '.$this->mysqli->connect_error, 'sys_error');        
            }

            // Set the character set to be used when sending data from and to the database server
            // This is the preferred way to change the charset
            // Using mysqli_query() to set it (such as SET NAMES utf8) is not recommended
            // Use utf8mb4 as the character set and utf8mb4_unicode_ci in MySQL as the collation if MySQL > 5.5
            // Use utf8 as the character set and utf8_unicode_ci in MySQL as the collation if MySQL < 5.5
            if ( ! $this->mysqli->set_charset($config['charset'])) {
                Common::halt('An Error Was Encountered', 'Failed to set the specified character set.', 'sys_error');        
            }
        }
    }

    /** 
     * Escapes special characters in a string for use in an SQL statement, 
     * taking into account the current charset of the connection
	 *
	 * @param string $str the raw query string
	 * @return string the escaped query string
     */ 
    public function escape($str) { 
        // Only escape string
        // is_string() will take '' as string
        if (is_string($str)) {
            $str = $this->mysqli->real_escape_string($str); 
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
	 * @param string $query the raw query string that contains directives
     * @param array $params an array of values to replace the directives
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
                        Common::halt('An Error Was Encountered', 'The binding value for %s must be a string!', 'sys_error');
                    }
                } elseif ($type === '%i') {
                    // 32 bit systems have a maximum signed integer range of -2147483648 to 2147483647. 
                    // So is_int(2147483648) will return FALSE in 32 bit systems.
                    // 64 bit systems have a maximum signed integer range of -9223372036854775808 to 9223372036854775807. 
                    // So is_int(9223372036854775808) will return FALSE in 64 bit systems.
                    if ( ! is_int($arg)) {
                        Common::halt('An Error Was Encountered', 'The binding value for %i must be an integer!', 'sys_error');
                    }
                } elseif ($type === '%f') {
                    if (is_float($arg)) {
                        // E.g., is_float(1e7) returns TRUE since 1e7 is a float in Scientific Notation
                        // We need to use floatval() to get the float value of the given variable
                        floatval($arg);
                    } else {
                        Common::halt('An Error Was Encountered', 'The binding value for %f must be a float!', 'sys_error');
                    }
                } else {
                    Common::halt('An Error Was Encountered', "Unknown binding marker in: $query", 'sys_error');
                }

                // Note that strlen() simply counts the number of bytes in a string, not the number of characters. 
                // This means for UTF-8 string the integer it returns is actually longer than the number of characters in the string.
                // BUT because $pos is not affected by the actual replacement string and 
                // $pos_adj only represents the query length increment after each replacement,
                // and at the same time substr_replace() counts exactly the same as strlen(), 
                // the prepared final query will be no problem.
                $query = substr_replace($query, $arg, $pos + $pos_adj, $type_length);
                // Adjust the start offset for next replacement
                $pos_adj += strlen($arg) - $type_length;
            }
        } 

        return $query; 
    } 
    
    /**
     * Perform a unique query (multiple queries are not supported) and try to determine result value
     *
     * @param string $query the raw query string
     * @return int the number of rows affected/selected or false on error
     */
    public function exec_query($query) {
        // Initialize return
        $return_val = 0;

        // Reset stored query result
        $this->query_result = array();

        // It's safe to use trim() without the second charlist argument on a UTF-8 string,
        // because the whitespace characters they are searching for are all in the ASCII 7 range.
        $query = trim($query);

        // Perform the query via mysqli->query() function.
        // Returns FALSE on failure. For successful SELECT, SHOW, DESCRIBE or EXPLAIN 
        // queries mysqli->query() will return a result object. 
        // For other successful queries mysqli->query() will return TRUE.
        $result = $this->mysqli->query($query);

        // If there is an error then take note of it.
        if ($err_msg = $this->mysqli->error) {
            Common::halt('An Error Was Encountered', $err_msg, 'sys_error');
        }

        // Query was an insert, delete, drop, update, replace, alter
        if (preg_match("/^(insert|delete|drop|update|replace|alter)\s+/i", $query)) {
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
        } elseif (preg_match("/^(select|describe|desc|show|explain)\s+/i", $query)) {
            // Store Query Results
            $num_rows = 0;
            while ($row = $result->fetch_object()) {
                // Store results as an objects within main array
                $this->query_result[$num_rows] = $row;
                $num_rows++;
            }

            $result->free_result();

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
     * Disable autocommit to begin transaction
     * MySQL MyISAM tables do not support transactions and will auto-commit even if a transaction has been started
     * 
     * @return bool
     */
    public function trans_begin() {
        return $this->mysqli->autocommit(FALSE);
    }
    
    /**
     * Commit Transaction
     *
     * @return bool
     */
    public function trans_commit() {
        // Note that only calling mysqli->commit() will NOT automatically set mysqli->autocommit() back to 'TRUE'.
        // This means that any queries following mysqli->commit() will be rolled back when this script exits.
        // We need to call mysqli->autocommit(TRUE) to turn on autocommit
        return ($this->mysqli->commit() && $this->mysqli->autocommit(TRUE));
    }
    
    /**
     * Rollback Transaction
     *
     * @return bool
     */
    public function trans_rollback() {
        // Note that only calling mysqli->rollback() will NOT automatically set mysqli->autocommit() back to 'TRUE'.
        // This means that any queries following mysqli->rollback() will be rolled back when this script exits.
        // We need to call mysqli->autocommit(TRUE) to turn on autocommit
        return ($this->mysqli->rollback()  && $this->mysqli->autocommit(TRUE));
    }
    
}

// End of file: ./system/core/mysqli_dao.php