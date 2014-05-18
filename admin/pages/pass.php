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
        global $style;
        global $db;
        global $main;
        if($_POST) {
            foreach($main->postvar as $key => $value) {
                if($value == "" && !$n) {
                    $main->errors("Please fill in all the fields!");
                    $n++;
                }
            }
            if(!$n) {
                $user = $db->staff($_SESSION['user']);
                if(!$user['password']) {
                    $main->errors("Wrong username!?");
                }
                else {
                    $data = $user;
                    if(md5(md5($main->postvar['old']) . md5($data['salt'])) == $data['password']) {
                        if($main->postvar['new'] != $main->postvar['confirm']) {
                            $main->errors("Your passwords don't match!");
                        }
                        else {
                            $newpass = 	md5(md5($main->postvar['new']) . md5($data['salt']));
                            $db->query("UPDATE `<PRE>staff` SET `password` = '{$newpass}' WHERE `id` = '{$_SESSION['user']}'");
                            $main->errors("Password changed!");
                        }
                    }
                    elseif (password_verify($main->postvar['old'], $data['password'])){
                        if($main->postvar['new'] != $main->postvar['confirm']) {
                            $main->errors("Your passwords don't match!");
                        }
                        else {
                            $newpass = 	password_hash($main->postvar['new'], PASSWORD_BCRYPT, array('cost'=>PASSWORD_COST));
                            $db->query("UPDATE `<PRE>staff` SET `password` = '{$newpass}' WHERE `id` = '{$_SESSION['user']}'");
                            $main->errors("Password changed!");
                        }
                    }
                    else {
                        $main->errors("Your old password was wrong!");
                    }
                }
            }
        }
        echo $style->replaceVar("tpl/changepass.tpl");
    }
}
