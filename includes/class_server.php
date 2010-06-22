<?php
//////////////////////////////
// The Hosting Tool
// Server Class
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

class server {
	
	private $servers = array(); # All the servers in a array
	
	# Start the Functions #
	private function createServer($package) { # Returns the server class for the desired package
		global $type, $main;
		$server = $type->determineServerType($type->determineServer($package)); # Determine server
		if($this->servers[$server]) {
			return;	
		}
		$link = LINK."servers/".$server.".php";
		if(!file_exists($link)) {
			$array['Error'] = "The server .php doesn't exist!";
			$array['Server ID'] = $server;
			$array['Path'] = $link;
			$main->error($array);
			return;	
		}
		else {
			include($link); # Get the server
			$serverphp = new $server;
			return $serverphp;
		}
	}
	
	public function signup() { # Echos the result of signup for ajax
		global $main;
		global $db;
		global $type;
			
		//Check details
		$query = $db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$main->getvar['package']}' AND `is_disabled` = 0"); # Package disabled?
		if($db->num_rows($query) != 1) {
			echo "Package is disabled.!";
			return;
		}
		if($main->getvar['domain'] == "dom") { # If Domain
			if(!$main->getvar['cdom']) {
				echo "Please fill in the domain field!";
				return;
			}
			else {
				$data = explode(".",$main->getvar['cdom']);
				if(!$data[1]) {
					echo "Your domain is the wrong format!";	
					return;
				}
				if ($db->config("tldonly")) { # Are we alowing TLD's Only?
					$ttlparts = count($data);
					if ($ttlparts > 2)
					{
						$dmndata = array('com', 'net', 'co', 'uk', 'org');
						if (!in_array($data[$ttlparts - 2], $dmndata)) {
							echo "We only allow Top Level Domains (.com/.net/.org, etc)";
							return;
						}
					} # If we get past this, its a top level domain :D yay
				}
			}
			$main->getvar['fdom'] = $main->getvar['cdom'];
		}
		if($main->getvar['domain'] == "sub") { # If Subdomain
			if(!$main->getvar['csub']) {
				echo "Please fill in the subdomain field!";
				return;
			}
			$main->getvar['fdom'] = $main->getvar['csub'].".".$main->getvar['csub2'];
		}
		
		if((!$main->getvar['username'])) {
			echo "Please enter a username!";
			return;
		}
		else {
			$query = $db->query("SELECT * FROM `<PRE>users` WHERE `user` = '{$main->getvar['username']}'");
			if($db->num_rows($query) != 0) {
				echo "That username already exists!";
				return;
			}
		}
		if((!$main->getvar['password'])) {
		   echo "Please enter a password!";
		   return;
		}
		else {
			if($main->getvar['password'] != $main->getvar['confirmp']) {
				echo "Your passwords don't match!";
				return;
			}
		}
		if((!$main->getvar['email'])) {
		   echo "Please enter a email!";
		   return;
		}
		if((!$main->check_email($main->getvar['email']))) {
				echo "Your email is the wrong format!";	
				return;
		}
		else {
			$query = $db->query("SELECT * FROM `<PRE>users` WHERE `email` = '{$main->getvar['email']}'");
			if($db->num_rows($query) != 0) {
				echo "That e-mail address is already in use!";
				return;
			}
		}
		if(($main->getvar['human'] != $_SESSION["pass"])) {
		   echo "Human test failed!";
		   return;
		}
		if((!$main->getvar['firstname'])) {
		   echo "Please enter a valid first name!";
		   return;
		}
		if((!$main->getvar['lastname'])) {
		   echo "Please enter a valid last name!";
		   return;
		}
		if((!$main->getvar['address'])) {
		   echo "Please enter a valid address!";
		   return;
		}
		if((!$main->getvar['city'])) {
		   echo "Please enter a valid city!";
		   return;
		}
		if((!$main->getvar['zip'])) {
		   echo "Please enter a valid zip code!";
		   return;
		}
		if((!$main->getvar['state'])) {
		   echo "Please enter a valid state!";
		   return;
		}
		if((!$main->getvar['state'])) {
		   echo "Please enter a valid state!";
		   return;
		}
		if((!$main->getvar['country'])) {
		   echo "Please select a country!";
		   return;
		}
		if ((!preg_match("/^([a-zA-Z\.\'\ \-])+$/",$main->getvar['firstname']))) {
			echo "Please enter a valid first name!";
			return;			
		}
		if ((!preg_match("/^([a-zA-Z\.\'\ \-])+$/",$main->getvar['lastname']))) {
			echo "Please enter a valid last name!";
			return;			
		}
		if ((!preg_match("/^([0-9a-zA-Z\.\ \-])+$/",$main->getvar['address']))) {
			echo "Please enter a valid address!";
			return;
		}
		if ((!preg_match("/^([a-zA-Z ])+$/",$main->getvar['city']))) {
			echo "Please enter a valid city!";
			return;			
		}
		if ((!preg_match("/^([a-zA-Z\.\ -])+$/",$main->getvar['state']))) {
			echo "Please enter a valid state!";
			return;
		}
		if((strlen($main->getvar['zip']) > 7)) {
			echo "Please enter a valid zip/postal code!";
			return;
		}
		if ((!preg_match("/^([0-9a-zA-Z\ \-])+$/",$main->getvar['zip']))) {
			echo "Please enter a valid zip/postal code!";
			return;
		}
		if((strlen($main->getvar['phone']) > 15)) {
			echo "Please enter a valid phone number!";
			return;
		}
		if ((!preg_match("/^([0-9\-])+$/",$main->getvar['phone']))) {
			echo "Please enter a valid phone number!";
			return;
		}
		
		$type2 = $type->createType($type->determineType($main->getvar['package']));
		if($type2->signup) {
			$pass = $type2->signup();
			if($pass) {
				echo $pass;	
				return;
			}
		}
		foreach($main->getvar as $key => $value) {
			$data = explode("_", $key);
			if($data[0] == "type") {
				if($n) {
					$additional .= ",";	
				}
				$additional .= $data[1]."=".$value;
				$n++;
			}
		}
		$main->getvar['fplan'] = $type->determineBackend($main->getvar['package']);
		$serverphp = $this->createServer($main->getvar['package']); # Create server class
		$pquery2 = $db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$main->getvar['package']}'");
		$pname2 = $db->fetch_array($pquery2);
		$done = $serverphp->signup($type->determineServer($main->getvar['package']), $pname2['reseller']);
		if($done == true) { # Did the signup pass?
			$date = time();
			$ip = $_SERVER['REMOTE_ADDR'];
			$salt = md5(rand(0,9999999));
			$password = md5(md5($main->getvar['password']).md5($salt));
			$UsrName = $main->getvar['username'];
			$newusername = $main->getvar['username'];	
			$db->query("INSERT INTO `<PRE>users` (user, email, password, salt, signup, ip, firstname, lastname, address, city, state, zip, country, phone, status) VALUES(
													  '{$main->getvar['username']}',
													  '{$main->getvar['email']}',
													  '{$password}',
													  '{$salt}',
													  '{$date}',
													  '{$ip}',
													  '{$main->getvar['firstname']}',
													  '{$main->getvar['lastname']}',
													  '{$main->getvar['address']}',
													  '{$main->getvar['city']}',
													  '{$main->getvar['state']}',
													  '{$main->getvar['zip']}',
													  '{$main->getvar['country']}',
													  '{$main->getvar['phone']}',
													  '3')");
			$db->query("INSERT INTO `<PRE>users_bak` (user, email, password, salt, signup, ip, firstname, lastname, address, city, state, zip, country, phone) VALUES(
													  '{$main->getvar['username']}',
													  '{$main->getvar['email']}',
													  '{$password}',
													  '{$salt}',
													  '{$date}',
													  '{$ip}',
													  '{$main->getvar['firstname']}',
													  '{$main->getvar['lastname']}',
													  '{$main->getvar['address']}',
													  '{$main->getvar['city']}',
													  '{$main->getvar['state']}',
													  '{$main->getvar['zip']}',
													  '{$main->getvar['country']}',
													  '{$main->getvar['phone']}')");
			$rquery = "SELECT * FROM `<PRE>users` WHERE `user` = '{$UsrName}' LIMIT 1;";
			$rdata = $db->query($rquery);
			$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
													  '{$rquery['userid']}',
													  '{$main->getvar['username']}',
													  '{$date}',
													  'Registered.')");
			$newSQL = "SELECT * FROM `<PRE>users` WHERE `user` = '{$UsrName}' LIMIT 1;";
			$query = $db->query($newSQL);
			if($db->num_rows($query) == 1) {
				$data = $db->fetch_array($query);
				$db->query("INSERT INTO `<PRE>user_packs` (userid, pid, domain, status, signup, additional) VALUES(
													  '{$data['id']}',
													  '{$main->getvar['package']}',
													  '{$main->getvar['fdom']}',
													  '1',
													  '{$date}',
													  '{$additional}')");
				$db->query("INSERT INTO `<PRE>user_packs_bak` (userid, pid, domain, status, signup, additional) VALUES(
													  '{$data['id']}',
													  '{$main->getvar['package']}',
													  '{$main->getvar['fdom']}',
													  '1',
													  '{$date}',
													  '{$additional}')");
				$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
													  '{$data['id']}',
													  '{$main->getvar['username']}',
													  '{$date}',
													  'Package created ({$main->getvar['fdom']})')");
				global $email;
				$url = $db->config("url");
				$array['USER'] = $newusername;
				$array['PASS'] = $main->getvar['password']; 
				$array['EMAIL'] = $main->getvar['email'];
				$array['DOMAIN'] = $main->getvar['fdom'];
				$array['CONFIRM'] = $url . "client/confirm.php?u=" . $newusername . "&c=" . $date;
				
				//Get plan email friendly name
				$pquery = $db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$main->getvar['package']}'");
				$pname = $db->fetch_array($pquery);
				$array['PACKAGE'] = $pname['name'];
				
				$puser = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `userid` = '{$data['id']}'");
				$puser2 = $db->fetch_array($puser);
				if($pname['admin'] == 0) {
					$emaildata = $db->emailTemplate("newacc");
					echo "<strong>Your account has been completed!</strong><br />You may now use the client login bar to see your client area or proceed to your control panel. An email has been dispatched to the address on file.";
					if($type->determineType($main->getvar['package']) == "paid") {
						echo " This will apply only when you've made payment.";	
						$_SESSION['clogged'] = 1;
						$_SESSION['cuser'] = $data['id'];
					}
					$donecorrectly = true;
				}
				elseif($pname['admin'] == 1) {
					if($serverphp->suspend($main->getvar['username'], $type->determineServer($main->getvar['package'])) == true) {
						$db->query("UPDATE `<PRE>user_packs` SET `status` = '3' WHERE `id` = '{$puser2['id']}'");
						$emaildata = $db->emailTemplate("newaccadmin");
						$emaildata2 = $db->emailTemplate("adminval");
						$email->staff($emaildata2['subject'], $emaildata2['content']);
						echo "<strong>Your account is awaiting admin validation!</strong><br />An email has been dispatched to the address on file. You will recieve another email when the admin has overlooked your account.";
						$donecorrectly = true;
					}
					else {
						echo "Something with admin validation went wrong (suspend). Your account should be running but contact your host!";	
					}
				}
				else {
					echo "Something with admin validation went wrong. Your account should be running but contact your host!";	
				}
				$email->send($array['EMAIL'], $emaildata['subject'], $emaildata['content'], $array);
			}
			else {
				echo "Your username doesn't exist in the DB meaning the query failed or it exists more than once!";	
			}
			if($donecorrectly && $type->determineType($main->getvar['package']) == "paid") {
				global $invoice;
				$amountinfo = $type->additional($main->getvar['package']);
				$amount = $amountinfo['monthly'];
				$due = time()+intval($db->config("suspensiondays")*24*60*60);
				$notes = "Your current hosting package monthly invoice. Package: ". $pname['name'];
				$invoice->create($data['id'], $amount, $due, $notes);
				$serverphp->suspend($main->getvar['username'], $type->determineServer($main->getvar['package']));
				$db->query("UPDATE `<PRE>user_packs` SET `status` = '4' WHERE `id` = '{$data['id']}'");
				$iquery = $db->query("SELECT * FROM `<PRE>invoices` WHERE `uid` = '{$data['id']}' AND `due` = '{$due}'");
				$idata = $db->fetch_array($iquery);
				echo '<div class="errors"><b>You are being redirected to payment! It will load in a couple of seconds..</b></div>';
			}
		}
	}
	public function terminate($id, $reason = false) { # Deletes a user account from the package ID
		global $db, $main, $type, $email;
		$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `id` = '{$db->strip($id)}'");
		if($db->num_rows($query) == 0) {
			$array['Error'] = "That package doesn't exist or cannot be terminated!";
			$array['User PID'] = $id;
			$main->error($array);
			return;	
		}
		else {
			$data = $db->fetch_array($query);
			$query2 = $db->query("SELECT * FROM `<PRE>users` WHERE `id` = '{$db->strip($data['userid'])}'");
			$data2 = $db->fetch_array($query2);
			$server = $type->determineServer($data['pid']);
			if(!is_object($this->servers[$server])) {
				$this->servers[$server] = $this->createServer($data['pid']); # Create server class
			}
			if($this->servers[$server]->terminate($data2['user'], $server) == true) {
				$date = time();
				$emaildata = $db->emailTemplate("termacc");
				$array['REASON'] = "Admin termination.";
				$email->send($data2['email'], $emaildata['subject'], $emaildata['content'], $array);
				$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
													  '{$db->strip($data['userid'])}',
													  '{$data2['user']}',
													  '{$date}',
													  'Terminated ($reason)')");
				$db->query("DELETE FROM `<PRE>user_packs` WHERE `id` = '{$data['id']}'");
				$db->query("DELETE FROM `<PRE>users` WHERE `id` = '{$db->strip($data['userid'])}'");
				return true;
			}
			else {
				return false;	
			}
		}
	}
	public function cancel($id, $reason = false) { # Deletes a user account from the package ID
		global $db, $main, $type, $email;
		$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `id` = '{$db->strip($id)}' AND `status` != '9'");
		if($db->num_rows($query) == 0) {
			$array['Error'] = "That package doesn't exist or cannot be cancelled! Are you trying to cancel an already cancelled account?";
			$array['User PID'] = $id;
			$main->error($array);
			return;	
		}
		else {
			$data = $db->fetch_array($query);
			$query2 = $db->query("SELECT * FROM `<PRE>users` WHERE `id` = '{$db->strip($data['userid'])}'");
			$data2 = $db->fetch_array($query2);
			$server = $type->determineServer($data['pid']);
			if(!is_object($this->servers[$server])) {
				$this->servers[$server] = $this->createServer($data['pid']); # Create server class
			}
			if($this->servers[$server]->terminate($data2['user'], $server) == true) {
				$date = time();
				$emaildata = $db->emailTemplate("cancelacc");
				$array['REASON'] = "Account Cancelled.";
				$email->send($data2['email'], $emaildata['subject'], $emaildata['content'], $array);
				$db->query("UPDATE `<PRE>user_packs` SET `status` = '9' WHERE `id` = '{$data['id']}'");
				$db->query("UPDATE `<PRE>users` SET `status` = '9' WHERE `id` = '{$db->strip($data['userid'])}'");
				$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
													  '{$db->strip($data['userid'])}',
													  '{$data2['user']}',
													  '{$date}',
													  'Cancelled  ($reason)')");
				return true;
			}
			else {
				return false;	
			}
		}
	}
	public function decline($id) { # Deletes a user account from the package ID
		global $db, $main, $type, $email;
		$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `id` = '{$db->strip($id)}' AND `status` != '9'");
		if($db->num_rows($query) == 0) {
			$array['Error'] = "That package doesn't exist or cannot be cancelled! Are you trying to cancel an already cancelled account?";
			$array['User PID'] = $id;
			$main->error($array);
			return;	
		}
		else {
			$data = $db->fetch_array($query);
			$query2 = $db->query("SELECT * FROM `<PRE>users` WHERE `id` = '{$db->strip($data['userid'])}'");
			$data2 = $db->fetch_array($query2);
			$server = $type->determineServer($data['pid']);
			if(!is_object($this->servers[$server])) {
				$this->servers[$server] = $this->createServer($data['pid']); # Create server class
			}
			if($this->servers[$server]->terminate($data2['user'], $server) == true) {
				$date = time();
				$emaildata = $db->emailTemplate("cancelacc");
				$array['REASON'] = "Account Declined.";
				$email->send($data2['email'], $emaildata['subject'], $emaildata['content'], $array);
				$db->query("UPDATE `<PRE>user_packs` SET `status` = '9' WHERE `id` = '{$data['id']}'");
				$db->query("UPDATE `<PRE>users` SET `status` = '9' WHERE `id` = '{$db->strip($data['userid'])}'");
				$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
													  '{$db->strip($data['userid'])}',
													  '{$data2['user']}',
													  '{$date}',
													  'Declined  (Package ID $id)')");
				return true;
			}
			else {
				return false;	
			}
		}
	}
	public function suspend($id, $reason = false) { # Suspends a user account from the package ID
		global $db, $main, $type, $email;
		$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `id` = '{$db->strip($id)}' AND `status` = '1'");
		if($db->num_rows($query) == 0) {
			$array['Error'] = "That package doesn't exist or cannot be suspended!";
			$array['User PID'] = $id;
			$main->error($array);
			return;	
		}
		else {
			$data = $db->fetch_array($query);
			$query2 = $db->query("SELECT * FROM `<PRE>users` WHERE `id` = '{$db->strip($data['userid'])}'");
			$data2 = $db->fetch_array($query2);
			$server = $type->determineServer($data['pid']);
			global $serverphp;
			if(!is_object($this->servers[$server]) && !$serverphp) {
				$this->servers[$server] = $this->createServer($data['pid']); # Create server class
				$donestuff = $this->servers[$server]->suspend($data2['user'], $server, $reason);
			}
			else {
				$donestuff = $serverphp->suspend($data2['user'], $server, $reason);
			}
			if($donestuff == true) {
				$date = time();
				$db->query("UPDATE `<PRE>user_packs` SET `status` = '2' WHERE `id` = '{$data['id']}'");
				$db->query("UPDATE `<PRE>users` SET `status` = '2' WHERE `id` = '{$db->strip($data['userid'])}'");
				$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
													  '{$db->strip($data['userid'])}',
													  '{$data2['user']}',
													  '{$date}',
													  'Suspended ($reason)')");
				$emaildata = $db->emailTemplate("suspendacc");
				$email->send($data2['email'], $emaildata['subject'], $emaildata['content']);
				return true;
			}
			else {
				return false;	
			}
		}
	}
	public function changePwd($id, $newpwd) { # Changes user's password.
		global $db, $main, $type, $email;
		$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `id` = '{$db->strip($id)}'");
		if($db->num_rows($query) == 0) {
			$array['Error'] = "That package doesn't exist!";
			$array['User PID'] = $id;
			$main->error($array);
			return;
		}
		else {
			$data = $db->fetch_array($query);
			$query2 = $db->query("SELECT * FROM `<PRE>users` WHERE `id` = '{$db->strip($data['userid'])}'");
			$data2 = $db->fetch_array($query2);
			$server = $type->determineServer($data['pid']);
			global $serverphp;
			if(!is_object($this->servers[$server]) && !$serverphp) {
				$this->servers[$server] = $this->createServer($data['pid']); # Create server class
				$donestuff = $this->servers[$server]->changePwd($data2['user'], $newpwd, $server);
			}
			else {
				$donestuff = $serverphp->changePwd($data2['user'], $newpwd, $server);
			}
			if($donestuff == true) {
				$date = time();
				$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
													  '{$db->strip($data['userid'])}',
													  '{$data2['user']}',
													  '{$date}',
													  'cPanel password updated.')");
				return true;
			}
			else {
				return false;	
			}
		}
	}
	
	public function unsuspend($id) { # Unsuspends a user account from the package ID
		global $db, $main, $type, $email;
		$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `id` = '{$db->strip($id)}' AND (`status` = '2' OR `status` = '3' OR `status` = '4')");
		if($db->num_rows($query) == 0) {
			$array['Error'] = "That package doesn't exist or cannot be unsuspended!";
			$array['User PID'] = $id;
			$main->error($array);
			return;	
		}
		else {
			$data = $db->fetch_array($query);
			$query2 = $db->query("SELECT * FROM `<PRE>users` WHERE `id` = '{$db->strip($data['userid'])}'");
			$data2 = $db->fetch_array($query2);
			$server = $type->determineServer($data['pid']);
			if(!is_object($this->servers[$server])) {
				$this->servers[$server] = $this->createServer($data['pid']); # Create server class
			}
			if($this->servers[$server]->unsuspend($data2['user'], $server) == true) {
				$date = time();
				$db->query("UPDATE `<PRE>user_packs` SET `status` = '1' WHERE `id` = '{$data['id']}'");
				$db->query("UPDATE `<PRE>users` SET `status` = '1' WHERE `id` = '{$db->strip($data['userid'])}'");
				$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
													  '{$db->strip($data['userid'])}',
													  '{$data2['user']}',
													  '{$date}',
													  'Unsuspended.')");
				$emaildata = $db->emailTemplate("unsusacc");
				$email->send($data2['email'], $emaildata['subject'], $emaildata['content']);
				return true;
			}
			else {
				return false;	
			}
		}
	}
	
	public function approve($id) { # Approves a user's account (Admin Validation).
		global $db, $main, $type, $email;
		$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `id` = '{$db->strip($id)}' AND (`status` = '2' OR `status` = '3' OR `status` = '4')");
		$uquery = $db->query("SELECT * FROM `<PRE>users` WHERE `id` = '{$query['userid']}' AND (`status` = '1')");
		if($db->num_rows($query) == 0 AND $db->num_rows($uquery) == 0) {
			$array['Error'] = "That package doesn't exist or cannot be approved! (Did they confirm their e-mail?)";
			$array['User PID'] = $id;
			$main->error($array);
			return;	
		}
		else {
			$data = $db->fetch_array($query);
			$query2 = $db->query("SELECT * FROM `<PRE>users` WHERE `id` = '{$db->strip($data['userid'])}'");
			$data2 = $db->fetch_array($query2);
			$server = $type->determineServer($data['pid']);
			if(!is_object($this->servers[$server])) {
				$this->servers[$server] = $this->createServer($data['pid']); # Create server class
			}
			if($this->servers[$server]->unsuspend($data2['user'], $server) == true) {
				$date = time();
				$db->query("UPDATE `<PRE>user_packs` SET `status` = '1' WHERE `id` = '{$data['id']}'");
				$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
													  '{$db->strip($data['userid'])}',
													  '{$data2['user']}',
													  '{$date}',
													  'Approved (Package ID $id)')");
				return true;
			}
			else {
				return false;	
			}
		}
	}
	
	public function confirm($username, $confirm) { # Set's user's account to Active when the unique link is visited.
		global $db, $main, $type, $email;
		$query = $db->query("SELECT * FROM `<PRE>users` WHERE `user` = '{$username}' AND `signup` = {$confirm} AND `status` = '3'");
		if($db->num_rows($query) == 0) {
			$array['Error'] = "That package doesn't exist or cannot be confirmed!";
			$main->error($array);
			return false;	
		}
		else {
			$data = $db->fetch_array($query);
			$date = time();
			$db->query("UPDATE `<PRE>users` SET `status` = '1' WHERE `user` = '{$username}'");
			$db->query("INSERT INTO `<PRE>logs` (uid, loguser, logtime, message) VALUES(
												  '{$db->strip($data['userid'])}',
												  '{$data['user']}',
												  '{$date}',
												  'Account/E-mail Confirmed.')");
			return true;
		}
	}
}
?>
