<?php
//////////////////////////////
// TheHostingTool
// Admin Area - Order Form
// By Kevin Mark
// Released under the GNU-GPL
//////////////////////////////

// Check if called by script
if(THT != 1){die();}

define("PAGE", "Order Form");

class page {
	
	public $navtitle;
	public $navlist = array();
	
	public function __construct() {
		$this->navtitle = "Order Form Actions";
		$this->navlist[] = array("Custom Fields", "table_gear.png", "customf");
	}
	
	public function description() {
		return "<strong>Client Order Form Options</strong><br />
		This is where you can modify and customize your frontend order form. Most notably,
		you can add and edit custom fields to meet your exact needs.";
	}
	
	public function content() {
		global $main;
		// An honest attempt to make this system a little less painful (for me)...
		if(array_key_exists("sub", $main->getvar) && !empty($main->getvar["sub"])) {
			$sub = "_" . strtolower($main->getvar["sub"]);
			if(method_exists($this, $sub)) {
				$this->{$sub}();
				return;
			}
			$main->error(array(__FILE__ => "<code>\$this->$sub</code> isn't a method."));
		}
	}
	
	public function _customf() {
		global $db, $style;
		echo $style->replaceVar("tpl/aorderform/top.tpl");
		$query = $db->query("SELECT * FROM `<PRE>orderfields` ORDER BY `order` ASC");
		if($db->num_rows($query) == 0) {
			echo "<center>".$style->notice(false, "You don't have any custom fields defined!")."</center>";
			return;
		}
		echo '<div id="sortableDiv">';
		while($arr = mysql_fetch_assoc($query)) {
			if(isset($pass)) { unset($pass); }
			$pass["ID"] = $arr["id"];
			$pass["TITLE"] = $arr["title"];
			$pass["DESCRIPTION"] = $arr["description"];
			if($arr["required"] == 1) {
				$pass["REQ"] = "<span style=\"color: red;\">*</span>";
			}
			else {
				$pass["REQ"] = "";
			}
			$pass["TYPELIST"] = $style->createInput('select', 'typelist-'.$arr["id"], '', array('id' => 'typelist-'.$arr["id"]),
				array(
					array('text' => 'Text', 'value' => 'text'),
					array('text' => 'Password', 'value' => 'password'),
					array('text' => 'Checkbox', 'value' => 'checkbox'),
					array('text' => 'Select Box', 'value' => 'select'),
					array('text' => '--- HTML5 ---', 'value' => 'html5', 'disabled' => true),
					array('text' => 'Telephone', 'value' => 'tel'),
					array('text' => 'URL', 'value' => 'url'),
					array('text' => 'Email', 'value' => 'email'),
					array('text' => 'DateTime', 'value' => 'datetime'),
					array('text' => 'Date', 'value' => 'date'),
					array('text' => 'Month', 'value' => 'month'),
					array('text' => 'Week', 'value' => 'week'),
					array('text' => 'Number', 'value' => 'number'),
					array('text' => 'Range', 'value' => 'range')
				)
			);
			$pass["DEFAULTVALUE"] = $arr["default"];
			echo $style->replaceVar("tpl/aorderform/orderfieldbox.tpl", $pass);
		}
		echo '</div>';
		echo $style->replaceVar("tpl/aorderform/bottom.tpl");
	}
	
}

?>
