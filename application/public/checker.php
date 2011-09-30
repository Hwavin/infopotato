<?php
//header('Content-Type: text/html; charset=utf-8');
//header('Cache-Control: s-maxage=0, max-age=0, must-revalidate');
//header('Expires: Mon, 23 Jan 1978 10:00:00 GMT');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex">
<meta name="generator" content="InfoPotato">

<title>InfoPotato Requirements Checker</title>

<style type="text/css">
html {
font: 13px/1.5 Verdana, sans-serif;
border-top: 5.3em solid #3b5998;
}

body {
border-top: 1px solid #E4DED5;
margin: 0;
background: #fff;
color: #333;
}

#container {
width: 960px;
margin: -5.3em auto 20px;
}

h1 {
font: 2.3em/1.5 sans-serif;
margin: .5em 0 1.5em;
color: #fff;
}

h2 {
font-size: 2em;
font-weight: normal;
color: #3484D2;
margin: .7em 0;
}

p {
margin: 1.2em 0;
}

.result {
margin: 1.5em 0;
padding: 0 1em;
border: 2px solid #fff;
}

.passed h2 {
color: #1A7E1E;
}

.failed h2 {
color: #8a1f11;
}

table {
padding: 0;
margin: 0;
border-collapse: collapse;
width: 100%;
}

table td, table th {
text-align: left;
padding: 10px;
vertical-align: top;
border-style: solid;
border-width: 1px 0 0;
border-color: inherit;
background: #fff none no-repeat 12px 8px;
background-color: inherit;
}

table th {
font-size: 105%;
font-weight: bold;
padding-left: 50px;
}

.passed, .info {
background-color: #E4F9E3;
border-color: #C6E5C4;
}

.passed th {
background-image: url('../checker/passed.gif');
}


.info th {
background-image: url('../checker/info.gif');
}


.warning {
background-color: #FEFAD4;
border-color: #EEEE99;
}

.warning th {
background-image: url('../checker/warning.gif');
}

.failed {
background-color: #F4D2D2;
border-color: #D2B994;
}

div.failed {
background-color: #fbc2c4;
border-color: #CD1818;
}

.failed th {
background-image: url('../checker/failed.gif');
}


.description td {
border-top: none !important;
padding: 0 10px 10px 50px;
color: #555;
}

.passed.description {
display: none;
}
</style>

<script src="../checker/denied/checker.js" type="text/javascript"></script>
</head>


<?php
/**
 * Check PHP configuration.
 */
foreach (array('function_exists', 'version_compare', 'extension_loaded', 'ini_get') as $function) {
	if ( ! function_exists($function)) {
		die("Error: function '$function' is required by InfoPotato and this Requirements Checker.");
	}
}

/**
 * Check InfoPotato requirements.
 */
$requirements = array();

$requirements[] = array(
	'title' => 'Web server',
	'message' => $_SERVER['SERVER_SOFTWARE'],
);

$requirements[] = array(
	'title' => 'PHP version',
	'required' => TRUE,
	'passed' => version_compare(PHP_VERSION, '5.2.0', '>='),
	'message' => PHP_VERSION,
	'description' => 'Your PHP version is too old. InfoPotato requires at least PHP 5.2.0 or higher.',
);

$requirements[] = array(
	'title' => 'Memory limit',
	'message' => ini_get('memory_limit'),
);

$requirements['ha'] = array(
	'title' => '.htaccess file protection',
	'required' => FALSE,
	'description' => 'File protection by <code>.htaccess</code> is optional. If it is absent, you must be careful to put files into document_root folder.',
	'script' => "var el = document.getElementById('resha');\nel.className = typeof checkerScript == 'undefined' ? 'passed' : 'warning';\nel.parentNode.removeChild(el.nextSibling.nodeType === 1 ? el.nextSibling : el.nextSibling.nextSibling);",
);

$requirements[] = array(
	'title' => 'Function ini_set',
	'required' => FALSE,
	'passed' => function_exists('ini_set'),
	'description' => 'Function <code>ini_set()</code> is disabled. Some parts of InfoPotato may not work properly.',
);

$requirements[] = array(
	'title' => 'Function error_reporting',
	'required' => TRUE,
	'passed' => function_exists('error_reporting'),
	'description' => 'Function <code>error_reporting()</code> is disabled. InfoPotato requires this to be enabled.',
);

$requirements[] = array(
	'title' => 'Register_globals',
	'required' => TRUE,
	'passed' => ! ini_flag('register_globals'),
	'message' => 'Disabled',
	'errorMessage' => 'Enabled',
	'description' => 'Configuration directive <code>register_globals</code> is enabled. InfoPotato requires this to be disabled.',
);

$requirements[] = array(
	'title' => 'Zend.ze1_compatibility_mode',
	'required' => TRUE,
	'passed' => ! ini_flag('zend.ze1_compatibility_mode'),
	'message' => 'Disabled',
	'errorMessage' => 'Enabled',
	'description' => 'Configuration directive <code>zend.ze1_compatibility_mode</code> is enabled. InfoPotato requires this to be disabled.',
);

$requirements[] = array(
	'title' => 'Variables_order',
	'required' => TRUE,
	'passed' => strpos(ini_get('variables_order'), 'G') !== FALSE && strpos(ini_get('variables_order'), 'P') !== FALSE && strpos(ini_get('variables_order'), 'C') !== FALSE,
	'description' => 'Configuration directive <code>variables_order</code> is missing. InfoPotato requires this to be set.',
);

$requirements[] = array(
	'title' => 'Session auto-start',
	'required' => TRUE,
	'passed' => session_id() === '' && ! defined('SID'),
	'description' => 'Session auto-start is enabled. InfoPotato requires this to be disabled.',
);

$requirements[] = array(
	'title' => 'Reflection extension',
	'required' => TRUE,
	'passed' => class_exists('ReflectionFunction'),
	'description' => 'Reflection extension is required.',
);

$requirements[] = array(
	'title' => 'SPL extension',
	'required' => TRUE,
	'passed' => extension_loaded('SPL'),
	'description' => 'SPL extension is required.',
);

$requirements[] = array(
	'title' => 'PCRE extension',
	'required' => TRUE,
	'passed' => extension_loaded('pcre') && @preg_match('/pcre/u', 'pcre'),
	'message' => 'Enabled and works properly',
	'errorMessage' => 'Disabled or without UTF-8 support',
	'description' => 'PCRE extension is required and must support UTF-8.',
);

$requirements[] = array(
	'title' => 'ICONV extension',
	'required' => TRUE,
	'passed' => extension_loaded('iconv') && (ICONV_IMPL !== 'unknown') && @iconv('UTF-16', 'UTF-8//IGNORE', iconv('UTF-8', 'UTF-16//IGNORE', 'test')) === 'test',
	'message' => 'Enabled and works properly',
	'errorMessage' => 'Disabled or works not properly',
	'description' => 'ICONV extension is required and must work properly.',
);

$requirements[] = array(
	'title' => 'PHP tokenizer',
	'required' => TRUE,
	'passed' => extension_loaded('tokenizer'),
	'description' => 'PHP tokenizer is required.',
);

$requirements[] = array(
	'title' => 'Multibyte String extension',
	'required' => FALSE,
	'passed' => extension_loaded('mbstring'),
	'description' => 'Multibyte String extension is absent. Some internationalization components may not work properly.',
);

$requirements[] = array(
	'title' => 'Multibyte String function overloading',
	'required' => TRUE,
	'passed' => ! extension_loaded('mbstring') || ! (mb_get_info('func_overload') & 2),
	'message' => 'Disabled',
	'errorMessage' => 'Enabled',
	'description' => 'Multibyte String function overloading is enabled. InfoPotato requires this to be disabled. If it is enabled, some string function may not work properly.',
);

$requirements[] = array(
	'title' => 'Magic quotes',
	'required' => FALSE,
	'passed' => ! ini_flag('magic_quotes_gpc') && ! ini_flag('magic_quotes_runtime'),
	'message' => 'Disabled',
	'errorMessage' => 'Enabled',
	'description' => 'Magic quotes <code>magic_quotes_gpc</code> and <code>magic_quotes_runtime</code> are enabled and should be turned off. InfoPotato disables <code>magic_quotes_runtime</code> automatically.',
);

$requirements[] = array(
	'title' => 'Fileinfo extension or mime_content_type()',
	'required' => FALSE,
	'passed' => extension_loaded('fileinfo'),
	'description' => 'Fileinfo extension or function <code>mime_content_type()</code> are absent. You will not be able to determine mime type of uploaded files.',
);

$errors = $warnings = FALSE;

foreach ($requirements as $id => $requirement) {
	$requirements[$id] = $requirement = (object) $requirement;
	if (isset($requirement->passed) && ! $requirement->passed) {
		if ($requirement->required) {
			$errors = TRUE;
		} else {
			$warnings = TRUE;
		}
	}
}


/**
 * Gets the boolean value of a configuration option.
 * @param  string  configuration option name
 * @return bool
 */
function ini_flag($var) {
	$status = strtolower(ini_get($var));
	return $status === 'on' || $status === 'true' || $status === 'yes' || (int) $status;
}
?>


<body>
<div id="container">
<h1>InfoPotato Requirements Checker</h1>

<p>
This script checks if your server and PHP configuration meets the requirements for running InfoPotato. It checks your PHP version, if appropriate PHP extensions have been loaded, and if PHP directives are set correctly.
</p>

<?php if ($errors): ?>
<div class="failed result">
<h2>Oops! Your server configuration does not satisfy the minimum requirements of InfoPotato.</h2>
</div>
<?php else: ?>
<div class="passed result">
<h2>Great! Your server configuration meets the minimum requirements for InfoPotato.</h2>
<?php if ($warnings): ?>
<p><strong>Please see the warnings listed below.</strong></p>
<?php endif; ?>
</div>
<?php endif; ?>


<h2>Details</h2>

<table>
<?php foreach ($requirements as $id => $requirement): ?>

<?php $class = isset($requirement->passed) ? ($requirement->passed ? 'passed' : ($requirement->required ? 'failed' : 'warning')) : 'info'; ?>
<tr id="res<?php echo $id; ?>" class="<?php echo $class; ?>">
<th><?php echo htmlSpecialChars($requirement->title); ?></th>

<?php if (empty($requirement->passed) && isset($requirement->errorMessage)): ?>
<td><?php echo htmlSpecialChars($requirement->errorMessage); ?></td>
<?php elseif (isset($requirement->message)): ?>
<td><?php echo htmlSpecialChars($requirement->message); ?></td>
<?php elseif (isset($requirement->passed)): ?>
<td><?php echo $requirement->passed ? 'Enabled' : 'Disabled'; ?></td>
<?php else: ?>
<td></td>
<?php endif; ?>
</tr>

<?php if (isset($requirement->description)): ?>
<tr id="desc<?php echo $id; ?>" class="<?php echo $class; ?> description">
<td colspan="2"><?php echo $requirement->description; ?></td>
</tr>
<?php endif; ?>

<?php if (isset($requirement->script)): ?>
<script type="text/javascript"><?php echo $requirement->script; ?></script>
<?php endif; ?>

<?php endforeach; ?>
</table>

<?php if ($errors): ?>
<p>Please check the error messages and <a href="?">try again</a>.</p>
<?php endif; ?>

</div>
</body>
</html>