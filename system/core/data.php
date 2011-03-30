<?php
/**
 * Base Data class file.
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class Data {
	
	/**
	 * @var  object  $db database object instance
	 */
	protected $db;
	
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
			$this->db = $this->_create_db_obj($connection);
		}
	}
	
	/**
	 * Create database object, only when RDBMS is used
	 *
	 * @param	string $connection database connection pool, s.g., 'mysql_adapter:default'
	 * @return	a database adapter object
	 */
	private function _create_db_obj($connection) {
		static $db_obj = array();
		// Parse the connection string
		$conn = explode(':', $connection);
			
		if (isset($db_obj[$connection])) {
			// Returns object from runtime cache
			return $db_obj[$connection];
		}
		
		if ( ! empty($conn)) {
			// Load data source config
			$data_source = require_once(APP_CONFIG_DIR.'data_source.php');
			
			// Load desired database adapter
			require_once(SYS_DIR.'core'.DS.strtolower($conn[0]).'.php');
			// Create instance
			$db_obj[$connection] = new $conn[0]($data_source[$conn[0]][$conn[1]]);
			return $db_obj[$connection];
		}
	}
}

// End of file: ./system/core/data.php 
