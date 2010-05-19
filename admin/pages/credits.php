<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - Credits
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

define("PAGE", "Credits");

class page {
	
	public function content() { # Displays the page 
		global $style;
		global $db;
		global $main;
		
		echo $style->replaceVar("tpl/credits.tpl");
	}
}
?>