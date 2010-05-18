<?php
/* 
 * //////////////////////////////
 * // The Hosting Tool
 * // XML-API
 * // by Kevin M
 * // Released under the GNU-GPL
 * //////////////////////////////
 */

//Before we do anything, let's "start" XML
header('Content-type: application/xml');

//Compile THT
define("LINK", "../includes/");
include(LINK ."compiler.php");

global $main, $db, $style;

//THT Variables
define("PAGE", "XML-API");

//Here we go...
if(empty($_REQUEST['function'])) {
    $function = false;
}
else {
    $function = $_REQUEST['function'];
}

if(empty($_REQUEST['params'])) {
    $params = false;
}
else {
    $params = $_REQUEST['params'];
}

if(empty($_REQUEST['auth'])) {
    $auth = false;
}
else {
    $auth = $_REQUEST['auth'];
}

//Get the key from the Config
$key = $db->config("api-key");

//Next we need to see if the client has authenticated correctly.
if($params === false) {
    //The method to use if there are no params.
    $hash = hash('sha512', sha1($function) . sha1($key));
    if($hash != $auth) {
        //Uh oh! Bad hash...
        die("<error>Authentication failed.</error>");
    }
}
else {
    $hash = hash('sha512', sha1($function . '|' . $params) . sha1($key));
    if($hash != $auth) {
        //Uh oh! Bad hash...
        die("<error>Authentication failed.</error>");
    }
}

//Now we'll actually start the switch. Which will go through all the commands
//in our API.
switch($function) {
    case "version":
        echo $style->replaceVar('tpl/xml-api/version.tpl', array('VERSION' => $db->config('version')));
        break;
    case "listaccts":
        $query = $db->query("SELECT * FROM `<PRE>users`, `<PRE>user_packs` WHERE `user` LIKE '%' AND <PRE>user_packs.userid = <PRE>users.id ORDER BY `user`");
        $rownum = $db->num_rows($query);
        if($rownum == 0) {
            echo "<error>No users.</error>";
        }
        else {
            $i = 1;
            while($data = $db->fetch_array($query)) {
                $client = $db->client($data['userid']);
                $array['ID'] = $data['id'];
                $array['USERID'] = $data['userid'];
		$array['USER'] = $data['user'];
		$array['DOMAIN'] = $client['domain'];
		$array['URL'] = URL;
                $array['EMAIL'] = $data['email'];
                $array['SIGNUP'] = $data['signup'];
                $array['IP'] = $data['ip'];
                $array['USER'] = $data['user'];
                $array['PID'] = $data['pid'];
                $array['ADDITIONAL'] = $data['additional'];
                if($client['status'] == "2") {
                    $array['STATUS'] = "Suspended";
		}
		elseif($client['status'] == "1") {
                    $array['STATUS'] = "Active";
		}
		elseif($client['status'] == "3") {
                    $array['SATUS'] = "Validating";
		}
		else {
                    $array['STATUS'] = "Other Status";
                }
                if($i == 1) {
                    $listOutput['ACCOUNTS'] = $style->replaceVar('tpl/xml-api/listaccts_one.tpl', $array) . "\n";
                }
                else {
                    $listOutput['ACCOUNTS'] .= $style->replaceVar('tpl/xml-api/listaccts_one.tpl', $array);
                }
                $i++;
            }
            echo $style->replaceVar('tpl/xml-api/listaccts.tpl', $listOutput);
        }
        break;
    case "listpkgs":
        echo $style->replaceVar('tpl/xml-api/listpkgs.tpl');
        break;
}

?>