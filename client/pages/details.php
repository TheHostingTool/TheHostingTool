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
		$data = $db->client($_SESSION['cuser']);
		$array['USER'] = $data['user'];
		$array['EMAIL'] = $data['email'];
		$array['DOMAIN'] = $data['domain'];
		$array['FIRSTNAME'] = $data['firstname'];
		$array['LASTNAME'] = $data['lastname'];
		$array['ADDRESS'] = $data['address'];
		$array['CITY'] = $data['city'];
		$array['STATE'] = $data['state'];
		$array['ZIP'] = $data['zip'];
		$array['COUNTRY'] = $data['country'];
		$array['PHONE'] = $data['phone'];
		$array['DISP'] = "<div>";
			if($_POST) {
				if(!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i',$main->postvar['email'])) {
					$main->errors("Your email is the wrong format!");
					echo $style->replaceVar("tpl/cedit.tpl", $array);
					return;
				}
				$query = $db->query("SELECT * FROM `<PRE>users` WHERE `email` = '{$main->postvar['email']}' AND `id` != '{$_SESSION['cuser']}'");
				if($db->num_rows($query) != 0) {
					$main->errors("That e-mail address is already in use!");
					echo $style->replaceVar("tpl/cedit.tpl", $array);
					return;
				}
				if(!$main->postvar['state']) {
					$main->errors("Please enter a valid state!");
					echo $style->replaceVar("tpl/cedit.tpl", $array);
					return;
				}
				if (!preg_match("/^([a-zA-Z\.\ -])+$/",$main->postvar['state'])) {
					$main->errors("Please enter a valid state!");
					echo $style->replaceVar("tpl/cedit.tpl", $array);
					return;
				}
				if(!$main->postvar['address']) {
					$main->errors("Please enter a valid address!");
					echo $style->replaceVar("tpl/cedit.tpl", $array);
					return;
				}
				if(!preg_match("/^([0-9a-zA-Z\.\ \-])+$/",$main->postvar['address'])) {
					$main->errors("Please enter a valid address!");
					echo $style->replaceVar("tpl/cedit.tpl", $array);
					return;
				}
				if(!$main->postvar['phone']) {
					$main->errors("Please enter a valid phone number!");
					echo $style->replaceVar("tpl/cedit.tpl", $array);
					return;
				}
				if (!preg_match("/^([0-9\-])+$/",$main->postvar['phone'])) {
					$main->errors("Please enter a valid phone number!");
					echo $style->replaceVar("tpl/cedit.tpl", $array);
					return;
				}
				if(strlen($main->postvar['phone']) > 15) {
					$main->errors("Phone number is to long!");
					echo $style->replaceVar("tpl/cedit.tpl", $array);
					return;
				}
				if(!$main->postvar['zip']) {
					$main->errors("Please enter a valid zip/postal code!");
					echo $style->replaceVar("tpl/cedit.tpl", $array);
					return;
				}
				if(strlen($main->postvar['zip']) > 7) {
					$main->errors("Zip/postal code is to long!");
					echo $style->replaceVar("tpl/cedit.tpl", $array);
					return;
				}
				if (!preg_match("/^([0-9a-zA-Z\ \-])+$/",$main->postvar['zip'])) {
					$main->errors("Please enter a valid zip/postal code!");
					echo $style->replaceVar("tpl/cedit.tpl", $array);
					return;
				}
				if(!$main->postvar['city']) {
					$main->errors("Please enter a valid city!");
					echo $style->replaceVar("tpl/cedit.tpl", $array);
					return;
				}
				if (!preg_match("/^([a-zA-Z ])+$/",$main->postvar['city'])) {
					$main->errors("Please enter a valid city!");
					echo $style->replaceVar("tpl/cedit.tpl", $array);
					return;
				}
				$db->query("UPDATE `<PRE>users` SET `email` = '{$main->postvar['email']}' WHERE `id` = '{$_SESSION['cuser']}'");
				$db->query("UPDATE `<PRE>users` SET `state` = '{$main->postvar['state']}' WHERE `id` = '{$_SESSION['cuser']}'");
				$db->query("UPDATE `<PRE>users` SET `address` = '{$main->postvar['address']}' WHERE `id` = '{$_SESSION['cuser']}'");	
				$db->query("UPDATE `<PRE>users` SET `phone` = '{$main->postvar['phone']}' WHERE `id` = '{$_SESSION['cuser']}'");
				$db->query("UPDATE `<PRE>users` SET `zip` = '{$main->postvar['zip']}' WHERE `id` = '{$_SESSION['cuser']}'");
				$db->query("UPDATE `<PRE>users` SET `city` = '{$main->postvar['city']}' WHERE `id` = '{$_SESSION['cuser']}'");
				if($main->postvar['change']) {
					$data = $db->client($_SESSION['cuser']);
					if(md5(md5($main->postvar['currentpass']) . md5($data['salt'])) == $data['password']) {
						if($main->postvar['newpass'] === $main->postvar['cpass']) {
						$cmd = $main->changeClientPassword($_SESSION['cuser'], $main->postvar['newpass']);
						if($cmd === true) {
							$main->errors("Details updated!");
						}
						else {
							$main->errors((string)$cmd);
						}
					}
						else {
							$main->errors("Your passwords don't match!");		
						}
					}
					else {
						$main->errors("Your current password is incorrect.");
					}
				}
				else {
					$array['DISP'] = "<div style=\"display:none;\">";
					$main->errors("Details updated!");					
				}
			}
			echo $style->replaceVar("tpl/cedit.tpl", $array);
	}
}
?>
