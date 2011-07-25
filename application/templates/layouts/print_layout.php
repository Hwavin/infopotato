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
<link rel="icon" href="<?php echo STATIC_URI_BASE; ?>images/shared/favicon.ico" type="image/x-icon" /> 

<!-- CSS Style --> 
<link type="text/css" rel="stylesheet" href="<?php echo APP_URI_BASE; ?>css/index/reset.css:printer.css<?php if(isset($stylesheets)) { echo ':'.implode(':', $stylesheets);  } ?>" media="screen, projection, handheld" charset="utf-8" /> 
<link type="text/css" rel="stylesheet" href="<?php echo APP_URI_BASE; ?>css/index/reset.css:print.css" media="print" charset="utf-8" /> 

</head> 

<body>	

<div id="container"> 
<div class="row">
<a href="javascript:print();" title="Click to print">Print</a> 
<?php echo $content; ?>
<div class="clear"></div>
</div>
</div> 

</body> 

</html> 
