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
			$pass["TITLE"] = htmlspecialchars($arr["title"]);
			$pass["DESCRIPTION"] = htmlspecialchars($arr["description"]);
			if($arr["required"] == 1) {
				$pass["REQ"] = "<span style=\"color: red;\">*</span>";
				$pass["REQC"] = " checked=\"yes\"";
			}
			else {
				$pass["REQ"] = "";
			}
			// A lame solution but I don't feel like solving this problem at 5 AM...
			$selected = array(false, false, false, false, false, false, false);
			switch($arr["type"]) {
				case "text":
					$selected[0] = true;
					break;
				case "password":
					$selected[1] = true;
					break;
				case "checkbox":
					$selected[2] = true;
					break;
				case "select":
					$selected[3] = true;
					break;
				case "tel":
					$selected[4] = true;
					break;
				case "url":
					$selected[5] = true;
					break;
				case "email":
					$selected[6] = true;
					break;
				case "range":
					$selected[7] = true;
					break;
			}
			$pass["TYPELIST"] = $style->createInput('select', 'cfield-field-typelist-'.$arr["id"], '', array('id' => 'cfield-field-typelist-'.$arr["id"], 'class' => 'cfield-field cfield-field-'.$arr["id"].' cfield-field-typelist'),
				array(
					array('text' => '--- Standard ---', 'value' => 'standard', 'disabled' => true),
					array('text' => 'Text', 'value' => 'text', 'selected' => $selected[0]),
					array('text' => 'Password', 'value' => 'password', 'selected' => $selected[1]),
					array('text' => 'Checkbox', 'value' => 'checkbox', 'selected' => $selected[2]),
					array('text' => 'Select Box', 'value' => 'select', 'selected' => $selected[3]),
					array('text' => '--- HTML5 ---', 'value' => 'html5', 'disabled' => true),
					array('text' => 'Telephone #', 'value' => 'tel', 'selected' => $selected[4]),
					array('text' => 'URL', 'value' => 'url', 'selected' => $selected[5]),
					array('text' => 'Email', 'value' => 'email', 'selected' => $selected[6]),
					array('text' => 'Range', 'value' => 'range', 'selected' => $selected[7])
				)
			);
			$pass["DEFAULTVALUE"] = htmlspecialchars($arr["default"]);
			$pass["REGEX"] = htmlspecialchars($arr["regex"]);
			echo $style->replaceVar("tpl/aorderform/orderfieldbox.tpl", $pass);
		}
		echo '</div>';
		echo $style->replaceVar("tpl/aorderform/bottom.tpl");
	}
	
}

?>
