<?php
/**
 * Email Library
 * Permits email to be sent using Mail, Sendmail, or SMTP.
 * 
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class Email_Library {

	/**
	 * Used as the User-Agent and X-Mailer headers' value
	 * 
	 * @var string
	 */
	protected $useragent = 'InfoPotato Email Class';

	/**
	 * Path to the Sendmail binary
	 * 
	 * @var string
	 */
	protected $mailpath	= '/usr/sbin/sendmail';

	/**
	 * Which method to use for sending e-mails
	 * 
	 * @var string 'mail', 'sendmail' or 'smtp'
	 */
	protected $protocol = 'mail';

	/**
	 * SMTP Server.  Example: mail.earthlink.net
	 * 
	 * @var string
	 */
	protected $smtp_host = '';

	/**
	 * SMTP Username
	 * 
	 * @var string
	 */
	protected $smtp_user = '';

	/**
	 * SMTP Password
	 * 
	 * @var string
	 */
	protected $smtp_pass = '';

	/**
	 * SMTP Port
	 * 
	 * @var string
	 */
	protected $smtp_port = '25';

	/**
	 * SMTP connection timeout in seconds
	 * 
	 * @var string
	 */
	protected $smtp_timeout	= 5;
	
	/**
	 * SMTP persistent connection
	 *
	 * @var	bool
	 */
	public $smtp_keepalive = FALSE;
	
	/**
	 * SMTP Encryption
	 * 
	 * @var string NULL, 'tls' or 'ssl'
	 */
	public $smtp_crypto = NULL;
	
	/**
	 * TRUE/FALSE  Turns word-wrap on/off
	 * 
	 * @var bool
	 */
	public $wordwrap = TRUE;
	
	/**
	 * Number of characters to wrap at
	 * 
	 * @var integer
	 */
	public $wrapchars = 76;

	/**
	 * Email message format
	 * 
	 * @var string 'text' or 'html'
	 */
	public $mailtype = 'text';

	/**
	 * Default character set
	 * 
	 * @var string
	 */
	public $charset = 'UTF-8';

	/**
	 * Multipart message
	 * 
	 * @var string 'mixed' (in the body) or 'related' (separate)
	 */
	public $multipart = 'mixed';

	/**
	 * Alternative message (for HTML messages only)
	 * 
	 * @var string
	 */
	public $alt_message = '';

	/**
	 * Whether to validate e-mail addresses
	 * 
	 * @var bool
	 */
	public $validate = FALSE;
	
	/**
	 * X-Priority header value 
	 * 
	 * @var string Default priority (1 - 5)
	 */
	public $priority = '3';

	/**
	 * Newline character sequence. "\r\n" or "\n" (Use "\r\n" to comply with RFC 822)
	 * 
	 * @var string
	 */
	public $newline = "\n";

	/**
	 * The RFC 2045 compliant CRLF for quoted-printable is "\r\n".  Apparently some servers,
	 * even on the receiving end think they need to muck with CRLFs, so using "\n", while
	 * distasteful, is the only thing that seems to work for all environments.
	 *
	 * @var string
	 */
	public $crlf = "\n";

	/**
	 * TRUE/FALSE - Yahoo does not like multipart alternative, so this is an override.  Set to FALSE for Yahoo.
	 * 
	 * @var bool
	 */
	public $send_multipart = TRUE;

	/**
	 * TRUE/FALSE  Turns on/off Bcc batch feature
	 * 
	 * @var bool
	 */
	public $bcc_batch_mode = FALSE;

	/**
	 * If bcc_batch_mode = TRUE, sets max number of Bccs in each batch
	 * 
	 * @var integer
	 */
	public $bcc_batch_size = 200;
	

	/**
	 * Subject header
	 *
	 * @var	string
	 */
	private	$_subject = '';
	
	/**
	 * Message body
	 *
	 * @var	string
	 */
	private	$_body = '';
	
	/**
	 * Final message body to be sent.
	 *
	 * @var	string
	 */
	private	$_finalbody = '';
	
	/**
	 * multipart/alternative boundary
	 *
	 * @var	string
	 */
	private	$_alt_boundary = '';
	
	/**
	 * Attachment boundary
	 *
	 * @var	string
	 */
	private	$_atc_boundary	= '';
	
	/**
	 * Final headers to send
	 *
	 * @var	string
	 */
	private	$_header_str = '';
	
	/**
	 * SMTP Connection socket placeholder
	 *
	 * @var	resource
	 */
	private	$_smtp_connect = '';
	
	/**
	 * Mail encoding
	 *
	 * @var	string	'8bit' or '7bit'
	 */
	private	$_encoding		= '8bit';

	/**
	 * Whether to perform SMTP authentication
	 *
	 * @var	bool
	 */
	private	$_smtp_auth	= FALSE;
	
	/**
	 * Whether to send a Reply-To header
	 *
	 * @var	bool
	 */
	private $_replyto_flag = FALSE;
	
	/**
	 * Debug messages
	 *
	 * @see	print_debugger()
	 * @var	string
	 */
	private	$_debug_msg = array();
	
	/**
	 * Recipients
	 *
	 * @var	string[]
	 */
	private	$_recipients = array();
	
	/**
	 * CC Recipients
	 *
	 * @var	string[]
	 */
	private	$_cc_array = array();
	
	/**
	 * BCC Recipients
	 *
	 * @var	string[]
	 */
	private	$_bcc_array = array();
	
	/**
	 * Message headers
	 *
	 * @var	string[]
	 */
	private	$_headers = array();
	
	/**
	 * Attachment data
	 *
	 * @var	array
	 */
	private	$_attach_name = array();
	private	$_attach_type = array();
	private	$_attach_disp = array();
	
	/**
	 * Valid $protocol values
	 *
	 * @see	CI_Email::$protocol
	 * @var	string[]
	 */
	private	$_protocols		= array('mail', 'sendmail', 'smtp');
	
	/**
	 * Character sets valid for 7-bit encoding
	 * 
	 * @var array
	 */
	private	$_base_charsets	= array('us-ascii', 'iso-2022-');

	/**
	 * Bit depths
	 *
	 * Valid mail encodings
	 *
	 * @see	CI_Email::$_encoding
	 * @var	string[]
	 */
	private $_bit_depths = array('7bit', '8bit');
	
	/**
	 * $priority translations
	 *
	 * Actual values to send with the X-Priority header
	 *
	 * @var	string[]
	 */
	private	$_priorities = array('1 (Highest)', '2 (High)', '3 (Normal)', '4 (Low)', '5 (Lowest)');


	/**
	 * Constructor - Sets Email Preferences
	 *
	 * The constructor can be passed an array of config values
	 */
	public function __construct(array $config = NULL) {
		if (count($config) > 0) {
			foreach ($config as $key => $val) {
				if (isset($this->$key)) {
					$method = 'set_'.$key;

					if (method_exists($this, $method)) {
						$this->$method($val);
					} else {
						$this->$key = $val;
					}
				}
			}
			$this->clear();
		}
		
		$this->_smtp_auth = ! ($this->smtp_user === '' && $this->smtp_pass === '');
	}


	/**
	 * Destructor - Releases Resources
	 *
	 * @return	void
	 */
	public function __destruct() {
		if (is_resource($this->_smtp_connect)) {
			$this->_send_command('quit');
		}
	}
	
	
	/**
	 * Initialize the Email Data
	 *
	 * @access	public
	 * @param	bool
	 * @return	Email_Library
	 */
	public function clear($clear_attachments = FALSE) {
		$this->_subject = '';
		$this->_body = '';
		$this->_finalbody = '';
		$this->_header_str = '';
		$this->_replyto_flag = FALSE;
		$this->_recipients = array();
		$this->_cc_array = array();
		$this->_bcc_array = array();
		$this->_headers = array();
		$this->_debug_msg = array();

		$this->_set_header('User-Agent', $this->useragent);
		$this->_set_header('Date', $this->_set_date());

		if ($clear_attachments !== FALSE) {
			$this->_attach_name = array();
			$this->_attach_type = array();
			$this->_attach_disp = array();
		}

		// This will return the instance this method is called on. 
		// This usually done for achieving fluent interfaces
		return $this;
	}


	/**
	 * Set FROM
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	string	Return-Path Return-Path is the address where bounce messages (undeliverable notifications, etc.) should be delivered.
	 * @return	Email_Library
	 */
	public function from($from, $name = '', $return_path = NULL) {
		if (preg_match('/\<(.*)\>/', $from, $match)) {
			$from = $match['1'];
		}

		if ($this->validate) {
			$this->validate_email($this->_str_to_array($from));
		}

		// prepare the display name
		if ($name !== '') {
			// only use Q encoding if there are characters that would require it
			if ( ! preg_match('/[\200-\377]/', $name)) {
				// add slashes for non-printing characters, slashes, and double quotes, and surround it in double quotes
				$name = '"'.addcslashes($name, "\0..\37\177'\"\\").'"';
			} else {
				$name = $this->_prep_q_encoding($name);
			}
		}

		$this->_set_header('From', $name.' <'.$from.'>');
		
		$return_path = isset($return_path) ? $return_path : $from;
		$this->_set_header('Return-Path', '<'.$return_path.'>');

		return $this;
	}


	/**
	 * Set Reply-to
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	Email_Library
	 */
	public function reply_to($replyto, $name = '') {
		if (preg_match('/\<(.*)\>/', $replyto, $match)) {
			$replyto = $match['1'];
		}

		if ($this->validate) {
			$this->validate_email($this->_str_to_array($replyto));
		}

		if ($name === '') {
			$name = $replyto;
		}

		if (strpos($name, '"') !== 0) {
			$name = '"'.$name.'"';
		}

		$this->_set_header('Reply-To', $name.' <'.$replyto.'>');
		$this->_replyto_flag = TRUE;

		return $this;
	}


	/**
	 * Set Recipients
	 *
	 * @access	public
	 * @param	string
	 * @return	Email_Library
	 */
	public function to($to) {
		$to = $this->_str_to_array($to);
		$to = $this->clean_email($to);

		if ($this->validate) {
			$this->validate_email($to);
		}

		if ($this->_get_protocol() !== 'mail') {
			$this->_set_header('To', implode(", ", $to));
		}

		switch ($this->_get_protocol()) {
			case 'smtp' :
				$this->_recipients = $to;
			    break;
			
			case 'sendmail'	:
			case 'mail'	:
				$this->_recipients = implode(", ", $to);
			    break;
		}

		return $this;
	}


	/**
	 * Set CC
	 *
	 * @access	public
	 * @param	string
	 * @return	Email_Library
	 */
	public function cc($cc) {
		$cc = $this->_str_to_array($cc);
		$cc = $this->clean_email($cc);

		if ($this->validate) {
			$this->validate_email($cc);
		}

		$this->_set_header('Cc', implode(", ", $cc));

		if ($this->_get_protocol() === 'smtp') {
			$this->_cc_array = $cc;
		}

		return $this;
	}


	/**
	 * Set BCC
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	Email_Library
	 */
	public function bcc($bcc, $limit = '') {
		if ($limit !== '' && is_numeric($limit)) {
			$this->bcc_batch_mode = TRUE;
			$this->bcc_batch_size = $limit;
		}

		$bcc = $this->_str_to_array($bcc);
		$bcc = $this->clean_email($bcc);

		if ($this->validate) {
			$this->validate_email($bcc);
		}

		if (($this->_get_protocol() === 'smtp') || ($this->bcc_batch_mode && count($bcc) > $this->bcc_batch_size)) {
			$this->_bcc_array = $bcc;
		} else {
			$this->_set_header('Bcc', implode(", ", $bcc));
		}

		return $this;
	}


	/**
	 * Set Email Subject
	 *
	 * @access	public
	 * @param	string
	 * @return	Email_Library
	 */
	public function subject($subject) {
		$subject = $this->_prep_q_encoding($subject);
		$this->_set_header('Subject', $subject);
		return $this;
	}


	/**
	 * Set Body
	 *
	 * @access	public
	 * @param	string
	 * @return	Email_Library
	 */
	public function message($body) {
		$this->_body = rtrim(str_replace("\r", '', $body));

		// strip slashes only if magic quotes is ON
		// if we do it with magic quotes OFF, it strips real, user-inputted chars.
		// NOTE: Returns 0 if magic_quotes_gpc is off, 1 otherwise. 
		// Or always returns FALSE as of PHP 5.4.0 because the magic quotes feature was removed from PHP.
		if (version_compare(PHP_VERSION, '5.4', '<') && get_magic_quotes_gpc()) {
			$this->_body = stripslashes($this->_body);
		}

		return $this;
	}


	/**
	 * Assign file attachments
	 *
	 * @access	public
	 * @param	string
	 * @return	Email_Library
	 */
	public function attach($filename, $disposition = 'attachment') {
		$this->_attach_name[] = $filename;
		$this->_attach_type[] = $this->_mime_types(pathinfo($filename, PATHINFO_EXTENSION));
		$this->_attach_disp[] = $disposition; // Can also be 'inline'  Not sure if it matters
		return $this;
	}


	/**
	 * Add a Header Item
	 *
	 * @access	protected
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	protected function _set_header($header, $value) {
		$this->_headers[$header] = $value;
	}


	/**
	 * Convert a String to an Array
	 *
	 * @access	protected
	 * @param	string
	 * @return	array
	 */
	protected function _str_to_array($email) {
		if ( ! is_array($email)) {
			if (strpos($email, ',') !== FALSE) {
				$email = preg_split('/[\s,]/', $email, -1, PREG_SPLIT_NO_EMPTY);
			} else {
				$email = trim($email);
				settype($email, "array");
			}
		}
		return $email;
	}


	/**
	 * Set Multipart Value
	 *
	 * @access	public
	 * @param	string
	 * @return	Email_Library
	 */
	public function set_alt_message($str = '') {
		$this->alt_message = $str;
		return $this;
	}


	/**
	 * Set Mailtype
	 *
	 * @access	public
	 * @param	string
	 * @return	Email_Library
	 */
	public function set_mailtype($type = 'text') {
		$this->mailtype = ($type === 'html') ? 'html' : 'text';
		return $this;
	}


	/**
	 * Set Wordwrap
	 *
	 * @access	public
	 * @param	string
	 * @return	Email_Library
	 */
	public function set_wordwrap($wordwrap = TRUE) {
		$this->wordwrap = (bool) $wordwrap;
		return $this;
	}


	/**
	 * Set Protocol
	 *
	 * @access	public
	 * @param	string
	 * @return	Email_Library
	 */
	public function set_protocol($protocol = 'mail') {
		$this->protocol = in_array($protocol, $this->_protocols, TRUE) ? strtolower($protocol) : 'mail';
		return $this;
	}


	/**
	 * Set Priority
	 *
	 * @access	public
	 * @param	integer
	 * @return	Email_Library
	 */
	public function set_priority($n = 3) {
		$this->priority = preg_match('/^[1-5]$/', $n) ? (int) $n : 3;
		return $this;
	}


	/**
	 * Set Newline Character
	 *
	 * @access	public
	 * @param	string
	 * @return	Email_Library
	 */
	public function set_newline($newline = "\n") {
		$this->newline = in_array($newline, array("\n", "\r\n", "\r")) ? $newline : "\n";
		return $this;
	}


	/**
	 * Set CRLF
	 *
	 * @access	public
	 * @param	string
	 * @return	Email_Library
	 */
	public function set_crlf($crlf = "\n") {
		$this->crlf = ($crlf !== "\n" && $crlf !== "\r\n" && $crlf !== "\r") ? "\n" : $crlf;
		return $this;
	}


	/**
	 * Set Message Boundary
	 *
	 * @access	protected
	 * @return	void
	 */
	protected function _set_boundaries() {
		$this->_alt_boundary = "B_ALT_".uniqid(''); // multipart/alternative
		$this->_atc_boundary = "B_ATC_".uniqid(''); // attachment boundary
	}


	/**
	 * Get the Message ID
	 *
	 * @access	protected
	 * @return	string
	 */
	protected function _get_message_id() {
		$from = str_replace(array('>', '<'), '', $this->_headers['Return-Path']);
		return '<'.uniqid('').strstr($from, '@').'>';
	}


	/**
	 * Get Mail Protocol
	 *
	 * @access	protected
	 * @param	bool
	 * @return	string
	 */
	protected function _get_protocol($return = TRUE) {
		$this->protocol = strtolower($this->protocol);
		$this->protocol = ( ! in_array($this->protocol, $this->_protocols, TRUE)) ? 'mail' : $this->protocol;

		if ($return === TRUE) {
			return $this->protocol;
		}
	}


	/**
	 * Get Mail Encoding
	 *
	 * @access	protected
	 * @param	bool
	 * @return	string
	 */
	protected function _get_encoding($return = TRUE) {
		$this->_encoding = in_array($this->_encoding, $this->_bit_depths) ? $this->_encoding : '8bit';

		foreach ($this->_base_charsets as $charset) {
			if (strpos($charset, $this->charset) === 0) {
				$this->_encoding = '7bit';
			}
		}

		if ($return === TRUE) {
			return $this->_encoding;
		}
	}


	/**
	 * Get content type (text/html/attachment)
	 *
	 * @access	protected
	 * @return	string
	 */
	protected function _get_content_type() {
		if ($this->mailtype === 'html') {
			return (count($this->_attach_name) === 0) ? 'html' : 'html-attach';
		} elseif	($this->mailtype === 'text' && count($this->_attach_name) > 0) {
			return 'plain-attach';
		} else {
			return 'plain';
		}
	}


	/**
	 * Set RFC 822 Date
	 *
	 * @access	protected
	 * @return	string
	 */
	protected function _set_date() {
		$timezone = date('Z');
		$operator = ($timezone[0] === '-') ? '-' : '+';
		$timezone = abs($timezone);
		$timezone = floor($timezone/3600) * 100 + ($timezone % 3600 ) / 60;

		return sprintf("%s %s%04d", date("D, j M Y H:i:s"), $operator, $timezone);
	}


	/**
	 * Mime message
	 *
	 * @access	protected
	 * @return	string
	 */
	protected function _get_mime_message() {
		return "This is a multi-part message in MIME format.".$this->newline."Your email application may not support this format.";
	}


	/**
	 * Validate Email Address
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function validate_email($email) {
		if ( ! is_array($email)) {
			$this->_set_error_message('email_must_be_array');
			return FALSE;
		}

		foreach ($email as $val) {
			if ( ! $this->valid_email($val)) {
				$this->_set_error_message('email_invalid_address', $val);
				return FALSE;
			}
		}

		return TRUE;
	}


	/**
	 * Email Validation
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function valid_email($address) {
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
	}


	/**
	 * Clean Extended Email Address: Joe Smith <joe@smith.com>
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	public function clean_email($email) {
		if ( ! is_array($email)) {
			return preg_match('/\<(.*)\>/', $email, $match) ? $match[1] : $email;
		}

		$clean_email = array();

		foreach ($email as $addy) {
			$clean_email[] = preg_match('/\<(.*)\>/', $addy, $match) ? $match[1] : $addy;
		}
		
		return $clean_email;
	}


	/**
	 * Build alternative plain text message
	 *
	 * This public function provides the raw message for use
	 * in plain-text headers of HTML-formatted emails.
	 * If the user hasn't specified his own alternative message
	 * it creates one by stripping the HTML
	 *
	 * @access	protected
	 * @return	string
	 */
	protected function _get_alt_message() {
		if ( ! empty($this->alt_message)) {
			return ($this->wordwrap) ? $this->word_wrap($this->alt_message, 76) : $this->alt_message;
		}

		$body = preg_match('/\<body.*?\>(.*)\<\/body\>/si', $this->_body, $match) ? $match[1] : $this->_body;
		$body = str_replace("\t", '', preg_replace('#<!--(.*)--\>#', '', trim(strip_tags($body))));

		for ($i = 20; $i >= 3; $i--) {
			$body = str_replace(str_repeat("\n", $i), "\n\n", $body);
		}

		// Reduce multiple spaces
		$body = preg_replace('| +|', ' ', $body);

		return ($this->wordwrap) ? $this->word_wrap($body, 76) : $body;
	}


	/**
	 * Word Wrap
	 *
	 * @access	public
	 * @param	string
	 * @param	integer line-length limit
	 * @return	string
	 */
	public function word_wrap($str, $charlim = NULL) {
		// Set the character limit, if not already present
		if (empty($charlim)) {
			$charlim = empty($this->wrapchars) ? 76 : $this->wrapchars;
		}
		
		// Reduce multiple spaces
		$str = preg_replace("| +|", " ", $str);

		// Standardize newlines
		if (strpos($str, "\r") !== FALSE) {
			$str = str_replace(array("\r\n", "\r"), "\n", $str);
		}

		// If the current word is surrounded by {unwrap} tags we'll
		// strip the entire chunk and replace it with a marker.
		$unwrap = array();
		if (preg_match_all("|(\{unwrap\}.+?\{/unwrap\})|s", $str, $matches)) {
			for ($i = 0; $i < count($matches['0']); $i++) {
				$unwrap[] = $matches['1'][$i];
				$str = str_replace($matches['1'][$i], "{{unwrapped".$i."}}", $str);
			}
		}

		// Use PHP's native public function to do the initial wordwrap.
		// We set the cut flag to FALSE so that any individual words that are
		// too long get left alone.  In the next step we'll deal with them.
		$str = wordwrap($str, $charlim, "\n", FALSE);

		// Split the string into individual lines of text and cycle through them
		$output = "";
		foreach (explode("\n", $str) as $line) {
			// Is the line within the allowed character count?
			// If so we'll join it to the output and continue
			if (strlen($line) <= $charlim) {
				$output .= $line.$this->newline;
				continue;
			}

			$temp = '';
			while ((strlen($line)) > $charlim) {
				// If the over-length word is a URL we won't wrap it
				if (preg_match("!\[url.+\]|://|wwww.!", $line)) {
					break;
				}

				// Trim the word down
				$temp .= substr($line, 0, $charlim-1);
				$line = substr($line, $charlim-1);
			}

			// If $temp contains data it means we had to split up an over-length
			// word into smaller chunks so we'll add it back to our current line
			if ($temp !== '') {
				$output .= $temp.$this->newline.$line;
			} else {
				$output .= $line;
			}

			$output .= $this->newline;
		}

		// Put our markers back
		if (count($unwrap) > 0) {
			foreach ($unwrap as $key => $val) {
				$output = str_replace("{{unwrapped".$key."}}", $val, $output);
			}
		}

		return $output;
	}


	/**
	 * Build final headers
	 *
	 * @access	protected
	 * @param	string
	 * @return	string
	 */
	protected function _build_headers() {
		$this->_set_header('X-Sender', $this->clean_email($this->_headers['From']));
		$this->_set_header('X-Mailer', $this->useragent);
		$this->_set_header('X-Priority', $this->_priorities[$this->priority - 1]);
		$this->_set_header('Message-ID', $this->_get_message_id());
		$this->_set_header('Mime-Version', '1.0');
	}


	/**
	 * Write Headers as a string
	 *
	 * @access	protected
	 * @return	void
	 */
	protected function _write_headers() {
		if ($this->protocol === 'mail') {
			$this->_subject = $this->_headers['Subject'];
			unset($this->_headers['Subject']);
		}

		reset($this->_headers);
		$this->_header_str = '';

		foreach ($this->_headers as $key => $val) {
			$val = trim($val);

			if ($val !== '') {
				$this->_header_str .= $key.": ".$val.$this->newline;
			}
		}

		if ($this->_get_protocol() === 'mail') {
			$this->_header_str = rtrim($this->_header_str);
		}
	}


	/**
	 * Build Final Body and attachments
	 *
	 * @access	protected
	 * @return	void
	 */
	protected function _build_message() {
		if ($this->wordwrap === TRUE  &&  $this->mailtype !== 'html') {
			$this->_body = $this->word_wrap($this->_body);
		}

		$this->_set_boundaries();
		$this->_write_headers();

		$hdr = ($this->_get_protocol() === 'mail') ? $this->newline : '';
		$body = '';

		switch ($this->_get_content_type()) {
			case 'plain' :
				$hdr .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
				$hdr .= "Content-Transfer-Encoding: " . $this->_get_encoding();

				if ($this->_get_protocol() === 'mail') {
					$this->_header_str .= $hdr;
					$this->_finalbody = $this->_body;
				} else {
					$this->_finalbody = $hdr . $this->newline . $this->newline . $this->_body;
				}

				return;

			    break;
			
			case 'html' :
				if ($this->send_multipart === FALSE) {
					$hdr .= "Content-Type: text/html; charset=" . $this->charset . $this->newline;
					$hdr .= "Content-Transfer-Encoding: quoted-printable";
				} else {
					$hdr .= "Content-Type: multipart/alternative; boundary=\"" . $this->_alt_boundary . "\"" . $this->newline . $this->newline;

					$body .= $this->_get_mime_message() . $this->newline . $this->newline;
					$body .= "--" . $this->_alt_boundary . $this->newline;

					$body .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
					$body .= "Content-Transfer-Encoding: " . $this->_get_encoding() . $this->newline . $this->newline;
					$body .= $this->_get_alt_message() . $this->newline . $this->newline . "--" . $this->_alt_boundary . $this->newline;

					$body .= "Content-Type: text/html; charset=" . $this->charset . $this->newline;
					$body .= "Content-Transfer-Encoding: quoted-printable" . $this->newline . $this->newline;
				}

				$this->_finalbody = $body . $this->_prep_quoted_printable($this->_body) . $this->newline . $this->newline;


				if ($this->_get_protocol() === 'mail') {
					$this->_header_str .= $hdr;
				} else {
					$this->_finalbody = $hdr . $this->_finalbody;
				}


				if ($this->send_multipart !== FALSE) {
					$this->_finalbody .= "--" . $this->_alt_boundary . "--";
				}

				return;

			    break;
			
			case 'plain-attach' :
				$hdr .= "Content-Type: multipart/".$this->multipart."; boundary=\"" . $this->_atc_boundary."\"" . $this->newline . $this->newline;

				if ($this->_get_protocol() === 'mail') {
					$this->_header_str .= $hdr;
				}

				$body .= $this->_get_mime_message() . $this->newline . $this->newline;
				$body .= "--" . $this->_atc_boundary . $this->newline;

				$body .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
				$body .= "Content-Transfer-Encoding: " . $this->_get_encoding() . $this->newline . $this->newline;

				$body .= $this->_body . $this->newline . $this->newline;

			    break;
			
			case 'html-attach' :
				$hdr .= "Content-Type: multipart/".$this->multipart."; boundary=\"" . $this->_atc_boundary."\"" . $this->newline . $this->newline;

				if ($this->_get_protocol() === 'mail') {
					$this->_header_str .= $hdr;
				}

				$body .= $this->_get_mime_message() . $this->newline . $this->newline;
				$body .= "--" . $this->_atc_boundary . $this->newline;

				$body .= "Content-Type: multipart/alternative; boundary=\"" . $this->_alt_boundary . "\"" . $this->newline .$this->newline;
				$body .= "--" . $this->_alt_boundary . $this->newline;

				$body .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
				$body .= "Content-Transfer-Encoding: " . $this->_get_encoding() . $this->newline . $this->newline;
				$body .= $this->_get_alt_message() . $this->newline . $this->newline . "--" . $this->_alt_boundary . $this->newline;

				$body .= "Content-Type: text/html; charset=" . $this->charset . $this->newline;
				$body .= "Content-Transfer-Encoding: quoted-printable" . $this->newline . $this->newline;

				$body .= $this->_prep_quoted_printable($this->_body) . $this->newline . $this->newline;
				$body .= "--" . $this->_alt_boundary . "--" . $this->newline . $this->newline;

			    break;
		}

		$attachment = array();

		$z = 0;

		for ($i = 0; $i < count($this->_attach_name); $i++) {
			$filename = $this->_attach_name[$i];
			$basename = basename($filename);
			$ctype = $this->_attach_type[$i];

			if ( ! file_exists($filename)) {
				$this->_set_error_message('email_attachment_missing', $filename);
				return FALSE;
			}

			$h  = "--".$this->_atc_boundary.$this->newline;
			$h .= "Content-type: ".$ctype."; ";
			$h .= "name=\"".$basename."\"".$this->newline;
			$h .= "Content-Disposition: ".$this->_attach_disp[$i].";".$this->newline;
			$h .= "Content-Transfer-Encoding: base64".$this->newline;

			$attachment[$z++] = $h;
			$file = filesize($filename) +1;

			if ( ! $fp = fopen($filename, FOPEN_READ)) {
				$this->_set_error_message('email_attachment_unreadable', $filename);
				return FALSE;
			}

			$attachment[$z++] = chunk_split(base64_encode(fread($fp, $file)));
			fclose($fp);
		}

		$body .= implode($this->newline, $attachment).$this->newline."--".$this->_atc_boundary."--";


		if ($this->_get_protocol() === 'mail') {
			$this->_finalbody = $body;
		} else {
			$this->_finalbody = $hdr . $body;
		}

		return;
	}


	/**
	 * Prep Quoted Printable
	 *
	 * Prepares string for Quoted-Printable Content-Transfer-Encoding
	 * Refer to RFC 2045 http://www.ietf.org/rfc/rfc2045.txt
	 *
	 * @access	protected
	 * @param	string
	 * @return	string
	 */
	protected function _prep_quoted_printable($str) {	
		// We are intentionally wrapping so mail servers will encode characters
		// properly and MUAs will behave, so {unwrap} must go!
		$str = str_replace(array('{unwrap}', '{/unwrap}'), '', $str);

		// RFC 2045 specifies CRLF as "\r\n".
		// However, many developers choose to override that and violate
		// the RFC rules due to (apparently) a bug in MS Exchange,
		// which only works with "\n".
		if ($this->crlf === "\r\n") {
			if (is_php('5.3')) {
				return quoted_printable_encode($str);
			} elseif (function_exists('imap_8bit')) {
				return imap_8bit($str);
			}
		}

		// Reduce multiple spaces & remove nulls
		$str = preg_replace(array('| +|', '/\x00+/'), array(' ', ''), $str);

		// Standardize newlines
		if (strpos($str, "\r") !== FALSE) {
			$str = str_replace(array("\r\n", "\r"), "\n", $str);
		}

		$escape = '=';
		$output = '';

		foreach (explode("\n", $str) as $line) {
			$length = strlen($line);
			$temp = '';

			// Loop through each character in the line to add soft-wrap
			// characters at the end of a line " =\r\n" and add the newly
			// processed line(s) to the output (see comment on $crlf class property)
			for ($i = 0; $i < $length; $i++) {
				// Grab the next character
				$char = $line[$i];
				$ascii = ord($char);

				// Convert spaces and tabs but only if it's the end of the line
				if ($i === ($length - 1) && ($ascii === 32 || $ascii === 9)) {
					$char = $escape.sprintf('%02s', dechex($ascii));
				} elseif ($ascii === 61) { // encode = signs
					$char = $escape.strtoupper(sprintf('%02s', dechex($ascii)));  // =3D
				}

				// If we're at the character limit, add the line to the output,
				// reset our temp variable, and keep on chuggin'
				if ((strlen($temp) + strlen($char)) >= 76) {
					$output .= $temp.$escape.$this->crlf;
					$temp = '';
				}

				// Add the character to our temporary line
				$temp .= $char;
			}

			// Add our completed line to the output
			$output .= $temp.$this->crlf;
		}

		// get rid of extra CRLF tacked onto the end
		return substr($output, 0, strlen($this->crlf) * -1);
	}


	/**
	 * Prep Q Encoding
	 *
	 * Performs "Q Encoding" on a string for use in email headers.  It's related
	 * but not identical to quoted-printable, so it has its own method
	 *
	 * @access	public
	 * @param	str
	 * @param	bool	// set to TRUE for processing From: headers
	 * @return	str
	 */
	protected function _prep_q_encoding($str) {
		$str = str_replace(array("\r", "\n"), '', $str);

		if ($this->charset === 'UTF-8') {
			if (extension_loaded('mbstring')) {
				// set internal encoding for multibyte string functions
				mb_internal_encoding($this->charset);
				
				return mb_encode_mimeheader($str, $this->charset, 'Q', $this->crlf);
			} elseif (extension_loaded('iconv')) {
				$output = @iconv_mime_encode('', $str,
					array(
						'scheme' => 'Q',
						'line-length' => 76,
						'input-charset' => $this->charset,
						'output-charset' => $this->charset,
						'line-break-chars' => $this->crlf
					)
				);

				// There are reports that iconv_mime_encode() might fail and return FALSE
				if ($output !== FALSE) {
					// iconv_mime_encode() will always put a header field name.
					// We've passed it an empty one, but it still prepends our
					// encoded string with ': ', so we need to strip it.
					return substr($output, 2);
				}

				$chars = iconv_strlen($str, 'UTF-8');
			}
		}

		// We might already have this set for UTF-8
		$chars = isset($chars) ? strlen($str) : $chars;

		$output = '=?'.$this->charset.'?Q?';
		for ($i = 0, $length = strlen($output), $iconv = extension_loaded('iconv'); $i < $chars; $i++) {
			$chr = ($this->charset === 'UTF-8' && $iconv === TRUE)
				? '='.implode('=', str_split(strtoupper(bin2hex(iconv_substr($str, $i, 1, $this->charset))), 2))
				: '='.strtoupper(bin2hex($str[$i]));

			// RFC 2045 sets a limit of 76 characters per line.
			// We'll append ?= to the end of each line though.
			if ($length + ($l = strlen($chr)) > 74) {
				$output .= '?='.$this->crlf // EOL
					.' =?'.$this->charset.'?Q?'.$chr; // New line
				$length = 6 + strlen($this->charset) + $l; // Reset the length for the new line
			}
			else {
				$output .= $chr;
				$length += $l;
			}
		}

		// End the header
		return $output.'?=';
	}


	/**
	 * Send Email
	 *
	 * Must set $auto_clear = FALSE to have print_debugger() work as expected
	 *
	 * @access	public
	 * @param	bool	$auto_clear = TRUE
	 * @return	bool
	 */
	public function send($auto_clear = TRUE) {
		if ($this->_replyto_flag === FALSE) {
			$this->reply_to($this->_headers['From']);
		}

		if (( ! isset($this->_recipients) && ! isset($this->_headers['To']))  
		        && ( ! isset($this->_bcc_array) && ! isset($this->_headers['Bcc'])) 
			    && ( ! isset($this->_headers['Cc']))) {
			$this->_set_error_message('email_no_recipients');
			return FALSE;
		}

		$this->_build_headers();

		if ($this->bcc_batch_mode && (count($this->_bcc_array) > $this->bcc_batch_size)) {
			$result = $this->batch_bcc_send();

			if ($result && $auto_clear) {
				$this->clear();
			}

			return $result;
		}

		$this->_build_message();

		$result = $this->_spool_email();

		if ($result && $auto_clear) {
			$this->clear();
		}

		return $result;
	}


	/**
	 * Batch Bcc Send.  Sends groups of BCCs in batches
	 *
	 * @access	public
	 * @return	void
	 */
	public function batch_bcc_send() {
		$float = $this->bcc_batch_size -1;
		$set = '';
		$chunk = array();

		for ($i = 0; $i < count($this->_bcc_array); $i++) {
			if (isset($this->_bcc_array[$i])) {
				$set .= ", ".$this->_bcc_array[$i];
			}

			if ($i === $float) {
				$chunk[] = substr($set, 1);
				$float = $float + $this->bcc_batch_size;
				$set = "";
			}

			if ($i === count($this->_bcc_array)-1) {
				$chunk[] = substr($set, 1);
			}
		}

		for ($i = 0; $i < count($chunk); $i++) {
			unset($this->_headers['Bcc']);
			unset($bcc);

			$bcc = $this->_str_to_array($chunk[$i]);
			$bcc = $this->clean_email($bcc);

			if ($this->protocol != 'smtp') {
				$this->_set_header('Bcc', implode(", ", $bcc));
			} else {
				$this->_bcc_array = $bcc;
			}

			$this->_build_message();
			$this->_spool_email();
		}
	}


	/**
	 * Unwrap special elements
	 *
	 * @access	protected
	 * @return	void
	 */
	protected function _unwrap_specials() {
		$this->_finalbody = preg_replace_callback("/\{unwrap\}(.*?)\{\/unwrap\}/si", array($this, '_remove_nl_callback'), $this->_finalbody);
	}


	/**
	 * Strip line-breaks via callback
	 *
	 * @access	protected
	 * @return	string
	 */
	protected function _remove_nl_callback($matches) {
		if (strpos($matches[1], "\r") !== FALSE || strpos($matches[1], "\n") !== FALSE) {
			$matches[1] = str_replace(array("\r\n", "\r", "\n"), '', $matches[1]);
		}

		return $matches[1];
	}


	/**
	 * Spool mail to the mail server
	 *
	 * @access	protected
	 * @return	bool
	 */
	protected function _spool_email() {
		$this->_unwrap_specials();

		$method = '_send_with_'.$this->_get_protocol();
		if ( ! $this->$method()) {
			$this->_set_error_message('email_send_failure_'.($this->_get_protocol() === 'mail' ? 'phpmail' : $this->_get_protocol()));
			return FALSE;
		}

		$this->_set_error_message('email_sent', $this->_get_protocol());
		return TRUE;
	}


	/**
	 * Send using mail()
	 *
	 * @access	protected
	 * @return	bool
	 */
	protected function _send_with_mail() {
		
		if (is_array($this->_recipients)) {
			$this->_recipients = implode(', ', $this->_recipients);
		}

		// most documentation of sendmail using the "-f" flag lacks a space after it, however
		// we've encountered servers that seem to require it to be in place.
		return mail($this->_recipients, $this->_subject, $this->_finalbody, $this->_header_str, '-f '.$this->clean_email($this->_headers['Return-Path']));
	}


	/**
	 * Send using Sendmail
	 *
	 * @access	protected
	 * @return	bool
	 */
	protected function _send_with_sendmail() {
		// Opens process file pointer
		$fp = @popen($this->mailpath.' -oi -f '.$this->clean_email($this->_headers['From']).' -t -r '.$this->clean_email($this->_headers['Return-Path']), 'w');

		if ($fp === FALSE) {
			// server probably has popen disabled, so nothing we can do to get a verbose error.
			return FALSE;
		}

		fputs($fp, $this->_header_str);
		fputs($fp, $this->_finalbody);

		$status = pclose($fp);

		if ($status !== 0) {
			$this->_set_error_message('email_exit_status', $status);
			$this->_set_error_message('email_no_socket');
			return FALSE;
		}

		return TRUE;
	}


	/**
	 * Send using SMTP
	 *
	 * @access	protected
	 * @return	bool
	 */
	protected function _send_with_smtp() {
		if ($this->smtp_host === '') {
			$this->_set_error_message('email_no_hostname');
			return FALSE;
		}

		if ( ! $this->_smtp_connect() || ! $this->_smtp_authenticate()) {
			return FALSE;
		}

		$this->_send_command('from', $this->clean_email($this->_headers['From']));

		foreach ($this->_recipients as $val) {
			$this->_send_command('to', $val);
		}

		if (count($this->_cc_array) > 0) {
			foreach ($this->_cc_array as $val) {
				if ($val !== '') {
					$this->_send_command('to', $val);
				}
			}
		}

		if (count($this->_bcc_array) > 0) {
			foreach ($this->_bcc_array as $val) {
				if ($val !== '') {
					$this->_send_command('to', $val);
				}
			}
		}

		$this->_send_command('data');

		// perform dot transformation on any lines that begin with a dot
		$this->_send_data($this->_header_str . preg_replace('/^\./m', '..$1', $this->_finalbody));

		$this->_send_data('.');

		$reply = $this->_get_smtp_data();

		$this->_set_error_message($reply);

		if (strpos($reply, '250') !== 0) {
			$this->_set_error_message('email_smtp_error', $reply);
			return FALSE;
		}

		if ($this->smtp_keepalive) {
			$this->_send_command('reset');
		} else {
			$this->_send_command('quit');
		}

		return TRUE;
	}


	/**
	 * SMTP Connect
	 *
	 * @access	protected
	 * @param	string
	 * @return	string
	 */
	protected function _smtp_connect() {
		if (is_resource($this->_smtp_connect)) {
			return TRUE;
		}
		
		$ssl = ($this->smtp_crypto === 'ssl') ? 'ssl://' : NULL;

		$this->_smtp_connect = fsockopen($ssl.$this->smtp_host, $this->smtp_port, $errno, $errstr, $this->smtp_timeout);

		if ( ! is_resource($this->_smtp_connect)) {
			$this->_set_error_message('email_smtp_error', $errno.' '.$errstr);
			return FALSE;
		}

		stream_set_timeout($this->_smtp_connect, $this->smtp_timeout);
		$this->_set_error_message($this->_get_smtp_data());

		if ($this->smtp_crypto === 'tls') {
			$this->_send_command('hello');
			$this->_send_command('starttls');
			
			$crypto = stream_socket_enable_crypto($this->_smtp_connect, TRUE, STREAM_CRYPTO_METHOD_TLS_CLIENT);
		
		    if ($crypto !== TRUE) {
				$this->_set_error_message('email_smtp_error', $this->_get_smtp_data());
				return FALSE;
			}
		}

		return $this->_send_command('hello');
	}


	/**
	 * Send SMTP command
	 *
	 * @access	protected
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	protected function _send_command($cmd, $data = '') {
		switch ($cmd) {
			case 'hello' :
				if ($this->_smtp_auth || $this->_get_encoding() === '8bit') {
					$this->_send_data('EHLO '.$this->_get_hostname());
				} else {
					$this->_send_data('HELO '.$this->_get_hostname());
                }
				$resp = 250;
			    break;
			
			case 'starttls'	:
				$this->_send_data('STARTTLS');
				$resp = 220;
			    break;
			
			case 'from' :
				$this->_send_data('MAIL FROM:<'.$data.'>');
				$resp = 250;
			    break;
			
			case 'to'	:
				$this->_send_data('RCPT TO:<'.$data.'>');
				$resp = 250;
			    break;
			
			case 'data'	:
				$this->_send_data('DATA');
				$resp = 354;
			    break;
			
			case 'reset':
				$this->_send_data('RSET');
				$resp = 250;

			case 'quit'	:
				$this->_send_data('QUIT');
				$resp = 221;
			    break;
		}

		$reply = $this->_get_smtp_data();

		$this->_debug_msg[] = '<pre>'.$cmd.': '.$reply.'</pre>';

		if ((int) substr($reply, 0, 3) !== $resp) {
			$this->_set_error_message('email_smtp_error', $reply);
			return FALSE;
		}

		if ($cmd === 'quit') {
			fclose($this->_smtp_connect);
		}

		return TRUE;
	}


	/**
	 *  SMTP Authenticate
	 *
	 * @access	protected
	 * @return	bool
	 */
	protected function _smtp_authenticate() {
		if ( ! $this->_smtp_auth) {
			return TRUE;
		}

		if ($this->smtp_user === '' && $this->smtp_pass === '') {
			$this->_set_error_message('email_no_smtp_unpw');
			return FALSE;
		}

		$this->_send_data('AUTH LOGIN');

		$reply = $this->_get_smtp_data();

		if (strpos($reply, '503') === 0) {
			// Already authenticated
			return TRUE;
		} elseif (strpos($reply, '334') !== 0) {
			$this->_set_error_message('email_failed_smtp_login', $reply);
			return FALSE;
		}

		$this->_send_data(base64_encode($this->smtp_user));

		$reply = $this->_get_smtp_data();

		if (strpos($reply, '334') !== 0) {
			$this->_set_error_message('email_smtp_auth_un', $reply);
			return FALSE;
		}

		$this->_send_data(base64_encode($this->smtp_pass));

		$reply = $this->_get_smtp_data();

		if (strpos($reply, '235') !== 0) {
			$this->_set_error_message('email_smtp_auth_pw', $reply);
			return FALSE;
		}

		return TRUE;
	}


	/**
	 * Send SMTP data
	 *
	 * @access	protected
	 * @return	bool
	 */
	protected function _send_data($data) {
		if ( ! fwrite($this->_smtp_connect, $data . $this->newline)) {
			$this->_set_error_message('email_smtp_data_failure', $data);
			return FALSE;
		} 
		
		return TRUE;
	}

	/**
	 * Get SMTP data
	 *
	 * @access	protected
	 * @return	string
	 */
	protected function _get_smtp_data() {
		$data = '';

		while ($str = fgets($this->_smtp_connect, 512)) {
			$data .= $str;

			if ($str[3] === ' ') {
				break;
			}
		}

		return $data;
	}


	/**
	 * Get Hostname
	 *
	 * @access	protected
	 * @return	string
	 */
	protected function _get_hostname() {
		return (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : 'localhost.localdomain';
	}

	
	/**
	 * Get Debug Message
	 *
	 * @access	public
	 * @param	array	$include	List of raw data chunks to include in the output
	 *					Valid options are: 'headers', 'subject', 'body'
	 * @return	string
	 */
	public function print_debugger($include = array('headers', 'subject', 'body')) {
		$msg = '';

		if (count($this->_debug_msg) > 0) {
			foreach ($this->_debug_msg as $val) {
				$msg .= $val;
			}
		}

		// Determine which parts of our raw data needs to be printed
		$raw_data = '';
		$include = is_array($include) ? $include : array($include);

		if (in_array('headers', $include, TRUE)) {
			$raw_data = $this->_header_str."\n";
		}

		if (in_array('subject', $include, TRUE)) {
			$raw_data .= htmlspecialchars($this->_subject)."\n";
		}

		if (in_array('body', $include, TRUE)) {
			$raw_data .= htmlspecialchars($this->_finalbody);
		}

		return $msg.($raw_data === '' ? '' : '<pre>'.$raw_data.'</pre>');
	}


	/**
	 * Set Message
	 *
	 * @access	protected
	 * @param	string
	 * @return	string
	 */
	protected function _set_error_message($msg, $val = '') {
		$error_messages = array(
			'email_must_be_array' => "The email validation method must be passed an array.",
			'email_invalid_address' => "Invalid email address: %s",
			'email_attachment_missing' => "Unable to locate the following email attachment: %s",
			'email_attachment_unreadable' => "Unable to open this attachment: %s",
			'email_no_recipients' => "You must include recipients: To, Cc, or Bcc",
			'email_send_failure_phpmail' => "Unable to send email using PHP mail().  Your server might not be configured to send mail using this method.",
			'email_send_failure_sendmail' => "Unable to send email using PHP Sendmail.  Your server might not be configured to send mail using this method.",
			'email_send_failure_smtp' => "Unable to send email using PHP SMTP.  Your server might not be configured to send mail using this method.",
			'email_sent' => "Your message has been successfully sent using the following protocol: %s",
			'email_no_socket' => "Unable to open a socket to Sendmail. Please check settings.",
			'email_no_hostname' => "You did not specify a SMTP hostname.",
			'email_smtp_error' => "The following SMTP error was encountered: %s",
			'email_no_smtp_unpw' => "Error: You must assign a SMTP username and password.",
			'email_failed_smtp_login' => "Failed to send AUTH LOGIN command. Error: %s",
			'email_smtp_auth_un' => "Failed to authenticate username. Error: %s",
			'email_smtp_auth_pw' => "Failed to authenticate password. Error: %s",
			'email_smtp_data_failure' => "Unable to send data: %s",
			'email_exit_status' => "Exit status code: %s"
		);
		
		if (array_key_exists($msg, $error_messages)) {
			$this->_debug_msg[] = str_replace('%s', $val, $error_messages[$msg])."<br />";
		} else {
			$this->_debug_msg[] = str_replace('%s', $val, $msg)."<br />";
		}
	}


	/**
	 * Mime Types
	 *
	 * @access	protected
	 * @param	string
	 * @return	string
	 */
	protected function _mime_types($ext = "") {
		$mimes = array(	
		    'hqx'	=>	'application/mac-binhex40',
			'cpt'	=>	'application/mac-compactpro',
			'doc'	=>	'application/msword',
			'bin'	=>	'application/macbinary',
			'dms'	=>	'application/octet-stream',
			'lha'	=>	'application/octet-stream',
			'lzh'	=>	'application/octet-stream',
			'exe'	=>	'application/octet-stream',
			'class'	=>	'application/octet-stream',
			'psd'	=>	'application/octet-stream',
			'so'	=>	'application/octet-stream',
			'sea'	=>	'application/octet-stream',
			'dll'	=>	'application/octet-stream',
			'oda'	=>	'application/oda',
			'pdf'	=>	'application/pdf',
			'ai'	=>	'application/postscript',
			'eps'	=>	'application/postscript',
			'ps'	=>	'application/postscript',
			'smi'	=>	'application/smil',
			'smil'	=>	'application/smil',
			'mif'	=>	'application/vnd.mif',
			'xls'	=>	'application/vnd.ms-excel',
			'ppt'	=>	'application/vnd.ms-powerpoint',
			'wbxml'	=>	'application/vnd.wap.wbxml',
			'wmlc'	=>	'application/vnd.wap.wmlc',
			'dcr'	=>	'application/x-director',
			'dir'	=>	'application/x-director',
			'dxr'	=>	'application/x-director',
			'dvi'	=>	'application/x-dvi',
			'gtar'	=>	'application/x-gtar',
			'php'	=>	'application/x-httpd-php',
			'php4'	=>	'application/x-httpd-php',
			'php3'	=>	'application/x-httpd-php',
			'phtml'	=>	'application/x-httpd-php',
			'phps'	=>	'application/x-httpd-php-source',
			'js'	=>	'application/x-javascript',
			'swf'	=>	'application/x-shockwave-flash',
			'sit'	=>	'application/x-stuffit',
			'tar'	=>	'application/x-tar',
			'tgz'	=>	'application/x-tar',
			'xhtml'	=>	'application/xhtml+xml',
			'xht'	=>	'application/xhtml+xml',
			'zip'	=>	'application/zip',
			'mid'	=>	'audio/midi',
			'midi'	=>	'audio/midi',
			'mpga'	=>	'audio/mpeg',
			'mp2'	=>	'audio/mpeg',
			'mp3'	=>	'audio/mpeg',
			'aif'	=>	'audio/x-aiff',
			'aiff'	=>	'audio/x-aiff',
			'aifc'	=>	'audio/x-aiff',
			'ram'	=>	'audio/x-pn-realaudio',
			'rm'	=>	'audio/x-pn-realaudio',
			'rpm'	=>	'audio/x-pn-realaudio-plugin',
			'ra'	=>	'audio/x-realaudio',
			'rv'	=>	'video/vnd.rn-realvideo',
			'wav'	=>	'audio/x-wav',
			'bmp'	=>	'image/bmp',
			'gif'	=>	'image/gif',
			'jpeg'	=>	'image/jpeg',
			'jpg'	=>	'image/jpeg',
			'jpe'	=>	'image/jpeg',
			'png'	=>	'image/png',
			'tiff'	=>	'image/tiff',
			'tif'	=>	'image/tiff',
			'css'	=>	'text/css',
			'html'	=>	'text/html',
			'htm'	=>	'text/html',
			'shtml'	=>	'text/html',
			'txt'	=>	'text/plain',
			'text'	=>	'text/plain',
			'log'	=>	'text/plain',
			'rtx'	=>	'text/richtext',
			'rtf'	=>	'text/rtf',
			'xml'	=>	'text/xml',
			'xsl'	=>	'text/xml',
			'mpeg'	=>	'video/mpeg',
			'mpg'	=>	'video/mpeg',
			'mpe'	=>	'video/mpeg',
			'qt'	=>	'video/quicktime',
			'mov'	=>	'video/quicktime',
			'avi'	=>	'video/x-msvideo',
			'movie'	=>	'video/x-sgi-movie',
			'doc'	=>	'application/msword',
			'word'	=>	'application/msword',
			'xl'	=>	'application/excel',
			'eml'	=>	'message/rfc822'
		);

		return ( ! isset($mimes[strtolower($ext)])) ? "application/x-unknown-content-type" : $mimes[strtolower($ext)];
	}

}

/* End of file: ./system/libraries/email/email_library.php */
