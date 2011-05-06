<script language="JavaScript"> 
/* code modified from ColdFusion's cfdump code */
				function dBug_toggleRow(source) {
					var target = (document.all) ? source.parentElement.cells[1] : source.parentNode.lastChild;
					dBug_toggleTarget(target,dBug_toggleSource(source));
				}
				
				function dBug_toggleSource(source) {
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
			
				function dBug_toggleTarget(target,switchToState) {
					target.style.display = (switchToState=='open') ? '' : 'none';
				}
			
				function dBug_toggleTable(source) {
					var switchToState=dBug_toggleSource(source);
					if(document.all) {
						var table=source.parentElement.parentElement;
						for(var i=1;i<table.rows.length;i++) {
							target=table.rows[i];
							dBug_toggleTarget(target,switchToState);
						}
					}
					else {
						var table=source.parentNode.parentNode;
						for (var i=1;i<table.childNodes.length;i++) {
							target=table.childNodes[i];
							if(target.style) {
								dBug_toggleTarget(target,switchToState);
							}
						}
					}
				}
			</script>
			
			<style type="text/css">
				table.dBug_array,table.dBug_object,table.dBug_resource,table.dBug_resourceC,table.dBug_xml {
					font-family:Verdana, Arial, Helvetica, sans-serif; color:#000; font-size:12px;
				}
				
				.dBug_arrayHeader,
				.dBug_objectHeader,
				.dBug_resourceHeader,
				.dBug_resourceCHeader,
				.dBug_xmlHeader 
					{ font-weight:bold; color:#fff; cursor:pointer; }
				
				.dBug_arrayKey,
				.dBug_objectKey,
				.dBug_xmlKey 
					{ cursor:pointer; }
					
				/* array */
				table.dBug_array { background-color:#006600; }
				table.dBug_array td { background-color:#fff; }
				table.dBug_array td.dBug_arrayHeader { background-color:#009900; }
				table.dBug_array td.dBug_arrayKey { background-color:#CCFFCC; }
				
				/* object */
				table.dBug_object { background-color:#0000CC; }
				table.dBug_object td { background-color:#fff; }
				table.dBug_object td.dBug_objectHeader { background-color:#4444CC; }
				table.dBug_object td.dBug_objectKey { background-color:#CCDDFF; }
				
				/* resource */
				table.dBug_resource, table.dBug_resourceC { background-color:#884488; }
				table.dBug_resource td, table.dBug_resourceC td { background-color:#fff; }
				table.dBug_resource td.dBug_resourceHeader, table.dBug_resourceC td.dBug_resourceCHeader { background-color:#AA66AA; }
				table.dBug_resource td.dBug_resourceKey, table.dBug_resourceC td.dBug_resourceCKey { background-color:#FFDDFF; }
				
				/* xml */
				table.dBug_xml { background-color:#888; }
				table.dBug_xml td { background-color:#fff; }
				table.dBug_xml td.dBug_xmlHeader { background-color:#aaa; }
				table.dBug_xml td.dBug_xmlKey { background-color:#ddd; }
			</style>

<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Dump Variable
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Dump Variable</h1>	

<p>
The Dump class is a PHP version of ColdFusion's cfdump based on <a href="http://dbug.ospinto.com/" class="external_link">dBug</a>. You can get colored and structured tabular variable information output by using this class in Managers. The output table cells can be expanded and collapsed. Variable types supported are: 
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
<span class="nx">Global_Functions</span><span class="o">::</span><span class="na">dump</span><span class="p">(</span><span class="nv">$variable</span><span class="p">);</span> 
</pre></div> 

<p>
This will generates the following output:
</p>

<div>
This is my string
</div>

<h2>Sample Usage - array</h2>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="nv">$variable</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
    <span class="s1">&#39;first&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;1&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;second&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;third&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
        <span class="s1">&#39;inner third 1&#39;</span><span class="p">,</span> 
        <span class="s1">&#39;inner third 2&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;yeah&#39;</span> 
    <span class="p">),</span> 
    <span class="s1">&#39;fourth&#39;</span> 
<span class="p">);</span> 
 
<span class="nx">Global_Functions</span><span class="o">::</span><span class="na">dump</span><span class="p">(</span><span class="nv">$variable</span><span class="p">);</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 

<table cellspacing="2" cellpadding="3" class="dBug_array"> 
				<tr> 
					<td class="dBug_arrayHeader" colspan=2 onClick='dBug_toggleTable(this)'>array</td> 
				</tr><tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_arrayKey">first</td> 
				<td>1</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_arrayKey">0</td> 
				<td>second</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_arrayKey">third</td> 
				<td><table cellspacing=2 cellpadding=3 class="dBug_array"> 
				<tr> 
					<td class="dBug_arrayHeader" colspan=2 onClick='dBug_toggleTable(this)'>array</td> 
				</tr><tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_arrayKey">0</td> 
				<td>inner third 1</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_arrayKey">inner third 2</td> 
				<td>yeah</td></tr> 
</table></td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_arrayKey">1</td> 
				<td>fourth</td></tr> 
</table>

<h2>Sample Usage - object</h2>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
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
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 

<p>Then in the manager file, you can create an instance of the above class and dump this object</p>

<div class="syntax"><pre>
<span class="nv">$variable</span> <span class="o">=</span> <span class="k">new</span> <span class="nx">Vegetable</span><span class="p">(</span><span class="s1">&#39;spinach&#39;</span><span class="p">);</span> 
 
<span class="nx">Global_Functions</span><span class="o">::</span><span class="na">dump</span><span class="p">(</span><span class="nv">$variable</span><span class="p">);</span> 
</pre></div> 

<table cellspacing=2 cellpadding=3 class="dBug_object"> 
				<tr> 
					<td class="dBug_objectHeader" colspan=2 onClick='dBug_toggleTable(this)'>object</td> 
				</tr><tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_objectKey">edible</td> 
				<td>spinach</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_objectKey">color</td> 
				<td>green</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_objectKey">vegetable</td> 
				<td>[method]</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_objectKey">is_edible</td> 
				<td>[method]</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_objectKey">what_color</td> 
				<td>[method]</td></tr> 
</table>

<h2>Sample Usage - XML</h2>

<p>
When an xml variable is dumped as is, it is recognized as a string. This is even so with PHP's var_dump. The dBug class has a second optional parameter where you pass in the string "xml".
</p>

<div class="syntax"><pre>
<span class="nv">$variable</span> <span class="o">=</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;data.xml&#39;</span><span class="p">;</span> <span class="c1">//path to xml file;;</span> 
 
<span class="nx">Global_Functions</span><span class="o">::</span><span class="na">dump</span><span class="p">(</span><span class="nv">$variable</span><span class="p">,</span> <span class="s1">&#39;xml&#39;</span><span class="p">);</span> 
</pre></div> 

<table cellspacing=2 cellpadding=3 class="dBug_xml"> 
				<tr> 
					<td class="dBug_xmlHeader" colspan=2 onClick='dBug_toggleTable(this)'>XML Document</td> 
				</tr><tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Root</td> 
				<td><table cellspacing=2 cellpadding=3 class="dBug_xml"> 
				<tr> 
					<td class="dBug_xmlHeader" colspan=2 onClick='dBug_toggleTable(this)'>Element</td> 
				</tr><tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Name</td> 
				<td><strong>chapter</strong></td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Attributes</td> 
				<td>&nbsp;</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Text</td> 
				<td> 
	</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Comment</td> 
				<td>&nbsp;</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Children</td> 
				<td><table cellspacing=2 cellpadding=3 class="dBug_xml"> 
				<tr> 
					<td class="dBug_xmlHeader" colspan=2 onClick='dBug_toggleTable(this)'>Element</td> 
				</tr><tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Name</td> 
				<td><strong>TITLE</strong></td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Attributes</td> 
				<td>&nbsp;</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Text</td> 
				<td>This is my title</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Comment</td> 
				<td>&nbsp;</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Children</td> 
				<td></td></tr> 
</table><table cellspacing=2 cellpadding=3 class="dBug_xml"> 
				<tr> 
					<td class="dBug_xmlHeader" colspan=2 onClick='dBug_toggleTable(this)'>Element</td> 
				</tr><tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Name</td> 
				<td><strong>tgroup</strong></td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Attributes</td> 
				<td><table cellspacing=2 cellpadding=3 class="dBug_array"> 
				<tr> 
					<td class="dBug_arrayHeader" colspan=2 onClick='dBug_toggleTable(this)'>array</td> 
				</tr><tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_arrayKey">cols</td> 
				<td>3</td></tr> 
</table></td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Text</td> 
				<td> 
		
		</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Comment</td> 
				<td> Another comment here
			on second line </td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Children</td> 
				<td><table cellspacing=2 cellpadding=3 class="dBug_xml"> 
				<tr> 
					<td class="dBug_xmlHeader" colspan=2 onClick='dBug_toggleTable(this)'>Element</td> 
				</tr><tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Name</td> 
				<td><strong>entry</strong></td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Attributes</td> 
				<td><table cellspacing=2 cellpadding=3 class="dBug_array"> 
				<tr> 
					<td class="dBug_arrayHeader" colspan=2 onClick='dBug_toggleTable(this)'>array</td> 
				</tr><tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_arrayKey">morerows</td> 
				<td>1</td></tr> 
</table></td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Text</td> 
				<td>b1</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Comment</td> 
				<td>&nbsp;</td></tr> 
<tr> 
				<td valign="top" onClick='dBug_toggleRow(this)' class="dBug_xmlKey">Children</td> 
				<td></td></tr> 
</table></td></tr> 
</table></td></tr> 
</table></td></tr> 
</table>

</div> 
<!-- end onecolumn -->
