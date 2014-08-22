<script type="text/javascript">
$(document).ready(function() {
    $("#emailcontent").ckeditor();
});
function templates(id) {
	$.get("<AJAX>?function=template&id="+id, function(data) {
			var result = data.split("{}[]{}");
			if(document.getElementById("templatebit").style.display == "none") {
				document.getElementById("subject").value = result[0];
				document.getElementById("description").innerHTML = result[1];
                $("#emailcontent").val(result[2]);
				$("#templatebit").slideDown(500);	
			}
			else {
				$("#templatebit").slideUp(500, function(data) {
					document.getElementById("subject").value = result[0];
					document.getElementById("description").innerHTML = result[1];
                    $("#emailcontent").val(result[2]);
					$("#templatebit").slideDown(500);
                });
			}
    });
}
</script>
<ERRORS>
<form action="" method="post" name="edit" id="edit">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="20%">Select a Template:</td>
    <td><select name="template" id="template" onchange="templates(this.value)"><option value="" disabled="disabled" selected="selected">Select a template</option>%TEMPLATES%</select>
    <a title="Which template are you going to edit?" class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
  </tr>
</table>
<div id="templatebit" style="display:none;">
	<br />
    <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td valign="top">Subject:</td>
        <td><input type="text" name="subject" id="subject" />
        <a title="The subject of the email." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
      </tr>
      <tr>
        <td width="20%" valign="top" id="description"></td>
        <td><textarea name="content" id="emailcontent" cols="" rows=""></textarea></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input name="edit" id="edit" type="submit" value="Edit Template" /></td>
      </tr>
    </table>
    </form>
</div>
