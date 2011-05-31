<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>tutorials/">Tutorials</a> &gt; Play Video
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Play Video</h1>	

<script type="text/javascript">
/* 
variables:
jpg : Archivo de imagen (thumbtail)
flv : Archivo multimedia de video tipo FLV
mp3 : Archivo de audio tipo MP3
autoplay : true : Empieza a reproducir el archivo una vez cargado el reproductor
fullscreen: false : Ocultar opcion de FullScreen (Direfente del parametro allowFullScreen)
backcolor : Color de la barra de reproducción. Ejemplo de color blanco: FFFFFF
frontcolor : Color de las opciones de la barra de reproduccion. Ejemplo de color negro: 000000
effect : false : Oculta los efectos de la barra de reproduccion
*/
// Don't add comma "," at the end of the last object element
var flashvars = {
	file: '<?php echo STATIC_URI_BASE; ?>videos/demo.flv', // Flash Video File
	image: '<?php echo STATIC_URI_BASE; ?>images/content/demo.jpg', // Preview photo
	duration: 124,
	autostart: false, // Autoplay, make true to autoplay
	plugins: 'captions-2',
	'captions.back': true, // a semitransparant black background is drawn below the captions
	'captions.file': '<?php echo STATIC_URI_BASE; ?>videos/captions.xml',
	'captions.state': true, // whether to show the captions on startup or not
	dock: false // showing a controlbar button CC
};

var params = {
	allowFullScreen: true, // Allow fullscreen, disable with false
	allowscriptaccess: 'always'
};

var attributes = {
	id: 'demo',
	name: 'demo'
};
swfobject.embedSWF('<?php echo STATIC_URI_BASE; ?>videos/player.swf', 'alternative_content', '480', '384', '9.0.0', '<?php echo STATIC_URI_BASE; ?>videos/expressInstall.swf', flashvars, params, attributes);
</script>

<div class="video_container">
<div id="alternative_content">
<a href="http://www.adobe.com/go/getflashplayer"><img src="<?php echo STATIC_URI_BASE; ?>images/shared/get_flash_player.gif" alt="Get Adobe Flash player" />Get Adobe Flash player</a>
</div>
</div>

</div> 
<!-- end onecolumn -->
