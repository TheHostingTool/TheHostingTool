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
        if(!$db->config("delacc")) {
            die('Disabled.');
        }
        else {
            $_SESSION['cdelete'] = true;
            $array['USER'] = $_SESSION['cuser'];
            echo $style->replaceVar("tpl/cdelete.tpl", $array);
        }
    }
}
