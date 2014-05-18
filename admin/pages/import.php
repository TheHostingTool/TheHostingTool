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

    private $classes = array();

    public function content() { # Displays the page
        global $style;
        global $db;
        global $main;
        global $type;
        $files = $main->folderFiles(LINK ."import/");
        foreach($files as $value) {
            $link = LINK. "import/". $value;
            $data = explode(".", $value);
            if(file_exists($link)) {
                include($link);
                $this->classes[$data[0]] = new $data[0];
                $values[] = array($this->classes[$data[0]]->name, $data[0]);
            }
        }
        if(!$main->getvar['do']) {
            if($_POST) {
                $main->redirect("?page=import&do=". $main->postvar['do']);
            }
            $array['DROPDOWN'] = $main->dropdown("do", $values);
            echo $style->replaceVar("tpl/import.tpl", $array);
        }
        else {
            if($this->classes[$main->getvar['do']]) {
                $this->classes[$main->getvar['do']]->import();
            }
            else {
                echo "That method doesn't exist!";
            }
        }
    }
}
