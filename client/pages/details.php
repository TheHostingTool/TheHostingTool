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

// Check if called by script
if(THT != 1){die();}

class page {

    public function content() { // Displays the page
        global $style, $db, $main;
        $data = $db->client($_SESSION['cuser']);
        $array['USER'] = $data['user'];
        $array['DOMAIN'] = $data['domain'];
        $array['FIRSTNAME'] = $data['firstname'];
        $array['LASTNAME'] = $data['lastname'];
        $array['ADDRESS'] = $data['address'];
        $array['CITY'] = $data['city'];
        $array['STATE'] = $data['state'];
        $array['ZIP'] = $data['zip'];
        $array['COUNTRY'] = $data['country'];
        $array['PHONE'] = $data['phone'];
        $array['DISP'] = "<div>";
            if($_POST) {
                if(!$main->postvar['state']) {
                    $main->errors("Please enter a valid state!");
                    echo $style->replaceVar("tpl/cedit.tpl", $array);
                    return;
                }
                if (!preg_match("/^([a-zA-Z\.\ -])+$/",$main->postvar['state'])) {
                    $main->errors("Please enter a valid state!");
                    echo $style->replaceVar("tpl/cedit.tpl", $array);
                    return;
                }
                if(!$main->postvar['address']) {
                    $main->errors("Please enter a valid address!");
                    echo $style->replaceVar("tpl/cedit.tpl", $array);
                    return;
                }
                if(!preg_match("/^([0-9a-zA-Z\.\ \-])+$/",$main->postvar['address'])) {
                    $main->errors("Please enter a valid address!");
                    echo $style->replaceVar("tpl/cedit.tpl", $array);
                    return;
                }
                if(!$main->postvar['phone']) {
                    $main->errors("Please enter a valid phone number!");
                    echo $style->replaceVar("tpl/cedit.tpl", $array);
                    return;
                }
                if (!preg_match("/^([0-9\-])+$/",$main->postvar['phone'])) {
                    $main->errors("Please enter a valid phone number!");
                    echo $style->replaceVar("tpl/cedit.tpl", $array);
                    return;
                }
                if(strlen($main->postvar['phone']) > 15) {
                    $main->errors("Phone number is to long!");
                    echo $style->replaceVar("tpl/cedit.tpl", $array);
                    return;
                }
                if(!$main->postvar['zip']) {
                    $main->errors("Please enter a valid zip/postal code!");
                    echo $style->replaceVar("tpl/cedit.tpl", $array);
                    return;
                }
                if(strlen($main->postvar['zip']) > 7) {
                    $main->errors("Zip/postal code is to long!");
                    echo $style->replaceVar("tpl/cedit.tpl", $array);
                    return;
                }
                if (!preg_match("/^([0-9a-zA-Z\ \-])+$/",$main->postvar['zip'])) {
                    $main->errors("Please enter a valid zip/postal code!");
                    echo $style->replaceVar("tpl/cedit.tpl", $array);
                    return;
                }
                if(!$main->postvar['city']) {
                    $main->errors("Please enter a valid city!");
                    echo $style->replaceVar("tpl/cedit.tpl", $array);
                    return;
                }
                if (!preg_match("/^([a-zA-Z ])+$/",$main->postvar['city'])) {
                    $main->errors("Please enter a valid city!");
                    echo $style->replaceVar("tpl/cedit.tpl", $array);
                    return;
                }
                $db->query("UPDATE `<PRE>users` SET `state` = '{$main->postvar['state']}' WHERE `id` = '{$_SESSION['cuser']}'");
                $db->query("UPDATE `<PRE>users` SET `address` = '{$main->postvar['address']}' WHERE `id` = '{$_SESSION['cuser']}'");
                $db->query("UPDATE `<PRE>users` SET `phone` = '{$main->postvar['phone']}' WHERE `id` = '{$_SESSION['cuser']}'");
                $db->query("UPDATE `<PRE>users` SET `zip` = '{$main->postvar['zip']}' WHERE `id` = '{$_SESSION['cuser']}'");
                $db->query("UPDATE `<PRE>users` SET `city` = '{$main->postvar['city']}' WHERE `id` = '{$_SESSION['cuser']}'");
                if($main->postvar['change']) {
                    $data = $db->client($_SESSION['cuser']);
                    if(md5(md5($main->postvar['currentpass']) . md5($data['salt'])) == $data['password']) {
                        if($main->postvar['newpass'] === $main->postvar['cpass']) {
                        $cmd = $main->changeClientPassword($_SESSION['cuser'], $main->postvar['newpass']);
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
                    }elseif (password_verify($main->postvar['currentpass'], $data['password'])){
                        if($main->postvar['newpass'] === $main->postvar['cpass']) {
                        $cmd = $main->changeClientPassword($_SESSION['cuser'], $main->postvar['newpass']);
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
                        $main->errors("Your current password is incorrect.");
                    }
                }
                else {
                    $array['DISP'] = "<div style=\"display:none;\">";
                    $main->errors("Details updated!");
                }
            }
            echo $style->replaceVar("tpl/cedit.tpl", $array);
    }
}
