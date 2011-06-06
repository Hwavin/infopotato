/*http://kntl.org/*/

.pager { 
background:#eee; 
width:908px;
height:36px; 
margin:20px 0; 
padding:0 5px; border:1px solid #ccc;
}

.pager .prev, 
.pager .next {
display:block;
width:80px; 
height:22px; 
background:#5B74A8;
margin:4px 0 0 0; 
padding:6px 0 0 0; 
text-align:center; 
color:#fff; 
}

.pager .prev{  
float:left; 
}

.pager .next{ 
float:right; 
}

.pager .prev span.bullet { 
float:left; 
margin:1px 0 0 0; 
border:solid 7px #000; 
border-color:#5B74A8 #fff #5B74A8 #5B74A8; 
}

.pager .next span.bullet { 
float:right; 
margin:1px 0 0 0; 
border:solid 7px #000; 
border-color:#5B74A8 #5B74A8 #5B74A8 #fff; 
}

.pager .prev:hover,
.pager .next:hover { 
background:#69A74E; 
color:#fff; 
}

.pager .prev:hover span.bullet { 
border-color:#69A74E #fff #69A74E #69A74E; 
}

.pager .next:hover span.bullet { 
border-color:#69A74E #69A74E #69A74E #fff; 
}

.pager .pagedots { 
width:500px; 
height:36px; 
margin:0 auto !important; 
padding:0 !important; 
list-style:none !important; 
}

.pager .pagedots li { 
float:left; 
margin:0 !important; 
padding:0 !important; 
}

.pager .pagedots li.dot a { 
position:relative; 
overflow:visible; 
display:block; 
width:25px; 
height:36px; 
text-align:center; 
text-decoration:none !important; 
}

.pager .pagedots li.dot a span.bullet { 
display:block; 
font-size:30px; 
line-height:normal !important; 
color:#000; 
}

.pager .pagedots li.dot a span.tipbullet { 
position:absolute; 
display:block; 
visibility:hidden; 
left:8px; 
top:2px; 
border-style:solid; 
border-width:5px; 
border-color:#5B74A8 #ebebeb #ebebeb #ebebeb; 
}

.pager .pagedots li.dot a span.tiplabel { 
position:absolute; 
display:block; 
visibility:hidden; 
background:#5B74A8; 
left:-43px; 
bottom:33px; 
width:110px; 
padding:2px; 
color:#fff; 
text-align:center; 
font-size:11px; 
line-height:normal !important; 
}

.pager .pagedots li.dot a:hover span.bullet { 
color:#69A74E; 
}

.pager .pagedots li.dot a:hover span.tipbullet { 
visibility:visible; 
}

.pager .pagedots li.dot a:hover span.tiplabel { 
visibility:visible; 
}

.pager .pagedots li.activedot a { 
position:relative; 
overflow:visible; 
display:block; 
width:25px; 
height:35px; 
text-align:center; 
text-decoration:none !important; 
}

.pager .pagedots li.activedot a span.bullet { 
display:block; 
font-size:30px; 
line-height:normal !important; 
color:#ff0000; 
}

.pager .pagedots li.activedot a span.tipbullet { 
position:absolute; 
display:block; 
left:8px; 
top:22px; 
border-style:solid; 
border-width:5px; 
border-color:#ebebeb #ebebeb #ff0000 #ebebeb; 
}

.pager .pagedots li.activedot a span.tiplabel { 
position:absolute; 
display:block; 
background:#ff0000; 
left:-43px; 
top:32px; 
width:110px;
padding:2px; 
color:#fff; 
text-align:center; 
font-size:11px; 
line-height:normal !important; 
}
