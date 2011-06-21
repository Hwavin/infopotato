<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Calendar</h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/library/">Library</a> &gt; Calendar
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/library/calendar/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>The Calendar class enables you to dynamically create calendars. Your calendars can be formatted through the use of a calendar
template, allowing 100% control over every aspect of its design. In addition, you can pass data to your calendar cells.</p> 
 
<h2>Initializing the Class</h2> 
 
<p>Like most other classes in InfoPotato, the Calendar class is initialized in your controller using the <dfn>$this->load_library</dfn> function:</p> 
 
<div class="syntax">
<pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;calendar/calendar_library&#39;</span><span class="p">,</span> <span class="s1">&#39;cal&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
</pre>
</div> 

<p>Once loaded, the Calendar object will be available using: <dfn>$this->cal</dfn></p> 
 
 
<h2>Displaying a Calendar</h2> 
 
<p>Here is a very simple example showing how you can display a calendar:</p> 
 
<div class="syntax">
<pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;calendar/calendar_library&#39;</span><span class="p">,</span> <span class="s1">&#39;cal&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
<span class="k">echo</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">cal</span><span class="o">-&gt;</span><span class="na">generate</span><span class="p">();</span> 
</pre>
</div> 
 
<p>The above code will generate a calendar for the current month/year based on your server time.
To show a calendar for a specific month and year you will pass this information to the calendar generating function:</p> 
 
<div class="syntax">
<pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;calendar/calendar_library&#39;</span><span class="p">,</span> <span class="s1">&#39;cal&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
<span class="k">echo</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">cal</span><span class="o">-&gt;</span><span class="na">generate</span><span class="p">(</span><span class="m">2006</span><span class="p">,</span> <span class="m">6</span><span class="p">);</span> 
</pre>
</div> 
 
<p>The above code will generate a calendar showing the month of June in 2006.  The first parameter specifies the year, the second parameter specifies the month.</p> 
 
<h2>Passing Data to your Calendar Cells</h2> 
 
<p>To add data to your calendar cells involves creating an associative array in which the keys correspond to the days
you wish to populate and the array value contains the data.  The array is passed to the third parameter of the calendar
generating function.  Consider this example:</p> 
 
<div class="syntax">
<pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;calendar/calendar_library&#39;</span><span class="p">,</span> <span class="s1">&#39;cal&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
<span class="nv">$data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
    <span class="m">3</span>  <span class="o">=&gt;</span> <span class="s1">&#39;http://example.com/news/article/2006/03/&#39;</span><span class="p">,</span> 
    <span class="m">7</span>  <span class="o">=&gt;</span> <span class="s1">&#39;http://example.com/news/article/2006/07/&#39;</span><span class="p">,</span> 
    <span class="m">13</span> <span class="o">=&gt;</span> <span class="s1">&#39;http://example.com/news/article/2006/13/&#39;</span><span class="p">,</span> 
    <span class="m">26</span> <span class="o">=&gt;</span> <span class="s1">&#39;http://example.com/news/article/2006/26/&#39;</span> 
<span class="p">);</span> 
 
<span class="k">echo</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">cal</span><span class="o">-&gt;</span><span class="na">generate</span><span class="p">(</span><span class="m">2006</span><span class="p">,</span> <span class="m">6</span><span class="p">,</span> <span class="nv">$data</span><span class="p">);</span> 
</pre>
</div> 
 
<p>Using the above example, day numbers 3, 7, 13, and 26 will become links pointing to the URLs you've provided.</p> 
 
<p class="important"><strong>Note:</strong> By default it is assumed that your array will contain links.
In the section that explains the calendar template below you'll see how you can customize
how data passed to your cells is handled so you can pass different types of information.</p> 
 
 
<h2>Setting Display Preferences</h2> 
 
<p>There are seven preferences you can set to control various aspects of the calendar.  Preferences are set by passing an
array of preferences in the second parameter of the loading function. Here is an example:</p> 
 
 
<div class="syntax">
<pre><span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span> <span class="p">(</span> 
    <span class="s1">&#39;start_day&#39;</span>    <span class="o">=&gt;</span> <span class="s1">&#39;saturday&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;month_type&#39;</span>   <span class="o">=&gt;</span> <span class="s1">&#39;long&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;day_type&#39;</span>     <span class="o">=&gt;</span> <span class="s1">&#39;short&#39;</span> 
<span class="p">);</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;calendar/calendar_library&#39;</span><span class="p">,</span> <span class="s1">&#39;cal&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
<span class="k">echo</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">cal</span><span class="o">-&gt;</span><span class="na">generate</span><span class="p">();</span> 
</pre></div>
 
<p>
The above code would start the calendar on saturday, use the "long" month heading, and the "short" day names.  More information
regarding preferences below.
</p> 
 
<table class="grid"> 
<thead>
<tr><th>Preference</th><th>Default&nbsp;Value</th><th>Options</th><th>Description</th></tr>
</thead>

<tbody>
<tr> 
<td><strong>template</strong></td><td class="td">None</td><td class="td">None</td><td class="td">A string containing your calendar template. See the template section below.</td> 
</tr><tr> 
<td><strong>local_time</strong></td><td class="td">time()</td><td class="td">None</td><td class="td">A Unix timestamp corresponding to the current time.</td> 
</tr><tr> 
<td><strong>start_day</strong></td><td class="td">sunday</td><td class="td">Any week day (sunday, monday, tuesday, etc.)</td><td class="td">Sets the day of the week the calendar should start on.</td> 
</tr><tr> 
<td><strong>month_type</strong></td><td class="td">long</td><td class="td">long, short</td><td class="td">Determines what version of the month name to use in the header. long = January, short = Jan.</td> 
</tr><tr> 
<td><strong>day_type</strong></td><td class="td">abr</td><td class="td">long, short, abr</td><td class="td">Determines what version of the weekday names to use in the column headers. long = Sunday, short = Sun, abr = Su.</td> 
</tr><tr> 
<td><strong>show_next_prev</strong></td><td class="td">FALSE</td><td class="td">TRUE/FALSE (boolean)</td><td class="td">Determines whether to display links allowing you to toggle to next/previous months. See information on this feature below.</td> 
</tr><tr> 
<td><strong>next_prev_url</strong></td><td class="td">None</td><td class="td">A URL</td><td class="td">Sets the basepath used in the next/previous calendar links.</td> 
</tr> 
</tbody>

</table> 
 
 
 
<h2>Showing Next/Previous Month Links</h2> 
 
<p>To allow your calendar to dynamically increment/decrement via the next/previous links requires that you set up your calendar
code similar to this example:</p> 
 
 
<div class="syntax">
<pre>
<span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span> <span class="p">(</span> 
    <span class="s1">&#39;show_next_prev&#39;</span>  <span class="o">=&gt;</span> <span class="k">TRUE</span><span class="p">,</span> 
    <span class="s1">&#39;next_prev_url&#39;</span>   <span class="o">=&gt;</span> <span class="s1">&#39;http://example.com/index.php/calendar/show/&#39;</span> 
<span class="p">);</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;calendar/calendar_library&#39;</span><span class="p">,</span> <span class="s1">&#39;cal&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
 
<span class="k">echo</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">cal</span><span class="o">-&gt;</span><span class="na">generate</span><span class="p">(</span><span class="nv">$year</span><span class="p">,</span> <span class="nv">$month</span><span class="p">);</span> 
</pre>
</div> 
 
<p>You'll notice a few things about the above example:</p> 
 
<ul> 
<li>You must set the "show_next_prev" to TRUE.</li> 
<li>You must supply the URL to the controller containing your calendar in the "next_prev_url" preference.</li> 
<li>You must supply the "year" and "month" to the calendar generating function via the URI segments where they appear (Note:  The calendar class automatically adds the year/month to the base URL you provide.).</li> 
</ul> 
 
 
 
<h2>Creating a Calendar Template</h2> 
 
<p>By creating a calendar template you have 100% control over the design of your calendar. Each component of your
calendar will be placed within a pair of pseudo-variables as shown here:</p> 
 
 
<div class="syntax">
<pre>
<span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;template&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;</span> 
<span class="s1">    {table_open}&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot;&gt;{/table_open}</span> 
 
<span class="s1">    {heading_row_start}&lt;tr&gt;{/heading_row_start}</span> 
 
<span class="s1">    {heading_previous_cell}&lt;th&gt;&lt;a href=&quot;{previous_url}&quot;&gt;&amp;lt;&amp;lt;&lt;/a&gt;&lt;/th&gt;{/heading_previous_cell}</span> 
<span class="s1">    {heading_title_cell}&lt;th colspan=&quot;{colspan}&quot;&gt;{heading}&lt;/th&gt;{/heading_title_cell}</span> 
<span class="s1">    {heading_next_cell}&lt;th&gt;&lt;a href=&quot;{next_url}&quot;&gt;&amp;gt;&amp;gt;&lt;/a&gt;&lt;/th&gt;{/heading_next_cell}</span> 
<span class="s1"> </span> 
<span class="s1">    {heading_row_end}&lt;/tr&gt;{/heading_row_end}</span> 
 
<span class="s1">    {week_row_start}&lt;tr&gt;{/week_row_start}</span> 
<span class="s1">    {week_day_cell}&lt;td&gt;{week_day}&lt;/td&gt;{/week_day_cell}</span> 
<span class="s1">    {week_row_end}&lt;/tr&gt;{/week_row_end}</span> 
 
<span class="s1">    {cal_row_start}&lt;tr&gt;{/cal_row_start}</span> 
<span class="s1">    {cal_cell_start}&lt;td&gt;{/cal_cell_start}</span> 
 
<span class="s1">    {cal_cell_content}&lt;a href=&quot;{content}&quot;&gt;{day}&lt;/a&gt;{/cal_cell_content}</span> 
<span class="s1">    {cal_cell_content_today}&lt;div class=&quot;highlight&quot;&gt;&lt;a href=&quot;{content}&quot;&gt;{day}&lt;/a&gt;&lt;/div&gt;   </span> 
<span class="s1">    {/cal_cell_content_today}</span> 
 
<span class="s1">    {cal_cell_no_content}{day}{/cal_cell_no_content}</span> 
<span class="s1">    {cal_cell_no_content_today}&lt;div class=&quot;highlight&quot;&gt;{day}&lt;/div&gt;{/cal_cell_no_content_today}</span> 
 
<span class="s1">    {cal_cell_blank}&amp;nbsp;{/cal_cell_blank}</span> 
 
<span class="s1">    {cal_cell_end}&lt;/td&gt;{/cal_cell_end}</span> 
<span class="s1">    {cal_row_end}&lt;/tr&gt;{/cal_row_end}</span> 
 
<span class="s1">    {table_close}&lt;/table&gt;{/table_close}</span> 
<span class="s1">&#39;</span><span class="p">;</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;calendar/calendar_library&#39;</span><span class="p">,</span> <span class="s1">&#39;cal&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
 
<span class="k">echo</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">cal</span><span class="o">-&gt;</span><span class="na">generate</span><span class="p">();</span> 
</pre></div> 
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 
<!-- end onecolumn -->
