/*http://kntl.org/*/

.pager { 
background:#eee repeat left top; 
height:36px; 
margin:20px 0; 
padding:0 5px; 
}

.pager a.pre { 
display:block; 
float:left; 
background:#5B74A8; 
width:80px; 
height:22px; 
margin:4px 0 0 0; 
padding:6px 0 0 0; 
font-size:14px; 
text-align:center; 
color:#fff; 
}

.pager a.next { 
display:block; 
float:right; 
background:#5B74A8; 
width:80px; 
height:22px;
margin:4px 0 0 0; 
padding:6px 0 0 0; 
font-size:14px; 
text-align:center; 
color:#fff; 
}

.pager a.pre span.bullet { 
float:left; 
margin:1px 0 0 0; 
border:solid 7px #000; 
border-color:#5B74A8 #fff #5B74A8 #5B74A8; 
}

.pager a.next span.bullet { 
float:right; 
margin:1px 0 0 0; 
border:solid 7px #000; 
border-color:#5B74A8 #5B74A8 #5B74A8 #fff; 
}

.pager a.pre:hover,
.pager a.next:hover { 
background:#ff0000; 
color:#fff; 
}

.pager a.pre:hover span.bullet { 
border-color:#ff0000 #fff #ff0000 #ff0000; 
}

.pager a.next:hover span.bullet { 
border-color:#ff0000 #ff0000 #ff0000 #fff; 
}

.pager a.pre:hover,
.pager a.next:hover { 
background:#ff0000; 
color:#fff; 
}

.pager a.pre:hover span.bullet { 
border-color:#ff0000 #fff #ff0000 #ff0000; 
}

.pager a.next:hover span.bullet { 
border-color:#ff0000 #ff0000 #ff0000 #fff; 
}

.pager ul.pagedots { 
width:200px; 
height:36px; 
margin:0 auto; 
list-style:none !important;
}

.pager ul.pagedots li { 
float:left; 
margin:0 !important; 
padding:0 !important; 
}

.pager ul.pagedots li.dot a { 
position:relative; 
overflow:visible; 
display:block; 
width:25px; 
height:36px; 
text-align:center; 
text-decoration:none !important; 
}

.pager ul.pagedots li.dot a span.bullet { 
display:block; 
font-size:30px; 
line-height:normal !important; 
color:#000; 
}

.pager ul.pagedots li.dot a span.tipbullet { 
position:absolute; 
display:block; 
visibility:hidden; 
left:8px; 
top:2px; 
border-style:solid; 
border-width:5px; 
border-color:#5B74A8 #ebebeb #ebebeb #ebebeb; 
}

.pager ul.pagedots li.dot a span.tiplabel { 
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

.pager ul.pagedots li.dot a:hover span.bullet { 
color:#69A74E; 
}

.pager ul.pagedots li.dot a:hover span.tipbullet { 
visibility:visible; 
}

.pager ul.pagedots li.dot a:hover span.tiplabel { 
visibility:visible; 
}

.pager ul.pagedots li.activedot a { 
position:relative; 
overflow:visible; 
display:block; 
width:25px; 
height:35px; 
text-align:center; 
text-decoration:none !important; 
}

.pager ul.pagedots li.activedot a span.bullet { 
display:block; 
font-size:30px; 
line-height:normal !important; 
color:#ff0000; 
}

.pager ul.pagedots li.activedot a span.tipbullet { 
position:absolute; 
display:block; 
left:8px; 
top:22px; 
border-style:solid; 
border-width:5px; 
border-color:#ebebeb #ebebeb #ff0000 #ebebeb; 
}

.pager ul.pagedots li.activedot a span.tiplabel { 
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

