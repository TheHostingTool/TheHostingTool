<?php
//////////////////////////////
// The Hosting Tool
// Support Area
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Compile THT
define("LINK", "../includes/");
include(LINK ."compiler.php");

//THT Variables
define("PAGE", "Support Area");

ob_start();

if(!$main->getvar['page']) { 
	$main->getvar['page'] = "home";
}
if(!$_SESSION['cuser']) {
	if($main->getvar['page'] == "forgotpass") {
		$main->redirect("../client/?page=forgotpass");
	}
	else {
		define("SUB", "Login");
		define("INFO", "Support Area");
		if($_POST) { # If user submitts form
			if($main->clientLogin($main->postvar['user'], $main->postvar['pass'])) {
				$main->redirect("?page=home");	
			}
			else {
				$main->errors("Incorrect username or password!");
			}
		}
		$array[] = "";
		if($db->config("senabled") == 0) {
			define("SUB", "Disabled");
			define("INFO", "Support Area Disabled");
			echo '<div align="center">'.$main->table("Support Area - Disabled", $db->config("smessage"), "300px").'</div>';
		}
		else {
			echo '<div align="center">'.$main->table("Support Area - Login", $style->replaceVar("tpl/slogin.tpl", $array), "300px").'</div>';
		}
	}
} 
else {
	$query = $db->query("SELECT * FROM `<PRE>supportnav` WHERE `link` = '{$main->getvar['page']}'");
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
			$sub = $db->query("SELECT * FROM `<PRE>supportnav`");
			while($row = $db->fetch_array($sub)) {
				$array2['IMGURL'] = $row['icon'];
				$array2['LINK'] = "?page=".$row['link'];
				$array2['VISUAL'] = $row['visual'];
				$array['LINKS'] .= $style->replaceVar("tpl/sidebarlink.tpl", $array2);
			}
			$sidebar = $style->replaceVar("tpl/sidebar.tpl", $array);
			
			//Page Sidebar
			if($content->navtitle) {
				$subnav = $content->navtitle;
				$sub = $db->query("SELECT * FROM `<PRE>supportnav`");
				foreach($content->navlist as $key => $value) {
					$array2['IMGURL'] = $value[1];
					$array2['LINK'] = "?page=".$main->getvar['page']."&sub=".$value[2];
					$array2['VISUAL'] = $value[0];
					$array3['LINKS'] .= $style->replaceVar("tpl/sidebarlink.tpl", $array2);
				}
				$subsidebar = $style->replaceVar("tpl/sidebar.tpl", $array3);
			}
			if(isset($main->getvar['sub'])) {
				ob_start();
				$content->content();
				$html = ob_get_contents(); # Retrieve the HTML
				ob_clean(); # Flush the HTML
			}
			elseif($content->navlist) {
				$html = $content->description();
			}
			else {
				ob_start();
				$content->content();
				$html = ob_get_contents(); # Retrieve the HTML
				ob_clean(); # Flush the HTML	
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
}

$data = ob_get_contents();
ob_end_clean();

echo $style->get("header.tpl");
echo $data;
echo $style->get("footer.tpl");

//Output
include(LINK ."output.php");

?>
