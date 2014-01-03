<?php
/**
 * Internationalization (i18n) class. Provides language loading and translation
 * methods without dependencies on [gettext](http://php.net/gettext).
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2014 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

namespace InfoPotato\core;

class I18n {
    /**
     * Directory of language files
     * 
     * @var string
     */
    private static $dir;
    
    /**
     * Default target language: en_us, es_es, zh_cn, etc
     * 
     * @var string
     */
    private static $lang = 'en_us';
    
    /**
     * Cache of loaded languages
     * 
     * @var array  
     */
    private static $cache = array();
    
    /**
     * Prevent direct object creation
     * 
     * @return I18n
     */
    private function __construct() {}
    
    /**
     * Sets the default language directory and specific language file
     * Call this in bootstrap script
     * 
     * @param string I18n directory
     * @param string optional language to load
     * @return array
     */
    public static function init($dir, $lang = 'en_us') {
        self::$dir = $dir;
        self::$lang = $lang;
    }

    /**
     * Returns the translation table for a given language
     *
     * @return array
     */
    private static function load() {
        if (isset(self::$cache[self::$lang])) {
            return self::$cache[self::$lang];
        }
        
        // New translation table
        $table = array();
        
        $file = self::$dir.self::$lang.'.php';
        if (file_exists($file)) {
            $t = array();
            // Merge the language strings into the sub table
            $t = array_merge($t, include $file);
            
            // Append the sub table, preventing less specific language
            // files from overloading more specific files
            $table += $t;
        } else {
            Common::halt('A System Error Was Encountered', "Language file '". self::$lang."' does not exist.", 'sys_error');
        }
        
        // Cache the translation table locally
        return self::$cache[self::$lang] = $table;
    }
    
    /**
     * Returns a translated string if one is found; Otherwise, the submitted message.
     * 
     * @param string text to translate
     * @return string
     */
    public static function __($str) {
        // Load the translation table for this language
        $table = self::load();
        
        // Returns translation of a string. If no translation exists, otherwise
        // the original string will be returned with no parameters are replaced.
        return isset($table[$str]) ? $table[$str] : $str;
    }
    
}

// End of file: ./system/core/i18n.php 