<html>
<head>
<title>Dump var</title>
<style type="text/css">
body {
background-color:#fff;
margin:40px;
font-family:Lucida Grande, Verdana, Sans-serif;
font-size:12px;
color:#000;
}

#content  {
background:#f7f7f7;  
border:1px solid #ddd;  
padding:20px;
}

h1 {
font-size:16px;
color:#990000;
}
</style>
</head>
<body>
<div id="content">
<h1>Dump var</h1>
<pre>
<?php echo htmlspecialchars(print_r($var, TRUE)); ?>
</pre>
</div>
</body>
</html>