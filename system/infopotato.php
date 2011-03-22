<?php
/**
 * Request_Dispatcher subclass file.
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

require(dirname(__FILE__).'/core/request_dispatcher.php');

/**
 * It encapsulates {@link Request_Dispatcher} which provides the actual implementation.
 * By writing your own InfoPotato class, you can customize some functionalities of Request_Dispatcher.
 */
class InfoPotato extends Request_Dispatcher{}
