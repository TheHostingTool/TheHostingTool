<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - System Tools
// By Jonny H & Jimmie Lin
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
			 	
	public $navtitle;
	public $navlist = array();
	
	public function __construct() {
		$this->navtitle = "System Tools Sub Menu";
		$this->navlist[] = array("System Health", "bug.png", "health");
		$this->navlist[] = array("Useful Tools", "wrench.png", "tools");
	}

	public function description() {
		return "<strong>System Tools</strong><br />
		Welcome to the system tools area. Here you can solve the most common problems and check your system health.<br />
		To start, choose a link from the submenu.";	
	}
	
	public function checkDir($dir,$friendly){
    	if (is_dir($dir)) { 
    		return "<div class='warn'><img src='../themes/icons/cross.png' alt='' /> Warning: $friendly still exists, please remove it.</div>";
		}
		else{
			return "<div class='noupg'><img src='../themes/icons/accept.png' alt='' /> Check Passed: $friendly does not exist</div>";
		}
	}
	
	public function checkPerms($file,$friendly){
		if (is_writable($file)){
			return "<div class='warn'><img src='../themes/icons/error.png' alt='' /> Warning: $friendly is world writable, please chmod it to 644!</div>";
		}
		else{
			return "<div class='noupg'><img src='../themes/icons/accept.png' alt='' /> Check Passed: $friendly is not world-writable!</div>";
		}
	}
	
	
	public function health() {
		global $page;
		global $style;
		$array['CHECK_INSTALL'] = $this->checkDir(LINK."../install/","Install Directory");
		$array['CHECK_CONF'] = $this->checkPerms(LINK."conf.inc.php", "Configuration File");
		$array['CHECK_ACPDIR'] = $this->checkPerms(LINK."../admin/", "Admin Folder");
		$array['CHECK_SUPPORTDIR'] = $this->checkPerms(LINK."../support/", "Support Folder");
		$array['CHECK_ORDERDIR'] = $this->checkPerms(LINK."../order/", "Order Folder");
		$array['CHECK_CLIENTDIR'] = $this->checkPerms(LINK."../client/", "Client Folder");
		$array['CHECK_INCDIR'] = $this->checkPerms(LINK, "Includes Folder");
		echo $style->replaceVar('tpl/systemhealth.tpl', $array);
	}
	
	public function tools() {
		global $page;
		global $style;
		global $email;
		$array['STATUS'] = "Viewing System tools for your THT Installation";
		echo $style->replaceVar('tpl/tools.tpl', $array);
	}
	
	public function chmod_conf() {
		global $style;
		if(is_writable(LINK."conf.inc.php")){
			$action = chmod(LINK."conf.inc.php", 0644);
			if($action == TRUE){
			     $array['STATUS'] = "Configuration File CHMOD'ed to 644";
			     echo $style->replaceVar('tpl/tools.tpl', $array);
			}
			elseif($action == FALSE){
				 error_reporting(0); # Kill all errors again
				 ini_set('display_errors', 'Off'); # Attempt to kill all errors.
				 $array['STATUS'] = "Failed to CHMOD Configuration File to 644.";
				 echo $style->replaceVar('tpl/tools.tpl', $array);
			}
			else {
			$array['STATUS'] = "You trying to hack me? You've been warned. An email has been sent.. May I say, Owned?";
			$email->staff("Possible Hacking Attempt", "A user has been logged trying to hack your copy of THT, their IP is: ". $_SERVER['REMOTE_ADDR']);
			echo $style->replaceVar('tpl/tools.tpl');
		    }
		}
		else{
			$array['STATUS'] = "Configuration File is already CHMOD 644!";
			echo $style->replaceVar('tpl/tools.tpl', $array);
		}
	}
	
	
	public function content() { # Displays the page 
		global $main;
		global $page;
		global $style;
		
		switch($main->getvar['sub']) {
		   default: $this->health(); break;
		   
		   case "health":
		   		$this->health();
		   break;
		   
		   case "tools":
		        $this->tools();
		   break;
		   
		   case "tool_chmodconf":
		        $this->chmod_conf();
		   break;
		}
	}
}
?>
