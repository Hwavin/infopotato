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
	public $db;
	
	/**
	 * Constructor
	 *
	 * RDBMS connection needs to be specified in the subclass's constructor
	 * 
	 * @param	string	connection RDBMS connection name
	 * @return  void
	 */
	public function __construct($connection = '') {
		if ($connection !== '') {
			// Assign a database adapter object
			$this->db = $this->_db_init($connection);
		}
	}
	
	/**
	 * Useful when RDBMS is used
	 *
	 * @param	string $connection the name of the database connection pool (if empty default pool is used)
	 * @return	a database adapter object
	 */
	private function _db_init($connection) {
		static $db_obj = array();
		// Load config information 
		require_once(APP_CONFIG_DIR.'database.php');
		if ($connection && isset($db_obj[$connection])) {
			// Returns object from runtime cache
			return $db_obj[$connection];
		}
		if ($connection && isset($database[$connection]) && ! empty($database[$connection]['adapter'])) {
			// Load database adapter
			require_once(SYS_DIR.'core'.DS.strtolower($database[$connection]['adapter']).'.php');
			// Initialize connection
			$db_obj[$connection] = new $database[$connection]['adapter']($database[$connection]);
			return $db_obj[$connection];
		}
	}
}

// End of file: ./system/core/data.php 
