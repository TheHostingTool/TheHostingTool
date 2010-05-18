<script type="text/javascript">
    var id;
    var divId;
        $(document).ready(function(){
		$("#packs").sortable({handle : '#grabber'});
                $("#addnavlinkLink").click(function(){
                        $("#nbit").slideToggle(500);
                });
                $("#saveorder").click(function(){
                    var order = $('#packs').sortable('serialize');
                    $("#packs").toggle("slide");
                    $.get("<AJAX>?"+order+"&function=porder", function(data){$("#packs").toggle("slide"); document.getElementById("message").innerHTML = order;});
                });
                $(".editIcon").click(function(){
                    id = this.id;
                    divId = id.split("-")[1];
                    $.get("<AJAX>?function=nedit&do="+divId, function(data) {
                        if($("#nedit").css("display") == "none") {
                            $("#nedit").html(data);
                            $("#nedit").slideDown(500);
                        }
                        else {
                                $("#nedit").slideUp(500, function() {
                                    $("#nedit").html(data);
                                    $("#nedit").slideDown(500);
                                });
                        }
                    });
                });
	});
</script>
<div class="subborder">
	<div class="sub">
    	<table width="100%" border="0" cellspacing="3" cellpadding="0">
          <tr>
            <td width="1%"><img src="<ICONDIR>add.png" alt="Add Navlink"/></td>
            <td><a href="javascript:void(0)" id="addnavlinkLink">Add Navigation Link</a></td>
          </tr>
        </table>
    </div>
</div>
<ERRORS>
<div id="nbit" style="display:none;"><div class="subborder"><div class="sub">
<ERRORS>
<form id="addnavlink" name="addnavlink" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
	<tr><td colspan="2"><strong>Add Navication Link</strong></td></tr>
  <tr>
    <td width="25%">Name:</td>
    <td>
      <input name="name" type="text" id="name" /><a title="The reference name in the database. Please use only lowercase characters, no spaces or symbols." class="tooltip"><img src="<URL>themes/icons/cog.png" alt='' /></a>
    </td>
  </tr>
  <tr>
    <td width="25%">Visual:</td>
    <td>
      <input name="visual" type="text" id="visual" /><a title="The text that your users see in the navigation link." class="tooltip"><img src="<URL>themes/icons/information.png" alt='' /></a>
    </td>
  </tr>
  <tr>
    <td width="25%">Icon:</td>
    <td>
      <input name="icon" type="text" id="icon" /><a title="The icons that your users see next to the navigation link. Icons are located in <URL>themes/icons.<br /><strong>Example:</strong> stop.png" class="tooltip"><img src="<URL>themes/icons/picture.png" alt='' /></a>
    </td>
  </tr>
  <tr>
    <td width="25%">Link:</td>
    <td>
        <input name="link" type="text" id="link" /><a title="The relative URL for the navigation link.<br />Example: admin/" class="tooltip"><img src="<URL>themes/icons/link.png" /></a>
    </td>
</tr>

</table>
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td colspan="2" id="customform"></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td colspan="2" align="center"><input type="submit" name="add" id="add" value="Add navigation link" /></td>
  </tr>
</table>
</form>
</div></div></div>
<div id="nedit" style="display:none;"></div>
<div id="packs">
	%PACKS%
</div>
<div align="center"><input name="saveorder" id="saveorder" type="button" value="Save navigation link order" /></div>
