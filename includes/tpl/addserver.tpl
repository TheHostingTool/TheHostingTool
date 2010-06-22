<script type="text/javascript">
function serverchange(value) {
	$.get("<AJAX>?function=serverhash&type="+value, function(data) {
		$("#passtext").slideUp(500);			
		$("#passbox").slideUp(500, function(lol2) {
			if(data == "1") {
				$("#passbox").html('<input name="hash" type="text" id="hash" />');
			}
			else {
				$("#passbox").html('<textarea name="hash" id="hash" cols="45" rows="5"></textarea>');
			}
			$("#passtext").slideDown(500);		
			$("#passbox").slideDown(500, function(lol) {
				if(data == "1") {
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
      <input name="name" type="text" id="name" /><a title="The Server Name, shown in the AdminCP." class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td width="20%">Host:</td>
    <td>
      <input name="host" type="text" id="host" /><a title="The Server's Hostname. Must be a FQDN!" class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td>Username:</td>
    <td><input type="text" name="user" id="user" /><a title="Username to connect to WebHost Manager" class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
    <td valign="top" width="20%"><span id="passtext"></span></td>
    <td><span id="passbox"></span></td>
  </tr>
  <tr>
    <td valign="top">Type:</td>
    <td><select name="type" id="type" onchange="serverchange(this.value)">%TYPE%</select> <a title="The Server Type. This is the Control Panel that your server is running.<br /><i>eg: cPanel/WHM</i>" class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="add" id="add" value="Add Server" /></td>
  </tr>
</table>
</form>
