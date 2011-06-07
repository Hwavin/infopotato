/*--------------------------------------------------------------
printer.css
@author Zhou Yuan
-------------------------------------------------------------- */

/* XHTML elements
-------------------------------------------------------------- */
html {
overflow-y:scroll;
}

/* Overriding The Default Text Selection Color with CSS3 */
::selection { 
background:#ffcc89; 
color:#222; 
}

::-moz-selection { 
background:#ffcc89; 
color:#222; 
}

body {
font-family:Arial, Helvetica, sans-serif;
font-size:90%;
color: #555; 
background:#f2f2f2;
}

h1, h2, h3 {
color:#3e3e3e;
font-weight:700;
line-height:1.2em;
}

h1 { 
font-size:2.6em; 
padding:20px 0 15px 0;
}

h2 { 
font-size:1.6em;
padding:15px 0 10px 0;
}

h3 { 
font-size:1.3em;  
padding:8px 0 5px 0;
}

a:link, a:visited {
color:#3b5998;
text-decoration:none;
}

/* a:hover MUST come after a:link and a:visited in the CSS definition in order to be effective! */
a:hover {
color:#f75342;
text-decoration:underline;
}

/* a:active MUST come after a:hover in the CSS definition in order to be effective! */
a:active {
color:#f75342;
text-decoration:underline;
/* All links bump themselves down 1px as you click them */
position: relative;
top: 1px;
}

p {
line-height:180%;
margin:0 0 1.5em 0;
}

li{
line-height:180%;
}

ul, ol { 
margin-bottom:1.5em; 
}

table {
margin-bottom:1.4em; 
background-color:#fff;
}

th {
font-size:1.2em;
text-align:left;
padding:10px;
font-weight:bold;
vertical-align:bottom;
}

td {
padding:10px;
}

sup, sub  { 
line-height:0; 
font-size:0.9em;
}

sup { 
vertical-align:super; 
}

sub { 
vertical-align:sub; 
}

abbr, acronym{ 
border-bottom:1px dotted #ff0000; 
}

address {
margin-bottom:1.5em;
font-style:normal;
}

/* form elements */
input[type=text], input[type=password], 
input[type=checkbox], input[type=radio], 
textarea, select {
font-family:Arial, Helvetica, sans-serif;
}

input[type=text], input[type=password], textarea, select {
border:1px solid #ddd;
padding:4px 5px;
}

input[type=checkbox], input[type=radio] { 
position:relative; 
top:0.25em; 
}

select {
border:1px solid #ddd;
padding:3px 5px;
}

input:focus, textarea:focus, select:focus{
background:#fff9d7;  
border:1px solid #ffd324;   
}

legend {
padding:0 5px;
}

fieldset { 
border: 1px solid #d7d7d7; 
padding: .5em;
margin: 0 
}

fieldset.iefix { 
background: transparent; 
border: none; 
padding: 0; 
margin: 0 
}

* html fieldset.iefix { 
width: 98% 
}

fieldset.iefix p { 
margin: 0 
}

legend { 
color: #999; 
padding: 0 .25em; 
font-weight: bold 
}

blockquote{
background:url(<?php echo STATIC_URI_BASE; ?>images/shared/quote_left.png) no-repeat left 2px;
font-style:italic;
line-height:1.5em;
padding-left:23px;
}

blockquote span{
background:url(<?php echo STATIC_URI_BASE; ?>images/shared/quote_right.png) no-repeat right 3px;
color:#666;
padding-right:23px;
}

blockquote div{
padding-top:5px;
text-align:right;
}

/* global classes
-------------------------------------------------------------- */
.clear {
clear:both;
}

.hr {
clear:both;
margin:1.5em 0;
border-bottom:1px solid #cbcbcb;
}

.break {
margin-bottom:10px;
border-bottom:1px solid #e2c822;
}

.center {
text-align:center;
}

.red {
color:#ff0000;
}

.blue {
color:#4866A9;
}

.green {
color:#69a74e;
}

.orange {
color:#DA6E00;
}

.purple {
color:#59438A;
}

.pink {
color:#BD4746;
}

.flag{
color:#ff0000;
font-family:verdana,sans-serif;
font-size:9px;
font-variant:normal;
font-weight:700;
text-transform:uppercase;
}

.note{
float:right;
width:231px;
height:178px;
color:#555;
margin-left:10px;
background:url(<?php echo STATIC_URI_BASE; ?>images/shared/note_bg.png) no-repeat center center;
}

.note_content {
padding:20px;
}

.hide { 
display: none; 
}

.first_heading { 
padding-top:10px;
}

.float_left {
float:left;
}

.float_right {
float:right;
}

.content_pic_left {
float:left;
margin:0 20px 0 0;
border:1px solid #ddd;
padding:3px;
}

.content_pic_right {
float:right;
margin:0 0 0 20px;
border:1px solid #ddd;
padding:3px;
}

.content_image {
margin:10px auto 20px auto;
background-color: #f4f4f4;
border:1px solid #ccc;
padding:20px 10px;
text-align: center;
}

.content_image img {
background-color: #f4f4f4;
border: 1px solid #ddd;
margin: auto;
}

.external_link {
background:url('<?php echo STATIC_URI_BASE; ?>images/shared/external_link.png') no-repeat center right; 
padding-right:14px;
}

.box {
border:1px solid #ddd;  
margin-bottom:30px;
}

.box_title {
background:#f7f7f7;  
padding:10px;
}

.box_title h2 {
padding:0 !important;
}

.box_content {
padding:10px;
border-top:1px dotted #ddd;
}

.notebox {
background:#fffecf url('<?php echo STATIC_URI_BASE; ?>images/shared/note.png') no-repeat 10px center;
padding:10px 10px 10px 35px;
border:1px dotted #dda;
line-height:180%;
}

.greybox, .bluebox, .yellowbox, .pinkbox, .greenbox { 
padding:10px; 
margin-bottom:20px;  
}

.greybox {  
background:#f7f7f7;  
border:1px solid #ddd;  
color:#333; 
}   

.bluebox {
background:#eceff6; 
border:1px solid #ddd;  
color:#333;
}

.yellowbox  {  
background:#FFF6BF; 
color:#514721; 
border:1px solid #ffd324; 
} 

.pinkbox  {  
background:#ffebe8; 
color: #8a1f11; 
border:1px solid #fbc2c4; 
}  

.greenbox { 
background:#E6EFC2; 
color:#264409; 
border:1px solid #c6d880; 
}

.box_right {
float:right;
width:220px;
margin:0 0 0 10px;
padding:10px;
position:relative;
}

/* form related */
.form_item {
clear:both;
margin-bottom:5px;
}

.desc {
line-height:150%;
color:#222;
display:block;
}

.req {
color:#F75342;
font-family:verdana,sans-serif;
font-weight:700;
}

.avater{
float:left;
background:#fff;
margin-right:20px;
border:1px solid #ddd;
padding:3px;
}

/* Correcting Orphans w/ Overflow */
.avater_desc {
overflow: hidden;
}

/* link button */
a.link_button {
background:#69a74e;
padding:10px;
font-size:1.2em;   
font-weight:700;
border-color:#3b6e22 #3b6e22 #2c5115;
color:#fff;
border-style:solid;
border-width:1px;
}

a.link_button:hover {  
background:#5b74a8;
border:1px solid #ddd;  
} 

input.submit {
font-size:1.5em;
}

table.grid th {
border:1px solid #c0c0c0;
}

table.grid td {
border:1px solid #c0c0c0;
}

tr.odd td{
background:#d4dae8;
}

tr.even td{
background:#eceff6;
}

/* page layout / structure
-------------------------------------------------------------- */
#container {
width:100%;
min-width:960px;
}

#header {
background-color:#3b5998;
}

.inner {
width:920px;
padding:10px 20px;
margin:0 auto;
}

#topnav {
width:920px;
float:left;
}

#topnav li{
display:inline;
line-height:29px;
}

#topnav li a {
float:left;
font-weight:700;
font-size:15px;
color:#fff;
text-decoration:none;
margin-right:15px;
}

#logo a {
background:url(<?php echo STATIC_URI_BASE; ?>images/shared/logo.jpg) no-repeat 0 0;
width:180px;
text-indent:-9999px;
}

#logo a:hover{
background-position:-180px 0;
}

/* content
-------------------------------------------------------------- */
#content {
width:920px;
padding:20px;
margin:0 auto;
border:solid 2px #ddd;
border-top:none;
position:relative;
background:#fff;
}



#content ul {
list-style:disc url('<?php echo STATIC_URI_BASE; ?>images/shared/green_dot.gif');
}

#content ol {
list-style-type:decimal;
}

#content li {
margin-left:20px;
padding:3px 0;
}

/* footer
-------------------------------------------------------------- */
#footer{
width:960px;
padding:10px 0;
margin:0 auto 10px;
line-height:180%;
}
