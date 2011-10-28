/*--------------------------------------------------------------
reset.css
@author Zhou Yuan
@copyright Institute for Learning 2009 - 2010
-------------------------------------------------------------- */
html, body, div, span, object, iframe,
h1, h2, h3, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
em, font, img, ins, kbd,
small, strong, sub, sup, tt, 
b, u, i,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td {
margin:0;
padding:0;
border:0;
outline:0;
font-size:100%;
vertical-align:baseline;
background:transparent;
}

ol, ul {
list-style:none;
}

blockquote {
quotes:none;
}

:focus {
outline:0;  
}

/* tables still need 'cellspacing="0"' in the markup */
table {
border-collapse:collapse;
border-spacing:0;
}

html {
overflow-y:scroll;
}

body {
font-family:Arial, Helvetica, sans-serif;
font-size:90%;
color: #363636;	 
}

.container {
padding:0 20px;
}

.row {
width: 100%;
max-width: 960px;
min-width: 755px;
margin: 0 auto;
overflow: hidden;
}

.onecol, .twocol, .threecol, .fourcol, .fivecol, .sixcol, .sevencol, .eightcol, .ninecol, .tencol, .elevencol {
margin-right: 3.8%;
float: left;
min-height: 1px;
}

.row .onecol {
width: 4.85%;
}

.row .twocol {
width: 13.45%;
}

.row .threecol {
width: 22.05%;
}

.row .fourcol {
width: 30.75%;
}

.row .fivecol {
width: 39.45%;
}

.row .sixcol {
width: 48%;
}

.row .sevencol {
width: 56.75%;
}

.row .eightcol {
width: 65.4%;
}

.row .ninecol {
width: 74.05%;
}

.row .tencol {
width: 82.7%;
}

.row .elevencol {
width: 91.35%;
}

.row .twelvecol {
width: 100%;
float: left;
}

.last {
margin-right: 0px;
}

img, object, embed {
max-width: 100%;
}

img {
height: auto;
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

h1, h2, h3 {
color:#3e3e3e;
font-weight:700;
line-height:1.5em;
}

h1 { 
font-size:2.6em; 
padding:20px 0 15px 0;
color: #776c68;	
letter-spacing:-0.02em;
}

h2 { 
font-size:1.6em;
padding:15px 0 10px 0;
color: #776c68;	
letter-spacing:-0.02em;
}

h3 { 
font-size:1.3em;  
padding:8px 0 5px 0;
color: #776c68;
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
padding-left:23px;
font-family: "felt-tip-roman-1","felt-tip-roman-2", Chalkboard, "Comic Sans MS", sans-serif;
font-size:16px;
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

.php_function {
background:url('<?php echo STATIC_URI_BASE; ?>images/shared/php_function.gif') no-repeat center left; 
padding-left:16px;
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
margin:10px 0;
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

.header {
background:#3b5998;
padding:10px 0;
border-bottom:10px solid #6d84b4;
position:fixed;
width:100%;
z-index:100;
}

.topnav {
float:left;
}

.topnav li{
display:inline;
line-height:29px;
}

.topnav li a {
float:left;
font-weight:700;
font-size:15px;
color:#fff;
text-decoration:none;
margin-right:15px;
}

.logo a {
background:url(<?php echo STATIC_URI_BASE; ?>images/shared/logo.jpg) no-repeat 0 0;
width:180px;
text-indent:-9999px;
}

.logo a:hover{
background-position:-180px 0;
}

.nav_item a {
padding:0 10px;
}

.nav_item a:hover {
background:#6D84B4;
padding:0 10px;
}

/* content
-------------------------------------------------------------- */
.content {
background:#fff;
border-bottom:2px solid #ddd;
padding-top:70px;
padding-bottom:20px;
}

.content ul {
list-style:disc url('<?php echo STATIC_URI_BASE; ?>images/shared/green_dot.gif');
}

.content ol {
list-style-type:decimal;
}

.content li {
margin-left:20px;
padding:3px 0;
}

#breadcrumb {
border-bottom:1px solid #ddd; 
margin-bottom:20px;
padding-bottom:20px;
}

#breadcrumb h1{
padding:0;
}

/* homepage
-------------------------------------------------------------- */
#intro {
margin-top:10px;
}

/* footer
-------------------------------------------------------------- */
.footer{
padding:10px 0;
margin:0;
line-height:180%;
background:#f2f2f2;
}

/* contact form
-------------------------------------------------------------- */
#contact {  
width:640px;
margin-bottom:20px; 
}

.contact_input {
width:400px;
}

.contact_textarea {
width:620px;
height:200px;
}

.anti_spam {
visibility: hidden;
height:0;
}


/* Fading Tooltips
-------------------------------------------------------------- */
#toolTip {
position:absolute;
max-width:300px;
background:#000;
border:2px double #fff;
text-align:left;
padding:5px;
-moz-border-radius:5px;
z-index:9999;
}

#toolTip p {
margin:0;
padding:0px;
color:#fff;
font:11px/12px verdana,arial,serif;
}

.ribbon { 
position: absolute; 
top: -1px; 
right: -1px; 
opacity: 0.9; 
}

.ribbon:hover, .ribbon:focus, .ribbon:active { 
opacity: 1; 
}

.ribbon img { 
display: block; 
border: 0; 
}

/* Numbered List */
.list_numbered {
line-height: 20px;
list-style: none;
}

.list_numbered li {
line-height: 20px;
list-style: none;
margin:0 0 20px 0;
}

span.big_number {
float: left;
width: 60px;
line-height: 44px;
display: block;
height: 60px;
font-size: 40px;
color: #5B74A8;
}

.list_numbered li p {
overflow: hidden;
}


/* Client Showcase */
.clients_row{
clear:both;
border-bottom:1px solid #ddd;
margin:20px 0;
}

.client{
float:left;
width:250px;
border-left:1px dotted #ddd;
margin:30px 0;
padding:0 28px 0 28px;
}

.first{
border-left:none;
argin-left:0;
adding:0;
}

.client img{
border:3px solid #d9d9d9;
idth:215px;
height:110px;
padding:3px;
}

.client a:hover img{
border:3px solid #3b5998;
}

.client a:hover{
text-decoration:none;
}

.client .title{
font-size: 16px;
font-weight:normal;
margin-top:10px;
}

/* Contact Form Error Messages */
.form_error div {
line-height:180%;
}

/* google +1, twitter, fb like button */
#share {
width:163px;
float:right;
padding:10px 0px;
margin:20px 0 0 10px;
}

#share span {
display:block;
font:400 1.3em/1.2 'Covered By Your Grace', cursive;
background: url(<?php echo STATIC_URI_BASE; ?>images/shared/heart.png) left 3px no-repeat;
padding:0 0 10px 20px;
}

.fact{
border-bottom:1px solid #d3dae5;
padding:14px 0;
}

.last_row .fact{
border-bottom:none;
}

.fact_img{
width:52px;
height:52px;
float:left;
margin:5px 10px 0 3px;
}


.fact_desc{
overflow: hidden;
}

.fact_desc h3{
font-size: 14px;
line-height:20px;
margin-bottom:0;
color:#4866A9;
}

#download_list {
list-style:none !important;
padding:0 !important;
}

#download_list li{
margin:0 !important;
}

#download {
width:150px;
background-color: #eee;
padding:5px;
}

#download_button{
width:150px;
font-size:1.2em;
min-height:38px;
letter-spacing:0em;
padding:5px;
text-align:center;
}

#download_button span {
display:block;
font-size:0.5em;
}

a.print{
float:right;
border:1px solid #d8dfea;
color:#3B5998;
padding:5px 5px 5px 25px;
margin:0 0 0 10px;
background:#f7f7f7 url(<?php echo STATIC_URI_BASE; ?>images/shared/print.gif) no-repeat 4px 7px;
}

a.print:hover{
background:#d8dfea url(<?php echo STATIC_URI_BASE; ?>images/shared/print.gif) no-repeat 4px 7px;
}





.highlighted_code {
overflow-x: auto; 
white-space: nowrap;
border: 1px solid #ccc;
}

.line_num { 
float: left; 
background:#eee;
width:25px;
color: #363636;	
text-align: right; 
margin-right:10px; 
padding-right:5px; 
border-right: 1px solid #ccc;
}

.highlighted_code .meta {
background:#ddd;
padding:10px;
}
