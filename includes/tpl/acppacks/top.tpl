		<style type="text/css">
		.column { width: 420px; }
		.portlet { margin: 0 1em 1em 0; }
		.portlet-header { margin: 0.3em; padding-bottom: 4px; padding-left: 0.2em; }
		.portlet-header .ui-icon { float: right; }
		.portlet-content { padding: 0.4em; }
		.portlet-icons { float: right; }
		.ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 50px !important; }
		.ui-sortable-placeholder * { visibility: hidden; }
		.center { margin-left: auto; margin-right: auto; }
		.right { float: right; }
                .hidden { display: none; }
                .inputClass {  }
		</style>
		<script type="text/javascript">
		//<![CDATA[
                var ultimateVar = new Array();

                function inputChangesSlide(id) {
                    $("#saveChangesDiv-" + id).slideDown(500);
                }
		$(document).ready(function(){
			$(".column").sortable({
				connectWith: '.column'
			});

			$(".portlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
				.find(".portlet-header")
					.addClass("ui-widget-header ui-corner-all")
					.prepend('<span class="ui-icon ui-icon-plusthick"></span>')
					.end()
				.find(".portlet-content");

			$(".portlet-header .ui-icon").click(function() {
				$(this).toggleClass("ui-icon-minusthick");
				$(this).parents(".portlet:first").find(".portlet-content").toggle();
			});

                        $(".inputClass").change(function() {
                            var idSplit = this.id.toString().split("-");
                            var id = idSplit[1];
                            var front = idSplit[0];
                            if(front == "inputType") {
                                var Type = $("#" + front + "-" + id).val();
                                if($("#" + front + "-" + id).val() != "free") {
                                    open("<AJAX>?function=acpPackages&action=typeInfo&type=" + Type + "&id=" + id,
                                    'popup-' + id, 'width=250,height=150');
                                }
                            }
                            inputChangesSlide(id);
                        });
                        
                        $(".deleteIcon").click(function() {
                            var id = this.id.toString().split("-")[1];
                            var result = confirm("Are you sure you wish to delete this package? " +
                            "It's gonna mess a lot of things up if you have clients connected to this package.");
                            if(result) {
                                $.get("<AJAX>?function=acpPackages&action=delete&id=" + id, function(data) {
                                   $("#portlet-" + id).slideUp(500, function() {
                                    $("#portlet-" + id).remove();
                                    $("#noticeChanges").slideDown(500);
                                   });
                                });
                            }
                        });

                        $(".saveChangesBtn").click(function() {
                            var id = this.id.toString().split("-")[1];
                            $("#saveChangesBtn-" + id).blur();
                            $("#saveChangesDiv-" + id).slideUp(500, function() {
                                tinyMCE.triggerSave();
                                var name = $("#inputName-" + id).val();
                                var backend = $("#inputBackend-" + id).val();
                                var description = $("#inputDescription-" + id).val();
                                var type = $("#inputType-" + id).val();
                                var server = $("#inputServer-" + id).val();
                                var signup = $("#inputSignup-" + id).val();
                                var monthly = $("#inputMonthly-" + id).val();
                                var additional = ultimateVar[id];
                                if($("#inputVal-" + id).is(':checked')) {
                                    var val = 1;
                                }
                                else {
                                    var val = 0;
                                }
                                if($("#inputReseller-" + id).is(':checked')) {
                                    var reseller = 1;
                                }
                                else {
                                    var reseller = 0;
                                }
                                if(id == "new") {
                                    $.post("<AJAX>?function=acpPackages", { action: "add", "name": name,
                                        "backend": backend, "description": description, "id": id,
                                         "type": type, "val": val, "reseller": reseller,
                                            "server": server, "additional": additional }, function(data) {
                                            window.location.reload();
                                        });
                                }
                                else {
                                    $.post("<AJAX>?function=acpPackages", { action: "edit", "name": name,
                                        "backend": backend, "description": description, "id": id,
                                        "type": type, "val": val, "reseller": reseller, "additional": additional }, function(data) {
                                        $("#saveChangesDiv-" + id).slideDown(500);

                                        });
                                }
                                $("#noticeChanges").slideDown(500);
                            });
                        });

                        $("#newPackage").click(function() {
                            $("#newWrapper").slideToggle(500);
                        });

                        $("#kthxSaveOrder").click(function() {
                        $("#" + this.id).blur();
                            $("#buttonSpace").slideUp(500, function() {
                                var id = this.id;
                                var array2 = $(".column").sortable('toArray');
                                var array2Length = array2.length - 1;
                                var url = "<AJAX>?function=acpPackages";
                                var submit = null;
                                for(var i in array2) {
                                    array2[i] = array2[i].split("-")[1];
                                }
                                for(var i in array2) {
                                    if(i == 0) {
                                        submit = array2[i];
                                    }
                                    else {
                                        submit = submit + "-" + array2[i];
                                    }
                                }
                                $.post(url, { action: "order", order: submit }, function(data) {
                                    $("#buttonSpace").slideDown(500);
                                    $("#noticeChanges").slideDown(500);
                                });
                            });
                        });


			//$(".column").disableSelection();

                        //Uncomment the following line if you want the portlets
                        //to start closed instead of open.
                        //$(".portlet-header .ui-icon").click();

		});
                    function plzChange(inst) {
                        var kthxId = inst.id.split('-')[1];
                        if(kthxId === undefined) {
                            inputChangesSlide("new");
                        }
                        else {
                            inputChangesSlide(kthxId);
                        }
                        inputChangesSlide();
                    }
tinyMCE.init({
    skin : "o2k7",
    mode: "textareas",
    theme: "simple",
    width: "200",
    onchange_callback : "plzChange"
});

function transfer(id, stuff) {
    ultimateVar[id] = stuff;
}
		//]]>
		</script>

<a href="javascript:void(0)" id="newPackage"><strong><img src="<ICONDIR>add.png" /> Add package.</strong></a>

	<div style="width: 420px;" id="newWrapper" class="center hidden"><div class="portlet" id="portlet-new">
		<div class="portlet-header">New Package</div>
		<div class="portlet-content">
			<table>
                        <tbody>
                            <tr>
                                    <td><label for="inputName-new">Name:</label></td>
                                    <td><input class="inputClass" type="text" name="name" id="inputName-new" /></td>
                                    <td><a class="tooltip" title="The User-Friendly version of the package name."><img src="<ICONDIR>eye.png" class="tooltip" alt="Eye" /></a></td>
                            </tr>
                            <tr>
                                    <td><label for="inputBackend-new">Backend:</label></td>
                                    <td><input class="inputClass" type="text" class="tooltip" name="link" id="inputBackend-new" /></td>
                                    <td><a class="tooltip" title="The name of your package as it appears in your hosting software. WHM, DirectAdmin, etc."><img src="<ICONDIR>cog.png" alt="Cog" /></a></td>
                            </tr>
                            <tr>
                                    <td><label for="inputDescription-new">Description:</label></td>
                                    <td><textarea id="inputDescription-new" rows="3"></textarea></td>
                                    <td><a class="tooltip" title="Describe your package to your customer."><img src="<ICONDIR>page.png" alt="Page" /></a></td>
                            </tr>
                            <tr>
                                    <td>Server:</td>
                                    <td>%SERVER%</td>
                            </tr>
                            <tr>
                                    <td><label for="inputType-new">Type:</label></td>
                                    <td>%TYPES%</td>
                                    <td><a class="tooltip" title="What kind of package is this?"><img src="<ICONDIR>brick.png" alt="Brick" /></a></td>
                            </tr>
                            <tr>
                                    <td><label for="inputVal-new">Admin Validation:</label></td>
                                    <td><input name="inputVal-new" class="inputClass" type="checkbox" id="inputVal-new" value="1" /></td>
                                    <td><a class="tooltip" title="Does this package require admin validation?"><img src="<ICONDIR>user_suit.png" alt="Suit" /></a></td>
                            </tr>
                            <tr>
                                    <td><label for="inputReseller-new">Reseller:</label></td>
                                    <td><input name="inputReseller-new" class="inputClass" type="checkbox" id="inputReseller-new" value="1" /></td>
                                    <td><a class="tooltip" title="Is this package a reseller?"><img src="<ICONDIR>user_add.png" alt="Add" /></a></td>
                            </tr>
                        </tbody>
			</table>
                        <div align="center" class="hidden saveChangesDiv" id="saveChangesDiv-new"><button class="saveChangesBtn" id="saveChangesBtn-new">Save Changes</button></div>
		</div>
	</div></div>

<div class="column center">