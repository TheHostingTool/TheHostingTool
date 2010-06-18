<?php
//////////////////////////////
// The Hosting Tool
// Main functions class
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

    $data = preg_replace("/<URL>/si", URL, $data);
    $data = preg_replace("/<IMG>/si", URL . "themes/". THEME ."/images/", $data);

?>
