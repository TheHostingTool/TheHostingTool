<script type="text/javascript">
$(document).ready(function() {
    $("#msgcontent").ckeditor();
});
var working = '<div align="center"><img src="<URL>themes/icons/working.gif"></div>';
function sendemail() {
    var subject = document.getElementById("msgsubject").value;
    var msg = $("#msgcontent").val();
    document.getElementById("ajaxemail").innerHTML = working;
    var json = { };
    json["subject"] = subject;
    json["msg"] = msg;
    json[csrfMagicName] = csrfMagicToken;
    $.post("<AJAX>?function=massemail", json, function(mydata) {
        if(mydata == "1") {
            document.getElementById("ajaxemail").innerHTML = "The email has been sent to all your clients!";
        }
        else {
            document.getElementById("ajaxemail").innerHTML = "Houston, we have a problem.<hr>" + (!mydata.error ? mydata : mydata.msg);
        }
    });
    return false;
}
</script>
<div id="ajaxemail">
<form action="" method="post" id="emailme" name="emailme">
  <table width="100%" border="0" cellspacing="3" cellpadding="0">
	  <tr>
	    <td width="30%">Subject:</td>
	    <td><label><input name="msgsubject" id="msgsubject" onkeypress="return checkEnter();" type="text" size="30" /></label></td>
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