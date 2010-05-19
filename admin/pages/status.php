<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - Home
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////
/*
 * Server Status for AdminCP
 * By Jimmie Lin
 * THT Community
 * Listening to: The Proclaimers - What makes you cry :p
 */

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
	
	public function mysqlversion() { #Thanks to tharis20 at p@p to solve this problem
	   $output = shell_exec('mysql -V');
	   preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
	   return $version[0];
	} 
	
	public function server_status(){
		$diskfreespace = disk_free_space('/') / 1073741824;
		$disktotalspace = disk_total_space('/') / 1073741824;
		$server = $_SERVER['HTTP_HOST'];
		global $style;
		$array['OS'] = PHP_OS;
		$array['SOFTWARE'] = getenv('SERVER_SOFTWARE');
		$array['PHP_VERSION'] = phpversion();
		$array['MYSQL_VERSION'] = $this->mysqlversion();
		$array['DISK_FREE_SPACE'] = substr($diskfreespace,0,4);
		$array['DISK_TOTAL_SPACE'] = substr($disktotalspace,0,4);
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
