<script type="text/javascript">
var step = 1;
var form = document.getElementById("order");
var wrong = '<img src="<URL>themes/icons/cross.png">';
var right = '<img src="<URL>themes/icons/accept.png">';
var loading = '<img src="<URL>themes/icons/ajax-loader.gif">';
var working = '<div align="center"><img src="<URL>themes/icons/working.gif"></div>';
var result;
var packageId;
var usingDomain;
var packageSubdomains; // Array if subdomains, false if none
var useNewSlide = %USENEWEFFECT%;
var pkgTableOffset;
var pkgTableWidth;

$(document).ready(function() {

    // Select sizing workaround
    if(/webkit/.test(navigator.userAgent.toLowerCase())) {
        $("#step-3 select").css("border-color", "#999");
    }

    // Keeps track of the ToS checkbox
    var termsAgreed = false;
    $("#chkAgreeToS").change(function() {
        termsAgreed = this.checked;
        if(step == 2) {
            if(termsAgreed) {
                $("#nextStepButton").removeAttr('disabled');
                return;
            }
            $("#nextStepButton").attr('disabled', 'disabled');
        }
    });

    // Moves us from the current step to the desired step
    var gotoStep = function(goto, callback) {
        if(useNewSlide) {
            $("#step-" + step).slideUp(); // Pretty cool (and fast) effect
            $("#step-" + goto).slideDown(callback); // Can be somewhat disorienting, disabled by default
        } else {
            if(step == 1) {
                $("#step-" + step).fadeOut(function() {
                    $("#left").fadeIn();
                    $("#right").show();
                    $("#step-" + goto).fadeIn(callback);
                });
            } else {
                $("#step-" + step).fadeOut(function() {
                    $("#step-" + goto).fadeIn(callback);
                });
            }
        }
        step = goto;
    };


    $("#username").change(function(event) {
        this.value = this.value.toLowerCase();
        check('username', this.value);
    });

    // When the user clicks the Order button on a package
    $(".pkgOrderButton").click(function() {
        $(".pkgOrderButton").attr('disabled', 'disabled');
        $(this).val("Loading...");
        var id = this.id.split("-")[1];

        // TTFN
        $(".footer").fadeOut();

        // Get offset and solidify element with absolute positioning
        pkgTableOffset = $("#packageTable-" + id).offset();
        pkgTableWidth = $("#packageTable-" + id).width();
        $("#packageTable-" + id).css("width", $("#packageTable-" + id).width() + "px")
                .css("top", pkgTableOffset.top + "px")
                .css("left", pkgTableOffset.left + "px")
                .css("z-index", "9999")
                .css("position", "absolute");

        // Hacky, but it works pretty damn well
        var step1Offset = $("#step-1").offset();

        // The magic begins
        $("#welcomeTable").fadeOut();
        $(".packageTable").not("#packageTable-" + id).fadeOut();
        $("#packageTable-" + id).animate({ top: step1Offset.top, left: step1Offset.left }, null, function() {
            $(this).appendTo("#newLeft")
                    .css("position", "").css("top", "").css("left", "")
                // 6 is a magic number here. 2 + 2 for padding and 1 + 1 for border
                    .animate({ width: $("#newLeft").width() - 6 + "px" }, function() {
                        // Remove width property altogether. It was only for animation.
                        $(this).css("width", "");
                        $("#step-2").slideDown(function() {
                            // Welcome back
                            $("#steps").fadeIn();
                            $(".footer").fadeIn();
                        });
                    });

            // Readmore.js
            var subcon = $("#packageTable-" + id + " .subcontent");
            if(subcon.height() > 150) {
                subcon.readmore({
                    speed: 750,
                    embedCSS: false,
                    startOpen: true,
                    moreLink: '<div><a href="javascript:void(0);">More...</a></div>',
                    lessLink: '<div><a href="javascript:void(0);">Less...</a></div>'
                });

                $("#packageTable-" + id + " .readmore-js-toggle").trigger('click');
                $("#packageTable-" + id + " .readmore-js-toggle").slideDown(function() {
                    // Change the links on-the-fly
                    subcon.data().plugin_readmore.options.moreLink = '<div style="display: block;"><a href="javascript:void(0);">More...</a></div>';
                    subcon.data().plugin_readmore.options.lessLink = '<div style="display: block;"><a href="javascript:void(0);">Less...</a></div>';
                });
            }
            $("#step-1").hide();
            $("#right").show();
            step = 2;
            $("#chkAgreeToS").change();
        });

        //$("#packageTable-" + id).css("top", step1Offset.top + "px");
        //$("#packageTable-" + id).css("left", step1Offset.left + "px");

        // Capture step 1 info
        packageId = this.id.split("-")[1];
        //usingDomain = $("#domain").val() == "dom";
        /*
         var json = { pid: packageId };
         json[csrfMagicName] = csrfMagicToken;
         $.post("<AJAX>?function=getSubdomains", json, function(data) {
         packageSubdomains = data.length > 0 ? data : false;
         gotoStep(2, function() {
         $("#pkgOrderButton-" + packageId).val("Order");
         $(".pkgOrderButton").removeAttr('disabled');
         $("#steps").fadeIn();
         });
         });*/
    });

    var changeValidity = function(selector, valid) {
        if(valid) {
            $(selector).switchClass("invalidField", "validField");
            return;
        }
        $(selector).switchClass("validField", "invalidField");
    };

    var invalidErrorMsgHandler = function(selector, valid, msg) {
        var error = $(selector);
        if(error.is(":visible")) {
            if(valid) {
                error.slideUp();
            } else if(error.html() != msg) {
                error.slideUp(function() {
                    error.html(msg).slideDown();
                });
            }
        } else if(!valid) {
            error.html(msg).slideDown();
        }
    };

    var step3SpinOpts = {
        lines: 12, // The number of lines to draw
        length: 3, // The length of each line
        width: 2, // The line thickness
        radius: 4, // The radius of the inner circle
        color: '#000', // #rbg or #rrggbb
        speed: 2, // Rounds per second
        trail: 60, // Afterglow percentage
        shadow: false // Whether to render a shadow
    };

    $(".step3Field").change(function() {
        var isValid = null;
        var $this = this;
        switch(this.id.split("step3")[1].toLowerCase()) {
            case "username":
                startFieldSpinner("#step3UsernameSpin");
                var json = {
                    operation: "checkUsername",
                    package: packageId,
                    username: $($this).val()
                };
                json[csrfMagicName] = csrfMagicToken;
                $.post("<AJAX>?function=orderForm", json, function(data) {
                    changeValidity($this, data.valid);
                    invalidErrorMsgHandler("#step3UsernameError", data.valid, data.msg);
                    stopFieldSpinner("#step3UsernameSpin");
                });
                break;
            case "password":
                isValid = $($this).val() != "";
                $("#step3Confirm").change();
                break;
            case "confirm":
                isValid = $($this).val() == $("#step3Password").val() && $($this).val() != "";
                break;
            case "subdomainselect":
                isValid = $($this).val() != "";
                break;
            case "email":
                // Regex + RFC 2822 = bad
                // Do better validation server-side
                startFieldSpinner("#step3EmailSpin");
                var json = {
                    operation: "checkEmail",
                    email: $($this).val()
                };
                json[csrfMagicName] = csrfMagicToken;
                $.post("<AJAX>?function=orderForm", json, function(data) {
                    changeValidity($this, data.valid);
                    invalidErrorMsgHandler("#step3EmailError", data.valid, data.msg);
                    stopFieldSpinner("#step3EmailSpin");
                });
                break;
        }
        if(isValid != null) {
            changeValidity(this, isValid);
        }
    });

    var startFieldSpinner = function(selector) {
        $(selector).spin(step3SpinOpts).fadeIn(150);
    }

    var stopFieldSpinner = function(selector) {
        $(selector).fadeOut(150, function() {
            $(this).spin(false);
        });
    }

    var startSpinner = function(selector) {
        $(selector).html($("#loadingSpinnerCopy").html());
        $(selector + " div.loadingSpinnerDiv").show();
        $(selector + " div.loadingSpinnerDiv div.loadingSpinner").spin({
            lines: 13, // The number of lines to draw
            length: 5, // The length of each line
            width: 2, // The line thickness
            radius: 5, // The radius of the inner circle
            color: '#000', // #rbg or #rrggbb
            speed: 2, // Rounds per second
            trail: 60, // Afterglow percentage
            shadow: false // Whether to render a shadow
        });
    };

    var stopSpinner = function(selector) {
        $(selector + " div.loadingSpinnerDiv").remove();
    };

    // When the user clicks the Next Step button
    $("#nextStepButton").click(function() {
        var readyUp = function() {
            $("#nextStepButton").removeAttr('disabled');
            $("#previousStepButton").removeAttr('disabled');
        };
        $(this).attr('disabled', 'disabled');
        $("#previousStepButton").attr('disabled', 'disabled');
        switch (step) {
            case 2:
                if(!termsAgreed) {
                    readyUp();
                    break;
                }
                $("#chkAgreeToS").attr('disabled', 'disabled');
                //startSpinner("#step-3 div.text");
                gotoStep(3, function() {
                    $("#chkAgreeToS").removeAttr('disabled');
                    readyUp();
                });
                break;
        }
    });

    // When the user clicks the Previous Step button
    $("#previousStepButton").click(function() {
        if(step != 2) {
            gotoStep(step - 1);
            return;
        }

        // Undo all that incredible shit we did
        $("#step-2").slideUp(function() {
            $("#right").hide();
            $("#step-1").show();
            var sidebarPkgOffset = $("#packageTable-" + packageId).offset();
            $("#packageTable-" + packageId).css("width", $("#packageTable-" + packageId).width() + "px")
                    .css("top", sidebarPkgOffset.top + "px")
                    .css("left", sidebarPkgOffset.left + "px")
                    .css("z-index", "9999")
                    .css("position", "absolute");
            $("#welcomeTable").fadeIn();
            $(".packageTable").not("#packageTable-" + packageId).fadeIn();
            $("#packageTable-" + packageId).animate({ top: pkgTableOffset.top, left: pkgTableOffset.left }, null, function() {
                $(this).appendTo("#packageTd-" + packageId)
                        .css("position", "").css("top", "").css("left", "")
                        .animate({ width: pkgTableWidth + "px" }, function() {
                            // Remove width property altogether. It was only for animation.
                            $(this).css("width", "");
                            $(".footer").fadeIn();
                            $(".pkgOrderButton").val("Order").removeAttr('disabled');
                            step = 1;
                        });

                var jsToggle = $("#packageTable-" + packageId + " .readmore-js-toggle");
                if(jsToggle.length) {
                    if(jsToggle.html().indexOf("More") != -1) {
                        jsToggle.click();
                    }

                    $("#packageTable-" + packageId + " .readmore-js-toggle").slideUp(function() {
                        $("#packageTable-" + packageId + " .subcontent").removeClass("readmore-js-section").removeData("plugin_readmore");
                        $(this).remove();
                    });
                }
            });
        });
        $("#steps").fadeOut();
        $(".footer").fadeOut();
        return;
    });
});

function stopRKey(evt) {
    var evt = (evt) ? evt : ((event) ? event : null);
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
    if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
            }

    document.onkeypress = stopRKey;

    function check(name, value) {
        $("#"+name+"check").html(loading);
        document.getElementById("next").disabled = true;
        window.setTimeout(function() {
            $.get("<AJAX>?function="+name+"check&THT=1&"+name+"="+value, function(data) {
                if(data == "1") {
                    $("#"+name+"check").html(right);
                }
                else {
                    $("#"+name+"check").html(wrong);
                }
                document.getElementById("next").disabled = false;
            });
        },300);
    }

    function orderstepme(id) {
        pid = id;
        document.getElementById("package").value = id;
        document.getElementById("order"+id).disabled = true;
        if(document.getElementById("domain").value == "sub") {
            document.getElementById("dom").style.display = 'none';
            document.getElementById("sub").style.display = '';
            $.get("<AJAX>?function=sub&pack="+document.getElementById("package").value, function(data) {
                document.getElementById("dropdownboxsub").innerHTML = data;
            });
        }
        else if(document.getElementById("domain").value == "dom") {
            document.getElementById("sub").style.display = 'none';
            document.getElementById("dom").style.display = '';
        }
        $.get('<AJAX>?function=orderForm&package='+ document.getElementById("package").value, function(stuff) {
            $("#custom").html('<table width="100%" border="0" cellspacing="2" cellpadding="0" id="custom">'+stuff+'</table>');
        });
        showhide(step, step + 1)
        step = step + 1
    }

    function nextstep() {
        switch(step) {
            case 2:
                if(document.getElementById("agree").checked == true) {
                    $.get("<AJAX>?function=orderIsUser", function(data) {
                        if (data == "1") {
                            showhide(step, step + 2);
                            step = step + 2;
                        }
                        else {
                            showhide(step, ++step);
                        }
                    });
                }
                else {
                    document.getElementById("verify").innerHTML = wrong;
                }
                break;

            case 3:
                var goodToGo = true;
                $('.step3Input').each(function(i) {
                    var html = $("#" + this.id + "check").html();
                    if(html != right) {
                        $("#verify").html(wrong);
                        goodToGo = false;
                        return;
                    }
                });
                if(goodToGo) {
                    $("#verify").html(right);
                    showhide(step, ++step);
                }
                break;
            case 4:
                final(step, step + 1)
                step = step + 1
                var url = "?function=create";
                var i;
                for(i="0"; i < document.order.length; i++){
                    if(document.order.elements[i].type == "checkbox"){
                        url = url+"&"+document.order.elements[i].id+"="+document.order.elements[i].checked;
                    }else{
                        url = url+"&"+document.order.elements[i].id+"="+document.order.elements[i].value;
                    }
                }
                document.getElementById("finished").innerHTML = working;
                document.getElementById("next").disabled = true;
                document.getElementById("back").disabled = true;
                $.get("<AJAX>"+url, function(data) {
                    document.getElementById("finished").innerHTML = data;
                    if((data.indexOf("Your account has been completed!") == -1) && (data.indexOf("Your account is awaiting admin validation!") == -1)) {
                        document.getElementById("back").disabled = false;
                    }
                    document.getElementById("verify").innerHTML = "";
                    $.get("<AJAX>?function=ispaid&pid="+ document.getElementById("package").value +"&uname="+ document.getElementById("username").value, function(data2) {
                        if(data2 != "") {
                            window.location = "../client/?page=invoices&iid="+data2;
                        }
                    });
                });
                break;
        }
    }
    function showhide(hide, show) {
        document.getElementById("next").disabled = true;
        document.getElementById("back").disabled = true;
        document.getElementById("verify").innerHTML = "";
        $("#"+hide).fadeOut(1000, function() {
            $("#steps").fadeIn(1000);
            $("#"+show).fadeIn(1000, function() {
                document.getElementById("next").disabled = false;
                document.getElementById("back").disabled = false;
            });
        });
    }
    function final(hide, show) {
        document.getElementById("next").disabled = true;
        document.getElementById("back").disabled = true;
        document.getElementById("verify").innerHTML = "";
        $("#"+hide).fadeOut(1000, function() {
            document.getElementById("verify").innerHTML = "<strong>Don't close or browse away from this page!</strong>";
            $("#"+show).fadeIn(1000);
        });
    }
    function previousstep() {
        if(step != 1) {
            document.getElementById("next").disabled = true;
            document.getElementById("back").disabled = true;
            document.getElementById("verify").innerHTML = "";

            var newstep = step - 1;
            if (newstep == 3) {
                $.get("<AJAX>?function=orderIsUser", function(data) {
                    if (data == "1") {
                        newstep = 2;
                    }
                });
            }
            $("#"+step).fadeOut(1000, function() {
                step = newstep;
                $("#"+step).fadeIn(1000, function() {
                    document.getElementById("next").disabled = false;
                    if(step != "1") {
                        document.getElementById("back").disabled = false;
                    }
                    if(step == "1") {
                        document.getElementById("next").disabled = true;
                        document.getElementById("order"+pid).disabled = false;
                    }
                });

            });
        }
    }
</script>

<style>
    .left {
        z-index: 5000;
        width: 30%;
        float: left;
    }
    .readmore-js-toggle {
        display: none;
        font-weight: bold;
    }
    .readmore-js-section {
        overflow: hidden;
        display: block;
        width: 100%;
    }
    #step-3 table {
        /* width: 60%; */
        margin: 0 auto;
    }
    #step-3 tr td:first-child {
        width: 35%;
        text-align: right;
    }
    #step-3 label {
        font-weight: bold;
        font-size: 16px;
        margin-right: 2.7em;
    }
    #step-3 input, select {
        font-size: 16px;
    }
    .validField {
        background-color: lightgreen;
    }
    .invalidField {
        background-color: lightcoral;
    }
    .step3Spin {
        position: relative;
        top: -15px;
        left: 180px;
        display: none;
    }
    .step3Error {
        display: none;
        color: red;
        font-weight: bold;
        font-style: italic;
    }
</style>

<div id="loadingSpinnerCopy">
    <div class="loadingSpinnerDiv" style="display: none; margin-left: auto; margin-right: auto; width: 0px; top: 18px; position: relative;">
        <div class="loadingSpinner"></div>
    </div>
</div>

<div id="newLeft" class="left"></div>

<div id="step-1">
    <div class="table" id="welcomeTable">
        <div class="cat">Step One - Choose Package</div>
        <div class="text" style="padding: 10px; min-height: 0px;">
            %WELCOMEMSG%
        </div>
    </div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        %PACKAGES%
    </table>
</div>

<div id="left" style="display: none;">
    <div class="table">
        <div class="cat">Home</div>
        <div class="text"><p>Order form navigation goes here</p></div>
    </div>
</div>

<div id="right" style="display: none;">
    <div class="table" id="step-2" style="display:none">
        <div class="cat">Step Two - Terms of Service</div>
        <div class="text">
            <table border="0" cellspacing="2" cellpadding="0" align="center" style="width: 100%;">
                <tr>
                    <td colspan="2"><div class="subborder">
                            <div class="sub" id="description">
                                %TOS%
                            </div>
                        </div></td>
                </tr>
                <tr>
                    <td width="330"><input name="chkAgreeToS" id="chkAgreeToS" type="checkbox" value="1" /> <label for="chkAgreeToS">I agree to the <NAME> Terms of Service.</label></td>
                    <td><a title="The Terms of Service is the set of rules you abide by. These must be agreed to if you wish to create an account." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="table" id="step-3" style="display: none;">
        <div class="cat">Step Three - Basic Account Info</div>
        <div class="text">
            <table><tbody>
                <tr>
                    <td>
                        <label for="step3Username">Username:</label>
                    </td>
                    <td style="max-width: 0px;">
                        <input name="step3Username" class="step3Field" id="step3Username" type="text" required>
                        <div id="step3UsernameSpin" class="step3Spin"></div>
                        <div id="step3UsernameError" class="step3Error"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="step3Password">Password:</label>
                    </td>
                    <td>
                        <input type="password" class="step3Field" name="step3Password" id="step3Password" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="step3Confirm">Confirm Password:</label>
                    </td>
                    <td>
                        <input type="password" class="step3Field" name="step3Confirm" id="step3Confirm" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="step3Domain">Domain:</label>
                    </td>
                    <td>
                        <input type="text" class="step3Field" name="step3Domain" id="step3Domain" required>
                        <select name="step3SubdomainSelect" id="step3SubdomainSelect" class="step3Field" required>
                            <option disabled="disabled">Pick an option</option>
                            <option>My own domain</option>
                            <optgroup label="Subdomains">
                                <option>.thehostingtool.com</option>
                                <option>.versobit.com</option>
                            </optgroup>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="step3Email">Email:</label>
                    </td>
                    <td>
                        <input type="email" name="step3Email" class="step3Field" id="step3Email" required>
                        <div id="step3EmailSpin" class="step3Spin"></div>
                        <div id="step3EmailError" class="step3Error"></div>
                    </td>
                </tr>
            </tbody></table>
        </div>
    </div>
    <table width="100%" border="0" cellspacing="2" cellpadding="0" id="steps" style="display:none;">
        <tr>
            <td width="33%" align="center"><input type="button" name="previousStepButton" id="previousStepButton" value="Previous Step"></td>
            <td width="33%" align="center" id="verify">&nbsp;</td>
            <td width="33%" align="center"><input type="button" name="nextStepButton" id="nextStepButton" value="Next Step"></td>
        </tr>
    </table>
</div>
