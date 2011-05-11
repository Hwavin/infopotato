<?php
/**
 * Application Dispatcher Class
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

require_once dirname(__FILE__).DS.'core'.DS.'dispatcher.php';

/**
 * It encapsulates {@link Dispatcher} which provides the actual implementation.
 * By writing your own App_Dispatcher class, you can customize some functionalities of Dispatcher
 */
final class App_Dispatcher extends Dispatcher {
	public function test() {
		echo $this->uri_string;
	}
}

// End of file: ./system/app_dispatcher.php 