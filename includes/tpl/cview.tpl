<script type="text/javascript">
function check() {
	if(document.getElementById("change").checked == true) {
		$("#changepass").slideDown(500);
	}
	else {
		$("#changepass").slideUp(500);
	}
}
</script>
<ERRORS>
<form id="edit" name="edit" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="20%"><strong>Username:</strong></td>
    <td>%USER%</td>
  </tr>
  <tr>
    <td width="20%"><strong>Date Ordered:</strong></td>
    <td>%SIGNUP%</td>
  </tr>
  <tr>
    <td width="20%"><strong>Domain:</strong></td>
    <td>%DOMAIN%</td>
  </tr>
  <tr>
    <td width="20%"><strong>Package:</strong></td>
    <td>%PACKAGE%</td>
  </tr>
  <tr>
    <td colspan="2">
    <div class="subborder">
        <div class="sub">
          %DESCRIPTION%
        </div>
    </div>
    </td>
  </tr>
  <tr>
    <td colspan="2"><strong>Change cPanel Password:</strong> <input name="change" type="checkbox" id="change" value="1" onchange="check()" /></td>
  </tr>
  <tr>
  <td colspan="2">
    <div class="subborder" id="changepass" style="display:none;"><div class="sub"><table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr>
  <td width="20%">Account Password:</td>
    <td><input type="password" name="currentpass" id="currentpass" />
     <a title="Type your current account password here." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
  </tr>
  <tr>
  <td>New cPanel Password:</td>
    <td><input type="password" name="newpass" id="newpass" />
     <a title="Type your new cPanel password here." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
  </tr>
  <tr>
  <td>Confirm cPanel Password:</td>
    <td><input type="password" name="cpass" id="cpass" />
     <a title="Confirm your new cPanel password here." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
  </tr>
</table>
</div></div>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="edit2" id="edit2" value="Edit Details" /></td>
    </tr>
</table>
</form>