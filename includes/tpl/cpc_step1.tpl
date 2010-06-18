<ERRORS>
<form id="add" name="add" method="post" action="">
		<table width="100%" border="0" cellspacing="3" cellpadding="0">
		  <tr>
		    <td colspan="2"><strong>cP Creator - SQL Details</strong></td>
	      </tr>
		  <tr>
			<td width="30%">Hostname:</td>
			<td><label>
			<input name="hostname" type="text" class="form" id="hostname" value="localhost" />
			</label></td>
		  </tr>
          <tr>
			<td width="30%">mySQL Username:</td>
			<td><label>
			<input name="username" type="text" class="form" id="username" />
			</label></td>
		  </tr>
          <tr>
            <td>mySQL Password:</td>
            <td><label>
              <input name="password" type="password" class="form" id="password" />
            </label></td>
          </tr>
          <tr>
            <td>mySQL Database:</td>
            <td><label>
              <input name="database" type="text" class="form" id="database" />
            </label></td>
          </tr>
          <tr>
            <td>cP Creator Prefix:</td>
            <td><input name="prefix" type="text" class="form" id="prefix" /></td>
          </tr>
          <tr>
            <td>Import P2H Users &amp; Settings:</td>
            <td><label>
              <input name="p2h" type="checkbox" id="p2h" value="1" checked="checked" />
            </label></td>
          </tr>
          <tr>
            <td colspan="2"><strong>Note: </strong>If importing P2H users it will import all the forums created in cP Creator and set the correct users to p2h. It will work automatically and still check monthly for monthly posts if the packages are set up correcttly. If the box isn't checked, all the users that are set to P2H will be imported as free.<br /><br />

            <strong>Second Note: </strong>If you're importing users with P2H make sure your packages in your ACP in THT are set up properly. The backends must be the SAME as the ones with the users being imported and for P2H to work the packages must be on P2H. Obviously.</td>
          </tr>
		  <tr>
			<td colspan="2" align="center">
			  <label>
				<input type="submit" name="type" id="type" value="Import Data" class="button" />
			  </label>			</td>
		  </tr>
		</table>
</form>