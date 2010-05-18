<ERRORS>
<strong>Editing Forum:</strong> %NAME%
<form id="add" name="add" method="post" action="">
		<table width="100%" border="0" cellspacing="3" cellpadding="0">
		  <tr>
			<td width="30%">Hostname:</td>
			<td><label>
			<input name="hostname" type="text" class="form" id="hostname" value="%HOST%" />
			</label></td>
		  </tr>
          <tr>
			<td width="30%">mySQL Username:</td>
			<td><label>
			<input name="username" type="text" class="form" id="username" value="%USER%" />
			</label></td>
		  </tr>
          <tr>
            <td>mySQL Password:<br />(Leave blank if you don't want it to change)</td>
            <td><label>
              <input name="password" type="password" class="form" id="password" />
            </label></td>
          </tr>
          <tr>
            <td>mySQL Database:</td>
            <td><label>
              <input name="database" type="text" class="form" id="database" value="%DB%" />
            </label></td>
          </tr>
          <tr>
            <td>Forum Prefix:</td>
            <td><input name="prefix" type="text" class="form" id="prefix" value="%PREFIX%" /></td>
          </tr>
		  <tr>
			<td colspan="2" align="center">
			  <label>
				<input type="submit" name="type" id="type" value="Edit Forum" class="button" />
			  </label>			</td>
		  </tr>
		</table>
</form>