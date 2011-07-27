<div class="container"> 

<div class="row">

<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading"><?php echo __('Download'); ?></h1>
<a href="<?php echo APP_URI_BASE; ?>home"><?php echo __('Home'); ?></a> &gt; <?php echo __('Download'); ?>
</div>
<!-- end breadcrumb -->

<h2>Option 1: Package Download</h2> 
<p> 
The latest stable version of InfoPotato is 1.0.0 You may safely use this version in your production Web applications. Continued support and bug fixes for this version will be provided
</p>  

<div class="greybox">
<form method="post" autocomplete="off"> 
<strong>Read and accept the license agreement before downloading.</strong>
<p><label><input type="checkbox" id="agree_to_license_" value="" name="agree_to_license"/> I agree to the terms of the <a href="<?php echo APP_URI_BASE; ?>about/index/license/">License Agreement</a>.</label></p> 
  
<strong>Choose the correct package for your environment.</strong>
<ul id="download_list"> 

<li> 
<label> 
<input type="radio" name="platform" value="Linux" /> 
Linux
</label> 
</li> 

<li> 
<label> 
<input type="radio" name="platform" value="Windows" /> 
Windows
</label> 
</li> 

<li> 
<label> 
<input type="radio" name="platform" value="Mac" /> 
Mac
</label> 
</li> 

</ul> 

<input type="submit" class="uibutton special" value="Download" />
</form> 

</div>
	


<h2>Option 2: InfoPotato on Git</h2> 
<p> 
InfoPotato keeps changing before it has got the ink dry on each draft. Check out the lastest code on Github. The official InfoPotato repository is hosted on <a class="external_link" href="https://github.com/yuanzhou/infopotato">GitHub</a>.
</p> 

<div class="greybox">
<strong>Clone Command:</strong> <span class="red">git clone git://github.com/yuanzhou/infopotato.git</span> 
</div>

<h2>Reporting Bugs</h2>

<p>
We use Lighthouse as issue tracker. Bug reports are incredibly helpful, so take time to report bugs and request features in our ticket tracker. We're always grateful for patches to infoPotato's code. Indeed, bug reports with attached patches will get fixed far quickly than those without any.
</p>


</div> 

</div>