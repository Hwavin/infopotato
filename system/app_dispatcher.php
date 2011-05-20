<?php
/**
 * Application Dispatcher Class
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
if ( ! is_readable(SYS_DIR.'core'.DS.'~dispatcher.php')) {
	$content = php_strip_whitespace(dirname(__FILE__).DS.'core'.DS.'dispatcher.php');
	file_put_contents(SYS_DIR.'core'.DS.'~dispatcher.php', $content);
}

require_once SYS_DIR.'core'.DS.'~dispatcher.php';

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