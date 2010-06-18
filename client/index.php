<?php
//////////////////////////////
// The Hosting Tool
// Client Area
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Compile THT
define("LINK", "../includes/");
include(LINK ."compiler.php");

//THT Variables
define("PAGE", "Client Area");

//Main ACP Function - Creates the ACP basically
function client() {
	global $main;
	global $db;
	global $style;
	global $type;
	global $email;
	ob_start(); # Stop the output buffer
		
	if(!$main->getvar['page']) { 
		$main->getvar['page'] = "home";
	}
	$query = $db->query("SELECT * FROM `<PRE>clientnav` WHERE `link` = '{$main->getvar['page']}'");
	$page = $db->fetch_array($query);
	$header = $page['visual'];
	$link = "pages/". $main->getvar['page'] .".php";
	if(!file_exists($link)) {
		$html = "Seems like the .php is non existant. Is it deleted?";	
	}
	else {
		//If deleting something
		if(preg_match("/[\.*]/", $main->getvar['page']) == 0) {
			include($link);
			$content = new page;
			// Main Side Bar HTML
			$nav = "Sidebar";
			if(!$db->config("delacc")) {
				$sub = $db->query("SELECT * FROM `<PRE>clientnav` WHERE `link` != 'delete'");
			}
			else {
				$sub = $db->query("SELECT * FROM `<PRE>clientnav`");
			}
			while($row = $db->fetch_array($sub)) {
				$array2['IMGURL'] = $row['icon'];
				$array2['LINK'] = "?page=".$row['link'];
				$array2['VISUAL'] = $row['visual'];
				$array['LINKS'] .= $style->replaceVar("tpl/sidebarlink.tpl", $array2);
			}
				# Types Navbar
			$navquery = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `userid` = '{$_SESSION['cuser']}'");
			$navdata = $db->fetch_array($navquery);
			$class = $type->createType($type->determineType($navdata['pid']));
			if($class->clientNav) {
				foreach($class->clientNav as $key2 => $value)  {
					$array2['IMGURL'] = $value[2];
					$array2['LINK'] = "?page=type&type=".$type->determineType($navdata['pid'])."&sub=".$value[1];
					$array2['VISUAL'] = $value[0];
					$array['LINKS'] .= $style->replaceVar("tpl/sidebarlink.tpl", $array2);	
					if($main->getvar['page'] == "type" && $main->getvar['type'] == $type->determineType($navdata['pid']) && $main->getvar['sub'] == $value[1]) {
						define("SUB", $value[3]);
						$header = $value[3];
						$main->getvar['myheader'] = $value[3];
					}
				}
			}
			$type->classes[$type->determineType($navdata['pid'])] = $class;
			
			$array2['IMGURL'] = "delete.png";
			$array2['LINK'] = "?page=logout";
			$array2['VISUAL'] = "Logout";
			$array['LINKS'] .= $style->replaceVar("tpl/sidebarlink.tpl", $array2);
			$sidebar = $style->replaceVar("tpl/sidebar.tpl", $array);
			
			//Page Sidebar
			if($content->navtitle) {
				$subnav = $content->navtitle;
				$sub = $db->query("SELECT * FROM `<PRE>clientnav`");
				foreach($content->navlist as $key => $value) {
					$array2['IMGURL'] = $value[1];
					$array2['LINK'] = "?page=".$main->getvar['page']."&sub=".$value[2];
					$array2['VISUAL'] = $value[0];
					$array3['LINKS'] .= $style->replaceVar("tpl/sidebarlink.tpl", $array2);
				}
				$subsidebar = $style->replaceVar("tpl/sidebar.tpl", $array3);
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
					$html = "Select a sub-page from the sidebar.";
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
	
	if($main->getvar['sub'] && $main->getvar['page'] != "type") {
		foreach($content->navlist as $key => $value) {
			if($value[2] == $main->getvar['sub']) {
				define("SUB", $value[0]);
				$header = $value[0];
			}
		}
	}
	$staffuser = $db->client($_SESSION['cuser']);
	define("SUB", $header);
	define("INFO", '<b>Welcome back, '. $staffuser['user'] .'</b><br />'. SUB);
	
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

if(!$_SESSION['clogged']) {
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
				$query = $db->query("SELECT * FROM `<PRE>users` WHERE `user` = '{$user}' AND `email` = '{$email2}'");
				if($db->num_rows($query) == 0) {
					$main->errors("That account doesn't exist!");
				}
				else {
					$client = $db->fetch_array($query);
					$password = rand(0,99999) . 'P@$$w0rD' . rand(0,99999);
					$cmd = $main->changeClientPassword($client['id'], $password);
					$main->errors("Password reset!");
					$array['PASS'] = $password;
					$emaildata = $db->emailTemplate("reset");
					$email->send($email2, $emaildata['subject'], $emaildata['content'], $array);
				}
			}
		}
		echo '<div align="center">'.$main->table("Client Area - Reset Password", $style->replaceVar("tpl/creset.tpl", $array), "300px").'</div>';
		
		echo $style->get("footer.tpl");
	}
	else {
		define("SUB", "Login");
		define("INFO", " ");
		if($_POST) { # If user submitts form
			if($main->clientLogin($main->postvar['user'], $main->postvar['pass'])) {
				$main->redirect("?page=home");	
			}
			else {
				$main->errors("Incorrect username or password!");
			}
		}
		
		echo $style->get("header.tpl");
		$array[] = "";
		if(!$db->config("cenabled")) {
			define("SUB", "Disabled");
			define("INFO", SUB);
			echo '<div align="center">'.$main->table("Client Area - Disabled", $db->config("cmessage"), "300px").'</div>';
		}
		else {
			echo '<div align="center">'.$main->table("Client Area - Login", $style->replaceVar("tpl/clogin.tpl", $array), "300px").'</div>';
		}
		echo $style->get("footer.tpl");
	}
}
elseif($_SESSION['clogged']) {
	if(!$main->getvar['page']) {
		$main->getvar['page'] = "home";
	}
	elseif($main->getvar['page'] == "logout") {
		session_destroy();
		$main->redirect("?page=home");
	}
	if(!$db->config("cenabled")) {
		define("SUB", "Disabled");
		define("INFO", SUB);
		$content = '<div align="center">'.$main->table("Client Area - Disabled", $db->config("cmessage"), "300px").'</div>';
	}
	else {
		$content = client();
	}
	echo $style->get("header.tpl");
	echo $content;
	echo $style->get("footer.tpl");
}

//End the sctipt
include(LINK ."output.php");
?>
