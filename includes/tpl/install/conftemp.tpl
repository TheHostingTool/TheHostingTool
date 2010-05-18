<?php
//////////////////////////////
// The Hosting Tool
// SQL Config
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Are we being called by the script?
if(THT != 1){die("FATAL: Trying to hack?");}

//MAIN SQL CONFIG - Change values accordingly
$sql['host'] = "%HOST%"; #The mySQL Host, usually default - localhost
$sql['user'] = "%USER%"; #The mySQL Username
$sql['pass'] = "%PASS%"; #The mySQL Password
$sql['db'] = "%DB%"; #The mySQL DB, remember to have your username prefix
$sql['pre'] = "%PRE%"; #The mySQL Prefix, usually default unless otherwise

//LEAVE
$sql['install'] = %TRUE%;
?>