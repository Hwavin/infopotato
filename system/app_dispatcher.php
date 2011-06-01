<?php
/**
 * Application Dispatcher Class
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
 
require_once SYS_CORE_DIR.'common.php';

// Get and set the current locale
I18n::$lang = Session::get('lang') ? Session::get('lang') : 'en/us';

/**
 * It encapsulates {@link Dispatcher} which provides the actual implementation.
 * By writing your own App_Dispatcher class, you can customize some functionalities of Dispatcher
 * and use them in the bootstrap script
 */
final class App_Dispatcher extends Dispatcher {
	public function test() {
		echo $this->uri_string;
	}
}

// End of file: ./system/app_dispatcher.php 