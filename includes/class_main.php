<?php
//////////////////////////////
// TheHostingTool
// Main functions class
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class main {
	
	public $postvar = array(), $getvar = array(); # All post/get strings
	
	public function cleaninteger($var){ # Transforms an Integer Value (1/0) to a Friendly version (Yes/No)
	     $patterns[0] = '/0/';
         $patterns[1] = '/1/';
         $replacements[0] = 'No';
         $replacements[1] = 'Yes';
         return preg_replace($patterns, $replacements, $var);
	}
	
	public function cleanwip($var){ # Cleans v* from the version Number so we can work
	     if(preg_match('/v/', $var)){
	     $wip[0] = '/v/';
	     $wipr[0] = '';
	     $cleaned = preg_replace($wip, $wipr, $var);
	     return $cleaned;
	     }
	     else{
	 	     return $var; #Untouched
	     }
	}
	public function error($array) { # The main THT Error show
		echo "<strong>/////////////////THT ERROR<br /></strong>";
		foreach($array as $key => $data) {
			echo "<strong>". $key . ":</strong> ". $data ."<br />";
		}
		echo "/////////////////<br />";
	}
	
	public function redirect($url, $headers = 0, $long = 0) { # Redirects user, default headers
		if(!$headers) {
			header("Location: ". $url);	# Redirect with headers
		}
		else {
			echo '<meta http-equiv="REFRESH" content="'.$long.';url='.$url.'">'; # HTML Headers
		}
	}
	
	public function errors($error = 0) { # Shows error default, sets error if $error set
		if(!$error) {
			if($_SESSION['errors']) {
				return $_SESSION['errors'];
			}
		}
		else {
			$_SESSION['errors'] = $error;
		}
	}
	
	public function table($header, $content = 0, $width = 0, $height = 0) { # Returns the HTML for a THT table
		global $style;
		if($width) {
			$props = "width:".$width.";";	
		}
		if($height) {
			$props .= "height:".height.";";	
		}
		$array['PROPS'] = $props;
		$array['HEADER'] = $header;
		$array['CONTENT'] = $content;
		$array['ID'] =rand(0,999999);
		$link = LINK."../themes/". THEME ."/tpl/table.tpl";
		if(file_exists($link)) {
			$tbl = $style->replaceVar("../themes/". THEME ."/tpl/table.tpl", $array);
		}
		else {
			$tbl = $style->replaceVar("tpl/table.tpl", $array);
		}
		return $tbl;
	}
	public function sub($left, $right) { # Returns the HTML for a THT table
		global $style;
		$array['LEFT'] = $left;
		$array['RIGHT'] = $right;
		$link = LINK."../themes/". THEME ."/tpl/sub.tpl";
		if(file_exists($link)) {
			$tbl = $style->replaceVar("../themes/". THEME ."/tpl/sub.tpl", $array);
		}
		else {
			$tbl = $style->replaceVar("tpl/sub.tpl", $array);
		}
		return $tbl;
	}
	
	public function evalreturn($code) { # Evals code and then returns it without showing
		ob_start();
		eval("?> " . $code . "<?php ");
		$data = ob_get_contents();
		ob_clean();
		return $data;
	}
	
	public function done() { # Redirects the user to the right part
		global $main;
		foreach($main->getvar as $key => $value) {
			if($key != "do") {
				if($i) {
					$i = "&";	
				}
				else {
					$i = "?";	
				}
				$url .= $i . $key . "=" . $value;
			}
		}
		$main->redirect($url);
	}
	
	public function check_email($email) {
		if($this->validEmail($email)) {
			return true;
		}
		else {
			return false;
		}
	}
	
	public function dropDown($name, $values, $default = 0, $top = 1, $class = "") { # Returns HTML for a drop down menu with all values and selected
		if($top) {
			$html .= '<select name="'.$name.'" id="'.$name.'" class="'.$class.'">';
		}
		if($values) {
			foreach($values as $key => $value) {
				$html .= '<option value="'.$value[1].'"';
				if($default == $value[1]) {
				$html .= 'selected="selected"';
				}
				$html .= '>'.$value[0].'</option>';
			}
		}
		if($top) {
			$html .= '</select>';
		}
		return $html;
	}
	
	public function userDetails($id) { # Returns the details of a user in an array
		global $db;
		global $main;
		$query = $db->query("SELECT * FROM `<PRE>users` WHERE `id` = '{$db->strip($id)}'");
		if($db->num_rows($query) == 0) {
			$array['Error'] = "That user doesn't exist!";
			$array['User ID'] = $id;
			$main->error($array);
			return;	
		}
		else {
			$data = $db->fetch_array($query);
			return $data;
		}
	}
	
	public function folderFiles($link) { # Returns the filenames of a content in a folder
		$folder = $link;
		if ($handle = opendir($folder)) { # Open the folder
			while (false !== ($file = readdir($handle))) { # Read the files
				if($file != "." && $file != ".." && $file != ".svn" && $file != "index.html") { # Check aren't these names
					$values[] = $file;
				}
			}
		}
		closedir($handle); #Close the folder
		return $values;
	}
	
	public function checkIP($ip) { # Returns boolean for ip. Checks if exists
		global $db;
		global $main;
		$query = $db->query("SELECT * FROM `<PRE>users` WHERE `ip` = '{$db->strip($ip)}'");
		if($db->num_rows($query) > 0) {
			return false;
		}
		else {
			return true;	
		}
	}
	
	public function checkPerms($id, $user = 0) { # Checks the staff permissions for a nav item
		global $main, $db;
		if(!$user) {
			$user = $_SESSION['user'];
		}
		$query = $db->query("SELECT * FROM `<PRE>staff` WHERE `id` = '{$user}'");
		if($db->num_rows($query) == 0) {
			$array['Error'] = "Staff member not found";
			$array['Staff ID'] = $id;
			$main->error($array);
		}
		else {
			$data = $db->fetch_array($query);
			$perms = explode(",", $data['perms']);
			foreach($perms as $value) {
				if($value == $id) {
					return false;	
				}
			}
			return true;
		}
	}
	
	public function clientLogin($user, $pass) { # Checks the credentails of the client and logs in, returns true or false
		global $db, $main;
		if($user && $pass) {
			$query = $db->query("SELECT * FROM `<PRE>users` WHERE `user` = '{$main->postvar['user']}' AND (`status` <= '2' OR `status` = '4')");
			if($db->num_rows($query) == 0) {
				return false;
			}
			else {
				$data = $db->fetch_array($query);
				$ip = $_SERVER['REMOTE_ADDR'];
				if(md5(md5($main->postvar['pass']) . md5($data['salt'])) == $data['password']) {
					$_SESSION['clogged'] = 1;
					$_SESSION['cuser'] = $data['id'];
					$date = time();
					$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
														'{$data['id']}',
														'{$main->postvar['user']}',
														'{$date}',
														'Login successful ($ip)')");
					return true;
				}
				else {
					$date = time();
					$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
														'{$data['id']}',
														'{$main->postvar['user']}',
														'{$date}',
														'Login failed ($ip)')");
					return false;
				}
			}
		}
		else {
			return false;
		}
	}
	
	public function staffLogin($user, $pass) { # Checks the credentials of a staff member and returns true or false
		global $db, $main;
		if($user && $pass) {
			$query = $db->query("SELECT * FROM `<PRE>staff` WHERE `user` = '{$main->postvar['user']}'");
			if($db->num_rows($query) == 0) {
				return false;
			}
			else {
				$data = $db->fetch_array($query);
				if(md5(md5($main->postvar['pass']) . md5($data['salt'])) == $data['password']) {
					$_SESSION['logged'] = 1;
					$_SESSION['user'] = $data['id'];
					$date = time();
					$ip = $_SERVER['REMOTE_ADDR'];
					$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
														'{$data['id']}',
														'{$main->postvar['user']}',
														'{$date}',
														'STAFF LOGIN SUCCESSFUL ($ip)')");
					return true;
				}
				else {
					$date = time();
					$ip = $_SERVER['REMOTE_ADDR'];
					$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
														'{$data['id']}',
														'{$main->postvar['user']}',
														'{$date}',
														'STAFF LOGIN FAILED ($ip)')");
					return false;
				}
			}
		}
		else {
			return false;
		}
	}
	
	public function laterMonth($num) { # Makes the date with num of months after current
		$day = date('d');
		$month = date('m');
		$year = date('Y');
		
		$endMonth = $month + $num;
		
		switch($endMonth) {
		case 1:
		$year++;
		break;
		case 2:
		{
		if ($day > 28)
		{
		// check if the year is leap
		$day = 28; // or you can keep the day and increase the month
		}
		}
		break;
		default:
		// nothing to do 
		break;
		}
		
		return mktime(0,0,0,$endMonth,$day,$year);
	}
	
	/**
	* Validate an email address.
	* Provide email address (raw input)
	* Returns true if the email address has the email 
	* address format and the domain exists.
	* Thank you, Linux Journal!
	* http://www.linuxjournal.com/article/9585
	*/
	public function validEmail($email)
	{
	   $isValid = true;
	   $atIndex = strrpos($email, "@");
	   if (is_bool($atIndex) && !$atIndex)
	   {
		  $isValid = false;
	   }
	   else
	   {
		  $domain = substr($email, $atIndex+1);
		  $local = substr($email, 0, $atIndex);
		  $localLen = strlen($local);
		  $domainLen = strlen($domain);
		  if ($localLen < 1 || $localLen > 64)
		  {
			 // local part length exceeded
			 $isValid = false;
		  }
		  else if ($domainLen < 1 || $domainLen > 255)
		  {
			 // domain part length exceeded
			 $isValid = false;
		  }
		  else if ($local[0] == '.' || $local[$localLen-1] == '.')
		  {
			 // local part starts or ends with '.'
			 $isValid = false;
		  }
		  else if (preg_match('/\\.\\./', $local))
		  {
			 // local part has two consecutive dots
			 $isValid = false;
		  }
		  else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
		  {
			 // character not valid in domain part
			 $isValid = false;
		  }
		  else if (preg_match('/\\.\\./', $domain))
		  {
			 // domain part has two consecutive dots
			 $isValid = false;
		  }
		  else if
	(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
					 str_replace("\\\\","",$local)))
		  {
			 // character not valid in local part unless 
			 // local part is quoted
			 if (!preg_match('/^"(\\\\"|[^"])+"$/',
				 str_replace("\\\\","",$local)))
			 {
				$isValid = false;
			 }
		  }
		  if ($isValid && !(checkdnsrr($domain,"MX") || 
	 checkdnsrr($domain,"A")))
		  {
			 // domain not found in DNS
			 $isValid = false;
		  }
	   }
	   return $isValid;
	}
	
	/*
	 * A more or less centralized function for changing a client's
	 * password. This updates both the cPanel/WHM and THT password.
	 * Will return true ONLY on success. Any other returned value should
	 * be treated as a failure. If the return value happens to be a
	 * string, it is an error message.
	 */
	function changeClientPassword($clientid, $newpass) {
		global $db, $server;
		//Making sure the $clientid is a reference to a valid id.
		$query = $db->query("SELECT * FROM `<PRE>users` WHERE `id` = {$db->strip($clientid)}");
		if($db->num_rows($query) == 0) {
			return "That client does not exist.";
		}
		
		/*
		 * We're going to set the password in cPanel/WHM first. That way
		 * if the password is rejected for some reason, THT will not 
		 * desync.
		 */
		$command = $server->changePwd($clientid, $newpass);
		if($command !== true) {
			return $command;
		}
		
		/*
		 * Let's change THT's copy of the password. Might as well make a
		 * new salt while we're at it.
		 */
		mt_srand((int)microtime(true));
		$salt = md5(mt_rand());
		$password = md5(md5($newpass) . md5($salt));
		$db->query("UPDATE `<PRE>users` SET `password` = '{$password}' WHERE `id` = '{$db->strip($clientid)}'");
		$db->query("UPDATE `<PRE>users` SET `salt` = '{$salt}' WHERE `id` = '{$db->strip($clientid)}'");
		
		//Let's wrap it all up.
		return true;
	}
	
        /*
        * Converts two-letter country codes to their full names.
        * This will probably come in handy considering we use the two-letter
        * country code format to store countries in the database.
        * I had to compress this code a bit so it wouldn't be too lengthy.
        * Original Snippet: http://snipplr.com/view/36868/php-country-code--to-country-name-list/
        */
	public function country_code_to_country($code) {
		$country = '';
		if($code=='AF')$country='Afghanistan';if($code=='AX')$country='Aland Islands';if($code=='AL')$country='Albania';if($code=='DZ')$country='Algeria';if($code=='AS')$country='American Samoa';if($code=='AD')$country='Andorra';if($code=='AO')$country='Angola';if($code=='AI')$country='Anguilla';if($code=='AQ')$country='Antarctica';if($code=='AG')$country='Antigua and Barbuda';if($code=='AR')$country='Argentina';if($code=='AM')$country='Armenia';if($code=='AW')$country='Aruba';if($code=='AU')$country='Australia';if($code=='AT')$country='Austria';if($code=='AZ')$country='Azerbaijan';if($code=='BS')$country='Bahamas the';if($code=='BH')$country='Bahrain';if($code=='BD')$country='Bangladesh';if($code=='BB')$country='Barbados';if($code=='BY')$country='Belarus';if($code=='BE')$country='Belgium';if($code=='BZ')$country='Belize';if($code=='BJ')$country='Benin';if($code=='BM')$country='Bermuda';if($code=='BT')$country='Bhutan';if($code=='BO')$country='Bolivia';if($code=='BA')$country='Bosnia and Herzegovina';if($code=='BW')$country='Botswana';if($code=='BV')$country='Bouvet Island (Bouvetoya)';if($code=='BR')$country='Brazil';if($code=='IO')$country='British Indian Ocean Territory (Chagos Archipelago)';if($code=='VG')$country='British Virgin Islands';if($code=='BN')$country='Brunei Darussalam';if($code=='BG')$country='Bulgaria';if($code=='BF')$country='Burkina Faso';if($code=='BI')$country='Burundi';if($code=='KH')$country='Cambodia';if($code=='CM')$country='Cameroon';if($code=='CA')$country='Canada';
		if($code=='CV')$country='Cape Verde';if($code=='KY')$country='Cayman Islands';if($code=='CF')$country='Central African Republic';if($code=='TD')$country='Chad';if($code=='CL')$country='Chile';if($code=='CN')$country='China';if($code=='CX')$country='Christmas Island';if($code=='CC')$country='Cocos (Keeling) Islands';if($code=='CO')$country='Colombia';if($code=='KM')$country='Comoros the';if($code=='CD')$country='Congo';if($code=='CG')$country='Congo the';if($code=='CK')$country='Cook Islands';if($code=='CR')$country='Costa Rica';if($code=='CI')$country='Cote d\'Ivoire';if($code=='HR')$country='Croatia';if($code=='CU')$country='Cuba';if($code=='CY')$country='Cyprus';if($code=='CZ')$country='Czech Republic';if($code=='DK')$country='Denmark';if($code=='DJ')$country='Djibouti';if($code=='DM')$country='Dominica';if($code=='DO')$country='Dominican Republic';if($code=='EC')$country='Ecuador';if($code=='EG')$country='Egypt';if($code=='SV')$country='El Salvador';if($code=='GQ')$country='Equatorial Guinea';if($code=='ER')$country='Eritrea';if($code=='EE')$country='Estonia';if($code=='ET')$country='Ethiopia';if($code=='FO')$country='Faroe Islands';if($code=='FK')$country='Falkland Islands (Malvinas)';if($code=='FJ')$country='Fiji the Fiji Islands';if($code=='FI')$country='Finland';if($code=='FR')$country='France, French Republic';if($code=='GF')$country='French Guiana';if($code=='PF')$country='French Polynesia';if($code=='TF')$country='French Southern Territories';if($code=='GA')$country='Gabon';
		if($code=='GM')$country='Gambia the';if($code=='GE')$country='Georgia';if($code=='DE')$country='Germany';if($code=='GH')$country='Ghana';if($code=='GI')$country='Gibraltar';if($code=='GR')$country='Greece';if($code=='GL')$country='Greenland';if($code=='GD')$country='Grenada';if($code=='GP')$country='Guadeloupe';if($code=='GU')$country='Guam';if($code=='GT')$country='Guatemala';if($code=='GG')$country='Guernsey';if($code=='GN')$country='Guinea';if($code=='GW')$country='Guinea-Bissau';if($code=='GY')$country='Guyana';if($code=='HT')$country='Haiti';if($code=='HM')$country='Heard Island and McDonald Islands';if($code=='VA')$country='Holy See (Vatican City State)';if($code=='HN')$country='Honduras';if($code=='HK')$country='Hong Kong';if($code=='HU')$country='Hungary';if($code=='IS')$country='Iceland';if($code=='IN')$country='India';if($code=='ID')$country='Indonesia';if($code=='IR')$country='Iran';if($code=='IQ')$country='Iraq';if($code=='IE')$country='Ireland';if($code=='IM')$country='Isle of Man';if($code=='IL')$country='Israel';if($code=='IT')$country='Italy';if($code=='JM')$country='Jamaica';if($code=='JP')$country='Japan';if($code=='JE')$country='Jersey';if($code=='JO')$country='Jordan';if($code=='KZ')$country='Kazakhstan';if($code=='KE')$country='Kenya';if($code=='KI')$country='Kiribati';if($code=='KP')$country='Korea';if($code=='KR')$country='Korea';if($code=='KW')$country='Kuwait';if($code=='KG')$country='Kyrgyz Republic';if($code=='LA')$country='Lao';if($code=='LV')$country='Latvia';
		if($code=='LB')$country='Lebanon';if($code=='LS')$country='Lesotho';if($code=='LR')$country='Liberia';if($code=='LY')$country='Libyan Arab Jamahiriya';if($code=='LI')$country='Liechtenstein';if($code=='LT')$country='Lithuania';if($code=='LU')$country='Luxembourg';if($code=='MO')$country='Macao';if($code=='MK')$country='Macedonia';if($code=='MG')$country='Madagascar';if($code=='MW')$country='Malawi';if($code=='MY')$country='Malaysia';if($code=='MV')$country='Maldives';if($code=='ML')$country='Mali';if($code=='MT')$country='Malta';if($code=='MH')$country='Marshall Islands';if($code=='MQ')$country='Martinique';if($code=='MR')$country='Mauritania';if($code=='MU')$country='Mauritius';if($code=='YT')$country='Mayotte';if($code=='MX')$country='Mexico';if($code=='FM')$country='Micronesia';if($code=='MD')$country='Moldova';if($code=='MC')$country='Monaco';if($code=='MN')$country='Mongolia';if($code=='ME')$country='Montenegro';if($code=='MS')$country='Montserrat';if($code=='MA')$country='Morocco';if($code=='MZ')$country='Mozambique';if($code=='MM')$country='Myanmar';if($code=='NA')$country='Namibia';if($code=='NR')$country='Nauru';if($code=='NP')$country='Nepal';if($code=='AN')$country='Netherlands Antilles';if($code=='NL')$country='Netherlands the';if($code=='NC')$country='New Caledonia';if($code=='NZ')$country='New Zealand';if($code=='NI')$country='Nicaragua';if($code=='NE')$country='Niger';if($code=='NG')$country='Nigeria';if($code=='NU')$country='Niue';if($code=='NF')$country='Norfolk Island';
		if($code=='MP')$country='Northern Mariana Islands';if($code=='NO')$country='Norway';if($code=='OM')$country='Oman';if($code=='PK')$country='Pakistan';if($code=='PW')$country='Palau';if($code=='PS')$country='Palestinian Territory';if($code=='PA')$country='Panama';if($code=='PG')$country='Papua New Guinea';if($code=='PY')$country='Paraguay';if($code=='PE')$country='Peru';if($code=='PH')$country='Philippines';if($code=='PN')$country='Pitcairn Islands';if($code=='PL')$country='Poland';if($code=='PT')$country='Portugal, Portuguese Republic';if($code=='PR')$country='Puerto Rico';if($code=='QA')$country='Qatar';if($code=='RE')$country='Reunion';if($code=='RO')$country='Romania';if($code=='RU')$country='Russian Federation';if($code=='RW')$country='Rwanda';if($code=='BL')$country='Saint Barthelemy';if($code=='SH')$country='Saint Helena';if($code=='KN')$country='Saint Kitts and Nevis';if($code=='LC')$country='Saint Lucia';if($code=='MF')$country='Saint Martin';if($code=='PM')$country='Saint Pierre and Miquelon';if($code=='VC')$country='Saint Vincent and the Grenadines';if($code=='WS')$country='Samoa';if($code=='SM')$country='San Marino';if($code=='ST')$country='Sao Tome and Principe';if($code=='SA')$country='Saudi Arabia';if($code=='SN')$country='Senegal';if($code=='RS')$country='Serbia';if($code=='SC')$country='Seychelles';if($code=='SL')$country='Sierra Leone';if($code=='SG')$country='Singapore';if($code=='SK')$country='Slovakia (Slovak Republic)';if($code=='SI')$country='Slovenia';
		if($code=='SB')$country='Solomon Islands';if($code=='SO')$country='Somalia, Somali Republic';if($code=='ZA')$country='South Africa';if($code=='GS')$country='South Georgia and the South Sandwich Islands';if($code=='ES')$country='Spain';if($code=='LK')$country='Sri Lanka';if($code=='SD')$country='Sudan';if($code=='SR')$country='Suriname';if($code=='SJ')$country='Svalbard & Jan Mayen Islands';if($code=='SZ')$country='Swaziland';if($code=='SE')$country='Sweden';if($code=='CH')$country='Switzerland, Swiss Confederation';if($code=='SY')$country='Syrian Arab Republic';if($code=='TW')$country='Taiwan';if($code=='TJ')$country='Tajikistan';if($code=='TZ')$country='Tanzania';if($code=='TH')$country='Thailand';if($code=='TL')$country='Timor-Leste';if($code=='TG')$country='Togo';if($code=='TK')$country='Tokelau';if($code=='TO')$country='Tonga';if($code=='TT')$country='Trinidad and Tobago';if($code=='TN')$country='Tunisia';if($code=='TR')$country='Turkey';if($code=='TM')$country='Turkmenistan';if($code=='TC')$country='Turks and Caicos Islands';if($code=='TV')$country='Tuvalu';if($code=='UG')$country='Uganda';if($code=='UA')$country='Ukraine';if($code=='AE')$country='United Arab Emirates';if($code=='GB')$country='United Kingdom';if($code=='US')$country='United States of America';if($code=='UM')$country='United States Minor Outlying Islands';if($code=='VI')$country='United States Virgin Islands';if($code=='UY')$country='Uruguay, Eastern Republic of';if($code=='UZ')$country='Uzbekistan';
		if($code=='VU')$country='Vanuatu';if($code=='VE')$country='Venezuela';if($code=='VN')$country='Vietnam';if($code=='WF')$country='Wallis and Futuna';if($code=='EH')$country='Western Sahara';if($code=='YE')$country='Yemen';if($code=='ZM')$country='Zambia';if($code=='ZW')$country='Zimbabwe';if( $country == '') $country = $code;
		return $country;
	}
}
?>
