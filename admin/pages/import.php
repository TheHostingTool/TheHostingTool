<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - Import Tool
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	
	public $navtitle;
	public $navlist = array();
	
	private $classes = array();
	
	public function content() { # Displays the page 
		global $style;
		global $db;
		global $main;
		global $type;
		$files = $main->folderFiles(LINK ."import/");
		foreach($files as $value) {
			$link = LINK. "import/". $value;
			$data = explode(".", $value);
			if(file_exists($link)) {
				include($link);
				$this->classes[$data[0]] = new $data[0];
				$values[] = array($this->classes[$data[0]]->name, $data[0]);
			}
		}
		if(!$main->getvar['do']) {
			if($_POST) {
				$main->redirect("?page=import&do=". $main->postvar['do']);	
			}
			$array['DROPDOWN'] = $main->dropdown("do", $values);
			echo $style->replaceVar("tpl/import.tpl", $array);
		}
		else {
			if($this->classes[$main->getvar['do']]) {
				$this->classes[$main->getvar['do']]->import();
			}
			else {
				echo "That method doesn't exist!";	
			}
		}
	}
}
?>