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
                      %CANHASSUBDOMAIN%
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
        	<table border="0" cellspacing="2" cellpadding="0" align="center" style="width: 100%;">
              <tr>
                <td colspan="2"><div class="subborder">
                <div class="sub" id="description">
                %TOS%
                </div>
                    </div></td>
              </tr>
              <tr>
                <td width="330"><input name="agree" id="agree" type="checkbox" value="1" /> Do you agree to the <NAME> Terms of Service?</td>
                <td><a title="The Terms of Service is the set of rules you abide by. These must be agreed to." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
              </tr>
            </table>
        </div>
    </div>
	<div class="table" id="3" style="display:none">
        <div class="cat">Step Three - Client Account</div>
        <div class="text">
        	<table border="0" cellspacing="2" cellpadding="0" align="center" style="width: 100%;">
              <tr>
                <td>Username:</td>
                <td><input type="text" name="username" id="username" /></td>
                <td align="left"><a title="The username is your unique identity to your account. This is both your client account and control panel username. Please keep it under 8 characters." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                <td align="left" id="usercheck">&nbsp;</td>
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
              <tr>
                <td>First Name:</td>
                <td><input type="text" name="firstname" id="firstname" onchange="check('firstname', this.value)" /></td>
                <td align="left"><a title="Your first name." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                <td id="firstnamecheck" align="left">&nbsp;</td>
              </tr>
              <tr>
                <td>Last Name:</td>
                <td><input type="text" name="lastname" id="lastname" onchange="check('lastname', this.value)" /></td>
                <td align="left"><a title="Your last name." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                <td id="lastnamecheck" align="left">&nbsp;</td>
              </tr>
              <tr>
                <td>Address:</td>
                <td><input type="text" name="address" id="address" onchange="check('address', this.value)" /></td>
                <td align="left"><a title="Your personal address." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                <td id="addresscheck" align="left">&nbsp;</td>
              </tr>
              <tr>
                <td>City:</td>
                <td><input type="text" name="city" id="city" onchange="check('city', this.value)" /></td>
                <td align="left"><a title="Your city. Letters only." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                <td id="citycheck" align="left">&nbsp;</td>
              </tr>
              <tr>
                <td>State:</td>
                <td><input type="text" name="state" id="state" onchange="check('state', this.value)" /></td>
                <td align="left"><a title="Your state. Letters only." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                <td id="statecheck" align="left">&nbsp;</td>
              </tr>
              <tr>
                <td>Zip Code:</td>
                <td><input type="text" name="zip" id="zip" onchange="check('zip', this.value)" /></td>
                <td align="left"><a title="Your zip/postal code. Numbers only." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                <td id="zipcheck" align="left">&nbsp;</td>
              </tr>
              <tr>
                <td>Country:</td>
                <td><select size="1" name="country" id="country"><option value="af">Afghanistan</option><option value="ax">Åland Islands</option><option value="al">Albania</option><option value="dz">Algeria</option><option value="as">American Samoa</option><option value="ad">Andorra</option><option value="ao">Angola</option><option value="ai">Anguilla</option><option value="aq">Antarctica</option><option value="ag">Antigua and Barbuda</option><option value="ar">Argentina</option><option value="am">Armenia</option><option value="aw">Aruba</option><option value="au">Australia</option><option value="at">Austria</option><option value="az">Azerbaijan</option><option value="bs">Bahamas</option><option value="bh">Bahrain</option><option value="bd">Bangladesh</option><option value="bb">Barbados</option><option value="by">Belarus</option><option value="be">Belgium</option><option value="bz">Belize</option><option value="bj">Benin</option><option value="bm">Bermuda</option><option value="bt">Bhutan</option><option value="bo">Bolivia</option><option value="ba">Bosnia and Herzegovina</option><option value="bw">Botswana</option><option value="bv">Bouvet Island</option><option value="br">Brazil</option><option value="io">British Indian Ocean Territory</option><option value="bn">Brunei Darussalam</option><option value="bg">Bulgaria</option><option value="bf">Burkina Faso</option><option value="bi">Burundi</option><option value="kh">Cambodia</option><option value="cm">Cameroon</option><option value="ca">Canada</option><option value="cv">Cape Verde</option><option value="ky">Cayman Islands</option><option value="cf">Central African Republic</option><option value="td">Chad</option><option value="cl">Chile</option><option value="cn">China</option><option value="cx">Christmas Island</option><option value="cc">Cocos (Keeling) Islands</option><option value="co">Colombia</option><option value="km">Comoros</option><option value="cg">Congo</option><option value="cd">Congo, The Democratic Republic of The</option><option value="ck">Cook Islands</option><option value="cr">Costa Rica</option><option value="ci">Cote D'ivoire</option><option value="hr">Croatia</option><option value="cu">Cuba</option><option value="cy">Cyprus</option><option value="cz">Czech Republic</option><option value="dk">Denmark</option><option value="dj">Djibouti</option><option value="dm">Dominica</option><option value="do">Dominican Republic</option><option value="ec">Ecuador</option><option value="eg">Egypt</option><option value="sv">El Salvador</option><option value="gq">Equatorial Guinea</option><option value="er">Eritrea</option><option value="ee">Estonia</option><option value="et">Ethiopia</option><option value="fk">Falkland Islands (Malvinas)</option><option value="fo">Faroe Islands</option><option value="fj">Fiji</option><option value="fi">Finland</option><option value="fr">France</option><option value="gf">French Guiana</option><option value="pf">French Polynesia</option><option value="tf">French Southern Territories</option><option value="ga">Gabon</option><option value="gm">Gambia</option><option value="ge">Georgia</option><option value="de">Germany</option><option value="gh">Ghana</option><option value="gi">Gibraltar</option><option value="gr">Greece</option><option value="gl">Greenland</option><option value="gd">Grenada</option><option value="gp">Guadeloupe</option><option value="gu">Guam</option><option value="gt">Guatemala</option><option value="gg">Guernsey</option><option value="gn">Guinea</option><option value="gw">Guinea-bissau</option><option value="gy">Guyana</option><option value="ht">Haiti</option><option value="hm">Heard Island and Mcdonald Islands</option><option value="va">Holy See (Vatican City State)</option><option value="hn">Honduras</option><option value="hk">Hong Kong</option><option value="hu">Hungary</option><option value="is">Iceland</option><option value="in">India</option><option value="id">Indonesia</option><option value="ir">Iran, Islamic Republic of</option><option value="iq">Iraq</option><option value="ie">Ireland</option><option value="im">Isle of Man</option><option value="il">Israel</option><option value="it">Italy</option><option value="jm">Jamaica</option><option value="jp">Japan</option><option value="je">Jersey</option><option value="jo">Jordan</option><option value="kz">Kazakhstan</option><option value="ke">Kenya</option><option value="ki">Kiribati</option><option value="kp">Korea, Democratic People's Republic of</option><option value="kr">Korea, Republic of</option><option value="kw">Kuwait</option><option value="kg">Kyrgyzstan</option><option value="la">Lao People's Democratic Republic</option><option value="lv">Latvia</option><option value="lb">Lebanon</option><option value="ls">Lesotho</option><option value="lr">Liberia</option><option value="ly">Libyan Arab Jamahiriya</option><option value="li">Liechtenstein</option><option value="lt">Lithuania</option><option value="lu">Luxembourg</option><option value="mo">Macao</option><option value="mk">Macedonia, The Former Yugoslav Republic of</option><option value="mg">Madagascar</option><option value="mw">Malawi</option><option value="my">Malaysia</option><option value="mv">Maldives</option><option value="ml">Mali</option><option value="mt">Malta</option><option value="mh">Marshall Islands</option><option value="mq">Martinique</option><option value="mr">Mauritania</option><option value="mu">Mauritius</option><option value="yt">Mayotte</option><option value="mx">Mexico</option><option value="fm">Micronesia, Federated States of</option><option value="md">Moldova, Republic of</option><option value="mc">Monaco</option><option value="mn">Mongolia</option><option value="me">Montenegro</option><option value="ms">Montserrat</option><option value="ma">Morocco</option><option value="mz">Mozambique</option><option value="mm">Myanmar</option><option value="na">Namibia</option><option value="nr">Nauru</option><option value="np">Nepal</option><option value="nl">Netherlands</option><option value="an">Netherlands Antilles</option><option value="nc">New Caledonia</option><option value="nz">New Zealand</option><option value="ni">Nicaragua</option><option value="ne">Niger</option><option value="ng">Nigeria</option><option value="nu">Niue</option><option value="nf">Norfolk Island</option><option value="mp">Northern Mariana Islands</option><option value="no">Norway</option><option value="om">Oman</option><option value="pk">Pakistan</option><option value="pw">Palau</option><option value="ps">Palestinian Territory, Occupied</option><option value="pa">Panama</option><option value="pg">Papua New Guinea</option><option value="py">Paraguay</option><option value="pe">Peru</option><option value="ph">Philippines</option><option value="pn">Pitcairn</option><option value="pl">Poland</option><option value="pt">Portugal</option><option value="pr">Puerto Rico</option><option value="qa">Qatar</option><option value="re">Reunion</option><option value="ro">Romania</option><option value="ru">Russian Federation</option><option value="rw">Rwanda</option><option value="sh">Saint Helena</option><option value="kn">Saint Kitts and Nevis</option><option value="lc">Saint Lucia</option><option value="pm">Saint Pierre and Miquelon</option><option value="vc">Saint Vincent and The Grenadines</option><option value="ws">Samoa</option><option value="sm">San Marino</option><option value="st">Sao Tome and Principe</option><option value="sa">Saudi Arabia</option><option value="sn">Senegal</option><option value="rs">Serbia</option><option value="sc">Seychelles</option><option value="sl">Sierra Leone</option><option value="sg">Singapore</option><option value="sk">Slovakia</option><option value="si">Slovenia</option><option value="sb">Solomon Islands</option><option value="so">Somalia</option><option value="za">South Africa</option><option value="gs">South Georgia and The South Sandwich Islands</option><option value="es">Spain</option><option value="lk">Sri Lanka</option><option value="sd">Sudan</option><option value="sr">Suriname</option><option value="sj">Svalbard and Jan Mayen</option><option value="sz">Swaziland</option><option value="se">Sweden</option><option value="ch">Switzerland</option><option value="sy">Syrian Arab Republic</option><option value="tw">Taiwan, Province of China</option><option value="tj">Tajikistan</option><option value="tz">Tanzania, United Republic of</option><option value="th">Thailand</option><option value="tl">Timor-leste</option><option value="tg">Togo</option><option value="tk">Tokelau</option><option value="to">Tonga</option><option value="tt">Trinidad and Tobago</option><option value="tn">Tunisia</option><option value="tr">Turkey</option><option value="tm">Turkmenistan</option><option value="tc">Turks and Caicos Islands</option><option value="tv">Tuvalu</option><option value="ug">Uganda</option><option value="ua">Ukraine</option><option value="ae">United Arab Emirates</option><option value="gb">United Kingdom</option><option value="us">United States</option><option value="um">United States Minor Outlying Islands</option><option value="uy">Uruguay</option><option value="uz">Uzbekistan</option><option value="vu">Vanuatu</option><option value="ve">Venezuela</option><option value="vn">Viet Nam</option><option value="vg">Virgin Islands, British</option><option value="vi">Virgin Islands, U.S.</option><option value="wf">Wallis and Futuna</option><option value="eh">Western Sahara</option><option value="ye">Yemen</option><option value="zm">Zambia</option><option value="zw">Zimbabwe</option></select></td>
                <td align="left"><a title="Your country." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                <td id="countrycheck" align="left">&nbsp;</td>
              </tr>
              <tr>
                <td>Phone Number:</td>
                <td><input type="text" name="phone" id="phone" onchange="check('phone', this.value)" /></td>
                <td align="left"><a title="Your personal phone number. Numbers and dashes only." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                <td id="phonecheck" align="left">&nbsp;</td>
              </tr>
              <tr>
                <td><img src="<URL>includes/captcha_image.php"></td>
                <td><input type="text" name="human" id="human" onchange="check('human', this.value)" /></td>
                <td align="left"><a title="Answer the question to prove you are not a bot." class="tooltip"><img src="<URL>themes/icons/information.png" /></a></td>
                <td id="humancheck" align="left">&nbsp;</td>
              </tr>
            </table>
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
