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
//<![CDATA[
$("document").ready(function() {
	$.fn.htmlInclusive = function() { return $('<div />').append($(this).clone()).html(); }
	var onFieldChangeEvent = function(paramId) {
		var id;
		if(!isNaN(parseFloat(paramId)) && isFinite(paramId)) {
			id = paramId;
		}
		else {
			id = this.id.split("-")[3];
		}
		$(".saveBtnDivStatus-" + id).slideUp();
		$("#saveBtnDiv-" + id).slideDown();
	}
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
		$(this).attr("disabled", "disabled");
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
	$(".saveBtn").click(function() {
		var id = this.id.split("-")[1];
		$(".saveBtnDivStatus-" + id).slideUp();
		
		if($("#cfield-field-title-" + id).val() == "") {
			alert("A title is required.");
			return;
		}
		
		$(this).attr("disabled", "disabled");
		$(this).html("Saving...");
		$("#cfield-field-title-" + id).attr("readonly", "readonly");
		$("#cfield-field-description-" + id).attr("readonly", "readonly");
		$("#cfield-field-typelist-" + id).attr("disabled", "disabled");
		$("#cfield-field-defaultvalue-" + id).attr("readonly", "readonly");
		$("#cfield-field-regex-" + id).attr("readonly", "readonly");
		$("#cfield-field-required-" + id).attr("disabled", "disabled");
		
		$.post("<AJAX>?function=updateCustomField", {
			title: $("#cfield-field-title-" + id).val(),
			description: $("#cfield-field-description-" + id).html(),
			type: $("#cfield-field-typelist-" + id).val(),
			selectopt: $("cfield-tbody-selectoptions-" + id).html(),
			defaultvalue: $("#cfield-field-defaultvalue-" + id).val(),
			regex: $("#cfield-field-regex-" + id).val(),
			required: $("#cfield-field-required-" + id).val(),
			__tht_csrf_magic: csrfMagicToken
		}, function(data) {
				if(data == "1") {
					$("#saveBtnDivGood-" + id).slideDown();
				}
				else {
					$("#saveBtnDivBad-" + id).fadeIn();
				}
				$("#saveBtn-" + id).html("Save Changes");
				$("#saveBtn-" + id).removeAttr("disabled");
				$(".cfield-field-" + id).removeAttr("disabled");
				$(".cfield-field-" + id).removeAttr("readonly");
			}
		);
	});
	$(".cfield-field-typelist").change(function() {
		var id = this.id.split("-")[3];
		// Needs testing with IE. Bypassing jQuery for compatibility
		if(this.value == "password") {
			document.getElementById("cfield-field-defaultvalue-" + id).setAttribute("type", "text");
			$(".cfield-selectstuff-optdiv-" + id).slideUp();
			$(".cfield-defaultval-td-" + id).slideDown();
		}
		else if(this.value == "select") {
			$(".cfield-selectstuff-optdiv-" + id).slideDown();
			$(".cfield-defaultval-td-" + id).slideUp();
		}
		else {
			document.getElementById("cfield-field-defaultvalue-" + id).setAttribute("type", this.value);
			$(".cfield-selectstuff-optdiv-" + id).slideUp();
			$(".cfield-defaultval-td-" + id).slideDown();
		}
	});
	$(".cfield-field-typelist").change();
	var bindActionsAgain = function() {
		$(".cfield-action-upoption").unbind('click');
		$(".cfield-action-downoption").unbind('click');
		$(".cfield-action-renameoption").unbind('click');
		$(".cfield-action-deleteoption").unbind('click');
		$(".cfield-action-upoption").click(onOptionUpClick);
		$(".cfield-action-downoption").click(onOptionDownClick);
		$(".cfield-action-renameoption").click(onOptionRenameClick);
		$(".cfield-action-deleteoption").click(onOptionDeleteClick);
	}
	var onOptionRenameClick = function() {
		var jTitle = $("#cfield-tr-selecttr-" + this.id.split("-")[3] + " td:nth-child(1)");
		var title = prompt('New title of "' + jTitle.html() + '"');
		if(title == undefined || title == null) {
			return;
		}
		else if(title == "") {
			alert("The title cannot be blank.");
			return;
		}
		jTitle.html(title);
		onFieldChangeEvent(this.id.split("-")[3]);
	}
	var onOptionDeleteClick = function() {
		var id = this.id.split("-")[3];
		if(!confirm("Are you want to delete \""+$("#cfield-tr-selecttr-" + id + " td:nth-child(1)").html()+"\"? All users who have used it (if any) will have it removed.")) {
			return;
		}
		$("#cfield-tr-selecttr-" + id).remove();
	}
	var onOptionUpClick = function() {
		var clicked = $("#cfield-tr-selecttr-" + this.id.split("-")[3]);
		if(clicked.prevAll().length > 0) {
			clicked.prev().before(clicked.htmlInclusive());
			clicked.remove();
			bindActionsAgain();
			onFieldChangeEvent(this.id.split("-")[3]);
		}
	}
	var onOptionDownClick = function() {
		var clicked = $("#cfield-tr-selecttr-" + this.id.split("-")[3]);
		if(clicked.nextAll().length > 0) {
			clicked.next().after(clicked.htmlInclusive());	
			clicked.remove();
			bindActionsAgain();
			onFieldChangeEvent(this.id.split("-")[3]);
		}
	}
	$(".cfield-action-newoption").click(function() {
		var id = this.id.split("-")[3];
		var title = prompt("The title of your new option:");
		if(title == undefined || title == null || title == "") {
			return;
		}
		// Low chance of collision. But there /is/ a chance.
		var rand = Math.floor(Math.random()*1000000);
		$("#cfield-tbody-selectoptions-" + id).append('<tr id="cfield-tr-selecttr-'+rand+'"><td>'+title+'</td><td><div style="text-align:right;font-weight:bold;width:100%;"><a id="cfield-action-upoption-'+rand+'" class="cfield-action-upoption" href="javascript:void(0);">[Up]</a> <a id="cfield-action-downoption-'+rand+'" class="cfield-action-downoption" href="javascript:void(0);">[Down]</a> <a id="cfield-action-renameoption-'+rand+'" class="cfield-action-renameoption" href="javascript:void(0);">[Rename]</a> <a id="cfield-action-deleteoption-'+rand+'" class="cfield-action-deleteoption" href="javascript:void(0);">[Delete]</a></div></td></tr>');
		// Re-bind events to new elements
		bindActionsAgain();
		onFieldChangeEvent(id);
	});
	$(".cfield-field").change(onFieldChangeEvent);
});
//]]>
</script>

<a href="javascript:void(0)" id="newCustomFieldLink"><strong><img src="<ICONDIR>add.png" /> New Custom Field</strong></a>
