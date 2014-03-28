<?php
/**
 * SQLite(PDO) Data Access Object
 *
 * SQLite 3 is supported through PDO SQLite
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2014 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

namespace InfoPotato\core;

class SQLite_DAO extends Base_DAO {
    /**
     * Database connection handler
     *
     * @var object
     */
    private $dbh;
    
    /**
     * Constructor
     * 
     * Allow the user to perform a connect at the same time as initialising the this class
     */
    public function __construct(array $config = array()) {
        // If there is no existing database connection then try to connect
        if ( ! is_object($this->dbh)) {
            try {
                $this->dbh = new PDO($config['dsn']);
            } catch (PDOException $e) {
                Common::halt('An Error Was Encountered', 'Connection failed: '.$e->getMessage(), 'sys_error');
            }
        }
    }

    /** 
     * Escapes special characters in a string for use in an SQL statement, 
     * taking into account the current charset of the connection
     */ 
    public function escape($str) { 
        // The input string should be un-quoted
        // is_string() will take '' as string
        if (is_string($str)) {
            $str = addslashes($str);
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
     * @return int Number of rows affected/selected
     */
    public function exec_query($query) {
        // Initialize return
        $return_val = 0;

        // Reset stored query result
        $this->query_result = array();

        // It's safe to use trim() without the second charlist argument on a UTF-8 string,
        // because the whitespace characters they are searching for are all in the ASCII 7 range.
        $query = trim($query);

        // Query was an insert, delete, drop, update, replace, alter
        if (preg_match("/^(insert|delete|drop|create|update|replace|alter)\s+/i", $query)) {
            // Execute the target query and return the number of affected rows
            // that were modified or deleted by the SQL statement you issued. 
            // If no rows were affected, returns 0.
            $rows_affected = $this->dbh->exec($query);

            // Take note of the last_insert_id
            // REPLACE works exactly like INSERT, except that if an old row in the table has the same value 
            // as a new row for a PRIMARY KEY or a UNIQUE index, the old row is deleted before the new row is inserted.
            if (preg_match("/^(insert|replace)\s+/i", $query)) {
                $this->last_insert_id = $this->dbh->lastInsertId();    
            }
            // Return number fo rows affected
            $return_val = $rows_affected;
        } elseif (preg_match("/^(select|describe|desc|show|explain)\s+/i", $query)) {
            // Executes an SQL statement, returns a PDOStatement object, or FALSE on failure.
            $statement = $this->dbh->query($query);

            if ($statement === FALSE) {
                $err = $this->dbh->errorInfo();
                Common::halt('An Error Was Encountered', $err[0].' - '.$err[1].' - '.$err[2], 'sys_error');
            }
            
            // Store Query Results
            $num_rows = 0;
            // PDO::FETCH_ASSOC is the fetch_style parameter predefined in PDO
            // don't be confused with the FETCH_ASSOC defined for DAO
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                // Store relults as an objects within main array
                // Convert to object
                $this->query_result[$num_rows] = (object) $row;
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
     * @return bool
     */
    public function trans_begin() {
        $this->dbh->beginTransaction();
    }
    
    /**
     * Commit Transaction using standard sql
     *
     * @return bool
     */
    public function trans_commit() {
        $this->dbh->commit();
    }
    
    /**
     * Rollback Transaction using standard sql
     *
     * @return bool
     */
    public function trans_rollback() {
        $this->dbh->rollBack();
    }
    
}

// End of file: ./system/core/sqlite_dao.php