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
    private $user_agent = 'InfoPotato Email Class';
    
    /**
     * Path to the Sendmail binary
     * 
     * @var string
     */
    private $sendmail_path = '/usr/sbin/sendmail';
    
    /**
     * Which method to use for sending e-mails
     * 
     * @var string 'mail', 'sendmail' or 'smtp'
     */
    private $protocol = 'mail';
    
    /**
     * SMTP Server.  Example: mail.earthlink.net
     * 
     * @var string
     */
    private $smtp_host = '';
    
    /**
     * SMTP Username
     * 
     * @var string
     */
    private $smtp_user = '';
    
    /**
     * SMTP Password
     * 
     * @var string
     */
    private $smtp_pass = '';
    
    /**
     * SMTP Port
     * 
     * @var int
     */
    private $smtp_port = 25;
    
    /**
     * SMTP connection timeout in seconds
     * 
     * @var int
     */
    private $smtp_timeout    = 5;
    
    /**
     * SMTP persistent connection
     *
     * @var    bool
     */
    private $smtp_keepalive = FALSE;
    
    /**
     * SMTP Encryption
     * 
     * @var string  'tls' or 'ssl'
     */
    private $smtp_crypto = '';
    
    /**
     * TRUE/FALSE  Turns word-wrap on/off
     * 
     * @var bool
     */
    private $wordwrap = TRUE;
    
    /**
     * Number of characters to wrap at
     * 
     * @var integer
     */
    private $wrapchars = 76;
    
    /**
     * Email message format
     * 
     * @var string 'text' or 'html'
     */
    private $mailtype = 'text';
    
    /**
     * Default character set
     * 
     * @var string
     */
    private $charset = 'UTF-8';
    
    /**
     * Multipart message
     * 
     * @var string 'mixed' (in the body) or 'related' (separate)
     */
    private $multipart = 'mixed';
    
    /**
     * Whether to validate e-mail addresses
     * 
     * @var bool
     */
    private $validate = FALSE;
    
    /**
     * X-Priority header value 
     * 
     * @var int (1 - 5)
     */
    private $priority = 3;
    
    /**
     * Newline character sequence. "\r\n" or "\n" (Use "\r\n" to comply with RFC 822)
     * 
     * @var string
     */
    private $newline = "\n";
    
    /**
     * The RFC 2045 compliant CRLF for quoted-printable is "\r\n".  Apparently some servers,
     * even on the receiving end think they need to muck with CRLFs, so using "\n", while
     * distasteful, is the only thing that seems to work for all environments.
     *
     * @var string
     */
    private $crlf = "\n";
    
    /**
     * Whether to use Delivery Status Notification (DSN).
     *
     * @var	bool
     */
    public $dsn = FALSE;
    
    /**
     * TRUE/FALSE - Yahoo does not like multipart alternative, so this is an override.  
     * Set to FALSE for Yahoo.
     * 
     * @var bool
     */
    private $send_multipart = TRUE;
    
    /**
     * TRUE/FALSE  Turns on/off Bcc batch feature
     * 
     * @var bool
     */
    private $bcc_batch_mode = FALSE;
    
    /**
     * If bcc_batch_mode = TRUE, sets max number of Bccs in each batch
     * 
     * @var integer
     */
    private $bcc_batch_size = 200;
    
    /**
     * Subject header
     *
     * @var    string
     */
    private $subject = '';
    
    /**
     * Message body
     *
     * @var    string
     */
    private $body = '';
    
    /**
     * Final message body to be sent.
     *
     * @var    string
     */
    private $finalbody = '';
    
    /**
     * Alternative message (for HTML messages only)
     * 
     * @var string
     */
    private $alt_message = '';
    
    /**
     * multipart/alternative boundary
     *
     * @var    string
     */
    private $alt_boundary = '';
    
    /**
     * Attachment boundary
     *
     * @var    string
     */
    private $atc_boundary = '';
    
    /**
     * Final headers to send
     *
     * @var    string
     */
    private $header_str = '';
    
    /**
     * SMTP Connection socket placeholder
     *
     * @var    resource
     */
    private $smtp_connect = '';
    
    /**
     * Mail encoding
     *
     * @var    string    '8bit' or '7bit'
     */
    private $encoding = '8bit';
    
    /**
     * Whether to perform SMTP authentication
     *
     * @var    bool
     */
    private $smtp_auth    = FALSE;
    
    /**
     * Whether to send a Reply-To header
     *
     * @var    bool
     */
    private $replyto_flag = FALSE;
    
    /**
     * Debug messages
     *
     * @see    print_debugger()
     * @var    string
     */
    private $debug_msg = array();
    
    /**
     * Recipients
     *
     * @var    string[]
     */
    private $recipients = array();
    
    /**
     * CC Recipients
     *
     * @var    string[]
     */
    private $cc_array = array();
    
    /**
     * BCC Recipients
     *
     * @var    string[]
     */
    private $bcc_array = array();
    
    /**
     * Message headers
     *
     * @var    string[]
     */
    private $headers = array();
    
    /**
     * Attachment data
     *
     * @var    array
     */
    private $attach_name = array();
    private $attach_type = array();
    private $attach_disp = array();
    
    /**
     * Valid $protocol values
     *
     * @see    Email_Library::$protocol
     * @var    string[]
     */
    private $protocols = array('mail', 'sendmail', 'smtp');
    
    /**
     * Character sets valid for 7-bit encoding
     * 
     * @var array
     */
    private $base_charsets = array('us-ascii', 'iso-2022-');
    
    /**
     * Bit depths
     *
     * Valid mail encodings
     *
     * @see    Email_Library::$encoding
     * @var    string[]
     */
    private $bit_depths = array('7bit', '8bit');
    
    /**
     * $priority translations
     *
     * Actual values to send with the X-Priority header
     *
     * @var    string[]
     */
    private $priorities = array('1 (Highest)', '2 (High)', '3 (Normal)', '4 (Low)', '5 (Lowest)');
    
    /**
     * Constructor
     *
     * The constructor can be passed an array of config values
     */
    public function __construct(array $config = NULL) {
        if (count($config) > 0) {
            foreach ($config as $key => $val) {
                // Using isset() requires $this->$key not to be NULL in property definition
                // property_exists() allows empty property
                if (property_exists($this, $key)) {
                    $method = 'initialize_'.$key;
                    
                    if (method_exists($this, $method)) {
                        $this->$method($val);
                    }
                } else {
                    exit("'".$key."' is not an acceptable config argument!");
                }
            }
            $this->clear();
        }
        
        $this->smtp_auth = ! ($this->smtp_user === '' && $this->smtp_pass === '');
    }
    
    /**
     * Validate and set $user_agent
     *
     * @param  $val string
     * @return void
     */
    private function initialize_user_agent($val) {
        if ( ! is_string($val)) {
            $this->invalid_argument_value('user_agent');
        }
        $this->user_agent = $val;
    }
    
    /**
     * Validate and set $protocol
     *
     * @param  $val string
     * @return void
     */
    private function initialize_protocol($val) {
        if ( ! is_string($val) || ! in_array($val, $this->protocols)) {
            $this->invalid_argument_value('protocol');
        }
        $this->protocol = $val;
    }
    
    /**
     * Validate and set $sendmail_path
     *
     * @param  $val string
     * @return void
     */
    private function initialize_sendmail_path($val) {
        if ( ! is_string($val)) {
            $this->invalid_argument_value('sendmail_path');
        }
        $this->sendmail_path = $val;
    }
    
    /**
     * Validate and set $smtp_host
     *
     * @param  $val string
     * @return void
     */
    private function initialize_smtp_host($val) {
        if ( ! is_string($val)) {
            $this->invalid_argument_value('smtp_host');
        }
        $this->smtp_host = $val;
    }
    
    /**
     * Validate and set $smtp_user
     *
     * @param  $val string
     * @return void
     */
    private function initialize_smtp_user($val) {
        if ( ! is_string($val)) {
            $this->invalid_argument_value('smtp_user');
        }
        $this->smtp_user = $val;
    }
    
    /**
     * Validate and set $smtp_pass
     *
     * @param  $val string
     * @return void
     */
    private function initialize_smtp_pass($val) {
        if ( ! is_string($val)) {
            $this->invalid_argument_value('smtp_pass');
        }
        $this->smtp_pass = $val;
    }
    
    /**
     * Validate and set $smtp_port
     *
     * @param  $val int
     * @return void
     */
    private function initialize_smtp_port($val) {
        if ( ! is_int($val)) {
            $this->invalid_argument_value('smtp_port');
        }
        $this->smtp_port = $val;
    }
    
    /**
     * Validate and set $smtp_timeout
     *
     * @param  $val int
     * @return void
     */
    private function initialize_smtp_timeout($val) {
        if ( ! is_int($val)) {
            $this->invalid_argument_value('smtp_timeout');
        }
        $this->smtp_timeout = $val;
    }
    
    /**
     * Validate and set $smtp_keepalive
     *
     * @param  $val bool
     * @return void
     */
    private function initialize_smtp_keepalive($val) {
        if ( ! is_bool($val)) {
            $this->invalid_argument_value('smtp_keepalive');
        }
        $this->smtp_keepalive = $val;
    }
    
    /**
     * Validate and set $smtp_crypto
     *
     * @param  $val string
     * @return void
     */
    private function initialize_smtp_crypto($val) {
        if ( ! is_string($val) || ! in_array($val, array('ssl', 'tls'))) {
            $this->invalid_argument_value('smtp_crypto');
        }
        if ( ! extension_loaded('openssl')) {
            exit('OpenSSL extension needed to use ssl or tls');
        }
        $this->smtp_crypto = $val;
    }
    
    /**
     * Validate and set $wordwrap
     *
     * @param  $val bool
     * @return void
     */
    private function initialize_smtp_wordwrap($val) {
        if ( ! is_bool($val)) {
            $this->invalid_argument_value('wordwrap');
        }
        $this->wordwrap = $val;
    }
    
    /**
     * Validate and set $wrapchars
     *
     * @param  $val int
     * @return void
     */
    private function initialize_wrapchars($val) {
        if ( ! is_int($val)) {
            $this->invalid_argument_value('wrapchars');
        }
        $this->wrapchars = $val;
    }
    
    /**
     * Validate and set $mailtype
     *
     * @param  $val string
     * @return void
     */
    private function initialize_mailtype($val) {
        if ( ! is_string($val) || ! in_array($val, array('text', 'html'))) {
            $this->invalid_argument_value('mailtype');
        }
        $this->mailtype = $val;
    }
    
    /**
     * Validate and set $multipart
     *
     * @param  $val string
     * @return void
     */
    private function initialize_multipart($val) {
        if ( ! is_string($val) || ! in_array($val, array('mixed', 'related'))) {
            $this->invalid_argument_value('multipart');
        }
        $this->multipart = $val;
    }
    
    /**
     * Validate and set $charset
     *
     * @param  $val string
     * @return void
     */
    private function initialize_charset($val) {
        if ( ! is_string($val)) {
            $this->invalid_argument_value('charset');
        }
        $this->charset = $val;
    }
    
    /**
     * Validate and set $validate
     *
     * @param  $val bool
     * @return void
     */
    private function initialize_validate($val) {
        if ( ! is_bool($val)) {
            $this->invalid_argument_value('validate');
        }
        $this->validate = $val;
    }
    
    /**
     * Validate and set $priority
     *
     * @param  $val int
     * @return void
     */
    private function initialize_priority($val) {
        if ( ! is_int($val) || ! in_array($val, array(1, 2, 3, 4, 5))) {
            $this->invalid_argument_value('priority');
        }
        $this->priority = $val;
    }
    
    /**
     * Validate and set $crlf
     *
     * @param  $val string
     * @return void
     */
    private function initialize_crlf($val) {
        if ( ! is_string($val) || ! in_array($val, array("\n", "\r\n", "\r"))) {
            $this->invalid_argument_value('crlf');
        }
        $this->crlf = $val;
    }
    
    /**
     * Validate and set $newline
     *
     * @param  $val string
     * @return void
     */
    private function initialize_newline($val) {
        if ( ! is_string($val) || ! in_array($val, array("\n", "\r\n", "\r"))) {
            $this->invalid_argument_value('newline');
        }
        $this->newline = $val;
    }
    
    /**
     * Validate and set $send_multipart
     *
     * @param  $val bool
     * @return void
     */
    private function initialize_dsn($val) {
        if ( ! is_bool($val)) {
            $this->invalid_argument_value('dsn');
        }
        $this->dsn = $val;
    }
    
    /**
     * Validate and set $send_multipart
     *
     * @param  $val bool
     * @return void
     */
    private function initialize_send_multipart($val) {
        if ( ! is_bool($val)) {
            $this->invalid_argument_value('send_multipart');
        }
        $this->send_multipart = $val;
    }
    
    /**
     * Validate and set $bcc_batch_mode
     *
     * @param  $val bool
     * @return void
     */
    private function initialize_bcc_batch_mode($val) {
        if ( ! is_bool($val)) {
            $this->invalid_argument_value('bcc_batch_mode');
        }
        $this->bcc_batch_mode = $val;
    }
    
    /**
     * Validate and set $bcc_batch_size
     *
     * @param  $val int
     * @return void
     */
    private function initialize_bcc_batch_size($val) {
        if ( ! is_int($val)) {
            $this->invalid_argument_value('bcc_batch_size');
        }
        $this->bcc_batch_size = $val;
    }
    
    /**
     * Output the error message for invalid argument value
     *
     * @return void
     */
    private function invalid_argument_value($arg) {
        exit("In your config array, the provided argument value of "."'".$arg."'"." is invalid.");
    }
    
    /**
     * Destructor - Releases Resources
     *
     * @return    void
     */
    public function __destruct() {
        if (is_resource($this->smtp_connect)) {
            $this->send_command('quit');
        }
    }
    
    
    /**
     * Initialize the Email Data
     *
     * @param    bool
     * @return    Email_Library
     */
    public function clear($clear_attachments = FALSE) {
        $this->subject = '';
        $this->body = '';
        $this->finalbody = '';
        $this->header_str = '';
        $this->replyto_flag = FALSE;
        $this->recipients = array();
        $this->cc_array = array();
        $this->bcc_array = array();
        $this->headers = array();
        $this->debug_msg = array();
        
        $this->add_header('User-Agent', $this->user_agent);
        $this->add_header('Date', $this->set_date());
        
        if ($clear_attachments !== FALSE) {
            $this->attach_name = array();
            $this->attach_type = array();
            $this->attach_disp = array();
        }
        
        // This will return the instance this method is called on. 
        // This usually done for achieving fluent interfaces
        return $this;
    }
    
    /**
     * Set FROM
     *
     * @param    string
     * @param    string
     * @param    string    Return-Path Return-Path is the address where bounce messages (undeliverable notifications, etc.) should be delivered.
     * @return    Email_Library
     */
    public function from($from, $name = '', $return_path = NULL) {
        if (preg_match('/\<(.*)\>/', $from, $match)) {
            $from = $match['1'];
        }
        
        if ($this->validate) {
            $this->validate_email($this->str_to_array($from));
            if ($return_path) {
                $this->validate_email($this->str_to_array($return_path));
            }
        }
        
        // prepare the display name
        if ($name !== '') {
            // only use Q encoding if there are characters that would require it
            if ( ! preg_match('/[\200-\377]/', $name)) {
                // add slashes for non-printing characters, slashes, and double quotes, and surround it in double quotes
                $name = '"'.addcslashes($name, "\0..\37\177'\"\\").'"';
            } else {
                $name = $this->prep_q_encoding($name);
            }
        }
        
        $this->add_header('From', $name.' <'.$from.'>');
        
        $return_path = isset($return_path) ? $return_path : $from;
        $this->add_header('Return-Path', '<'.$return_path.'>');

        return $this;
    }
    
    /**
     * Set Reply-to
     *
     * @param    string
     * @param    string
     * @return    Email_Library
     */
    public function reply_to($replyto, $name = '') {
        if (preg_match('/\<(.*)\>/', $replyto, $match)) {
            $replyto = $match['1'];
        }
        
        if ($this->validate) {
            $this->validate_email($this->str_to_array($replyto));
        }
        
        if ($name === '') {
            $name = $replyto;
        }
        
        if (strpos($name, '"') !== 0) {
            $name = '"'.$name.'"';
        }
        
        $this->add_header('Reply-To', $name.' <'.$replyto.'>');
        $this->replyto_flag = TRUE;
        
        return $this;
    }
    
    /**
     * Set Recipients
     *
     * @param    string
     * @return    Email_Library
     */
    public function to($to) {
        $to = $this->str_to_array($to);
        $to = $this->clean_email($to);
        
        if ($this->validate) {
            $this->validate_email($to);
        }
        
        if ($this->get_protocol() !== 'mail') {
            $this->add_header('To', implode(", ", $to));
        }
        
        switch ($this->get_protocol()) {
            case 'smtp' :
                $this->recipients = $to;
                break;
            
            case 'sendmail'    :
            case 'mail'    :
                $this->recipients = implode(", ", $to);
                break;
        }
        
        return $this;
    }
    
    /**
     * Set CC
     *
     * @param    string
     * @return    Email_Library
     */
    public function cc($cc) {
        $cc = $this->str_to_array($cc);
        $cc = $this->clean_email($cc);
        
        if ($this->validate) {
            $this->validate_email($cc);
        }
        
        $this->add_header('Cc', implode(", ", $cc));
        
        if ($this->get_protocol() === 'smtp') {
            $this->cc_array = $cc;
        }
        
        return $this;
    }
    
    /**
     * Set BCC
     *
     * @param    string
     * @param    string
     * @return    Email_Library
     */
    public function bcc($bcc, $limit = '') {
        if ($limit !== '' && is_numeric($limit)) {
            $this->bcc_batch_mode = TRUE;
            $this->bcc_batch_size = $limit;
        }
        
        $bcc = $this->str_to_array($bcc);
        $bcc = $this->clean_email($bcc);
        
        if ($this->validate) {
            $this->validate_email($bcc);
        }
        
        if (($this->get_protocol() === 'smtp') || ($this->bcc_batch_mode && count($bcc) > $this->bcc_batch_size)) {
            $this->bcc_array = $bcc;
        } else {
            $this->add_header('Bcc', implode(", ", $bcc));
        }
        
        return $this;
    }
    
    
    /**
     * Set Email Subject
     *
     * @param    string
     * @return    Email_Library
     */
    public function subject($subject) {
        $subject = $this->prep_q_encoding($subject);
        $this->add_header('Subject', $subject);
        return $this;
    }
    
    /**
     * Set Body
     *
     * @param    string
     * @return    Email_Library
     */
    public function message($body) {
        $this->body = rtrim(str_replace("\r", '', $body));
        
        // strip slashes only if magic quotes is ON
        // if we do it with magic quotes OFF, it strips real, user-inputted chars.
        // NOTE: Returns 0 if magic_quotes_gpc is off, 1 otherwise. 
        // Or always returns FALSE as of PHP 5.4.0 because the magic quotes feature was removed from PHP.
        if (version_compare(PHP_VERSION, '5.4', '<') && get_magic_quotes_gpc()) {
            $this->body = stripslashes($this->body);
        }
        
        return $this;
    }
    
    /**
     * Set alternative message (for HTML messages only)
     *
     * @param    string
     * @return    Email_Library
     */
    public function set_alt_message($str = '') {
        $this->alt_message = $str;
        return $this;
    }
    
    
    /**
     * Assign file attachments
     *
     * @param    string
     * @return    Email_Library
     */
    public function attach($filename, $disposition = 'attachment') {
        $this->attach_name[] = $filename;
        $this->attach_type[] = $this->mime_types(pathinfo($filename, PATHINFO_EXTENSION));
        $this->attach_disp[] = $disposition; // Can also be 'inline'  Not sure if it matters
        return $this;
    }
    
    /**
     * Add a Header Item
     *
     * @param    string
     * @param    string
     * @return    void
     */
    private function add_header($header, $value) {
        $this->headers[$header] = str_replace(array("\n", "\r"), '', $value);
    }
    
    /**
     * Convert a String to an Array
     *
     * @param    string
     * @return    array
     */
    private function str_to_array($email) {
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
     * Set Message Boundary
     *
     * @return    void
     */
    private function set_boundaries() {
        $this->alt_boundary = "B_ALT_".uniqid(''); // multipart/alternative
        $this->atc_boundary = "B_ATC_".uniqid(''); // attachment boundary
    }
    
    /**
     * Get the Message ID
     *
     * @return    string
     */
    private function get_message_id() {
        $from = str_replace(array('>', '<'), '', $this->headers['Return-Path']);
        return '<'.uniqid('').strstr($from, '@').'>';
    }
    
    /**
     * Get Mail Protocol
     *
     * @param    bool
     * @return    string
     */
    private function get_protocol($return = TRUE) {
        $this->protocol = strtolower($this->protocol);
        $this->protocol = ( ! in_array($this->protocol, $this->protocols, TRUE)) ? 'mail' : $this->protocol;
        
        if ($return === TRUE) {
            return $this->protocol;
        }
    }
    
    /**
     * Get Mail Encoding
     *
     * @param    bool
     * @return    string
     */
    private function get_encoding($return = TRUE) {
        $this->encoding = in_array($this->encoding, $this->bit_depths) ? $this->encoding : '8bit';
        
        foreach ($this->base_charsets as $charset) {
            if (strpos($charset, $this->charset) === 0) {
                $this->encoding = '7bit';
            }
        }
        
        if ($return === TRUE) {
            return $this->encoding;
        }
    }
    
    /**
     * Get content type (text/html/attachment)
     *
     * @return    string
     */
    private function get_content_type() {
        if ($this->mailtype === 'html') {
            return (count($this->attach_name) === 0) ? 'html' : 'html-attach';
        } elseif    ($this->mailtype === 'text' && count($this->attach_name) > 0) {
            return 'plain-attach';
        } else {
            return 'plain';
        }
    }
    
    /**
     * Set RFC 822 Date
     *
     * @return    string
     */
    private function set_date() {
        $timezone = date('Z');
        $operator = ($timezone[0] === '-') ? '-' : '+';
        $timezone = abs($timezone);
        $timezone = floor($timezone/3600) * 100 + ($timezone % 3600 ) / 60;
        
        return sprintf("%s %s%04d", date("D, j M Y H:i:s"), $operator, $timezone);
    }
    
    /**
     * Mime message
     *
     * @return    string
     */
    private function get_mime_message() {
        return "This is a multi-part message in MIME format.".$this->newline."Your email application may not support this format.";
    }
    
    /**
     * Validate Email Address
     *
     * @param    string
     * @return    bool
     */
    private function validate_email($email) {
        if ( ! is_array($email)) {
            $this->set_error_message('email_must_be_array');
            return FALSE;
        }
        
        foreach ($email as $val) {
            $is_valid = ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $val)) ? FALSE : TRUE;
            
            if ( ! $is_valid) {
                $this->set_error_message('email_invalid_address', $val);
                return FALSE;
            }
        }
        
        return TRUE;
    }
    
    /**
     * Clean Extended Email Address: Joe Smith <joe@smith.com>
     *
     * @param    string
     * @return    string
     */
    private function clean_email($email) {
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
     * Provides the raw message for use in plain-text headers of HTML-formatted emails.
     * If the user hasn't specified his own alternative message it creates one by stripping the HTML
     *
     * @return    string
     */
    private function get_alt_message() {
        if ( ! empty($this->alt_message)) {
            return ($this->wordwrap) ? $this->word_wrap($this->alt_message, 76) : $this->alt_message;
        }
        
        $body = preg_match('/\<body.*?\>(.*)\<\/body\>/si', $this->body, $match) ? $match[1] : $this->body;
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
     * @param    string
     * @param    integer line-length limit
     * @return    string
     */
    private function word_wrap($str, $charlim = NULL) {
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
     * @param    string
     * @return    string
     */
    private function build_headers() {
        $this->add_header('X-Sender', $this->clean_email($this->headers['From']));
        $this->add_header('X-Mailer', $this->user_agent);
        $this->add_header('X-Priority', $this->priorities[$this->priority - 1]);
        $this->add_header('Message-ID', $this->get_message_id());
        $this->add_header('Mime-Version', '1.0');
    }
    
    /**
     * Write Headers as a string
     *
     * @return    void
     */
    private function write_headers() {
        if ($this->protocol === 'mail') {
            $this->subject = $this->headers['Subject'];
            unset($this->headers['Subject']);
        }
        
        reset($this->headers);
        $this->header_str = '';
        
        foreach ($this->headers as $key => $val) {
            $val = trim($val);
            
            if ($val !== '') {
                $this->header_str .= $key.": ".$val.$this->newline;
            }
        }
        
        if ($this->get_protocol() === 'mail') {
            $this->header_str = rtrim($this->header_str);
        }
    }
    
    /**
     * Build Final Body and attachments
     *
     * @return    void
     */
    private function build_message() {
        if ($this->wordwrap === TRUE  &&  $this->mailtype !== 'html') {
            $this->body = $this->word_wrap($this->body);
        }
        
        $this->set_boundaries();
        $this->write_headers();
        
        $hdr = ($this->get_protocol() === 'mail') ? $this->newline : '';
        $body = '';
        
        switch ($this->get_content_type()) {
            case 'plain' :
                $hdr .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
                $hdr .= "Content-Transfer-Encoding: " . $this->get_encoding();
                
                if ($this->get_protocol() === 'mail') {
                    $this->header_str .= $hdr;
                    $this->finalbody = $this->body;
                } else {
                    $this->finalbody = $hdr . $this->newline . $this->newline . $this->body;
                }
                
                return;
                
                break;
            
            case 'html' :
                if ($this->send_multipart === FALSE) {
                    $hdr .= "Content-Type: text/html; charset=" . $this->charset . $this->newline;
                    $hdr .= "Content-Transfer-Encoding: quoted-printable";
                } else {
                    $hdr .= "Content-Type: multipart/alternative; boundary=\"" . $this->alt_boundary . "\"" . $this->newline . $this->newline;
                    
                    $body .= $this->get_mime_message() . $this->newline . $this->newline;
                    $body .= "--" . $this->alt_boundary . $this->newline;
                    
                    $body .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
                    $body .= "Content-Transfer-Encoding: " . $this->get_encoding() . $this->newline . $this->newline;
                    $body .= $this->get_alt_message() . $this->newline . $this->newline . "--" . $this->alt_boundary . $this->newline;
                    
                    $body .= "Content-Type: text/html; charset=" . $this->charset . $this->newline;
                    $body .= "Content-Transfer-Encoding: quoted-printable" . $this->newline . $this->newline;
                }
                
                $this->finalbody = $body . $this->prep_quoted_printable($this->body) . $this->newline . $this->newline;
                
                
                if ($this->get_protocol() === 'mail') {
                    $this->header_str .= $hdr;
                } else {
                    $this->finalbody = $hdr . $this->finalbody;
                }
                
                if ($this->send_multipart !== FALSE) {
                    $this->finalbody .= "--" . $this->alt_boundary . "--";
                }
                
                return;

                break;
            
            case 'plain-attach' :
                $hdr .= "Content-Type: multipart/".$this->multipart."; boundary=\"" . $this->atc_boundary."\"" . $this->newline . $this->newline;
                
                if ($this->get_protocol() === 'mail') {
                    $this->header_str .= $hdr;
                }
                
                $body .= $this->get_mime_message() . $this->newline . $this->newline;
                $body .= "--" . $this->atc_boundary . $this->newline;
                
                $body .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
                $body .= "Content-Transfer-Encoding: " . $this->get_encoding() . $this->newline . $this->newline;
                $body .= $this->body . $this->newline . $this->newline;

                break;
            
            case 'html-attach' :
                $hdr .= "Content-Type: multipart/".$this->multipart."; boundary=\"" . $this->atc_boundary."\"" . $this->newline . $this->newline;
                
                if ($this->get_protocol() === 'mail') {
                    $this->header_str .= $hdr;
                }
                
                $body .= $this->get_mime_message() . $this->newline . $this->newline;
                $body .= "--" . $this->atc_boundary . $this->newline;
                
                $body .= "Content-Type: multipart/alternative; boundary=\"" . $this->alt_boundary . "\"" . $this->newline .$this->newline;
                $body .= "--" . $this->alt_boundary . $this->newline;
                
                $body .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
                $body .= "Content-Transfer-Encoding: " . $this->get_encoding() . $this->newline . $this->newline;
                $body .= $this->get_alt_message() . $this->newline . $this->newline . "--" . $this->alt_boundary . $this->newline;
                
                $body .= "Content-Type: text/html; charset=" . $this->charset . $this->newline;
                $body .= "Content-Transfer-Encoding: quoted-printable" . $this->newline . $this->newline;
                
                $body .= $this->prep_quoted_printable($this->body) . $this->newline . $this->newline;
                $body .= "--" . $this->alt_boundary . "--" . $this->newline . $this->newline;
                
                break;
        }
        
        $attachment = array();
        
        $z = 0;
        
        for ($i = 0; $i < count($this->attach_name); $i++) {
            $filename = $this->attach_name[$i];
            $basename = basename($filename);
            $ctype = $this->attach_type[$i];
            
            if ( ! file_exists($filename)) {
                $this->set_error_message('email_attachment_missing', $filename);
                return FALSE;
            }
            
            $h  = "--".$this->atc_boundary.$this->newline;
            $h .= "Content-type: ".$ctype."; ";
            $h .= "name=\"".$basename."\"".$this->newline;
            $h .= "Content-Disposition: ".$this->attach_disp[$i].";".$this->newline;
            $h .= "Content-Transfer-Encoding: base64".$this->newline;
            
            $attachment[$z++] = $h;
            $file = filesize($filename) +1;
            
            if ( ! $fp = fopen($filename, 'rb')) {
                $this->set_error_message('email_attachment_unreadable', $filename);
                return FALSE;
            }
            
            $attachment[$z++] = chunk_split(base64_encode(fread($fp, $file)));
            fclose($fp);
        }
        
        $body .= implode($this->newline, $attachment).$this->newline."--".$this->atc_boundary."--";
        
        
        if ($this->get_protocol() === 'mail') {
            $this->finalbody = $body;
        } else {
            $this->finalbody = $hdr . $body;
        }
        
        return;
    }
    
    /**
     * Prep Quoted Printable
     *
     * Prepares string for Quoted-Printable Content-Transfer-Encoding
     * Refer to RFC 2045 http://www.ietf.org/rfc/rfc2045.txt
     *
     * @param    string
     * @return    string
     */
    private function prep_quoted_printable($str) {    
        // We are intentionally wrapping so mail servers will encode characters
        // properly and MUAs will behave, so {unwrap} must go!
        $str = str_replace(array('{unwrap}', '{/unwrap}'), '', $str);
        
        // RFC 2045 specifies CRLF as "\r\n".
        // However, many developers choose to override that and violate
        // the RFC rules due to (apparently) a bug in MS Exchange,
        // which only works with "\n".
        if ($this->crlf === "\r\n") {
            // Convert a 8 bit string to a quoted-printable string
            // This function requries PHP 5.3.0 or newer
            // This function is similar to imap_8bit(), except this one does not require the IMAP module to work
            return quoted_printable_encode($str);
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
     * @param    str
     * @param    bool    // set to TRUE for processing From: headers
     * @return    str
     */
    private function prep_q_encoding($str) {
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
     * @param    bool    $auto_clear = TRUE
     * @return    bool
     */
    public function send($auto_clear = TRUE) {
        if ($this->replyto_flag === FALSE) {
            $this->reply_to($this->headers['From']);
        }
        
        if (( ! isset($this->recipients) && ! isset($this->headers['To']))  
                && ( ! isset($this->bcc_array) && ! isset($this->headers['Bcc'])) 
                && ( ! isset($this->headers['Cc']))) {
            $this->set_error_message('email_no_recipients');
            return FALSE;
        }
        
        $this->build_headers();
        
        if ($this->bcc_batch_mode && (count($this->bcc_array) > $this->bcc_batch_size)) {
            $result = $this->batch_bcc_send();
            
            if ($result && $auto_clear) {
                $this->clear();
            }
            
            return $result;
        }
        
        $this->build_message();
        
        $result = $this->spool_email();
        
        if ($result && $auto_clear) {
            $this->clear();
        }
        
        return $result;
    }
    
    /**
     * Batch Bcc Send.  Sends groups of BCCs in batches
     *
     * @return    void
     */
    private function batch_bcc_send() {
        $float = $this->bcc_batch_size -1;
        $set = '';
        $chunk = array();
        
        for ($i = 0; $i < count($this->bcc_array); $i++) {
            if (isset($this->bcc_array[$i])) {
                $set .= ", ".$this->bcc_array[$i];
            }
            
            if ($i === $float) {
                $chunk[] = substr($set, 1);
                $float = $float + $this->bcc_batch_size;
                $set = "";
            }
            
            if ($i === count($this->bcc_array)-1) {
                $chunk[] = substr($set, 1);
            }
        }
        
        for ($i = 0; $i < count($chunk); $i++) {
            unset($this->headers['Bcc']);
            unset($bcc);
            
            $bcc = $this->str_to_array($chunk[$i]);
            $bcc = $this->clean_email($bcc);
            
            if ($this->protocol !== 'smtp') {
                $this->add_header('Bcc', implode(", ", $bcc));
            } else {
                $this->bcc_array = $bcc;
            }
            
            $this->build_message();
            $this->spool_email();
        }
    }
    
    /**
     * Unwrap special elements
     *
     * @return    void
     */
    private function unwrap_specials() {
        $this->finalbody = preg_replace_callback("/\{unwrap\}(.*?)\{\/unwrap\}/si", array($this, 'remove_nl_callback'), $this->finalbody);
    }
    
    /**
     * Strip line-breaks via callback
     *
     * @return    string
     */
    private function remove_nl_callback($matches) {
        if (strpos($matches[1], "\r") !== FALSE || strpos($matches[1], "\n") !== FALSE) {
            $matches[1] = str_replace(array("\r\n", "\r", "\n"), '', $matches[1]);
        }
        
        return $matches[1];
    }
    
    /**
     * Spool mail to the mail server
     *
     * @return    bool
     */
    private function spool_email() {
        $this->unwrap_specials();
        
        $method = 'send_with_'.$this->get_protocol();
        if ( ! $this->$method()) {
            $this->set_error_message('email_send_failure_'.($this->get_protocol() === 'mail' ? 'phpmail' : $this->get_protocol()));
            return FALSE;
        }
        
        $this->set_error_message('email_sent', $this->get_protocol());
        return TRUE;
    }
    
    /**
     * Send using mail()
     *
     * @return    bool
     */
    private function send_with_mail() {
        
        if (is_array($this->recipients)) {
            $this->recipients = implode(', ', $this->recipients);
        }
        
        // most documentation of sendmail using the "-f" flag lacks a space after it, however
        // we've encountered servers that seem to require it to be in place.
        return mail($this->recipients, $this->subject, $this->finalbody, $this->header_str, '-f '.$this->clean_email($this->headers['Return-Path']));
    }
    
    /**
     * Send using Sendmail
     *
     * @return    bool
     */
    private function send_with_sendmail() {
        // Opens process file pointer
        $fp = @popen($this->sendmail_path.' -oi -f '.$this->clean_email($this->headers['From']).' -t -r '.$this->clean_email($this->headers['Return-Path']), 'w');
        
        if ($fp === FALSE) {
            // server probably has popen disabled, so nothing we can do to get a verbose error.
            return FALSE;
        }
        
        fputs($fp, $this->header_str);
        fputs($fp, $this->finalbody);
        
        $status = pclose($fp);
        
        if ($status !== 0) {
            $this->set_error_message('email_exit_status', $status);
            $this->set_error_message('email_no_socket');
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * Send using SMTP
     *
     * @return    bool
     */
    private function send_with_smtp() {
        if ($this->smtp_host === '') {
            $this->set_error_message('email_no_hostname');
            return FALSE;
        }
        
        if ( ! $this->smtp_connect() || ! $this->smtp_authenticate()) {
            return FALSE;
        }
        
        $this->send_command('from', $this->clean_email($this->headers['From']));
        
        foreach ($this->recipients as $val) {
            $this->send_command('to', $val);
        }
        
        if (count($this->cc_array) > 0) {
            foreach ($this->cc_array as $val) {
                if ($val !== '') {
                    $this->send_command('to', $val);
                }
            }
        }
        
        if (count($this->bcc_array) > 0) {
            foreach ($this->bcc_array as $val) {
                if ($val !== '') {
                    $this->send_command('to', $val);
                }
            }
        }
        
        $this->send_command('data');
        
        // perform dot transformation on any lines that begin with a dot
        $this->send_data($this->header_str . preg_replace('/^\./m', '..$1', $this->finalbody));
        
        $this->send_data('.');
        
        $reply = $this->get_smtp_data();
        
        $this->set_error_message($reply);
        
        if (strpos($reply, '250') !== 0) {
            $this->set_error_message('email_smtp_error', $reply);
            return FALSE;
        }
        
        if ($this->smtp_keepalive) {
            $this->send_command('reset');
        } else {
            $this->send_command('quit');
        }
        
        return TRUE;
    }
    
    /**
     * SMTP Connect
     *
     * @param    string
     * @return    string
     */
    private function smtp_connect() {
        if (is_resource($this->smtp_connect)) {
            return TRUE;
        }
        
        // OpenSSL extension needed to use ssl
        $ssl = ($this->smtp_crypto === 'ssl') ? 'ssl://' : '';
        
        // You can prefix the hostname with either ssl:// or tls:// to use an SSL or TLS client connection over TCP/IP 
        // to connect to the remote host if OpenSSL support is installed
        $this->smtp_connect = fsockopen($ssl.$this->smtp_host, $this->smtp_port, $errno, $errstr, $this->smtp_timeout);
        
        if ( ! is_resource($this->smtp_connect)) {
            $this->set_error_message('email_smtp_error', $errno.' '.$errstr);
            return FALSE;
        }
        
        stream_set_timeout($this->smtp_connect, $this->smtp_timeout);
        $this->set_error_message($this->get_smtp_data());
        
        if ($this->smtp_crypto === 'tls') {
            $this->send_command('hello');
            $this->send_command('starttls');
            
            $crypto = stream_socket_enable_crypto($this->smtp_connect, TRUE, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        
            if ($crypto !== TRUE) {
                $this->set_error_message('email_smtp_error', $this->get_smtp_data());
                return FALSE;
            }
        }
        
        return $this->send_command('hello');
    }
    
    /**
     * Send SMTP command
     *
     * @param    string
     * @param    string
     * @return    bool
     */
    private function send_command($cmd, $data = '') {
        switch ($cmd) {
            case 'hello' :
                if ($this->smtp_auth || $this->get_encoding() === '8bit') {
                    $this->send_data('EHLO '.$this->get_hostname());
                } else {
                    $this->send_data('HELO '.$this->get_hostname());
                }
                $resp = 250;
                break;
            
            case 'starttls'    :
                $this->send_data('STARTTLS');
                $resp = 220;
                break;
            
            case 'from' :
                $this->send_data('MAIL FROM:<'.$data.'>');
                $resp = 250;
                break;
            
            case 'to' :
                if ($this->dsn) {
                    $this->_send_data('RCPT TO:<'.$data.'> NOTIFY=SUCCESS,DELAY,FAILURE ORCPT=rfc822;'.$data);
                } else {
                    $this->send_data('RCPT TO:<'.$data.'>');
                }
                $resp = 250;
                break;
            
            case 'data'    :
                $this->send_data('DATA');
                $resp = 354;
                break;
            
            case 'reset':
                $this->send_data('RSET');
                $resp = 250;
                
            case 'quit'    :
                $this->send_data('QUIT');
                $resp = 221;
                break;
        }
        
        $reply = $this->get_smtp_data();
        
        $this->debug_msg[] = '<pre>'.$cmd.': '.$reply.'</pre>';
        
        if ((int) substr($reply, 0, 3) !== $resp) {
            $this->set_error_message('email_smtp_error', $reply);
            return FALSE;
        }
        
        if ($cmd === 'quit') {
            fclose($this->smtp_connect);
        }
        
        return TRUE;
    }
    
    /**
     *  SMTP Authenticate
     *
     * @return    bool
     */
    private function smtp_authenticate() {
        if ( ! $this->smtp_auth) {
            return TRUE;
        }
        
        if ($this->smtp_user === '' && $this->smtp_pass === '') {
            $this->set_error_message('email_no_smtp_unpw');
            return FALSE;
        }
        
        $this->send_data('AUTH LOGIN');
        
        $reply = $this->get_smtp_data();
        
        if (strpos($reply, '503') === 0) {
            // Already authenticated
            return TRUE;
        } elseif (strpos($reply, '334') !== 0) {
            $this->set_error_message('email_failed_smtp_login', $reply);
            return FALSE;
        }
        
        $this->send_data(base64_encode($this->smtp_user));
        
        $reply = $this->get_smtp_data();
        
        if (strpos($reply, '334') !== 0) {
            $this->set_error_message('email_smtp_auth_un', $reply);
            return FALSE;
        }
        
        $this->send_data(base64_encode($this->smtp_pass));
        
        $reply = $this->get_smtp_data();
        
        if (strpos($reply, '235') !== 0) {
            $this->set_error_message('email_smtp_auth_pw', $reply);
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * Send SMTP data
     *
     * @return    bool
     */
    private function send_data($data) {
        if ( ! fwrite($this->smtp_connect, $data . $this->newline)) {
            $this->set_error_message('email_smtp_data_failure', $data);
            return FALSE;
        } 
        
        return TRUE;
    }
    
    /**
     * Get SMTP data
     *
     * @return    string
     */
    private function get_smtp_data() {
        $data = '';
        
        while ($str = fgets($this->smtp_connect, 512)) {
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
     * @return    string
     */
    private function get_hostname() {
        return (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : 'localhost.localdomain';
    }
    
    /**
     * Get Debug Message
     *
     * @param    array    $include    List of raw data chunks to include in the output
     *                    Valid options are: 'headers', 'subject', 'body'
     * @return    string
     */
    public function print_debugger($include = array('headers', 'subject', 'body')) {
        $msg = '';
        
        if (count($this->debug_msg) > 0) {
            foreach ($this->debug_msg as $val) {
                $msg .= $val;
            }
        }
        
        // Determine which parts of our raw data needs to be printed
        $raw_data = '';
        $include = is_array($include) ? $include : array($include);
        
        if (in_array('headers', $include, TRUE)) {
            $raw_data = $this->header_str."\n";
        }
        
        if (in_array('subject', $include, TRUE)) {
            $raw_data .= htmlspecialchars($this->subject)."\n";
        }
        
        if (in_array('body', $include, TRUE)) {
            $raw_data .= htmlspecialchars($this->finalbody);
        }
        
        return $msg.($raw_data === '' ? '' : '<pre>'.$raw_data.'</pre>');
    }
    
    /**
     * Set Message
     *
     * @param    string    $msg
     * @param    string    $val = ''
     * @return    void
     */
    private function set_error_message($msg, $val = '') {
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
            $this->debug_msg[] = str_replace('%s', $val, $error_messages[$msg])."<br />";
        } else {
            $this->debug_msg[] = str_replace('%s', $val, $msg)."<br />";
        }
    }
    
    /**
     * Mime Types
     *
     * @param    string
     * @return    string
     */
    private function mime_types($ext = "") {
        $mimes = array(    
            'hqx'    =>    'application/mac-binhex40',
            'cpt'    =>    'application/mac-compactpro',
            'doc'    =>    'application/msword',
            'bin'    =>    'application/macbinary',
            'dms'    =>    'application/octet-stream',
            'lha'    =>    'application/octet-stream',
            'lzh'    =>    'application/octet-stream',
            'exe'    =>    'application/octet-stream',
            'class'    =>    'application/octet-stream',
            'psd'    =>    'application/octet-stream',
            'so'    =>    'application/octet-stream',
            'sea'    =>    'application/octet-stream',
            'dll'    =>    'application/octet-stream',
            'oda'    =>    'application/oda',
            'pdf'    =>    'application/pdf',
            'ai'    =>    'application/postscript',
            'eps'    =>    'application/postscript',
            'ps'    =>    'application/postscript',
            'smi'    =>    'application/smil',
            'smil'    =>    'application/smil',
            'mif'    =>    'application/vnd.mif',
            'xls'    =>    'application/vnd.ms-excel',
            'ppt'    =>    'application/vnd.ms-powerpoint',
            'wbxml'    =>    'application/vnd.wap.wbxml',
            'wmlc'    =>    'application/vnd.wap.wmlc',
            'dcr'    =>    'application/x-director',
            'dir'    =>    'application/x-director',
            'dxr'    =>    'application/x-director',
            'dvi'    =>    'application/x-dvi',
            'gtar'    =>    'application/x-gtar',
            'php'    =>    'application/x-httpd-php',
            'php4'    =>    'application/x-httpd-php',
            'php3'    =>    'application/x-httpd-php',
            'phtml'    =>    'application/x-httpd-php',
            'phps'    =>    'application/x-httpd-php-source',
            'js'    =>    'application/x-javascript',
            'swf'    =>    'application/x-shockwave-flash',
            'sit'    =>    'application/x-stuffit',
            'tar'    =>    'application/x-tar',
            'tgz'    =>    'application/x-tar',
            'xhtml'    =>    'application/xhtml+xml',
            'xht'    =>    'application/xhtml+xml',
            'zip'    =>    'application/zip',
            'mid'    =>    'audio/midi',
            'midi'    =>    'audio/midi',
            'mpga'    =>    'audio/mpeg',
            'mp2'    =>    'audio/mpeg',
            'mp3'    =>    'audio/mpeg',
            'aif'    =>    'audio/x-aiff',
            'aiff'    =>    'audio/x-aiff',
            'aifc'    =>    'audio/x-aiff',
            'ram'    =>    'audio/x-pn-realaudio',
            'rm'    =>    'audio/x-pn-realaudio',
            'rpm'    =>    'audio/x-pn-realaudio-plugin',
            'ra'    =>    'audio/x-realaudio',
            'rv'    =>    'video/vnd.rn-realvideo',
            'wav'    =>    'audio/x-wav',
            'bmp'    =>    'image/bmp',
            'gif'    =>    'image/gif',
            'jpeg'    =>    'image/jpeg',
            'jpg'    =>    'image/jpeg',
            'jpe'    =>    'image/jpeg',
            'png'    =>    'image/png',
            'tiff'    =>    'image/tiff',
            'tif'    =>    'image/tiff',
            'css'    =>    'text/css',
            'html'    =>    'text/html',
            'htm'    =>    'text/html',
            'shtml'    =>    'text/html',
            'txt'    =>    'text/plain',
            'text'    =>    'text/plain',
            'log'    =>    'text/plain',
            'rtx'    =>    'text/richtext',
            'rtf'    =>    'text/rtf',
            'xml'    =>    'text/xml',
            'xsl'    =>    'text/xml',
            'mpeg'    =>    'video/mpeg',
            'mpg'    =>    'video/mpeg',
            'mpe'    =>    'video/mpeg',
            'qt'    =>    'video/quicktime',
            'mov'    =>    'video/quicktime',
            'avi'    =>    'video/x-msvideo',
            'movie'    =>    'video/x-sgi-movie',
            'doc'    =>    'application/msword',
            'word'    =>    'application/msword',
            'xl'    =>    'application/excel',
            'eml'    =>    'message/rfc822'
        );
        return ( ! isset($mimes[strtolower($ext)])) ? "application/x-unknown-content-type" : $mimes[strtolower($ext)];
    }
    
}

/* End of file: ./system/libraries/email/email_library.php */
