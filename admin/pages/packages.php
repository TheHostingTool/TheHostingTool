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

    public $navtitle;
    public $navlist = array();
    public $defaultNav;

    public function __construct() {
        $this->navtitle = "Packages Sub Menu";
        $this->navlist[] = array("Add Packages", "package_add.png", "add");
        $this->navlist[] = array("Edit Packages", "package_go.png", "edit");
        $this->navlist[] = array("Delete Packages", "package_delete.png", "delete");
        $this->defaultNav = 1;
    }

    public function description() {
        return "<strong>Managing Packages</strong><br />
        Welcome to the Package Management Area. Here you can add, edit and delete web hosting packages. Have fun :)<br />
        To get started, choose a link from the sidebar's SubMenu.";
    }

    private function isAdditional($key) {
        return $key != "name" && $key != "backend" && $key != "description" && $key != "type" && $key != "server" && $key != "admin" && $key != $GLOBALS['csrf']['input-name'] && $key != "direct" && $key != "add" && $key != "cfields";
    }

    public function content() { # Displays the page
        global $main, $db, $style, $type;
        $pkgs = array();
        $query = $db->query("SELECT * FROM `<PRE>packages` ORDER BY `order` ASC");
        while($p = $db->fetch_array($query)) {
            $pkgs[] = array("id" => (int)$p["id"], "name" => $p["name"], "backend" => $p["backend"], "description" => $p["description"],
            "type" => $p["type"], "server" => (int)$p["server"], "admin" => (bool)$p["admin"], "reseller" => (int)$p["reseller"],
            "order" => (int)$p["order"], "hidden" => (bool)$p["is_hidden"], "disabled" => (bool)$p["is_disabled"],
            "domains" => (bool)$p["allow_domains"], "custom" => json_decode($p["custom_fields"], true),
            "additional" => json_decode($p["additional"], true), "hiddenHash" => $p["hidden_hash"]);
        }
        $servers = array("srvtypes" => array(), "srvs" => array());
        if($serversDir = opendir(LINK . "servers")) {
            while(false !== ($entry = readdir($serversDir))) {
                if(!preg_match("/^(\w[\w\.]*)\.php$/", $entry, $matches)) {
                    continue;
                }
                if(!class_exists($matches[1])) {
                    require_once(LINK . "servers/" . $matches[0]);
                }
                $servers["srvtypes"][$matches[1]] = (new $matches[1])->name;
            }
            closedir($serversDir);
        } else {
            echo json_encode(false);
            return;
        }
        $query = $db->query("SELECT `id`, `name`, `type` FROM `<PRE>servers`");
        while($s = $db->fetch_array($query)) {
            if(!array_key_exists($s["type"], $servers["srvs"])) {
                $servers["srvs"][$s["type"]] = array();
            }
            $servers["srvs"][$s["type"]][] = array("id" => $s["id"], "name" => $s["name"]);
        }
        $cfields = array();
        $query = $db->query("SELECT `id`,`title` FROM `<PRE>orderfields` ORDER BY `order` ASC");
        while($c = $db->fetch_array($query)) {
            $cfields[] = array("id" => $c["id"], "name" => $c["title"]);
        }
        $pkgtypes = array();
        foreach($type->classes as $t) {
            $pkgtypes[] = array(
                "tid" => $t->getInternalName(),
                "name" => $t->getName(),
                "fields" => $t->getPkgFields()
            );
        }
        $data = array(
            "INITPKGS" => json_encode($pkgs),
            "INITSRVS" => json_encode($servers),
            "INITCFLD" => json_encode($cfields),
            "INITTYPS" => json_encode($pkgtypes)
        );
        echo $style->replaceVar("tpl/admin/packages/main.tpl", $data);
    }
}
