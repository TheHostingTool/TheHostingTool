<script type="text/javascript">
function addme() {
	$("#addbox").slideToggle(500);	
}
</script>
<script type="text/javascript">
function editme(id) {
	$.get("<AJAX>?function=%AJAX%&id="+id, function(data) {
			var result = data.split("{}[]{}");
			if(document.getElementById("editbox").style.display == "none") {
				document.getElementById("editname").value = result[0];
				tinyMCE.get("editdescription").execCommand('mceSetContent',false, result[1] );
				$("#editbox").slideDown(500);	
			}
			else {
				$("#editbox").slideUp(500, function(data) {
					document.getElementById("editname").value = result[0];
					tinyMCE.get("editdescription").execCommand('mceSetContent',false, result[1] );
					$("#editbox").slideDown(500);
														});		
			}
			document.getElementById("id").value = id;
															});
}
</script>
<script type="text/javascript" src="<URL>includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
	mode : "textareas",
	skin : "o2k7",
	theme : "simple",
	width : "75%"
	});
</script>
<ERRORS>
<div class="subborder">
	<div class="sub">
   	  <table width="100%" border="0" cellspacing="2" cellpadding="0">
    	  <tr>
    	    <td width="1%"><img src="<ICONDIR>add.png"></td>
    	    <td><a href="Javascript:addme()">Add %NAME%</a></td>
  	    </tr>
  	  </table>
	</div>
</div>
<form action="" method="post" name="add%NAME%">
    <div class="subborder" id="addbox" style="display:none;">
        <div class="sub">
          <table width="100%" border="0" cellspacing="2" cellpadding="0">
            <tr>
                <td colspan="2"><strong>Add %NAME%</strong></td>
            </tr>
            %CATID%
            <tr>
                <td width="20%">%SUB%:</td>
                <td><input name="name" type="text" id="name" size="40" /></td>
            </tr>
            <tr>
                <td width="20%" valign="top">%SUB2%:</td>
                <td><textarea name="description" id="description" cols="" rows=""></textarea></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input name="add" id="add" type="submit" value="Add %NAME%" /></td>
            </tr>
          </table>
        </div>
    </div>
</form>
<form action="" method="post" name="edit%NAME%">
    <div class="subborder" id="editbox" style="display:none;">
        <div class="sub">
          <table width="100%" border="0" cellspacing="2" cellpadding="0">
            <tr>
                <td colspan="2"><strong>Edit %NAME%</strong></td>
            </tr>
            <tr>
                <td width="20%">%SUB%:</td>
                <td><input name="editname" type="text" id="editname" size="40" /></td>
            </tr>
            <tr>
                <td width="20%" valign="top">%SUB2%:</td>
                <td><textarea name="editdescription" id="editdescription" cols="" rows=""></textarea></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input name="edit" id="edit" type="submit" value="Edit %NAME%" /><input name="id" id="id" type="hidden" /></td>
            </tr>
          </table>
        </div>
    </div>
</form>
%BOXES%