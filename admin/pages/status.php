<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - Server Status
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
			 	
	public $navtitle;
	public $navlist = array();
	
	public function __construct() {
		$this->navtitle = "Server Status Sub Menu";
		$this->navlist[] = array("Server Status", "application_osx_terminal.png", "status");
		$this->navlist[] = array("PHP Info", "page_white_php.png", "phpinfo");
	}

	public function description() {
		return "<strong>Server Status</strong><br />
		Welcome to the server status system. Here you can see your server information, php information and more.";	
	}
	
	public function mysqlVersion() {
		global $db;
		$result = $db->fetch_array($db->query("SELECT Version()"));
		return $result[0];
	}
	
	public function getLinuxDistro($raw = false) {
		global $main;
		if($main->canRun("shell_exec")) {
			$result = shell_exec("cat /etc/*-release");
			if($raw) {
				return $result;
			}
			if(preg_match('/DISTRIB_DESCRIPTION="(.*)"/', $result, $match)) {
				return $match[1];
			}
			return $result;
		}
		return false;
	}
	
	public function server_status() {
		global $main;
		$array['EXTRA'] = '';
		if(!$main->canRun('shell_exec')) {
			$array['EXTRA'] = 'Some statistics could not be provided because shell_exec has been disabled.';
		}
		$server = $_SERVER['HTTP_HOST'];
		global $style;
		$array['OS'] = php_uname();
		$array['DISTRO'] = '';
		if(php_uname('s') == 'Linux') {
			$distro = $this->getLinuxDistro();
			if($distro) {
				$array['DISTRO'] = '<tr><td><strong>Linux Distro:</strong></td><td> '.$distro.' </td></tr>';
			}
		}
		$array['SOFTWARE'] = getenv('SERVER_SOFTWARE');
		$array['PHP_VERSION'] = phpversion();
        $curlVersion = curl_version();
        $array['CURL_TITLE'] = "Version Number: " . $curlVersion["version_number"] . "<br />Version: " . $curlVersion["version"]
        . "<br />SSL Version Number: " . $curlVersion["ssl_version_number"] . "<br />SSL Version: " . $curlVersion["ssl_version"]
        . "<br />zlib Version: " . $curlVersion["libz_version"] . "<br />Host: " . $curlVersion["host"] . "<br />Age: " . $curlVersion["age"]
        . "<br />Protocols: " . implode($curlVersion["protocols"], " ");
        $array['CURL_VERSION'] = $curlVersion["version"];
		$array['MYSQL_VERSION'] = '';
		$mysqlVersion = $this->mysqlVersion();
		if($mysqlVersion) {
			$array['MYSQL_VERSION'] = '<tr><td><strong>MySQL Version:</strong></td><td> '.$mysqlVersion.' </td></tr>';
		}
		$array['SERVER'] = $server;
		echo $style->replaceVar('tpl/aserverstatus.tpl',$array);
	}
	
	public function content() { # Displays the page 
		global $main;
		global $page;
		global $style;
		
		switch($main->getvar['sub']) {
		   default: $this->server_status(); break;
		   
		   case "phpinfo":
		   		echo $style->replaceVar('tpl/phpinfo.tpl',$array);
		   break;
		}
	}
}
?>
