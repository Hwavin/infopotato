<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Encryption
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Encryption</h1>	

<p>The Encryption Class provides two-way data encryption.  It uses a scheme that either compiles
the message using a randomly hashed bitwise XOR encoding scheme, or is encrypted using
the Mcrypt library.  If Mcrypt is not available on your server the encoded message will
still provide a reasonable degree of security for encrypted sessions or other such "light" purposes.
If Mcrypt is available, you'll be provided with a high degree of security appropriate for storage.</p> 
 
 
<h2>Setting your Key</h2> 
 
<p>A <em>key</em> is a piece of information that controls the cryptographic process and permits an encrypted string to be decoded.
In fact, the key you chose will provide the <strong>only</strong> means to decode data that was encrypted with that key,
so not only must you choose the key carefully, you must never change it if you intend use it for persistent data.</p> 
 
<p>It goes without saying that you should guard your key carefully.
Should someone gain access to your key, the data will be easily decoded.  If your server is not totally under your control
it's impossible to ensure key security so you may want to think carefully before using it for anything
that requires high security, like storing credit card numbers.</p> 
 
<p>To take maximum advantage of the encryption algorithm, your key should be 32 characters in length (128 bits).
The key should be as random a string as you can concoct, with numbers and uppercase and lowercase letters.
Your key should <strong>not</strong> be a simple text string. In order to be cryptographically secure it
needs to be as random as possible.</p> 
 
<p>You can design your own storage mechanism and pass the key dynamically when encoding/decoding.</p> 
 
<h2>Message Length</h2> 
 
<p>It's important for you to know that the encoded messages the encryption function generates will be approximately 2.6 times longer than the original
message.  For example, if you encrypt the string "my super secret data", which is 21 characters in length, you'll end up
with an encoded string that is roughly 55 characters (we say "roughly" because the encoded string length increments in
64 bit clusters, so it's not exactly linear).  Keep this information in mind when selecting your data storage mechanism.  Cookies,
for example, can only hold 4K of information.</p> 
 
 
<h2>Initializing the Class</h2> 
 
<p>Like most other classes in CodeIgniter, the Encryption class is initialized in your controller using the <dfn>$this->load->library</dfn> function:</p> 
 
<code>$this->load_library('encrypt');</code> 
<p>Once loaded, the Encrypt library object will be available using: <dfn>$this->encrypt</dfn></p> 
 
 
<h2>$this->encrypt->encode()</h2> 
 
<p>Performs the data encryption and returns it as a string. Example:</p> 
<code> 
$msg = 'My secret message';<br /> 
<br /> 
$encrypted_string = $this->encrypt->encode($msg);</code> 
 
<p>You can optionally pass your encryption key via the second parameter if you don't want to use the one in your config file:</p> 
 
<code> 
$msg = 'My secret message';<br /> 
$key = 'super-secret-key';<br /> 
<br /> 
$encrypted_string = $this->encrypt->encode($msg, $key);</code> 
 
 
<h2>$this->encrypt->decode()</h2> 
 
<p>Decrypts an encoded string.  Example:</p> 
 
<code> 
$encrypted_string = 'APANtByIGI1BpVXZTJgcsAG8GZl8pdwwa84';<br /> 
<br /> 
$plaintext_string = $this->encrypt->decode($encrypted_string);</code> 
 
<p>You can optionally pass your encryption key via the second parameter if you don't want to use the one in your config file:</p> 
 
<code> 
$msg = 'My secret message';<br /> 
$key = 'super-secret-key';<br /> 
<br /> 
$encrypted_string = $this->encrypt->decode($msg, $key);</code> 
 
 
<h2>$this->encrypt->set_cipher();</h2> 
 
<p>Permits you to set an Mcrypt cipher.  By default it uses <samp>MCRYPT_RIJNDAEL_256</samp>.  Example:</p> 
<code>$this->encrypt->set_cipher(MCRYPT_BLOWFISH);</code> 
<p>Please visit php.net for a list of  <a href="http://php.net/mcrypt">available ciphers</a>.</p> 
 
<p>If you'd like to manually test whether your server supports Mcrypt you can use:</p> 
<code>echo ( ! function_exists('mcrypt_encrypt')) ? 'Nope' : 'Yup';</code> 
 
 
<h2>$this->encrypt->set_mode();</h2> 
 
<p>Permits you to set an Mcrypt mode.  By default it uses <samp>MCRYPT_MODE_CBC</samp>.  Example:</p> 
<code>$this->encrypt->set_mode(MCRYPT_MODE_CFB);</code> 
<p>Please visit php.net for a list of  <a href="http://php.net/mcrypt">available modes</a>.</p> 
 
 
<h2>$this->encrypt->sha1();</h2> 
<p>SHA1 encoding function.  Provide a string and it will return a 160 bit one way hash.  Note:  SHA1, just like MD5 is non-decodable. Example:</p> 
<code>$hash = $this->encrypt->sha1('Some string');</code> 
 
<p>Many PHP installations have SHA1 support by default so if all you need is to encode a hash it's simpler to use the native
function:</p> 
 
<code>$hash = sha1('Some string');</code> 
 
<p>If your server does not support SHA1 you can use the provided function.</p> 
 
<h2 id="legacy">$this->encrypt->encode_from_legacy(<kbd>$orig_data</kbd>, <kbd>$legacy_mode</kbd> = MCRYPT_MODE_ECB, <kbd>$key</kbd> = '');</h2> 
<p>Enables you to re-encode data that was originally encrypted with CodeIgniter 1.x to be compatible with the Encryption library in CodeIgniter 2.x.  It is only
	necessary to use this method if you have encrypted data stored permanently such as in a file or database and are on a server that supports Mcrypt.  "Light" use encryption
	such as encrypted session data or transitory encrypted flashdata require no intervention on your part.  However, existing encrypted Sessions will be
	destroyed since data encrypted prior to 2.x will not be decoded.</p> 
 
<p class="important"><strong>Why only a method to re-encode the data instead of maintaining legacy methods for both encoding and decoding?</strong>  The algorithms in
	the Encryption library have improved in CodeIgniter 2.x	both for performance and security, and we do not wish to encourage continued use of the older methods.
	You can of course extend the Encryption library if you wish and replace the new methods with the old and retain seamless compatibility with CodeIgniter 1.x
	encrypted data, but this a decision that a developer should make cautiously and deliberately, if at all.</p> 
 
<code>$new_data = $this->encrypt->encode_from_legacy(<kbd>$old_encrypted_string</kbd>);</code> 


<table class="grid"> 
<thead>
<tr> 
	<th>Parameter</th> 
	<th>Default</th> 
	<th>Description</th> 
</tr> 
</thead>

<tbody>
<tr> 
	<td><strong>$orig_data</strong></td> 
	<td>n/a</td> 
	<td>The original encrypted data from CodeIgniter 1.x's Encryption library</td> 
</tr> 
<tr> 
	<td><strong>$legacy_mode</strong></td> 
	<td>MCRYPT_MODE_ECB</td> 
	<td>The Mcrypt mode that was used to generate the original encrypted data.  CodeIgniter 1.x's default was MCRYPT_MODE_ECB, and it will
		assume that to be the case unless overridden by this parameter.</td> 
</tr> 
<tr> 
	<td><strong>$key</strong></td> 
	<td>n/a</td> 
	<td>The encryption key.  This it typically specified in your config file as outlined above.</td> 
</tr> 
</tbody>
</table> 
</div> 
<!-- end onecolumn -->
