<?php
//////////////////////////////
// The Hosting Tool
// Install Script
// By Jonny H and Kevin M
// Released under the GNU-GPL
//////////////////////////////


/*
 * Quick little function made to make generating a default site URL
 * easy. Hopefully this will assist alot of support topics regarding
 * bad site URLs, as the automatically generated ones should be correct.
*/
function generateSiteUrl() {
	$url = "";
	if(!empty($_SERVER["HTTPS"])) {
		$url .= "https://";
	}
	else {
		$url .= "http://";
	}
	$url .= $_SERVER["SERVER_NAME"];
	$exploded = explode(basename($_SERVER["PHP_SELF"]), $_SERVER["PHP_SELF"]);
	$url .= dirname($exploded[0]) . "/";
	return $url;
}

//INSTALL GLOBALS
define("CVER", "1.2.3");
define("NVER", "1.2.4");

define("LINK", "../includes/"); # Set link
include(LINK."compiler.php"); # Get compiler

function writeconfig($host, $user, $pass, $db, $pre, $true) {
	global $style;
	$array['HOST'] =  $host;
	$array['USER'] =  $user;
	$array['PASS'] =  $pass;
	$array['DB'] =  $db;
	$array['PRE'] =  $pre;
	$array['TRUE'] = $true;
	$tpl = $style->replaceVar("tpl/install/conftemp.tpl", $array);
	$link = LINK."conf.inc.php";
	if(is_writable($link)) {
		file_put_contents($link, $tpl);
		return true;
	}
	else {
		return false;
	}
}

define("THEME", "Reloaded2"); # Set the theme
define("URL", "../"); # Set url to blank

define("NAME", "THT");
define("PAGE", "Install");
define("SUB", "Choose Method");

$array['VERSION'] = NVER;
$array['ANYTHING'] = "";
$link = LINK."conf.inc.php";
$disable = false;
if(INSTALL == 1) {
	include(LINK."conf.inc.php");
	if(!writeconfig($sql['host'], $sql['user'], $sql['pass'], $sql['db'], $sql['pre'], "false")) {
		$array['ANYTHING'] = "Your $link isn't writeable or does not exist! Please CHMOD it to 666 and make sure it exists!!";
		$disable = true;
	}
	else {
		$array['ANYTHING'] = "Since you've already ran the installer, your config has been re-written to the \"not installed\" state. If you are upgrading, this is normal!";
	}
}
if(!file_exists($link)) {
	$array["ANYTHING"] = "Your $link file doesn't exist! Please create it as a blank file and CHMOD it to 666!";
	$disable = true;
}
elseif(!is_writable($link)) {
	$array["ANYTHING"] = "Your $link isn't writeable! Please CHMOD it to 666!";
	$disable = true;
}
echo $style->get("header.tpl");
if($disable) {
	echo '<script type="text/javascript">$(function(){$(".twobutton").attr("disabled", "true");$("#method").attr("disabled", "true");});</script>';
}
$array["GENERATED_URL"] = generateSiteUrl();
echo $style->replaceVar("tpl/install/install.tpl", $array);
echo $style->get("footer.tpl");

include(LINK."output.php"); #Output it

?>
