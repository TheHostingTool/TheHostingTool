<ERRORS>
<script type="text/javascript" src="<URL>includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
	mode : "textareas",
	skin : "o2k7",
	theme : "advanced"
	});
</script>
<form id="settings" name="settings" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="20%">Multiple Signups:</td>
    <td width="1%">
      %MULTIPLE%
    </td>
    <td><a title="Do you allow multiple signups for one user?" class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
    <tr>
        <td valign="top">Welcome Message:</td>
        <td><textarea name="welcomemsg" id="welcomemsg" cols="" rows="">%WELCOMEMSG%</textarea></td>
    </tr>
  <tr>
    <td width="20%">Email Validation:</td>
    <td>
      <input type="hidden" name="emailval" value="0" />
      <input type="checkbox" value="1" name="emailval" %EMAILVAL% />
    </td>
    <td><a title="Should we ask the user to confirm their email?" class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
    <tr>
        <td width="20%">Sliding Next Step Animation:</td>
        <td>
            <input type="hidden" name="useNewOrderSlideEffect" value="0" />
            <input type="checkbox" value="1" name="useNewOrderSlideEffect" %NEWEFFECT% />
        </td>
        <td><a title="Use a sliding animation for the next step procedure on the order form. It's cool, but can be disorienting and may not look great on all browsers." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
    </tr>
  <tr>
    <td width="20%">TLD's only:</td>
    <td>
      %TLDONLY%
    </td>
    <td><a title="Allow ONLY top level domains? Leave as Disabled if unsure." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
    <td width="20%">General Signups:</td>
    <td>
      %GENERAL%
    </td>
    <td><a title="Is the signup system offline?" class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
    <td valign="top">Signups Closed Message:</td>
    <td><textarea name="message" id="message" cols="" rows="">%MESSAGE%</textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="add" id="add" value="Edit Settings" /></td>
  </tr>
</table>
</form>
