<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - Servers
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	
	public $navtitle;
	public $navlist = array();
							
	public function __construct() {
		$this->navtitle = "Servers Sub Menu";
		$this->navlist[] = array("View Servers", "server_go.png", "view");
		$this->navlist[] = array("Add Server", "server_add.png", "add");
		$this->navlist[] = array("Delete Server", "server_delete.png", "delete");
	}
	
	public function description() {
		return "<strong>Managing Hosting Servers</strong><br />
		Welcome to the Servers Management Area. Here you can view, add, and delete servers.<br />
		To get started, choose a link from the sidebar's SubMenu.";	
	}
	
	public function content() { # Displays the page 
		global $main;
		global $style;
		global $db;
		switch($main->getvar['sub']) {
			default:
				if($_POST) {
					foreach($main->postvar as $key => $value) {
						if($value == "" && !$n) {
							$main->errors("Please fill in all the fields!");
							$n++;
						}
					}
					if(!$n) {
						$db->query("INSERT INTO `<PRE>servers` (name, host, user, accesshash, type) VALUES('{$main->postvar['name']}', '{$main->postvar['host']}', '{$main->postvar['user']}', '{$main->postvar['hash']}', '{$main->postvar['type']}')");
						$main->errors("Server has been added!");
					}
				}
				$files = $main->folderFiles(LINK."servers/");
				foreach($files as $value) {
					include(LINK."servers/".$value);
					$fname = explode(".", $value);
					$stype = new $fname[0];
					$values[] = array($stype->name, $fname[0]);	
				}
				$array['TYPE'] = $main->dropDown("type", $values, 0, 0);
				echo $style->replaceVar("tpl/addserver.tpl", $array);
			break;
			
			case "view":
				if(isset($main->getvar['do'])) {
					$query = $db->query("SELECT * FROM `<PRE>servers` WHERE `id` = '{$main->getvar['do']}'");
					if($db->num_rows($query) == 0) {
						echo "That server doesn't exist!";	
					}
					else {
						if($_POST) {
							foreach($main->postvar as $key => $value) {
								if($value == "" && !$n) {
									$main->errors("Please fill in all the fields!");
									$n++;
								}
							}
							if(!$n) {
								$db->query("UPDATE `<PRE>servers` SET `name` = '{$main->postvar['name']}' WHERE `id` = '{$main->getvar['do']}'");
								$db->query("UPDATE `<PRE>servers` SET `user` = '{$main->postvar['user']}' WHERE `id` = '{$main->getvar['do']}'");
								$db->query("UPDATE `<PRE>servers` SET `host` = '{$main->postvar['host']}' WHERE `id` = '{$main->getvar['do']}'");
								$db->query("UPDATE `<PRE>servers` SET `accesshash` = '{$main->postvar['hash']}' WHERE `id` = '{$main->getvar['do']}'");
								$db->query("UPDATE `<PRE>servers` SET `type` = '{$main->postvar['type']}' WHERE `id` = '{$main->getvar['do']}'");
								$main->errors("Server edited!");
								$main->done();
							}
						}
						$data = $db->fetch_array($query);
						$array['USER'] = $data['user'];
						$array['HOST'] = $data['host'];
						$array['NAME'] = $data['name'];
						$array['HASH'] = $data['accesshash'];
						$array['ID'] = $data['id'];
						$files = $main->folderFiles(LINK."servers/");
						foreach($files as $value) {
							include(LINK."servers/".$value);
							$fname = explode(".", $value);
							$stype = new $fname[0];
							$values[] = array($stype->name, $fname[0]);	
						}
						$array['TYPE'] = $main->dropDown("type", $values, $data['type'], 0, 0);
						echo $style->replaceVar("tpl/viewserver.tpl", $array);
					}
				}
				else {
					$query = $db->query("SELECT * FROM `<PRE>servers`");
					if($db->num_rows($query) == 0) {
						echo "There are no servers to view!";	
					}
					else {
						echo "<ERRORS>";
						while($data = $db->fetch_array($query)) {
							echo $main->sub("<strong>".$data['name']."</strong>", '<a href="?page=servers&sub=view&do='.$data['id'].'"><img src="'. URL .'themes/icons/magnifier.png"></a>');
							if($n) {
								echo "<br />";	
							}
							$n++;
						}
					}
				}
				break;
			
			case "delete":
				if($main->getvar['do']) {
					$db->query("DELETE FROM `<PRE>servers` WHERE `id` = '{$main->getvar['do']}'");
					$main->errors("Server Account Deleted!");		
				}
				$query = $db->query("SELECT * FROM `<PRE>servers`");
				if($db->num_rows($query) == 0) {
					echo "There are no servers to delete!";	
				}
				else {
					echo "<ERRORS>";
					while($data = $db->fetch_array($query)) {
						echo $main->sub("<strong>".$data['name']."</strong>", '<a href="?page=servers&sub=delete&do='.$data['id'].'"><img src="'. URL .'themes/icons/delete.png"></a>');
						if($n) {
							echo "<br />";	
						}
						$n++;
					}
				}
			break;
		}
	}
}
?>
