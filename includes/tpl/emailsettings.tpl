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
<div id="smtp" style="display:none;">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="20%">SMTP Host:</td>
        <td>
        <input name="smtp_host" id="smtp_host" type="text" value="%SMTP_HOST%" />
        <a title="Your SMTP Host." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
      </tr>
      <tr>
        <td width="20%">SMTP Port:</td>
        <td>
        <input name="smtp_port" id="smtp_port" type="text" value="%SMTP_PORT%" />
        <a title="The port number that your SMTP server listens on." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
      </tr>
      <tr>
        <td width="20%">SMTP Secure Connections:</td>
        <td>
        <input name="smtp_secure" id="smtp_secure" type="checkbox" value="1" %SMTP_SECURE%" />
        <a title="Check this if your SMTP server accepts secure SSL/TLS connections." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
      </tr>
       <tr>
        <td width="20%">SMTP Username:</td>
        <td>
        <input name="smtp_user" id="smtp_user" type="text" value="%SMTP_USER%" />
        <a title="Your SMTP username." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
      </tr>
      <tr>
        <td width="20%">SMTP Password:</td>
        <td>
        <input name="smtp_password" id="smtp_password" type="password" value="%SMTP_PASS%" />
        <a title="Your SMTP Password." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
      </tr>
    </table>
</div>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
      <tr>
    <td colspan="2" align="center"><input type="submit" name="add" id="add" value="Edit Email Settings" /></td>
  </tr>
</table>
</form>
