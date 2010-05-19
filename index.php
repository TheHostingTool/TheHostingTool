<?php
//////////////////////////////
// The Hosting Tool
// Index Page
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

#Include the compiler, creates everything
define("LINK", "includes/");
include(LINK ."compiler.php");

#Retrieve default page and redirect to it
$page = $db->config("default");
if($page != "") {
	$main->redirect($page);
}
?>