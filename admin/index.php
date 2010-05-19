<?php
//////////////////////////////
// The Hosting Tool
// Admin Area
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Compile THT
define("LINK", "../includes/");
define("CRON", 0);
include(LINK ."compiler.php");

//THT Variables
define("PAGE", "Admin Area");

//Main ACP Function - Creates the ACP basically
function acp() {
	global $main;
	global $db;
	global $style;
	global $type;
	global $email;
	ob_start(); # Stop the output buffer
	
	if(!$main->getvar['page']) { 
		$main->getvar['page'] = "home";
	}
	$query = $db->query("SELECT * FROM `<PRE>acpnav` WHERE `link` = '{$main->getvar['page']}'");
	$page = $db->fetch_array($query);
	$header = $page['visual'];
	$link = "pages/". $main->getvar['page'] .".php";
	if(!file_exists($link)) {
		$html = "<strong>THT Fatal Error:</strong> Seems like the .php is non existant. Is it deleted?";	
	}
	elseif(!$main->checkPerms($page['id']) && $db->num_rows($query) != 0) {
		$html = "You don't have access to this page.";	
	}
	else {
		//If deleting something
		if(preg_match("/[\.*]/", $main->getvar['page']) == 0) {
			include($link);
			$content = new page;
			// Main Side Bar HTML
			$nav = "Sidebar Menu";
		
			$sub = $db->query("SELECT * FROM `<PRE>acpnav`");
			while($row = $db->fetch_array($sub)) {
				if($main->checkPerms($row['id'])) {
					$array2['IMGURL'] = $row['icon'];
					$array2['LINK'] = "?page=".$row['link'];
					$array2['VISUAL'] = $row['visual'];
					$array['LINKS'] .= $style->replaceVar("tpl/sidebarlink.tpl", $array2);
				}
			}
			# Types Navbar
			/*
			 * When Working on the navbar, to make a spacer use this:
			 * $array['LINKS'] .= $style->replaceVar("tpl/spacer.tpl");
			 */
			$type->createAll();
			foreach($type->classes as $key => $value) {
				if($type->classes[$key]->acpNav) {
					foreach($type->classes[$key]->acpNav as $key2 => $value)  {
						$array2['IMGURL'] = $value[2];
						$array2['LINK'] = "?page=type&type=".$key."&sub=".$value[1];
						$array2['VISUAL'] = $value[0];
						$array['LINKS'] .= $style->replaceVar("tpl/sidebarlink.tpl", $array2);	
						if($main->getvar['page'] == "type" && $main->getvar['type'] == $key && $main->getvar['sub'] == $value[1]) {
							define("SUB", $value[3]);
							$header = $value[3];
							$main->getvar['myheader'] = $value[3];
						}
					}
				}
			}
			$array2['IMGURL'] = "information.png";
			$array2['LINK'] = "?page=credits";
			$array2['VISUAL'] = "Credits";
			$array['LINKS'] .= $style->replaceVar("tpl/sidebarlink.tpl", $array2);
			$array2['IMGURL'] = "delete.png";
			$array2['LINK'] = "?page=logout";
			$array2['VISUAL'] = "Logout";
			$array['LINKS'] .= $style->replaceVar("tpl/sidebarlink.tpl", $array2);
			$sidebar = $style->replaceVar("tpl/sidebar.tpl", $array);
			
			//Page Sidebar
			if($content->navtitle) {
				$subnav = $content->navtitle;
				$sub = $db->query("SELECT * FROM `<PRE>acpnav`");
				foreach($content->navlist as $key => $value) {
					$array2['IMGURL'] = $value[1];
					$array2['LINK'] = "?page=".$main->getvar['page']."&sub=".$value[2];
					$array2['VISUAL'] = $value[0];
					$array3['LINKS'] .= $style->replaceVar("tpl/sidebarlink.tpl", $array2);
				}
				$subsidebar = $style->replaceVar("tpl/sidebar.tpl", $array3);
			}
			
			if($main->getvar['sub'] && $main->getvar['page'] != "type") {
				foreach($content->navlist as $key => $value) {
					if($value[2] == $main->getvar['sub']) {
						if(!$value[0]) {
							define("SUB", $main->getvar['page']);	
							$header = $main->getvar['page'];
						}
						else {
							define("SUB", $value[0]);
							$header = $value[0];
						}
					}
				}
			}
			if($main->getvar['sub'] == "delete" && isset($main->getvar['do']) && !$_POST && !$main->getvar['confirm']) {
				foreach($main->postvar as $key => $value) {
					$array['HIDDEN'] .= '<input name="'.$key.'" type="hidden" value="'.$value.'" />';
				}
				$array['HIDDEN'] .= " ";
				$html = $style->replaceVar("tpl/warning.tpl", $array);	
			}
			elseif($main->getvar['sub'] == "delete" && isset($main->getvar['do']) && $_POST && !$main->getvar['confirm']) {
				if($main->postvar['yes']) {
					foreach($main->getvar as $key => $value) {
					  if($i) {
						  $i = "&";	
					  }
					  else {
						  $i = "?";	
					  }
					  $url .= $i . $key . "=" . $value;
					}
					$url .= "&confirm=1";
					$main->redirect($url);
				}
				elseif($main->postvar['no']) {
					$main->done();	
				}
			}
			else {
				if(isset($main->getvar['sub'])) {
					ob_start();
					$content->content();
					$html = ob_get_contents(); # Retrieve the HTML
					ob_clean(); # Flush the HTML
				}
				elseif($content->navlist) {
					$html .= $content->description(); # First, we gotta get the page description.
                    $html .= "<br /><br />"; # Break it up
                    // Now we should prepend some stuff here
                    $subsidebar2 .= "<strong>Page Submenu</strong><div class='break'></div>";
                    $subsidebar2 .= $subsidebar;
                    // Done, now output it in a sub() table
                    $html .= $main->sub($subsidebar2, NULL); # Initial implementation, add the SubSidebar(var) into the description, basically append it 
				}
				else {
					ob_start();
					$content->content();
					$html = ob_get_contents(); # Retrieve the HTML
					ob_clean(); # Flush the HTML
				}
			}
		}
		else {
			$html = "You trying to hack me? You've been warned. An email has been sent.. May I say, Owned?";
			$email->staff("Possible Hacking Attempt", "A user has been logged trying to hack your copy of THT, their IP is: ". $_SERVER['REMOTE_ADDR']);
		}
	}
	$staffuser = $db->staff($_SESSION['user']);
	define("SUB", $header);
	define("INFO", '<b>Welcome back, '. strip_tags($staffuser['name']) .'</b><br />'. SUB);
	
	echo '<div id="left">';
	echo $main->table($nav, $sidebar);
	if($content->navtitle) {
		echo "<br />";
		echo $main->table($subnav, $subsidebar);
	}
	echo '</div>';
	
	echo '<div id="right">';
	echo $main->table($header, $html);
	echo '</div>';
	
	$data = ob_get_contents(); # Retrieve the HTML
	ob_clean(); # Flush the HTML
	
	return $data; # Return the HTML
}

if(!$_SESSION['logged']) {
	if($main->getvar['page'] == "forgotpass") {
		define("SUB", "Reset Password");
		define("INFO", SUB);
		echo $style->get("header.tpl");
		
		if($_POST) {
			foreach($main->postvar as $key => $value) {
				if($value == "" && !$n) {
					$main->errors("Please fill in all the fields!");
					$n++;
				}
			}
			if(!$n) {
				$user = $main->postvar['user'];
				$email2 = $main->postvar['email'];
				$query = $db->query("SELECT * FROM `<PRE>staff` WHERE `user` = '{$user}' AND `email` = '{$email2}'");
				if($db->num_rows($query) == 0) {
					$main->errors("That account doesn't exist!");
				}
				else {
					$curstaff = $db->fetch_array($query);
					$password = rand(0,999999);
					$newpass = md5(md5($password) . md5($curstaff['salt']));
					$db->query("UPDATE `<PRE>staff` SET `password` = '{$newpass}' WHERE `id` = '{$curstaff['id']}'");
					$main->errors("Password reset!");
					$array['PASS'] = $password;
					$emaildata = $db->emailTemplate("areset");
					$email->send($email2, $emaildata['subject'], $emaildata['content'], $array);
				}
			}
		}
		echo '<div align="center">'.$main->table("Admin Area - Reset Password", $style->replaceVar("tpl/areset.tpl", $array), "300px").'</div>';
		
		echo $style->get("footer.tpl");
	}
	else{
		define("SUB", "Login");
		define("INFO", " ");
		if($_POST) { # If user submitts form
		if($main->staffLogin($main->postvar['user'], $main->postvar['pass'])) {
			$main->redirect("?page=home");	
		}
		else {
			$main->errors("Incorrect username or password!");
		}
	}
	
	echo $style->get("header.tpl");
	$array[] = "";
	echo '<div align="center">'.$main->table("Admin Area - Login", $style->replaceVar("tpl/alogin.tpl", $array), "300px").'</div>';
	echo $style->get("footer.tpl");
}
}
	elseif($_SESSION['logged']) {
	if(!$main->getvar['page']) {
		$main->getvar['page'] = "home";
	}
	elseif($main->getvar['page'] == "logout") {
		session_destroy();
		$main->redirect("?page=home");
	}
	$content = acp();
	echo $style->get("header.tpl");
	echo $content;
	echo $style->get("footer.tpl");
}

//End the sctipt
include(LINK ."output.php");
?>
