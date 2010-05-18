		<style type="text/css">
		.column { width: 320px; }
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
		</style>
		<script type="text/javascript">
		//<![CDATA[
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
                            var id = this.id.toString().split("-")[1];
                            $("#saveChangesDiv-" + id).slideDown(500)
                        });

                        $(".deleteIcon").click(function() {
                            var id = this.id.toString().split("-")[1];
                            var result = confirm("Are you sure you wish to delete this NavBar link?");
                            if(result) {
                                $.get("<AJAX>?function=navbar&action=delete&id=" + id, function(data) {
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
                                var name = $("#inputName-" + id).val();
                                var icon = $("#inputIcon-" + id).val();
                                var link = $("#inputLink-" + id).val();
                                if(id == "new") {
                                    $.post("<AJAX>?function=navbar", { action: "add", "name": name,
                                        "icon": icon, "link": link }, function(data) {
                                            window.location.reload();
                                        });
                                }
                                else {
                                    $.post("<AJAX>?function=navbar", { action: "edit", "name": name,
                                        "icon": icon, "link": link, "id": id }, function(data) {
                                        $("#saveChangesDiv-" + id).slideDown(500);
                                        });
                                }
                                $("#noticeChanges").slideDown(500);
                            });
                        });

                        $("#newNavLink").click(function() {
                            $("#newWrapper").slideToggle(500);
                        });

                        $("#kthxSaveOrder").click(function() {
                        $("#" + this.id).blur();
                            $("#buttonSpace").slideUp(500, function() {
                                var id = this.id;
                                var array2 = $(".column").sortable('toArray');
                                var array2Length = array2.length - 1;
                                var url = "<AJAX>?function=navbar";
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
		//]]>
		</script>

<a href="javascript:void(0)" id="newNavLink"><strong><img src="<ICONDIR>add.png" /> Add NavBar link.</strong></a>

	<div style="width: 320px;" id="newWrapper" class="center hidden"><div class="portlet" id="portlet-new">
		<div class="portlet-header">New NavBar Link</div>
		<div class="portlet-content">
			<table>
				<tr>
					<td><label for="inputName-new">Name:</label></td>
					<td><input class="inputClass" type="text" name="name" id="inputName-new" /></td>
					<td><a class="tooltip" title="The text your users see in the navbar."><img src="<ICONDIR>eye.png" class="tooltip" alt="Eye" /></a></td>
				</tr>
				<tr>
					<td><label for="inputIcon-new">Icon:</label></td>
					<td><input class="inputClass" type="text" class="tooltip" name="icon" id="inputIcon-new" /></td>
					<td><a class="tooltip" title="The icons that your users see next to the navigation link. Icons are located in the 'themes/icons' folder. Example: stop.png"><img src="<ICONDIR>picture.png" alt="Picture" /></a></td>
				</tr>
				<tr>
					<td><label for="inputLink-%ID%">Link:</label></td>
					<td><input class="inputClass" type="text" class="tooltip" name="link" id="inputLink-new" /></td>
					<td><a class="tooltip" title="The relative URL for the navagation link. Example: admin/"><img src="<ICONDIR>link.png" alt="Link" /></a></td>
				</tr>
			</table>
                        <div align="center" class="hidden saveChangesDiv" id="saveChangesDiv-new"><button class="saveChangesBtn" id="saveChangesBtn-new">Save Changes</button></div>
		</div>
	</div></div>

<div class="column center">