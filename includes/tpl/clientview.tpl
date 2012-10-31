<script type="text/javascript">
var status = "%SUS%";
$(document).ready(function() {
	if(status == "No Action") {
		// It's not going to do anything... so get rid of it!
		$("#suspendTr").remove();
		$("#cancelTr").remove();
	}
	$(".suspendIcon").click(function(){
		if(status == "Suspend") {
			var reason = prompt('Please state your reason for suspending. Leave blank for none.');
			if(reason != null && reason != "") {
				var query = window.location + "&func=sus&reason=" + reason;
			}
			else if(reason == null) {
				alert("No action taken.");
				return;
			}
			else {
				var query = window.location + "&func=sus";
			}
			window.location = query;
		}
		else if(status == "Unsuspend") {
			window.location = "%URL%admin/?page=users&sub=search&do=%ID%&func=unsus";
		}
		else if(status == "<a href='?page=users&sub=validate'>Validate</a>") {
			window.location = "%URL%/admin/?page=users&sub=validate";
		}
		else if(status == "No Action") {
			alert("No action to be performed.");
		}
		else {
			alert("Unhandled status: " + status);
		}
	});
	
	$("#cancelLink").click(function() {
		var reason = prompt('CAUTION: If you proceed, the account "%USER%" will be completely and irrevocably removed from the server but not THT.\r\n\r\nWhy are you canceling this account? Leave blank if you do not wish to provide a reason.');
		if(reason != null && reason != "") {
			var query = window.location + "&func=cancel&reason=" + reason;
		}
		else if(reason == null) {
			alert("No action taken.");
			return;
		}
		else {
			var query = window.location + "&func=cancel";
		}
		window.location = query;
	});
	$("#termLink").click(function(){
		var reason = prompt('CAUTION: If you proceed, the account "%USER%" will be completely and irrevocably removed from the server and THT.\r\n\r\nWhy are you terminating this account? Leave blank if you do not wish to provide a reason.');
		if(reason != null && reason != "") {
			var query = window.location + "&func=term&reason=" + reason;
		}
		else if(reason == null) {
			alert("No action taken.");
			return;
		}
		else {
			var query = window.location + "&func=term";
		}
		window.location = query;
	});
});
</script>
<ERRORS>
<table width="100%" border="0" cellspacing="2">
  <tr>
    <td width="30%" valign="top">
    <div class="subborder">
    <table width="100%" border="0" align="center" cellspacing="2" class="sub">
    	<tr>
        <td width="1%" align="center"><img src="<URL>themes/icons/user_go.png" /></td>
        <td align="left"><a href="?page=users&amp;sub=search&amp;do=%ID%">Client Details</a></td>
      </tr>
      <tr id="suspendTr">
        <td width="1%" align="right"><img src="<URL>themes/icons/%IMG%" /></td>
        <td align="left"><a class="suspendIcon" href="javascript:void(0);">%SUS%</a></td>
      </tr>
      <tr id="cancelTr">
        <td width="1%" align="center"><img src="<URL>themes/icons/package_delete.png" /></td>
        <td align="left"><a id="cancelLink" title="Terminates the package on the server but keeps the client and package info in THT." class="tooltip" href="javascript:void(0);">Cancel User</a></td>
      </tr>
      <tr>
        <td width="1%" align="center"><img src="<URL>themes/icons/cross.png" /></td>
        <td align="left"><a id="termLink" title="Completely remove the account from THT and the server." class="tooltip" href="javascript:void(0);">Terminate User</a></td>
      </tr>
      <tr>
        <td width="1%" align="center"><img src="<URL>themes/icons/email.png" /></td>
        <td align="left"><a href="?page=users&amp;sub=search&amp;do=%ID%&amp;func=email">Email User</a></td>
      </tr>
      <tr>
        <td width="1%" align="center"><img src="<URL>themes/icons/user_edit.png" /></td>
        <td align="left"><a href="?page=users&amp;sub=search&amp;do=%ID%&amp;func=passwd">Change Password</a></td>
      </tr>
    </table>
    </div>
	<table>
	  <tr>
	  <td>
	  <div id="1" style="display:none;">
		<a title="Delinks this account from the package in THT. The account itself will not be touched." class="tooltip"><img src="<ICONDIR>information.png" /></a> <a class="cancel" href="javascript:void(0);">Cancel</a><br />
		<a title="Completely remove the account from THT and the server." class="tooltip"><img src="<ICONDIR>information.png" /></a> <a class="term" href="javascript:void(0);">Terminate</a>
	  </div>
	  </td>
	  </tr>
	  </table>
    </td>
    <td class="rightbreak"></td>
    <td width="70%" valign="top">%CONTENT%%BOX%</td>
  </tr>
</table>
