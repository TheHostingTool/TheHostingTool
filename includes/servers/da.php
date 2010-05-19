<?php
//////////////////////////////
// The Hosting Tool
// Direct Admin Server Class
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

class da {
	
	# START THE MO TRUCKIN FUNCTIONS #
	
	public $name = "Direct Admin"; # THT Values
	public $hash = false; # Password or Access Hash?
	
	private $server;
	
	private function serverDetails($server) {
		global $db;
		global $main;
		$query = $db->query("SELECT * FROM `<PRE>servers` WHERE `id` = '{$db->strip($server)}'");
		if($db->num_rows($query) == 0) {
			$array['Error'] = "That server doesn't exist!";
			$array['Server ID'] = $id;
			$main->error($array);
			return;	
		}
		else {
			return $db->fetch_array($query);
		}
	}
	
	private function remote($action, $url) {
		$data = $this->serverDetails($this->server);
		$ch = curl_init();
		$ip = gethostbyname($data['host']);
		$serverstuff = "http://".$data['user'].":".$data['accesshash']."@" . $data['host'] . ":2222/". $action;
		//die($serverstuff.$url);
		curl_setopt($ch, CURLOPT_URL, $serverstuff);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST,1);
 		curl_setopt($ch, CURLOPT_POSTFIELDS,$url);
		$data = curl_exec ($ch);
		curl_close ($ch);
		//Work with data
		$split = explode("&", $data);
		foreach($split as $value) {
			$stuff = explode("=", $value);
			$final[$stuff[0]] = $stuff[1];
		}
		//die(print_r($final));
		return $final;
	}

	public function GenUsername() {
		$t = rand(5,8);
		for ($digit = 0; $digit < $t; $digit++) {
			$r = rand(0,1);
			$c = ($r==0)? rand(65,90) : rand(97,122);
			$user .= chr($c);
		}
		return $user;
	}
	
	public function GenPassword() {
		for ($digit = 0; $digit < 5; $digit++) {
			$r = rand(0,1);
			$c = ($r==0)? rand(65,90) : rand(97,122);
			$passwd .= chr($c);
		}
		return $passwd;
	}
	
	public function signup($server, $reseller, $user = '', $email = '', $pass = '') {
		global $main;
		global $db;
		if ($user == '') { $user = $main->getvar['username']; }
		if ($email == '') { $email = $main->getvar['email']; }
		if ($pass == '') { $pass = $main->getvar['password']; }
		$this->server = $server;
		$data = $this->serverDetails($this->server);
		$ip = gethostbyname($data['host']);
		$string =   "action=create&add=Submit&username=". $user . "".
					"&passwd=". $pass ."".
					"&passwd2=". $pass ."".
					"&domain=". $main->getvar['fdom'] ."".
					"&package=". $main->getvar['fplan'] ."".
					"&notify=no".
					"&email=".$email."";
		if($reseller) {
			$define = "CMD_API_ACCOUNT_RESELLER";
			$string .= "&ip=shared";
		}
		else {
			$define = "CMD_API_ACCOUNT_USER";
			$string .= "&ip=".$ip;
		}
		//echo $action."<br />". $reseller;
		$command = $this->remote($define,$string);
		if($command['error']) {
			echo "<strong>".$command['text']."</strong><br />". $command['details'];	
		}
		else {
			return true;	
		}
	}
	
	public function suspend($user, $server, $reason = false) {
		$this->server = $server;
		$define = "CMD_API_SELECT_USERS";
		$action = "dosuspend=Suspend&suspend=suspend&location=CMD_SELECT_USERS&select0=" . strtolower($user);
		$command = $this->remote($define, $action);
		if(!$command['error']) {
			return true;
		}
		else {
			return false;
		}
	}
	
	public function unsuspend($user, $server) {
		$this->server = $server;
		$define = "CMD_API_SELECT_USERS";
		$action = "dounsuspend=Unsuspend&suspend=unsuspend&select0=" . strtolower($user);
		$command = $this->remote($define ,$action);
		if(!$command['error']) {
			return true;
		}
		else {
			return false;
		}
	}
	public function terminate($user, $server) {
		$this->server = $server;
		$define = "CMD_API_SELECT_USERS";
		$action = "confirmed=Confirm&delete=yes&select0=" . strtolower($user);
		$command = $this->remote($define ,$action);
		if(!$command['error']) {
			return true;
		}
		else {
			return false;
		}
	}
}

?>