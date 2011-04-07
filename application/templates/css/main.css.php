/*--------------------------------------------------------------
main.css
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

/* (0.75 * 16px = 12px) */
body {
font-family:Arial, Helvetica, sans-serif;
font-size:85%;
color: #555; 
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
margin-bottom:0.5em; 
}

table {
margin-bottom:1.4em; 
width:100%;
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
input[type=button], input[type=submit],
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

input[type=button], input[type=submit] {
cursor:pointer;
background:#3b5998;
color:#fff;
border-color:#d9dfea #0e1f5b #0e1f5b #d9dfea;
border-style:solid;
border-width:1px;
padding:3px 10px;
font-size:1.5em;
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
background:url(<?php echo BASE_URI; ?>img/shared/quote_left.png) no-repeat left 2px;
font-style:italic;
line-height:1.5em;
padding-left:23px;
}

blockquote span{
background:url(<?php echo BASE_URI; ?>img/shared/quote_right.png) no-repeat right 3px;
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
background:url(<?php echo BASE_URI; ?>img/shared/note_bg.png) no-repeat center center;
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
margin:0 10px 0 0;
border:1px solid #ddd;
padding:3px;
}

.content_pic_right {
float:right;
margin:0 0 0 10px;
border:1px solid #ddd;
padding:3px;
}

.content_image {
margin:10px auto 20px auto;
background-color: #f4f4f4;
border:1px solid #ddd;
padding:20px;
text-align: center;
}

.content_image img {
background-color: #f4f4f4;
border: 1px solid #ccc;
margin: auto;
}

.back_to_top {
background:url('<?php echo BASE_URI; ?>img/shared/back_to_top.png') no-repeat center right; 
padding-right:20px;
}

.external_link {
background:url('<?php echo BASE_URI; ?>img/shared/external_link.png') no-repeat center right; 
padding-right:14px;
}

.box {
clear:both;
position:relative;
display:block;
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

.tipbox {
background:#fffecf url('<?php echo BASE_URI; ?>img/shared/tip.png') no-repeat 10px center;
padding:10px 10px 10px 35px;
border:1px dotted #dda;
}

.greybox, .bluebox, .infobox, .errorbox, .successbox { 
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

.infobox  {  
background:#FFF6BF; 
color:#514721; 
border:1px solid #ffd324; 
} 

.errorbox  {  
background:#ffebe8; 
color: #8a1f11; 
border:1px solid #fbc2c4; 
}  

.successbox { 
background:#E6EFC2; 
color:#264409; 
border:1px solid #c6d880; 
}

.box_right {
float:right;
width:220px;
margin:0 0 0 10px;
padding:10px;
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

.inner{
width:920px;
padding:10px 20px;
margin:0 auto;
}

#topnav {
width:800px;
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
background:url(<?php echo BASE_URI; ?>img/shared/logo.jpg) no-repeat 0 0;
width:180px;
text-indent:-9999px;
}

#logo a:hover{
background-position:-180px 0;
}

.nav_item a {
padding:0 10px;
}

.nav_item a:hover {
background-color:#5c75aa;
padding:0 10px;
}

/* breadcrumb
-------------------------------------------------------------- */
#breadcrumb{
background:#ededed;
font-size:1.2em;
font-weight:700;
color:#264409; 
border-bottom:1px solid #C6D880; 
}

/* content
-------------------------------------------------------------- */
#content {
padding-bottom:10px;
background:#fff;
}

#alpha_bar {
height:10px;
background:#597eaa;
}

/* content onecolumn
-------------------------------------------------------------- */
#onecolumn {
width:920px;
padding:10px 20px 20px;
margin:0 auto;
-moz-box-shadow:0 6px 15px rgba(0, 0, 0, 0.40);
-webkit-box-shadow:0 6px 15px rgba(0, 0, 0, 0.40);
box-shadow:0 6px 15px rgba(0, 0, 0, 0.40);
border:solid 1px #ddd;
border-top:none;
}

#onecolumn ul {
list-style:disc url('<?php echo BASE_URI; ?>img/shared/green_dot.gif');
}

#onecolumn ol {
list-style-type:decimal;
margin-left:20px;
}

#onecolumn li {
margin-left:20px;
padding:3px 0;
}

/* homepage
-------------------------------------------------------------- */
#intro {
width:590px;
float:left;
}

#download {
float:right;
width:280px;
margin:40px 0 10px 20px;
}

#download a {
background: transparent url("<?php echo BASE_URI; ?>img/shared/download_background.png") repeat-x 0 -125px;
color:#4a6d24;
text-shadow: 1px 1px 0 rgba(255, 255, 255, 0.75);
display:block;
border: solid 1px #5a8032;
-moz-box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.35), inset 0px 0px 1px #fafafa;
-webkit-box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.35);
box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.35);
padding: 15px 15px 15px 65px;
position: relative;
}

#download a:hover {
background: transparent url("<?php echo BASE_URI; ?>img/shared/download_background.png") repeat-x 0 -425px;
text-decoration:none;
}

#download_text {
font-weight:700;
display: block;
text-transform: uppercase;
font-size: 32px;
}

#download_version {
display: block;
text-align:center;
}

#download_arrow {
background: transparent url(<?php echo BASE_URI; ?>img/shared/download_arrow.png) no-repeat;
height: 97px;
width: 54px;
position: absolute;
left: 0;
top: -18px;
}

.index_column ul {
padding:5px 0 5px 15px;
}

#index_left {
float:left;
width:599px;
padding:20px 10px;
border-right:1px solid #ddd;
}

#index_left ul{
float:left;
width:280px;
}

ul#yes  {
list-style:disc url('<?php echo BASE_URI; ?>img/shared/yes.png') !important;
}

ul#no  {
list-style:disc url('<?php echo BASE_URI; ?>img/shared/no.png') !important;
}

#index_right {
float:left;
width:270px;
padding:20px 10px;
margin-left:10px;
}

.latest_news {
margin:10px 0 13px 0;
}

.post_date {
color:#999;
font-size:.8em;
}
	
/* footer
-------------------------------------------------------------- */
#footer{
width:960px;
padding:10px 0;
margin:0 auto 10px;
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

.contact_username {
visibility:hidden;
display:none;
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

.facts div {
float:left;
width:418px;
height:100px;
margin:0 10px 20px;
padding:10px;
background:#f7f7f7;  
border:1px solid #ddd;  
color:#333;
}

.word_doc {
background:url(<?php echo BASE_URI; ?>img/shared/word.png) no-repeat; 
padding-left:16px;
}

.pdf_doc {
background:url(<?php echo BASE_URI; ?>img/shared/pdf.png) no-repeat; 
padding-left:16px;
}

.text_doc {
background:url(<?php echo BASE_URI; ?>img/shared/text.png) no-repeat; 
padding-left:16px;
}

/* syntac highlighter*/
.syntax  { 
background: #f8f8f8; 
border:1px solid #ddd;
padding:10px;
margin-bottom:1.5em;
}

.syntax .c { color: #408080; font-style: italic } /* Comment */
.syntax .err { border: 1px solid #FF0000 } /* Error */
.syntax .k { color: #008000; font-weight: bold } /* Keyword */
.syntax .o { color: #666666 } /* Operator */
.syntax .cm { color: #408080; font-style: italic } /* Comment.Multiline */
.syntax .cp { color: #BC7A00 } /* Comment.Preproc */
.syntax .c1 { color: #408080; font-style: italic } /* Comment.Single */
.syntax .cs { color: #408080; font-style: italic } /* Comment.Special */
.syntax .gd { color: #A00000 } /* Generic.Deleted */
.syntax .ge { font-style: italic } /* Generic.Emph */
.syntax .gr { color: #FF0000 } /* Generic.Error */
.syntax .gh { color: #000080; font-weight: bold } /* Generic.Heading */
.syntax .gi { color: #00A000 } /* Generic.Inserted */
.syntax .go { color: #808080 } /* Generic.Output */
.syntax .gp { color: #000080; font-weight: bold } /* Generic.Prompt */
.syntax .gs { font-weight: bold } /* Generic.Strong */
.syntax .gu { color: #800080; font-weight: bold } /* Generic.Subheading */
.syntax .gt { color: #0040D0 } /* Generic.Traceback */
.syntax .kc { color: #008000; font-weight: bold } /* Keyword.Constant */
.syntax .kd { color: #008000; font-weight: bold } /* Keyword.Declaration */
.syntax .kn { color: #008000; font-weight: bold } /* Keyword.Namespace */
.syntax .kp { color: #008000 } /* Keyword.Pseudo */
.syntax .kr { color: #008000; font-weight: bold } /* Keyword.Reserved */
.syntax .kt { color: #B00040 } /* Keyword.Type */
.syntax .m { color: #666666 } /* Literal.Number */
.syntax .s { color: #BA2121 } /* Literal.String */
.syntax .na { color: #7D9029 } /* Name.Attribute */
.syntax .nb { color: #008000 } /* Name.Builtin */
.syntax .nc { color: #0000FF; font-weight: bold } /* Name.Class */
.syntax .no { color: #880000 } /* Name.Constant */
.syntax .nd { color: #AA22FF } /* Name.Decorator */
.syntax .ni { color: #999999; font-weight: bold } /* Name.Entity */
.syntax .ne { color: #D2413A; font-weight: bold } /* Name.Exception */
.syntax .nf { color: #0000FF } /* Name.Function */
.syntax .nl { color: #A0A000 } /* Name.Label */
.syntax .nn { color: #0000FF; font-weight: bold } /* Name.Namespace */
.syntax .nt { color: #008000; font-weight: bold } /* Name.Tag */
.syntax .nv { color: #19177C } /* Name.Variable */
.syntax .ow { color: #AA22FF; font-weight: bold } /* Operator.Word */
.syntax .w { color: #bbbbbb } /* Text.Whitespace */
.syntax .mf { color: #666666 } /* Literal.Number.Float */
.syntax .mh { color: #666666 } /* Literal.Number.Hex */
.syntax .mi { color: #666666 } /* Literal.Number.Integer */
.syntax .mo { color: #666666 } /* Literal.Number.Oct */
.syntax .sb { color: #BA2121 } /* Literal.String.Backtick */
.syntax .sc { color: #BA2121 } /* Literal.String.Char */
.syntax .sd { color: #BA2121; font-style: italic } /* Literal.String.Doc */
.syntax .s2 { color: #BA2121 } /* Literal.String.Double */
.syntax .se { color: #BB6622; font-weight: bold } /* Literal.String.Escape */
.syntax .sh { color: #BA2121 } /* Literal.String.Heredoc */
.syntax .si { color: #BB6688; font-weight: bold } /* Literal.String.Interpol */
.syntax .sx { color: #008000 } /* Literal.String.Other */
.syntax .sr { color: #BB6688 } /* Literal.String.Regex */
.syntax .s1 { color: #BA2121 } /* Literal.String.Single */
.syntax .ss { color: #19177C } /* Literal.String.Symbol */
.syntax .bp { color: #008000 } /* Name.Builtin.Pseudo */
.syntax .vc { color: #19177C } /* Name.Variable.Class */
.syntax .vg { color: #19177C } /* Name.Variable.Global */
.syntax .vi { color: #19177C } /* Name.Variable.Instance */
.syntax .il { color: #666666 } /* Literal.Number.Integer.Long */



.news_item, .tutorials_item {
margin:0 0 20px 0;
}

.news_item h2, .tutorials_item h2 {
padding:0 0 5px 0;
}

.date {
color:#999;
}


