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

// Compile THT
define("LINK", "../includes/");
include(LINK ."compiler.php");

// THT Variables
define("PAGE", "Support Area");

ob_start();

if(!$main->getvar['page']) {
    $main->getvar['page'] = "kb";
}
    $query = $db->query("SELECT * FROM `<PRE>supportnav` WHERE `link` = '{$main->getvar['page']}'");
    $page = $db->fetch_array($query);
    $header = $page['visual'];
    $link = "pages/". $main->getvar['page'] .".php";
if($db->config("senabled") == 0) {
    $html = $db->config("smessage");
}
else {
    if(!file_exists($link)) {
        $html = "Seems like the .php is non existant. Is it deleted?";
    }
    else {
        // If deleting something
        if(preg_match("/[\.*]/", $main->getvar['page']) == 0) {
            include($link);
            $content = new page;
            if(isset($main->getvar['sub'])) {
                ob_start();
                $content->content();
                $html = ob_get_contents(); # Retrieve the HTML
                ob_clean(); # Flush the HTML
            }
            elseif($content->navlist) {
                $html = $content->description();
            }
            else {
                ob_start();
                $content->content();
                $html = ob_get_contents(); # Retrieve the HTML
                ob_clean(); # Flush the HTML
            }
        }
        else {
            $html = "You trying to hack me? You've been warned. An email has been sent.. May I say, Owned?";
            $email->staff("Possible Hacking Attempt", "A user has been logged trying to hack your copy of THT, their IP is: ". $_SERVER['REMOTE_ADDR']);
        }
    }
}
    echo '<div>';
    echo $main->table($header, $html);
    echo '</div>';

$data = ob_get_contents();
ob_end_clean();

echo $style->get("header.tpl");
echo $data;
echo $style->get("footer.tpl");

// Output
include(LINK ."output.php");
