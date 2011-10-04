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
<meta name="viewport" content="width=device-width; initial-scale=1.0;"> 

<title><?php echo $page_title; ?> | InfoPotato</title>

<!-- favicon -->  
<link rel="icon" href="<?php echo STATIC_URI_BASE; ?>images/shared/favicon.ico" type="image/x-icon" /> 

<link rel="image_src" href="<?php echo STATIC_URI_BASE; ?>images/shared/fb_like_logo.jpg" />

<!-- CSS Style --> 
<link type="text/css" rel="stylesheet" href="<?php echo APP_URI_BASE; ?>css/index/main.css:fb-buttons.css<?php if(isset($stylesheets)) { echo ':'.implode(':', $stylesheets);  } ?>" media="all" charset="utf-8" /> 
<link type="text/css" rel="stylesheet" href="<?php echo APP_URI_BASE; ?>css/index/print.css" media="print" charset="utf-8" /> 

<!-- JavaScript -->
<script type="text/javascript" language="javascript" src="<?php echo APP_URI_BASE; ?>js/index/sweet_titles.js"></script>
	
<?php if(isset($javascripts)) : ?>
<script type="text/javascript" language="javascript" src="<?php echo APP_URI_BASE; ?>js/index/<?php echo implode(':', $javascripts); ?>"></script>
<?php endif; ?>
</head> 

<body>	

<!-- begin header -->
<div class="container header"> 

<div class="row">

<div class="topnav">
<ul> 
<li class="logo"><a href="<?php echo APP_URI_BASE; ?>home/" title="<?php echo __('Return to frontpage'); ?>">infopotato</a></li>
<li class="nav_item"><a href="<?php echo APP_URI_BASE; ?>about/" title="<?php echo __('About'); ?>"><?php echo __('About'); ?></a></li>
<li class="nav_item"><a href="<?php echo APP_URI_BASE; ?>download/" title="<?php echo __('Download'); ?>"><?php echo __('Download'); ?></a></li>
<li class="nav_item"><a href="<?php echo APP_URI_BASE; ?>code/" title="<?php echo __('Code'); ?>"><?php echo __('Code'); ?></a></li>
<li class="nav_item"><a href="<?php echo APP_URI_BASE; ?>documentation/" title="<?php echo __('Documentation'); ?>"><?php echo __('Documentation'); ?></a></li>
<li class="nav_item"><a href="<?php echo APP_URI_BASE; ?>contact/" title="<?php echo __('Contact'); ?>"><?php echo __('Contact'); ?></a></li>
</ul>
</div>	

</div>

</div>  
<!-- end header --> 

<!-- begin main content --> 
<div class="container content"> 
<?php echo $content; ?>
<div class="clear"></div>
<div class="hide">
Page URI: <?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];?>
</div>
</div> 
<!-- end main content --> 

<!-- begin footer --> 
<div class="container footer">
<div class="row">
<div id="locale_select" class="hide">
<?php echo __('Languages'); ?> <a href="<?php echo APP_URI_BASE; ?>lang/index/en/us/">English</a> - <a href="<?php echo APP_URI_BASE; ?>lang/index/zh/cn/">简体中文</a>
</div>
Powered by InfoPotato PHP5 Framework &copy; Zhou Yuan 2009-2011
</div>
</div>
<!-- end footer -->

</body> 

</html> 
