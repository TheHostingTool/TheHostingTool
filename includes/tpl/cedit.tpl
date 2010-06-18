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
%DISP%
<form id="edit" name="edit" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="20%">Username:</td>
    <td>
      <input type="text" name="username" id="username" value="%USER%" readonly="readonly" />
      <a title="The username you registered with." class="tooltip"><img src="<ICONDIR>information.png" /></a>
    </td>
  </tr>
  <tr>
    <td width="20%">Email:</td>
    <td>
      <input type="text" name="email" id="email" value="%EMAIL%" />
      <a title="Please type your email address here." class="tooltip"><img src="<ICONDIR>information.png" /></a>
    </td>
  </tr>
    <tr>
    <td width="20%">First Name:</td>
    <td>
      <input type="text" name="firstname" id="firstname" value="%FIRSTNAME%" readonly="readonly" />
      <a title="Your first name." class="tooltip"><img src="<ICONDIR>information.png" /></a>
    </td>
  </tr>
    <tr>
    <td width="20%">Last Name:</td>
    <td>
      <input type="text" name="lastname" id="lastname" value="%LASTNAME%" readonly="readonly"/>
      <a title="Your last name." class="tooltip"><img src="<ICONDIR>information.png" /></a>
    </td>
  </tr>
  <tr>
    <td width="20%">Address:</td>
    <td>
      <input type="text" name="address" id="address" value="%ADDRESS%"/>
      <a title="Your address." class="tooltip"><img src="<ICONDIR>information.png" /></a>
    </td>
  </tr>
  <tr>
    <td width="20%">City:</td>
    <td>
      <input type="text" name="city" id="city" value="%CITY%"/>
      <a title="Your city/province." class="tooltip"><img src="<ICONDIR>information.png" /></a>
    </td>
  </tr>
  <tr>
    <td width="20%">State:</td>
    <td>
      <input type="text" name="state" id="state" value="%STATE%"/>
      <a title="Your state/territory." class="tooltip"><img src="<ICONDIR>information.png" /></a>
    </td>
  </tr>
  <tr>
    <td width="20%">Zip Code:</td>
    <td>
      <input type="text" name="zip" id="zip" value="%ZIP%"/>
      <a title="Your zip/postal code." class="tooltip"><img src="<ICONDIR>information.png" /></a>
    </td>
  </tr>
  <tr>
    <td width="50%">Country:</td>
    <td>
		<img src="<URL>themes/flags/%COUNTRY%.gif" />
	</td>
  </tr>
  <tr>
    <td width="20%">Phone Number:</td>
    <td>
      <input type="text" name="phone" id="phone" value="%PHONE%"/>
      <a title="Your phone number." class="tooltip"><img src="<ICONDIR>information.png" /></a>
    </td>
  </tr>
  <tr>
    <td>Change Password: 
    </td>
    <td><input name="change" type="checkbox" id="change" value="1" onchange="check()" /></td>
  </tr>
  <tr>
  	<td colspan="2">
    <div class="subborder" id="changepass" style="display:none;"><div class="sub"><table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td width="20%">Current Password:</td>
    <td><input type="password" name="currentpass" id="currentpass" />
     <a title="Type your current password here." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
  </tr>
  <tr>
    <td>New Password:</td>
    <td><input type="password" name="newpass" id="newpass" />
     <a title="Type your new password here." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
  </tr>
  <tr>
    <td>Confirm Password:</td>
    <td><input type="password" name="cpass" id="cpass" />
     <a title="Confirm your new password here." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
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
</div>