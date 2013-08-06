<?php
/**
 * Sendmail Library
 * 
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class Sendmail_Library {
    /**
     * Used as the User-Agent and X-Mailer headers' value
     * 
     * @var string
     */
    private $user_agent = 'InfoPotato Sendmail';
    
    /**
     * Path to the Sendmail binary
     * 
     * @var string
     */
    private $sendmail_path = '/usr/sbin/sendmail';
 
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
     * Multipart subtype 
     * 
     * http://en.wikipedia.org/wiki/MIME#Multipart_subtypes
     * 
     * @var string 'mixed' (in the body) or 'related' (separate)
     */
    private $multipart_subtype = 'mixed';
    
    /**
     * Whether to validate all email addresses
     * 
     * @var bool
     */
    private $email_validation = FALSE;
    
    /**
     * The message sender's importance value for X-Priority header value 
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
     * Return-Path
     *
     * @var    string
     */
    private $return_path = '';
    
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
     * Final headers to send
     *
     * @var    string
     */
    private $header_str = '';

    /**
     * Debug messages
     *
     * @see    print_debugger()
     * @var    array
     */
    private $debug_msg = array();
    
    /**
     * To Recipients
     *
     * @var    array
     */
    private $to_recipients = array();
    
    /**
     * CC Recipients
     *
     * @var    array
     */
    private $cc_recipients = array();
    
    /**
     * BCC Recipients
     *
     * @var    array
     */
    private $bcc_recipients = array();
    
    /**
     * Message headers
     *
     * @var    array
     */
    private $headers = array();
    
    /**
     * Attachment data
     *
     * @var    array
     */
    private $attachments = array(); 
    
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
     * Validate and set $wordwrap
     *
     * @param  $val bool
     * @return void
     */
    private function initialize_wordwrap($val) {
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
     * Validate and set $multipart_subtype
     *
     * @param  $val string
     * @return void
     */
    private function initialize_multipart_subtype($val) {
        if ( ! is_string($val) || ! in_array($val, array('mixed', 'related'))) {
            $this->invalid_argument_value('multipart_subtype');
        }
        $this->multipart_subtype = $val;
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
     * Validate and set $email_validation
     *
     * @param  $val bool
     * @return void
     */
    private function initialize_validate($val) {
        if ( ! is_bool($val)) {
            $this->invalid_argument_value('email_validation');
        }
        $this->email_validation = $val;
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
        
        // Actual values to send with the X-Priority header (http://tools.ietf.org/html/rfc4356#section-2.1.3.3.1)
        $priorities = array('1 (Highest)', '2 (High)', '3 (Normal)', '4 (Low)', '5 (Lowest)');

        $this->priority = $priorities[$val - 1];
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
        exit('In your config array, the provided argument value of '."'".$arg."'".' is invalid.');
    }

    /**
     * Reset the Email Data
     *
     * @param    bool
     * @return    Email_Library
     */
    public function clear($clear_attachments = FALSE) {
        $this->subject = '';
        $this->return_path = '';
        $this->body = '';
        $this->finalbody = '';
        $this->header_str = '';
        $this->to_recipients = array();
        $this->cc_recipients = array();
        $this->bcc_recipients = array();
        $this->headers = array();
        $this->debug_msg = array();

        // You need to set $clear_attachments = TRUE if attachments are sent in loop
        if ($clear_attachments === TRUE) {
            $this->attachments = array();
        }
        
        // This will return the instance this method is called on. 
        // This usually done for achieving fluent interfaces
        return $this;
    }
    
    /**
     * Set FROM
     *
     * Specifies who actually wrote the email, and usually who sent it
     *
     * @param    string
     * @param    string
     * @return    Email_Library
     */
    public function from($from, $name = '') {
        // RFC-822(http://tools.ietf.org/html/rfc822) allows email addresses to be specified 
        // either by a pure email-style address, called an "addr-spec" (e.g., name@host.domain); 
        // or by using a nickname ("phrase") with the email-style address (the "addr-spec") enclosed 
        // in angle brackets (Foo Bar <foobar@host.domain>).
        if (preg_match('/\<(.*)\>/', $from, $match)) {
            $from = $match[1];
        }
        
        if ($this->email_validation) {
            $this->validate_email($this->str_to_array($from));
        }
        
        // Prepare the display name
        if ($name !== '') {
            // Only use Q encoding if there are characters that would require it
            if ( ! preg_match('/[\200-\377]/', $name)) {
                // Add slashes for non-printing characters, slashes, and double quotes, and surround it in double quotes
                $name = '"'.addcslashes($name, "\0..\37\177'\"\\").'"';
            } else {
                $name = $this->prep_q_encoding($name);
            }
        }
        
        // Note that there need not be a space between $name  and  the '<',  
        // but  adding a space enhances readability.
        $this->set_header('From', $name.' <'.$from.'>');

        return $this;
    }
    
    /**
     * Set address for bouncing notifications
     *
     * If set, this email address will be used for the bounce messages
     * If not set, SMTP and Sendmail will use the address given in from() as Return-Path
     *
     * @param    string
     * @return    Email_Library
     */
    public function return_path($return_path) {
        if (preg_match('/\<(.*)\>/', $return_path, $match)) {
            $return_path = $match[1];
        }
        
        if ($this->email_validation) {
            $this->validate_email($this->str_to_array($return_path));
        }

        // SMTP servers do not look at the message headers, so the Return-Path headers and others are irrelevant. 
        // In SMTP, this address will be passed to the FROM command (where and bounced messages will go).
        // In Sendmail, this address will be passed to the -r papameter
        $this->return_path = $return_path;

        // When the delivery SMTP server makes the "final delivery" of a message, it inserts 
        // a Return-Path header at the beginning of the mail data. We don't need to set it here.

        return $this;
    }
    
    /**
     * Set Reply-To
     *
     * If not specified, will use the address given in from() 
     *
     * @param    string
     * @param    string
     * @return    Email_Library
     */
    public function reply_to($reply_to, $name = '') {
        if (preg_match('/\<(.*)\>/', $reply_to, $match)) {
            $reply_to = $match[1];
        }
        
        if ($this->email_validation) {
            $this->validate_email($this->str_to_array($reply_to));
        }
        
        if ($name === '') {
            $name = $reply_to;
        }
        
        if (strpos($name, '"') !== 0) {
            $name = '"'.$name.'"';
        }
        
        $this->set_header('Reply-To', $name.' <'.$reply_to.'>');
        
        return $this;
    }
    
    /**
     * Set Recipients, a comma-delimited list or an array
     *
     * @param    string | array
     * @return    Email_Library
     */
    public function to($to) {
        $to = $this->extract_email($this->str_to_array($to));
        
        if ($this->email_validation) {
            $this->validate_email($to);
        }
        
        $this->set_header('To', implode(', ', $to));
        
        $this->to_recipients = $to;

        return $this;
    }
    
    /**
     * Set CC, a comma-delimited list or an array
     *
     * @param    string | array
     * @return    Email_Library
     */
    public function cc($cc) {
        $cc = $this->extract_email($this->str_to_array($cc));
        
        if ($this->email_validation) {
            $this->validate_email($cc);
        }
        
        $this->set_header('Cc', implode(', ', $cc));

        return $this;
    }
    
    /**
     * Set BCC, a comma-delimited list or an array
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

        $bcc = $this->extract_email($this->str_to_array($bcc));
        
        if ($this->email_validation) {
            $this->validate_email($bcc);
        }
        
        if ($this->bcc_batch_mode && count($bcc) > $this->bcc_batch_size) {
            $this->bcc_recipients = $bcc;
        } else {
            $this->set_header('Bcc', implode(', ', $bcc));
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
        // Q Encoding, the form is: "=?charset?encoding?encoded text?=".
        $subject = $this->prep_q_encoding($subject);
        $this->set_header('Subject', $subject);
        return $this;
    }
    
    /**
     * Set message Body
     *
     * @param    string
     * @param    string
     * @return    Email_Library
     */
    public function message($body, $alt_body = '') {
        $this->body = rtrim(str_replace("\r", '', $body));
        
        // Strip slashes only if magic quotes is ON
        // If we do it with magic quotes OFF, it strips real, user-inputted chars.
        // NOTE: Returns 0 if magic_quotes_gpc is off, 1 otherwise. 
        // Or always returns FALSE as of PHP 5.4.0 because the magic quotes feature was removed from PHP.
        if (version_compare(PHP_VERSION, '5.4', '<') && get_magic_quotes_gpc()) {
            $this->body = stripslashes($this->body);
        }
        
        // Set alternative plain text message (for HTML messages only)
        if ($this->mailtype === 'html') {
            // If no alternative message specified, we create one by stripping the HTML message
            if ($alt_body === '') {
                // Fetch content from HTML message
                $alt_body = preg_match('/\<body.*?\>(.*)\<\/body\>/si', $this->body, $match) ? $match[1] : $this->body;
                $alt_body = str_replace("\t", '', preg_replace('#<!--(.*)--\>#', '', trim(strip_tags($alt_body))));
                
                for ($i = 20; $i >= 3; $i--) {
                    $alt_body = str_replace(str_repeat("\n", $i), "\n\n", $alt_body);
                }
                
                // Reduce multiple spaces
                $alt_body = preg_replace('| +|', ' ', $alt_body);
            }
            
            $this->alt_message = ($this->wordwrap) ? $this->word_wrap($alt_body, 76) : $alt_body;
        }
        
        return $this;
    }

    /**
     * Add an attachment that exists on disk
     *
     * @param    string    $file_path
     * @param    string    $newname = '' (optional)
     * @param    string    $content_type = '' (optional)
     * @param    string    $content_disposition = 'attachment' (optional)
     * @return    Email_Library
     */
    public function attach_from_path($file_path, $newname = '', $content_type = '', $content_disposition = '') {
        if ( ! file_exists($file_path)) {
            $this->set_error_message('email_attachment_missing', $file_path);
            return FALSE;
        }

        $file = filesize($file_path) +1;

        if ( ! $fp = fopen($file_path, 'rb')) {
            $this->set_error_message('email_attachment_unreadable', $file_path);
            return FALSE;
        }

        $file_content = fread($fp, $file);
        fclose($fp);
        
        $this->attachments[] = array(
            'name' => ($newname === '') ? basename($file_path) : $newname, // Use new name if there is one provided
            'content' => $file_content,
            'content_type' => ($content_type === '') ? $this->mime_types(pathinfo($file_path, PATHINFO_EXTENSION)) : $content_type,
            'content_disposition' => empty($content_disposition) ? 'attachment' : $content_disposition, // Can also be 'inline'  Not sure if it matters
        );
        
        return $this;
    }
    
    /**
     * Create an attachment on-the-fly
     *
     * @param    string    $file_content
     * @param    string    $filename
     * @param    string    $content_type
     * @param    string    $content_disposition = 'attachment' (optional)
     * @return    Email_Library
     */
    public function attach_from_content($file_content, $filename, $content_type, $content_disposition = '') {
        $this->attachments[] = array(
            'name' => $filename,
            'content' => $file_content,
            'content_type' => $content_type,
            'content_disposition' => empty($content_disposition) ? 'attachment' : $content_disposition, // Can also be 'inline'  Not sure if it matters
        );

        return $this;
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
        // Use the name and address in from() if Reply-To is not specified
        if ( ! isset($this->headers['Reply-To'])) {
            $this->headers['Reply-To'] = $this->headers['From'];
        }
        
        // Check if recipients specified
        if (( ! isset($this->to_recipients) && ! isset($this->headers['To']))  
                && ( ! isset($this->bcc_recipients) && ! isset($this->headers['Bcc'])) 
                && ( ! isset($this->headers['Cc']))) {
            $this->set_error_message('email_no_recipients');
            return FALSE;
        }

        // Build some headers
        $this->set_header('Date', $this->set_date());
        $this->set_header('User-Agent', $this->user_agent);
        // X-headers is the generic term for headers starting with a capital X and a hyphen. 
        // The convention is that X-headers are nonstandard and provided for information only, and that, 
        // conversely, any nonstandard informative header should be given a name starting with "X-". 
        // This convention is frequently violated.
        $this->set_header('X-Mailer', $this->user_agent);
        // X-Priority: does not mark how important an email is. 
        // It marks how important the sender of the email thinks it is.
        $this->set_header('X-Priority', $this->priority);
        $this->set_header('Mime-Version', '1.0');
        // Message-ID: An automatically generated field; used to prevent multiple delivery 
        // and for reference in In-Reply-To 
        // In-Reply-To is used to link related messages together (only applies for reply messages).
        $this->set_header('Message-ID', $this->create_message_id());

        if ($this->bcc_batch_mode && (count($this->bcc_recipients) > $this->bcc_batch_size)) {
            $result = $this->batch_bcc_send();
            
            if ($result && $auto_clear) {
                $this->clear();
            }
            
            return $result;
        }
        
        if ($this->build_message() === FALSE) {
            return FALSE;
        }
        
        $result = $this->spool_email();
        
        if ($result && $auto_clear) {
            $this->clear();
        }
        
        return $result;
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
        
        // Determine which part of our raw data needs to be printed
        $raw_data = '';
        $include = is_array($include) ? $include : array($include);
        
        if (in_array('headers', $include, TRUE)) {
            $raw_data = htmlspecialchars($this->header_str)."\n";
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
     * Batch Bcc Send.  Sends groups of BCCs in batches
     *
     * @return    void
     */
    private function batch_bcc_send() {
        $float = $this->bcc_batch_size - 1;
        $set = '';
        $chunk = array();
        
        for ($i = 0; $i < count($this->bcc_recipients); $i++) {
            if (isset($this->bcc_recipients[$i])) {
                $set .= ', '.$this->bcc_recipients[$i];
            }
            
            if ($i === $float) {
                $chunk[] = substr($set, 1);
                $float = $float + $this->bcc_batch_size;
                $set = '';
            }
            
            if ($i === count($this->bcc_recipients)-1) {
                $chunk[] = substr($set, 1);
            }
        }
        
        for ($i = 0; $i < count($chunk); $i++) {
            unset($this->headers['Bcc']);
            unset($bcc);

            $bcc = $this->extract_email($this->str_to_array($chunk[$i]));
            
            $this->set_header('Bcc', implode(', ', $bcc));
            
            if ($this->build_message() === FALSE) {
                return FALSE;
            }
            
            $this->spool_email();
        }
    }
    
    /**
     * Add a Header Item
     *
     * @param    string
     * @param    string
     * @return    void
     */
    private function set_header($header, $value) {
        // Filters the input by removing all "\\n" and "\\r" characters.
        $this->headers[$header] = str_replace(array("\n", "\r"), '', $value);
    }
    
    /**
     * Convert a String to an Array
     *
     * @param    string | array
     * @return    array
     */
    private function str_to_array($email) {
        if ( ! is_array($email)) {
            return (strpos($email, ',') !== FALSE)
                ? preg_split('/[\s,]/', $email, -1, PREG_SPLIT_NO_EMPTY)
                : (array) trim($email);
        }
        return $email;
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
     * Get the Message ID
     *
     * @return    string
     */
    private function create_message_id() {
        // The "Message-ID:" field provides a unique message identifier that
        // refers to a particular version of a particular message.
        // The Message-ID uses the domain part of the From
        $from = str_replace(array('>', '<'), '', $this->headers['From']);
        return '<'.uniqid('InfoPotato_').strstr($from, '@').'>';
    }

    /**
     * Get message body content type 
     *
     * @return    string
     */
    private function get_content_type() {
        // mailtype can only be 'html' or 'text'
        if ($this->mailtype === 'html') {
            return (count($this->attachments) > 0) ? 'html-attach' : 'html';
        } 
        
        if ($this->mailtype === 'text') {
            return (count($this->attachments) > 0) ? 'plain-attach' : 'plain';
        }
    }
    
    /**
     * Get Content Transfer Encoding
     *
     * http://en.wikipedia.org/wiki/MIME#Content-Transfer-Encoding
     * http://tools.ietf.org/html/rfc2045#section-6
     * 
     * @return    string
     */
    private function get_content_transfer_encoding() {
        // Default mail encoding
        $content_transfer_encoding = '8bit';

        // 'us-ascii' and 'iso-2022-' (excluding language suffix) are character sets valid for 7bit encoding
        if (strpos('us-ascii', $this->charset) === 0 || strpos('iso-2022-', $this->charset) === 0) {
            $content_transfer_encoding = '7bit';
        }
        
        return $content_transfer_encoding;
    }

    /**
     * Mime message
     *
     * @return    string
     */
    private function insert_mime_message() {
        // This text is inserted before the first boundary, 
        // is typically present in every multipart message, 
        // and is not visible to the client unless there is a problem with the e-mail format. 
        // For example, a hard line break might have been inserted in the message in the wrong position.
        return 'This message is in MIME format.'.$this->newline
               .'Because your mail reader does not understand this format, some or all of this message may not be legible.';
    }
    
    /**
     * Validate Email Address
     *
     * @param    array
     * @return    bool
     */
    private function validate_email($email) {
        foreach ($email as $val) {
            // Use PHP's email validate filter
            if ( ! filter_var($val, FILTER_VALIDATE_EMAIL)) {
                $this->_set_error_message('email_invalid_address', $val);
                return FALSE;
            }
        }
        
        return TRUE;
    }

    /**
     * Only extract the email part inside the angle brackets ('<', '>') 
     *
     * Example: 'My name <email@example.com>' should result in 'email@example.com'
     * 
     * @param    string | array
     * @return    string | array
     */
    private function extract_email($email) {
        if ( ! is_array($email)) {
            return preg_match('/\<(.*)\>/', $email, $match) ? $match[1] : $email;
        }
        
        $extracted_email = array();
        
        foreach ($email as $addr) {
            $extracted_email[] = preg_match('/\<(.*)\>/', $addr, $match) ? $match[1] : $addr;
        }
        
        return $extracted_email;
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
        
        // Standardize newlines
        if (strpos($str, "\r") !== FALSE) {
            $str = str_replace(array("\r\n", "\r"), "\n", $str);
        }
        
        // Reduce multiple spaces
        $str = preg_replace('| +\n|', "\n", $str);

        // If the current word is surrounded by {unwrap} tags we'll
        // strip the entire chunk and replace it with a marker.
        $unwrap = array();
        if (preg_match_all("|(\{unwrap\}.+?\{/unwrap\})|s", $str, $matches)) {
            for ($i = 0; $i < count($matches['0']); $i++) {
                $unwrap[] = $matches[1][$i];
                $str = str_replace($matches[1][$i], '{{unwrapped'.$i.'}}', $str);
            }
        }
        
        // Use PHP's native public function to do the initial wordwrap.
        // We set the cut flag to FALSE so that any individual words that are
        // too long get left alone.  In the next step we'll deal with them.
        $str = wordwrap($str, $charlim, "\n", FALSE);
        
        // Split the string into individual lines of text and cycle through them
        $output = '';
        foreach (explode("\n", $str) as $line) {
            // Is the line within the allowed character count?
            // If so we'll join it to the output and continue
            if (strlen($line) <= $charlim) {
                $output .= $line.$this->newline;
                continue;
            }
            
            $temp = '';
            do {
                // If the over-length word is a URL we won't wrap it
                if (preg_match('!\[url.+\]|://|wwww.!', $line)) {
                    break;
                }
                
                // Trim the word down
                $temp .= substr($line, 0, $charlim-1);
                $line = substr($line, $charlim-1);
            } while (strlen($line) > $charlim);
            
            // If $temp contains data it means we had to split up an over-length
            // word into smaller chunks so we'll add it back to our current line
            if ($temp !== '') {
                $output .= $temp.$this->newline;
            }
            
            $output .= $line.$this->newline;
        }
        
        // Put our markers back
        if (count($unwrap) > 0) {
            foreach ($unwrap as $key => $val) {
                $output = str_replace('{{unwrapped'.$key.'}}', $val, $output);
            }
        }
        
        return $output;
    }

    /**
     * Build Final Body and attachments
     *
     * @return    bool
     */
    private function build_message() {
        if ($this->wordwrap === TRUE  &&  $this->mailtype !== 'html') {
            $this->body = $this->word_wrap($this->body);
        }
        
        // Set Message Boundary
        $alt_boundary = 'B_ALT_'.uniqid(''); // For multipart/alternative
        $atc_boundary = 'B_ATC_'.uniqid(''); // For attachment boundary

        // Set the internal pointer of headers array to its first element
        reset($this->headers);
        
        // Final headers string
        $this->header_str = '';

        foreach ($this->headers as $key => $val) {
            $val = trim($val);
            if ($val !== '') {
                $this->header_str .= $key.': '.$val.$this->newline;
            }
        }

        $hdr = '';
        $body = '';
        
        // Assemble the message headers and body content
        switch ($this->get_content_type()) {
            case 'plain' :
                $hdr .= 'Content-Type: text/plain; charset='.$this->charset.$this->newline;
                $hdr .= 'Content-Transfer-Encoding: '.$this->get_content_transfer_encoding();
                
                $this->finalbody = $hdr.$this->newline.$this->newline.$this->body;
                
                return;
            
            case 'html' :
                if ($this->send_multipart === FALSE) {
                    $hdr .= 'Content-Type: text/html; charset='.$this->charset.$this->newline;
                    // quoted-printable is used to encode arbitrary octet sequences into a form that satisfies the rules of 7bit. 
                    // Designed to be efficient and mostly human readable when used for text data consisting primarily of US-ASCII characters 
                    // but also containing a small proportion of bytes with values outside that range.
                    $hdr .= 'Content-Transfer-Encoding: quoted-printable'.$this->newline.$this->newline;
                } else {
                    $hdr .= 'Content-Type: multipart/alternative; boundary="'.$alt_boundary.'"'.$this->newline.$this->newline;
                    
                    $body .= $this->insert_mime_message().$this->newline.$this->newline;
                    $body .= '--'.$alt_boundary.$this->newline;
                    
                    $body .= 'Content-Type: text/plain; charset='.$this->charset.$this->newline;
                    $body .= 'Content-Transfer-Encoding: '.$this->get_content_transfer_encoding().$this->newline.$this->newline;
                    $body .= $this->alt_message.$this->newline.$this->newline.'--'.$alt_boundary.$this->newline;
                    
                    $body .= 'Content-Type: text/html; charset='.$this->charset.$this->newline;
                    $body .= 'Content-Transfer-Encoding: quoted-printable'.$this->newline.$this->newline;
                }
                
                $this->finalbody = $body.$this->prep_quoted_printable($this->body).$this->newline.$this->newline;

                $this->finalbody = $hdr.$this->finalbody;
                
                if ($this->send_multipart !== FALSE) {
                    $this->finalbody .= '--'.$alt_boundary.'--';
                }
                
                return;
            
            case 'plain-attach' :
                $hdr .= 'Content-Type: multipart/'.$this->multipart_subtype.'; boundary="'.$atc_boundary.'"'.$this->newline.$this->newline;

                $body .= $this->insert_mime_message().$this->newline.$this->newline;
                $body .= '--'.$atc_boundary.$this->newline;
                
                $body .= 'Content-Type: text/plain; charset='.$this->charset.$this->newline;
                $body .= 'Content-Transfer-Encoding: '.$this->get_content_transfer_encoding().$this->newline.$this->newline;
                $body .= $this->body.$this->newline.$this->newline;

                break;
            
            case 'html-attach' :
                $hdr .= 'Content-Type: multipart/'.$this->multipart_subtype.'; boundary="'.$atc_boundary.'"'.$this->newline.$this->newline;

                $body .= $this->insert_mime_message().$this->newline.$this->newline;
                $body .= '--'.$atc_boundary.$this->newline;
                
                $body .= 'Content-Type: multipart/alternative; boundary="'.$alt_boundary.'"'.$this->newline.$this->newline;
                $body .= '--'.$alt_boundary.$this->newline;
                
                $body .= 'Content-Type: text/plain; charset='.$this->charset.$this->newline;
                $body .= 'Content-Transfer-Encoding: '.$this->get_content_transfer_encoding().$this->newline.$this->newline;
                $body .= $this->alt_message.$this->newline.$this->newline.'--'.$alt_boundary.$this->newline;
                
                $body .= 'Content-Type: text/html; charset='.$this->charset.$this->newline;
                $body .= 'Content-Transfer-Encoding: quoted-printable'.$this->newline.$this->newline;
                
                $body .= $this->prep_quoted_printable($this->body).$this->newline.$this->newline;
                $body .= '--'.$alt_boundary.'--'.$this->newline.$this->newline;
                
                break;
        }
        
        $attachment = array();

        for ($i = 0, $c = count($this->attachments), $z = 0; $i < $c; $i++) {
            $attachment[$z++] = '--'.$atc_boundary.$this->newline
                .'Content-type: '.$this->attachments[$i]['content_type'].'; '
                .'name="'.$this->attachments[$i]['name'].'"'.$this->newline
                .'Content-Disposition: '.$this->attachments[$i]['content_disposition'].';'.$this->newline
                .'Content-Transfer-Encoding: base64'.$this->newline;

            $attachment[$z++] = chunk_split(base64_encode($this->attachments[$i]['content']));
        }
        
        $body .= implode($this->newline, $attachment).$this->newline.'--'.$atc_boundary.'--';
        $this->finalbody = $hdr.$body;
        
        return TRUE;
    }
    
    /**
     * Prepares string for Quoted-Printable Content-Transfer-Encoding
     * 
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
     * Performs "Q Encoding" on a string for use in email header values.
     * 
     * The form is: "=?charset?encoding?encoded text?=".
     * http://tools.ietf.org/html/rfc2047#section-4.2
     *
     * @param    string
     * @return    string
     */
    private function prep_q_encoding($str) {
        $str = str_replace(array("\r", "\n"), '', $str);
        
        if ($this->charset === 'UTF-8') {
            if (extension_loaded('mbstring')) {
                // Set internal encoding for multibyte string functions
                mb_internal_encoding($this->charset);
                // Encode string for MIME header
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
     * Unwrap special elements
     *
     * @return    void
     */
    private function unwrap_specials() {
        $this->finalbody = preg_replace_callback('/\{unwrap\}(.*?)\{\/unwrap\}/si', array($this, 'remove_nl_callback'), $this->finalbody);
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

        // Sendmail will use the address given to From: header as the return path 
        // if it's not specified using $this->return_path()
        $return_path = ($this->return_path !== '') ? $this->return_path : $this->headers['From'];
        
        // Opens process file pointer
        // Check out http://www.postfix.org/sendmail.1.html for sendmail flags
        // -t extracts recipients from message headers.
        // -r sets the envelope sender address. This  is  the address where delivery problems are sent to.
        $fp = @popen($this->sendmail_path.' -oi -f '.$this->extract_email($this->headers['From']).' -t -r '.$this->extract_email($return_path), 'w');
        
        if ($fp === FALSE) {
            $this->set_error_message('email_send_failure_sendmail');
            
            // Server probably has popen disabled, so nothing we can do to get a verbose error.
            return FALSE;
        }
        
        fputs($fp, $this->header_str);
        fputs($fp, $this->finalbody);
        
        $status = pclose($fp);
        
        if ($status !== 0) {
            $this->set_error_message('email_exit_status', $status);
            $this->set_error_message('email_no_socket');
            
            $this->set_error_message('email_send_failure_sendmail');
            return FALSE;
        }
        
        $this->set_error_message('email_sent', 'sendmail');
        return TRUE;
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
     * Set Message
     *
     * @param    string    $msg
     * @param    string    $val = ''
     * @return    void
     */
    private function set_error_message($msg, $val = '') {
        $error_messages = array(
            'email_invalid_address' => "Invalid email address: %s",
            'email_attachment_missing' => "Unable to locate the following email attachment: %s",
            'email_attachment_unreadable' => "Unable to open this attachment: %s",
            'email_no_recipients' => "You must include recipients: To, Cc, or Bcc",
            'email_send_failure_sendmail' => "Unable to send email using Sendmail.  Your server might not be configured to send mail using this method.",
            'email_sent' => "Your message has been successfully sent using sendmail",
            'email_no_socket' => "Unable to open a socket to Sendmail. Please check settings.",
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
    private function mime_types($ext = '') {
        $mimes = array(
            'hqx' => array('application/mac-binhex40', 'application/mac-binhex', 'application/x-binhex40', 'application/x-mac-binhex40'),
            'cpt' => 'application/mac-compactpro',
            'csv' => array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain'),
            'bin' => array('application/macbinary', 'application/mac-binary', 'application/octet-stream', 'application/x-binary', 'application/x-macbinary'),
            'dms' => 'application/octet-stream',
            'lha' => 'application/octet-stream',
            'lzh' => 'application/octet-stream',
            'exe' => array('application/octet-stream', 'application/x-msdownload'),
            'class' => 'application/octet-stream',
            'psd' => array('application/x-photoshop', 'image/vnd.adobe.photoshop'),
            'so' => 'application/octet-stream',
            'sea' => 'application/octet-stream',
            'dll' => 'application/octet-stream',
            'oda' => 'application/oda',
            'pdf' => array('application/pdf', 'application/force-download', 'application/x-download', 'binary/octet-stream'),
            'ai' => array('application/pdf', 'application/postscript'),
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            'smi' => 'application/smil',
            'smil' => 'application/smil',
            'mif' => 'application/vnd.mif',
            'xls' => array('application/vnd.ms-excel', 'application/msexcel', 'application/x-msexcel', 'application/x-ms-excel', 'application/x-excel', 'application/x-dos_ms_excel', 'application/xls', 'application/x-xls', 'application/excel', 'application/download', 'application/vnd.ms-office', 'application/msword'),
            'ppt' => array('application/powerpoint', 'application/vnd.ms-powerpoint'),
            'pptx' => array('application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/x-zip', 'application/zip'),
            'wbxml' => 'application/wbxml',
            'wmlc' => 'application/wmlc',
            'dcr' => 'application/x-director',
            'dir' => 'application/x-director',
            'dxr' => 'application/x-director',
            'dvi' => 'application/x-dvi',
            'gtar' => 'application/x-gtar',
            'gz' => 'application/x-gzip',
            'gzip' => 'application/x-gzip',
            'php' => array('application/x-httpd-php', 'application/php', 'application/x-php', 'text/php', 'text/x-php', 'application/x-httpd-php-source'),
            'php4' => 'application/x-httpd-php',
            'php3' => 'application/x-httpd-php',
            'phtml' => 'application/x-httpd-php',
            'phps' => 'application/x-httpd-php-source',
            'js' => array('application/x-javascript', 'text/plain'),
            'swf' => 'application/x-shockwave-flash',
            'sit' => 'application/x-stuffit',
            'tar' => 'application/x-tar',
            'tgz' => array('application/x-tar', 'application/x-gzip-compressed'),
            'xhtml' => 'application/xhtml+xml',
            'xht' => 'application/xhtml+xml',
            'zip' => array('application/x-zip', 'application/zip', 'application/x-zip-compressed', 'application/s-compressed', 'multipart/x-zip'),
            'rar' => array('application/x-rar', 'application/rar', 'application/x-rar-compressed'),
            'mid' => 'audio/midi',
            'midi' => 'audio/midi',
            'mpga' => 'audio/mpeg',
            'mp2' => 'audio/mpeg',
            'mp3' => array('audio/mpeg', 'audio/mpg', 'audio/mpeg3', 'audio/mp3'),
            'aif'  => array('audio/x-aiff', 'audio/aiff'),
            'aiff' => array('audio/x-aiff', 'audio/aiff'),
            'aifc' => 'audio/x-aiff',
            'ram' => 'audio/x-pn-realaudio',
            'rm' => 'audio/x-pn-realaudio',
            'rpm' => 'audio/x-pn-realaudio-plugin',
            'ra' => 'audio/x-realaudio',
            'rv' => 'video/vnd.rn-realvideo',
            'wav' => array('audio/x-wav', 'audio/wave', 'audio/wav'),
            'bmp' => array('image/bmp', 'image/x-windows-bmp'),
            'gif' => 'image/gif',
            'jpeg' => array('image/jpeg', 'image/pjpeg'),
            'jpg' => array('image/jpeg', 'image/pjpeg'),
            'jpe' => array('image/jpeg', 'image/pjpeg'),
            'png' => array('image/png',  'image/x-png'),
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'css' => array('text/css', 'text/plain'),
            'html' => array('text/html', 'text/plain'),
            'htm' => array('text/html', 'text/plain'),
            'shtml' => array('text/html', 'text/plain'),
            'txt' => 'text/plain',
            'text' => 'text/plain',
            'log' => array('text/plain', 'text/x-log'),
            'rtx' => 'text/richtext',
            'rtf' => 'text/rtf',
            'xml' => array('application/xml', 'text/xml', 'text/plain'),
            'xsl' => array('application/xml', 'text/xsl', 'text/xml'),
            'mpeg' => 'video/mpeg',
            'mpg' => 'video/mpeg',
            'mpe' => 'video/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            'avi' => array('video/x-msvideo', 'video/msvideo', 'video/avi', 'application/x-troff-msvideo'),
            'movie' => 'video/x-sgi-movie',
            'doc' => array('application/msword', 'application/vnd.ms-office'),
            'docx' => array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/msword', 'application/x-zip'),
            'dot' => array('application/msword', 'application/vnd.ms-office'),
            'dotx' => array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/msword'),
            'xlsx' => array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/vnd.ms-excel', 'application/msword', 'application/x-zip'),
            'word' => array('application/msword', 'application/octet-stream'),
            'xl' => 'application/excel',
            'eml' => 'message/rfc822',
            'json' => array('application/json', 'text/json'),
            'pem' => array('application/x-x509-user-cert', 'application/x-pem-file', 'application/octet-stream'),
            'p10' => array('application/x-pkcs10', 'application/pkcs10'),
            'p12' => 'application/x-pkcs12',
            'p7a' => 'application/x-pkcs7-signature',
            'p7c' => array('application/pkcs7-mime', 'application/x-pkcs7-mime'),
            'p7m' => array('application/pkcs7-mime', 'application/x-pkcs7-mime'),
            'p7r' => 'application/x-pkcs7-certreqresp',
            'p7s' => 'application/pkcs7-signature',
            'crt' => array('application/x-x509-ca-cert', 'application/x-x509-user-cert', 'application/pkix-cert'),
            'crl' => array('application/pkix-crl', 'application/pkcs-crl'),
            'der' => 'application/x-x509-ca-cert',
            'kdb' => 'application/octet-stream',
            'pgp' => 'application/pgp',
            'gpg' => 'application/gpg-keys',
            'sst' => 'application/octet-stream',
            'csr' => 'application/octet-stream',
            'rsa' => 'application/x-pkcs7',
            'cer' => array('application/pkix-cert', 'application/x-x509-ca-cert'),
            '3g2' => 'video/3gpp2',
            '3gp' => 'video/3gp',
            'mp4' => 'video/mp4',
            'm4a' => 'audio/x-m4a',
            'f4v' => 'video/mp4',
            'webm' => 'video/webm',
            'aac' => 'audio/x-acc',
            'm4u' => 'application/vnd.mpegurl',
            'm3u' => 'text/plain',
            'xspf' => 'application/xspf+xml',
            'vlc' => 'application/videolan',
            'wmv' => array('video/x-ms-wmv', 'video/x-ms-asf'),
            'au' => 'audio/x-au',
            'ac3' => 'audio/ac3',
            'flac' => 'audio/x-flac',
            'ogg' => 'audio/ogg',
            'kmz' => array('application/vnd.google-earth.kmz', 'application/zip', 'application/x-zip'),
            'kml' => array('application/vnd.google-earth.kml+xml', 'application/xml', 'text/xml'),
            'ics' => 'text/calendar',
            'zsh' => 'text/x-scriptzsh',
            '7zip' => array('application/x-compressed', 'application/x-zip-compressed', 'application/zip', 'multipart/x-zip'),
            'cdr' => array('application/cdr', 'application/coreldraw', 'application/x-cdr', 'application/x-coreldraw', 'image/cdr', 'image/x-cdr', 'zz-application/zz-winassoc-cdr'),
            'wma' => array('audio/x-ms-wma', 'video/x-ms-asf'),
            'jar' => array('application/java-archive', 'application/x-java-application', 'application/x-jar', 'application/x-compressed')
        );
        
        $ext = strtolower($ext);

        if (isset($mimes[$ext])) {
            // Returns the current element if $mimes[$ext] is an array
            return is_array($mimes[$ext]) ? current($mimes[$ext]) : $mimes[$ext];
        }
        
        return 'application/x-unknown-content-type';
    }
    
}

/* End of file: ./system/libraries/email/sendmail_library.php */
