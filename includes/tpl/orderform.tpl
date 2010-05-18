<script type="text/javascript">
var step = 1;
var form = document.getElementById("order");
var wrong = '<img src="<URL>themes/icons/cross.png">';
var right = '<img src="<URL>themes/icons/accept.png">';
var loading = '<img src="<URL>themes/icons/ajax-loader.gif">';
var working = '<div align="center"><img src="<URL>themes/icons/working.gif"></div>';
var result;
var pid;

$(document).ready(function(){
   $("#username").change(function(event) {
	   this.value = this.value.toLowerCase();
	   check('user', this.value);
   });
});

function stopRKey(evt) { 
  var evt = (evt) ? evt : ((event) ? event : null); 
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
} 

document.onkeypress = stopRKey;

function check(name, value) {
	document.getElementById(name+"check").innerHTML = loading;
	document.getElementById("next").disabled = true;
	window.setTimeout(function() {
		$.get("<AJAX>?function="+name+"check&"+name+"="+value, function(data) {
			if(data == "1") {
				document.getElementById(name+"check").innerHTML = right;
			}
			else {
				document.getElementById(name+"check").innerHTML = wrong;
			}													
			document.getElementById("next").disabled = false;
																		});
							   },500);
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
						showhide(step, step + 2)
						step = step + 2
					}
					else {
						showhide(step, step + 1)
						step = step + 1
					}
				});
			}
			else {
				document.getElementById("verify").innerHTML = wrong
			}
			break;
			
		case 3:
			$.get("<AJAX>?function=clientcheck", function(data) {
			if(data == "1") {
				document.getElementById("verify").innerHTML = right;
				showhide(step, step + 1)
				step = step + 1
			}
			else {
				document.getElementById("verify").innerHTML = wrong;
			}													
																		});
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
				document.getElementById("back").disabled = false;
				document.getElementById("verify").innerHTML = "";
				$.get("<AJAX>?function=ispaid&pid="+ document.getElementById("package").value +"&uname="+ document.getElementById("username").value, function(data2) {							
																																							 //document.getElementById("finished").innerHTML = data2;
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
	document.getElementById("verify").innerHTML = ""
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
	document.getElementById("verify").innerHTML = ""
	$("#"+hide).fadeOut(1000, function() {
		document.getElementById("verify").innerHTML = "<strong>Don't close or browse away from this page!</strong>";
		$("#"+show).fadeIn(1000);
     });
}
function previousstep() {
	if(step != 1) {
		document.getElementById("next").disabled = true;
		document.getElementById("back").disabled = true;
		document.getElementById("verify").innerHTML = ""
		
		var newstep = step - 1;
		if (newstep == 3) {
			$.get("<AJAX>?function=orderIsUser", function(data) {
				if (data == "1") {
					newstep = 2
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
<form action="" method="post" name="order" id="order">
<div>
	<div id="1">
    	<input name="package" id="package" type="hidden" value="" />
        <div class="table">
            <div class="cat">Step One - Choose Type/Package</div>
            <div class="text">
                <table width="100%" border="0" cellspacing="2" cellpadding="0">
                  <tr>
                    <td width="20%">Domain/Subdomain:</td>
                    <td><select name="domain" id="domain">
                      <option value="dom" selected="selected">Domain</option>
                      <option value="sub">Subdomain</option>
                    </select></td>
                    <td width="70%"><a title="Choose the type of hosting:<br /><strong>Domain:</strong> example.com<br /><strong>Subdomain:</strong> example.subdomain.com" class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                  </tr>
                </table>
            </div>
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          %PACKAGES%
        </table>
    </div>
    <div class="table" id="2" style="display:none">
        <div class="cat">Step Two - Terms of Service</div>
        <div class="text">
        	<table width="100%" border="0" cellspacing="2" cellpadding="0">
              <tr>
                <td colspan="2"><div class="subborder">
                    	<div class="sub" id="description">
                        	%TOS%
                </div>
                    </div></td>
              </tr>
              <tr>
                <td width="98%">
                <input name="agree" id="agree" type="checkbox" value="1" /> Do you agree to the <NAME> Terms of Service?
                </td>
                <td width="2%"><a title="The Terms of Service is the set of rules you abide by. These must be agreed to." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
              </tr>
            </table>
        </div>
    </div>
	<div class="table" id="3" style="display:none">
        <div class="cat">Step Three - Client Account</div>
        <div class="text">
        	<table width="100%" border="0" cellspacing="2" cellpadding="0">
              <tr>
                <td width="25%">Username:</td>
                <td width="10%"><input type="text" name="username" id="username" /></td>
                <td width="1%" align="left"><a title="The username is your unique identity to your account. This is both your client account and control panel username. Please keep it under 8 characters." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                <td width="62%" align="left" id="usercheck">&nbsp;</td>
              </tr>
              <tr>
                <td>Password:</td>
                <td><input type="password" name="password" id="password" onchange="check('pass', this.value+':'+document.getElementById('confirmp').value)"/></td>
                <td rowspan="2" align="left" valign="middle"><a title="Your password is your own personal key that allows only you to log you into your account." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                <td rowspan="2" align="left" valign="middle" id="passcheck">&nbsp;</td>
              </tr>
              <tr>
                <td>Confirm Password:</td>
                <td><input type="password" name="confirmp" id="confirmp" onchange="check('pass', this.value+':'+document.getElementById('password').value)"/></td>
              </tr>
              <tr>
                <td>Email:</td>
                <td><input type="text" name="email" id="email" onchange="check('email', this.value)" /></td>
                <td align="left"><a title="Your email is your own address where all <NAME> emails will be sent to. Make sure this is valid." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                <td id="emailcheck" align="left">&nbsp;</td>
              </tr>
            </table>
        </div>
    </div>
    <div class="table" id="4" style="display:none">
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
    <div class="table" id="5" style="display:none">
        <div class="cat">Step 5 - Create Account</div>
        <div class="text" id="creation">
        	<div id="finished">
            </div>
        </div>
    </div>
    <table width="100%" border="0" cellspacing="2" cellpadding="0" id="steps" style="display:none;">
      <tr>
        <td width="33%" align="center"><input type="button" name="back" id="back" value="Previous Step" onclick="previousstep()" disabled="disabled" /></td>
        <td width="33%" align="center" id="verify">&nbsp;</td>
        <td width="33%" align="center"><input type="button" name="next" id="next" value="Next Step" onclick="nextstep()" ondblclick="return false" /></td>
      </tr>
    </table>
</div>
</form>
