<!DOCTYPE html>
<html class="no-js">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title><?php echo isset($page_title) ? $page_title : 'InfoPotato'; ?></title>

<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- favicon -->  
<link rel="shortcut icon" href="<?php echo STATIC_URI_BASE; ?>favicon.ico">

<!-- CSS Style --> 
<link rel="stylesheet" href="<?php echo APP_URI_BASE; ?>css/index/potatoguide.css:main.css<?php if(isset($stylesheets)) { echo ':'.implode(':', $stylesheets);  } ?>"> 

<!-- JavaScript -->
<script src="<?php echo APP_URI_BASE; ?>js/index/modernizr-2.7.1.min.js<?php if(isset($javascripts)) { echo ':'.implode(':', $javascripts);  } ?>"></script>

</head> 

<body>


<!-- begin header -->
<header class="header clearfix">

<div class="inner">

<h1 class="logo">InfoPotato</h1>

<h2 class="tagline">The Lighter and Faster PHP5 Framework</h2>

</div>

</header> 
<!-- end header --> 
 
<!-- begin main content --> 
<div class="content clearfix"> 

<?php echo isset($content) ? $content : ''; ?>

</div> 
<!-- end content --> 

<!-- begin footer --> 
<footer class="footer clearfix">
<div class="inner">

<div class="inner">
&copy; Zhou Yuan 2009-2014. All rights reserved. Back-end powered by <a href="http://infopotato.com/" class="external_link" target="_blank">InfoPotato</a> and front-end styled with <a href="http://potatoguide.com/" class="external_link" target="_blank">PotatoGuide</a>.
</div>

</div>
</footer> 
<!-- end footer --> 


</body> 

</html> 
