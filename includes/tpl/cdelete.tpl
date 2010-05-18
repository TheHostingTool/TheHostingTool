<script type="text/javascript">
var working = '<div align="center"><img src="<URL>themes/icons/working.gif"></div>';
function stopRKey(evt) { 
  var evt = (evt) ? evt : ((event) ? event : null); 
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
} 
document.onkeypress = stopRKey;

function check() {
	if(document.getElementById("understand").checked == true) {
		$("#passwordpart").slideDown(500);
	}
	else {
		$("#passwordpart").slideUp(500);
		$("#finish").slideUp(500);
	}
}

function term(user, pass) {
	document.getElementById("finishtext").innerHTML = working;
	$("#finish").slideDown(500);
	$("#passwordpart").slideUp(500);
	$.get("<AJAX>?function=termacc&user="+user+"&pass="+pass, function(data) {
		document.getElementById("finishtext").innerHTML = data;
						});
}

</script>
<form id="delete" name="delete" method="post">
<div class="subborder">
    <div class="sub">
      <span class="errors">Notice:</span> This WILL delete your client account and your hosting account. This means all your files, once this step has completed can't be retrieved.<br />
      <input name="understand" type="checkbox" id="understand" value="1" onchange="check()" /><strong>I understand this notice above</strong>
       <a title="Tick here <b>only if you <i>really</i> want to delete your account!</b>" class="tooltip"><img src="<ICONDIR>information.png" /></a>
    </div>
</div>
<div id="passwordpart" style="display:none">
    <div class="subborder">
        <div class="sub">
            <table width="100%" border="0" cellspacing="2" cellpadding="0">
              <tr>
                <td width="25%">Your Password:</td>
                <td width="10%"><input name="password" id="password" type="password" />
                </td>
                <td width="65%" align="left"><input name="delete" id="delete" type="button" value="Delete" onclick="term('%USER%', document.getElementById('password').value)"/>  <a title="Click here to terminate your account." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
              </tr>
            </table>
        </div>
    </div>
</div>
<div id="finish" style="display:none">
    <div class="subborder">
        <div class="sub">
            <span id="finishtext"></span>
        </div>
    </div>
</div>
</form>
