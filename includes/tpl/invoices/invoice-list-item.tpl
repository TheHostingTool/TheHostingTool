<script type="text/javascript">
function doswirl(id) {
	document.getElementById("swirl"+id).innerHTML = '<img src="<URL>themes/icons/ajax-loader.gif">';
	window.location = 'index.php?page=invoices&iid='+id;
}
</script>
<div class="subborder">
	<div class="sub">
        <table width="100%" border="0" cellspacing="3" cellpadding="0">
          <tr>
            <td width="30%" align="center">%paid%<br /><br />
            <div id="swirl%id%">%pay%</div></td>
            <td class="rightbreak"></td>
            <td>
              <table width="100%" border="0" cellspacing="3" cellpadding="0">
                <tr>
                  <td><strong>#%id%</strong></td>
                </tr>
                <tr>
                  <td width="1%"><a title="The amount of money you owe." class="tooltip"><img src="<ICONDIR>money.png" border="0" /></a></td>
                  <td>%amount%</td>
                </tr>
                <tr>
                  <td><a title="Whens it due." class="tooltip"><img src="<ICONDIR>time.png" border="0" /></a></td>
                  <td>%due%</td>
                </tr>
                <tr>
                  <td><a title="What is the invoice for?" class="tooltip"><img src="<ICONDIR>information.png" border="0" /></a></td>
                  <td>%notes%</td>
                </tr>
            </table></td>
          </tr>
        </table>
</div></div>