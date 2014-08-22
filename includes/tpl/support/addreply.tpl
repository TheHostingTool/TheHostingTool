<script type="text/javascript">
$(document).ready(function() {
    $("#addcontent").ckeditor();
});
</script>
<div class="subborder">
	<div class="sub">
        <form action="" method="post">
            <table width="100%" border="0" cellspacing="2" cellpadding="0">
            <tr>
                <td width="25%">Reply Title:</td>
                <td><input name="title" type="text" id="addtitle" value="%TITLE%" size="40" /></td>
              </tr>
              <tr>
                <td colspan="2"><textarea name="content" id="addcontent" cols="" rows=""></textarea></td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input type="submit" name="reply" id="reply" value="Add Reply" /></td>
              </tr>
            </table>
        </form>
    </div>
</div>