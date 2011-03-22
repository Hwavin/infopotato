<?php
/**
 * Feed Writer Class
 */
class Feed_Writer_Library {
	/**
	 * @var string The feed version - rss20, rss10, rss092, atom
	 */
	private $format = 'rss20';
	
	/**
	 * @var string The items in feed 'body'
	 */
	private $items = '';
	
	/**
	 * @var string The complete feed content ('head' + 'body')
	 */
	private $feed = '';
	
	/**
	 * Constructor
	 */	
	public function __construct($config = array()) { 
		if (count($config) > 0) {
			$this->initialize($config);
		}
	}
	
	/**
	 * Initialize the user preferences
	 *
	 * @param	array	config preferences
	 */	
	public function initialize($config = array()) { 
		$this->format = $config['format'];
	}
	
	/**
	 * Build complete feed string from feed_data
	 *
	 * @param	array('body' => array(), 'head' => array())
	 * @return	the complete feed string
	 */
	public function write_feed($feed_data = array()) {
		// Build feed body
		foreach ($feed_data['body'] as $value) {
			$this->_add_item($value);
		}
		// Build feed head then put head and body together
		$this->_assemble_feed($feed_data['head']) ;
		
		return $this->feed;
	}
	
	/**
	 * Strip '&', '<', and '>' into XML entities
	 *
	 * @param	string
	 * @return	string
	 */
	private function _strip($s) {
		$s = str_replace('&', '&amp;', $s);
		$s = str_replace('<', '&lt;', $s);
		$s = str_replace('>', '&gt;', $s);
		if ($s == '') {
			$s = ' ';
		}
		return $s;
	}
	
	/**
	 * Add each feed item or entry to feed 'body'
	 *
	 * @param	array('id', 'title', 'link', 'abstract', 'author', 'detail')
	 * @return	void
	 */	
	private function _add_item($a) {
		switch ($this->format) {
			case 'rss092':
				$item = "\t" . '<item>' . "\n";
				$item .= "\t\t" . '<title>' . $this->_strip($a['title']) . '</title>				' . "\n";
				$item .= "\t\t" . '<description><![CDATA[' . $a['abstract'] . ']]></description>' . "\n";
				$item .= "\t\t" . '<link>' . $this->_strip($a['link']) . '</link>' . "\n";
				$item .= "\t" . '</item>' . "\n";
				break;
			case 'rss10':
				$item = "\t" . '<item rdf:about="' . $this->_strip($a['link']) . '">' . "\n";
				$item .= "\t\t" . '<title>' . $this->_strip($a['title']) . '</title>' . "\n";
				$item .= "\t\t" . '<dc:title>' . $this->_strip($a['title']) . '</dc:title>' . "\n";
				$item .= "\t\t" . '<description><![CDATA[' . $a['abstract'] . ']]></description>' . "\n";
				$item .= "\t\t" . '<link>' . $this->_strip($a['link']) . '</link>' . "\n";
				$item .= "\t\t" . '<dc:date>' . substr($a['date'], 0, 4) . '-' . substr($a['date'], 4, 2) . '-' . substr($a['date'], 6, 2) . ' ' . substr($a['date'], 8, 2) . ':' . substr($a['date'], 10, 2) . ':' . substr($a['date'], 12, 4) . '</dc:date>' . "\n";
				$item .= "\t\t" . '<dc:creator>' . $this->_strip($a['author']) . '</dc:creator>' . "\n";
				$item .= "\t" . '</item>' . "\n";
				break;
			case 'rss20':
				$item = "\t" . '<item>' . "\n";
				$item .= "\t\t" . '<title>' . $this->_strip($a['title']) . '</title>' . "\n";
				$item .= "\t\t" . '<link>' . $this->_strip($a['link']) . '</link>' . "\n";
				$item .= "\t\t" . '<pubDate>' . date('D, d M Y H:i:s', $a['date']) . ' GMT</pubDate>' . "\n";
				$item .= "\t\t" . '<description><![CDATA[' . $a['abstract'] . ']]></description>' . "\n";
				$item .= "\t\t" . '<author>' . $this->_strip($a['author']) . '</author>' . "\n";
				$item .= "\t" . '</item>' . "\n";
				break;
			case 'atom':
				$item = "\t" . '<entry>' . "\n";
				$item .= "\t\t" . '<id>' . $this->_strip($a['id']) . '</id>' . "\n";
				$item .= "\t\t" . '<title>' . $this->_strip($a['title']) . '</title>' . "\n";
				$item .= "\t\t" . '<link>' . $this->_strip($a['link']) . '</link>' . "\n";
				$item .= "\t\t" . '<updated>' . substr($a['date'], 0, 4) . '-' . substr($a['date'], 4, 2) . '-' . substr($a['date'], 6, 2) . 'T' . substr($a['date'], 8, 2) . ':' . substr($a['date'], 10, 2) . ':' . substr($a['date'], 12, 4) . '</updated>' . "\n";
				$item .= "\t\t" . '<summary><![CDATA[' . $a['abstract'] . ']]></summary>' . "\n";
				$item .= "\t\t" . '<author>' . "\n";
				$item .= "\t\t\t" . '<name>' . $this->_strip($a['author']) . '</name>' . "\n";
				$item .= "\t\t" . '</author>' . "\n";
				$item .= "\t\t" . '<content><![CDATA[' . $a['detail'] . ']]></content>' . "\n";
				$item .= "\t" . '</entry>' . "\n";
				break;
		}
		$this->items .= $item;
	}
	
	/**
	 * Combine feed 'head' with feed 'body'
	 *
	 * @param	array('title', 'description', 'link', 'date')
	 * @return	void
	 */
	private function _assemble_feed($fa) {
		$this->feed = '<?xml version="1.0" encoding="utf-8"?>' . "\n"; 
		// $fa = func_get_args();
		switch ($this->format) {
			case 'rss092':
				$this->feed .= '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://my.netscape.com/rdf/simple/0.9/">' . "\n";
				$this->feed .= '<channel>' . "\n";
				$this->feed .= "\t" . '<title>' . $this->_strip($fa['title']) . '</title>' . "\n";
				$this->feed .= "\t" . '<description><![CDATA[' . $fa['description'] . ']]></description>' . "\n";
				$this->feed .= "\t" . '<link>' . $this->_strip($fa['link']) . '</link>' . "\n";
				$this->feed .= '</channel>' . "\n";
				$this->feed .= $this->items;
				$this->feed .= '</rdf:RDF>';
				break;
			case 'rss10':
				$this->feed .= '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://purl.org/rss/1.0/" xmlns:dc="http://purl.org/dc/elements/1.1/">' . "\n";
				$this->feed .= '<channel>' . "\n";
				$this->feed .= "\t" . '<title>' . $this->_strip($fa['title']) . '</title>' . "\n";
				$this->feed .= "\t" . '<description><![CDATA[' . $fa['description'] . ']]></description>' . "\n";
				$this->feed .= "\t" . '<link>' . $this->_strip($fa['link']) . '</link>' . "\n";
				$this->feed .= $this->items;
				$this->feed .= '</channel>' . "\n";
				$this->feed .= '</rdf:RDF>' . "\n";
				break;
			// http://cyber.law.harvard.edu/rss/rss.html#hrelementsOfLtitemgt
			case 'rss20':
				$this->feed .= '<rss version="2.0">' . "\n";
				$this->feed .= '<channel>' . "\n";
				$this->feed .= "\t" . '<title>' . $this->_strip($fa['title']) . '</title>' . "\n";
				$this->feed .= "\t" . '<description><![CDATA[' . $fa['description'] . ']]></description>' . "\n";
				$this->feed .= "\t" . '<link>' . $this->_strip($fa['link']) . '</link>' . "\n";
				$this->feed .= "\t" . '<lastBuildDate>' . date('D, d M Y H:i:s', $fa['date']) . ' GMT</lastBuildDate>' . "\n";
				$this->feed .= $this->items;
				$this->feed .= '</channel>' . "\n";
				$this->feed .= '</rss>' . "\n";
				break;
			// http://www.atomenabled.org/developers/syndication/
			case 'atom': 
				$this->feed .= '<feed version="0.3" xmlns="http://purl.org/atom/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xml:lang="zh-cn">' . "\n";
				$this->feed .= "\t" . '<id>http://example.com/</id>' . "\n";
				$this->feed .= "\t" . '<title>' . $this->_strip($fa['title']) . '</title>' . "\n";
				$this->feed .= "\t" . '<link>' . $this->_strip($fa['link']) . '</link>' . "\n";
				$this->feed .= "\t" . '<updated>' . substr($fa['date'], 0, 4) . '-' . substr($fa['date'], 4, 2) . '-' . substr($fa['date'], 6, 2) . 'T' . substr($fa['date'], 8, 2) . ':' . substr($fa['date'], 10, 2) . ':' . substr($fa['date'], 12, 4) . '</updated>' . "\n";
				$this->feed .= $this->items;
				$this->feed .= '</feed>' . "\n";
				break;
		}
	}
} 
 
/* End of file: ./system/libraries/feed_writer/feed_writer_library.php */