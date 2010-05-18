<ERRORS>
<form id="form1" name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="3" cellpadding="0">
  <tr>
    <td width="30%">Days Unpaid Until Suspension:</td>
    <td width="13%"><input name="susdays" type="text" id="susdays" size="5" value="%SUSDAYS%" />
    </td>
    <td width="57%"><a title="The amount of days that when a invoice has been left unpaid, it suspends the client." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
    <td>Days Suspended Until Termination:</td>
    <td><input name="termdays" type="text" id="termdays" size="5" value="%TERDAYS%" />&nbsp;</td>
    <td><a title="How many days of suspension it takes to terminate." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
    <td>Currency:</td>
    <td>%CURRENCY%</td>
    <td><a title="The currency the user has to pay the invoice in." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
    <td>Paypal Email:</td>
    <td><input name="paypalemail" type="text" id="paypalemail" size="20" value="%PAYPALEMAIL%" /></td>
    <td><a title="The email you want paypal working with." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
    <td colspan="3" align="center"><input type="submit" name="submit" id="submit" value="Save Settings" /></td>
    </tr>
</table>
</form>
