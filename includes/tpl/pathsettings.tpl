<ERRORS>
<form id="settings" name="settings" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="20%">Site Name:</td>
    <td>
      <input name="name" type="text" id="name" value="%NAME%" />
      <a title="Your THT Website's Name." class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td width="20%">URL: (Including trailing slash)</td>
    <td>
      <input name="url" type="text" id="host" value="%URL%" />
      <a title="Your THT Website's URL. (Recommended: http://%RECURL%/)" class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td>Default Page:</td>
    <td>%DROPDOWN%    <a title="The Default page shown when accessing the root directory." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="add" id="add" value="Edit Settings" /></td>
  </tr>
</table>
</form>
