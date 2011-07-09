<?php
//////////////////////////////
// The Hosting Tool
// Cron Job
// By Jonny H and Kevin M
// Released under the GNU-GPL
//////////////////////////////

define("LINK", "./");
define("CRON", 1);
include("compiler.php");
set_time_limit(0);

// Stop the output
ob_start(); // Damn, I swear I use too much of these. Their like crack. So fuckin addictive.

$type->createAll(); // Create all the types
$classes = $type->classes; // Because I'm a tad lazy, I set the types to a shorter variable

// Scans through each type
foreach($classes as $key => $value) {
	// Has the type got a cron?
	if($classes[$key]->cron) {
		// Well run it then...
		$classes[$key]->cron();	
	}
}

// 1.2 Run the Paid CronJob. 
$invoice->cron();

// Now we get the data
$data = ob_get_clean(); // Get all the HTML created by the script and clean the buffer
echo $data; // Lets just show it. Tickles my pickle. Don't have to keep checking emails.

// Now we mo truckin email it. Yeah I said it. Aren't I smart?
if($data != "") {
	// TBD: Provide an option to have an email where the cron output is sent to
	//$email->staff("Cron Job", $data);
}

// We've done.. Should I say I.

?>
