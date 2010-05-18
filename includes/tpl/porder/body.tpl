To change the order of the packages, drag and drop each box accordingly. The boxes will save after every update.<br />
<script type="text/javascript">
	$(function() {
		$("#sortable").sortable();
	});
	function update() {
		var order = $('#sortable').sortable('serialize');
		$("#sortable").toggle("slide");
		$.get("<AJAX>?"+order+"&function=porder", function(data){$("#sortable").toggle("slide"); document.getElementById("message").innerHTML = order;});	
	}
</script>
<div class="errors" id="message"></div>
<div id="sortable">
    %PACKS%
</div>
<div align="center"><input name="save" type="button" value="Save Order" onclick="update()" /></div>
