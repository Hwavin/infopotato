<?php
/**
 * Injector class file.
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2014 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
 
namespace InfoPotato\core;

class Injector {
    /**
     * Prevent direct object creation
     * 
     * @return Injector
     */
    private function __construct() {}
    
    /**
     * Data Object Dependency Injector
     *
     * If your data is located in a sub-folder, include the relative path from your data folder.
     *
     * @param string $data the name of the APP data class
     * @return object instance of APP data class
     */    
    public static function data($data) {
		// Data name is case-sensitive, since you can have two files with the same names but different cases of same letters on Linux
		// You need to make sure the case of the provided name is consistent with the case of the file name
        $orig_data = $data;
        
        // Only the forward slash '/' is allowed for sub-directory
        // strpos() returns FALSE if the needle was not found, 
        // otherwise returns the position of where the needle exists
        if (strpos($data, '\\') !== FALSE) {
            Common::halt('A System Error Was Encountered', 'The backslash is not allowed for the data name to be loaded', 'sys_error');
        }
        
        // Is the data in a sub-folder? If so, parse out the filename and path.
        if (strpos($data, '/') === FALSE) {
            $path = '';
            $namespace = '';
        } else {
            $path = strtr(pathinfo($data, PATHINFO_DIRNAME), '/', DS).DS;
            $namespace = strtr(pathinfo($data, PATHINFO_DIRNAME), '/', '\\').'\\';
            $data = substr(strrchr($data, '/'), 1);
        }

		$source_file = APP_DATA_DIR.$path.$data.'.php';
		
		if ( ! file_exists($source_file)) {
			Common::halt('A System Error Was Encountered', "APP data file '{$orig_data}.php' does not exist!", 'sys_error');
		}
		
		// Load stripped source when runtime cache is turned-on
		// Otherwise load the origional unstripped file
		$file = $source_file;

		if (RUNTIME_CACHE === TRUE) {
			// Replace all directory separators with underscore
			$temp_cache_name = str_replace(DS, '_', $path.$data);
			// The runtime folder must be writable
			$file = APP_RUNTIME_CACHE_DIR.'~'.$temp_cache_name.'.php';
			if ( ! file_exists($file)) {
				// Return source with stripped comments and whitespace
				file_put_contents($file, php_strip_whitespace($source_file));
			}
		} 
		
		require $file;

		// Prefix namespace
		// Trim the leading backslash in case user added
		$app_data_namespace = trim(APP_DATA_NAMESPACE, '\\');
		$data = $app_data_namespace.'\\'.$namespace.$data;

		// Now the data class has been prefixed with proper namespace
		// Set autoload FALSE so this function won't try to autoload the class if it doesn't exists
		// This prevents incorrect autoloading error message defined in spl_autoload_register()
		// Otherwise missing APP Manager file error message will be triggered
		if ( ! class_exists($data, FALSE)) {
			Common::halt('A System Error Was Encountered', "The required class '{$orig_data}' is not defined in '{$orig_data}.php'!", 'sys_error');
		}
		
		// Instantiate the data object and return it
		return new $data;
    }
    
    /**
     * Library Class Dependency Injector
     *
     * This function lets users load and instantiate classes.
     * It is designed to be called from a user's app controllers.
     *
     * If library is located in a sub-folder, include the relative path from libraries folder.
     *
     * @param string $scope 'SYS' or 'APP'
     * @param string $library the name of the class
     * @param array $config the optional config parameters
     * @return object instance of APP or SYS library class
     */       
    public static function library($scope, $library, array $config = NULL) {
        // Library name is case-sensitive, since you can have two files with the same names but different cases of same letters on Linux
		// You need to make sure the case of the provided name is consistent with the case of the file name
        $orig_library = $library;
        
        // Only the forward slash '/' is allowed for sub-directory
        // strpos() returns FALSE if the needle was not found, 
        // otherwise returns the position of where the needle exists
        if (strpos($library, '\\') !== FALSE) {
            Common::halt('A System Error Was Encountered', 'The backslash is not allowed for the library name to be loaded', 'sys_error');
        }
        
        // Is the library in a sub-folder? If so, parse out the filename and path.
        if (strpos($library, '/') === FALSE) {
            $path = '';
            $namespace = '';
        } else {
            $path = strtr(pathinfo($library, PATHINFO_DIRNAME), '/', DS).DS;
            $namespace = strtr(pathinfo($library, PATHINFO_DIRNAME), '/', '\\').'\\';
            $library = substr(strrchr($library, '/'), 1);
        }

		if ($scope === 'SYS') {
			$source_file = SYS_LIBRARY_DIR.$path.$library.'.php';
			
			// Prefix namespace -- hard-coded
			$sys_library_namespace = 'InfoPotato\libraries';
			$library = $sys_library_namespace.'\\'.$namespace.$library;
		} elseif ($scope === 'APP') {
			$source_file = APP_LIBRARY_DIR.$path.$library.'.php';

			// Prefix namespace
			// Trim the leading backslash in case user added
			$app_library_namespace = trim(APP_LIBRARY_NAMESPACE, '\\');
			$library = $app_library_namespace.'\\'.$namespace.$library;
		} else {
			Common::halt('A System Error Was Encountered', "The location of the library must be specified, either 'SYS' or 'APP'", 'sys_error');
		}

		if ( ! file_exists($source_file)) {
			Common::halt('A System Error Was Encountered', "{$scope} library file '{$orig_library}.php' does not exist!", 'sys_error');
		}
		
		// Load stripped source when runtime cache is turned-on
		// Otherwise load the origional unstripped file
		$file = $source_file;

		if (RUNTIME_CACHE === TRUE) {
			// Replace all directory separators with underscore
			// Must use $orig_library since $library has been prefixed with namespace
			$temp_cache_name = str_replace(DS, '_', $path.$orig_library);
		
			// The runtime cache folder must be writable
			if ($scope === 'SYS') {
				$file = SYS_RUNTIME_CACHE_DIR.'~'.$temp_cache_name.'.php';
			} elseif ($scope === 'APP') {
				$file = APP_RUNTIME_CACHE_DIR.'~'.$temp_cache_name.'.php';
			}
			
			if ( ! file_exists($file)) {
				// Return source with stripped comments and whitespace
				file_put_contents($file, php_strip_whitespace($source_file));
			}
		} 
		
		require $file;

		// Now the library class has been prefixed with proper namespace
		// Set autoload FALSE so this function won't try to autoload the class if it doesn't exists
		// This prevents incorrect autoloading error message defined in spl_autoload_register()
		// Otherwise missing APP Manager file error message will be triggered
		if ( ! class_exists($library, FALSE)) { 
			Common::halt('A System Error Was Encountered', "The required class '{$orig_library}' is not defined in {$scope} library file '{$orig_library}.php'!", 'sys_error');
		}

		// Instantiate the library object as a manager's property 
		// An empty array is considered as a NULL variable
		// The names of user-defined classes are case-insensitive
		// Don't create static properties or methods for library
		return new $library($config);
    }

}

// End of file: ./system/core/injector.php 
