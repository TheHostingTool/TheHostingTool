<?php


abstract class Panel {
	
	public function GenUsername() {
		global $main;
		return $main->generateUsername();
	}
	
	public function GenPassword() {
		global $main;
		return $main->generatePassword();	
	}
	
	public function serverDetails($server) {
		global $db, $main;
		$sql = "SELECT * FROM `<PRE>servers` WHERE `id` = '{$db->strip($server)}'";
		$query = $db->query($sql);
		if($db->num_rows($query) == 0) {
			$array['Error'] = "That server doesn't exist!";
			$array['Server ID'] = $server;
			$main->error($array);
			return;	
		} else {
			return $db->fetch_array($query);
		}
	}
	
	public	function remote($action, $params){}
	public	function changePwd($username, $newpwd, $server_id) {}
	public	function signup($server, $reseller, $user, $email, $pass ) {}
	public	function suspend($username, $server_id, $reason) {}
	public	function unsuspend($username, $server_id) {}
	public	function terminate($username, $server_id) {}	
}