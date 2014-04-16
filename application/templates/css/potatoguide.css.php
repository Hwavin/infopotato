/*!
 * =====================================================
 * PotatoGuide (http://potatoguide.com)
 * Simple front-end web style/pattern guide for consistent design
 *
 * Copyright 2014 Zhou Yuan
 * Licensed under MIT
 * =====================================================

/*! normalize.css v3.0.0 | MIT License | git.io/normalize */

/**
 * 1. Set default font family to sans-serif.
 * 2. Prevent iOS text size adjust after orientation change, without disabling
 *    user zoom.
 */

html {
  font-family: sans-serif; /* 1 */
  -ms-text-size-adjust: 100%; /* 2 */
  -webkit-text-size-adjust: 100%; /* 2 */
}

/**
 * Remove default margin.
 */

body {
  margin: 0;
}

/* HTML5 display definitions
   ========================================================================== */

/**
 * Correct `block` display not defined in IE 8/9.
 */

article,
aside,
details,
figcaption,
figure,
footer,
header,
hgroup,
main,
nav,
section,
summary {
  display: block;
}

/**
 * 1. Correct `inline-block` display not defined in IE 8/9.
 * 2. Normalize vertical alignment of `progress` in Chrome, Firefox, and Opera.
 */

audio,
canvas,
progress,
video {
  display: inline-block; /* 1 */
  vertical-align: baseline; /* 2 */
}

/**
 * Prevent modern browsers from displaying `audio` without controls.
 * Remove excess height in iOS 5 devices.
 */

audio:not([controls]) {
  display: none;
  height: 0;
}

/**
 * Address `[hidden]` styling not present in IE 8/9.
 * Hide the `template` element in IE, Safari, and Firefox < 22.
 */

[hidden],
template {
  display: none;
}

/* Links
   ========================================================================== */

/**
 * Remove the gray background color from active links in IE 10.
 */

a {
  background: transparent;
}

/**
 * Improve readability when focused and also mouse hovered in all browsers.
 */

a:active,
a:hover {
  outline: 0;
}

/* Text-level semantics
   ========================================================================== */

/**
 * Address styling not present in IE 8/9, Safari 5, and Chrome.
 */

abbr[title] {
  border-bottom: 1px dotted;
}

/**
 * Address style set to `bolder` in Firefox 4+, Safari 5, and Chrome.
 */

b,
strong {
  font-weight: bold;
}

/**
 * Address styling not present in Safari 5 and Chrome.
 */

dfn {
  font-style: italic;
}

/**
 * Address variable `h1` font-size and margin within `section` and `article`
 * contexts in Firefox 4+, Safari 5, and Chrome.
 */

h1 {
  font-size: 2em;
  margin: 0.67em 0;
}

/**
 * Address styling not present in IE 8/9.
 */

mark {
  background: #ff0;
  color: #000;
}

/**
 * Address inconsistent and variable font size in all browsers.
 */

small {
  font-size: 80%;
}

/**
 * Prevent `sub` and `sup` affecting `line-height` in all browsers.
 */

sub,
sup {
  font-size: 75%;
  line-height: 0; /* http://www.slideshare.net/slideshow/embed_code/1689979 */
  position: relative;
  vertical-align: baseline;
}

sup {
  top: -0.5em;
}

sub {
  bottom: -0.25em;
}

/* Embedded content
   ========================================================================== */

/**
 * Remove border when inside `a` element in IE 8/9.
 */

img {
  border: 0;
}

/**
 * Correct overflow displayed oddly in IE 9.
 */

svg:not(:root) {
  overflow: hidden;
}

/* Grouping content
   ========================================================================== */

/**
 * Address margin not present in IE 8/9 and Safari 5.
 */

figure {
  margin: 1em 40px;
}

/**
 * Address differences between Firefox and other browsers.
 */

hr {
  -moz-box-sizing: content-box;
  box-sizing: content-box;
  height: 0;
}

/**
 * Contain overflow in all browsers.
 */

pre {
  overflow: auto;
}

/**
 * Address odd `em`-unit font size rendering in all browsers.
 */

code,
kbd,
pre,
samp {
  font-family: monospace, monospace;
  font-size: 1em;
}

/* Forms
   ========================================================================== */

/**
 * Known limitation: by default, Chrome and Safari on OS X allow very limited
 * styling of `select`, unless a `border` property is set.
 */

/**
 * 1. Correct color not being inherited.
 *    Known issue: affects color of disabled elements.
 * 2. Correct font properties not being inherited.
 * 3. Address margins set differently in Firefox 4+, Safari 5, and Chrome.
 */

button,
input,
optgroup,
select,
textarea {
  color: inherit; /* 1 */
  font: inherit; /* 2 */
  margin: 0; /* 3 */
}

/**
 * Address `overflow` set to `hidden` in IE 8/9/10.
 */

button {
  overflow: visible;
}

/**
 * Address inconsistent `text-transform` inheritance for `button` and `select`.
 * All other form control elements do not inherit `text-transform` values.
 * Correct `button` style inheritance in Firefox, IE 8+, and Opera
 * Correct `select` style inheritance in Firefox.
 */

button,
select {
  text-transform: none;
}

/**
 * 1. Avoid the WebKit bug in Android 4.0.* where (2) destroys native `audio`
 *    and `video` controls.
 * 2. Correct inability to style clickable `input` types in iOS.
 * 3. Improve usability and consistency of cursor style between image-type
 *    `input` and others.
 */

button,
html input[type="button"], /* 1 */
input[type="reset"],
input[type="submit"] {
  -webkit-appearance: button; /* 2 */
  cursor: pointer; /* 3 */
}

/**
 * Re-set default cursor for disabled elements.
 */

button[disabled],
html input[disabled] {
  cursor: default;
}

/**
 * Remove inner padding and border in Firefox 4+.
 */

button::-moz-focus-inner,
input::-moz-focus-inner {
  border: 0;
  padding: 0;
}

/**
 * Address Firefox 4+ setting `line-height` on `input` using `!important` in
 * the UA stylesheet.
 */

input {
  line-height: normal;
}

/**
 * It's recommended that you don't attempt to style these elements.
 * Firefox's implementation doesn't respect box-sizing, padding, or width.
 *
 * 1. Address box sizing set to `content-box` in IE 8/9/10.
 * 2. Remove excess padding in IE 8/9/10.
 */

input[type="checkbox"],
input[type="radio"] {
  box-sizing: border-box; /* 1 */
  padding: 0; /* 2 */
}

/**
 * Fix the cursor style for Chrome's increment/decrement buttons. For certain
 * `font-size` values of the `input`, it causes the cursor style of the
 * decrement button to change from `default` to `text`.
 */

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  height: auto;
}

/**
 * 1. Address `appearance` set to `searchfield` in Safari 5 and Chrome.
 * 2. Address `box-sizing` set to `border-box` in Safari 5 and Chrome
 *    (include `-moz` to future-proof).
 */

input[type="search"] {
  -webkit-appearance: textfield; /* 1 */
  -moz-box-sizing: content-box;
  -webkit-box-sizing: content-box; /* 2 */
  box-sizing: content-box;
}

/**
 * Remove inner padding and search cancel button in Safari and Chrome on OS X.
 * Safari (but not Chrome) clips the cancel button when the search input has
 * padding (and `textfield` appearance).
 */

input[type="search"]::-webkit-search-cancel-button,
input[type="search"]::-webkit-search-decoration {
  -webkit-appearance: none;
}

/**
 * Define consistent border, margin, and padding.
 */

fieldset {
  border: 1px solid #c0c0c0;
  margin: 0 2px;
  padding: 0.35em 0.625em 0.75em;
}

/**
 * 1. Correct `color` not being inherited in IE 8/9.
 * 2. Remove padding so people aren't caught out if they zero out fieldsets.
 */

legend {
  border: 0; /* 1 */
  padding: 0; /* 2 */
}

/**
 * Remove default vertical scrollbar in IE 8/9.
 */

textarea {
  overflow: auto;
}

/**
 * Don't inherit the `font-weight` (applied by a rule above).
 * NOTE: the default cannot safely be changed in Chrome and Safari on OS X.
 */

optgroup {
  font-weight: bold;
}

/* Tables
   ========================================================================== */

/**
 * Remove most spacing between table cells.
 */

table {
  border-collapse: collapse;
  border-spacing: 0;
}

td,
th {
  padding: 0;
}



/* ==========================================================================
   PotatoGuide | MIT License | http://potatoguide.com/
   ========================================================================== */

/*
 * Remove text-shadow in selection highlight: h5bp.com/i
 * These selection rule sets have to be separate.
 * Customize the background color to match your design.
 */
::-moz-selection {
background: #b3d4fc;
text-shadow: none;
}

::selection {
background: #b3d4fc;
text-shadow: none;
}

/*
 * Remove the gap between images, videos, audio and canvas and the bottom of
 * their containers: h5bp.com/i/440
 */
audio,
canvas,
img,
svg,
video {
vertical-align: middle;
}

/*
 * Remove default fieldset styles.
 */
fieldset {
    border: 0;
    margin: 0;
    padding: 0;
}

/*
 * Allow only vertical resizing of textareas.
 */
textarea {
    resize: vertical;
}

/* Hyperlinks */
a:link, a:visited {
color:#3b5998;
}

/* a:hover MUST come after a:link and a:visited in the CSS definition in order to be effective! */
a:hover {
color:#f75342;
}

/* selected link */
/* a:active MUST come after a:hover in the CSS definition in order to be effective! */
a:active {
color:#f75342;
}

p {
line-height:1.6;
}

h1,
h2,
h3,
h4,
h5,
h6{
line-height: 1.1;
}

/* Unordered Lists and Ordered Lists */
/* http://www.w3.org/wiki/Styling_lists_and_links */
.ul, .ol {
margin-left: 0;
padding-left: 0;
}

.ul li {
margin-left:1.8em;
line-height:1.6;
}

.ol li {
margin-left:2em;
line-height:1.6;
}

/* Description Lists */
.dl {
margin:20px 0;
}

.dl dt{
font-weight: 700;
}

.dl dd {
margin-bottom: 10px;
margin-left: 0;
line-height:1.6;
}

/* 12 or 16 Column Grid Layout  */
.inner {
width:940px;
margin:0 auto;
padding:0;
}

.row12, .row16 {
margin-left: -20px;
width: 960px; /* Total width 940px  */
}

.row12:before, .row12:after, .row16:before, .row16:after {
display: table;
content: "";
}

.row12:after, .row16:after {
clear: both;
}

/* 12 Column Grid */
.row12 .span1,
.row12 .span2,
.row12 .span3,
.row12 .span4,
.row12 .span5,
.row12 .span6,
.row12 .span7,
.row12 .span8,
.row12 .span9,
.row12 .span10,
.row12 .span11,
.row12 .span12 {
float: left;
margin-left: 20px; /*Gutter 20px */
}

/* 12 Column Grid */
.row12 .span1 {
width: 60px;
}

.row12 .span2 {
width: 140px;
}

.row12 .span3 {
width: 220px;
}

.row12 .span4 {
width: 300px;
}

.row12 .span5 {
width: 380px;
}

.row12 .span6 {
width: 460px;
}

.row12 .span7 {
width: 540px;
}

.row12 .span8 {
width: 620px;
}

.row12 .span9 {
width: 700px;
}

.row12 .span10 {
width: 780px;
}

.row12 .span11 {
width: 860px;
}

.row12 .span12 {
width: 940px;
}

/* 16 Column Grid */
.row16 .span1,
.row16 .span2,
.row16 .span3,
.row16 .span4,
.row16 .span5,
.row16 .span6,
.row16 .span7,
.row16 .span8,
.row16 .span9,
.row16 .span10,
.row16 .span11,
.row16 .span12,
.row16 .span13,
.row16 .span14,
.row16 .span15,
.row16 .span16 {
float: left;
margin-left: 20px; /*Gutter 20px */
}

/* 16 Column Grid */
.row16 .span1 {
width: 40px;
}

.row16 .span2 {
width: 100px;
}

.row16 .span3 {
width: 160px;
}

.row16 .span4 {
width: 220px;
}

.row16 .span5 {
width: 280px;
}

.row16 .span6 {
width: 340px;
}

.row16 .span7 {
width: 400px;
}

.row16 .span8 {
width: 460px;
}

.row16 .span9 {
width: 520px;
}

.row16 .span10 {
width: 580px;
}

.row16 .span11 {
width: 640px;
}

.row16 .span12 {
width: 700px;
}

.row16 .span13 {
width: 760px;
}

.row16 .span14 {
width: 820px;
}

.row16 .span15 {
width: 880px;
}

.row16 .span16 {
width: 940px;
}

/* Text Colors */
.red_tx{
color:#e27b67 !important;
}

.orange_tx{
color:#ff8d5b !important;
}

.yellow_tx{
color:#fbdca0 !important;
}

.green_tx{
color:#7bcfbb !important;
}

.grey_tx{
color:#d6d6d6 !important;
}

.blue_tx{
color:#82ACDE !important;
}

.purple_tx{
color:#8763A3 !important;
}

/* Background Colors */
.red_bg{
background-color:#e27b67 !important;
}

.orange_bg{
background-color:#ff8d5b !important;
}

.yellow_bg{
background-color:#fbdca0 !important;
}

.green_bg{
background-color:#7AC4A0 !important;
}

.grey_bg{
background-color:#d6d6d6 !important;
}

.blue_bg{
background-color:#82ACDE !important;
}

.purple_bg{
background-color:#AE87C4 !important;
}

/* Misc CSS Helper Classes */
.quote {
font-family: "Georgia", serif;
font-size: 17.5px;
color: #666;
border-left: 5px solid #859ce6;
padding-left:20px;
margin:20px;
line-height:1.6;
}

.quote footer {
display: block;
font-size: .85em;
color: #999;
}

.quote footer:before {
content: '\2014 \00A0';
}

/* clearfix hack http://nicolasgallagher.com/micro-clearfix-hack/ */
.clearfix:before,
.clearfix:after {
content: " "; /* 1 */
display: table; /* 2 */
}

.clearfix:after {
clear: both;
}

.inline_code {
padding: 2px 4px;
font-size: 90%;
color: #DC4945;
white-space: nowrap;
background-color: #f9f2f4;
border-radius: 4px;
}

/* The <acronym> tag is not supported in HTML5. Use the <abbr> tag instead. */
.abbr { 
border-bottom:1px dotted #ff0000; 
cursor:help;
}

/* For the <address> tag. */
.addr {
margin:10px 0 20px;
font-style: normal;
line-height: 1.428571429;
}

.hide { 
display: none; 
}

.float_left {
float:left;
}

.float_right {
float:right;
}

.align_left {
text-align:left;
}

.align_right {
text-align:right;
}

.align_center {
text-align:center;
}

.line_through{
text-decoration:line-through;
}

.external_link {
background-image:url('<?php echo STATIC_URI_BASE; ?>images/external_link.png') !important; 
background-repeat:no-repeat !important;
background-position: center right !important; 
padding-right:12px !important;
}

/* Card With Border & Padding */
.card { 
padding:20px; 
border:1px solid #ccc; 
}

/* Dot Leaders List */
.dot_leaders {
list-style-type:none !important;
padding-left:0 !important;
margin-left:0 !important;
overflow-x:hidden;
}

.dot_leaders li{
line-height:1.6;
padding-left:0 !important;
margin-left:0 !important;
}

.dot_leaders li:before{
float:left;
width:0;
white-space:nowrap;
color:#d9d9d9;
content:"................................................................................................................................................................................................................................................................................................................................";
font-size:10px;
line-height:inherit;
}

.dot_leaders_left{
background-color:#fff;
padding-right:12px;
}

.dot_leaders_right{
background-color:#fff;
float:right;
padding-left:12px;
}


/* Table */

.table {
width:100%;
border:1px solid #ccc;
margin:20px 0;
}

.table th {
line-height:1.6;
padding:15px 10px; 
background:#ebeff9;
border:1px solid #ccc;
/* The text in th are bold and centered by default. */
}

.table td {
line-height:1.6;
padding:10px; 
border:1px solid #ccc;
}

.table_hover>tbody>tr:hover>td{
background-color:#fefbd6;
}


/* Basic Form Styling */

.form input[type="text"],
.form input[type="password"],
.form input[type="date"],
.form input[type="datetime"],
.form input[type="datetime-local"],
.form input[type="month"],
.form input[type="week"],
.form input[type="email"],
.form input[type="number"],
.form input[type="search"],
.form input[type="tel"],
.form input[type="time"],
.form input[type="url"],
.form textarea {
-webkit-appearance: none;
background-color: #fff;
font-family: inherit;
border: 1px solid #ccc;
color: #000 !important;
display: block;
font-size: 0.875em;
height: 2.3125em;
margin: 0 0 0.88889em 0;
padding: 0.44444em;
width: 100%;
-moz-box-sizing: border-box;
-webkit-box-sizing: border-box;
box-sizing: border-box;
transition: border linear 0.2s, box-shadow linear 0.2s;
/* Remove the border-radius in Safari */
-webkit-border-radius: 0;
border-radius: 0;
}

.form textarea{
height:auto; /* Override the height specified above */
}

.form input[type="text"]:focus,
.form input[type="password"]:focus,
.form input[type="date"]:focus,
.form input[type="datetime"]:focus,
.form input[type="datetime-local"]:focus,
.form input[type="month"]:focus,
.form input[type="week"]:focus,
.form input[type="email"]:focus,
.form input[type="number"]:focus,
.form input[type="search"]:focus,
.form input[type="tel"]:focus,
.form input[type="time"]:focus,
.form input[type="url"]:focus,
.form textarea:focus {
border-color:#52A8EC;
border-color: rgba(82, 168, 236, 0.8);
background:#fff9d7; 
outline: 0;
-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(82,168,236,.6);
box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(82,168,236,.6);
}

.form input[type="text"][disabled],
.form input[type="password"][disabled],
.form input[type="date"][disabled],
.form input[type="datetime"][disabled],
.form input[type="datetime-local"][disabled],
.form input[type="month"][disabled],
.form input[type="week"][disabled],
.form input[type="email"][disabled],
.form input[type="number"][disabled],
.form input[type="search"][disabled],
.form input[type="tel"][disabled],
.form input[type="time"][disabled],
.form input[type="url"][disabled],
.form textarea[disabled] {
background-color: #ddd; 
}

/* Add height value for select elements to match text input height */
.form select {
padding: 0.5em;
font-size: 0.875em;
width: 100%;
height: 2.3125em;
border: 1px solid #ccc;
/* Remove the border-radius in Safari */
-webkit-border-radius: 0;
border-radius: 0;
}

/* Fix the Firefox showing the dropdown arrow on left corner */
@-moz-document url-prefix() {
    .form select {
    background: #fff; 
    }
}

/* Adjust margin for form elements below */
.form input[type="file"],
.form input[type="checkbox"],
.form input[type="radio"],
.form select {
margin: 0 0 1em 0; 
}

/* Remove the border-radius except radio button in Safari */
.form input[type="file"],
.form input[type="checkbox"],
.form select {
-webkit-border-radius: 0;
border-radius: 0;
}

.form input[type="checkbox"] + label,
.form input[type="radio"] + label {
display: inline-block;
margin-left: 0.5em;
margin-right: 1em;
margin-bottom: 0;
vertical-align: baseline; 
}

/* Normalize file input width */
.form input[type="file"] {
width: 100%; 
}

/* We add basic fieldset styling */
.form fieldset {
border: solid 1px #ddd;
padding: 1.25em;
margin: 1.125em 0; 
}

.form fieldset legend {
font-weight: bold;
background: white;
padding: 0 0.1875em;
margin: 0;
margin-left: -0.1875em; 
}

/* Buttons - Form Buttons, Regular Buttons, and Link Buttons */

.form input[type="button"],
.form input[type="submit"],
.form input[type="reset"],
.form button,
.button {
display:inline-block;
text-decoration: none;
color: #000 !important;
font-family: inherit;
border: 1px solid #ccc;
border-bottom-color: #b3b3b3;
line-height: 1;
padding: 0.44444em;
font-weight: 500;
background-color: #f1f1f1;
background-image: -moz-linear-gradient(top, #fcfcfc, #e0e0e0);
background-image: -ms-linear-gradient(top, #fcfcfc, #e0e0e0);
background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fcfcfc), to(#e0e0e0));
background-image: -webkit-linear-gradient(top, #fcfcfc, #e0e0e0);
background-image: -o-linear-gradient(top, #fcfcfc, #e0e0e0);
background-image: linear-gradient(top, #fcfcfc, #e0e0e0);
text-shadow: 0 1px 0 #fff;
box-shadow: none;
/* Remove the border-radius in Safari */
-webkit-border-radius: 0;
border-radius: 0;
}

.form input[type="button"]:hover,
.form input[type="submit"]:hover,
.form input[type="reset"]:hover,
.form button:hover,
.button:hover {
color: #000;
background: #e0e0e0;
}

/* ==========================================================================
   Print styles.
   Inlined to avoid required HTTP connection: h5bp.com/r
   ========================================================================== */

@media print {
    * {
        background: transparent !important;
        color: #000 !important; /* Black prints faster: h5bp.com/s */
        box-shadow: none !important;
        text-shadow: none !important;
    }

    a,
    a:visited {
        text-decoration: underline;
    }
    
    /* Disabled by PotatoGuide
    a[href]:after {
        content: " (" attr(href) ")";
    }

    abbr[title]:after {
        content: " (" attr(title) ")";
    }
    */
    
    /*
     * Don't show links for images, or javascript/internal links
     */

    a[href^="javascript:"]:after,
    a[href^="#"]:after {
        content: "";
    }

    pre,
    blockquote {
        border: 1px solid #999;
        page-break-inside: avoid;
    }

    thead {
        display: table-header-group; /* h5bp.com/t */
    }

    tr,
    img {
        page-break-inside: avoid;
    }

    img {
        max-width: 100% !important;
    }

    p,
    h2,
    h3 {
        orphans: 3;
        widows: 3;
    }

    h2,
    h3 {
        page-break-after: avoid;
    }
    
    /* PotatoGuide */
    .print_hide {
        display: none !important; 
    }

    .print_show {
        display: inherit !important; 
    }

}