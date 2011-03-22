/*--------------------------------------------------------------
print.css
@author Zhou Yuan
@copyright Institute for Learning 2009 - 2011
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
/* sans-serif fonts are easier to read on the screen, serif fonts are easier to read on paper */
font-family:Arial, Helvetica, serif;
font-size:75%;
color:#222; 
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
}

th {
font-size:1.2em;
text-align:left;
padding:10px;
font-weight:bold;
vertical-align:bottom;
border:2px solid #fff;
background:#3b5998;
color:#fff;
}

tr.odd td{
background:#d4dae8;
}

tr.even td{
background:#eceff6;
}

td {
border:2px solid #fff;
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
font-size:12px;
}

input[type=text], input[type=password], textarea, select {
border:1px solid #ccc;
padding:4px 5px;
}

input[type=checkbox], input[type=radio] { 
position:relative; 
top:0.25em; 
}

select {
border:1px solid #ccc;
padding:3px 5px;
}

input[type=button], input[type=submit] {
cursor:pointer;
background:#3b5998;
color:#fff;
border-color:#d9dfea #0e1f5b #0e1f5b #d9dfea;
border-style:solid;
border-width:1px;
padding:3px 10px;
}

legend {
padding:0 5px;
}

/* global classes
-------------------------------------------------------------- */
.clear {
clear:both;
}

.block {
margin:1.5em 0;
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
color:#B22E11;
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
border:1px solid #ccc;
padding:3px;
}

.content_pic_right {
float:right;
margin:0 0 0 10px;
border:1px solid #ccc;
padding:3px;
}

.back_to_top {
display:none;
}

.box {
clear:both;
position:relative;
display:block;
border:1px solid #ccc;  
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
border-top:1px dotted #ccc;
}

.tipbox {
padding:10px 10px 10px 35px;
border:1px dotted #dda;
}

.greybox, .bluebox, .infobox, .errorbox, .successbox { 
padding:10px; 
margin-bottom:20px;  
}

.greybox {  
background:#f7f7f7;  
border:1px solid #ccc;  
color:#333; 
}   

.bluebox {
background:#eceff6; 
border:1px solid #d4dae8;  
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

/* form related */
.form_item {
clear:both;
margin-bottom:5px;
}

.desc {
line-height:150%;
color:#222;
display:block;
font-size:12px;
}

.req {
color:#F75342;
font-family:verdana,sans-serif;
font-weight:700;
}

/* link button */
a.link_button {
background:#69a74e;
padding:5px 10px;
font-size:1.2em;   
font-weight:700;
border-color:#3b6e22 #3b6e22 #2c5115;
color:#fff;
border-style:solid;
border-width:1px;
}

a.link_button:hover {  
background:#5b74a8;
border:1px solid #ccc;  
} 

/* page layout / structure
-------------------------------------------------------------- */
#container {
width:960px;
margin:0 auto;
background:#fff;
}

#header {
display:none;
}

/* navigation */
#navigation{
display:none;
}

#banner {
display:none;
}

/* breadcrumb
-------------------------------------------------------------- */
#breadcrumb{
font-size:1.2em;
font-weight:700;
padding:10px;  
background: #e6efc2; 
color:#264409; 
border-bottom:1px solid #c6d880; 
}

/* content
-------------------------------------------------------------- */
#content {
padding-bottom:20px;
}

.index_column {
float:left;
height:470px;
display:inline;
position:relative;
padding:20px 10px;
border-right:1px solid #ddd;
}

.index_column h1 {
font-size:1.55em;
}

.section_title {
font-size:1.1em;
font-weight:700;
display:block;
margin-bottom:5px;
}

.index_column ul {
padding:5px 0 5px 15px;
}

.index_column li {
padding:5px 0;
}

#column1 {
width:299px;
}

#column1 ul {
list-style:disc url('<?php echo BASE_URI; ?>img/shared/arrow.gif');
}

#column2 {
width:299px;
}

#column2 ul {
list-style:disc url('<?php echo BASE_URI; ?>img/shared/arrow.gif');
}

#column3 {
width:299px;
border-right:none;
}

.column3_box {
margin-top:11px;
}

#leftcolumn {
display:none;
}

#rightcolumn {
width:940px;
padding:10px;
}

#onecolumn {
width:940px;
padding:10px;
}

#onecolumn ul, #rightcolumn ul {
list-style:disc inside url('<?php echo BASE_URI; ?>img/shared/bullet.gif');
}

#onecolumn ol, #rightcolumn ol {
list-style-type:decimal;
margin-left:20px;
}

#onecolumn li, #rightcolumn li {
padding:3px 0;
}

#footer {
display:none;
}

/* contact
-------------------------------------------------------------- */
#contact {
background:#f7f7f7;  
padding:10px;  
margin-bottom:20px; 
}

.contact_input {
width:189px;
}

.contact_textarea {
width:189px;
height:135px;
}

.contact_username {
visibility:hidden;
display:none;
}

.anti_spam {
visibility: hidden;
height:0;
}

/* search result */
#search_result {
padding:10px;
}

/* events */
table.event td {
border-bottom:none !important;
}

td.td_label {
width:25%;
}

/* Ask the Educator */
.ask_the_educator{
margin:10px 0;
padding:10px 0;
}

.transcript {
border-bottom:1px dotted #ccc;
border-top:1px dotted #ccc;
padding:10px;
background:#fffecf;
margin-bottom:20px;
}

.video_container {
display:none;
}

/* media thumb / overlay */
.media_thumb {
float:left;
margin:0 10px 0 0;
border:1px solid #ccc;
background:#fff;
padding:3px;
}

.media_overlay {
display: block;
padding-left:24px;
}

/* member login */
ul.docs{
list-style-image:none !important;
list-style-type:none !important;
padding:0 !important;
}

ul.docs li{
margin-bottom:12px;
padding-left:40px !important;
line-height:29px;
font-size:1.2em;
font-weight:700;
}

/* Speaker headshot */
.headshot {
float:left;
margin-right:20px;
border:1px solid #ccc;
padding:3px;
}

/* Correcting Orphans w/ Overflow */
.speaker_desc {
overflow: hidden;
}

/* site map 
-------------------------------------------------------------- */
.col {
width:172px;
float:left;
margin-left:20px;
}

.col img {
border:#ccc 1px solid;
background:#f7f7f7;
padding:3px;
display:block;
}

#col_first {
clear:both;
margin-left:0px;
}

.col ul {
list-style:none;
padding-left:3px;
}

.col ul li {
text-align: left;
display: block;
padding: 0.5em 0;
border-bottom: 1px solid #e9e9e9;
}

.col ul li:hover {
background:#f6f6f6;
}

/* Fading Tooltips */
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

/* Online Registration */
.reg_short_input {
width:360px;
}

.reg_long_input {
width:819px;
}

.reg_long_select{
width:829px;
}

.reg_short_select{
width:370px;
}

/* online options signup*/
.price_box {
width: 328px;
height: 370px;
border: 1px solid #999;
float: left;
margin:0 0 20px 0;
padding: 10px;
background-color: #fff9df;
}

#individual {
border-right:0;
}

.price_box h2 {
color:#649005;
border-bottom:1px solid #ccc;
}

.price h3 {
margin:0 0 15px 0;
padding:0 0 7px 0;
font-size:14px;
font-weight:bold;
font-family:Helvetica, Verdana, sans-serif;
color:#73a1c7;
}

.price li strong {
color:#c33700;
}
