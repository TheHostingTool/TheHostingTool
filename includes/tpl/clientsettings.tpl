<ERRORS>
<script type="text/javascript">
$(document).ready(function() {
    $("#cmessagetxt").ckeditor();
    $("#calertstxt").ckeditor();
});
</script>
<form id="settings" name="settings" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="20%">Cancel Account:</td>
    <td>
      %DELACC%    <a title="Do you allow your clients to cancel their own account?" class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td width="20%">Client Area:</td>
    <td>
      %CENABLED% <a title="Is the Client area online?" class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td valign="top">Signups Closed Message:</td>
    <td><textarea name="cmessage" id="cmessagetxt" cols="" rows="">%CMESSAGE%</textarea></td>
  </tr>
  <tr>
    <td valign="top">Client Area Announcements:</td>
    <td><textarea name="alerts" id="calertstxt" cols="" rows="">%ALERTS%</textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="add" id="add" value="Edit Settings" /></td>
  </tr>
</table>
</form>
