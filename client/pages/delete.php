<?php
//////////////////////////////
// The Hosting Tool
// Client Area - Home
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	
	public function content() { # Displays the page 
		global $style, $db, $main;
		if(!$db->config("delacc")) {
			die('Disabled.');
		}
		else {
			$_SESSION['cdelete'] = true;
			$array['USER'] = $_SESSION['cuser'];
			echo $style->replaceVar("tpl/cdelete.tpl", $array);
		}
	}
}
?>