<?php
//////////////////////////////
// The Hosting Tool
// Support Area - Home
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	
	public function content() { # Displays the page 
		global $db;
		global $main;
		global $style;
		$query = $db->query("SELECT * FROM `<PRE>tickets` WHERE `reply` = '0' AND `userid` = '{$_SESSION['cuser']}'");
		$array['TICKETS'] = $db->num_rows($query);
		$query = $db->query("SELECT * FROM `<PRE>tickets` WHERE `reply` = '0' AND `userid` = '{$_SESSION['cuser']}' AND `status` = '1'");
		$array['OPENTICKETS'] = $db->num_rows($query);
		$query = $db->query("SELECT * FROM `<PRE>tickets` WHERE `reply` = '0' AND `userid` = '{$_SESSION['cuser']}' AND `status` = '3'");
		$array['CLOSEDTICKETS'] = $db->num_rows($query);
		echo $style->replaceVar("tpl/support/home.tpl", $array);
	}
}
?>
