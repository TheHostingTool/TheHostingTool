<form action="" method="post" autocomplete="off">
<table width="100%">
    <tr>
        <td>Username:</td>
        <td><input name="username" type="text" autocomplete="off" autofocus="on" required="required"></td>
    </tr>
    <tr>
        <td>Password:</td>
        <td><input name="password" type="password" autocomplete="off" required="required"></td>
    </tr>
    <tr>
        <td>Re-type Password:</td>
        <td><input name="confirm" type="password" autocomplete="off" required="required"></td>
    </tr>
    <tr>
        <td>Strength:</td>
        <td></td>
    </tr>
    <tr>
        <td width="20%">Domain:</td>
        <td><input name="domain" type="text" autocomplete="off" required="on"><select name="subdomain"><option>No Subdomain</option>%SUBDOMAINS%</select></td>
    </tr>
    <tr>
        <td>Email:</td>
        <td><input name="email" type="email" autocomplete="off" required="required"></td>
    </tr>
    <tr>
        <td>Package:</td>
        <td><select name="package">%PACKAGES%</select></td>
    </tr>
    <tr>
        <td></td>
        <td><input type="submit" value="Create Client"></td>
    </tr>
</table>
</form>