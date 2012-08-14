<?php
/**
 * PHP HTTP client
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://snoopy.sourceforge.net/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
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
	 * $rawheaders["Content-type"]="text/html";
	 */
	public $rawheaders = array();	

	/**
	 * http redirection depth maximum. 0 = disallow
	 *
	 * @var  integer  
	 */
	public $maxredirs =	5;
	
	/**
	 * contains address of last redirected address
	 *
	 * @var  string  
	 */
	public $lastredirectaddr = '';
	
	/**
	 * allows redirection off-site
	 *
	 * @var  boolean  
	 */
	public $offsiteok =	TRUE;
	
	/**
	 * frame content depth maximum. 0 = disallow
	 *
	 * @var  integer  
	 */
	public $maxframes =	0;
	
	/**
	 * expand links to fully qualified URLs. this only applies to fetchlinks() submitlinks(), and submittext()
	 *
	 * @var  boolean  
	 */
	public $expandlinks	= TRUE;	
	
	/**
	 * pass set cookies back through redirects
	 *
	 * @var  boolean  
	 * NOTE: this currently does not respect dates, domains or paths.
	 */
	public $passcookies	= TRUE;	

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
	public $maxlength =	500000;
	
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
	private	$_maxlinelen = 4096;
	
	/**
	 * default http request method
	 *
	 * @var  string  
	 */
	private $_httpmethod = 'GET';
	
	/**
	 * default http request version
	 *
	 * @var  string  
	 */
	private $_httpversion =	'HTTP/1.0';
	
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
	private $_redirectaddr = FALSE;
	
	/**
	 * increments on an http redirect
	 *
	 * @var  integer  
	 */
	private $_redirectdepth	= 0;
	
	/**
	 * frame src urls
	 *
	 * @var  string  
	 */
	private $_frameurls = array();
	
	/**
	 * increments on frame depth
	 *
	 * @var  integer  
	 */
	private $_framedepth = 0;
	
	/**
	 * set if using a proxy server
	 *
	 * @var  boolean  
	 */
	private $_isproxy =	FALSE;
	
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
	 * Input:		$URI	the location of the page to fetch
	 * Output:		$this->results	the output text from the fetch
	 *
	 * @param	string	$URI the location of the page to fetch
	 * @param	integer	the month
	 * @return	boolean
	 */
	public function fetch($URI) {
	
		//preg_match("|^([^:]+)://([^:/]+)(:[\d]+)*(.*)|",$URI,$URI_PARTS);
		$URI_PARTS = parse_url($URI);
		if ( ! empty($URI_PARTS["user"])) {
			$this->user = $URI_PARTS["user"];
		}
		
		if ( ! empty($URI_PARTS["pass"])) {
			$this->pass = $URI_PARTS["pass"];
		}
		
		if (empty($URI_PARTS["query"])) {
			$URI_PARTS["query"] = '';
		} 
		
		if (empty($URI_PARTS["path"])) {
			$URI_PARTS["path"] = '';
		}
		
		switch (strtolower($URI_PARTS['scheme'])) {
			case 'http':
				$this->host = $URI_PARTS['host'];
				if ( ! empty($URI_PARTS['port']))
					$this->port = $URI_PARTS['port'];
				if ($this->_connect($fp)) {
					if ($this->_isproxy) {
						// using proxy, send entire URI
						$this->_httprequest($URI,$fp,$URI,$this->_httpmethod);
					} else {
						$path = $URI_PARTS['path'].($URI_PARTS['query'] ? "?".$URI_PARTS['query'] : '');
						// no proxy, send only the path
						$this->_httprequest($path, $fp, $URI, $this->_httpmethod);
					}
					
					$this->_disconnect($fp);

					if ($this->_redirectaddr) {
						/* url was redirected, check if we've hit the max depth */
						if ($this->maxredirs > $this->_redirectdepth) {
							// only follow redirect if it's on this site, or offsiteok is true
							if (preg_match("|^http://".preg_quote($this->host)."|i", $this->_redirectaddr) || $this->offsiteok) {
								/* follow the redirect */
								$this->_redirectdepth++;
								$this->lastredirectaddr=$this->_redirectaddr;
								$this->fetch($this->_redirectaddr);
							}
						}
					}

					if ($this->_framedepth < $this->maxframes && count($this->_frameurls) > 0) {
						$frameurls = $this->_frameurls;
						$this->_frameurls = array();
						
						while (list(,$frameurl) = each($frameurls)) {
							if ($this->_framedepth < $this->maxframes) {
								$this->fetch($frameurl);
								$this->_framedepth++;
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
				$this->host = $URI_PARTS['host'];
				if ( ! empty($URI_PARTS['port']))
					$this->port = $URI_PARTS['port'];
				if ($this->_isproxy) {
					// using proxy, send entire URI
					$this->_httpsrequest($URI,$URI,$this->_httpmethod);
				} else {
					$path = $URI_PARTS['path'].($URI_PARTS['query'] ? "?".$URI_PARTS['query'] : '');
					// no proxy, send only the path
					$this->_httpsrequest($path, $URI, $this->_httpmethod);
				}

				if  ($this->_redirectaddr) {
					/* url was redirected, check if we've hit the max depth */
					if ($this->maxredirs > $this->_redirectdepth) {
						// only follow redirect if it's on this site, or offsiteok is true
						if (preg_match("|^http://".preg_quote($this->host)."|i",$this->_redirectaddr) || $this->offsiteok) {
							/* follow the redirect */
							$this->_redirectdepth++;
							$this->lastredirectaddr=$this->_redirectaddr;
							$this->fetch($this->_redirectaddr);
						}
					}
				}

				if ($this->_framedepth < $this->maxframes && count($this->_frameurls) > 0) {
					$frameurls = $this->_frameurls;
					$this->_frameurls = array();

					while (list(,$frameurl) = each($frameurls)) {
						if ($this->_framedepth < $this->maxframes) {
							$this->fetch($frameurl);
							$this->_framedepth++;
						}
						else
							break;
					}
				}					
				return TRUE;					
				break;
			
			default:
				// not a valid protocol
				$this->error = 'Invalid protocol "'.$URI_PARTS['scheme'].'"\n';
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
	 * Input:		$URI	the location to post the data
	 * $formvars the formvars to use. (format: $formvars["var"] = "val";)
	 * $formfiles  an array of files to submit (format: $formfiles["var"] = "/dir/filename.ext";)
	 * Output:		$this->results	the text output from the post
	 *
	 * @param	string	$URI the location of the page to fetch
	 * @return	boolean
	 */
	public function submit($URI, $formvars = '', $formfiles = '') {
		unset($postdata);
		
		$postdata = $this->_prepare_post_body($formvars, $formfiles);
			
		$URI_PARTS = parse_url($URI);
		if ( ! empty($URI_PARTS['user'])) {
			$this->user = $URI_PARTS['user'];
		}
		
		if ( ! empty($URI_PARTS['pass'])) {
			$this->pass = $URI_PARTS['pass'];
		}
		
		if (empty($URI_PARTS['query'])) {
			$URI_PARTS['query'] = '';
		}
		
		if (empty($URI_PARTS['path'])) {
			$URI_PARTS["path"] = '';
		}
		
		switch(strtolower($URI_PARTS['scheme'])) {
			case "http":
				$this->host = $URI_PARTS['host'];
				if ( ! empty($URI_PARTS['port']))
					$this->port = $URI_PARTS['port'];
				if ($this->_connect($fp)) {
					if ($this->_isproxy) {
						// using proxy, send entire URI
						$this->_httprequest($URI,$fp,$URI,$this->_submit_method,$this->_submit_type,$postdata);
					} else {
						$path = $URI_PARTS['path'].($URI_PARTS['query'] ? '?'.$URI_PARTS['query'] : '');
						// no proxy, send only the path
						$this->_httprequest($path, $fp, $URI, $this->_submit_method, $this->_submit_type, $postdata);
					}
					
					$this->_disconnect($fp);

					if ($this->_redirectaddr) {
						/* url was redirected, check if we've hit the max depth */
						if ($this->maxredirs > $this->_redirectdepth) {						
							if ( ! preg_match("|^".$URI_PARTS['scheme']."://|", $this->_redirectaddr))
								$this->_redirectaddr = $this->_expandlinks($this->_redirectaddr, $URI_PARTS['scheme']."://".$URI_PARTS['host']);						
							
							// only follow redirect if it's on this site, or offsiteok is true
							if (preg_match("|^http://".preg_quote($this->host)."|i", $this->_redirectaddr) || $this->offsiteok) {
								/* follow the redirect */
								$this->_redirectdepth++;
								$this->lastredirectaddr=$this->_redirectaddr;
								if( strpos( $this->_redirectaddr, '?' ) > 0 )
									$this->fetch($this->_redirectaddr); // the redirect has changed the request method from post to get
								else
									$this->submit($this->_redirectaddr,$formvars, $formfiles);
							}
						}
					}

					if ($this->_framedepth < $this->maxframes && count($this->_frameurls) > 0) {
						$frameurls = $this->_frameurls;
						$this->_frameurls = array();
						
						while (list(,$frameurl) = each($frameurls)) {														
							if ($this->_framedepth < $this->maxframes) {
								$this->fetch($frameurl);
								$this->_framedepth++;
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
				$this->host = $URI_PARTS['host'];
				if ( ! empty($URI_PARTS['port']))
					$this->port = $URI_PARTS['port'];
				if ($this->_isproxy) {
					// using proxy, send entire URI
					$this->_httpsrequest($URI, $URI, $this->_submit_method, $this->_submit_type, $postdata);
				} else {
					$path = $URI_PARTS["path"].($URI_PARTS['query'] ? '?'.$URI_PARTS['query'] : '');
					// no proxy, send only the path
					$this->_httpsrequest($path, $URI, $this->_submit_method, $this->_submit_type, $postdata);
				}

				if ($this->_redirectaddr) {
					/* url was redirected, check if we've hit the max depth */
					if ($this->maxredirs > $this->_redirectdepth) {						
						if ( ! preg_match("|^".$URI_PARTS['scheme']."://|", $this->_redirectaddr))
							$this->_redirectaddr = $this->_expandlinks($this->_redirectaddr,$URI_PARTS['scheme']."://".$URI_PARTS['host']);						

						// only follow redirect if it's on this site, or offsiteok is true
						if (preg_match("|^http://".preg_quote($this->host)."|i",$this->_redirectaddr) || $this->offsiteok) {
							/* follow the redirect */
							$this->_redirectdepth++;
							$this->lastredirectaddr=$this->_redirectaddr;
							if ( strpos( $this->_redirectaddr, '?' ) > 0 )
								$this->fetch($this->_redirectaddr); // the redirect has changed the request method from post to get
							else
								$this->submit($this->_redirectaddr, $formvars, $formfiles);
						}
					}
				}

				if ($this->_framedepth < $this->maxframes && count($this->_frameurls) > 0) {
					$frameurls = $this->_frameurls;
					$this->_frameurls = array();

					while (list(,$frameurl) = each($frameurls)) {														
						if ($this->_framedepth < $this->maxframes) {
							$this->fetch($frameurl);
							$this->_framedepth++;
						}
						else
							break;
					}
				}					
				return TRUE;					
				break;
				
			default:
				// not a valid protocol
				$this->error	=	'Invalid protocol "'.$URI_PARTS["scheme"].'"\n';
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
	 * Input:		$URI	where you are fetching from
	 * Output:		$this->results	an array of the URLs
	 *
	 * @param	string	$URI the location of the page to fetch
	 * @return	boolean
	 */
	public function fetchlinks($URI) {
		if ($this->fetch($URI)) {			
			if ($this->lastredirectaddr)
				$URI = $this->lastredirectaddr;
			if (is_array($this->results)) {
				for($x=0;$x<count($this->results);$x++)
					$this->results[$x] = $this->_striplinks($this->results[$x]);
			} else {
				$this->results = $this->_striplinks($this->results);
			}
			if ($this->expandlinks) {
				$this->results = $this->_expandlinks($this->results, $URI);
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
	 * Input:		$URI	where you are fetching from
	 * Output:		$this->results	the resulting html form
	 *
	 * @param	string	$URI the location of the page to fetch
	 * @return	boolean
	 */
	public function fetchform($URI) {
		if ($this->fetch($URI)) {			
			if (is_array($this->results)) {
				for($x = 0; $x<count($this->results); $x++)
					$this->results[$x] = $this->_stripform($this->results[$x]);
			} else {
				$this->results = $this->_stripform($this->results);
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}
	

	/**
	 * Fetch the text from a web page, stripping the links
	 * 
	 * Input:		$URI	where you are fetching from
	 * Output:		$this->results	the text from the web page
	 *
	 * @param	string	$URI the location of the page to fetch
	 * @return	boolean
	 */
	public function fetchtext($URI) {
		if ($this->fetch($URI)) {			
			if (is_array($this->results)) {
				for($x=0; $x<count($this->results); $x++)
					$this->results[$x] = $this->_striptext($this->results[$x]);
			} else {
				$this->results = $this->_striptext($this->results);
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}


	/**
	 * Grab links from a form submission
	 * 
	 * Input:		$URI	where you are submitting from
	 * Output:		$this->results	an array of the links from the post
	 *
	 * @param	
	 * @return	boolean
	 */
	public function submitlinks($URI, $formvars = '', $formfiles = '') {
		if ($this->submit($URI, $formvars, $formfiles)) {			
			if ($this->lastredirectaddr)
				$URI = $this->lastredirectaddr;
			if (is_array($this->results)) {
				for ($x = 0; $x<count($this->results); $x++) {
					$this->results[$x] = $this->_striplinks($this->results[$x]);
					if ($this->expandlinks)
						$this->results[$x] = $this->_expandlinks($this->results[$x], $URI);
				}
			} else {
				$this->results = $this->_striplinks($this->results);
				if ($this->expandlinks) {
					$this->results = $this->_expandlinks($this->results, $URI);
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
	 * Input:		$URI	where you are submitting from
	 * Output:		$this->results	the text from the web page
	 *
	 * @param	
	 * @return	boolean
	 */
	public function submittext($URI, $formvars = '', $formfiles = '') {
		if ($this->submit($URI,$formvars, $formfiles)) {			
			if ($this->lastredirectaddr) {
				$URI = $this->lastredirectaddr;
			}
			
			if (is_array($this->results)) {
				for ($x=0; $x<count($this->results); $x++) {
					$this->results[$x] = $this->_striptext($this->results[$x]);
					if ($this->expandlinks) {
						$this->results[$x] = $this->_expandlinks($this->results[$x], $URI);
					}
				}
			} else {
				$this->results = $this->_striptext($this->results);
				if($this->expandlinks) {
					$this->results = $this->_expandlinks($this->results,$URI);
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
	private function _striplinks($document) {	
		preg_match_all("'<\s*a\s.*?href\s*=\s*			# find <a href=
						([\"\'])?					# find single or double quote
						(?(1) (.*?)\\1 | ([^\s\>]+))		# if quote found, match up to next matching
													# quote, otherwise match up to next space
						'isx",$document,$links);
						

		// catenate the non-empty matches from the conditional subpattern

		while (list($key,$val) = each($links[2])) {
			if ( ! empty($val)) {
				$match[] = $val;
			}
		}				
		
		while (list($key,$val) = each($links[3])) {
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
	private function _stripform($document) {	
		preg_match_all("'<\/?(FORM|INPUT|SELECT|TEXTAREA|(OPTION))[^<>]*>(?(2)(.*(?=<\/?(option|select)[^<>]*>[\r\n]*)|(?=[\r\n]*))|(?=[\r\n]*))'Usi",$document,$elements);
		
		// catenate the matches
		$match = implode("\r\n",$elements[0]);
				
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
	private function _striptext($document) {
		
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
					
		$text = preg_replace($search,$replace,$document);
								
		return $text;
	}


	/**
	 * Expand each link into a fully qualified URL
	 * 
	 * Input:		$links			the links to qualify
	 * $URI			the full URI to get the base from
	 * Output:		$expandedLinks	the expanded links
	 *
	 * @param	
	 * @return	
	 */
	private function _expandlinks($links,$URI) {
		
		preg_match("/^[^\?]+/",$URI,$match);

		$match = preg_replace("|/[^\/\.]+\.[^\/\.]+$|","",$match[0]);
		$match = preg_replace("|/$|","",$match);
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
				
		$expandedLinks = preg_replace($search,$replace,$links);

		return $expandedLinks;
	}


	/**
	 * Go get the http data from the server
	 * 
	 * Input:		$url		the url to fetch
	 * $fp			the current open file pointer
	 * $URI		the full URI
	 * $body		body contents to send if any (POST)
	 *
	 * @param	
	 * @return	
	 */
	private function _httprequest($url,$fp,$URI,$http_method,$content_type="",$body="") {
		$cookie_headers = '';
		if ($this->passcookies && $this->_redirectaddr)
			$this->setcookies();
			
		$URI_PARTS = parse_url($URI);
		if (empty($url)) {
			$url = "/";
		}
		$headers = $http_method." ".$url." ".$this->_httpversion."\r\n";		
		if ( ! empty($this->agent)) {
			$headers .= "User-Agent: ".$this->agent."\r\n";
		}
		
		if ( ! empty($this->host) && !isset($this->rawheaders['Host'])) {
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
				foreach ( $this->cookies as $cookieKey => $cookieVal ) {
					$cookie_headers .= $cookieKey."=".urlencode($cookieVal)."; ";
				}
				$headers .= substr($cookie_headers,0,-2) . "\r\n";
			} 
		}
		
		if ( ! empty($this->rawheaders)) {
			if ( ! is_array($this->rawheaders)) {
				$this->rawheaders = (array)$this->rawheaders;
			}
			while (list($headerKey,$headerVal) = each($this->rawheaders)) {
				$headers .= $headerKey.": ".$headerVal."\r\n";
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
		
		fwrite($fp,$headers.$body,strlen($headers.$body));
		
		$this->_redirectaddr = FALSE;
		unset($this->headers);
						
		while ($currentHeader = fgets($fp,$this->_maxlinelen)) {
			if ($this->read_timeout > 0 && $this->_check_timeout($fp)) {
				$this->status = -100;
				return FALSE;
			}
				
			if ($currentHeader == "\r\n") {
				break;
			}
			
			// if a header begins with Location: or URI:, set the redirect
			if (preg_match("/^(Location:|URI:)/i",$currentHeader)) {
				// get URL portion of the redirect
				preg_match("/^(Location:|URI:)[ ]+(.*)/i",chop($currentHeader),$matches);
				// look for :// in the Location header to see if hostname is included
				if ( ! preg_match("|\:\/\/|",$matches[2])) {
					// no host in the path, so prepend
					$this->_redirectaddr = $URI_PARTS["scheme"]."://".$this->host.":".$this->port;
					// eliminate double slash
					if ( ! preg_match("|^/|",$matches[2])) {
							$this->_redirectaddr .= "/".$matches[2];
					} else {
							$this->_redirectaddr .= $matches[2];
					}
				} else {
					$this->_redirectaddr = $matches[2];
				}
			}
		
			if (preg_match("|^HTTP/|",$currentHeader)) {
                if (preg_match("|^HTTP/[^\s]*\s(.*?)\s|",$currentHeader, $status)) {
					$this->status= $status[1];
                }				
				$this->response_code = $currentHeader;
			}
				
			$this->headers[] = $currentHeader;
		}

		$results = '';
		do {
    		$_data = fread($fp, $this->maxlength);
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
		
		if (preg_match("'<meta[\s]*http-equiv[^>]*?content[\s]*=[\s]*[\"\']?\d+;[\s]*URL[\s]*=[\s]*([^\"\']*?)[\"\']?>'i", $results,$match)) {
			$this->_redirectaddr = $this->_expandlinks($match[1], $URI);	
		}

		// have we hit our frame depth and is there frame src to fetch?
		if (($this->_framedepth < $this->maxframes) && preg_match_all("'<frame\s+.*src[\s]*=[\'\"]?([^\'\"\>]+)'i", $results, $match)) {
			$this->results[] = $results;
			for ($x=0; $x<count($match[1]); $x++)
				$this->_frameurls[] = $this->_expandlinks($match[1][$x], $URI_PARTS['scheme']."://".$this->host);
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
	private function _httpsrequest($url, $URI, $http_method, $content_type = '',$body = '') {  
		if ($this->passcookies && $this->_redirectaddr) {
			$this->setcookies();
		}
		$headers = array();		
					
		$URI_PARTS = parse_url($URI);
		if (empty($url)) {
			$url = "/";
		}
		// GET ... header not needed for curl
		//$headers[] = $http_method." ".$url." ".$this->_httpversion;		
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
				foreach ( $this->cookies as $cookieKey => $cookieVal ) {
					$cookie_str .= $cookieKey."=".urlencode($cookieVal)."; ";
				}
				$headers[] = substr($cookie_str, 0, -2);
			}
		}
		
		if ( ! empty($this->rawheaders)) {
			if ( ! is_array($this->rawheaders))
				$this->rawheaders = (array)$this->rawheaders;
			while(list($headerKey,$headerVal) = each($this->rawheaders))
				$headers[] = $headerKey.": ".$headerVal;
		}
		
		if ( ! empty($content_type)) {
			if ($content_type == "multipart/form-data")
				$headers[] = "Content-type: $content_type; boundary=".$this->_mime_boundary;
			else
				$headers[] = "Content-type: $content_type";
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
		
		$headerfile = tempnam($temp_dir, "sno");

		exec($this->curl_path." -k -D \"$headerfile\"".$cmdline_params." \"".escapeshellcmd($URI)."\"",$results,$return);
		
		if ($return) {
			$this->error = "Error: cURL could not retrieve the document, error $return.";
			return FALSE;
		}
			
			
		$results = implode("\r\n", $results);
		
		$result_headers = file("$headerfile");
						
		$this->_redirectaddr = FALSE;
		unset($this->headers);
						
		for ($currentHeader = 0; $currentHeader < count($result_headers); $currentHeader++) {
			
			// if a header begins with Location: or URI:, set the redirect
			if (preg_match("/^(Location: |URI: )/i", $result_headers[$currentHeader])) {
				// get URL portion of the redirect
				preg_match("/^(Location: |URI:)\s+(.*)/",chop($result_headers[$currentHeader]), $matches);
				// look for :// in the Location header to see if hostname is included
				if ( ! preg_match("|\:\/\/|",$matches[2])) {
					// no host in the path, so prepend
					$this->_redirectaddr = $URI_PARTS["scheme"]."://".$this->host.":".$this->port;
					// eliminate double slash
					if ( ! preg_match("|^/|",$matches[2])) {
						$this->_redirectaddr .= "/".$matches[2];
					} else {
						$this->_redirectaddr .= $matches[2];
					}
				} else {
					$this->_redirectaddr = $matches[2];
				}
			}
		
			if (preg_match("|^HTTP/|",$result_headers[$currentHeader]))
				$this->response_code = $result_headers[$currentHeader];

			$this->headers[] = $result_headers[$currentHeader];
		}

		// check if there is a a redirect meta tag
		
		if (preg_match("'<meta[\s]*http-equiv[^>]*?content[\s]*=[\s]*[\"\']?\d+;[\s]*URL[\s]*=[\s]*([^\"\']*?)[\"\']?>'i",$results,$match)) {
			$this->_redirectaddr = $this->_expandlinks($match[1], $URI);	
		}

		// have we hit our frame depth and is there frame src to fetch?
		if (($this->_framedepth < $this->maxframes) && preg_match_all("'<frame\s+.*src[\s]*=[\'\"]?([^\'\"\>]+)'i", $results,$match)) {
			$this->results[] = $results;
			for($x=0; $x<count($match[1]); $x++) {
				$this->_frameurls[] = $this->_expandlinks($match[1][$x],$URI_PARTS["scheme"]."://".$this->host);
			}
		}
		// have we already fetched framed content?
		elseif (is_array($this->results)) {
			$this->results[] = $results;
		} // no framed content
		else {
			$this->results = $results;
		}

		unlink("$headerfile");
		
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
			if (preg_match('/^set-cookie:[\s]+([^=]+)=([^;]+)/i', $this->headers[$x],$match)) {
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
				$this->_isproxy = TRUE;
				
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
	 * Input:		$formvars  - form variables
	 * Output:		post body
	 *
	 * @param	
	 * @param	
	 * @return	
	 */
	private function _prepare_post_body($formvars, $formfiles) {
		settype($formvars, "array");
		settype($formfiles, "array");
		$postdata = '';

		if (count($formvars) === 0 && count($formfiles) === 0) {
			return;
		}
		
		switch ($this->_submit_type) {
			case "application/x-www-form-urlencoded":
				reset($formvars);
				while (list($key,$val) = each($formvars)) {
					if (is_array($val) || is_object($val)) {
						while (list($cur_key, $cur_val) = each($val)) {
							$postdata .= urlencode($key)."[]=".urlencode($cur_val)."&";
						}
					} else
						$postdata .= urlencode($key)."=".urlencode($val)."&";
				}
				break;

			case "multipart/form-data":
				$this->_mime_boundary = "InfoPotato".md5(uniqid(microtime()));
				
				reset($formvars);
				while (list($key,$val) = each($formvars)) {
					if (is_array($val) || is_object($val)) {
						while (list($cur_key, $cur_val) = each($val)) {
							$postdata .= "--".$this->_mime_boundary."\r\n";
							$postdata .= "Content-Disposition: form-data; name=\"$key\[\]\"\r\n\r\n";
							$postdata .= "$cur_val\r\n";
						}
					} else {
						$postdata .= "--".$this->_mime_boundary."\r\n";
						$postdata .= "Content-Disposition: form-data; name=\"$key\"\r\n\r\n";
						$postdata .= "$val\r\n";
					}
				}
				
				reset($formfiles);
				while (list($field_name, $file_names) = each($formfiles)) {
					settype($file_names, "array");
					while (list(, $file_name) = each($file_names)) {
						if ( ! is_readable($file_name)) {
							continue;
						}

						$fp = fopen($file_name, "r");
						$file_content = fread($fp, filesize($file_name));
						fclose($fp);
						$base_name = basename($file_name);

						$postdata .= "--".$this->_mime_boundary."\r\n";
						$postdata .= "Content-Disposition: form-data; name=\"$field_name\"; filename=\"$base_name\"\r\n\r\n";
						$postdata .= "$file_content\r\n";
					}
				}
				$postdata .= "--".$this->_mime_boundary."--\r\n";
				break;
		}

		return $postdata;
	}
}

// End of file: ./system/libraries/http_client/http_client_library.php 
