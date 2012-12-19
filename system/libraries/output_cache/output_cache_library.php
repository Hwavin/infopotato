<?php
/**
 * File-based Output Cache Library
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2012 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link		based on http://www.jongales.com/blog/2009/02/18/simple-file-based-php-cache-class/
 * @link        based on http://www.rooftopsolutions.nl/article/107
 */
class Output_Cache_Library {  
	
	/**
	 * The cache dir. The dir should end with DIRECTORY_SEPARATOR
	 * 
	 * @var string 
	 */
	private $_dir;
	
	/**
	 * Constructor
	 *
	 * @param array $config['cache_dir']
	 *
	 */	
	public function __construct(array $config = NULL) { 
		if (count($config) > 0) {
			if (isset($config['cache_dir'])) {
				$this->_dir = $config['cache_dir'];
			}
		}
	}
	
	/**
	 * Find the filename for a certain key 
	 *
	 * @param	string
	 * @return	string
	 */
    private function _name($key) {  
        return ($this->_dir).sha1($key);  
    }  
	
	/**
	 * Get the cached file for a certain key 
	 * 
	 * @param	$key string
	 * @return	mixed - FALSE|string (cached data)
	 */
    public function get($key) {  
        if ( ! is_dir($this->_dir)) {
            return FALSE;   
		}
        $cache_path = $this->_name($key);  

        if ( ! file_exists($cache_path) || ! is_readable($cache_path)) {
            return FALSE;   
		}
		// Open for reading only; use 'b' to force binary mode
		if ( ! $fp = fopen($cache_path, 'rb')) {
            return FALSE;  
		}
		// To acquire a shared lock (reader)
		flock($fp, LOCK_SH);  
		// file_get_contents() is much faster than fread()
		// $cached_data is an array contains [0] => expire time, [1] => cached content
		$cached_data = unserialize(file_get_contents($cache_path));  
		// To release a lock (shared or exclusive)
		flock($fp, LOCK_UN);
		// The lock is released also by fclose() (which is also called automatically when script finished).
		fclose($fp);
		
		if ($cached_data) {
			// Check if the cached file was expired 
			if (time() > $cached_data[0]) { 
				$this->clear($key);  
				return FALSE;
			}
			return $cached_data[1]; 
		} 
		return FALSE; 
    }  
	
	/**
	 * Create the cache file for a certain key 
	 * 
	 * @param	$key string
	 * @param	$data string the data to be cached
	 * @param	$ttl integer - time to life (seconds)
	 * @return	boolean
	 */
    public function set($key, $data, $ttl = 3600) {  
        if ( ! is_dir($this->_dir) || ! $this->_is_really_writable($this->_dir)) {
            return FALSE;  
		}
        $cache_path = $this->_name($key);  
		
		// Open for writing only; use 'b' to force binary mode
        if ( ! $fp = fopen($cache_path, 'wb')) {
            return FALSE;    
		}
		// To acquire an exclusive lock (writer)
        if (flock($fp, LOCK_EX)) {  	
			// fwrite is faster than file_put_contents() 
			fwrite($fp, serialize(array(time() + $ttl, $data)));  
            // To release a lock (shared or exclusive)
			flock($fp, LOCK_UN);
			// The lock is released also by fclose() (which is also called automatically when script finished).
			fclose($fp); 
        } else {
            return FALSE;  
        }
        chmod($cache_path, 0777); 
		return TRUE;    
    }  
	
	/**
	 * Delete the cached file for a certain key 
	 *
	 * @param	$key string
	 * @return	boolean
	 */
    public function clear($key) {  
        $cache_path = $this->_name($key);  
        if (file_exists($cache_path)) {  
            // Deletes cached file
			unlink($cache_path);  
            return TRUE;  
        }  
        return FALSE;  
    }  
	
	/**
	 * Tests for file writability
	 *
	 * is_writable() returns TRUE on Windows servers when you really can't write to 
	 * the file, based on the read-only attribute.  is_writable() is also unreliable
	 * on Unix servers if safe_mode is on. 
	 *
	 * @return	void
	 */
	private function _is_really_writable($file) {	
		// If we're on a Unix server with safe_mode off we call is_writable
		if (DIRECTORY_SEPARATOR == '/' && @ini_get("safe_mode") == FALSE) {
			return is_writable($file);
		}

		// For windows servers and safe_mode "on" installations we'll actually
		// write a file then read it.  Bah...
		if (is_dir($file)) {
			$file = rtrim($file, '/').'/'.md5(rand(1, 100));

			if (($fp = @fopen($file, 'ab')) === FALSE) {
				return FALSE;
			}

			fclose($fp);
			@chmod($file, 0777);
			@unlink($file);
			return TRUE;
		} elseif (($fp = @fopen($file, 'ab')) === FALSE) {
			return FALSE;
		}

		fclose($fp);
		return TRUE;
	}
} 
 
/* End of file: ./system/libraries/output_cache/output_cache_library.php */