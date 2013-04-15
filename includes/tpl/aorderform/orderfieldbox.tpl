<div class="orderfieldbox subborder sortableHandle" id="orderfieldbox-%ID%">
	<div class="sub">
		<table width="100%" border="0" cellspacing="2" cellpadding="0">
			<tr>
				<td><div style="font-weight: bold;" id="orderTitle-%ID%" class="orderTitle"><a href="javascript:void(0);">%TITLE%</a><div style="display: inline; color: red;" id="orderTitle-Req-%ID%">%REQ%</div></div></td>
				<td rowspan="2" align="right">
					<a id="orderEditBtn-%ID%" class="orderEditBtn tooltip" href="javascript:void(0);" title="Edit Details for the %TITLE% field."><img src="<ICONDIR>pencil.png" /></a>
					<a id="orderDelBtn-%ID%" class="orderDelBtn tooltip" href="javascript:void(0);" title="Delete the %TITLE% field."><img src="<ICONDIR>delete.png" /></a>
				</td>
			</tr>
		</table>
		<div id="hiddenFieldBox-%ID%" class="hiddenFieldBox hiddenStyle">
			<table width="100%" border="0" cellspacing="2" cellpadding="0">
				<tr>
					<td><strong>Title:</strong></td><td><input id="cfield-field-title-%ID%" class="cfield-field cfield-field-%ID% cfield-field-title" value="%TITLE%"></td><td width="50%"><a href="javascript:void(0);"><img class="tooltip" title="The &quot;key&quot; of the custom field. Should be short and sweet like: <em>First Name</em> or <em>Phone #</em>." src="<ICONDIR>information.png" /></a></td>
				</tr>
				<tr>
					<td><strong>Description:</strong></td><td><textarea id="cfield-field-description-%ID%" class="cfield-field cfield-field-%ID% cfield-field-description">%DESCRIPTION%</textarea></td><td width="50%"><a href="javascript:void(0);"><img class="tooltip" title="The content you enter here will be shown in a tooltip (like this one) to the user to describe the field." src="<ICONDIR>information.png" /></a></td>
				</tr>
				<tr>
					<td><strong>Input Type:</strong></td><td>%TYPELIST%</td><td width="50%"><a href="javascript:void(0);"><img class="tooltip" title="The kind of field that will be presented. Use HTML5 options at your own risk. Regular expressions will be automatically applied for backwards compatibility. Changing the type of an already in-use field may cause unexpected and undiserable results." src="<ICONDIR>information.png" /></a></td>
				</tr>
                <tr>
                    <td><div class="cfield-typeopt-typeoptdiv-%ID% hiddenStyle"><strong>Type Options:</strong></div></td>
                    <td><div class="cfield-typeopt-typeoptdiv-%ID% hiddenStyle">
                            <table><tbody>
                                <tr><td><label for=cfield-typeopt-min-%ID%">Min:</label></td><td><input class="cfield-field cfield-field-%ID% cfield-typeopt-input" id="cfield-typeopt-min-%ID%" type="text" size="10" value="%MIN%"></td></tr>
                                <tr><td><label for="cfield-typeopt-max-%ID%">Max:</label></td><td><input class="cfield-field cfield-field-%ID% cfield-typeopt-input" id="cfield-typeopt-max-%ID%" type="text" size="10" value="%MAX%"></td></tr>
                                <tr><td><label for="cfield-typeopt-step-%ID%">Step:</label></td><td><input class="cfield-field cfield-field-%ID% cfield-typeopt-input" id="cfield-typeopt-step-%ID%" type="text" size="10" value="%STEP%"></td></tr>
                            </tr></tbody></table>
                    </div></td>
                </tr>
				<tr>
					<td><div class="cfield-selectstuff-optdiv-%ID% hiddenStyle"><strong>Select Box Options:</strong></div></td>
					<td><div class="cfield-selectstuff-optdiv-%ID% hiddenStyle">
						<a href="javascript:void(0);" id="cfield-action-newoption-%ID%" class="cfield-action cfield-action-newoption cfield-action-%ID%">New Option</a>
						<div>
							<table width="100%" border="0" cellspacing="2" cellpadding="0"><tbody id="cfield-tbody-selectoptions-%ID%">
								%SELECTOPTIONS%
							</tbody></table>
						</div>
					</div></td>
				</tr>
				<tr>
					<td><div class="cfield-defaultval-td-%ID%"><strong>Default Value:</strong></div></td>
					<td id="tddefaultval-%ID%" class="tddefaultval"><div class="cfield-defaultval-td-%ID%">
						<input type="text" class="cfield-field cfield-field-%ID% cfield-field-defaultvalue hiddenStyle" name="cfield-field-defaultvalue-%ID%" id="cfield-field-defaultvalue-%ID%" value="%DEFAULTVALUE%" min="%MIN%" max="%MAX%" step="%STEP%" %CHECKED% />
                        <select class="cfield-field cfield-field-%ID% cfield-field-defaultoption hiddenStyle" id="cfield-field-defaultoption-%ID%">
                            <option value="" %DEFAULTSELECTED%>No Default</option>
                            %SELECTOPTIONS4REAL%
                        </select>
					</div></td>
                    <td width="50%"><a href="javascript:void(0);"><img class="tooltip" title="The value that will be placed in the field when the order form loads." src="<ICONDIR>information.png" /></a></td>
				</tr>
				<tr>
					<td><div class="tdregexpdiv-%ID% hiddenStyle" style="font-weight: bold;">Regular Expression:</div><td><div class="tdregexpdiv-%ID% hiddenStyle"><textarea id="cfield-field-regex-%ID%" class="cfield-field cfield-field-%ID% cfield-field-regex">%REGEX%</textarea></div></td></td><td width="50%"><a href="javascript:void(0);"><img class="tooltip" title="Must be a PCRE (Perl Compatible Regular Expressions). This will be checked against what the client inputs for this custom field to verify its integrity. If the client's input matches the regex, then the input will be accepted. Check out regxlib.com for some prefab expressions." src="<ICONDIR>information.png" /></a></td>
				</tr>
				<tr>
					<td><strong>Required?</strong></td><td><input id="cfield-field-required-%ID%" class="cfield-field cfield-field-%ID% cfield-field-required" type="checkbox"%REQC% /></td><td width="50%"><a href="javascript:void(0);"><img class="tooltip" title="If this custom field is required, then the client must enter valid content into it. (It can't be left blank.)" src="<ICONDIR>information.png" /></a></td>
				</tr>
				<tr>
					<td width="20%"></td><td><div id="saveBtnDiv-%ID%" class="hiddenStyle"><button class="saveBtn" id="saveBtn-%ID%">Save Changes</button></div></td>
				</tr>
				<tr>
					<td></td><td>
						<div id="saveBtnDivGood-%ID%" class="saveBtnDivStatus-%ID% hiddenStyle" style="color: green; font-weight: bold; font-style: italic;">Saved!</div>
						<div id="saveBtnDivBad-%ID%" class="hiddenStyle saveBtnDivStatus-%ID%" style="color: red; font-weight: bold; font-style: italic;"></div>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
