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
     * Returns the translation table for a given language
     *
     * @return array
     */
    private static function init() {
        if ( ! defined('APP_I18N_DIR')) {
            Common::halt('A System Error Was Encountered', "Please define the 'APP_I18N_DIR'.", 'sys_error');
        }
        
        if ( ! defined('APP_I18N_LANG')) {
            Common::halt('A System Error Was Encountered', "Please define the 'APP_I18N_LANG'.", 'sys_error');
        }
        
        // APP_I18N_DIR and APP_I18N_LANG need to be defined in bootstrap
        // before you can start to use the I18N component 
        
        // Directory of language files
        $dir = APP_I18N_DIR;
        // Default target language: 'en_us', 'es_es', 'zh_cn', etc...
        $lang = APP_I18N_LANG;
        
        if (isset(self::$cache[$lang])) {
            return self::$cache[$lang];
        }
        
        // New translation table
        $table = array();
        
        $file = $dir.$lang.'.php';
        if (file_exists($file)) {
            $t = array();
            // Merge the language strings into the sub table
            $t = array_merge($t, include $file);
            
            // Append the sub table, preventing less specific language
            // files from overloading more specific files
            $table += $t;
        } else {
            Common::halt('A System Error Was Encountered', "Language file '". $lang."' does not exist.", 'sys_error');
        }
        
        // Cache the translation table locally
        return self::$cache[$lang] = $table;
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