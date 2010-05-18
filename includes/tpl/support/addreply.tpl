<script type="text/javascript" src="<URL>includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
	mode : "textareas",
	skin : "o2k7",
	theme : "simple",
	width : "100%"
	});
</script>
<div class="subborder">
	<div class="sub">
        <form action="" method="post">
            <table width="100%" border="0" cellspacing="2" cellpadding="0">
            <tr>
                <td width="25%">Reply Title:</td>
                <td><input name="title" type="text" id="title" value="%TITLE%" size="40" /></td>
              </tr>
              <tr>
                <td colspan="2"><textarea name="content" id="content" cols="" rows=""></textarea></td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input type="submit" name="reply" id="reply" value="Add Reply" /></td>
              </tr>
            </table>
        </form>
    </div>
</div>