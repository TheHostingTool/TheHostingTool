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

//Create the class
class style {

    # Start the functions #

    private function error($name, $template, $func) { #Shows a SQL error from main class
        if(INSTALL){
            $error['Error'] = $name;
            $error['Function'] = $func;
            $error['Template'] = $mysqlerror;
            global $main;
            $main->error($error);
        }
    }

    private function getFile($name, $prepare = 0, $override = 0) { # Returns the content of a file
        global $db;
        $link = LINK ."../themes/". THEME . "/" . $name;
        if(!file_exists($link) || $override != 0) {
            $link = LINK . $name;
        }
        if(!file_exists($link) && INSTALL) {
            $error['Error'] = "File doesn't exist!";
            $error['Path'] = $link;
            global $main;
            //$main->error($error);
        }
        else {
            if($prepare) {
                return $this->prepare(file_get_contents($link));
            }
            else {
                return file_get_contents($link);
            }
        }
    }

    public function prepare($data) { # Returns the content with the THT variables replaced
        include(LINK . "variables.php");
        return $data;
    }

    public function get($resource) { // Fetches a theme resource
        return $this->getFile($resource);
    }

    public function css() { // Fetches the CSS and prepares it
        global $db;
        $css = '<link rel="stylesheet" href="'.URL.'includes/css.php" type="text/css" />' . "\n";
        if(FOLDER != "install" && FOLDER != "includes") {
            $css .= '<link rel="stylesheet" href="'.URL.'includes/css/'.(INSTALL?$db->config('ui-theme'):'cupertino').'/jquery-ui.min.css" type="text/css" />' . "\n";
        }
        $css .= '<link rel="stylesheet" href="'.URL.'includes/css/qtip.min.css" type="text/css" />';
        return $css;
    }

    public function replaceVar($template, $array = 0, $style = 0) { #Fetches a template then replaces all the variables in it with that key
        $data = $this->getFile($template, 0, $style);
        if($array) {
            foreach($array as $key => $value) {
                $data = preg_replace("/%". $key ."%/si", $value, $data);
            }
        }
        return $data;
    }

    public function javascript() { # Returns the HTML code for the header that includes all the JS in the javascript folder
        $folder = LINK . "javascript/";
        $html = '<script type="text/javascript" src="'.URL.'includes/javascript/jquery.js"></script>'."\r\n";
        if ($handle = opendir($folder)) { # Open the folder
            while (false !== ($file = readdir($handle))) { # Read the files
                if($file != "." && $file != ".." && $file != "jquery.js" && $file != "simpletip.js") { # Check aren't these names
                    $base = explode(".", $file); # Explode the file name, for checking
                    if($base[count($base)-1] == "js") { # Is it a JS?
                        $html .= '<script type="text/javascript" src="'.URL.'includes/javascript/'.$file.'"></script>'."\r\n";
                    }
                }
            }
        }
        $html .= '<script type="text/javascript" src="'.URL.'includes/ckeditor/ckeditor.js"></script>'."\r\n";
        $html .= '<script type="text/javascript" src="'.URL.'includes/ckeditor/adapters/jquery.js"></script>'."\r\n";
        closedir($handle); #Close the folder
        return $html;
    }

        public function notice($good, $message) {
            if($good) {
                //Cool! Everything's OK.
                $color = "green";
            }
            else {
                //Oh no! It's a bad message!
                $color = "red";
            }
            $notice = '<strong><em style="color: '. $color .';">';
            $notice .= $message;
            $notice .= '</em></strong>';
            return $notice;
        }

        // Returns a form input element according to the parameters given
        public function createInput($type, $name, $value = "", $extra = array(), $options = array()) {
            $type = strtolower(trim($type));
            $extraHtml = "";
            foreach($extra as $k => $v) {
                $extraHtml .= $k.'="'.$v.'" ';
            }
            switch($type) {
                case "textarea":
                    return '<textarea name="'.$name.'" '.$extraHtml.'>'.htmlspecialchars($value).'</textarea>';
                    break;
                case "select":
                    $return = '<select name="'.$name.'" '.$extraHtml.'>';
                    foreach($options as $o) {
                        if(array_key_exists("disabled", $o) && $o["disabled"]) { $d = " disabled"; } else { $d = ""; }
                        if(array_key_exists("selected", $o) && $o["selected"]) { $s = " selected"; } else { $s = ""; }
                        $return .= '<option value="'.$o["value"].'"'.$d.$s.'>'.$o["text"].'</option>';
                    }
                    $return .= '</select>';
                    return $return;
                    break;
                default:
                    return '<input type="'.$type.'" name="'.$name.'" value="'.$value.'" '.$extraHtml.'/>';
                    break;
            }
        }

    //Obsolete Functions...

    public function update($template, $code) { # Updates a template
        global $db;
        $query = $db->query("SELECT * FROM `<PRE>templates` WHERE `name` = '{$db->strip($template)}'");
        if($db->num_rows($query) == 0) {
            $this->error("Template not found!", $template, __FUNCTION__);
        }
        else {
            $db->query("UPDATE `<PRE>templates` SET `code` = '{$db->strip($code)}' WHERE `name` = '{$db->strip($template)}'");
        }
    }

    public function delete($template) { # Gets a query and returns the rows/columns as array
        global $db;
        $query = $db->query("DELETE * FROM `<PRE>templates` WHERE `name` = '{$db->strip($template)}'");
    }
}
//End Template
