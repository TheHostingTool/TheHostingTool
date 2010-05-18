<script type="text/javascript" src="<URL>includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
	mode : "textareas",
	skin : "o2k7",
	width : "50%",
	theme : "simple"
	});
</script>
<ERRORS>
<form id="addticket" name="addticket" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="20%">Title:</td>
    <td width="10%">
      <input name="title" type="text" id="title" /></td>
    <td width="70%"><a title="The name of the ticket. This should briefly describe your problem." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
  </tr>
  <tr>
    <td valign="top">Urgency:</td>
    <td><label>
      <select name="urgency" id="urgency">
        <option>Very High</option>
        <option>High</option>
        <option selected="selected">Medium</option>
        <option>Low</option>
      </select>
    </label></td>
    <td><a title="The urgency of your ticket. Is it very important and needs solving fast?" class="tooltip"><img src="<URL>themes/icons/information.png" alt="Info" /></a></td>
  </tr>
  <tr>
    <td valign="top">Content:</td>
    <td colspan="2"><textarea name="content" id="content" cols="45" rows="5"></textarea></td>
  </tr>
  <tr>
    <td align="center" colspan="3"><input type="submit" name="add" id="add" value="Add Ticket" /></td>
  </tr>
</table>
</form>
