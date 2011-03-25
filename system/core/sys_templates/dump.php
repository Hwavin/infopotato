<div style="background:#f7f7f7; border:1px solid #ddd; padding:20px; margin:20px;">
<h1 style="color:#990000;">Dump Variable</h1>
<b>Type:</b> <?php echo gettype($var); ?>
<pre>
<?php echo htmlspecialchars(print_r($var, TRUE)); ?>
</pre>
</div>
