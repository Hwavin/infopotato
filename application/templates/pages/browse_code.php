<div class="row">

<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading">Code</h1> 
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>code">Code</a>&gt; <?php echo $filename; ?>
</div>
<!-- end breadcrumb -->

<p>

</p>

<div class="highlighted_code">
<div class="meta">
<?php echo $filename; ?>
</div>

<code>
<?php foreach ($highlighted_code as $num => $string): $num++; ?>
<span class="line_num"><?php echo $num ?></span>
<?php echo $string; ?>
<br />
<?php endforeach; ?>
</code>
</div>

</div> 

 


 