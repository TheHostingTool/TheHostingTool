<?php
//Web Server Status v 1.4, Copyright 2002 By Ryan Schwiebert, visit http://www.schwebdesigns.com/
//This script may be freely distributed providing all copyright headers are kept intact. 

//Concept from:
//Abax Server Status v1.04, Copyright 2002 By Nathan Dickman, visit http://www.NathanDickman.com/
//Location of the live or dead server images

//Please change to your server specifications
$live = "../themes/icons/lightbulb.png";
$dead = "../themes/icons/lightbulb_off.png";

//The status checking script
//meddle at your own risk!
//check for port number, default is 80
$link = $_GET['link'].":";
$s_link = str_replace("::", ":", $link);
list($addr,$port)= explode (':',"$s_link");
if (empty($port)){
    $port = 80;
}
//Test the server connection
$churl = @fsockopen(server($addr), $port, $errno, $errstr, 5);
             if (!$churl){
             //echo $errstr;
                header("Location: $dead");
                }
             else {
                   header("Location: $live");             
          }
function server($addr){
         if(strstr($addr,"/")){$addr = substr($addr, 0, strpos($addr, "/"));}
         return $addr;
}
?>
