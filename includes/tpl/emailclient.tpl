<script type="text/javascript" src="<URL>includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
	mode : "textareas",
	skin : "o2k7",
	theme : "advanced",
	// Theme options
	theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,forecolor,backcolor",
	theme_advanced_buttons3 : "hr,removeformat,visualaid,|,sub,sup",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "center",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	width : "100%"
	});
</script>
<form action="" method="post" name="email" id="email">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td valign="top">Subject:</td>
    <td><input type="text" name="subject" id="subject" />
    <a title="The subject of the email." class="tooltip"><img src="<ICONDIR>information.png" /></a></td>
  </tr>
  <tr>
    <td width="20%" valign="top" id="description">Message:</td>
    <td><textarea name="content" id="content" cols="" rows=""></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input name="edit" id="edit" type="submit" value="Send Email" /></td>
  </tr>
</table>
</form>