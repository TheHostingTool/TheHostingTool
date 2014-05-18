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
define("PAGE", "Confirm");

global $main;
global $server;
global $style;

echo $style->get("header.tpl"); // Output Header
echo '<div align="center">';

        if(!$_REQUEST['i'] || !$_REQUEST['u'] || !$_REQUEST['c']) {
            echo "Please use the link provided in your e-mail.";
        }
        else {
            if($server->confirm($_REQUEST['i'], $_REQUEST['c'], $_REQUEST['u'])) {
                echo 'Email confirmed.';
            }
            else {
                echo "That user doesn't exist, has already been confirmed, or the confirmation code is invalid.";
            }
        }
echo '</div>'; // End it
echo $style->get("footer.tpl"); // Output Footer

//Output
include(LINK ."output.php");
