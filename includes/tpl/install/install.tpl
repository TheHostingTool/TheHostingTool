<script type="text/javascript">
var step;
var text = [];
var carryon;
var etc;
var upgrade;
var wrong = '<img src="<ICONDIR>cross.png">';
var working = '<div align="center"><img src="<URL>themes/icons/working.gif"></div>';
var goingforit;
text[1] = "Choose Method";
text[2] = "Enter Details";
text[3] = "Running Queries";
text[4] = "Enter THT Details";
text[5] = "Finished";
function change() {
	if(goingforit != 1) {
		goingforit = 1;
		switch(step) {
			case 1:
				var type = document.getElementById("method").value;
				if(type == "upgrade") {
					document.getElementById(step).style.fontWeight = "normal";
					upgrade = 1;
					step = 2;
				}
				gogo();
				if(type == "upgrade") {
					installstuff();	
				}
				break;
				
			case 2:
				document.getElementById("button3").disabled = true;
				document.getElementById("button3").value = "Working...";
				$.get("<AJAX>?function=sqlcheck&host="+document.getElementById("hostname").value+"&user="+document.getElementById("username").value+"&pass="+document.getElementById("password").value+"&db="+document.getElementById("database").value+"&pre="+document.getElementById("prefix").value, function(mydata) {
				document.getElementById("button3").disabled = false;
				document.getElementById("button3").value = "Next Step";
				//This if statement isn't really a big deal, but it adds some polish.
				if(parseInt(mydata) != 2) {
					document.getElementById("sqlerror").innerHTML = mydata;
				}

				switch(parseInt(mydata)) {
						case 0:
							document.getElementById("sqlerror").innerHTML = wrong+"Your SQL Details are incorrect!";
							break;
						case 1:
							document.getElementById("sqlerror").innerHTML = wrong+"Your SQL Database is incorrect!";
							break;
						case 2:
							gogo();
							document.getElementById("step3").innerHTML = working;
							installstuff();
							break;
						case 3:
							document.getElementById("sqlerror").innerHTML = wrong+"Your Config isn't writeable!";
							break;
						case 4:
							document.getElementById("sqlerror").innerHTML = wrong+"THT is already installed!";
							break;
					}
				});
			break;
			
			case 3:
				gogo();
				break;
				
			case 4:
				document.getElementById("button5").disabled = true;
				document.getElementById("button5").value = "Working...";
				$.get("<AJAX>?function=installfinal&user="+document.getElementById("staffusername").value+"&pass="+document.getElementById("staffpassword").value+"&email="+document.getElementById("email").value+"&name="+document.getElementById("name").value+"&url="+document.getElementById("url").value,
					  function(finisheddata) {
							document.getElementById("button5").disabled = false;
							document.getElementById("button5").value = "Next Step";
							if(parseInt(finisheddata) != 1) {
								document.getElementById("finalerror").innerHTML = finisheddata;
							}
							switch(parseInt(finisheddata)) {
								case 1:
									gogo();
									break;
								case 0:
									document.getElementById("finalerror").innerHTML = wrong+"Please fill in all the fields!";
									break;
							}
					  });
				break;
		}
	}
	goingforit = 0;
}
function gogo() {
	if(upgrade == 1) {
		step = 1;	
	}
	$("#step"+step).slideUp(500, function() {
		if(upgrade != 1) {
			document.getElementById(step).style.fontWeight = "normal";
		}
		else {
			step = 2;
		}
		step = step + 1;
		next();
	});	
}
function next() {
	$("#step"+step).slideDown(500, function() {
		document.getElementById(step).style.fontWeight = "bold";
		//document.getElementById("title").innerHTML = text[step];
		document.title = "THT :: Install - "+ text[step];
	});
}
function installstuff() {
	if(upgrade == 1) {
		etc = "&type=upgrade";	
	}
	else {
		etc = "&type=install";	
	}
	$.get("<AJAX>?function=install&version=%VERSION%"+etc, function(myinstall) {
		document.getElementById("step3").innerHTML = myinstall;
	});	
}
$(window).load(function () {
	step = 1;
	next();
});
</script>
<table width="100%" border="0" cellspacing="3" cellpadding="0">
  <tr>
    <td width="30%" valign="top"><table width="100%" border="0" cellspacing="3" cellpadding="0">
      <tr>
        <td><div class="subborder"><div class="sub" id="1">Step 1: Choose Method</div></div></td>
      </tr>
      <tr>
        <td><div class="subborder"><div class="sub" id="2">Step 2: Enter Details</div></div></td>
      </tr>
      <tr>
        <td><div class="subborder"><div class="sub" id="3">Step 3: Running Queries</div></div></td>
      </tr>
      <tr>
        <td><div class="subborder"><div class="sub" id="4">Step 4: Enter THT Details</div></div></td>
      </tr>
      <tr>
        <td><div class="subborder"><div class="sub" id="5">Step 5: Finished</div></div></td>
      </tr>
    </table></td>
    <td valign="top"><div class="table" style="margin-top:7px;">
        <div class="text" id="main">
        	<div id="step1" style="display:none;">
            <span class="errors">%ANYTHING%</span>
                <form id="install" name="install" method="post" action="">
                <table width="100%" border="0" cellspacing="2">
                  <tr>
                    <td width="20%">Choose Method:</td>
                    <td width="1%"><label>
                      <select name="method" id="method">
                        <option value="install" selected="selected">Install</option>
                        <option value="upgrade">Upgrade</option>
                      </select>
                    </label>
                    </td>
                    <td valign="middle" align="left">
                    <a class="tooltip" title="Choose if you want to upgrade or install the script.<br /><b>Install:</b> This is for people who haven't installed the script prior to this. Or using different mySQL details.<br /><b>Upgrade:</b> This for people who have installed THT and are upgrading to a new version."><img src="<ICONDIR>information.png"></a>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3" align="center"><label>
                      <input type="button" name="2" id="2" class="twobutton" value="Next Step" onclick="change()" />
                    </label></td>
                    </tr>
                </table>
                <input name="step" id="step" type="hidden" value="2" />
             </div>
             <div id="step2" style="display:none;">
                <table width="100%" border="0" cellspacing="2">
                <tr>
                	<td colspan="2" id="sqlerror" class="errors">
                    </td>
                </tr>
                  <tr>
                    <td width="20%">Hostname:</td>
                    <td><label>
                      <input name="hostname" type="text" id="hostname" value="localhost" />
                    </label><a class="tooltip" title="This is the server url for mySQL. Usually localhost unless you want to use a external mySQL server."><img src="<ICONDIR>information.png"></a></td>
                  </tr>
                   <tr>
                    <td width="20%">mySQL Username:</td>
                    <td><label>
                      <input name="username" type="text" id="username" />
                    </label><a class="tooltip" title="This is your mySQL username. You can create these in your control panel."><img src="<ICONDIR>information.png"></a></td>
                  </tr>
                  <tr>
                    <td width="20%">mySQL Password:</td>
                    <td><label>
                      <input name="password" type="password" id="password" />
                    </label><a class="tooltip" title="This is the password for your mySQL account. This was entered on username creation."><img src="<ICONDIR>information.png"></a></td>
                  </tr>
                     <tr>
                    <td width="20%">mySQL Database:</td>
                    <td><label>
                      <input name="database" type="text" id="database" />
                    </label><a class="tooltip" title="The database where the THT SQL will be inside. Includes your control panel username."><img src="<ICONDIR>information.png"></a></td>
                  </tr>
                    </tr>
                     <tr>
                    <td width="20%">THT Prefix:</td>
                    <td><label>
                      <input name="prefix" type="text" id="prefix" value="tht_" />
                    </label><a class="tooltip" title="This is the table prefixes for the THT database. Unless you want this to be different. Leave it default."><img src="<ICONDIR>information.png"></a></td>
                  </tr>
                  <tr>
                    <td colspan="2" align="center"><label>
                      <input type="button" name="3" id="button3" value="Next Step" onclick="change()" />
                    </label></td>
                    </tr>
                </table>
             </div>
             <div id="step3" style="display:none;">
             </div>
             <div id="step4" style="display:none;">
             <table width="100%" border="0" cellspacing="2">
             <tr>
                	<td colspan="2" id="finalerror" class="errors">
                    </td>
                </tr>
              <tr>
                <td width="30%">Your THT URL:<br />
                  (Including the trailing slash)</td>
                <td><label>
                  <input name="url" type="text" id="url" value="%GENERATED_URL%" />
                </label><a class="tooltip" title="This is a very important field. Make sure this is correct before clicking next. The THT URL is where your THT is located. Make sure it's in this format: http://example.com/tht/"><img src="<ICONDIR>information.png"></a></td>
              </tr>
               <tr>
                <td colspan="2"><strong>Staff Account</strong></td>
                </tr>
              <tr>
                <td width="30%">Username:</td>
                <td><label>
                  <input name="staffusername" type="text" id="staffusername" />
                </label><a class="tooltip" title="This is your admin username, this is what you'll be logging into with the staff area."><img src="<ICONDIR>information.png"></a></td>
              </tr>
                 <tr>
                <td width="30%">Password:</td>
                <td><label>
                  <input name="staffpassword" type="password" id="staffpassword" />
                </label><a class="tooltip" title="This is your password. Make sure it's entered correctly."><img src="<ICONDIR>information.png"></a></td>
              </tr>
              </tr>
                 <tr>
                <td width="30%">Email:</td>
                <td><label>
                  <input name="email" type="text" id="email" />
                </label><a class="tooltip" title="This is your email where all THT emails will be sent to."><img src="<ICONDIR>information.png"></a></td>
              </tr>
              </tr>
                 <tr>
                <td width="30%">Full Name:</td>
                <td><label>
                  <input name="name" type="text" id="name" />
                </label><a class="tooltip" title="This is your name, this will be shown to clients via Tickets and other methods."><img src="<ICONDIR>information.png"></a></td>
              </tr>
              <tr>
                <td colspan="2" align="center"><label>
                  <input type="button" name="button5" id="button5" value="Next Step" onclick="change()" />
                  </label></td>
              </tr>
            </table>
             </div>
             <div id="step5" style="display:none;">
             All the steps have been completed! You can now use your script as per usual. You can login in to your staff area <a href="../admin/">here</a>.
             </div>
        </div>
    </div>
    </form></td>
  </tr>
</table>
