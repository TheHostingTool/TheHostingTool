<?php
//////////////////////////////
// The Hosting Tool
// Install Script
// By Jonny H and Kevin M
// Released under the GNU-GPL
//////////////////////////////

/*
 * This is a pretty bad attempt at being secure. If you're having
 * problems with it, feel free to comment it out. But it was
 * better than what we had before and should work.
*/

// __FILE__ is an absolute path and we need to make it relative to
// the document root. This file must be called directly and
// directly only.
$compare = explode($_SERVER["DOCUMENT_ROOT"], __FILE__);
if($compare[1] !== $_SERVER["PHP_SELF"]) {
	die("You can only run the install from the <em>".__FILE__."</em> file.");
}

//INSTALL GLOBALS
define("CVER", "1.2.1");
define("NVER", "1.2.2");

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
		$array['ANYTHING'] = "Since you've already ran the install your config has been re-written to not installed. If you are upgrading, this is fine!";
	}
}
if(!file_exists($link)) {
	$array["ANYTHING"] = "Your $link file doesn't exist! Please create it!";
	$disable = true;
}
elseif(!is_writable($link)) {
	$array['ANYTHING'] = "Your $link isn't writeable! Please CHMOD it to 666!";
	$disable = true;
}
echo $style->get("header.tpl");
if($disable) {
	echo '<script type="text/javascript">$(function(){$(".twobutton").attr("disabled", "true");$("#method").attr("disabled", "true");});</script>';
}
echo $style->replaceVar("tpl/install/install.tpl", $array);
echo $style->get("footer.tpl");

include(LINK."output.php"); #Output it

?>
