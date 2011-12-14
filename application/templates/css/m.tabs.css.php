/*--------------------------------------------------------------
m.tabs.css
@author Zhou Yuan
-------------------------------------------------------------- */
.tabs {
position: relative;
background: #ebeff9;
margin: 0 0 20px;
border-bottom: 1px solid #c9d5df;
height:50px;
-webkit-border-radius: 2px 2px 0 0;
-moz-border-radius: 2px 2px 0 0;
border-radius: 2px 2px 0 0;
}

.tabs ul {
list-style: none;
padding: 0;
overflow: hidden;
border: 1px solid #c9d5df;
border-bottom: none;
float: left;
margin: 0;
padding:0;
-webkit-border-radius: 9px 9px 0 0;
-moz-border-radius: 9px 9px 0 0;
border-radius: 9px 9px 0 0;
position: absolute;
bottom: 0;
left: 10px;
}

.tabs ul.selected {
bottom: -1px;
}

.tabs li {
float: left;
padding:0;
margin:0;
}

.tabs li a {
display: block;
background: #ebf1f6;
width: 116px;
text-align: center;
padding: 12px 0px 10px 0px;
color: #7b97b0;
font-size: 14px;
border-left: 1px solid #c9d5df;
-webkit-box-shadow: inset 0 1px 0px #fff, inset 1px 0 0 #fff;
-moz-box-shadow: inset 0 1px 0px #fff, inset 1px 0 0 #fff;
box-shadow: inset 0 1px 0px #fff, inset 1px 0 0 #fff;
font-weight: 500;
text-shadow: 0 1px 0 #fff;
-webkit-border-radius: 0px 0 0 0;
-moz-border-radius: 0px 0 0 0;
border-radius: 0;
text-decoration: none;
}

.tabs li a:hover {
background: #f2f6f9;
}

.tabs li.selected a {
background: #fff;
padding: 13px 0 10px;
color: #3d6a92;
}

.tabs li:first-child a {
border-left: none;
-webkit-border-top-left-radius: 9px 9px;
-moz-border-radius-topleft: 9px 9px;
border-top-left-radius: 9px 9px;
}

.tabs li:last-child a {
-webkit-border-top-right-radius: 9px 9px;
-moz-border-radius-topright: 9px 9px;
border-top-right-radius: 9px 9px;
}
