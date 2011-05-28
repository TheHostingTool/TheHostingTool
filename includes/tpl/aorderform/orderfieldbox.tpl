<div class="orderfieldbox subborder sortableHandle" id="orderfieldbox-%ID%">
	<div class="sub">
        <table width="100%" border="0" cellspacing="2" cellpadding="0">
          <tr>
            <td><strong><a id="orderTitle-%ID%" class="orderTitle" href="javascript:void(0);">%TITLE%</a>%REQ%</strong></td>
            <td rowspan="2" align="right">
            	<a id="orderEditBtn-%ID%" class="orderEditBtn tooltip" href="javascript:void(0);" title="Edit Details for the %TITLE% field."><img src="<ICONDIR>pencil.png" /></a>
            	<a id="orderDelBtn-%ID%" class="orderDelBtn tooltip" href="javascript:void(0);" title="Delete the %TITLE% field."><img src="<ICONDIR>delete.png" /></a>
            </td>
          </tr>
        </table>
        <div id="hiddenFieldBox-%ID%" class="hiddenFieldBox hiddenStyle">
        <table width="100%" border="0" cellspacing="2" cellpadding="0">
          <tr>
            <td><strong>Title:</strong></td><td><input value="%TITLE%"></td>
          </tr>
          <tr>
			  <td><strong>Description:</strong></td><td><textarea>%DESCRIPTION%</textarea></td>
          </tr>
          <tr>
			  <td><strong>Input Type:</strong></td><td>%TYPELIST%</td>
          </tr>
          <tr>
			  <td><strong>Default Value:</strong></td><td><input type="text" name="defaultval-%ID%" id="defaultval-%ID%" value="%DEFAULTVALUE%" /></td>
          </tr>
          <tr>
			  <td></td><td><button id="saveBtn-%ID%" class="hiddenStyle">Save Changes</button></td>
          </tr>
        </table>
        </div>
    </div>
</div>
