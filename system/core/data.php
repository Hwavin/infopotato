<?php
/**
 * Base Data class
 *
 * If no RDBMS DAO used in application-specific data class,
 * no need to include this class in namespace and extend this base data class
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2014 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
 
namespace InfoPotato\core;

class Data {
    /**
     * Database object instance
     * @var object
     */
    protected $db;
    
    /**
     * Constructor, has to be public since it's instantiated by manager's load_data()
     *
     * RDBMS connection needs to be specified in the subclass's constructor
     * One database connection for one data file
     * 
     * @param string RDBMS connection string
     * @return void
     */
    public function __construct($connection = '') {
        if ($connection !== '') {
            $this->db = self::create_db_obj($connection);
        }
    }
    
    /**
     * Create database object, only when RDBMS is used
     *
     * @param string $connection database connection string, e.g., 'mysqli_dao:default'
     * @return object A specific database access object
     */
    private static function create_db_obj($connection) {
        static $db_obj = array();
        
        if (isset($db_obj[$connection])) {
            // Returns object from runtime cache
            return $db_obj[$connection];
        }
        
        // Parse the connection string
        $conn = explode(':', $connection);
        
        if ( ! empty($conn)) {
            // Load data source config
            // DO NOT use require_once() here, otherwise if you mix use different DAOs
            // $data_source will retuen the config array for the first time when DAO is called
            // Then $data_source will return TRUE for the following DAO connections
            $data_source = require APP_CONFIG_DIR.'data_source.php';

            // Checks if data config exists 
            if ( ! array_key_exists($conn[0], $data_source) || ! array_key_exists($conn[1], $data_source[$conn[0]])) { 
                Common::halt('An Error Was Encountered', 'The specified database connection string is incorrect!', 'sys_error');
            }
            
            // Prefix namespace
            $sql_dao_namespace = 'InfoPotato\core';
            $sql_dao = $sql_dao_namespace.'\\'.$conn[0];

            // Create instance
            $db_obj[$connection] = new $sql_dao($data_source[$conn[0]][$conn[1]]);
            return $db_obj[$connection];
        }
    }
}

// End of file: ./system/core/data.php 
