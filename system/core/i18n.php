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
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

namespace InfoPotato\core;

class I18n {
    /**
     * Default target language: en_us, es_es, zh_cn, etc
     * 
     * @var  string
     */
    public static $lang = 'en_us';
    
    /**
     * Cache of loaded languages
     * 
     * @var  array  
     */
    private static $cache = array();
    
    /**
     * Prevent direct object creation
     * 
     * @return I18ns
     */
    private function __construct() {}
    
    /**
     * Returns translation of a string. If no translation exists, the original
     * string will be returned. No parameters are replaced.
     *
     * $hello = I18n::get('Hello friends, my name is :name');
     *
     * @param   string   text to translate
     * @param   string   target language
     * @return    string
     */
    private static function get($string) {
        $lang = self::$lang;
        
        // Load the translation table for this language
        $table = self::load($lang);
        
        // Return the translated string if it exists
        return isset($table[$string]) ? $table[$string] : $string;
    }
    
    /**
     * Returns the translation table for a given language.
     *
     * Get all defined Spanish messages
     * $messages = I18n::load('zh_cn');
     *
     * @param   string   language to load
     * @return    array
     */
    private static function load($lang) {
        if (isset(self::$cache[$lang])) {
            return self::$cache[$lang];
        }
        
        // New translation table
        $table = array();
        
        $file = APP_I18N_DIR.$lang.'.php';
        if (file_exists($file)) {
            $t = array();
            // Merge the language strings into the sub table
            $t = array_merge($t, include $file);
            
            // Append the sub table, preventing less specific language
            // files from overloading more specific files
            $table += $t;
        }
        
        // Cache the translation table locally
        return self::$cache[$lang] = $table;
    }
    
    /**
     * Returns a translated string if one is found; Otherwise, the submitted message.
     * 
     * Translation/internationalization function. The PHP function
     * [strtr](http://php.net/strtr) is used for replacing parameters.
     *
     * __('Welcome back, :user', array(':user' => $username));
     *
     * The target language is defined by [I18n::$lang].
     * 
     * @uses    I18n::get
     * @param   string  text to translate
     * @param   array   values to replace in the translated text
     * @return  string
     */
    public static function __($str, array $values = NULL) {
        // Get the translation for this message
        $str = self::get($str);
        return empty($values) ? $str : strtr($str, $values);
    }
    
}

// End of file: ./system/core/i18n.php 