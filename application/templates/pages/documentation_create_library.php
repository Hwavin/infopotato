<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_ENTRY_URI; ?>home">Home</a> &gt; <a href="<?php echo APP_ENTRY_URI; ?>documentation/">Documentation</a> &gt; Creating Libraries
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Creating Libraries</h1>	

<p>When we use the term "Libraries" we are normally referring to the classes that are located in the <kbd>libraries</kbd> 
directory and described in the Class Reference of this user guide.  In this case, however, we will instead describe how you can create
your own libraries within your <dfn>system/libraries</dfn> directory as part of the global framework resources.</p> 
 
<p>As an added bonus, InfoPotato permits your libraries to <kbd>extend</kbd> native classes if you simply need to add some functionality
to an existing library. Or you can even replace native libraries just by placing identically named versions in your <dfn>system/libraries</dfn> folder.</p> 
 
<p>In summary:</p> 
 
<ul> 
<li>You can create entirely new libraries.</li> 
<li>You can extend native libraries.</li> 
<li>You can replace native libraries.</li> 
</ul> 
 
<p>The page below explains these three concepts in detail.</p> 
 
<h2>Storage</h2> 
 
<p>Your library classes should be placed within your <dfn>system/libraries</dfn> folder, as this is where InfoPotato will look for them when
they are initialized.</p> 
 
 
<h2>Naming Conventions</h2> 
 
<ul> 
<li>File names must be lowercased. For example:&nbsp; <dfn>myclass_library.php</dfn></li> 
<li>Class declarations must be capitalized. For example:&nbsp;  <kbd>class My_Library</kbd></li> 
</ul> 
 
 
<h2>The Class File</h2> 
 
<p>Classes should have this basic prototype (Note:  We are using the name <kbd>Someclass</kbd> purely as an example):</p> 
 
<pre>&lt;?php
class Someclass_Library {<br /> 
	public function __construct($config = array()) {
		// Some initial code
		
		if (count($config) > 0) {
			$this->initialize($config);
		}
	}
	
	public function initialize($config = array()) { 
		foreach ($config as $key => $val) {
			if (isset($key)) {
				$this->$key = $val;
			}
		}
	}
	
	// Other functions
}
?&gt;
</pre> 
 
 
<h2>Using Your Class</h2> 
 
<p>From within any of your <a href="controllers.html">Controller</a> functions you can initialize your class using the standard:</p> 
 
<code>$this->load_library('<kbd>someclass/someclass_library</kbd>', '<kbd>alias</kbd>', $config);</code> 
 
<p>Where <em>someclass_library</em> is the file name, without the ".php" file extension. You can submit the file name capitalized or lower case.
InfoPotato doesn't care.</p> 
 
<p class="important"> 
<strong>Note:</strong> If no alias provided, for example <code>$this->load_library('<kbd>someclass/someclass_library</kbd>', '', $config);</code> then the library name will be used as the object instance.
And you can access the library functions like: <code>$this-><kbd>someclass_library</kbd>->some_function();
</code> 
</p> 
 
<p>Once loaded you can access your class using the <kbd>lower case</kbd> version:</p> 
 
<code>$this-><kbd>alias</kbd>->some_function();&nbsp; // Object instances will always be lower case
</code> 
 
 
 
<h2>Passing Parameters When Initializing Your Class</h2> 
 
<p>In the library loading function you can dynamically pass data as an array via the second parameter and it will be passed to your class
constructor:</p> 
 
<pre> 
$config = array(
	'type' => 'large', 
	'color' => 'red'
);
 
$this->load_library('Someclass', '', <kbd>$config</kbd>);
</pre> 
 
<p>If you use this feature you must set up your class constructor to expect data:</p> 
 
<pre>&lt;?php
class Someclass_Library {<br /> 
	public function __construct($config = array()) {
		// Some initial code
		
		if (count($config) > 0) {
			$this->initialize($config);
		}
	}
	
	public function initialize($config = array()) { 
		foreach ($config as $key => $val) {
			if (isset($key)) {
				$this->$key = $val;
			}
		}
	}
	
	// Other functions
}
?&gt;
</pre> 
</div> 
<!-- end onecolumn -->
