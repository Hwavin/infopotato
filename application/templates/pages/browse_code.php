<div class="row">

<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading"><?php echo __('Code'); ?></h1> 
<a href="<?php echo APP_URI_BASE; ?>home"><?php echo __('Home'); ?></a> &gt; <a href="<?php echo APP_URI_BASE; ?>code"><?php echo __('Code'); ?></a>&gt; <?php echo $filename; ?>
</div>
<!-- end breadcrumb -->

<code style="overflow-x: auto; white-space: nowrap">
<?php foreach ($highlighted_code as $num => $string): $num++; ?>
<span class="line_num"><?php echo $num ?></span>
<?php echo $string; ?>
<br>
<?php endforeach; ?>
</code>

</div> 

 


 