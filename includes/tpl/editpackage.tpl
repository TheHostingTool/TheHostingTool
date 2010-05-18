<script type="text/javascript" src="<URL>includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
	mode : "textareas",
	skin : "o2k7",
	theme : "simple"
	});
</script>
<ERRORS>
<form id="addpackage" name="addpackage" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="20%">Name:</td>
    <td>
      <input name="name" type="text" id="name" value="%NAME%" /><a title="The package name" class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td width="20%">Backend:</td>
    <td>
      <input name="backend" type="text" id="backend" value="%BACKEND%" /><a title="The backend package name, shown in WHM." class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td valign="top">Description:</td>
    <td><textarea name="description" id="description" cols="45" rows="5">%DESCRIPTION%</textarea></td>
  </tr>
  <tr>
    <td valign="top">Server:</td>
    <td>
    %SERVER% <a title="The Server where the package is located at." class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td valign="top">Admin Validation:</td>
    <td>
      <input name="admin" type="checkbox" id="admin" value="1" %CHECKED% /> <a title="Does this package require admin validation?" class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
    <tr>
    <td valign="top">Reseller:</td>
    <td>
      <input name="reseller" type="checkbox" id="reseller" value="1" %CHECKED2% /> <a title="Is this package a reseller?" class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td colspan="2" id="customform">%FORM%</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td colspan="2" align="center"><input type="submit" name="add" id="add" value="Edit Package" /></td>
  </tr>
</table>
</form>
