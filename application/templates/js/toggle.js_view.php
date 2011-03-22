// http://bonrouge.com/br.php?page=togglit

function getElementsByIdTagAndClassName(id, tag, cname) {
	var tags = document.getElementById(id).getElementsByTagName(tag);
	var cEls = new Array();
	for (i = 0; i<tags.length; i++) {
		var rE = new RegExp("(^|\s)" + cname + "(\s|$)");
		if (rE.test(tags[i].className)) {
			cEls.push(tags[i]);
		}
	}
	return cEls;
}

// Refer CSS classes
var ccn = "clicker";
var clcn = "closed";
var opcn = "open";
 
function toggleNextPlusMinus(el) {
	var next = el.nextSibling;
	el.className = el.className.replace(new RegExp(opcn+"\\b"), "");
	el.className = el.className.replace(new RegExp(clcn+"\\b"), "");
	while (next.nodeType != 1) next = next.nextSibling;
	next.style.display = ((next.style.display == "none") ? "block" : "none");
	el.className += ((next.style.display == "block")? " "+opcn : " "+clcn);
}

function toggleNextByIdTagAndClassName(id, tag, cname) {
	clickers = getElementsByIdTagAndClassName(id, tag, cname);
	for (i = 0; i<clickers.length; i++) {
		clickers[i].className += " "+ccn;
		clickers[i].className += " "+clcn;
		clickers[i].onclick = function() {toggleNextPlusMinus(this)}
		toggleNextPlusMinus(clickers[i]);
	}
}