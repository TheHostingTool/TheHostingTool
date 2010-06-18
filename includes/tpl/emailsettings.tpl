<script type="text/javascript">
function emailchange(value) {
	if(value == "smtp") {
		$("#smtp").slideDown(500);
	}
	else {
		$("#smtp").slideUp(500);
	}
}
$(window).load(function () {
	if(document.getElementById("emailmethod").value == "smtp") {
		$("#smtp").slideDown(500);	
	}
					   });
</script>
<ERRORS>
<form id="settings" name="settings" method="post" action="">
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr>
    <td width="20%">Email Method:</td>
    <td>
    <select name="emailmethod" id="emailmethod" onchange="emailchange(this.value)">%METHOD%</select>
    <a title="The email method that you are going to use." class="tooltip"><img src="<ICONDIR>information.png" /></a>
    </td>
  </tr>
  <tr>
        <td width="20%">Email From:</td>
        <td><input name="emailfrom" id="emailfrom" type="text" value="%EMAILFROM%" />
        <a title="Email from who?" class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
      </tr>
</table>
<table width="100%" border="0" cellspacing="2" cellpadding="0" id="smtp" style="display:none;">
      <tr>
        <td>SMTP Host:</td>
        <td align="right">
        <input name="smtp_host" id="smtp_host" type="text" value="%SMTP_HOST%" />
        <a title="Your SMTP Host." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
      </tr>
       <tr>
        <td>SMTP Username:</td>
        <td align="right">
        <input name="smtp_user" id="smtp_user" type="text" value="%SMTP_USER%" />
        <a title="Your SMTP username." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
      </tr>
      <tr>
        <td>SMTP Password:</td>
        <td align="right">
        <input name="smtp_password" id="smtp_password" type="password" value="%SMTP_PASS%" />
        <a title="Your SMTP Password." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
      </tr>
    </table>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
      <tr>
    <td colspan="2" align="center"><input type="submit" name="add" id="add" value="Edit Email Settings" /></td>
  </tr>
</table>
</form>
