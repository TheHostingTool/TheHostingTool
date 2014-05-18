<?php
/* Copyright © 2014 TheHostingTool
 *
 * This file is part of TheHostingTool.
 *
 * TheHostingTool is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TheHostingTool is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with TheHostingTool.  If not, see <http://www.gnu.org/licenses/>.
 */

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
