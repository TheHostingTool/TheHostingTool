<script type="text/javascript">
var status = "%SUS%";
var accountId = "%ID%";
var icondir = "<ICONDIR>";
$(document).ready(function() {
	if(status == "No Action") {
		// It's not going to do anything... so get rid of it!
		$("#suspendTr").remove();
		$("#cancelTr").remove();
	}
	var accountActionClick = function() {
	    var id = this.id;
	    switch(id.split("-")[1]) {
	    	case "suspend":
			    if(status == "Suspend") {
			      var reason = prompt('Please state your reason for suspending. Leave blank for none.');
			      var post = { id: accountId, action: "suspend" };
			      post[csrfMagicName] = csrfMagicToken;
			      if(reason != null && reason != "") {
			        post.reason = reason;
			      }
			      else if(reason == null) {
			        return;
			      }
			      $(".accountActions").unbind("click");
			      $("#clientactionimg-suspend").attr('src', icondir + "ajax-loader.gif");
			      $.post("<AJAX>?function=clientAction", post, function(data) {
			        if(!data.error) {
			          window.location.reload();
			          return;
			        }
			        alert(data.msg);
			        $("#clientactionimg-suspend").attr('src', icondir + "exclamation.png");
			        $(".accountActions").click(accountActionClick);
			      });
			    }
			    else if(status == "Unsuspend") {
			      var post = { id: accountId, action: "unsuspend" };
			      post[csrfMagicName] = csrfMagicToken;
			      $(".accountActions").unbind("click");
			      $("#clientactionimg-suspend").attr('src', icondir + "ajax-loader.gif");
			      $.post("<AJAX>?function=clientAction", post, function(data) {
			        if(!data.error) {
			          window.location.reload();
			          return;
			        }
			        alert(data.msg);
			        $("#clientactionimg-suspend").attr('src', icondir + "accept.png");
			        $(".accountActions").click(accountActionClick);
			      });
			    }
			    else if(status == "<a href='?page=users&sub=validate'>Validate</a>") {
			      window.location = "%URL%/admin/?page=users&sub=validate";
			    }
			    else if(status == "Cancelled") {
			      window.location = "%URL%/admin/none.php";
			    }
			    else {
			      window.location = "%URL%/admin/?page=users&sub=validate";
			    }
	    		break;
	    	case "cancel":
				var reason = prompt('CAUTION: If you proceed, the account "%USER%" will be completely and irrevocably removed from the server but not THT.\r\n\r\nWhy are you canceling this account? Leave blank if you do not wish to provide a reason.');
				var post = { id: accountId, action: "cancel" };
				post[csrfMagicName] = csrfMagicToken;
				if(reason != null && reason != "") {
					post.reason = reason;
				}
				else if(reason == null) {
					return;
				}
                $(".accountActions").unbind("click");
                $("#clientactionimg-cancel").attr('src', icondir + "ajax-loader.gif");
		        $.post("<AJAX>?function=clientAction", post, function(data) {
			        if(!data.error) {
			          window.location.reload();
			          return;
			        }
			        alert(data.msg);
			        $("#clientactionimg-cancel").attr('src', icondir + "package_delete.png");
			    	$(".accountActions").click(accountActionClick);
			    });
	    		break;
	    	case "terminate":
                var reason = prompt('CAUTION: If you proceed, the account "%USER%" will be completely and irrevocably removed from the server and THT.\r\n\r\nWhy are you terminating this account? Leave blank if you do not wish to provide a reason.');
                var post = { id: accountId, action: "terminate" };
                post[csrfMagicName] = csrfMagicToken;
                if(reason != null && reason != "") {
                    post.reason = reason;
                }
                else if(reason == null) {
                    return;
                }
                $(".accountActions").unbind("click");
                $("#clientactionimg-terminate").attr('src', icondir + "ajax-loader.gif");
                $.post("<AJAX>?function=clientAction", post, function(data) {
                    if(!data.error) {
                      window.location = "?page=users&sub=search";
                      return;
                    }
                    alert(data.msg);
                    $("#clientactionimg-terminate").attr('src', icondir + "cross.png");
                    $(".accountActions").click(accountActionClick);
                });
	    		break;
	    }
	}
	$(".accountActions").click(accountActionClick);
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
        <td width="1%" align="right"><img id="clientactionimg-suspend" src="<URL>themes/icons/%IMG%" /></td>
        <td align="left"><a class="accountActions" id="clientaction-suspend" class="accountActions suspendIcon" href="javascript:void(0);">%SUS%</a></td>
      </tr>
      <tr id="cancelTr">
        <td width="1%" align="center"><img id="clientactionimg-cancel" src="<URL>themes/icons/package_delete.png" /></td>
        <td align="left"><a id="clientaction-cancel" title="Terminates the package on the server but keeps the client and package info in THT." class="accountActions tooltip" href="javascript:void(0);">Cancel User</a></td>
      </tr>
      <tr>
        <td width="1%" align="center"><img id="clientactionimg-terminate" src="<URL>themes/icons/cross.png" /></td>
        <td align="left"><a id="clientaction-terminate" title="Completely remove the account from THT and the server." class="accountActions tooltip" href="javascript:void(0);">Terminate User</a></td>
      </tr>
      <tr>
        <td width="1%" align="center"><img src="<URL>themes/icons/email.png" /></td>
        <td align="left"><a href="?page=users&amp;sub=search&amp;do=%ID%&amp;func=email">Email User</a></td>
      </tr>
      <tr>
        <td width="1%" align="center"><img src="<URL>themes/icons/key.png" /></td>
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
