function ajaxSlide(id, url, callback) {
	if(callback == undefined || callback == null) {
		callback = function() {};
	}
	$.get(url, function(data) {
		if(document.getElementById(id).style.display == 'none') {
			document.getElementById(id).innerHTML = data;	
			$("#"+id).slideDown(500, callback);
		}
		else {
			$("#"+id).slideUp(500, function() {
				document.getElementById(id).innerHTML = data;	
				$("#"+id).slideDown(500, callback);
			});
		}

	});
}

function ajax(id, url, callback) {
	if(callback == undefined || callback == null) {
		callback = function() {};
	}
	$.get(url, function(data) {
		document.getElementById(id).innerHTML = data;
		callback();
	});
}
