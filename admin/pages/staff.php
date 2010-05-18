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
		$this->navtitle = "Staff Accounts Sub Menu";
		$this->navlist[] = array("Add Staff Account", "user_add.png", "add");
		$this->navlist[] = array("Edit Staff Account", "user_edit.png", "edit");
		$this->navlist[] = array("Delete Staff Account", "user_delete.png", "delete");
	}
	
	public function description() {
		return "<strong>Managing Staff Accounts</strong><br />
		This is where you add/edit/delete staff accounts. <b>Be careful, don't delete yourself!</b><br />
		To get started, just choose a link from the sidebar's SubMenu.";	
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
						$broke = explode("_", $key);
						if($broke[0] == "pages") {
							$main->postvar['perms'][$broke[1]] = $value;	
						}
					}
					if(!$n) {
						$query = $query = $db->query("SELECT * FROM `<PRE>staff` WHERE `user` = '{$main->postvar['user']}'");
						if(!$main->check_email($main->postvar['email'])) {
							$main->errors("Your email is the wrong format!");
						}
						elseif($main->postvar['pass'] != $main->postvar['conpass']) {
							$main->errors("Passwords don't match!");								
						}
						elseif($db->num_rows($query) >= 1) {
							$main->errors("That account already exists!");			
						}
						else {
							if($main->postvar['perms']) {
								foreach($main->postvar['perms'] as $key => $value) {
									if($n) {
										$string .= ",";	
									}
									if($value == "1") {
										$string .= $key;
									}
									$n++;
								}
							}
							$salt = md5(rand(0,9999999));
							$password = md5(md5($main->postvar['pass']).md5($salt));
							$db->query("INSERT INTO `<PRE>staff` (user, name, email, password, salt, perms) VALUES('{$main->postvar['user']}', '{$main->postvar['name']}', '{$main->postvar['email']}','{$password}','{$salt}', '{$string}')");
							$main->errors("Account added!");	
						}
					}
				}
				$query = $db->query("SELECT * FROM `<PRE>acpnav`");
				$array['PAGES'] = '<table width="100%" border="0" cellspacing="0" cellpadding="1">';
				while($data = $db->fetch_array($query)) {
					$array['PAGES'] .= '<tr><td width="30%" align="left">'.$data['visual'].':</td><td><input name="pages_'.$data['id'].'" id="pages_'.$data['id'].'" type="checkbox" value="1" /></td></tr>';
				}
				$array['PAGES'] .= "</table>";
				echo $style->replaceVar("tpl/addstaff.tpl", $array);
			break;
			
			case "edit":
				if(isset($main->getvar['do'])) {
					$query = $db->query("SELECT * FROM `<PRE>staff` WHERE `user` = '{$main->getvar['do']}'");
					if($db->num_rows($query) == 0) {
						echo "That account doesn't exist!";	
					}
					else {
						if($_POST) {
							foreach($main->postvar as $key => $value) {
								if($value == "" && !$n) {
									$main->errors("Please fill in all the fields!");
									$n++;
								}
								$broke = explode("_", $key);
								if($broke[0] == "pages") {
									$main->postvar['perms'][$broke[1]] = $value;	
								}
							}
							if(!$n) {
								if(!$main->check_email($main->postvar['email'])) {
									$main->errors("Your email is the wrong format!");
								}
								else {
									foreach($main->postvar['perms'] as $key => $value) {
										if($n) {
											$string .= ",";	
										}
										if($value == "1") {
											$string .= $key;
										}
										$n++;
									}
									$db->query("UPDATE `<PRE>staff` SET `email` = '{$main->postvar['email']}' WHERE `user` = '{$main->getvar['do']}'");
									$db->query("UPDATE `<PRE>staff` SET `name` = '{$main->postvar['name']}' WHERE `user` = '{$main->getvar['do']}'");
									$db->query("UPDATE `<PRE>staff` SET `perms` = '{$string}' WHERE `user` = '{$main->getvar['do']}'");
									$db->query("UPDATE `<PRE>staff` SET `user` = '{$main->postvar['user']}' WHERE `user` = '{$main->getvar['do']}'");
									$main->errors("Staff account edited!");
									$main->done();
								}
							}
						}
						$data = $db->fetch_array($query);
						$array['USER'] = $data['user'];
						$array['EMAIL'] = $data['email'];
						$array['NAME'] = $data['name'];
						$query = $db->query("SELECT * FROM `<PRE>acpnav`");
						$array['PAGES'] = '<table width="100%" border="0" cellspacing="0" cellpadding="1">';
						while($data2 = $db->fetch_array($query)) {
							if(!$main->checkPerms($data2['id'], $data['id'])) {
								$string = 'checked="checked"';	
							}
							$array['PAGES'] .= '<tr><td width="30%" align="left">'.$data2['visual'].':</td><td><input name="pages_'.$data2['id'].'" id="pages_'.$data2['id'].'" type="checkbox" value="1" '.$string.'/></td></tr>';
							$string = NULL;
						}
						$array['PAGES'] .= "</table>";
						echo $style->replaceVar("tpl/editstaff.tpl", $array);	
					}
				}
				else {
					$query = $db->query("SELECT * FROM `<PRE>staff`");
					if($db->num_rows($query) == 0) {
						echo "There are no staff accounts to edit!";	
					}
					else {
						echo "<ERRORS>";
						while($data = $db->fetch_array($query)) {
							echo $main->sub("<strong>".$data['user']."</strong>", '<a href="?page=staff&sub=edit&do='.$data['user'].'"><img src="'. URL .'themes/icons/pencil.png"></a>');
						}
					}
				}
				break;
			
			case "delete":
				$query = $db->query("SELECT * FROM `<PRE>staff`");
				if($main->getvar['do'] && $db->num_rows($query) > 1) {
					$db->query("DELETE FROM `<PRE>staff` WHERE `user` = '{$main->getvar['do']}'");
					$main->errors("Staff Account Deleted!");
				}
				elseif($main->getvar['do']) {
					$main->errors("Theres only one staff account!");
				}
				if($db->num_rows($query) == 0) {
					echo "There are no staff accounts to edit!";	
				}
				else {
					echo "<ERRORS>";
					while($data = $db->fetch_array($query)) {
						echo $main->sub("<strong>".$data['user']."</strong>", '<a href="?page=staff&sub=delete&do='.$data['user'].'"><img src="'. URL .'themes/icons/delete.png"></a>');
					}
				}
			break;
		}
	}
}
?>
