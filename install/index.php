<?php
//////////////////////////////
// The Hosting Tool
// Install Script
// By Jonny H and Kevin M
// Released under the GNU-GPL
//////////////////////////////

// The new version of THT we're installing
define("NVER", "1.2.4");
define("NVERCODE", 1010204);

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

// Page generated
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

define("FOLDER", substr($path,$position));

session_start();
ob_start();

define("LINK", "../includes/");

/*
 * Quick little function made to make generating a default site URL
 * easy. Hopefully this will assist a lot of support topics regarding
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
    if($_SERVER["SERVER_PORT"] != 80 && $_SERVER["SERVER_PORT"] != 443) {
        $url .= ":" . $_SERVER["SERVER_PORT"];
    }
	$exploded = explode(basename($_SERVER["PHP_SELF"]), $_SERVER["PHP_SELF"]);
	$url .= dirname($exploded[0]) . "/";
	return $url;
}

function writeconfig($host, $user, $pass, $db, $pre, $true) {
	global $style;
	$array['HOST']	=  addcslashes($host, '\\\'');
	$array['USER']	=  addcslashes($user, '\\\'');
	$array['PASS']	=  addcslashes($pass, '\\\'');
	$array['DB']	=  addcslashes($db, '\\\'');
	$array['PRE']	=  addcslashes($pre, '\\\'');
	$array['TRUE']	=  $true;
	$tpl = $style->replaceVar("tpl/install/conftemp.tpl", $array);
	$link = LINK."conf.inc.php";
	if(is_writable($link)) {
		return file_put_contents($link, $tpl) !== false;
	}
	return false;
}

define("INSTALL", 0);
define("THT", 1);
define("THEME", "Reloaded2"); # Set the theme
define("URL", "../"); # Set url to blank

define("NAME", "THT");
define("PAGE", "Install");
define("SUB", "Choose Method");

require_once(LINK."/class_db.php");
require_once(LINK."/class_main.php");
require_once(LINK."/class_style.php");
$main = new main();
$style = new style();

$array['VERSION'] = NVER;
$array['VCODE'] = NVERCODE;
$array['ANYTHING'] = "";
$link = LINK."conf.inc.php";
if(file_exists($link)) {
	unset($sql);
	require($link);
	if(isset($sql)) {
		$db = new db();
	}
}
$disable = false;
if($sql['install'] == 'true') {
	if(!writeconfig($sql['host'], $sql['user'], $sql['pass'], $sql['db'], $sql['pre'], "false")) {
		$array['ANYTHING'] = "Your $link isn't writeable or does not exist! Please CHMOD it to 666 and make sure it exists!";
		$disable = true;
	}
	else {
		$array['ANYTHING'] = "Since you've already ran the installer, your config has been re-written to the \"not installed\" state. If you are upgrading, this is normal.";
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
