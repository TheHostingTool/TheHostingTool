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
#orderSpinnerDiv {
	float: right;
	width: 16px;
	position: relative;
	top: 10px;
}
</style>
<script type="text/javascript">
//<![CDATA[
$("document").ready(function() {
	var spinnerOpts = {
		lines: 12, // The number of lines to draw
		length: 3, // The length of each line
		width: 2, // The line thickness
		radius: 4, // The radius of the inner circle
		color: '#000', // #rbg or #rrggbb
		speed: 1, // Rounds per second
		trail: 60, // Afterglow percentage
		shadow: false // Whether to render a shadow
	};
	$.fn.htmlInclusive = function() { return $('<div />').append($(this).clone()).html(); }
	
	var startSpin = function() {
		$("#orderSpinner").spin(spinnerOpts);
		$("#orderSpinnerDiv").fadeIn("fast");
	}
	
	var stopSpin = function() {
			$("#orderSpinnerDiv").fadeOut("fast", function() {
				$("#orderSpinner").spin();
			});
	}
	
	var lockBox = function(id) {
		$("#cfield-field-title-" + id).attr("readonly", "readonly");
		$("#cfield-field-description-" + id).attr("readonly", "readonly");
		$("#cfield-field-typelist-" + id).attr("disabled", "disabled");
		$("#cfield-field-defaultvalue-" + id).attr("readonly", "readonly");
		$("#cfield-field-regex-" + id).attr("readonly", "readonly");
		$("#cfield-field-required-" + id).attr("disabled", "disabled");
		$("#saveBtn-" + id).attr("disabled", "disabled");
	}
	
	var unlockBox = function(id) {
		$("#saveBtn-" + id).removeAttr("disabled");
		$(".cfield-field-" + id).removeAttr("disabled");
		$(".cfield-field-" + id).removeAttr("readonly");
	}
	
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
	
	$(".orderDelBtn").click(function() {
		var id = this.id.split("-")[1];
		var name = $("#orderTitle-" + id).html();
		if(confirm("Are you sure you want to delete the \""+name+"\" field?\n"
		+ "This will also remove any user data associated with it.")) {
			lockBox(id);
			startSpin();
			$.post("<AJAX>?function=deleteCustomField", {
				"id": id,
				__tht_csrf_magic: csrfMagicToken
			}, function (data) {
				if(data == "1") {
					$("#orderfieldbox-" + id).slideUp(function() {
						$("#orderfieldbox-" + id).remove();
						stopSpin();
					});
				}
				else {
					unlockBox(id);
					stopSpin();
				}
			});
		}
	});
	
	$("#saveOrderChangesBtn").click(function() {
		$(this).attr("disabled", "disabled");
		startSpin();
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
				stopSpin();
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
		lockBox(id);
		startSpin();
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
				unlockBox(id);
				stopSpin();
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
<div id="orderSpinnerDiv" class="hiddenStyle"><a id="orderSpinner" class="orderSpinner"></a></div>
