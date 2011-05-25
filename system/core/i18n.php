<?php
/**
 * Internationalization (i18n) class. Provides language loading and translation
 * methods without dependencies on [gettext](http://php.net/gettext).
 *
 * Typically this class would never be used directly, but used via the __()
 * function, which loads the message and replaces parameters:
 *
 *     // Display a translated message
 *     echo __('Hello, world');
 *
 *     // With parameter replacement
 *     echo __('Hello, :user', array(':user' => $username));
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class I18n {

	/**
	 * Target language: en/us, es/es, zh/cn, etc
	 * 
	 * @var  string   
	 */
	public static $lang = 'en/us';

	/**
	 * Cache of loaded languages
	 * 
	 * @var  array  
	 */
	protected static $_cache = array();
	
	/**
	 * Returns translation of a string. If no translation exists, the original
	 * string will be returned. No parameters are replaced.
	 *
	 *     $hello = I18n::get('Hello friends, my name is :name');
	 *
	 * @param   string   text to translate
	 * @param   string   target language
	 * @return  string
	 */
	public static function get($string) {
		$lang = I18n::$lang;

		// Load the translation table for this language
		$table = I18n::load($lang);

		// Return the translated string if it exists
		return isset($table[$string]) ? $table[$string] : $string;
	}

	/**
	 * Returns the translation table for a given language.
	 *
	 *     // Get all defined Spanish messages
	 *     $messages = I18n::load('es/es');
	 *
	 * @param   string   language to load
	 * @return  array
	 */
	public static function load($lang) {
		if (isset(I18n::$_cache[$lang])) {
			return I18n::$_cache[$lang];
		}

		// New translation table
		$table = array();

		// Is the lang in a sub-folder? If so, parse out the filename and path.
		if (strpos($lang, '/') === FALSE) {
			$path = '';
		} else {
			$path = str_replace('/', DS, pathinfo($lang, PATHINFO_DIRNAME)).DS;
			$lang = substr(strrchr($lang, '/'), 1);	
		}
		
		$file = APP_I18N_DIR.$path.$lang.'.php';
		if (file_exists($file)) {
			$t = array();
			// Merge the language strings into the sub table
			$t = array_merge($t, include $file);

			// Append the sub table, preventing less specific language
			// files from overloading more specific files
			$table += $t;
		}

		// Cache the translation table locally
		return I18n::$_cache[$lang] = $table;
	}

}

// End of file: ./system/core/i18n.php 