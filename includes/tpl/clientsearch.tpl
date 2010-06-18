<script type="text/javascript">
        var text = "%TEXT";
	function clientsearch(type, value, page) {
		var num = document.getElementById('num').value;
		ajaxSlide("clientsajax", "<AJAX>?function=search&type="+type+"&value="+value+"&page="+page+"&num="+num);
                //the following code is h4xx
                setTimeout('kthx()', 1000);
	}
	function page(num) {
		clientsearch(document.getElementById('type').value, document.getElementById('value').value, num);
	}

        function kthx() {
            $(".suspendIcon").click(function(){
                var id = this.id;
                var accountId = id.split("-")[1];
                //Dang. Look at this split. :P
                var status = $("#" + id)[0].className.split(" ")[2].split("-")[1];
                if(status == "Suspend") {
                    var reason = prompt('Please state your reason for suspending. Leave blank for none.');
                    if(reason != null && reason != "") {
                        var query = window.location + "&do=" + accountId + "&func=sus&reason=" + reason;
                    }
                    else {
                        var query = window.location + "&do=" + accountId + "&func=sus";
                    }
                    window.location = query;
                }
                else if(status == "Unsuspend") {
                    window.location = window.location + "&do=" + accountId + "&func=unsus";
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
            });
        }

$(window).load(function () {
	clientsearch("user", "", 1);
});

</script>
<ERRORS>
<table width="100%" border="0" cellspacing="2">
  <tr>
    <td width="30%" valign="top">
    <div class="subborder">
    <table width="100%" border="0" align="center" cellspacing="2" class="sub">
      <tr>
        <td width="40%" align="right">Search Type:</td>
        <td width="11%"><label>
          <select name="type" id="type">
            <option value="user">Username</option>
            <option value="email">Email</option>
            <option value="domain">Domain</option>
            <option value="ip">IP</option>
            <option value="id">User ID</option>
          </select>
        </label></td>
      </tr>
      <tr>
        <td align="right">Search Criteria:</td>
        <td><label>
          <input type="text" name="value" id="value" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Clients Per Page:</td>
        <td><label>
          <select name="num" id="num">
            <option>5</option>
            <option selected="selected">10</option>
            <option>20</option>
            <option>30</option>
            <option>40</option>
            <option>50</option>
          </select>
        </label></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="button" name="search" id="search" value="Search" onclick="clientsearch(document.getElementById('type').value, document.getElementById('value').value, 1)" />
          <input type="button" name="search2" id="search2" value="Reset Search" onclick="clientsearch(document.getElementById('type').value, '', 1); document.getElementById('value').value = '';" /></td>
      </tr>
    </table>
    </div>
    </td>
    <td class="rightbreak"></td>
    <td width="70%" valign="top">
<div id="clientsajax" style="display:none;">
</div></td>
  </tr>
</table>
