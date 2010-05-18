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
	
	public function content() { # Displays the page 
		global $style;
		global $db;
		global $main;
		if($_POST) {
			foreach($main->postvar as $key => $value) {
				if($value == "" && !$n) {
					$main->errors("Please fill in all the fields!");
					$n++;
				}
			}
			if(!$n) {
				$user = $db->staff($_SESSION['user']);
				if(!$user['password']) {
					$main->errors("Wrong username!?");
				}
				else {
					$data = $user;
					if(md5(md5($main->postvar['old']) . md5($data['salt'])) == $data['password']) {
						if($main->postvar['new'] != $main->postvar['confirm']) {
							$main->errors("Your passwords don't match!");
						}
						else {
							$newpass = 	md5(md5($main->postvar['new']) . md5($data['salt']));
							$db->query("UPDATE `<PRE>staff` SET `password` = '{$newpass}' WHERE `id` = '{$_SESSION['user']}'");
							$main->errors("Password changed!");
						}
					}
					else {
						$main->errors("Your old password was wrong!");
					}
				}
			}
		}
		echo $style->replaceVar("tpl/changepass.tpl");
	}
}
?>