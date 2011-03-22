<?php
include_once('../simple_html_dom.php');

echo file_get_html('http://ifl.lrdc.pitt.edu/ifl/index.php/home')->plaintext;
?>