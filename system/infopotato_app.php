<?php
/**
 * InfoPotato Application Class
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

require(dirname(__FILE__).DS.'core'.DS.'infopotato.php');

/**
 * It encapsulates {@link InfoPotato} which provides the actual implementation.
 * By writing your own InfoPotato_App class, you can customize some functionalities of InfoPotato
 */
final class InfoPotato_App extends InfoPotato{
	public function test() {
		echo $this->uri_string;
	}
}

// End of file: ./system/infopotato_app.php 