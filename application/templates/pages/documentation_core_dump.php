<script language="JavaScript"> 
/* code modified from ColdFusion's cfdump code */
				function dump_toggle_row(source) {
					var target = (document.all) ? source.parentElement.cells[1] : source.parentNode.lastChild;
					dump_toggle_target(target,dump_toggle_source(source));
				}
				
				function dump_toggle_source(source) {
					if (source.style.fontStyle=='italic') {
						source.style.fontStyle='normal';
						source.title='click to collapse';
						return 'open';
					} else {
						source.style.fontStyle='italic';
						source.title='click to expand';
						return 'closed';
					}
				}
			
				function dump_toggle_target(target,switchToState) {
					target.style.display = (switchToState=='open') ? '' : 'none';
				}
			
				function dump_toggle_table(source) {
					var switchToState=dump_toggle_source(source);
					if(document.all) {
						var table=source.parentElement.parentElement;
						for(var i=1;i<table.rows.length;i++) {
							target=table.rows[i];
							dump_toggle_target(target,switchToState);
						}
					}
					else {
						var table=source.parentNode.parentNode;
						for (var i=1;i<table.childNodes.length;i++) {
							target=table.childNodes[i];
							if(target.style) {
								dump_toggle_target(target,switchToState);
							}
						}
					}
				}
			</script>
			
			<style type="text/css">
				table.dump_array,
				table.dump_object,
				table.dump_resource,
				table.dump_resourceC,
				table.dump_xml {
				font-family:Verdana, Arial, Helvetica, sans-serif; 
				color:#000; 
				font-size:12px;
				margin:10px;
				}
				
				table.dump_array td,
				table.dump_object td,
				table.dump_resource td,
				table.dump_resourceC td,
				table.dump_xml td {
				font-family:Verdana, Arial, Helvetica, sans-serif; 
				color:#000; 
				}
				
				.dump_array_header,
				.dump_object_header,
				.dump_resource_header,
				.dump_resourceC_header,
				.dump_xml_header { 
				font-weight:bold; 
				color:#fff; 
				cursor:pointer; 
				}
					
				.dump_file_n_line {
				font-weight:normal;
				}
				
				.dump_array_key,
				.dump_object_key,
				.dump_xml_key { 
				cursor:pointer; 
				}
					
				/* array */
				table.dump_array { 
				background:#00A000; 
				}
				
				table.dump_array td { 
				background:#fff; 
				}
				
				table.dump_array td.dump_array_header { 
				background:#90FF90; 
				}
				
				table.dump_array td.dump_array_key { 
				background:#CCFFCC; 
				}
				
				/* object */
				table.dump_object { 
				background:#4040FF; 
				}
				
				table.dump_object td { 
				background:#fff; 
				}
				
				table.dump_object td.dump_object_header { 
				background:#C0C0FF; 
				}
				
				table.dump_object td.dump_object_key { 
				background:#CCDDFF; 
				}
				
				/* resource */
				table.dump_resource, 
				table.dump_resourceC { 
				background:#884488; 
				}
				
				table.dump_resource td, 
				table.dump_resourceC td { 
				background:#fff; 
				}
				
				table.dump_resource td.dump_resource_header, 
				table.dump_resourceC td.dump_resourceC_header { 
				background:#AA66AA; 
				}
				
				table.dump_resource td.dump_resource_key, 
				table.dump_resourceC td.dump_resourceC_key { 
				background:#FFDDFF; 
				}
				
				/* xml */
				table.dump_xml { 
				background:#888; 
				}
				
				table.dump_xml td { 
				background:#fff; 
				}
				
				table.dump_xml td.dump_xml_header { 
				background-color:#aaa; 
				}
				
				table.dump_xml td.dump_xml_key { 
				background-color:#ddd; 
				}
			</style>

<div class="row">
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Dump Variable</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; Dump Variable
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/core/dump/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
The Dumper class in InfoPotato is a modification of <a href="http://dbug.ospinto.com/" class="external_link">dBug</a> which is a PHP version of ColdFusion's cfdump based on <a href="http://dbug.ospinto.com/" class="external_link">dump</a>. You can get colored and structured tabular variable information output by using this class in Managers. The output table cells can be expanded and collapsed. This is a much better presentation with more visual output of a variable's contents than PHP's <a href="http://us2.php.net/manual/en/function.var-dump.php" class="external_link"><code class="php_function">var_dump</code></a> and <a href="http://us2.php.net/manual/en/function.print-r.php" class="external_link"><code class="php_function">print_r</code></a> functions. Variable types supported are: 
</p>

<ul>
<li>Strings</li>
<li>Arrays</li>
<li>Classes/Objects</li>
<li>XML Resources</li>
</ul>

<p>
You can force an object variable to be outputted as an array type variable.
</p>

<h2>Sample Usage - string</h2>

<div class="syntax"><pre>
<span class="nv">$variable</span> <span class="o">=</span> <span class="s1">&#39;This is my string&#39;</span><span class="p">;</span> 
<span class="nx">dump</span><span class="p">(</span><span class="nv">$variable</span><span class="p">);</span> 
</pre></div> 

<p>
This will generates the following output:
</p>

<div>
This is my string
</div>

<h2>Sample Usage - array</h2>

<div class="syntax"><pre>
<span class="nv">$variable</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
    <span class="s1">&#39;first&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;1&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;second&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;third&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
        <span class="s1">&#39;inner third 1&#39;</span><span class="p">,</span> 
        <span class="s1">&#39;inner third 2&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;yeah&#39;</span> 
    <span class="p">),</span> 
    <span class="s1">&#39;fourth&#39;</span> 
<span class="p">);</span> 
 
<span class="nx">dump</span><span class="p">(</span><span class="nv">$variable</span><span class="p">);</span> 
</pre></div> 

<table cellspacing="2" cellpadding="3" class="dump_array grid"> 
<tr> 
<td class="dump_array_header" colspan="2" onClick='dump_toggle_table(this)'>array</td> 
</tr><tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_array_key">first</td> 
<td>1</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_array_key">0</td> 
<td>second</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_array_key">third</td> 
<td><table cellspacing=2 cellpadding="3" class="dump_array"> 
<tr> 
<td class="dump_arrayHeader" colspan="2" onClick='dump_toggle_table(this)'>array</td> 
</tr><tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_array_key">0</td> 
<td>inner third 1</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_array_key">inner third 2</td> 
<td>yeah</td></tr> 
</table></td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_array_key">1</td> 
<td>fourth</td></tr> 
</table>

<h2>Sample Usage - object</h2>

<div class="syntax"><pre>
<span class="k">class</span> <span class="nc">Vegetable</span> <span class="p">{</span> 
 
   <span class="k">public</span> <span class="nv">$edible</span><span class="p">;</span> 
   <span class="k">public</span> <span class="nv">$color</span><span class="p">;</span> 
 
   <span class="k">public</span> <span class="k">function</span> <span class="nf">Vegetable</span><span class="p">(</span><span class="nv">$edible</span><span class="p">,</span> <span class="nv">$color</span> <span class="o">=</span> <span class="s1">&#39;green&#39;</span><span class="p">)</span> <span class="p">{</span> 
       <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">edible</span> <span class="o">=</span> <span class="nv">$edible</span><span class="p">;</span> 
       <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">color</span> <span class="o">=</span> <span class="nv">$color</span><span class="p">;</span> 
   <span class="p">}</span> 
 
   <span class="k">public</span> <span class="k">function</span> <span class="nf">is_edible</span><span class="p">()</span> <span class="p">{</span> 
       <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">edible</span><span class="p">;</span> 
   <span class="p">}</span> 
 
   <span class="k">public</span> <span class="k">function</span> <span class="nf">what_color</span><span class="p">()</span> <span class="p">{</span> 
       <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">color</span><span class="p">;</span> 
   <span class="p">}</span> 
   
<span class="p">}</span> 
</pre></div> 

<p>Then in the manager file, you can create an instance of the above class and dump this object</p>

<div class="syntax"><pre>
<span class="nv">$variable</span> <span class="o">=</span> <span class="k">new</span> <span class="nx">Vegetable</span><span class="p">(</span><span class="s1">&#39;spinach&#39;</span><span class="p">);</span> 
 
<span class="nx">dump</span><span class="p">(</span><span class="nv">$variable</span><span class="p">);</span> 
</pre></div> 

<table cellspacing="2" cellpadding="3" class="dump_object grid"> 
<tr> 
<td class="dump_object_header" colspan="2" onClick='dump_toggle_table(this)'>object</td> 
</tr><tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_object_key">edible</td> 
<td>spinach</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_object_key">color</td> 
<td>green</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_object_key">vegetable</td> 
<td>[method]</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_object_key">is_edible</td> 
<td>[method]</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_object_key">what_color</td> 
<td>[method]</td></tr> 
</table>

<h2>Sample Usage - XML</h2>

<p>
When an xml variable is dumped as is, it is recognized as a string. This is even so with PHP's var_dump. The dump class has a second optional parameter where you pass in the string "xml".
</p>

<div class="syntax"><pre>
<span class="nv">$variable</span> <span class="o">=</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;data.xml&#39;</span><span class="p">;</span> <span class="c1">//path to xml file;; </span> 
<span class="nx">dump</span><span class="p">(</span><span class="nv">$variable</span><span class="p">,</span> <span class="s1">&#39;xml&#39;</span><span class="p">);</span> 
</pre></div> 

<table cellspacing=2 cellpadding="3" class="dump_xml grid"> 
<tr> 
<td class="dump_xml_header" colspan="2" onClick='dump_toggle_table(this)'>XML Document</td> 
</tr><tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Root</td> 
<td><table cellspacing=2 cellpadding="3" class="dump_xml"> 
<tr> 
<td class="dump_xmlHeader" colspan="2" onClick='dump_toggle_table(this)'>Element</td> 
</tr><tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Name</td> 
<td><strong>chapter</strong></td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Attributes</td> 
<td>&nbsp;</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Text</td> 
<td> 
</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Comment</td> 
<td>&nbsp;</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Children</td> 
<td><table cellspacing=2 cellpadding="3" class="dump_xml"> 
<tr> 
<td class="dump_xml_header" colspan="2" onClick='dump_toggle_table(this)'>Element</td> 
</tr><tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Name</td> 
<td><strong>TITLE</strong></td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Attributes</td> 
<td>&nbsp;</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Text</td> 
<td>This is my title</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Comment</td> 
<td>&nbsp;</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Children</td> 
<td></td></tr> 
</table><table cellspacing="2" cellpadding="3" class="dump_xml"> 
<tr> 
<td class="dump_xml_header" colspan="2" onClick='dump_toggle_table(this)'>Element</td> 
</tr><tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Name</td> 
<td><strong>tgroup</strong></td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Attributes</td> 
<td><table cellspacing=2 cellpadding="3" class="dump_array"> 
<tr> 
<td class="dump_array_header" colspan="2" onClick='dump_toggle_table(this)'>array</td> 
</tr><tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_array_key">cols</td> 
<td>3</td></tr> 
</table></td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Text</td> 
<td> 

</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Comment</td> 
<td> Another comment here
on second line </td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Children</td> 
<td><table cellspacing=2 cellpadding="3" class="dump_xml"> 
<tr> 
<td class="dump_xml_header" colspan="2" onClick='dump_toggle_table(this)'>Element</td> 
</tr><tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Name</td> 
<td><strong>entry</strong></td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Attributes</td> 
<td><table cellspacing="2" cellpadding="3" class="dump_array"> 
<tr> 
<td class="dump_array_header" colspan="2" onClick='dump_toggle_table(this)'>array</td> 
</tr><tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_array_key">morerows</td> 
<td>1</td></tr> 
</table></td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Text</td> 
<td>b1</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Comment</td> 
<td>&nbsp;</td></tr> 
<tr> 
<td valign="top" onClick='dump_toggle_row(this)' class="dump_xml_key">Children</td> 
<td></td></tr> 
</table></td></tr> 
</table></td></tr> 
</table></td></tr> 
</table>
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 

