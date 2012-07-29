<ERRORS>
<form id="settings" name="settings" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="20%">Site Name:</td>
    <td>
      <input name="name" type="text" id="name" value="%NAME%" />
    </td>
    <td width="45%"><a title="Your THT Website's Name." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
    <td>URL: (Including trailing slash)</td>
    <td>
      <input name="url" type="text" id="host" value="%URL%" />
    </td>
    <td><a title="This should be the primary URL your THT installation can be accessed at." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
    <td>WWW Subdomain:</td>
    <td>
      %WWWDROPDOWN%
    </td>
    <td><a title="<b>Force WWW:</b> Redirect requests without www. to www. <br><b>Force No WWW</b>: Redirect requests using www. to no www.<br><b>Both:</b> Do not redirect any requests." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
    <td>Default Page:</td>
    <td>%DROPDOWN%</td>
    <td><a title="The Default page shown when accessing the root directory." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
      <td>Timezone:</td>
      <td>%TZDROPDOWN%</td>
      <td><a title="Select the timezone you wish your THT installation to function in." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
      <td>Current Date & Time:</td>
      <td>%DATETIME%</td>
      <td><a title="This is the current date & time on your server in your selected timezone. Presented in RFC 2822 format." class="tooltip"><img src="<URL>themes/icons/information.png"/></a></td>
  </tr>
  <tr>
    <td></td>
    <td><input type="submit" name="add" id="add" value="Edit Settings" /></td>
  </tr>
</table>
</form>
