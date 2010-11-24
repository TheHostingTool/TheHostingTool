<?php
//////////////////////////////
// The Hosting Tool
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
	 ↪checkdnsrr($domain,"A")))
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
}
?>
