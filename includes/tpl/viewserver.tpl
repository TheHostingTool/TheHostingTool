<script type="text/javascript">
function serverchange(value) {
	$.get("<AJAX>?function=editserverhash&server=%ID%&type="+value, function(data) {
		$("#passtext").slideUp(500);			
		$("#passbox").slideUp(500, function(lol2) {
			var result = data.split(";:;");
			if(result[0] == "1") {
				$("#passbox").html('<input name="hash" type="text" id="hash" value="'+result[1]+'" />');
			}
			else {
				$("#passbox").html('<textarea name="hash" id="hash" cols="45" rows="5">'+result[1]+'</textarea>');
			}
			$("#passtext").slideDown(500);		
			$("#passbox").slideDown(500, function(lol) {
				if(result[0] == "1") {
					$("#passtext").html('Password:');
				}
				else {
					$("#passtext").html('Access Hash:');
				}
												  });
		});
	});
}
$(window).load(function () {
	serverchange(document.getElementById('type').value);
});
</script>
<ERRORS>
<form id="addserver" name="addserver" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="20%">Name:</td>
    <td>
      <input name="name" type="text" id="name" value="%NAME%" />
      <a title="The server's user-friendly name." class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td width="20%">Host:</td>
    <td>
      <input name="host" type="text" id="host" value="%HOST%" />
      <a title="The Server's Hostname. Must be a FQDN!" class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td>Username:</td>
    <td><input type="text" name="user" id="user" value="%USER%" />
    <a title="The username to access the server." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
    <td valign="top" width="20%"><span id="passtext"></span></td>
    <td><span id="passbox"></span></td>
  </tr>  
  <tr>
    <td valign="top">Type:</td>
    <td><select name="type" id="type" onchange="serverchange(this.value)">%TYPE%</select> <a title="The control panel that this server is running." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="add" id="add" value="Edit Server" /></td>
  </tr>
</table>
</form>
<table width="100%" border="0" cellspacing="2" cellpadding="0">
<tr>
<td align="center"><strong>HTTP</strong></td>
<td align="center"><strong>FTP</strong></td>
<td align="center"><strong>MySQL</strong></td>
<td align="center"><strong>POP3</strong></td>
<td align="center"><strong>SSH</strong></td>
</tr>
<tr>
<td align="center"> <img src="../includes/status.php?link=%HOST%:80"></td>
<td align="center"> <img src="../includes/status.php?link=%HOST%:21"></td>
<td align="center"> <img src="../includes/status.php?link=%HOST%:3306"></td>
<td align="center"> <img src="../includes/status.php?link=%HOST%:110"></td>
<td align="center"> <img src="../includes/status.php?link=%HOST%:22"></td>
</tr>
</table>
