<style type="text/css">
.sortableHandle {
	cursor: move;
}
.hiddenStyle {
	display: none;
}
.savedDiv {
	text-align: center;
	font-weight: bold;
	font-style: italic;
}
#goodSavedDiv {
	color: green;
}
#badSavedDiv {
	color: red;
}
</style>
<script type="text/javascript">
$("document").ready(function() {
	$("#newCustomFieldLink").click(function() {
		
	});
	$("#sortableDiv").sortable({
		change: function(event, ui) {
			$("#saveOrderChangesDiv").slideDown();
		}
	});
	$(".orderEditBtn").click(editOrderBtnClickEvent);
	$(".orderTitle").click(editOrderBtnClickEvent);
	function editOrderBtnClickEvent(event) {
		var id = this.id.split("-")[1];
		$("#hiddenFieldBox-"+id).slideToggle();
	}
	$("#saveOrderChangesBtn").click(function() {
		$("#saveOrderChangesBtn").attr("disabled", "disabled");
		$("#atBottomDiv").slideUp(function() {
			$(".savedDiv").hide();
			var order = [];
			$(".orderfieldbox").each(function(index) {
				order[index] = $(this).attr('id').split("-")[1];
			});
			$.post("<AJAX>?function=setOrderOfRows", {
				table: 'orderfields',
				order: order.join(','),
				__tht_csrf_magic: csrfMagicToken
			}, function(data) {
				if(data == "1") {
					$("#goodSavedDiv").show();
				}
				else {
					$("#badSavedDiv").show();
				}
				$("#saveOrderChangesBtn").removeAttr("disabled");
				$("#atBottomDiv").slideDown();
			});
		});
	});
});
</script>

<a href="javascript:void(0)" id="newCustomFieldLink"><strong><img src="<ICONDIR>add.png" /> New Custom Field</strong></a>
