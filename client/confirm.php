<?php
//////////////////////////////
// The Hosting Tool
// Account/E-mail Confirmation
// By KuJoe
// Released under the GNU-GPL
//////////////////////////////

//Compile THT
define("LINK", "../includes/");
include(LINK ."compiler.php");

//THT Variables
define("PAGE", "Confirm");

global $main;
global $server;
global $style;

echo $style->get("header.tpl"); #Output Header
echo '<div align="center">';

		if(!$_REQUEST['i'] || !$_REQUEST['u'] || !$_REQUEST['c']) {
			echo "Please use the link provided in your e-mail.";
		}
		else {
			if($server->confirm($_REQUEST['i'], $_REQUEST['c'], $_REQUEST['u'])) {
				echo 'Email confirmed.';
			}
			else {
				echo "That user doesn't exist, has already been confirmed, or the confirmation code is invalid.";
			}
		}
echo '</div>'; #End it
echo $style->get("footer.tpl"); #Output Footer

//Output
include(LINK ."output.php");

?>