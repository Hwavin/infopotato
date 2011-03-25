<div style="background:#f7f7f7; border:1px solid #ddd; padding:20px; margin:20px;">
<h1 style="color:#990000;">Dump Database Variable</h1>

<b>Type:</b> <?php echo gettype($mixed); ?><br />
<b>Last Query</b> <?php echo "[$this->num_queries]: ".($this->last_query ? $this->last_query : 'NULL'); ?><br />
<b>Last Function Call:</b> <?php echo ($this->func_call ? $this->func_call : 'None'); ?><br />
<b>Last Rows Returned:</b> <?php echo count($this->last_result); ?><br />

<pre>
<?php print_r(($mixed ? $mixed : 'No Value / FALSE')); ?>
</pre>
</div>
