<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - Mail Center
// By Jonny H, Nick D
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {

	public $navtitle;
	public $navlist = array();
							
	public function __construct() {
		$this->navtitle = "Mail Center Sub Menu";
		$this->navlist[] = array("Email Templates", "email_open.png", "templates");		
		$this->navlist[] = array("Mass Emailer", "transmit.png", "mass");
	}
	
	public function description() {
		return "<strong>Mail Center</strong><br />
		Welcome to the Mail. Here you can edit your email templates or send a mass email to all your users.<br />";			
	}

	public function content() { # Displays the page 
		global $main, $style, $db;
		
		switch($main->getvar['sub']) {
		
			case "templates": #email templates
				if($_POST) {
					foreach($main->postvar as $key => $value) {
						if($value == "" && !$n) {
							$main->errors("Please fill in all the fields!");
							$n++;
						}
					}
					if(!$n) {
						$db->query("UPDATE `<PRE>templates` SET
								   `subject` = '{$main->postvar['subject']}',
							   `content` = '{$main->postvar['content']}'
							   WHERE `id` = '{$main->postvar['template']}'");
						$main->errors("Template edited!");
					}
				}
				$query = $db->query("SELECT * FROM `<PRE>templates` ORDER BY `acpvisual` ASC");
				while($data = $db->fetch_array($query)) {
					$values[] = array($data['acpvisual'], $data['id']);	
				}
				$array['TEMPLATES'] = $main->dropDown("LOL", $values, $dID, 0, 1);
				echo $style->replaceVar("tpl/emailtemplates.tpl", $array);
			break;
			
			case "mass": #mass emailer
				echo $style->replaceVar("tpl/massemail.tpl");
			break;
		}
	}
}
?>