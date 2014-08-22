<script type="text/javascript">
$(document).ready(function() {
    $("#emailcontent").ckeditor();
});
</script>
<form action="" method="post" name="email" id="email">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td valign="top">Subject:</td>
    <td><input type="text" name="subject" id="subject" />
    <a title="The subject of the email." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
  </tr>
  <tr>
    <td width="20%" valign="top" id="description">Message:</td>
    <td><textarea name="content" id="emailcontent" cols="" rows=""></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input name="edit" id="edit" type="submit" value="Send Email" /></td>
  </tr>
</table>
</form>