<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - Home
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	
	public $navtitle;
	public $navlist = array();
	
	public function content() { # Displays the page 
		global $style;
		global $db;
		global $main;
		global $type;
		if(!$main->getvar['type'] || !$main->getvar['sub']) {
			echo "Not all variables set!";	
		}
		else {
			$type->classes[$main->getvar['type']]->clientPage();
		}
	}
}