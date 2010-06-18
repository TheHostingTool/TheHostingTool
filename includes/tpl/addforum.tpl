<script type="text/javascript">
function changePrefix(value)
{
	var prefix = document.getElementById('prefix');
	
	if (value == 'phpbb') { prefix.value = 'phpbb_'; }
	if (value == 'phpbb2') { prefix.value = 'phpbb_'; }
	if (value == 'mybb') { prefix.value = 'mybb_'; }
	if (value == 'ipb') { prefix.value = 'ipb_'; }
        if (value == 'ipb3') { prefix.value = 'ipb_'; }
	if (value == 'vb') { prefix.value = 'vb_'; }
	if (value == 'smf') { prefix.value = 'smf_'; }
	if (value == 'aef') { prefix.value = 'aef_'; }
	if (value == 'drupal') { prefix.value = ''; }
}
</script>
<ERRORS>
<form id="add" name="add" method="post" action="">
		<table width="100%" border="0" cellspacing="3" cellpadding="0">
		  <tr>
			<td width="30%">Hostname:</td>
			<td><label>
			<input name="hostname" type="text" class="form" id="hostname" value="localhost" />
			</label></td>
		  </tr>
		  <tr>
            <td>Forum Name:</td>
		    <td><label>
              <input name="name" type="text" class="form" id="name" maxlength="28"/>
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
			<td width="30%">Forum:</td>
			<td><label>
			<select name="forum" id="forum" class="form" onchange="changePrefix(this.value)">
			  <option value="phpbb" selected="selected">phpBB 3</option>
              <option value="phpbb2">phpBB 2</option>
			  <option value="mybb">myBB</option>
			  <option value="ipb">Invision Power Board 2</option>
                          <option value="ipb3">Invision Power Board 3</option>
			  <option value="vb">vBulletin</option>
              <option value="smf">SMF</option>
              <option value="aef">AEF</option>
			  <option value="drupal">Drupal</option>
		    </select>
			</label></td>
		  </tr>
          
          <tr>
            <td>Forum Prefix:</td>
            <td><input name="prefix" type="text" class="form" id="prefix" value="phpbb_" /></td>
          </tr>
          
		  <tr>
			<td colspan="2" align="center">
			  <label>
				<input type="submit" name="type" id="type" value="Add Forum" class="button" />
			  </label>			</td>
		  </tr>
		</table>
</form>
