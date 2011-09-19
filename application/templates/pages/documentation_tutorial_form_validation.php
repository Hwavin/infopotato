<script type="text/javascript" src="<?php echo APP_URI_BASE; ?>js/index/livevalidation.js"></script>

<div class="container"> 

<div class="row">

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Client-side and server-side form validation</h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/tutorial/">Tutorials</a> &gt; Client-side and server-side form validation
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/tutorial/form_validation/'); ?>" class="print">Print</a>

<!-- PRINT: start -->

<h2>Before We Start</h2>

<p>
In this tutorial we will show you how to use InfoPotato's server-side form validation library and client-side javascript to validate forms. Lets face it, forms are boring, validation is a pain. To inject some life into them and make them fun again, we will use <a href="http://livevalidation.com/" class="external_link">LiveValidation</a> to do the client-side job. LiveValidation is a small open source javascript library for making client-side validation quick, easy, and powerful.
</p>

<h2>Validating Entire Form on Submition</h2>

<p>
When a LiveValidation object is instantiated, if it finds it is a child of a form, it will automatically attach itself to the submit event of the form, so when it is submitted, validation on all the LiveValidation objects inside it will be performed automatically. If any of the validations fail it will stop the form submitting (this of course only works when javascript is on in the users bowser, that's why we also need to validate the form on server-side with InfoPotato's form validation library.
</p>

<div class="bluebox">
<form action="<?php echo APP_URI_BASE; ?>form_validation/" method="post"> 
 
<div class="form_item">
<label for="field1" class="desc">Email (optional):</label>
<input type="text" id="field1" value="<?php if (isset($field1)) echo $field1; ?>" name="field1" class="contact_input" />
<?php if (isset($field1_error)) echo $field1_error; ?>
</div>

<div class="form_item">
<label for="field2" class="desc">Message (required):</label>
<textarea  id="field2" name="field2" class="contact_textarea" rows="8"><?php if (isset($field2)) echo $field2; ?></textarea>
<?php if (isset($field2_error)) echo $field2_error; ?>
</div>

<p>
<input type="submit" class="submit" value="Test me!" />
</p> 

</form>
</div>

<script type="text/javascript"> 
var field1 = new LiveValidation( 'field1', {onlyOnSubmit: true } );
field1.add( Validate.Email );

var field2 = new LiveValidation( 'field2', {onlyOnSubmit: true } );
field2.add( Validate.Presence );
</script>

<p>
You can also specify the onlyOnSubmit option to be true when you create the LiveValidation object to make it only validate when the form is submitted.
</p>

<div class="syntax"><pre><span class="kd">var</span> <span class="nx">field1</span> <span class="o">=</span> <span class="k">new</span> <span class="nx">LiveValidation</span><span class="p">(</span> <span class="s1">&#39;field1&#39;</span><span class="p">,</span> <span class="p">{</span><span class="nx">onlyOnSubmit</span><span class="o">:</span> <span class="kc">true</span> <span class="p">}</span> <span class="p">);</span> 
<span class="nx">field1</span><span class="p">.</span><span class="nx">add</span><span class="p">(</span> <span class="nx">Validate</span><span class="p">.</span><span class="nx">Email</span> <span class="p">);</span> 
 
<span class="kd">var</span> <span class="nx">field2</span> <span class="o">=</span> <span class="k">new</span> <span class="nx">LiveValidation</span><span class="p">(</span> <span class="s1">&#39;field2&#39;</span><span class="p">,</span> <span class="p">{</span><span class="nx">onlyOnSubmit</span><span class="o">:</span> <span class="kc">true</span> <span class="p">}</span> <span class="p">);</span> 
<span class="nx">field2</span><span class="p">.</span><span class="nx">add</span><span class="p">(</span> <span class="nx">Validate</span><span class="p">.</span><span class="nx">Presence</span> <span class="p">);</span> 
</pre></div>

<p>
In this example the form will be submitted to the server-side for further process is it is valid. When JavaScript is disabled in some users' browsers, the client-side validation will not work and the server-side validation instead will show the user error messages if there is any invalid input. For the detail useage of InfoPotato's form validation library please see <a href="<?php echo APP_URI_BASE; ?>documentation/library/form_validation/">Form Validation Library</a>.
</p>

<div class="syntax"><pre>
<span class="k">final</span> <span class="k">class</span> <span class="nc">Form_Validation_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">post_index</span><span class="p">()</span> <span class="p">{</span> 
        <span class="c1">// Load Form Validation library and assign post data</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;form_validation/form_validation_library&#39;</span><span class="p">,</span> <span class="s1">&#39;fv&#39;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;post&#39;span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_POST_DATA</span><span class="p">));</span> 
 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">set_rules</span><span class="p">(</span><span class="s1">&#39;field1&#39;</span><span class="p">,</span> <span class="s1">&#39;Email&#39;</span><span class="p">,</span> <span class="s1">&#39;trim|valid_email&#39;</span><span class="p">);</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">set_rules</span><span class="p">(</span><span class="s1">&#39;field2&#39;</span><span class="p">,</span> <span class="s1">&#39;Message&#39;</span><span class="p">,</span> <span class="s1">&#39;trim|required&#39;</span><span class="p">);</span> 
		
	<span class="nv">$result</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">run</span><span class="p">();</span> 
 
	<span class="c1">// Further process the input data with htmlawed function</span> 
	<span class="nv">$field1</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">set_value</span><span class="p">(</span><span class="s1">&#39;field1&#39;</span><span class="p">);</span> 
	<span class="nv">$field2</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">set_value</span><span class="p">(</span><span class="s1">&#39;field2&#39;</span><span class="p">);</span> 
 
	<span class="k">if</span> <span class="p">(</span><span class="nv">$result</span> <span class="o">==</span> <span class="k">FALSE</span><span class="p">)</span> <span class="p">{</span> 
	    <span class="nv">$data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;field1&#39;</span> <span class="o">=&gt;</span> <span class="nv">$field1</span><span class="p">,</span> 
		<span class="s1">&#39;field2&#39;</span> <span class="o">=&gt;</span> <span class="nv">$field2</span><span class="p">,</span> 
		<span class="s1">&#39;field1_error&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">field_error</span><span class="p">(</span><span class="s1">&#39;field1&#39;</span><span class="p">,</span> <span class="s1">&#39;&lt;span class=&quot;red&quot;&gt;&#39;</span><span class="p">,</span> <span class="s1">&#39;&lt;/span&gt;&#39;</span><span class="p">),</span> 
		<span class="s1">&#39;field2_error&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">field_error</span><span class="p">(</span><span class="s1">&#39;field2&#39;</span><span class="p">,</span> <span class="s1">&#39;&lt;span class=&quot;red&quot;&gt;&#39;</span><span class="p">,</span> <span class="s1">&#39;&lt;/span&gt;&#39;</span><span class="p">),</span> 
	    <span class="p">);</span> 
			
	    <span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Form Validation&#39;</span><span class="p">,</span> 
		<span class="s1">&#39;stylesheets&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;syntax.css&#39;</span><span class="p">),</span> 
		<span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;pages/documentation_tutorial_form_validation&#39;</span><span class="p">,</span> <span class="nv">$data</span><span class="p">),</span> 
	    <span class="p">);</span> 
        <span class="p">}</span> <span class="k">else</span> <span class="p">{</span>	
	    <span class="nv">$data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;sent&#39;</span> <span class="o">=&gt;</span> <span class="k">TRUE</span><span class="p">,</span> 
	    <span class="p">);</span>	
			
	    <span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Form Validation&#39;</span><span class="p">,</span> 
		<span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;pages/form_validation_response&#39;</span><span class="p">,</span> <span class="nv">$data</span><span class="p">),</span> 
	    <span class="p">);</span> 
        <span class="p">}</span> 
 
	<span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;layouts/default_layout&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
	    <span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/html&#39;</span><span class="p">,</span> 
	<span class="p">);</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
    <span class="p">}</span>	
<span class="p">}</span> 
</pre></div>

<div class="notebox">
You can try to disable JavaScript in your browser and try out the server-side validation.
</div>

<!-- PRINT: stop -->

</div> 

</div>
