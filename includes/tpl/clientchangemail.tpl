<ERRORS>
<form action="" method="POST">
<table>
	<tbody>
		<tr>
			<td><strong>Email Status:</strong></td>
			<td>%STATUS%</td>
		</tr>
		<tr>
			<td><strong>Current Email:</strong></td>
			<td>%EMAIL%</td>
		</tr>
		<tr>
			<td><strong>New Email:</strong></td>
			<td><input type="email" name="newemail" value="%NEWEMAIL%" size="30" length="50" /> <input type="submit" name="change" value="Update email" /></td>
		</tr>
		<tr>
			<td><strong>Actions</strong></td>
			<td><input type="submit" name="resend" value="Resend verification email" %RESEND% />
				<input type="submit" name="cancel" value="Cancel email update" %CANCEL% /></td>
		</tr>
	</tbody>
</table>
</form>