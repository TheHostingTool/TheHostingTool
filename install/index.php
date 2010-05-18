<?php
//////////////////////////////
// The Hosting Tool
// Install Script
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//INSTAL GLOBALS
define("CVER", "1.1.2");
define("NVER", "1.2");

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
define("SUB", "Enter Details");

$array['VERSION'] = NVER;
$array['ANYTHING'] = "";
if(INSTALL == 1) {
	include(LINK."conf.inc.php");
	if(!writeconfig($sql['host'], $sql['user'], $sql['pass'], $sql['db'], $sql['pre'], "false")) {
		$array['ANYTHING'] = "Your config isn't writeable! Please CHMOD it!";
	}
	else {
		$array['ANYTHING'] = "Since you've already ran the install your config has been re-written to not installed. If you are upgrading, this is fine!";
	}
}
$link = LINK."conf.inc.php";
if(!is_writable($link)) {
	$array['ANYTHING'] = "Your config isn't writeable! Please CHMOD it!";
}
echo $style->get("header.tpl");
echo $style->replaceVar("tpl/install/install.tpl", $array);
echo $style->get("footer.tpl");

include(LINK."output.php"); #Output it

?>