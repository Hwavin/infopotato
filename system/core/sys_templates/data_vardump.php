<div style="background:#f7f7f7; border:1px solid #ddd; padding:20px; margin:20px;">
<h1 style="color:#990000;">Dump Database Variable</h1>

<b>Variable Type:</b> <?php echo gettype($mixed); ?><br />
<b>Query:</b> <?php echo ($this->last_query ? $this->last_query : 'NULL'); ?><br />
<b>Rows Returned:</b> <?php echo count($this->last_result); ?><br />

<pre>
<?php print_r(($mixed ? $mixed : 'No Value / FALSE')); ?>
</pre>
</div>
