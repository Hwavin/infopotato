<!-- begin banner --> 
<div id="banner"> 
<img src="<?php echo BASE_URI; ?>img/banner/search.jpg" width="960" height="180" class="banner_image" alt="Search" title="Search" />
<div class="clear"></div> 
</div> 
<!-- end banner --> 
<div class="clear"></div>

<!-- begin breadcrumb -->
<div id="breadcrumb">
<a href="<?php echo BASE_URI; ?>home">Home</a> &gt; Search
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn --> 
<div id="onecolumn">

<?php if (empty($results)) : ?>
<div class="errorbox">
<h1 class="first_heading">Sorry, your search <strong>keyword</strong> did not match any documents.</h1>
<p>Suggestions:</p>
<ul>
<li>Make sure all words are spelled correctly.</li>
<li>Try different keywords.</li>
<li>Try more general keywords.</li>
<li>Try fewer keywords.</li>
</ul>
</div>
<?php else : ?>
<div class="successbox">
<strong>Your search <strong>keyword</strong> matched <?php echo $results_count; ?> resource(s)</strong>
</div>
<?php foreach ($results as $key => $value) : ?>
<div>
<h2><a href="<?php echo BASE_URI.$value; ?>"><?php echo $key; ?></a></h2>
<div class="hr"></div>
</div>
<?php endforeach; ?>
<?php endif; ?>

</div>
<!-- end onecolumn --> 