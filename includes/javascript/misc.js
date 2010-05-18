function onlyNumbers(evt)
{
    var e = event || evt; // for trans-browser compatibility
    var charCode = e.which || e.keyCode;

    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;

}

function tblshowhide(id, imgid, url) {
	if(document.getElementById(id).style.display == "none") {
		$("#"+id).slideDown(500, function() {
			document.getElementById(imgid).src = url+"themes/icons/bullet_toggle_minus.png";
										});
	}
	else {
		$("#"+id).slideUp(500, function() {
			document.getElementById(imgid).src = url+"themes/icons/bullet_toggle_plus.png";
										});
	}
}