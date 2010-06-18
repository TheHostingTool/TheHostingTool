	<div class="portlet" id="portlet-%ID%">
		<div class="portlet-header">%NAME%</div>
		<div class="portlet-content">
			<div class="portlet-icons">
				<a class="tooltip" title="Delete NavLink"><img src="<ICONDIR>delete.png" alt="Delete" class="deleteIcon" id="deleteIcon-%ID%" /></a>
			</div>
			<table>
				<tr>
					<td><label for="inputName-%ID%">Name:</label></td>
					<td><input class="inputClass" type="text" name="name" value="%NAME%" id="inputName-%ID%" /></td>
					<td><a class="tooltip" title="The text your users see in the navbar."><img src="<ICONDIR>eye.png" class="tooltip" alt="Eye" /></a></td>
				</tr>
				<tr>
					<td><label for="inputIcon-%ID%">Icon:</label></td>
					<td><input class="inputClass" type="text" class="tooltip" name="icon" value="%ICON%" id="inputIcon-%ID%" /></td>
					<td><a class="tooltip" title="The icons that your users see next to the navigation link. Icons are located in the 'themes/icons' folder. Example: stop.png"><img src="<ICONDIR>picture.png" alt="Picture" /></a></td>
				</tr>
				<tr>
					<td><label for="inputLink-%ID%">Link:</label></td>
					<td><input class="inputClass" type="text" class="tooltip" name="link" value="%LINK%" id="inputLink-%ID%" /></td>
					<td><a class="tooltip" title="The relative URL for the navagation link. Example: admin/"><img src="<ICONDIR>link.png" alt="Link" /></a></td>
				</tr>
			</table>
                        <div align="center" class="hidden saveChangesDiv" id="saveChangesDiv-%ID%"><button class="saveChangesBtn" id="saveChangesBtn-%ID%">Save Changes</button></div>
		</div>
	</div>