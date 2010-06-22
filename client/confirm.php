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

		if(!$main->getvar['u']) {
			echo "Please use the link provided in your e-mail.";
		}
		else {
			$username = $main->getvar['u'];
			$confirm = $main->getvar['c'];
			$command = $server->confirm($username, $confirm);
			if($command == false) {
				echo 'Confirmation failed, please try to copy and paste the link into your browser.';
			}
			else {
				echo 'Account confirmed.';
			}
		}
echo '</div>'; #End it
echo $style->get("footer.tpl"); #Output Footer

//Output
include(LINK ."output.php");

?>