<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 

<!-- Metadata -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="distribution" content="Global" /> 
<meta name="robots" content="index,follow" /> 
<meta name="author" content="Zhou Yuan" /> 
<meta name="copyright" content="Copyright (C) Zhou Yuan" /> 
<meta name="rating" content="General" /> 
<meta name="revisit-after" content="14 Days" /> 
<meta name="description" content="InfoPotato" /> 
<meta name="keywords" content="InfoPotato" /> 

<title><?php echo $page_title; ?> | InfoPotato</title>

<!-- favicon -->  
<link rel="icon" href="<?php echo BASE_URI; ?>img/shared/favicon.ico" type="image/x-icon" /> 

<!-- CSS Style --> 
<link type="text/css" rel="stylesheet" href="<?php echo BASE_URI; ?>css/reset.css:main.css<?php if(isset($stylesheets)) { echo ':'.implode(':', $stylesheets);  } ?>" media="screen, projection, handheld" charset="utf-8" /> 
<link type="text/css" rel="stylesheet" href="<?php echo BASE_URI; ?>css/reset.css:print.css" media="print" charset="utf-8" /> 

<!-- JavaScript -->
<script type="text/javascript" language="javascript" src="<?php echo BASE_URI; ?>js/sweet_titles.js"></script>

<?php if(isset($javascripts)) : ?>
<script type="text/javascript" language="javascript" src="<?php echo BASE_URI; ?>js/<?php echo implode(':', $javascripts); ?>"></script>
<?php endif; ?>
</head> 

<body>	

<!-- begin container -->
<div id="container"> 

<!-- begin container -->
<div id="header">

<!-- begin header inner --> 
<div class="inner">

<!-- begin header inner topnav--> 
<div id="topnav">
<ul> 
<li id="logo"><a href="<?php echo BASE_URI; ?>home/" title="Return to frontpage">infopotato</a></li>
<li class="nav_item"><a href="<?php echo BASE_URI; ?>about/" title="About">About</a></li>
<li class="nav_item"><a href="<?php echo BASE_URI; ?>download/" title="Download">Download</a></li>
<li class="nav_item"><a href="<?php echo BASE_URI; ?>news/" title="News">News</a></li>
<li class="nav_item"><a href="<?php echo BASE_URI; ?>documentation/" title="Documentation">Documentation</a></li>
<li class="nav_item"><a href="<?php echo BASE_URI; ?>tutorials/" title="Tutorials">Tutorials</a></li>
<li class="nav_item"><a href="<?php echo BASE_URI; ?>contact/" title="Contact">Contact</a></li>
</ul>

</div>	
<!-- end header inner topnav--> 
<div class="clear"></div> 

</div>
<!-- end header inner -->

</div>  
<!-- end header --> 
<div class="clear"></div> 

<!-- begin content --> 
<div id="content"> 
<div id="alpha_bar"></div>
<?php echo $content; ?>
<div class="clear"></div>
<div class="hide">
Page URI: <?php echo BASE_URI.$_SERVER["REQUEST_URI"];?>
</div>
</div> 
<!-- end content --> 
<div class="clear"></div> 
 

<!-- begin footer --> 
<div id="footer"> 
Powered by InfoPotato PHP5 Framework &copy; Zhou Yuan 2009-2011
</div>
<!-- end footer -->
 
</div> 
<!-- end container -->

</body> 

</html> 
