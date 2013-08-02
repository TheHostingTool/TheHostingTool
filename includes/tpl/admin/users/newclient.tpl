<ERRORS>
<script type="text/javascript">
$(document).ready(function() {
    $("#newclientform").submit(function() {
        $("#newclientsubmit").attr("disabled", "disabled");
        $("#newclientsubmit").val("Please wait...");
    });
    var updatestr = function() {
        $("#txtstrength").html("Loading...");
        var json = {
            package: $("#packageinput").val(),
            passwd: $("#txtpasswd").val()
        }
        json[csrfMagicName] = csrfMagicToken;
        $.post("<AJAX>?function=passwdStrength", json, function(data) {
            if(!data.error) {
                $("#txtstrength").html(data.strength);
                return;
            }
            $("#txtstrength").html(data.error);
        }, "json");
    }
    $("#txtpasswd").change(updatestr);
    $("#packageinput").change(updatestr);
});
</script>
<form id="newclientform" action="" method="post" autocomplete="off">
<table width="100%">
    <tr>
        <td>Username:</td>
        <td><input name="username" type="text" autocomplete="off" autofocus="on" required="required" value="%USERNAME%"></td>
    </tr>
    <tr>
        <td>Password:</td>
        <td><input id="txtpasswd" name="password" type="password" autocomplete="off" required="required" value="%PASSWORD%"></td>
    </tr>
    <tr>
        <td>Re-type Password:</td>
        <td><input name="confirm" type="password" autocomplete="off" required="required"></td>
    </tr>
    <tr>
        <td>Strength:</td>
        <td><div id="txtstrength"></div></td>
    </tr>
    <tr>
        <td width="20%">Domain:</td>
        <td><input name="domain" type="text" autocomplete="off" required="on" value="%DOMAIN%"><select name="subdomain"><option value="no">No Subdomain</option>%SUBDOMAINS%</select></td>
    </tr>
    <tr>
        <td>Email:</td>
        <td><input name="email" type="email" autocomplete="off" required="required" value="%EMAIL%"></td>
    </tr>
    <tr>
        <td>Package:</td>
        <td><select id="packageinput" name="package">%PACKAGES%</select></td>
    </tr>
    <tr>
        <td></td>
        <td><input id="newclientsubmit" type="submit" value="Create Client"></td>
    </tr>
</table>
</form>