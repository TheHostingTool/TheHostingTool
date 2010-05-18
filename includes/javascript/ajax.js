function ajaxSlide(id, url){
	$.get(url, function(data) {
		if(document.getElementById(id).style.display == 'none') {
			document.getElementById(id).innerHTML = data;	
			$("#"+id).slideDown(500);
		}
		else {
			$("#"+id).slideUp(500, function() {
					document.getElementById(id).innerHTML = data;	
					$("#"+id).slideDown(500);
											  });
		}
						});
}

function ajax(id, url){
	$.get(url, function(data) {
		document.getElementById(id).innerHTML = data;
						});
}
