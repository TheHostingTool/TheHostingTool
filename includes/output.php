<?php
//////////////////////////////
// The Hosting Tool
// Output
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

// Check if called by script
if(!defined("THT")){die();}

echo $style->prepare(ob_get_clean()); // Prepare and output the modified buffer
?>
