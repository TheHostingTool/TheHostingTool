<?php
//////////////////////////////
// The Hosting Tool
// Compiler
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

#Define the main THT
define("THT", 1);

#Page generated
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

#Start us up
if(CRON != 1) {
	session_start();
}

#Stop the output
ob_start();

#Check for Dependencies
$d = checkForDependencies();
if($d !== true) {
	die((string)$d);
}

#Check PHP Version
$version = explode(".", phpversion());

//Grab DB First
require LINK."/class_db.php"; # Get the file
if(file_exists(LINK."/conf.inc.php")) {
	include LINK."/conf.inc.php"; # Get the config
	define("NOCONFIG", false);
}
else {
	define("NOCONFIG", true);
}
if($sql['install']) {
	define("INSTALL", 1);
	$db = new db; # Create the class
	global $db; # Globalise it
}

$folder = LINK;
if ($handle = opendir($folder)) { # Open the folder
	while (false !== ($file = readdir($handle))) { # Read the files
		if($file != "." && $file != "..") { # Check aren't these names
			$base = explode(".", $file); # Explode the file name, for checking
			if($base[1] == "php") { # Is it a php?
				$base2 = explode("_", $base[0]);
				if($base2[0] == "class" && $base2[1] != "db") {
					require $folder."/".$file; # Get the file
					${$base2[1]} = new $base2[1]; # Create the class
					global ${$base2[1]}; # Globalise it
				}
			}
		}
	}
}
closedir($handle); #Close the folder

if(INSTALL == 1) {
	define("THEME", $db->config("theme")); # Set the default theme
	define("URL", $db->config("url")); # Sets the URL THT is located at
	define("NAME", $db->config("name")); # Sets the name of the website
	//Converts all POSTS into variable - DB Friendly.
	if($_POST) {
		foreach($_POST as $key => $value) {
			$main->postvar[$key] = $db->strip($value);
		}
	}
}
//Converts all GET into variable - DB Friendly.
foreach($_GET as $key => $value) {
	if(INSTALL == 1) {
		$main->getvar[$key] = $db->strip($value);
	}
	else {
		$main->getvar[$key] = $value;	
	}
}
$path = dirname($_SERVER['PHP_SELF']);
$position = strrpos($path,'/') + 1;
define("FOLDER", substr($path,$position)); # Add current folder name to global
if(FOLDER != "install" && FOLDER != "includes" && INSTALL != 1) { # Are we installing?	
	$error['Error'] = "THT isn't Installed!";
	$error['What to do'] = "Please run the install script @ <a href='".LINK."../install'>here</a>";
	die($main->error($error));
}

//Resets the error.
$_SESSION['ecount'] = 0;
$_SESSION['errors'] = 0;

//If payment..
if(FOLDER == "client" && $main->getvar['page'] == "invoices" && $main->getvar['iid'] && $_SESSION['clogged'] == 1) {
	$invoice->pay($main->getvar['iid'], "client/index.php?page=invoices");
	echo "You made it this far.. something went wrong.";
}

//SHHHH... SECRET
if($main->getvar['devh4xx']) {
	$array['VERSION'] = $db->config("version");
	$array['THEME'] = $db->config("theme");
	$array['P2H'] = $db->config("p2hcheck");
	$array['URL'] = $db->config("url");
	die($style->replacevar("tpl/info.tpl", $array));
}

function checkForDependencies() {
	//Here, we're going to see if we have the functions that we need. :D
	$needed = array();
	//First things first:
	$version = explode(".", phpversion());
	if($version[0] < 5) {
		die("PHP Version 5 or over is required! You're currently running: " . phpversion());
	}
	if(!function_exists("curl_init")) {
		$needed[] = "cURL";
	}
	if(!function_exists("mysql_connect")) {
		$needed[] = "MySQL";
	}
	if(count($needed) == 0) {
		return true;
	}
	else {
		$output = "The following function(s) are/is needed for
		TheHostingTool to run properly: <ul>";
		foreach($needed as $key => $value) {
			$output .= "<li>$value</li>";
		}
		$output .= "</ul>";
		return $output;
	}
}
?>
