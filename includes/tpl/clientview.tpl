<script type="text/javascript">
	function clientsearch() {
		var type = document.getElementById("type").value;
		var value = document.getElementById("value").value;
		ajaxSlide("clientsajax", "<AJAX>?function=search&type="+type+"&value="+value);
                kthx();
	}
$(document).ready(function() {
    kthx();
});
function kthx() {
            $(".suspendIcon").click(function(){
                var status = "%SUS%";
                if(status == "Suspend Account") {
                 var reason = prompt('Please state your reason for suspending. Leave blank for none.');
                if(reason != null && reason != "") {
                    var query = window.location + "&func=sus&reason=" + reason;
                }
                else {
                    var query = window.location + "&func=sus";
                }
                window.location = query;
                }
                else if(status == "Unsuspend Account") {
                    window.location = "%URL%admin/?page=users&sub=search&do=%ID%&func=unsus";
                }
                else if(status == "<a href='?page=users&sub=validate'>Admin Validation Page</a>") {
                    window.location = "%URL%/admin/?page=users&sub=validate";
                }
                else {
                    alert("Uh oh...");
                }
            });
        }
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
      <tr>
        <td width="1%" align="right"><img src="<URL>themes/icons/%IMG%" /></td>
        <td align="left"><a class="suspendIcon" href="javascript:void(0);">%SUS%</a></td>
      </tr>
      <tr>
        <td width="1%" align="center"><img src="<URL>themes/icons/cross.png" /></td>
        <td align="left"><a href="?page=users&amp;sub=search&amp;do=%ID%&amp;func=term">Terminate User</a></td>
      </tr>
      <tr>
        <td width="1%" align="center"><img src="<URL>themes/icons/email.png" /></td>
        <td align="left"><a href="?page=users&amp;sub=search&amp;do=%ID%&amp;func=email">Email User</a></td>
      </tr>
    </table>
    </div>
    </td>
    <td class="rightbreak"></td>
    <td width="70%" valign="top">%CONTENT%%BOX%</td>
  </tr>
</table>