<?php
//////////////////////////////
// The Hosting Tool
// Client Area - View Package
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	
	public function content() { # Displays the page 
		global $style, $db, $main, $server, $type;
		$data = $db->client($_SESSION['cuser']);
		$query2 = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `userid` = '{$db->strip($data['id'])}'");
		$data3 = $db->fetch_array($query2);
		$query = $db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$db->strip($data3['pid'])}'");
		$data2 = $db->fetch_array($query);		
		$query3 = $db->query("SELECT * FROM `<PRE>users` WHERE `id` = '{$db->strip($data['id'])}'");
		$data4 = $db->fetch_array($query3);
		$array['USER'] = $data4['user'];
		$array['SIGNUP'] = strftime("%D", $data3['signup']);
		$array['DOMAIN'] = $data3['domain'];
		$array['PACKAGE'] = $data2['name'];
		$array['DESCRIPTION'] = $data2['description'];
        $array['DISPLAY'] = $array['DISPLAY2'] = "display: none;";

        if($type->determineServerType($type->determineServer($data3["pid"])) == "whm") {
            $array['DISPLAY'] = '';
            if($data2['reseller'] == '1') {
                $array['DISPLAY2'] = '';
            }
            if($_GET["go"] && !isset($_POST["passwd"])) {
                echo '<form action="" method="post"><div align="center">Password: <input autofocus="on" name="passwd" type="password"><input value="Login" type="submit"></div></form>';
                return;
            }
        }
		
		if($_POST) {
                if($_GET["go"] && isset($_POST["passwd"]) && $array['DISPLAY'] == '') {
                    $host = $db->query("SELECT `host` FROM `<PRE>servers` WHERE `id` = '{$type->determineServer($data3["pid"])}'");
                    $host = $db->fetch_array($host);
                    $go = $_GET["go"] == "whm" ? "whm" : "cpanel";
                    $url = LogMeIn::getLoggedInUrl($data4['user'], $_POST['passwd'], $host['host'], $go);
                    if($url) {
                        $main->redirect($url);
                    }
                    $main->errors("Error logging in.");
                }
				elseif(md5(md5($main->postvar['currentpass']) . md5($data['salt'])) == $data['password']) {
					if($main->postvar['newpass'] == $main->postvar['cpass']) {
						$cmd = $main->changeClientPassword($data3['id'], $main->postvar['cpass']);
						if($cmd === true) {
							$main->errors("Details updated!");
						}
						else {
							$main->errors((string)$cmd);
						}
					}
					else {
						$main->errors("Your passwords don't match!");		
					}
				}
				else {
					$main->errors("Your current password wasn't correct!");	
				}
		}
		
		echo $style->replaceVar("tpl/cview.tpl", $array);
	}
}
?>
