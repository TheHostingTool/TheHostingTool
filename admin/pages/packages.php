<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - Packages
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

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
		global $main, $db, $style;
		$pkgs = array();
		$query = $db->query("SELECT * FROM `<PRE>packages` ORDER BY `order` ASC");
		while($p = $db->fetch_array($query)) {
			$pkgs[] = array("id" => (int)$p["id"], "name" => $p["name"], "backend" => $p["backend"], "description" => $p["description"],
			"type" => $p["type"], "server" => (int)$p["server"], "admin" => (bool)$p["admin"], "reseller" => (int)$p["reseller"],
			"order" => (int)$p["order"], "hidden" => (bool)$p["is_hidden"], "disabled" => (bool)$p["is_disabled"],
			"domains" => (bool)$p["allow_domains"]);
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
		$data = array(
			"INITPKGS" => json_encode($pkgs),
			"INITSRVS" => json_encode($servers),
			"INITCFLD" => json_encode($cfields)
		);
		echo $style->replaceVar("tpl/admin/packages/main.tpl", $data);
	}
}
?>
