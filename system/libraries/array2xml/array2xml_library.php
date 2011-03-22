<?php
/**
 * The Array to XML class
 *
 */
class Array2xml_Library {
	/**
	 * Convert array to xml tree
	 * 
	 * @param array $array 
	 * @param array $options 
	 * @return string 
	 * @example xmlize()
	 */
	static public function xmlize($array, $options = null) {
		$encoding = isset($options['encoding']) ? $options[$encoding] :'utf-8';
		$root = isset($options['root']) ? $options['root'] : 'response';
		$xml = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>\n<$root>\n";
		$xml .= self::_xmlize($array);
		$xml .= "</$root>";
		return $xml;
	}

	/**
	 * Convert array element to xml string
	 *
	 * @param mixed $array
	 * @return string
	 */
	static private function _xmlize($array) {
		$string = '';
		foreach ($array as $key => $value) {
			$stag = is_numeric($key) ? 'item id="' . $key . '"' : $key;
			$etag = is_numeric($key) ? 'item' : $key;
			if (is_array($value)) {
				$string .= '<' . $stag . ">\n" . self::_xmlize($value) . '</' . $etag . ">\n";
			} else {
				$string .= '<' . $stag . '>' . $value . '</' . $etag . ">\n";
			}
		}
		return $string;
	}
}
 
/* End of file: ./system/libraries/array2xml_library.php */