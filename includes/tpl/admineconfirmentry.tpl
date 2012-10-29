<div class="subborder">
	<div class="sub">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	    <tr>
	      <td width="50%">
          <strong>Username:</strong> <a href="?page=users&sub=search&do=%ID%">%USER%</a><br />
          <strong>Email:</strong> %EMAIL%<br />
          <strong>New Email:</strong> %NEWEMAIL%<br />
          <strong>Signup Date:</strong> %SIGNUP%<br />
			</td>
	      <td align="right">
	      	<form action="" method="POST">
	      		<input type="hidden" name="id" value="%ID%" />
		      	<a href="?page=users&sub=search&do=%ID%"><img src="<URL>themes/icons/eye.png" style="padding-right:5px;"/></a>
		      	<input title="Resend confirmation email" type="image" name="action" value="resend" src="<URL>themes/icons/email_add.png" style="padding-right:3px;" />
		      	<input title="Manually confirm email" type="image" name="action" value="confirm" src="<URL>themes/icons/accept.png"/>
	        </form>
	      </td>
        </tr>
      </table>
	</div>
</div>