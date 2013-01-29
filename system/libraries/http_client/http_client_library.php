<?php
/**
 * PHP HTTP client
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://snoopy.sourceforge.net/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class HTTP_Client_Library {
	/**** Public variables ****/
	
	/* user definable vars */

	/**
	 * Host name we are connecting to 
	 *
	 * @var  string  
	 */
	public $host = ''; 
	
	/**
	 * port we are connecting to
	 *
	 * @var  integer
	 */
	public $port = 80;
	
	/**
	 * proxy host to use
	 *
	 * @var  string  
	 */
	public $proxy_host = ''; 
	
	/**
	 * proxy port to use 
	 *
	 * @var  string  
	 */
	 public $proxy_port = '';
	
	/**
	 * proxy user to use
	 *
	 * @var  string  
	 */
	public $proxy_user = '';
	
	/**
	 * proxy password to use
	 *
	 * @var  string  
	 */
	public $proxy_pass = '';
	
	/**
	 * agent we masquerade as
	 *
	 * @var  string  
	 */
	public $agent =	'InfoPotato HTTP Client';
	
	/**
	 * referer info to pass
	 *
	 * @var  string  
	 */
	public $referer = '';
	
	/**
	 * array of cookies to pass
	 *
	 * @var  array  
	 * $cookies["username"]="joe";
	 */
	public $cookies = array();
	
	/**
	 * array of raw headers to send
	 *
	 * @var  array  
	 */
	public $raw_headers = array();	

	/**
	 * http redirection depth maximum. 0 = disallow
	 *
	 * @var  integer  
	 */
	public $max_redirs =	5;
	
	/**
	 * contains address of last redirected address
	 *
	 * @var  string  
	 */
	public $last_redirect_addr = '';
	
	/**
	 * allows redirection off-site
	 *
	 * @var  boolean  
	 */
	public $offsite_ok =	TRUE;
	
	/**
	 * frame content depth maximum. 0 = disallow
	 *
	 * @var  integer  
	 */
	public $max_frames =	0;
	
	/**
	 * expand links to fully qualified URLs. this only applies to fetch_links() submit_links(), and submit_text()
	 *
	 * @var  boolean  
	 */
	public $expand_links = TRUE;	
	
	/**
	 * pass set cookies back through redirects
	 *
	 * @var  boolean  
	 * NOTE: this currently does not respect dates, domains or paths.
	 */
	public $pass_cookies	= TRUE;	

	/**
	 * user for http authentication
	 *
	 * @var  string  
	 */
	public $user = '';
	
	/**
	 * password for http authentication
	 *
	 * @var  string  
	 */
	public $pass = '';
	
	/**
	 * http accept types
	 *
	 * @var  string  
	 */
	public $accept = 'image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*';
	
	/**
	 * where the content is put
	 *
	 * @var  string  
	 */
	public $results = '';
		
	/**
	 * error messages sent here
	 *
	 * @var  string  
	 */
	public $error =	'';
	
	/**
	 * response code returned from server
	 *
	 * @var  string  
	 */
	public $response_code =	'';
	
	/**
	 * headers returned from server sent here
	 *
	 * @var  array  
	 */
	public $headers	= array();
	
	/**
	 * max return data length (body)
	 *
	 * @var  integer  
	 */
	public $max_length =	500000;
	
	/**
	 * timeout on read operations, in seconds
	 *
	 * @var  integer
	 */
	public $read_timeout = 0;	
	
	/**
	 * set to 0 to disallow timeouts if a read operation timed out
	 *
	 * @var  boolean
	 */										
	public $timed_out =	FALSE;
	
	/**
	 * http request status
	 *
	 * @var  string  
	 */
	public $status = 0;

	/**
	 * temporary directory that the webserver has permission to write to. under Windows, this should be C:\temp
	 *
	 * @var  string  
	 */
	public $temp_dir = '/tmp';
	
	/**
	 * Snoopy will use cURL for fetching SSL content if a full system path to
	 * the cURL binary is supplied here. set to FALSE if you do not have
	 * cURL installed. See http://curl.haxx.se for details on installing cURL.
	 * Snoopy does *not* use the cURL library functions built into php,
	 * as these functions are not stable as of this Snoopy release.
	 *
	 * @var  string  http accept types
	 */
	public $curl_path = '/usr/local/bin/curl';
	
	
	/**** Private variables ****/	
	
	/**
	 * max line length (headers)
	 *
	 * @var  integer  
	 */
	private	$_max_line_len = 4096;
	
	/**
	 * default http request method
	 *
	 * @var  string  
	 */
	private $_http_method = 'GET';
	
	/**
	 * default http request version
	 *
	 * @var  string  
	 */
	private $_http_version =	'HTTP/1.0';
	
	/**
	 * default submit method
	 *
	 * @var  string  
	 */
	private $_submit_method	= 'POST';
	
	/**
	 * default submit type
	 *
	 * @var  string  
	 */
	private $_submit_type =	'application/x-www-form-urlencoded';
	
	/**
	 * MIME boundary for multipart/form-data submit type
	 *
	 * @var  string  
	 */
	private $_mime_boundary	= '';
	
	/**
	 * will be set if page fetched is a redirect
	 *
	 * @var  boolean  
	 */
	private $_redirect_addr = FALSE;
	
	/**
	 * increments on an http redirect
	 *
	 * @var  integer  
	 */
	private $_redirect_depth	= 0;
	
	/**
	 * frame src urls
	 *
	 * @var  string  
	 */
	private $_frame_urls = array();
	
	/**
	 * increments on frame depth
	 *
	 * @var  integer  
	 */
	private $_frame_depth = 0;
	
	/**
	 * set if using a proxy server
	 *
	 * @var  boolean  
	 */
	private $_is_proxy =	FALSE;
	
	/**
	 * timeout for socket connection
	 *
	 * @var  integer  
	 */
	private $_fp_timeout = 30;

	
	/**
	 * Constructor
	 */
	public function __construct($config = array()) {
		if (count($config) > 0) {
			foreach ($config as $key => $val) {
				if (isset($key)) {
					$this->$key = $val;
				}
			}
		}
	}
	

	/**
	 * Fetch the contents of a web page
	 * 
	 * (and possibly other protocols in the future like ftp, nntp, gopher, etc.)
	 * 
	 * Input:		$uri	the location of the page to fetch
	 * Output:		$this->results	the output text from the fetch
	 *
	 * @param	string	$uri the location of the page to fetch
	 * @param	integer	the month
	 * @return	boolean
	 */
	public function fetch($uri) {
		//preg_match("|^([^:]+)://([^:/]+)(:[\d]+)*(.*)|", $uri, $uri_parts);
		$uri_parts = parse_url($uri);
		if ( ! empty($uri_parts["user"])) {
			$this->user = $uri_parts["user"];
		}
		
		if ( ! empty($uri_parts["pass"])) {
			$this->pass = $uri_parts["pass"];
		}
		
		if (empty($uri_parts["query"])) {
			$uri_parts["query"] = '';
		} 
		
		if (empty($uri_parts["path"])) {
			$uri_parts["path"] = '';
		}
		
		switch (strtolower($uri_parts['scheme'])) {
			case 'http':
				$this->host = $uri_parts['host'];
				if ( ! empty($uri_parts['port']))
					$this->port = $uri_parts['port'];
				if ($this->_connect($fp)) {
					if ($this->_is_proxy) {
						// using proxy, send entire uri
						$this->_http_request($uri, $fp, $uri, $this->_http_method);
					} else {
						$path = $uri_parts['path'].($uri_parts['query'] ? "?".$uri_parts['query'] : '');
						// no proxy, send only the path
						$this->_http_request($path, $fp, $uri, $this->_http_method);
					}
					
					$this->_disconnect($fp);

					if ($this->_redirect_addr) {
						/* url was redirected, check if we've hit the max depth */
						if ($this->max_redirs > $this->_redirect_depth) {
							// only follow redirect if it's on this site, or offsite_ok is true
							if (preg_match("|^http://".preg_quote($this->host)."|i", $this->_redirect_addr) || $this->offsite_ok) {
								/* follow the redirect */
								$this->_redirect_depth++;
								$this->last_redirect_addr = $this->_redirect_addr;
								$this->fetch($this->_redirect_addr);
							}
						}
					}

					if ($this->_frame_depth < $this->max_frames && count($this->_frame_urls) > 0) {
						$frameurls = $this->_frame_urls;
						$this->_frame_urls = array();
						
						while (list(, $frame_url) = each($frameurls)) {
							if ($this->_frame_depth < $this->max_frames) {
								$this->fetch($frame_url);
								$this->_frame_depth++;
							}
							else
								break;
						}
					}					
				} else {
					return FALSE;
				}
				return TRUE;					
				break;
			
			case "https":
				if ( ! $this->curl_path)
					return FALSE;
				if (function_exists("is_executable"))
				    if ( ! is_executable($this->curl_path))
				        return FALSE;
				$this->host = $uri_parts['host'];
				if ( ! empty($uri_parts['port']))
					$this->port = $uri_parts['port'];
				if ($this->_is_proxy) {
					// using proxy, send entire uri
					$this->_https_request($uri,$uri,$this->_http_method);
				} else {
					$path = $uri_parts['path'].($uri_parts['query'] ? "?".$uri_parts['query'] : '');
					// no proxy, send only the path
					$this->_https_request($path, $uri, $this->_http_method);
				}

				if  ($this->_redirect_addr) {
					/* url was redirected, check if we've hit the max depth */
					if ($this->max_redirs > $this->_redirect_depth) {
						// only follow redirect if it's on this site, or offsite_ok is true
						if (preg_match("|^http://".preg_quote($this->host)."|i", $this->_redirect_addr) || $this->offsite_ok) {
							/* follow the redirect */
							$this->_redirect_depth++;
							$this->last_redirect_addr = $this->_redirect_addr;
							$this->fetch($this->_redirect_addr);
						}
					}
				}

				if ($this->_frame_depth < $this->max_frames && count($this->_frame_urls) > 0) {
					$frameurls = $this->_frame_urls;
					$this->_frame_urls = array();

					while (list(, $frame_url) = each($frameurls)) {
						if ($this->_frame_depth < $this->max_frames) {
							$this->fetch($frame_url);
							$this->_frame_depth++;
						}
						else
							break;
					}
				}					
				return TRUE;					
				break;
			
			default:
				// not a valid protocol
				$this->error = 'Invalid protocol "'.$uri_parts['scheme'].'"\n';
				return FALSE;
				break;
		}		
		return TRUE;
	}


	/**
	 * Submit an http form
	 * 
	 * (and possibly other protocols in the future like ftp, nntp, gopher, etc.)
	 * 
	 * Input:		$uri	the location to post the data
	 * $form_vars the form_vars to use. (format: $form_vars["var"] = "val";)
	 * $form_files  an array of files to submit (format: $form_files["var"] = "/dir/filename.ext";)
	 * Output:		$this->results	the text output from the post
	 *
	 * @param	string	$uri the location of the page to fetch
	 * @return	boolean
	 */
	public function submit($uri, $form_vars = '', $form_files = '') {
		unset($post_data);
		
		$post_data = $this->_prepare_post_body($form_vars, $form_files);
			
		$uri_parts = parse_url($uri);
		if ( ! empty($uri_parts['user'])) {
			$this->user = $uri_parts['user'];
		}
		
		if ( ! empty($uri_parts['pass'])) {
			$this->pass = $uri_parts['pass'];
		}
		
		if (empty($uri_parts['query'])) {
			$uri_parts['query'] = '';
		}
		
		if (empty($uri_parts['path'])) {
			$uri_parts['path'] = '';
		}
		
		switch(strtolower($uri_parts['scheme'])) {
			case "http":
				$this->host = $uri_parts['host'];
				if ( ! empty($uri_parts['port']))
					$this->port = $uri_parts['port'];
				if ($this->_connect($fp)) {
					if ($this->_is_proxy) {
						// using proxy, send entire uri
						$this->_http_request($uri, $fp, $uri, $this->_submit_method,$this->_submit_type, $post_data);
					} else {
						$path = $uri_parts['path'].($uri_parts['query'] ? '?'.$uri_parts['query'] : '');
						// no proxy, send only the path
						$this->_http_request($path, $fp, $uri, $this->_submit_method, $this->_submit_type, $post_data);
					}
					
					$this->_disconnect($fp);

					if ($this->_redirect_addr) {
						/* url was redirected, check if we've hit the max depth */
						if ($this->max_redirs > $this->_redirect_depth) {						
							if ( ! preg_match("|^".$uri_parts['scheme']."://|", $this->_redirect_addr))
								$this->_redirect_addr = $this->_expand_links($this->_redirect_addr, $uri_parts['scheme']."://".$uri_parts['host']);						
							
							// only follow redirect if it's on this site, or offsite_ok is true
							if (preg_match("|^http://".preg_quote($this->host)."|i", $this->_redirect_addr) || $this->offsite_ok) {
								/* follow the redirect */
								$this->_redirect_depth++;
								$this->last_redirect_addr = $this->_redirect_addr;
								if( strpos( $this->_redirect_addr, '?' ) > 0 )
									$this->fetch($this->_redirect_addr); // the redirect has changed the request method from post to get
								else
									$this->submit($this->_redirect_addr, $form_vars, $form_files);
							}
						}
					}

					if ($this->_frame_depth < $this->max_frames && count($this->_frame_urls) > 0) {
						$frame_urls = $this->_frame_urls;
						$this->_frame_urls = array();
						
						while (list(, $frame_url) = each($frame_urls)) {														
							if ($this->_frame_depth < $this->max_frames) {
								$this->fetch($frame_url);
								$this->_frame_depth++;
							}
							else
								break;
						}
					}					
					
				} else {
					return FALSE;
				}
				return TRUE;					
				break;
			
			case "https":
				if ( ! $this->curl_path)
					return FALSE;
				if (function_exists("is_executable"))
				    if ( ! is_executable($this->curl_path))
				        return FALSE;
				$this->host = $uri_parts['host'];
				if ( ! empty($uri_parts['port']))
					$this->port = $uri_parts['port'];
				if ($this->_is_proxy) {
					// using proxy, send entire uri
					$this->_https_request($uri, $uri, $this->_submit_method, $this->_submit_type, $post_data);
				} else {
					$path = $uri_parts['path'].($uri_parts['query'] ? '?'.$uri_parts['query'] : '');
					// no proxy, send only the path
					$this->_https_request($path, $uri, $this->_submit_method, $this->_submit_type, $post_data);
				}

				if ($this->_redirect_addr) {
					/* url was redirected, check if we've hit the max depth */
					if ($this->max_redirs > $this->_redirect_depth) {						
						if ( ! preg_match("|^".$uri_parts['scheme']."://|", $this->_redirect_addr))
							$this->_redirect_addr = $this->_expand_links($this->_redirect_addr, $uri_parts['scheme']."://".$uri_parts['host']);						

						// only follow redirect if it's on this site, or offsite_ok is true
						if (preg_match("|^http://".preg_quote($this->host)."|i", $this->_redirect_addr) || $this->offsite_ok) {
							/* follow the redirect */
							$this->_redirect_depth++;
							$this->last_redirect_addr = $this->_redirect_addr;
							if ( strpos( $this->_redirect_addr, '?' ) > 0 )
								$this->fetch($this->_redirect_addr); // the redirect has changed the request method from post to get
							else
								$this->submit($this->_redirect_addr, $form_vars, $form_files);
						}
					}
				}

				if ($this->_frame_depth < $this->max_frames && count($this->_frame_urls) > 0) {
					$frame_urls = $this->_frame_urls;
					$this->_frame_urls = array();

					while (list(, $frame_url) = each($frame_urls)) {														
						if ($this->_frame_depth < $this->max_frames) {
							$this->fetch($frame_url);
							$this->_frame_depth++;
						}
						else
							break;
					}
				}					
				return TRUE;					
				break;
				
			default:
				// not a valid protocol
				$this->error = 'Invalid protocol "'.$uri_parts["scheme"].'"\n';
				return FALSE;
				break;
		}		
		return TRUE;
	}


	/**
	 * Fetch the links from a web page
	 * 
	 * (and possibly other protocols in the future like ftp, nntp, gopher, etc.)
	 * 
	 * Input:		$uri	where you are fetching from
	 * Output:		$this->results	an array of the URLs
	 *
	 * @param	string	$uri the location of the page to fetch
	 * @return	boolean
	 */
	public function fetch_links($uri) {
		if ($this->fetch($uri)) {			
			if ($this->last_redirect_addr)
				$uri = $this->last_redirect_addr;
			if (is_array($this->results)) {
				for($x = 0; $x < count($this->results); $x++)
					$this->results[$x] = $this->_strip_links($this->results[$x]);
			} else {
				$this->results = $this->_strip_links($this->results);
			}
			if ($this->expand_links) {
				$this->results = $this->_expand_links($this->results, $uri);
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}


	/**
	 * Fetch the form elements from a web page
	 * 
	 * (and possibly other protocols in the future like ftp, nntp, gopher, etc.)
	 * 
	 * Input:		$uri	where you are fetching from
	 * Output:		$this->results	the resulting html form
	 *
	 * @param	string	$uri the location of the page to fetch
	 * @return	boolean
	 */
	public function fetch_form($uri) {
		if ($this->fetch($uri)) {			
			if (is_array($this->results)) {
				for($x = 0; $x < count($this->results); $x++)
					$this->results[$x] = $this->_strip_form($this->results[$x]);
			} else {
				$this->results = $this->_strip_form($this->results);
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}
	

	/**
	 * Fetch the text from a web page, stripping the links
	 * 
	 * Input:		$uri	where you are fetching from
	 * Output:		$this->results	the text from the web page
	 *
	 * @param	string	$uri the location of the page to fetch
	 * @return	boolean
	 */
	public function fetch_text($uri) {
		if ($this->fetch($uri)) {			
			if (is_array($this->results)) {
				for($x = 0; $x < count($this->results); $x++)
					$this->results[$x] = $this->_strip_text($this->results[$x]);
			} else {
				$this->results = $this->_strip_text($this->results);
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}


	/**
	 * Grab links from a form submission
	 * 
	 * Input:		$uri	where you are submitting from
	 * Output:		$this->results	an array of the links from the post
	 *
	 * @param	
	 * @return	boolean
	 */
	public function submit_links($uri, $form_vars = '', $form_files = '') {
		if ($this->submit($uri, $form_vars, $form_files)) {			
			if ($this->last_redirect_addr)
				$uri = $this->last_redirect_addr;
			if (is_array($this->results)) {
				for ($x = 0; $x < count($this->results); $x++) {
					$this->results[$x] = $this->_strip_links($this->results[$x]);
					if ($this->expand_links)
						$this->results[$x] = $this->_expand_links($this->results[$x], $uri);
				}
			} else {
				$this->results = $this->_strip_links($this->results);
				if ($this->expand_links) {
					$this->results = $this->_expand_links($this->results, $uri);
				}
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}


	/**
	 * Grab text from a form submission
	 * 
	 * Input:		$uri	where you are submitting from
	 * Output:		$this->results	the text from the web page
	 *
	 * @param	
	 * @return	boolean
	 */
	public function submit_text($uri, $form_vars = '', $form_files = '') {
		if ($this->submit($uri, $form_vars, $form_files)) {			
			if ($this->last_redirect_addr) {
				$uri = $this->last_redirect_addr;
			}
			
			if (is_array($this->results)) {
				for ($x = 0; $x < count($this->results); $x++) {
					$this->results[$x] = $this->_strip_text($this->results[$x]);
					if ($this->expand_links) {
						$this->results[$x] = $this->_expand_links($this->results[$x], $uri);
					}
				}
			} else {
				$this->results = $this->_strip_text($this->results);
				if($this->expand_links) {
					$this->results = $this->_expand_links($this->results, $uri);
				}
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}


	/**
	 * Set the form submission content type to multipart/form-data
	 */
	public function set_submit_multipart() {
		$this->_submit_type = "multipart/form-data";
	}


	/**
	 * Set the form submission content type to application/x-www-form-urlencoded
	 */
	public function set_submit_normal() {
		$this->_submit_type = "application/x-www-form-urlencoded";
	}


/*======================================================================*\
	Private functions
\*======================================================================*/

	/**
	 * Strip the hyperlinks from an html document
	 * 
	 * Input:		$document	document to strip.
	 * Output:		$match		an array of the links
	 *
	 * @param	
	 * @return	
	 */
	private function _strip_links($document) {	
		preg_match_all("'<\s*a\s.*?href\s*=\s*			# find <a href=
						([\"\'])?					# find single or double quote
						(?(1) (.*?)\\1 | ([^\s\>]+))		# if quote found, match up to next matching
													# quote, otherwise match up to next space
						'isx", $document, $links);
						

		// catenate the non-empty matches from the conditional subpattern

		while (list($key, $val) = each($links[2])) {
			if ( ! empty($val)) {
				$match[] = $val;
			}
		}				
		
		while (list($key, $val) = each($links[3])) {
			if ( ! empty($val)) {
				$match[] = $val;
			}
		}		
		
		// return the links
		return $match;
	}


	/**
	 * Strip the form elements from an html document
	 * 
	 * Input:		$document	document to strip.
	 * Output:		$match		an array of the links
	 *
	 * @param	
	 * @return	
	 */
	private function _strip_form($document) {	
		preg_match_all("'<\/?(FORM|INPUT|SELECT|TEXTAREA|(OPTION))[^<>]*>(?(2)(.*(?=<\/?(option|select)[^<>]*>[\r\n]*)|(?=[\r\n]*))|(?=[\r\n]*))'Usi",$document,$elements);
		
		// catenate the matches
		$match = implode("\r\n", $elements[0]);
				
		// return the links
		return $match;
	}


	/**
	 * Strip the text from an html document
	 * 
	 * Input:		$document	document to strip.
	 * Output:		$text		the resulting text
	 *
	 * @param	
	 * @return	
	 */
	private function _strip_text($document) {
		
		// I didn't use preg eval (//e) since that is only available in PHP 4.0.
		// so, list your entities one by one here. I included some of the
		// more common ones.
								
		$search = array(
			"'<script[^>]*?>.*?</script>'si",	// strip out javascript
			"'<[\/\!]*?[^<>]*?>'si",			// strip out html tags
			"'([\r\n])[\s]+'",					// strip out white space
			"'&(quot|#34|#034|#x22);'i",		// replace html entities
			"'&(amp|#38|#038|#x26);'i",			// added hexadecimal values
			"'&(lt|#60|#060|#x3c);'i",
			"'&(gt|#62|#062|#x3e);'i",
			"'&(nbsp|#160|#xa0);'i",
			"'&(iexcl|#161);'i",
			"'&(cent|#162);'i",
			"'&(pound|#163);'i",
			"'&(copy|#169);'i",
			"'&(reg|#174);'i",
			"'&(deg|#176);'i",
			"'&(#39|#039|#x27);'",
			"'&(euro|#8364);'i",				// europe
			"'&a(uml|UML);'",					// german
			"'&o(uml|UML);'",
			"'&u(uml|UML);'",
			"'&A(uml|UML);'",
			"'&O(uml|UML);'",
			"'&U(uml|UML);'",
			"'&szlig;'i",
		);
		
		$replace = array(	
			"",
			"",
			"\\1",
			"\"",
			"&",
			"<",
			">",
			" ",
			chr(161),
			chr(162),
			chr(163),
			chr(169),
			chr(174),
			chr(176),
			chr(39),
			chr(128),
			"ä",
			"ö",
			"ü",
			"Ä",
			"Ö",
			"Ü",
			"ß",
		);
					
		$text = preg_replace($search, $replace, $document);
								
		return $text;
	}


	/**
	 * Expand each link into a fully qualified URL
	 * 
	 * Input:		$links			the links to qualify
	 * $uri			the full uri to get the base from
	 * Output:		$expanded_links	the expanded links
	 *
	 * @param	
	 * @return	
	 */
	private function _expand_links($links, $uri) {
		
		preg_match("/^[^\?]+/", $uri, $match);

		$match = preg_replace("|/[^\/\.]+\.[^\/\.]+$|","",$match[0]);
		$match = preg_replace("|/$|", "", $match);
		$match_part = parse_url($match);
		$match_root =
		$match_part["scheme"]."://".$match_part["host"];
				
		$search = array( 	
			"|^http://".preg_quote($this->host)."|i",
			"|^(\/)|i",
			"|^(?!http://)(?!mailto:)|i",
			"|/\./|",
			"|/[^\/]+/\.\./|"
		);
						
		$replace = array(	
			"",
			$match_root."/",
			$match."/",
			"/",
			"/"
		);			
				
		$expanded_links = preg_replace($search, $replace, $links);

		return $expanded_links;
	}


	/**
	 * Go get the http data from the server
	 * 
	 * Input:		$url		the url to fetch
	 * $fp			the current open file pointer
	 * $uri		the full uri
	 * $body		body contents to send if any (POST)
	 *
	 * @param	
	 * @return	
	 */
	private function _http_request($url, $fp, $uri, $http_method, $content_type = "", $body = "") {
		$cookie_headers = '';
		if ($this->pass_cookies && $this->_redirect_addr)
			$this->setcookies();
			
		$uri_parts = parse_url($uri);
		if (empty($url)) {
			$url = "/";
		}
		$headers = $http_method." ".$url." ".$this->_http_version."\r\n";		
		if ( ! empty($this->agent)) {
			$headers .= "User-Agent: ".$this->agent."\r\n";
		}
		
		if ( ! empty($this->host) && !isset($this->raw_headers['Host'])) {
			$headers .= "Host: ".$this->host;
			if( ! empty($this->port)) {
				$headers .= ":".$this->port;
			}
			$headers .= "\r\n";
		}
		
		if ( ! empty($this->accept)) {
			$headers .= "Accept: ".$this->accept."\r\n";
		}
		
		if( ! empty($this->referer)) {
			$headers .= "Referer: ".$this->referer."\r\n";
		}
		
		if ( ! empty($this->cookies)) {			
			if(!is_array($this->cookies)) {
				$this->cookies = (array)$this->cookies;
			}
			reset($this->cookies);
			if ( count($this->cookies) > 0 ) {
				$cookie_headers .= 'Cookie: ';
				foreach ( $this->cookies as $cookie_key => $cookie_val ) {
					$cookie_headers .= $cookie_key."=".urlencode($cookie_val)."; ";
				}
				$headers .= substr($cookie_headers,0,-2) . "\r\n";
			} 
		}
		
		if ( ! empty($this->raw_headers)) {
			if ( ! is_array($this->raw_headers)) {
				$this->raw_headers = (array)$this->raw_headers;
			}
			while (list($header_key, $header_val) = each($this->raw_headers)) {
				$headers .= $header_key.": ".$header_val."\r\n";
			}
		}
		
		if ( ! empty($content_type)) {
			$headers .= "Content-type: $content_type";
			if ($content_type == "multipart/form-data") {
				$headers .= "; boundary=".$this->_mime_boundary;
			}
			$headers .= "\r\n";
		}
		
		if ( ! empty($body)) {
			$headers .= "Content-length: ".strlen($body)."\r\n";
		}
		
		if ( ! empty($this->user) || ! empty($this->pass)) {
			$headers .= "Authorization: Basic ".base64_encode($this->user.":".$this->pass)."\r\n";
		}
		
		//add proxy auth headers
		if ( ! empty($this->proxy_user)) {
			$headers .= 'Proxy-Authorization: ' . 'Basic ' . base64_encode($this->proxy_user . ':' . $this->proxy_pass)."\r\n";
		}

		$headers .= "\r\n";
		
		// set the read timeout if needed
		if ($this->read_timeout > 0) {
			socket_set_timeout($fp, $this->read_timeout);
		}
		$this->timed_out = FALSE;
		
		fwrite($fp, $headers.$body, strlen($headers.$body));
		
		$this->_redirect_addr = FALSE;
		unset($this->headers);
						
		while ($current_header = fgets($fp,$this->_max_line_len)) {
			if ($this->read_timeout > 0 && $this->_check_timeout($fp)) {
				$this->status = -100;
				return FALSE;
			}
				
			if ($current_header == "\r\n") {
				break;
			}
			
			// if a header begins with Location: or URI:, set the redirect
			if (preg_match("/^(Location:|URI:)/i", $current_header)) {
				// get URL portion of the redirect
				preg_match("/^(Location:|URI:)[ ]+(.*)/i", chop($current_header), $matches);
				// look for :// in the Location header to see if hostname is included
				if ( ! preg_match("|\:\/\/|",$matches[2])) {
					// no host in the path, so prepend
					$this->_redirect_addr = $uri_parts["scheme"]."://".$this->host.":".$this->port;
					// eliminate double slash
					if ( ! preg_match("|^/|",$matches[2])) {
							$this->_redirect_addr .= "/".$matches[2];
					} else {
							$this->_redirect_addr .= $matches[2];
					}
				} else {
					$this->_redirect_addr = $matches[2];
				}
			}
		
			if (preg_match("|^HTTP/|", $current_header)) {
                if (preg_match("|^HTTP/[^\s]*\s(.*?)\s|", $current_header, $status)) {
					$this->status= $status[1];
                }				
				$this->response_code = $current_header;
			}
				
			$this->headers[] = $current_header;
		}

		$results = '';
		do {
    		$_data = fread($fp, $this->max_length);
    		if (strlen($_data) == 0) {
        		break;
    		}
    		$results .= $_data;
		} while (TRUE);

		if ($this->read_timeout > 0 && $this->_check_timeout($fp)) {
			$this->status=-100;
			return FALSE;
		}
		
		// check if there is a a redirect meta tag
		
		if (preg_match("'<meta[\s]*http-equiv[^>]*?content[\s]*=[\s]*[\"\']?\d+;[\s]*URL[\s]*=[\s]*([^\"\']*?)[\"\']?>'i", $results, $match)) {
			$this->_redirect_addr = $this->_expand_links($match[1], $uri);	
		}

		// have we hit our frame depth and is there frame src to fetch?
		if (($this->_frame_depth < $this->max_frames) && preg_match_all("'<frame\s+.*src[\s]*=[\'\"]?([^\'\"\>]+)'i", $results, $match)) {
			$this->results[] = $results;
			for ($x=0; $x<count($match[1]); $x++)
				$this->_frame_urls[] = $this->_expand_links($match[1][$x], $uri_parts['scheme']."://".$this->host);
		}
		// have we already fetched framed content?
		elseif (is_array($this->results)) {
			$this->results[] = $results;
		}
		// no framed content
		else {
			$this->results = $results;
		}
		return TRUE;
	}


	
	/**
	 * Go get the https data from the server using curl
	 * 
	 * Input:		$url		the url to fetch
	 * $URI		the full URI
	 * $body		body contents to send if any (POST)
	 * 
	 *
	 * @param	
	 * @return	
	 */
	private function _https_request($url, $uri, $http_method, $content_type = '',$body = '') {  
		if ($this->pass_cookies && $this->_redirect_addr) {
			$this->setcookies();
		}
		$headers = array();		
					
		$uri_parts = parse_url($uri);
		if (empty($url)) {
			$url = "/";
		}
		// GET ... header not needed for curl
		//$headers[] = $http_method." ".$url." ".$this->_http_version;		
		if ( ! empty($this->agent)) {
			$headers[] = "User-Agent: ".$this->agent;
		}
		
		if ( ! empty($this->host)) {
			if ( ! empty($this->port)) {
				$headers[] = "Host: ".$this->host.":".$this->port;
			} else {
				$headers[] = "Host: ".$this->host;
			}
		}
		
		if ( ! empty($this->accept)) {
			$headers[] = "Accept: ".$this->accept;
		}
		
		if ( ! empty($this->referer)) {
			$headers[] = "Referer: ".$this->referer;
		}
		
		if ( ! empty($this->cookies)) {			
			if ( ! is_array($this->cookies)) {
				$this->cookies = (array)$this->cookies;
			}
			reset($this->cookies);
			if ( count($this->cookies) > 0 ) {
				$cookie_str = 'Cookie: ';
				foreach ($this->cookies as $cookie_key => $cookie_val ) {
					$cookie_str .= $cookie_key."=".urlencode($cookie_val)."; ";
				}
				$headers[] = substr($cookie_str, 0, -2);
			}
		}
		
		if ( ! empty($this->raw_headers)) {
			if ( ! is_array($this->raw_headers)) {
				$this->raw_headers = (array)$this->raw_headers;
			}
			while(list($header_key, $header_val) = each($this->raw_headers)) {
				$headers[] = $header_key.": ".$header_val;
			}
		}
		
		if ( ! empty($content_type)) {
			if ($content_type == "multipart/form-data") {
				$headers[] = "Content-type: $content_type; boundary=".$this->_mime_boundary;
			} else {
				$headers[] = "Content-type: $content_type";
			}
		}
		
		if ( ! empty($body)) {
			$headers[] = "Content-length: ".strlen($body);
		}
		
		if ( ! empty($this->user) || ! empty($this->pass)) {
			$headers[] = "Authorization: BASIC ".base64_encode($this->user.":".$this->pass);
		}
		
		for ($curr_header = 0; $curr_header < count($headers); $curr_header++) {
			$safer_header = strtr( $headers[$curr_header], "\"", " " );
			$cmdline_params .= " -H \"".$safer_header."\"";
		}
		
		if ( ! empty($body)) {
			$cmdline_params .= " -d \"$body\"";
		}
		
		if ( $this->read_timeout > 0) {
			$cmdline_params .= " -m ".$this->read_timeout;
		}
		
		$header_file = tempnam($temp_dir, "sno");

		exec($this->curl_path." -k -D \"$header_file\"".$cmdline_params." \"".escapeshellcmd($uri)."\"",$results,$return);
		
		if ($return) {
			$this->error = "Error: cURL could not retrieve the document, error $return.";
			return FALSE;
		}
			
			
		$results = implode("\r\n", $results);
		
		$result_headers = file("$header_file");
						
		$this->_redirect_addr = FALSE;
		unset($this->headers);
						
		for ($current_header = 0; $current_header < count($result_headers); $current_header++) {
			
			// if a header begins with Location: or URI:, set the redirect
			if (preg_match("/^(Location: |URI: )/i", $result_headers[$current_header])) {
				// get URL portion of the redirect
				preg_match("/^(Location: |URI:)\s+(.*)/",chop($result_headers[$current_header]), $matches);
				// look for :// in the Location header to see if hostname is included
				if ( ! preg_match("|\:\/\/|",$matches[2])) {
					// no host in the path, so prepend
					$this->_redirect_addr = $uri_parts["scheme"]."://".$this->host.":".$this->port;
					// eliminate double slash
					if ( ! preg_match("|^/|",$matches[2])) {
						$this->_redirect_addr .= "/".$matches[2];
					} else {
						$this->_redirect_addr .= $matches[2];
					}
				} else {
					$this->_redirect_addr = $matches[2];
				}
			}
		
			if (preg_match("|^HTTP/|",$result_headers[$current_header]))
				$this->response_code = $result_headers[$current_header];

			$this->headers[] = $result_headers[$current_header];
		}

		// check if there is a a redirect meta tag
		
		if (preg_match("'<meta[\s]*http-equiv[^>]*?content[\s]*=[\s]*[\"\']?\d+;[\s]*URL[\s]*=[\s]*([^\"\']*?)[\"\']?>'i", $results, $match)) {
			$this->_redirect_addr = $this->_expand_links($match[1], $uri);	
		}

		// have we hit our frame depth and is there frame src to fetch?
		if (($this->_frame_depth < $this->max_frames) && preg_match_all("'<frame\s+.*src[\s]*=[\'\"]?([^\'\"\>]+)'i", $results, $match)) {
			$this->results[] = $results;
			for($x = 0; $x < count($match[1]); $x++) {
				$this->_frame_urls[] = $this->_expand_links($match[1][$x], $uri_parts["scheme"]."://".$this->host);
			}
		}
		// have we already fetched framed content?
		elseif (is_array($this->results)) {
			$this->results[] = $results;
		} // no framed content
		else {
			$this->results = $results;
		}

		unlink("$header_file");
		
		return TRUE;
	}

	
	/**
	 * set cookies for a redirection
	 * 
	 * @param	
	 * @return	
	 */
	public function setcookies() {
		for ($x = 0; $x < count($this->headers); $x++) {
			if (preg_match('/^set-cookie:[\s]+([^=]+)=([^;]+)/i', $this->headers[$x], $match)) {
				$this->cookies[$match[1]] = urldecode($match[2]);
			}
		}
	}


	/**
	 * Checks whether timeout has occurred
	 * 
	 * Input:		$fp	file pointer
	 *
	 * @param	
	 * @return	
	 */
	private function _check_timeout($fp) {
		if ($this->read_timeout > 0) {
			$fp_status = socket_get_status($fp);
			if ($fp_status["timed_out"]) {
				$this->timed_out = TRUE;
				return TRUE;
			}
		}
		return FALSE;
	}

	
	/**
	 * Make a socket connection
	 * 
	 * Input:		$fp	file pointer
	 *
	 * @param	
	 * @return	
	 */
	private function _connect(&$fp) {
		if ( ! empty($this->proxy_host) && ! empty($this->proxy_port)) {
				$this->_is_proxy = TRUE;
				
				$host = $this->proxy_host;
				$port = $this->proxy_port;
		} else {
			$host = $this->host;
			$port = $this->port;
		}
	
		$this->status = 0;
		
		if ($fp = fsockopen($host, $port, $errno, $errstr, $this->_fp_timeout)) {
			// socket connection succeeded
			return TRUE;
		} else {
			// socket connection failed
			$this->status = $errno;
			switch($errno) {
				case -3:
					$this->error="socket creation failed (-3)";
				
				case -4:
					$this->error="dns lookup failure (-4)";
				
				case -5:
					$this->error="connection refused or timed out (-5)";
				
				default:
					$this->error="connection failed (".$errno.")";
			}
			return FALSE;
		}
	}

	
	/**
	 * Disconnect a socket connection
	 * 
	 * Input:		$fp	file pointer
	 *
	 * @param	
	 * @return	
	 */
	private function _disconnect($fp) {
		return(fclose($fp));
	}


	/**
	 * Prepare post body according to encoding type
	 * 
	 * Input:		$form_vars  - form variables
	 * Output:		post body
	 *
	 * @param	
	 * @param	
	 * @return	
	 */
	private function _prepare_post_body($form_vars, $form_files) {
		settype($form_vars, "array");
		settype($form_files, "array");
		$post_data = '';

		if (count($form_vars) === 0 && count($form_files) === 0) {
			return;
		}
		
		switch ($this->_submit_type) {
			case "application/x-www-form-urlencoded":
				reset($form_vars);
				while (list($key,$val) = each($form_vars)) {
					if (is_array($val) || is_object($val)) {
						while (list($cur_key, $cur_val) = each($val)) {
							$post_data .= urlencode($key)."[]=".urlencode($cur_val)."&";
						}
					} else
						$post_data .= urlencode($key)."=".urlencode($val)."&";
				}
				break;

			case "multipart/form-data":
				$this->_mime_boundary = "InfoPotato".md5(uniqid(microtime()));
				
				reset($form_vars);
				while (list($key,$val) = each($form_vars)) {
					if (is_array($val) || is_object($val)) {
						while (list($cur_key, $cur_val) = each($val)) {
							$post_data .= "--".$this->_mime_boundary."\r\n";
							$post_data .= "Content-Disposition: form-data; name=\"$key\[\]\"\r\n\r\n";
							$post_data .= "$cur_val\r\n";
						}
					} else {
						$post_data .= "--".$this->_mime_boundary."\r\n";
						$post_data .= "Content-Disposition: form-data; name=\"$key\"\r\n\r\n";
						$post_data .= "$val\r\n";
					}
				}
				
				reset($form_files);
				while (list($field_name, $file_names) = each($form_files)) {
					settype($file_names, "array");
					while (list(, $file_name) = each($file_names)) {
						if ( ! is_readable($file_name)) {
							continue;
						}

						$fp = fopen($file_name, "r");
						$file_content = fread($fp, filesize($file_name));
						fclose($fp);
						$base_name = basename($file_name);

						$post_data .= "--".$this->_mime_boundary."\r\n";
						$post_data .= "Content-Disposition: form-data; name=\"$field_name\"; filename=\"$base_name\"\r\n\r\n";
						$post_data .= "$file_content\r\n";
					}
				}
				$post_data .= "--".$this->_mime_boundary."--\r\n";
				break;
		}

		return $post_data;
	}
}

// End of file: ./system/libraries/http_client/http_client_library.php 
