<script type="text/javascript" src="<URL>includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
	mode : "textareas",
	skin : "o2k7",
	theme : "simple"
	});
</script>
<ERRORS>
<form id="addpackage" name="addpackage" method="post" action="">
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="20%">Name:</td>
    <td>
      <input name="name" type="text" id="name" value="%NAME%" /><a title="The package name" class="tooltip"><img src="<URL>themes/icons/information.png" /></a>
    </td>
  </tr>  
  <tr>
    <td valign="top">Description:</td>
    <td><textarea name="description" id="description" cols="45" rows="5">%DESCRIPTION%</textarea></td>
  </tr>


  
 <tr> 
   <td valign="top">Billing cycle:</td>
    <td>
	%BILLING_CYCLE%  
  	</td>
  </tr>
  
        <tr>
    <td valign="top">Active</td>
    <td>
	 %STATUS%	
    </td>
  </tr>
  
  
 
    
</table>
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td colspan="2" align="center"><input type="submit" name="add" id="add" value="Edit Addon" /></td>
  </tr>
</table>
</form>
