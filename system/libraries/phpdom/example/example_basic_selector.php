<?php
// example of how to use basic selector to retrieve HTML contents
include('../simple_html_dom.php');
 
// get DOM from URL or file
$html = file_get_html('http://ifl.lrdc.pitt.edu/ifl/index.php/home');

// find all link
foreach($html->find('a') as $e) 
    echo $e->href . '<br>';

// find all image
foreach($html->find('img') as $e)
    echo $e->src . '<br>';

// find all image with full tag
foreach($html->find('img') as $e)
    echo $e->outertext . '<br>';

// find all div tags with id=gbar
foreach($html->find('div#gbar') as $e)
    echo $e->innertext . '<br>';

// find all span tags with class=gb1
foreach($html->find('span.gb1') as $e)
    echo $e->outertext . '<br>';

// find all td tags with attribite align=center
foreach($html->find('div[id=content]') as $e)
    echo $e->innertext . '<br>';

// extract text from HTML
echo $html->plaintext;
?>