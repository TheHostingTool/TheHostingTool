<script type="text/javascript">
	$(function() {
		$("#packs").sortable({handle : '#grabber'});
	});
	function update() {
		var order = $('#packs').sortable('serialize');
		$("#packs").toggle("slide");
		$.get("<AJAX>?"+order+"&function=porder", function(data){$("#packs").toggle("slide"); document.getElementById("message").innerHTML = order;});	
	}
	function addpackage() {
		$("#pbit").slideToggle(500);
	}
	function edit(id) {
		$.get("<AJAX>?function=pedit&do="+id, function(data) {
													   if($("#pedit").css("display") == "none") {
														    $("#pedit").html(data);
													   	$("#pedit").slideDown(500);
														tinyMCE.execCommand('mceAddControl', true, "description2");
													   }
													   else {
														   tinyMCE.execCommand('mceRemoveControl', true, "description2");
														 $("#pedit").slideUp(500, function() {$("#pedit").html(data);$("#pedit").slideDown(500);tinyMCE.execCommand('mceAddControl', true, "description2");});  
													   }
													   });
	}
</script>
<div class="subborder">
	<div class="sub">
    	<table width="100%" border="0" cellspacing="3" cellpadding="0">
          <tr>
            <td width="1%"><img src="<ICONDIR>add.png" alt="Add Package"/></td>
            <td><a href="Javascript:addpackage()">Add Package</a></td>
          </tr>
        </table>
    </div>
</div>
<ERRORS>
<div id="pedit" style="display:none;"></div>
<div id="pbit" style="display:none;"><div class="subborder"><div class="sub">
<script type="text/javascript" src="<URL>includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
	mode : "textareas",
	skin : "o2k7",
	theme : "advanced"
	});
</script>
<ERRORS>
<form id="addpackage" name="addpackage" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
	<tr><td colspan="2"><strong>Add Package</strong></td></tr>
  <tr>
    <td width="20%">Name:</td>
    <td>
      <input name="name" type="text" id="name" /><a title="The User-Friendly version of the package name. Type whatever you want to show to the users." class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td width="20%">Backend:</td>
    <td>
      <input name="backend" type="text" id="backend" /><a title="The <b>backend</b> name of your package. This is the name of the package shown in your WebHost Manager." class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td valign="top">Description:</td>
    <td><textarea name="description" id="description" cols="45" rows="5"></textarea></td>
  </tr>
  <tr>
    <td valign="top">Type:</td>
    <td>
    <select name="type" id="type" onchange="ajaxSlide('customform','<AJAX>?function=acpPadd&type='+ this.value)">
      <option value="free">Free</option>
      <option value="p2h">Post 2 Host</option>
      <option value="paid">Paid</option>
    </select><a title="The type of your package. You can choose between <em>free</em>, <em>post2host</em> and <em>paid</em>." class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td valign="top">Server:</td>
    <td>
    %SERVER% <a title="The Server which the package is located at." class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>
  <tr>
    <td valign="top">Admin Validation:</td>
    <td>
      <input name="admin" type="checkbox" id="admin" value="1" /><a title="Does this package require Administrator Validation?" class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
    <td valign="top">Reseller:</td>
    <td>
      <input name="reseller" type="checkbox" id="reseller" value="1" /><a title="Is this package a reseller package?" class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td colspan="2" id="customform"></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td colspan="2" align="center"><input type="submit" name="add" id="add" value="Add Package" /></td>
  </tr>
</table>
</form>
</div></div></div>
<div id="packs">
	%PACKS%
</div>
<div align="center"><input name="saveorder" type="button" value="Save Package Order" /></div>