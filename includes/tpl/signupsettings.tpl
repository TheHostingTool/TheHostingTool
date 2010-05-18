<ERRORS>
<script type="text/javascript" src="<URL>includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
	mode : "textareas",
	skin : "o2k7",
	theme : "simple"
	});
</script>
<form id="settings" name="settings" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="20%">Multiple Signups:</td>
    <td>
      %MULTIPLE%    <a title="Do you allow multiple signups for one user?" class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td width="20%">TLD's only:</td>
    <td>
      %TLDONLY%    <a title="Allow ONLY top level domains? Leave as Disabled if unsure." class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td width="20%">General Signups:</td>
    <td>
      %GENERAL%    <a title="Is the signup system offline?" class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
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
