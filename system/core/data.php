<?php
/**
 * Base Data class
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class Data {
	
	/**
	 * Database object instance
	 * @var  object
	 */
	protected $_db;
	
	/**
	 * Constructor
	 *
	 * RDBMS connection needs to be specified in the subclass's constructor
	 * One database connection for one data file
	 * 
	 * @param	string	connection RDBMS connection name
	 * @return  void
	 */
	public function __construct($connection = '') {
		if ($connection !== '') {
			$this->db = self::_create_db_obj($connection);
		}
	}
	
	/**
	 * Create database object, only when RDBMS is used
	 *
	 * @param	string $connection database connection pool, s.g., 'mysql_dao:default'
	 * @return	a specific database access object
	 */
	private static function _create_db_obj($connection) {
		static $db_obj = array();
		// Parse the connection string
		$conn = explode(':', $connection);
			
		if (isset($db_obj[$connection])) {
			// Returns object from runtime cache
			return $db_obj[$connection];
		}
		
		if ( ! empty($conn)) {
			// Load data source config
			$data_source = require_once APP_CONFIG_DIR.'data_source.php';
			// Checks if data config exists 
			if ( ! array_key_exists($conn[0], $data_source) || ! array_key_exists($conn[1], $data_source[$conn[0]])) { 
				halt('An Error Was Encountered', 'Incorrect database connection string', 'sys_error');
			}
			// Create instance
			$db_obj[$connection] = new $conn[0]($data_source[$conn[0]][$conn[1]]);
			return $db_obj[$connection];
		}
	}
}

// End of file: ./system/core/data.php 
