function init(){
	
	//substitute title, taken from http://stackoverflow.com/questions/11954931/loading-variables-from-external-file-on-server-into-html-doc
	

	var tags = document.getElementsByClassName('templated');
	for (var i=0; i<tags.length; ++i) {
		applyTemplate(tags[i]);
	}
	
	function applyTemplate (tag) {
		for (var key in title) {
			var regexp = new RegExp('%'+key, 'g');
			tag.innerHTML = tag.innerHTML.replace(regexp, title[key]);
		}
	}
}
