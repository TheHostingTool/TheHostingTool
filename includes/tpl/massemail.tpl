<script type="text/javascript" src="<URL>includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
	mode : "textareas",
	skin : "o2k7",
	theme : "advanced"
	});
</script>
<script type="text/javascript">
var working = '<div align="center"><img src="<URL>themes/icons/working.gif"></div>';
function sendemail() {
		tinyMCE.triggerSave(true,true);
		var subject = document.getElementById("msgsubject").value;
		var msg = tinyMCE.get('msgcontent').getContent();
		document.getElementById("ajaxemail").innerHTML = working;
		$.get("<AJAX>?function=massemail&subject="+subject+"&msg="+msg, function(mydata) {
	if(mydata == "1") {
		document.getElementById("ajaxemail").innerHTML = "The email has been sent to all your clients!";
	}
	if(mydata == "0") {
		document.getElementById("ajaxemail").innerHTML = "There was a problem sending this email!";
	}});	
}
</script>
<div id="ajaxemail">
<form action="" method="post" id="emailme" name="emailme">
  <table width="100%" border="0" cellspacing="3" cellpadding="0">
	  <tr>
	    <td width="30%">Subject:</td>
	    <td><label><input name="msgsubject" id="msgsubject" type="text" size="30" /></label></td>
    </tr>
	  <tr>
	    <td valign="top">Content:</td>
	    <td><label>
	      <textarea name="msgcontent" id="msgcontent" cols="45" rows="5"></textarea>
        </label></td>
    </tr>
	  <tr>
	    <td colspan="2" align="center"><label>
	      <input type="button" name="goform" id="goform" value="Send Email" onclick="sendemail()" />
        </label></td>
      </tr>
  </table>
 </form>
</div>