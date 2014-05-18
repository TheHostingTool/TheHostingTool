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

    public $navtitle;
    public $navlist = array();

    public function __construct() {
        $this->navtitle = "Subdomain Sub Menu";
        $this->navlist[] = array("Add Subdomain", "add.png", "add");
        $this->navlist[] = array("Edit Subdomain", "pencil.png", "edit");
        $this->navlist[] = array("Delete Subdomain", "delete.png", "delete");
    }
    public function description() {
        return "<strong>Managing Subdomains</strong><br />
        This is where you add domains so users can make subdomains with them.<br />
        To get started, choose a link from the sidebar's SubMenu.";
    }
    public function content() { // Displays the page
        global $main;
        global $style;
        global $db;
        switch($main->getvar['sub']) {
            default:
                if($_POST) {
                    foreach($main->postvar as $key => $value) {
                        if($value == "" && !$n) {
                            $main->errors("Please fill in all the fields!");
                            $n++;
                        }
                    }
                    if(!$n) {
                        $db->query("INSERT INTO `<PRE>subdomains` (subdomain, server) VALUES('{$main->postvar['subdomain']}', '{$main->postvar['server']}')");
                        $main->errors("Subdomain has been added!");
                    }
                }
                $query = $db->query("SELECT * FROM `<PRE>servers`");
                if($db->num_rows($query) == 0) {
                    echo "There are no servers, you need to add a server first!";
                    return;
                }
                while($data = $db->fetch_array($query)) {
                    $values[] = array($data['name'], $data['id']);
                }
                $array['SERVER'] = $main->dropDown("server", $values);
                echo $style->replaceVar("tpl/addsubdomain.tpl", $array);
            break;

            case "edit":
                if(isset($main->getvar['do'])) {
                    $query = $db->query("SELECT * FROM `<PRE>subdomains` WHERE `id` = '{$main->getvar['do']}'");
                    if($db->num_rows($query) == 0) {
                        echo "That subdomain doesn't exist!";
                    }
                    else {
                        if($_POST) {
                            foreach($main->postvar as $key => $value) {
                                if($value == "" && !$n) {
                                    $main->errors("Please fill in all the fields!");
                                    $n++;
                                }
                            }
                            if(!$n) {
                                $db->query("UPDATE `<PRE>subdomains` SET `subdomain` = '{$main->postvar['subdomain']}',
                                                                      `server` = '{$main->postvar['server']}'
                                                                       WHERE `id` = '{$main->getvar['do']}'");
                                $main->errors("Subdomain edited!");
                                $main->done();
                            }
                        }
                        $data = $db->fetch_array($query);
                        $array['SUBDOMAIN'] = $data['subdomain'];
                        $query = $db->query("SELECT * FROM `<PRE>servers`");
                        while($data = $db->fetch_array($query)) {
                            $values[] = array($data['name'], $data['id']);
                        }
                        $array['SERVER'] = $array['THEME'] = $main->dropDown("server", $values, $data['server']);
                        echo $style->replaceVar("tpl/editsubdomain.tpl", $array);
                    }
                }
                else {
                    $query = $db->query("SELECT * FROM `<PRE>subdomains`");
                    if($db->num_rows($query) == 0) {
                        echo "There are no subdomains to edit!";
                    }
                    else {
                        echo "<ERRORS>";
                        while($data = $db->fetch_array($query)) {
                            echo $main->sub("<strong>".$data['subdomain']."</strong>", '<a href="?page=sub&sub=edit&do='.$data['id'].'"><img src="'. URL .'themes/icons/pencil.png"></a>');
                        }
                    }
                }
                break;

            case "delete":
                if(isset($main->getvar['do'])) {
                    $db->query("DELETE FROM `<PRE>subdomains` WHERE `id` = '{$main->getvar['do']}'");
                    $main->errors("Subdomain Deleted!");
                }
                $query = $db->query("SELECT * FROM `<PRE>subdomains`");
                if($db->num_rows($query) == 0) {
                    echo "There are no subdomains to delete!";
                }
                else {
                    echo "<ERRORS>";
                    while($data = $db->fetch_array($query)) {
                        echo $main->sub("<strong>".$data['subdomain']."</strong>", '<a href="?page=sub&sub=delete&do='.$data['id'].'"><img src="'. URL .'themes/icons/delete.png"></a>');
                    }
                }
            break;
        }
    }
}
