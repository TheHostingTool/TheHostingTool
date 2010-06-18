	<div class="portlet" id="portlet-%ID%">
		<div class="portlet-header">%NAME%</div>
		<div class="portlet-content">
			<div class="portlet-icons">
				<a class="tooltip" title="Delete Package"><img src="<ICONDIR>delete.png" alt="Delete" class="deleteIcon" id="deleteIcon-%ID%" /></a>
			</div>
			<table>
                        <tbody>
				<tr>
					<td><label for="inputName-%ID%">Name:</label></td>
					<td><input class="inputClass" type="text" name="name" value="%NAME%" id="inputName-%ID%" /></td>
					<td><a class="tooltip" title="The User-Friendly version of the package name."><img src="<ICONDIR>eye.png" class="tooltip" alt="Eye" /></a></td>
				</tr>
				<tr>
					<td><label for="inputBackend-%ID%">Backend:</label></td>
					<td><input class="inputClass" type="text" class="tooltip" name="link" value="%BACKEND%" id="inputBackend-%ID%" /></td>
					<td><a class="tooltip" title="The name of your package as it appears in your hosting software. WHM, DirectAdmin, etc."><img src="<ICONDIR>cog.png" alt="Cog" /></a></td>
				</tr>
				<tr>
					<td><label for="inputDescription-%ID%">Description:</label></td>
                                        <td><textarea id="inputDescription-%ID%" rows="3">%DESCRIPTION%</textarea></td>
					<td><a class="tooltip" title="Describe your package to your customer."><img src="<ICONDIR>page.png" alt="Page" /></a></td>
				</tr>
                                <tr>
                                        <td><label for="inputType-%ID%">Type:</label></td>
                                        <td>%TYPES%</td>
                                        <td><a class="tooltip" title="What kind of package is this?"><img src="<ICONDIR>brick.png" alt="Brick" /></a></td>
                                </tr>
                                <tr>
                                        <td><label for="inputVal-%ID%">Admin Validation:</label></td>
                                        <td><input name="inputVal-%ID%" class="inputClass" type="checkbox" id="inputVal-%ID%" value="1" %CHECKED% /></td>
                                        <td><a class="tooltip" title="Does this package require admin validation?"><img src="<ICONDIR>user_suit.png" alt="Suit" /></a></td>
                                </tr>
                                <tr>
                                        <td><label for="inputReseller-%ID%">Reseller:</label></td>
                                        <td><input name="inputReseller-%ID%" class="inputClass" type="checkbox" id="inputReseller-%ID%" value="1" %CHECKED2% /></td>
                                        <td><a class="tooltip" title="Is this package a reseller?"><img src="<ICONDIR>user_add.png" alt="Add" /></a></td>
                                </tr>
                        </tbody>
			</table>
                        <div align="center" class="hidden saveChangesDiv" id="saveChangesDiv-%ID%"><button class="saveChangesBtn" id="saveChangesBtn-%ID%">Save Changes</button></div>
		</div>
	</div>