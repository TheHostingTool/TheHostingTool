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

class page {

    public function content() { # Displays the page
        global $main;
        global $style;
        global $db;
        global $email;
        $query = $db->query("SELECT * FROM `<PRE>cats`");
        if(!$db->num_rows($query)) {
            echo "There are no Knowledge Base Categories/Articles!";
        }
        else {
            if($main->getvar['cat']) {
                $cat = $db->query("SELECT * FROM `<PRE>cats` WHERE `id` = '{$main->getvar['cat']}'");
                if(!$db->num_rows($cat)) {
                    echo "That category doesn't exist!";
                }
                else {
                    echo $main->sub('<img src="<ICONDIR>arrow_rotate_clockwise.png"><a href="?page=kb">Return To Category Selection</a>','');
                    $arts = $db->query("SELECT * FROM `<PRE>articles` WHERE `catid` = '{$main->getvar['cat']}'");
                    if(!$db->num_rows($arts)) {
                        echo "There are no articles in this category!";
                    }
                    else {
                        while($art = $db->fetch_array($arts)) {
                            $array['NAME'] = $art['name'];
                            $array['ID'] = $art['id'];
                            echo $style->replaceVar("tpl/support/artbox.tpl", $array);
                        }
                    }
                }
            }
            elseif($main->getvar['art']) {
                $cat = $db->query("SELECT * FROM `<PRE>articles` WHERE `id` = '{$main->getvar['art']}'");
                if(!$db->num_rows($cat)) {
                    echo "That article doesn't exist!";
                }
                else {
                    $art = $db->fetch_array($cat);
                    $array['NAME'] = $art['name'];
                    $array['CONTENT'] = $art['content'];
                    $array['CATID'] = $art['catid'];
                    echo $style->replaceVar("tpl/support/viewarticle.tpl", $array);
                }
            }
            else {
                while($cat = $db->fetch_array($query)) {
                    $array['NAME'] = $cat['name'];
                    $array['DESCRIPTION'] = $cat['description'];
                    $array['ID'] = $cat['id'];
                    echo $style->replaceVar("tpl/support/catbox.tpl", $array);
                }
            }
        }
    }
}
