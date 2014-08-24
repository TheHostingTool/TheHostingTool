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
.packageIcon {
    cursor: pointer;
    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
}
.template-tooltip {
    vertical-align: middle;
    padding-left: 5px;
}
.packagebox label, .packagebox a {
    font-family: "Lucida Grande", "Lucida Sans", "Trebuchet MS", Helvetica, Arial, Verdana, sans-serif;
}
.packagebox td {
    font-size: 12px;
}
.hiddenFieldBox input[type=text], .hiddenFieldBox textarea {
    font-size: 12px;
    width: 82%;
}
.hiddenFieldBox select {
    font-size: 14px;
}
@media screen and (-webkit-min-device-pixel-ratio:0) {
    .hiddenFieldBox select {
        font-size: 16px;
    }
}
.hiddenFieldBox input[type=checkbox] {
    font-size: 16px;
    width: 16px;
    height: 16px;
}
.hiddenFieldBox button {
    font-size: 12px;
}
.pkgEditorLeftTd {
    width: 50%;
}
.pkgEditorLeftTable td:first-child {
    width: 25%;
}
.pkgEditorLeftTable {
    width: 100%;
}
.pkgEditorRightTd {
    vertical-align: top;
}
.pkgEditorRightTable label {
    vertical-align: middle;
    display: inline-block;
    width: 78%;
}
.pkgEditorRightTable input[type=checkbox] {
    vertical-align: middle;
    font-size: 16px;
    width: 16px;
    height: 16px;
}
.pkgEditorLeftChkbxTable {
    text-align: center;
}
.pkgEditorLeftChkbxTable label {
    vertical-align: middle;
    display: inline-block;

    width: 60%;
}
.pkgEditorLeftChkbxTable input, .pkgEditorLeftChkbxTable a {
    display: inline-block;
    vertical-align: middle;
}
.savePkgBtnDiv {
    text-align: center;
}
.savePkgBtnDiv button {
    font-size: 13px;
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
    right: 5px;
}
label {
    font-weight: bold;
}
.disabledGrey {
    -webkit-filter: grayscale(100%); /* Chrome 19+, Safari 6+, MobileSafari 6+ */
    /* Firefox 10+, Firefox on Android */
    filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale");
}
.cke_chrome {
    display: inline-block;
    width: 82%;
}
#pkgTopActions {
    margin: 3px 0 5px 5px;
}
.topActions {
    font-weight: bold;
    margin-right: 10px;
}
.topActions > img {
    vertical-align: middle;
}
.topActions > span {
    vertical-align: middle;
}
#shrinkEditor {
    display: none;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    var initialPackages = %INITPKGS%;
    var initialServers = %INITSRVS%;
    var initialCFields = %INITCFLD%;
    var initialPkgTypes = %INITTYPS%;
    var iconDir = "<ICONDIR>";
    var newId = 0;
    //$.fn.htmlInclusive = function() { return $('<div />').append($(this).clone()).html(); }

    var spinnerOpts = {
        lines: 12, // The number of lines to draw
        length: 3, // The length of each line
        width: 2, // The line thickness
        radius: 4, // The radius of the inner circle
        color: '#000', // #rbg or #rrggbb
        speed: 2, // Rounds per second
        trail: 60, // Afterglow percentage
        shadow: false // Whether to render a shadow
    };
    var startSpin = function() {
        $("#orderSpinner").spin(spinnerOpts);
        $("#orderSpinnerDiv").fadeIn("fast");
    };

    var stopSpin = function() {
            $("#orderSpinnerDiv").fadeOut("fast", function() {
                $("#orderSpinner").spin();
            });
    };

    // Populate type selector
    $.each(initialPkgTypes, function(index, entry) {
        $("#pkg-field-type-5id5").append($("<option>", {value: entry.tid}).text(entry.name));
        if(entry.fields === false) {
            return;
        }
        var html = $("#pkgTypeHtmlTemplate").html();
        html = html.replace(/-5type5/g, "-" + entry.tid);
        $("#pkg-typetd-5id5").append(html);
        $.each(entry.fields, function(index2, entry2) {
            var idformat = "pkg-field-typefield-5id5-" + entry.tid + "-" + entry2.id;
            var classformat = "pkg-field pkg-field-5id5 pkg-typefield pkg-field-typefield-5id5-" + entry.tid;
            var tr = $("<tr>");
            tr.append($("<td>").append($("<label>", {for: idformat}).text(entry2.name + ":")));
            var td = $("<td>");
            switch(entry2.type) {
                case "select":
                    var select = $("<select>", {
                        id: idformat,
                        class: classformat
                    });
                    $.each(entry2.options, function(index3, entry3) {
                        select.append($("<option>", {value: index3}).text(entry3));
                    });
                    if(entry2.hasOwnProperty("default")) {
                        // TODO for some stupid reason you can't set the default until later
                    }
                    td.append(select);
                    break;
                default:
                    td.append($("<input>", {
                        type: entry2.type,
                        id: idformat,
                        class: classformat
                    }));
            }
            tr.append(td);
            $("#pkg-typefields-tbody-" + entry.tid + "-5id5").append(tr);
        });
    });

    // Populate server selector
    $.each(initialServers.srvs, function(index, entry) {
        $("#pkg-field-server-5id5").append($("<option>", {disabled: true}).text("---" + initialServers.srvtypes[index] + "---"));
        $.each(entry, function(index2, entry2) {
            $("#pkg-field-server-5id5").append($("<option>", {value: entry2.id}).text(entry2.name));
        });
    });

    // Populate custom fields table
    for(var i = 0; i < Math.ceil(initialCFields.length / 3); i++) {
        var tr = $("<tr>");
        for(var innerI = 0; innerI < 3; innerI++) {
            var cfIndex = i * 3 + innerI;
            if(!initialCFields.hasOwnProperty(cfIndex)) {
                break;
            }
            var td = $("<td>");
            td.append($("<label>", {
                for: "pkg-field-custom-5id5-" + initialCFields[cfIndex].id
            }).text(initialCFields[cfIndex].name));
            td.append($("<input>", {
                type: "checkbox",
                id: "pkg-field-custom-5id5-" + initialCFields[cfIndex].id,
                class: "pkg-field pkg-field-5id5 pkg-field-custom pkg-field-custom-5id5"
            }));
            tr.append(td);
        }
        $("#pkgEditorRightTable-5id5 tbody").append(tr);
    }

    var rebindEvents = function() {
        $(".pkg-field").unbind("change", onPackageFieldChanged);
        $(".pkg-field").bind("change", onPackageFieldChanged);

        $(".savePkgBtn").unbind("click", onSavePackageClick);
        $(".savePkgBtn").bind("click", onSavePackageClick);

        $(".packageName").unbind("click", onPackageNameClick);
        $(".packageEditBtn").unbind("click", onPackageNameClick);
        $(".packageDelBtn").unbind("click", onPackageDeleteClick);

        $(".packageName").one("click", onPackageNameClick);
        $(".packageEditBtn").one("click", onPackageNameClick);
        $(".packageDelBtn").bind("click", onPackageDeleteClick);
    };

    var initEditor = function($element, defaultHtml) {
        var arglen = arguments.length;
        $element.ckeditor(function(textarea) {
            var editor = this;
            var firstCall = false;
            if(arglen > 1) {
                firstCall = true;
                $(textarea).val(defaultHtml);
            }
            editor.on("change", function() {
                // Even though we set the value of the textarea first the onChange event is still somehow
                // being called afterwards for the initial data. To workaround, we need to ignore the first call.
                if(firstCall) {
                    firstCall = false;
                    return;
                }
                onPackageFieldChanged.call(textarea);
            });
            editor.on("mode", function() {
                if(this.mode == "source") {
                    var editable = editor.editable();
                    editable.attachListener(editable, "input", function() {
                        onPackageFieldChanged.call($element[0]);
                    });
                }
            });
        }, {
            toolbarGroups: [
                { name: "document",	   groups: [ "mode", "document", "doctools" ] },
                { name: "editing",     groups: [ "find", "selection", "spellchecker" ] },
                { name: "basicstyles", groups: [ "basicstyles", "cleanup" ] },
                { name: "paragraph",   groups: [ "list", "indent", "blocks", "align", "bidi" ] },
                { name: "links" },
                { name: "insert" },
                { name: "colors" },
                { name: "tools" },
                { name: "others" },
                { name: "about" }
            ],
            removeButtons: "Cut,Copy,Paste,Undo,Redo,Anchor,Underline,Strike,Subscript,Superscript"
        });
    };

    var populatePackage = function(entry, insertAfter) {
        // Get template HTML
        var html = $("#pkgHtmlTemplate").html();
        html = html.replace(/-5id5/g, "-" + entry.id);
        html = html.replace(/5iconIsGrey5/g, entry.disabled ? "disabledGrey" : "");
        html = html.replace(/class="template-tooltip template-tooltip2"/g, "class=\"template-tooltip tooltip-" + entry.id +"\"")
        if(typeof(insertAfter) != "undefined") {
            $(insertAfter).after(html);
        } else {
            $("#sortablePackages").append(html);
        }
        $("#hiddenFieldBox-" + entry.id).hide();
        $("#packagebox-" + entry.id).show();
        $("#packageName-" + entry.id + " > a").html(entry.name);
        $("#pkg-field-name-" + entry.id).val(entry.name);
        $("#pkg-field-backend-" + entry.id).val(entry.backend);
        initEditor($("#pkg-field-desc-" + entry.id), entry.description);
        $("#pkg-field-type-" + entry.id).val(entry.type);
        $("#pkg-field-server-" + entry.id).val(entry.server);
        $("#pkg-field-admin-" + entry.id).prop("checked", entry.admin);
        $("#pkg-field-resell-" + entry.id).prop("checked", entry.reseller);
        $("#pkg-field-dmains-" + entry.id).prop("checked", entry.domains);
        $("#pkg-field-hidden-" + entry.id).prop("checked", entry.hidden);
        $("#pkg-field-disabled-" + entry.id).prop("checked", entry.disabled);
        $("#savePkgBtnDiv-" + entry.id).hide();
        var typefields = $("#pkg-typefields-" + entry.type + "-" + entry.id);
        if(typefields.length > 0) {
            typefields.show();
        }
        if(entry.additional != null && entry.additional.hasOwnProperty("types")) {
            $.each(entry.additional.types, function(typeid, typevals) {
                $.each(typevals, function(fieldkey, fieldval) {
                    $("#pkg-field-typefield-" + entry.id + "-" + typeid + "-" + fieldkey).val(fieldval);
                });
            });
        }
        entry.custom.forEach(function(cf) {
            $("#pkg-field-custom-" + entry.id + "-" + cf).prop("checked", true);
        });
        doTooltip(".tooltip-" + entry.id, true);
    };

    // Populate packages
    $.each(initialPackages, function(index, entry) {
        populatePackage(entry);
    });

    var onSortableStart = function(event, ui) {
        var id = ui.item.context.id.split("-")[1];
        $("#pkg-field-desc-" + id).ckeditorGet().destroy();
    };

    var onSortableStop = function(event, ui) {
        var id = ui.item.context.id.split("-")[1];
        initEditor($("#pkg-field-desc-" + id));
    };

    var onSortableChange = function(event, ui) {
        $("#saveOrderChangesDiv").slideDown();
    };

    $("#sortablePackages").sortable({
        start: onSortableStart,
        stop: onSortableStop,
        change: onSortableChange
    });

    var onPackageNameClick = function() {
        var $this = this;
        var id = $this.id.split("-")[1];
        $("#hiddenFieldBox-" + id).slideToggle(function() {
            $($this).one("click", onPackageNameClick);
        });
    };

    $(".packageName").one("click", onPackageNameClick);
    $(".packageEditBtn").one("click", onPackageNameClick);

    var onPackageDeleteClick = function() {
        var id = this.id.split("-")[1];

        if(id.indexOf("new") !== -1) {
            // New package, just delete the DOM element
            $("#packagebox-" + id).slideUp(function() {
                $(this).remove();
            });
            return;
        }

        var packageName = $("#pkg-field-name-" + id).val();
        if(!confirm("Deleting " + packageName + " will unlink it from any users currently using this package." +
        " If you just wish to prevent further signups select the Disabled option in the package settings. It is" +
        " safe to delete this package if there are no clients using it.\r\n\r\nAre you sure" +
        " you want to delete " + packageName + "?")) {
            return;
        }
        startSpin();
        var json = {
            operation: "delete",
            id: id
        };
        json[csrfMagicName] = csrfMagicToken;
        $.post("<AJAX>?function=acpPackages", json, function(data) {
            if(data) {
                $("#packagebox-" + id).slideUp(function() {
                    $(this).remove();
                });
            } else {
                alert("An error occured while deleting " + packageName + ".");
            }
            stopSpin();
        });
    };

    $(".packageDelBtn").bind("click", onPackageDeleteClick);

    $("#newPackage").click(function() {
        var $newId = newId;
        newId++;
        var html = $("#pkgHtmlTemplate").html();
        html = html.replace(/-5id5/g, "-new" + $newId);
        html = html.replace(/5iconIsGrey5/g, "");
        html = html.replace(/class="template-tooltip template-tooltip2"/g, "class=\"template-tooltip tooltip-new" + $newId +"\"")
        $("#sortablePackages").prepend(html);
        $("#packageIcon-new" + $newId).attr("src", iconDir + "package_green.png");
        doTooltip(".tooltip-new" + $newId, true);
        $("#savePkgBtn-new" + $newId).html("Create Package");
        initEditor($("#pkg-field-desc-new" + $newId));
        $("#packagebox-new" + $newId).slideDown();
        rebindEvents();
    });

    var onPackageFieldChanged = function() {
        var $this = $(this);
        var id = this.id.split("-")[3];
        var btnDiv = $("#savePkgBtnDiv-" + id);
        if(!btnDiv.is(":visible")) {
            btnDiv.slideDown();
        }

        if($this.hasClass("pkg-field-name")) {
            $("#packageName-" + id + " > a").fadeOut(function() {
                $(this).html($this.val()).fadeIn();
            });
            return;
        }

        if($this.hasClass("pkg-field-disabled")) {
            if($this.prop("checked")) {
                $("#packageIcon-" + id).addClass("disabledGrey");
                return;
            }
            $("#packageIcon-" + id).removeClass("disabledGrey");
            return;
        }

        if($this.hasClass("pkg-field-type")) {
            var typefields = $("#pkg-typefields-" + $this.val() + "-" + id);
            var oldfields = $(".pkg-typefieldsid-" + id + ":visible");
            if(oldfields.length > 0) {
                oldfields.slideUp();
            }
            if(typefields.length > 0) {
                typefields.slideDown();
            }
        }
    };

    $(".pkg-field").bind("change", onPackageFieldChanged);

    var onSavePackageClick = function() {
        startSpin();
        var $this = this;
        var id = $this.id.split("-")[1];
        $($this).prop("disabled", true).html("Saving...");

        var customFields = [];
        $(".pkg-field-custom-" + id).each(function(index, entry) {
            if(!$(entry).prop("checked")) {
                return;
            }
            customFields.push(parseInt(entry.id.split("-")[4], 10));
        });

        var typeFields = {};
        $(".pkg-field-typefield-" + id + "-" + $("#pkg-field-type-" + id).val()).each(function(index, entry) {
            typeFields[entry.id.split("-")[5]] = $(entry).val();
        });

        var json = {
            operation: "edit",
            id: id,
            name: $("#pkg-field-name-" + id).val(),
            backend: $("#pkg-field-backend-" + id).val(),
            desc: $("#pkg-field-desc-" + id).val(),
            type: $("#pkg-field-type-" + id).val(),
            server: $("#pkg-field-server-" + id).val(),
            admin: $("#pkg-field-admin-" + id).prop("checked"),
            reseller: $("#pkg-field-resell-" + id).prop("checked"),
            domain: $("#pkg-field-dmains-" + id).prop("checked"),
            hidden: $("#pkg-field-hidden-" + id).prop("checked"),
            disabled: $("#pkg-field-disabled-" + id).prop("checked"),
            custom: customFields,
            typefields: typeFields
        };
        json[csrfMagicName] = csrfMagicToken;

        if(json.name == "" || json.backend == "" || json.desc == "") {
            $($this).prop("disabled", false).html("Please complete all fields.");
            stopSpin();
            return;
        }

        if(id.indexOf("new") !== -1) {
            json["operation"] = "new";
        }

        $.post("<AJAX>?function=acpPackages", json, function(data) {
            if(json.operation == "new" && typeof(data) == "object") {
                var pkgjson = {
                    id: data.insertId,
                    name: json.name,
                    backend: json.backend,
                    description: json.desc,
                    type: json.type,
                    server: json.server,
                    admin: json.admin,
                    reseller: json.reseller,
                    domain: json.domain,
                    hidden: json.hidden,
                    disabled: json.disabled,
                    custom: customFields,
                    additional: {
                        types: {
                            // Added below
                        }
                    }
                };
                // Types can modify field values during the validation process
                pkgjson.additional.types[json.type] = data.typeFields === false ? {} : data.typeFields;
                populatePackage(pkgjson, "#packagebox-" + id);
                rebindEvents();
                $("#packagebox-" + id).slideUp(function() {
                    $(this).remove();
                });
                $("#hiddenFieldBox-" + data.insertId).slideDown();
            }
            else if(data === true) {
                $($this).prop("disabled", false).html("Saved!");
                setTimeout(function() {
                    $("#savePkgBtnDiv-" + id).slideUp(function() {
                        $($this).html("Save Changes");
                    });
                }, 3000);
            } else {
                $($this).prop("disabled", false).html("An error occured.");
            }
            stopSpin();
        });
    };

    $(".savePkgBtn").bind("click", onSavePackageClick);

    $("#saveOrderChangesBtn").click(function() {
        $(this).attr("disabled", "disabled");
        startSpin();
        $("#atBottomDiv").slideUp(function() {
            $(".savedDiv").hide();
            var order = [];
            var orderi = 0;
            $(".packagebox").each(function(index) {
                var ofbid = $(this).attr('id').split("-")[1];
                if(ofbid != "5id5") {
                    order[orderi++] = ofbid;
                }
            });
            var json = {
                operation: "order",
                order: order
            };
            json[csrfMagicName] = csrfMagicToken;
            $.post("<AJAX>?function=acpPackages", json, function(data) {
                if(data) {
                    $("#goodSavedDiv").show();
                }
                else {
                    alert(data);
                    $("#badSavedDiv").show();
                }
                $("#saveOrderChangesBtn").removeAttr("disabled");
                $("#atBottomDiv").slideDown();
                stopSpin();
            });
        });
    });

    var cssRightWidth = $("#right").width() + "px";
    // Attempt to nab raw CSS width value
    $.each(document.styleSheets, function(k1, stylesheet) {
        $.each(stylesheet.rules || stylesheet.cssRules, function(k2, rule) {
            if(rule instanceof CSSStyleRule && rule.selectorText.toLowerCase() == "#right") {
                cssRightWidth = rule.style.getPropertyValue("width");
            }
        });
    });

    $("#growEditor").click(function() {
        var $footer = $(".footer");
        $(this).fadeOut();
        $footer.fadeOut();
        $("#left").fadeOut(function() {
            $("#right").animate({
                width: "100%"
            });
            $footer.fadeIn();
            $("#shrinkEditor").fadeIn();
        });
    });

    $("#shrinkEditor").click(function() {
        var $footer = $(".footer");
        $(this).fadeOut();
        $footer.fadeOut();
        $("#right").animate({
            width: cssRightWidth
        }, function() {
            $("#left").fadeIn();
            $footer.fadeIn();
            $("#growEditor").fadeIn();
        });
    });
});
</script>
<div id="pkgTypeHtmlTemplate" class="hiddenStyle">
    <div id="pkg-typefields-5type5-5id5" class="pkg-typefields pkg-typefields-5type5 pkg-typefieldsid-5id5" style="display: none;">
        <table><tbody id="pkg-typefields-tbody-5type5-5id5"><!-- Added dynamically --></table></tbody>
    </div>
</div>
<div id="pkgHtmlTemplate" class="hiddenStyle">
    <div class="packagebox subborder sortableHandle " id="packagebox-5id5" style="display: none;">
    <div class="sub">
        <table width="100%" border="0" cellspacing="2" cellpadding="0"><tbody>
            <tr>
                <td><div style="font-weight: bold; height: 100%;" id="packageName-5id5" class="packageName"><img id="packageIcon-5id5" class="packageIcon 5iconIsGrey5" style="vertical-align: middle;" src="<ICONDIR>package.png"> <a href="javascript:void(0);">New Package</a></div></td>
                <td rowspan="2" align="right">
                    <a id="packageEditBtn-5id5" class="packageEditBtn" href="javascript:void(0);"><img class="template-tooltip template-tooltip2" title="Edit details" src="<ICONDIR>pencil.png"></a>
                    <a id="packageDelBtn-5id5" class="packageDelBtn" href="javascript:void(0);"><img class="template-tooltip template-tooltip2" title="Delete this package" src="<ICONDIR>delete.png"></a>
                </td>
            </tr>
        </tbody></table>
        <div id="hiddenFieldBox-5id5" class="hiddenFieldBox hiddenStyle" style="display: block;">
            <table width="100%" border="0" cellspacing="2" cellpadding="0"><tbody><tr><td class="pkgEditorLeftTd">
                <table class="pkgEditorLeftTable" border="0" cellspacing="2" cellpadding="0">
                    <tbody>
                    <tr>
                        <td><label for="pkg-field-name-5id5">Name:</label></td><td><input id="pkg-field-name-5id5" class="pkg-field pkg-field-5id5 pkg-field-name" value="New Package" type="text"><a href="javascript:void(0);"><img class="template-tooltip template-tooltip2" src="<ICONDIR>information.png" title="The user-visible name for the package."></a></td>
                    </tr>
                    <tr>
                        <td><label for="pkg-field-backend-5id5">Backend:</label></td><td><input id="pkg-field-backend-5id5" class="pkg-field pkg-field-5id5 pkg-field-backend" value="" type="text"><a href="javascript:void(0);"><img class="template-tooltip template-tooltip2" title="The backend name of the package the server refers to." src="<ICONDIR>information.png"></a></td>
                    </tr>
                    <tr>
                        <td><label for="pkg-field-desc-5id5">Description:</label></td><td><textarea id="pkg-field-desc-5id5" class="pkg-field pkg-field-5id5 pkg-field-desc"></textarea><a href="javascript:void(0);"><img class="template-tooltip template-tooltip2" title="The user-visible description of the package." src="<ICONDIR>information.png"></a></td>
                    </tr>
                    <tr>
                        <td><label for="pkg-field-type-5id5">Type:</label></td><td id="pkg-typetd-5id5">
                        <select id="pkg-field-type-5id5" class="pkg-field pkg-field-5id5 pkg-field-type"><!-- Added dynamically --></select>
                        <a href="javascript:void(0);"><img class="template-tooltip template-tooltip2" title="The type of package." src="<ICONDIR>information.png"></a>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="pkg-field-server-5id5">Server:</label></td><td>
                        <select id="pkg-field-server-5id5" class="pkg-field pkg-field-5id5 pkg-field-server"><!-- Added dynamically --></select>
                        <a href="javascript:void(0);"><img class="template-tooltip template-tooltip2" title="The server the package is on." src="<ICONDIR>information.png"></a>
                    </td>
                    </tr>
                </tbody></table>
                <table class="pkgEditorLeftChkbxTable" width="100%" border="0" cellspacing="2" cellpadding="0"><tbody>
                    <tr>
                        <td><label for="pkg-field-admin-5id5">Admin Validation:</label><input style="display: inline-block; vertical-align: middle;" id="pkg-field-admin-5id5" class="pkg-field pkg-field-5id5 pkg-field-admin" type="checkbox" value="1"><a href="javascript:void(0);"><img class="template-tooltip template-tooltip2" title="Require this package to be manually staff validated." src="<ICONDIR>information.png"></a></td>
                        <td><label for="pkg-field-resell-5id5">Reseller:</label><input id="pkg-field-resell-5id5" class="pkg-field pkg-field-5id5 pkg-field-resell" type="checkbox" value="1"><a href="javascript:void(0);"><img class="template-tooltip template-tooltip2" title="Mark this package as a reseller." src="<ICONDIR>information.png"></a></td>
                    </tr>
                    <tr>
                        <td><label for="pkg-field-dmains-5id5">Allow Domains:</label><input id="pkg-field-dmains-5id5" class="pkg-field pkg-field-5id5 pkg-field-dmains" type="checkbox" value="1"><a href="javascript:void(0);"><img class="template-tooltip template-tooltip2" title="Allow the use of custom domains, not just subdomains you have defined." src="<ICONDIR>information.png"></a></td>
                        <td><label for="pkg-field-hidden-5id5">Hidden:</label><input id="pkg-field-hidden-5id5" class="pkg-field pkg-field-5id5 pkg-field-hidden" type="checkbox" value="1"><a href="javascript:void(0);"><img class="template-tooltip template-tooltip2" title="Hide this package from the order form." src="<ICONDIR>information.png"></a></td>
                    </tr>
                    <tr>
                        <td><label for="pkg-field-disabled-5id5">Disabled:</label><input id="pkg-field-disabled-5id5" class="pkg-field pkg-field-5id5 pkg-field-disabled" type="checkbox" value="1"><a href="javascript:void(0);"><img class="template-tooltip template-tooltip2" title="Do not allow new clients to register for this package." src="<ICONDIR>information.png"></a></td>
                        <td><input type="text" value="Hidden URL here"></td>
                    </tr>
                </tbody></table>
            </td>
            <td class="pkgEditorRightTd" id="pkgEditorRightTd-5id5">
                <div style="text-align: center; font-weight: bold; font-size: 14px; font-family: Lucida Grande, sans-serif;">Custom Fields</div>
                <table class="pkgEditorRightTable" id="pkgEditorRightTable-5id5" width="100%" border="0" cellpadding="5"><tbody style="text-align: center; font-size: 12px;">
                    <tr>
                        <th width="33%"></th>
                    </tr>
                </tbody></table>
            </td>
            </tr>
        </tbody></table>
        <div id="savePkgBtnDiv-5id5" class="savePkgBtnDiv hiddenStyle"><button class="savePkgBtn" id="savePkgBtn-5id5">Save Changes</button></div>
        </div>
    </div>
    </div>
</div>
<div id="orderSpinnerDiv" class="hiddenStyle"><a id="orderSpinner" class="orderSpinner"></a></div>
<div id="pkgTopActions">
    <a href="javascript:void(0);" id="newPackage" class="topActions"><img src="<ICONDIR>add.png" />&nbsp;<span>New Package</span></a>
    <a href="javascript:void(0);" id="growEditor" class="topActions"><img src="<ICONDIR>arrow_out.png" />&nbsp;<span>Grow Editor</span></a>
    <a href="javascript:void(0);" id="shrinkEditor" class="topActions"><img src="<ICONDIR>arrow_in.png" />&nbsp;<span>Shrink Editor</span></a>
</div>
<div id="sortablePackages">
</div>
<div id="atBottomDiv" style="text-align: center;">
    <div id="saveOrderChangesDiv" class="hiddenStyle"><button id="saveOrderChangesBtn">Save Order Changes</button></div>
    <div id="goodSavedDiv" class="savedDiv hiddenStyle">The order of your packages have been saved!</div>
    <div id="badSavedDiv" class="savedDiv hiddenStyle">There was a problem when trying to save the order of your packages...</div>
</div>
