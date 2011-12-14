/*--------------------------------------------------------------
Reset
Props to Eric Meyer (meyerweb.com) for his CSS reset file. 
We're using an adapted version here that cuts out some of the 
reset HTML elements we will never need here (i.e., dfn, samp, etc).
-------------------------------------------------------------- */
html, body {
margin: 0;
padding: 0;
}
h1,
h2,
h3,
h4,
h5,
h6,
p,
blockquote,
pre,
a,
abbr,
acronym,
address,
cite,
code,
del,
dfn,
em,
img,
q,
s,
samp,
small,
strike,
strong,
sub,
sup,
tt,
var,
dd,
dl,
dt,
li,
ol,
ul,
fieldset,
form,
label,
legend,
button,
table,
caption,
tbody,
tfoot,
thead,
tr,
th,
td {
margin: 0;
padding: 0;
border: 0;
font-weight: normal;
font-style: normal;
font-size: 100%;
line-height: 1;
font-family: inherit;
}

table {
border-collapse: collapse;
border-spacing: 0;
}

ol, ul {
list-style: none;
}

q:before,
q:after,
blockquote:before,
blockquote:after {
content: "";
}

html {
overflow-y: scroll;
font-size: 100%;
-webkit-text-size-adjust: 100%;
-ms-text-size-adjust: 100%;
}

a:focus {
outline: thin dotted;
}

a:hover, a:active {
outline: 0;
}

article,
aside,
details,
figcaption,
figure,
footer,
header,
hgroup,
nav,
section {
display: block;
}

audio, canvas, video {
display: inline-block;
*display: inline;
*zoom: 1;
}

audio:not([controls]) {
display: none;
}

sub, sup {
font-size: 75%;
line-height: 0;
position: relative;
vertical-align: baseline;
}

sup {
top: -0.5em;
}

sub {
bottom: -0.25em;
}

img {
border: 0;
-ms-interpolation-mode: bicubic;
max-width: 100%;
height: auto;
}

button,
input,
select,
textarea {
font-size: 100%;
margin: 0;
vertical-align: baseline;
*vertical-align: middle;
}

button, input {
line-height: normal;
*overflow: visible;
}

button::-moz-focus-inner, input::-moz-focus-inner {
border: 0;
padding: 0;
}

button,
input[type="button"],
input[type="reset"],
input[type="submit"] {
cursor: pointer;
-webkit-appearance: button;
}

input[type="search"] {
-webkit-appearance: textfield;
-webkit-box-sizing: content-box;
-moz-box-sizing: content-box;
box-sizing: content-box;
}

input[type="search"]::-webkit-search-decoration {
-webkit-appearance: none;
}

textarea {
overflow: auto;
vertical-align: top;
}

/*--------------------------------------------------------------
main
-------------------------------------------------------------- */
body {
-webkit-user-select:none;
webkit-text-size-adjust:none;
font-family:Arial, Helvetica, sans-serif;
-webkit-perspective:800;
color:#404040;
background:#f2f2f2;
word-wrap: break-word;
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
font-weight:700;
}

h1 { 
font-size:20px; 
padding:5px 0;
letter-spacing:-0.02em;
line-height:130%;
}

h2 { 
font-size:18px;
padding:5px 0;
letter-spacing:-0.02em;
}

h3 { 
font-size:14px;  
padding:5px 0;
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
line-height:160%;
margin:0 0 1.5em 0;
font-size:16px;
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
font-family:"lucida grande", tahoma, verdana, arial, sans-serif;
}

input[type=text], input[type=password], textarea, select {
border:1px solid #ddd;
padding:3px 5px 3px 5px;
}

input[type=checkbox], input[type=radio] { 
position:relative; 
top:0.25em; 
}

input, textarea {
-webkit-transition: border linear 0.2s, box-shadow linear 0.2s;
-moz-transition: border linear 0.2s, box-shadow linear 0.2s;
-ms-transition: border linear 0.2s, box-shadow linear 0.2s;
-o-transition: border linear 0.2s, box-shadow linear 0.2s;
transition: border linear 0.2s, box-shadow linear 0.2s;
-webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
-moz-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

input:focus, textarea:focus {
outline: 0;
border-color: rgba(82, 168, 236, 0.8);
-webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 8px rgba(82, 168, 236, 0.6);
-moz-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 8px rgba(82, 168, 236, 0.6);
box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 8px rgba(82, 168, 236, 0.6);
}

input[type=file]:focus, input[type=checkbox]:focus, select:focus {
-webkit-box-shadow: none;
-moz-box-shadow: none;
box-shadow: none;
outline: 1px dotted #666;
}

:-moz-placeholder {
color: #bfbfbf;
}

::-webkit-input-placeholder {
color: #bfbfbf;
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
display:none;
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
width:100%;
background:#ccc;
}

.topnav {
margin:0 auto;
}

.logo{
clear:both;
background:#3b5998;
padding:5px 6px;
}

ul.nav {
display:block;
text-align:center;
}

ul.nav li{
display:table-cell;
float:left;
line-height:29px;
}

ul.nav li a {
display:block;
font-weight:700;
font-size:15px;
color:#3b5998;
text-decoration:none;
padding:10px;
}

ul.nav li a:hover {
background:#6D84B4;
}

ul.nav li#nav_contact {
display:none;
}


/* content
-------------------------------------------------------------- */
.content {
background:#fff;
padding:10px 6px;
margin:0 auto;
border-top:0;
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
display:none;
}

/* footer
-------------------------------------------------------------- */
.footer{
width:100%;
}

.footer .copyright{
padding:10px 6px 20px;
margin:0 auto;
line-height:180%;
}

/* contact form
-------------------------------------------------------------- */
#contact {  
margin-bottom:20px; 
}

.contact_input {

}

.contact_textarea {
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
margin:0 0 10px 0;
}

span.big_number {
float: left;
width: 24px;
line-height: 24px;
display: block;
height: 30px;
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


.fact{
padding:10px 0;
}

.fact_img{
width:52px;
height:52px;
float:left;
margin:5px 10px 0 3px;
}

.fact_desc{
overflow: hidden;
font-size:0.9em;
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
display:none;
}

.highlighted_code {
overflow-x: auto; 
white-space: nowrap;
border: 1px solid #ccc;

font-family:Menlo,'Courier New',Courier,monospace;
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

ul.code_file{
list-style:none !important;
}

ul.code_file li{
background:#f7f7f7;  
border:1px solid #ddd;  
color:#333; 
margin:5px 0;
padding:10px;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
-o-border-radius: 4px;
-webkit-transition: background-color .2s ease-in-out;
-moz-transition: background-color .2s ease-in-out;
-o-transition: background-color .2s ease-in-out;
}

ul.code_file li a{
width: 100%;
display: block;
text-decoration: none;
padding:10px 0;
}

ul.credits_links{
list-style:none !important;
margin:0;
}

ul.credits_links li{
background:#f7f7f7;  
border:1px solid #ddd;  
color:#333; 
margin:5px 0;
padding:10px;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
-o-border-radius: 4px;
-webkit-transition: background-color .2s ease-in-out;
-moz-transition: background-color .2s ease-in-out;
-o-transition: background-color .2s ease-in-out;
}

ul.credits_links li a{
width: 100%;
display: block;
text-decoration: none;
padding:10px 0;
}

.doc_category{
background:#f7f7f7;  
border:1px solid #ddd;  
color:#333; 
margin:10px 0;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
-o-border-radius: 4px;
-webkit-transition: background-color .2s ease-in-out;
-moz-transition: background-color .2s ease-in-out;
-o-transition: background-color .2s ease-in-out;
}

.doc_category a {
width: 100%;
display: block;
text-align: center;
text-decoration: none;
padding:10px 0;
}
  
.mobile_hide{
display:none;
}

.quote_item{
margin-bottom:20px;
}

blockquote.quote{
padding:10px;
color:#333;
margin:20px 0 15px 0;


position: relative;
font-family: chaparral-pro-1, chaparral-pro-2, Georgia, Times, serif;
line-height: 24px;
border: 1px solid #798eae;
background: -moz-linear-gradient(#e2ecf9, #d8e5f7);
background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#e2ecf9), to(#d8e5f7));
background: -webkit-linear-gradient(#e2ecf9, #d8e5f7);
background: -o-linear-gradient(#e2ecf9, #d8e5f7);
-moz-border-radius: 4px;
-webkit-border-radius: 4px;
border-radius: 4px;
-moz-box-shadow: inset rgba(255, 255, 255, 0.9) 0 1px 0, rgba(0, 38, 97, 0.4) 0 1px 3px;
-webkit-box-shadow: inset rgba(255, 255, 255, 0.9) 0 1px 0, rgba(0, 38, 97, 0.4) 0 1px 3px;
box-shadow: inset rgba(255, 255, 255, 0.9) 0 1px 0, rgba(0, 38, 97, 0.4) 0 1px 3px;
}

.quote_content{
padding:10px 10px 10px 21px;
position:relative;
}

.curly_quote_open{
background:url("<?php echo STATIC_URI_BASE; ?>images/shared/quote_open.png") no-repeat scroll 0 0 transparent;
display:inline-block;
height:13px;
left:0;
position:absolute;
top:14px;
width:14px;
}

.quote_text{

}

.curly_quote_close{
background:url("<?php echo STATIC_URI_BASE; ?>images/shared/quote_close.png") no-repeat scroll 0 0 transparent;
display:inline-block;
height:13px;
position:relative;
width:14px;
margin-left:3px;
}

.arrow {
display: block;
width: 12px;
height: 8px;
background: url("<?php echo STATIC_URI_BASE; ?>images/shared/arrow.png") no-repeat;
position: absolute;
bottom:-8px;
left:35px;
}

.quote_author{
display:inline-block;
margin-left:23px;
}

.quote_author_photo {
float:left;
margin-right:10px;
}

.round_corner_img{
-moz-border-radius: 4px;
-webkit-border-radius: 4px;
border-radius: 4px;
-moz-box-shadow: white 0 1px 0;
-webkit-box-shadow: white 0 1px 0;
box-shadow: white 0 1px 0;
}

.quote_author_name{
display:inline-block;
padding-top:12px;
}
