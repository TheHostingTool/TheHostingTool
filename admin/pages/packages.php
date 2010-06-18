<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - Packages
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	
	public $navtitle;
	public $navlist = array();
							
	public function __construct() {
		$this->navtitle = "Packages Sub Menu";
		$this->navlist[] = array("Add Packages", "package_add.png", "add");
		$this->navlist[] = array("Edit Packages", "package_go.png", "edit");
		$this->navlist[] = array("Delete Packages", "package_delete.png", "delete");
	}
	
	public function description() {
		return "<strong>Managing Packages</strong><br />
		Welcome to the Package Management Area. Here you can add, edit and delete web hosting packages. Have fun :)<br />
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
						if($value == "" && !$n && $key != "admin") {
							$main->errors("Please fill in all the fields!");
							$n++;
						}
					}
					if(!$n) {
						foreach($main->postvar as $key => $value) {
							if($key != "name" && $key != "backend" && $key != "description" && $key != "type" && $key != "server" && $key != "admin") {
								if($n) {
									$additional .= ",";	
								}
								$additional .= $key."=".$value;
								$n++;
							}
						}
						$db->query("INSERT INTO `<PRE>packages` (name, backend, description, type, server, admin, is_hidden, is_disabled, additional, reseller) VALUES('{$main->postvar['name']}', '{$main->postvar['backend']}', '{$main->postvar['description']}', '{$main->postvar['type']}', '{$main->postvar['server']}', '{$main->postvar['admin']}', '{$main->postvar['hidden']}', '{$main->postvar['disabled']}', '{$additional}', '{$main->postvar['reseller']}')");
						$main->errors("Package has been added!");
					}
				}
				$query = $db->query("SELECT * FROM `<PRE>servers`");
				if($db->num_rows($query) == 0) {
					echo "There are no servers, you need to add a server first!";
					return;
				}
				while($data = $db->fetch_array($query)) {
					$values[] = array($data['name'], $data['id']);	
				}
				$array['SERVER'] = $main->dropDown("server", $values);
				echo $style->replaceVar("tpl/addpackage.tpl", $array);
				break;
				
			case "edit":
				if(isset($main->getvar['do'])) {
					$query = $db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$main->getvar['do']}'");
					if($db->num_rows($query) == 0) {
						echo "That package doesn't exist!";	
					}
					else {
						if($_POST) {
							foreach($main->postvar as $key => $value) {
								if($value == "" && !$n && $key != "admin") {
									$main->errors("Please fill in all the fields!");
									$n++;
								}
							}
							if(!$n) {
								foreach($main->postvar as $key => $value) {
									if($key != "name" && $key != "backend" && $key != "description" && $key != "type" && $key != "server" && $key != "admin") {
										if($n) {
											$additional .= ",";	
										}
										$additional .= $key."=".$value;
										$n++;
									}
								}
								$db->query("UPDATE `<PRE>packages` SET
										   `name` = '{$main->postvar['name']}',
										   `backend` = '{$main->postvar['backend']}',
										   `description` = '{$main->postvar['description']}',
										   `server` = '{$main->postvar['server']}',
										   `admin` = '{$main->postvar['admin']}',
										   `additional` = '{$additional}',
										   `reseller` = '{$main->postvar['reseller']}',
										   `is_hidden` = '{$main->postvar['hidden']}',
										   `is_disabled` = '{$main->postvar['disabled']}'
										   WHERE `id` = '{$main->getvar['do']}'");
								$main->errors("Package has been edited!");
								$main->done();
							}
						}
						$data = $db->fetch_array($query);
						$array['BACKEND'] = $data['backend'];
						$array['DESCRIPTION'] = $data['description'];
						$array['NAME'] = $data['name'];
						$array['URL'] = $db->config("url");
						$array['ID'] = $data['id'];
						if($data['admin'] == 1) {
							$array['CHECKED'] = 'checked="checked"';	
						}
						else {
							$array['CHECKED'] = "";
						}
						if($data['reseller'] == 1) {
							$array['CHECKED2'] = 'checked="checked"';	
						}
						else {
							$array['CHECKED2'] = "";
						}
						if($data['is_hidden'] == 1) {
							$array['CHECKED3'] = 'checked="checked"';	
						}
						else {
							$array['CHECKED3'] = "";
						}
						if($data['is_disabled'] == 1) {
							$array['CHECKED4'] = 'checked="checked"';	
						}
						else {
							$array['CHECKED4'] = "";
						}
						$additional = explode(",", $data['additional']);
						foreach($additional as $key => $value) {
							$me = explode("=", $value);
							$cform[$me[0]] = $me[1];
						}
						global $type;
						$array['FORM'] = $type->acpPedit($data['type'], $cform);
						$query = $db->query("SELECT * FROM `<PRE>servers`");
						while($data = $db->fetch_array($query)) {
							$values[] = array($data['name'], $data['id']);	
						}
						$array['SERVER'] = $array['THEME'] = $main->dropDown("server", $values, $data['server']);
						echo $style->replaceVar("tpl/editpackage.tpl", $array);
					}
				}
				else {
					$query = $db->query("SELECT * FROM `<PRE>packages`");
					if($db->num_rows($query) == 0) {
						echo "There are no packages to edit!";	
					}
					else {
						echo "<ERRORS>";
						while($data = $db->fetch_array($query)) {
							echo $main->sub("<strong>".$data['name']."</strong>", '<a href="?page=packages&sub=edit&do='.$data['id'].'"><img src="'. URL .'themes/icons/pencil.png"></a>');
							$n++;
						}
					}
				}
				break;
				
			case "delete":
				if($main->getvar['do']) {
					$db->query("DELETE FROM `<PRE>packages` WHERE `id` = '{$main->getvar['do']}'");
					$main->errors("Package has been Deleted!");		
				}
				$query = $db->query("SELECT * FROM `<PRE>packages`");
				if($db->num_rows($query) == 0) {
					echo "There are no servers to delete!";	
				}
				else {
					echo "<ERRORS>";
					while($data = $db->fetch_array($query)) {
						echo $main->sub("<strong>".$data['name']."</strong>", '<a href="?page=packages&sub=delete&do='.$data['id'].'"><img src="'. URL .'themes/icons/delete.png"></a>');
						$n++;
					}
				}
			break;
		}
	}
}
?>
