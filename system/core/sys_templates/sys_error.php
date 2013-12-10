<html>
<head>
<title>Error</title>
<style type="text/css">
body {
background-color:#fff;
margin:40px;
font-family:Lucida Grande, Verdana, Sans-serif;
font-size:12px;
color:#000;
word-wrap: break-word;
}

#content  {
border:1px solid #FBC2C4; 
background-color:#FBE3E4;
padding:20px;
}

h1 {
font-size:16px;
color:#990000;
}

h2 {
font-size:13px;
color:#990000;
}
</style>
</head>
<body>
<div id="content">
<h1><?php echo $heading; ?></h1>
<p><?php echo $message; ?></p>
<h2>Backtrace Info</h2>
<div><pre><?php echo $debug_backtrace; ?></pre></div>
</div>
</body>
</html>