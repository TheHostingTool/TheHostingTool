<?php
//////////////////////////////
// The Hosting Tool
// Client Area - Edit Details
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	
	public function content() { # Displays the page 
		global $style, $db, $main;
		if($_POST) {
			if(!$main->postvar['email']) {
				$n++;	
			}
			if($n) {
				$main->errors("Please fill in all the fields!");	
			}
			else {
				$db->query("UPDATE `<PRE>users` SET `email` = '{$main->postvar['email']}' WHERE `id` = '{$_SESSION['cuser']}'");
				if($main->postvar['change']) {
					$data = $db->client($_SESSION['cuser']);
					if(md5(md5($main->postvar['currentpass']) . md5($data['salt'])) == $data['password']) {
						if($main->postvar['newpass'] == $main->postvar['cpass']) {
							$password = md5(md5($main->postvar['newpass']) . md5($data['salt']));
							$db->query("UPDATE `<PRE>users` SET `password` = '{$password}' WHERE `id` = '{$_SESSION['cuser']}'");
							$main->errors("Details updated!");
						}
						else {
							$main->errors("Your passwords don't match!");		
						}
					}
					else {
						$main->errors("Your current password wasn't correct!");	
					}
				}
				else {
					$main->errors("Email updated!");	
				}
			}
		}
		$data = $db->client($_SESSION['cuser']);
		$array['EMAIL'] = $data['email'];
		echo $style->replaceVar("tpl/cedit.tpl", $array);
	}
}
?>