<ERRORS>
<form id="addbillincycle" name="addbillincycle" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="20%">Name:</td>
    <td>
      <input name="name" type="text" id="name" /><a title="The User-Friendly version of the package name. Type whatever you want to show to the users." class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  
  <tr>
    <td valign="top">Number of months</td>
    <td>
	%NUMBER_MONTHS%    
    <a title="The type of your package. You can choose between <em>free</em>, <em>post2host</em> and <em>paid</em>." class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  
    <tr>
    <td valign="top">Active</td>
    <td>
	 %STATUS%	
    </td>
  </tr>
  
  
  <!--
    <tr>
    <td width="20%">Language variable</td>
    <td>
      <input name="name" type="text" id="langname" /><a title="The User-Friendly version of the package name. Type whatever you want to show to the users." class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  -->
  
</table>
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td colspan="2" id="customform"></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td colspan="2" align="center"><input type="submit" name="add" id="add" value="Add billing cycle" /></td>
  </tr>
</table>
</form>