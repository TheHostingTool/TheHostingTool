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
		
		// Rich here - This is a new variable for the 
		// muliple orders thingy :D
		$newuser = true;
		
		//Check details
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
		
		if($_SESSION['clogged']) {
			$cdata = $db->client($_SESSION['cuser']);
			$newuser = false;
		}
		
		if((!$main->getvar['username']) && ($newuser==true)) {
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
		if((!$main->getvar['password']) && ($newuser==true)) {
		   echo "Please enter a password!";
		   return;
		}
		else {
			if($main->getvar['password'] != $main->getvar['confirmp']) {
				echo "Your passwords don't match!";
				return;
			}
		}
		if((!$main->getvar['email']) && ($newuser==true)) {
		   echo "Please enter a email!";
		   return;
		}
		else {
			if((!$main->check_email($main->getvar['email'])) && ($newuser==true)) {
				echo "Your email is the wrong format!";	
				return;
			}
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
		if ($newuser == false) {
			$newusername = $serverphp->GenUsername();
			$newpass = $serverphp->GenPassword();
			$done = $serverphp->signup($type->determineServer($main->getvar['package']), $pname2['reseller'], $newusername, $cdata['email'], $newpass);
		} 
		else {
			$done = $serverphp->signup($type->determineServer($main->getvar['package']), $pname2['reseller']);
		}
		if($done == true) { # Did the signup pass?
			$date = time();
			$ip = $_SERVER['REMOTE_ADDR'];
			if ($newuser == true) {
				$salt = md5(rand(0,9999999));
				$password = md5(md5($main->getvar['password']).md5($salt));
				$UsrName = $main->getvar['username'];
				$newusername = $main->getvar['username'];
				
				$db->query("INSERT INTO `<PRE>users` (user, email, password, salt, signup, ip) VALUES(
													  '{$main->getvar['username']}',
													  '{$main->getvar['email']}',
													  '{$password}',
													  '{$salt}',
													  '{$date}',
													  '{$ip}')");
			}
			else {
				$UsrName = $cdata['user'];
			}
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
				global $email;
				$array['USER'] = $newusername;
				if ($newuser == true) { $array['PASS'] = $main->getvar['password']; $array['EMAIL'] = $main->getvar['email']; }
				else { $array['PASS'] = $newpass; $array['EMAIL'] = $cdata['email']; }
				$array['DOMAIN'] = $main->getvar['fdom'];
				
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
						echo "<strong>Your account is awaiting admin validation!</strong><br />You may now use the client login bar to see your client area. An email has been dispatched to the address on file. You will recieve another email when the admin has overlooked your account.";
						$donecorrectly = true;
					}
					else {
						echo "Something with admin validation wen't wrong (suspend). Your account should be running but contact your host!";	
					}
				}
				else {
					echo "Something with admin validation wen't wrong. Your account should be running but contact your host!";	
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
				$iquery = $db->query("SELECT * FROM `<PRE>invoices` WHERE `uid` = '{$data['id']}' AND `due` = '{$due}'");
				$idata = $db->fetch_array($iquery);
				echo '<div class="errors"><b>You are being redirected to payment! It will load in a couple of seconds..</b></div>';
			}
		}
	}
	public function terminate($id) { # Deletes a user account from the package ID
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
			if(!is_object($this->servers[$server])) {
				$this->servers[$server] = $this->createServer($data['pid']); # Create server class
			}
			if($this->servers[$server]->terminate($data2['user'], $server) == true) {
				$emaildata = $db->emailTemplate("termacc");
				$array['REASON'] = "Admin termination.";
				$email->send($data2['email'], $emaildata['subject'], $emaildata['content'], $array);
				$db->query("DELETE FROM `<PRE>user_packs` WHERE `id` = '{$data['id']}'");
				$db->query("DELETE FROM `<PRE>users` WHERE `id` = '{$db->strip($data['userid'])}'");
				return true;
			}
			else {
				return false;	
			}
		}
	}
	public function suspend($id, $reason = false) { # Suspends a user account from the package ID
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
				$donestuff = $this->servers[$server]->suspend($data2['user'], $server, $reason);
			}
			else {
				$donestuff = $serverphp->suspend($data2['user'], $server, $reason);
			}
			if($donestuff == true) {
				$db->query("UPDATE `<PRE>user_packs` SET `status` = '2' WHERE `id` = '{$data['id']}'");
				$emaildata = $db->emailTemplate("suspendacc");
				$email->send($data2['email'], $emaildata['subject'], $emaildata['content']);
				return true;
			}
			else {
				return false;	
			}
		}
	}
	public function changePwd($id, $newpwd) { # Suspends a user account from the package ID
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
				return true;
			}
			else {
				return false;	
			}
		}
	}
	
	public function unsuspend($id) { # Unsuspends a user account from the package ID
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
			if(!is_object($this->servers[$server])) {
				$this->servers[$server] = $this->createServer($data['pid']); # Create server class
			}
			if($this->servers[$server]->unsuspend($data2['user'], $server) == true) {
				$db->query("UPDATE `<PRE>user_packs` SET `status` = '1' WHERE `id` = '{$data['id']}'");
				$emaildata = $db->emailTemplate("unsusacc");
				$email->send($data2['email'], $emaildata['subject'], $emaildata['content']);
				return true;
			}
			else {
				return false;	
			}
		}
	}
}
?>
