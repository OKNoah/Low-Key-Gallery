window.onload = function () {
	var thumbs = document.getElementsByClassName('lk-thumbnail')
	var key = document.getElementById('lk-bigscreen')

	Array.prototype.forEach.call(thumbs, function(each) {
		addListener(each, swap)
	} )

	function swap(newKey){
		key.src = newKey.replace("-150x150", "")
	}

	function addListener(each, callback) {
		each.addEventListener('click', function() {
			imgSrc = each.src
			callback(imgSrc)
		}, false);
	}
};





