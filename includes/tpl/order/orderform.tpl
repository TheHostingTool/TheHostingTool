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

$(document).ready(function() {

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
        var pkgTableOffset = $("#packageTable-" + id).offset();
        $("#packageTable-" + id).css("width", $("#packageTable-" + id).width() + "px")
                .css("top", pkgTableOffset.top + "px")
                .css("left", pkgTableOffset.left + "px")
                .css("z-index", "9999")
                .css("position", "absolute");

        // Hacky, but it works pretty damn well
        var step1Offset = $("#step-1").offset();

        // The magic begins
        $(".table").not("#packageTable-" + id).fadeOut();
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
        //
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
</style>

<div id="loadingSpinnerCopy">
    <div class="loadingSpinnerDiv" style="display: none; margin-left: auto; margin-right: auto; width: 0px; top: 18px; position: relative;">
        <div class="loadingSpinner"></div>
    </div>
</div>

<div id="newLeft" class="left"></div>

<form action="" method="post" name="order" id="order">
    <div>
        <div id="step-1">
            <div class="table">
                <div class="cat">Step One - Choose Package</div>
                <div class="text" style="padding: 10px; min-height: 0px;">
                    %WELCOMEMSG%
                </div>
            </div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                %PACKAGES%
            </table>
        </div>
        <div class="table" id="step-4" style="display:none">
            <div class="cat">Step Four - Hosting Account</div>
            <div class="text">
                <table width="100%" border="0" cellspacing="2" cellpadding="0">
                    <tr id="dom">
                        <td width="20%" id="domtitle">Domain:</td>
                        <td width="78%" id="domcontent">%DOMAIN%</td>
                        <td width="2%" align="left" id="domaincheck"><a title="Your domain, this must be in the format: <strong>example.com</strong>" class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                    </tr>
                    <tr id="sub">
                        <td width="20%" id="domtitle">Subdomain:</td>
                        <td id="domcontent"><input name="csub" id="csub" type="text" />.<span id="dropdownboxsub"></span></td>
                        <td id="domaincheck" align="left"><a title="Your subdomain, this must be in the format: <strong>subdomain.desiredsuffix.com</strong>" class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                    </tr>
                </table>
                <div id="custom">
                </div>
            </div>
        </div>
        <div class="table" id="step-5" style="display:none">
            <div class="cat">Step 5 - Create Account</div>
            <div class="text" id="creation">
                <div id="finished">
                </div>
            </div>
        </div>
    </div>
</form>

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
        <div class="cat">Step Three - Client Account</div>
        <div class="text">
            <style>
                #form_wrapper
                {
                    padding:14px;

                }

                .col-1
                {

                    width:30%;
                    float:left;
                }
                .col-2
                {
                    width:65%;
                    float:right;
                }

                #form_wrapper h1
                {
                    font-size:24px;
                    font-weight:bold;
                    margin-bottom:8px;
                    text-align:center;
                }
                #form_wrapper p{
                    font-size:12px;
                    margin-bottom:20px;
                    padding-bottom:10px;
                    text-align:center;
                }
                #form_wrapper label{

                    margin-bottom:18px;
                    font-weight:bold;
                    text-align:right;
                    display:block;
                }
                #form_wrapper .small{
                    font-size:11px;
                    font-weight:normal;
                    text-align:right;
                    display:block;
                }
                #form_wrapper input{
                    margin-bottom:15px;
                    font-size:12px;
                    padding:5px 2px;
                    width:100%;
                    font-weight:bold;
                }

                #form_wrapper input[type="radio"]
                {
                    margin-bottom:30px;

                    border:1px solid black;
                    width:15px;
                }
                #form_wrapper select
                {
                    padding:5px 2px;
                    width:100%;
                    margin-bottom:15px;
                }

                #form_wrapper button
                {
                    clear:both;
                    margin-left:50px;
                    width:125px;
                    height:31px;
                    text-align:center;
                    line-height:31px;
                    font-size:11px;
                    font-weight:bold;
                }
            </style>
            <div id="form_wrapper">
                <form id="form" name="form" method="post" action="index.html">
                    <div class="col-1">

                        <label>Name
                            <span class="small">Enter Full Name</span>
                        </label>

                        <label>Email
                            <span class="small">Add a valid address</span>
                        </label>

                        <label >Password
                            <span class="small">Min. size 6 chars</span>
                        </label>

                        <label >Gender
                            <span class="small">Enter your gender</span>
                        </label>

                        <label >Country
                            <span class="small">Enter your country</span>
                        </label>
                    </div>
                    <div class="col-2">

                        <input type="text" name="name" id="name" />

                        <input type="text" name="email" id="email"   />

                        <input type="text" name="password" id="password"/>

                        <input type="radio" name="password" id="password"/> Male
                        <input type="radio" name="password" id="password"/>Female

                        <select>
                            <option>Country1</option>
                            <option>Country2</option>
                        </select>
                    </div>

                    <center>
                        <button type="submit">Sign-up</button>
                    </center>

                </form>

            </div>
        </div>
    </div>
    <table width="100%" border="0" cellspacing="2" cellpadding="0" id="steps" style="display:none;">
        <tr>
            <td width="33%" align="center"><input type="button" name="previousStepButton" id="previousStepButton" value="Previous Step" disabled="disabled" /></td>
            <td width="33%" align="center" id="verify">&nbsp;</td>
            <td width="33%" align="center"><input type="button" name="nextStepButton" id="nextStepButton" value="Next Step" /></td>
        </tr>
    </table>
</div>
