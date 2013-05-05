<?php
//////////////////////////////
// TheHostingTool
// Admin Area - Order Form
// By Kevin Mark
// Released under the GNU-GPL
//////////////////////////////

// Check if called by script
if(THT != 1){die();}

if(!class_exists("Ajax")) { define("PAGE", "Order Form"); }

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
	
	private function _customf() {
		global $db, $style;
		$query = $db->query("SELECT * FROM `<PRE>orderfields` ORDER BY `order` ASC");
        $boxes = "";
        $globalSelectOptCounter = 0;
		while($arr = $db->fetch_array($query)) {
			if(isset($pass)) { unset($pass); }
			$boxes .= $this->buildFieldBox($arr["id"], $arr["title"], $arr["type"], $arr["default"], $arr["description"],
                $arr["required"], $arr["regex"], $arr["extra"], $globalSelectOptCounter);
		}
        $newbox = array("ID" => "new", "TITLE" => "New Field", "REQ" => "", "REQC" => "", "MIN" => "", "MAX" => "",
            "STEP" => "", "SELECTOPTIONS" => "", "SELECTOPTIONS4REAL" => "", "DEFAULTSELECTED" => " selected",
            "CHECKED" => "", "DEFAULTVALUE" => "", "REGEX" => "", "DESCRIPTION" => "", "DELETEDISABLED" => "disabledGrey",
            "HIDDEN" => "hiddenStyle");
        $newbox["TYPELIST"] = $this->buildTypeList("new", array(false,false,false,false,false,false,false,false,false,false));
        $top = array("NEWBOX" => $style->replaceVar("tpl/aorderform/orderfieldbox.tpl", $newbox),
            "GLOBALSELECTOPTIONCOUNTER" => $globalSelectOptCounter);
        echo $style->replaceVar("tpl/aorderform/top.tpl", $top);
        echo $boxes;
		echo $style->replaceVar("tpl/aorderform/bottom.tpl");
	}

    // Referenced by ajax.php!
    public function buildFieldBox($id, $title, $type, $default, $description, $required, $regex, $extra, &$globalSelectOptCounter) {
        $pass["ID"] = $id;
        $pass["TITLE"] = htmlspecialchars($title);
        $pass["DESCRIPTION"] = htmlspecialchars($description);
        if($required == 1) {
            $pass["REQ"] = "*";
            $pass["REQC"] = " checked=\"yes\"";
        }
        else {
            $pass["REQ"] = $pass["REQC"] = "";
        }
        $pass["MIN"] = $pass["MAX"] = $pass["STEP"] = $pass["SELECTOPTIONS"] = $pass["SELECTOPTIONS4REAL"] =
        $pass["DEFAULTSELECTED"] = "";
        if($extra != '') {
            $extra = json_decode($extra);
            $pass["MIN"] = $extra->min != null ? $extra->min : "";
            $pass["MAX"] = $extra->max != null ? $extra->max : "";
            $pass["STEP"] = $extra->step != null ? $extra->step : "";
            if($extra->selectopt != null) {
                $usedSelected = false;
                foreach($extra->selectopt as $s) {
                    $pass["SELECTOPTIONS"] .= '
<tr id="cfield-tr-selecttr-'.$globalSelectOptCounter.'">
    <td>'.htmlspecialchars($s).'</td>
    <td><div style="text-align:right;font-weight:bold;width:100%;">
    <a id="cfield-action-upoption-'.$globalSelectOptCounter.'" class="cfield-action-upoption" href="javascript:void(0);">[Up]</a>
    <a id="cfield-action-downoption-'.$globalSelectOptCounter.'" class="cfield-action-downoption" href="javascript:void(0);">[Down]</a>
    <a id="cfield-action-renameoption-'.$globalSelectOptCounter.'" class="cfield-action-renameoption" href="javascript:void(0);">[Rename]</a>
    <a id="cfield-action-deleteoption-'.$globalSelectOptCounter.'" class="cfield-action-deleteoption" href="javascript:void(0);">[Delete]</a>
    </div></td>
</tr>';
                    $insert = "";
                    if(!$usedSelected && $s == $default) {
                        $usedSelected = true;
                        $insert = "selected";
                    }
                    $pass["SELECTOPTIONS4REAL"] .= '<option id="cfield-field-defaultoption-option-'.$globalSelectOptCounter.'" value="'.htmlspecialchars($s).'" '.$insert.'>'.htmlspecialchars($s).'</option>';
                    $globalSelectOptCounter++;
                }
                $pass["DEFAULTSELECTED"] = $usedSelected ? "" : "selected";
            }
        }

        $pass["CHECKED"] = "";

        // A lame solution but I don't feel like solving this problem at 5 AM...
        $selected = array(false, false, false, false, false, false, false);
        switch($type) {
            case "text":
                $selected[0] = true;
                break;
            case "password":
                $selected[1] = true;
                break;
            case "checkbox":
                $selected[2] = true;
                $pass["CHECKED"] = $default == "1" ? "checked" : "";
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
            case "week":
                $selected[8] = true;
                break;
            case "number":
                $selected[9] = true;
                break;
        }
        $pass["TYPELIST"] = $this->buildTypeList($id, $selected);
        $pass["DEFAULTVALUE"] = htmlspecialchars($default);
        $pass["REGEX"] = htmlspecialchars($regex);
        $pass["DELETEDISABLED"] = $pass["HIDDEN"] =  "";
        global $style;
        return $style->replaceVar("tpl/aorderform/orderfieldbox.tpl", $pass);
    }

    private function buildTypeList($id, $selected) {
        global $style;
        return $style->createInput('select', 'cfield-field-typelist-'.$id, '', array('id' => 'cfield-field-typelist-'.$id, 'class' => 'cfield-field cfield-field-'.$id.' cfield-field-typelist'),
            array(
                array('text' => '--- Standard ---', 'value' => 'standard', 'disabled' => true),
                array('text' => 'Text', 'value' => 'text', 'selected' => $selected[0]),
                array('text' => 'Password', 'value' => 'password', 'selected' => $selected[1]),
                array('text' => 'Checkbox', 'value' => 'checkbox', 'selected' => $selected[2]),
                array('text' => 'Select Box', 'value' => 'select', 'selected' => $selected[3]),
                array('text' => '--- HTML5 ---', 'value' => 'html5', 'disabled' => true),
                array('text' => 'Number', 'value' => 'number', 'selected' => $selected[9]),
                array('text' => 'Telephone #', 'value' => 'tel', 'selected' => $selected[4]),
                array('text' => 'URL', 'value' => 'url', 'selected' => $selected[5]),
                array('text' => 'Email', 'value' => 'email', 'selected' => $selected[6]),
                array('text' => 'Range', 'value' => 'range', 'selected' => $selected[7]),
                array('text' => 'Week', 'value' => 'week', 'selected' => $selected[8])
            )
        );
    }
	
}

?>
