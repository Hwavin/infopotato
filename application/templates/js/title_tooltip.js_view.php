// Grab Elements from the DOM by className
function getElementsByClass(searchClass, node, tag) {
	var classElements = new Array();
	if ( node == null ) {
		node = document;
	}
	if ( tag == null ) {
		tag = '*';
	}
	var els = node.getElementsByTagName(tag);
	var elsLen = els.length;
	var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
	for (i = 0, j = 0; i < elsLen; i++) {
		if ( pattern.test(els[i].className) ) {
			classElements[j] = els[i];
			j++;
		}
	}
	return classElements;
}

// The element css class is tooltip, convert title attribute to tips
// E.g., <span class="tooltip" title="bala bala">tooltip here</span>
window.onload = function() {
	var elements = getElementsByClass('tooltip');
	var n = elements.length;
	for (var i = 0; i < n; i++) {
		if (elements[i].className == 'tooltip' && elements[i].title && elements[i].title != '') {
			// add the title to anchor innerhtml
			elements[i].innerHTML += '<span>'+elements[i].title+'</span>'; 
			elements[i].title = ''; // remove the title
		}
	}
}