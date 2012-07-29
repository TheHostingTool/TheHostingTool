<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - General Settings
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	
	public $navtitle;
	public $navlist = array();
							
	public function __construct() {
		$this->navtitle = "General Settings Sub Menu";
		$this->navlist[] = array("General Configuration", "world.png", "paths");
		$this->navlist[] = array("Security Settings", "lock.png", "security");
		$this->navlist[] = array("Signup Form", "user_red.png", "signup");
		$this->navlist[] = array("Terms of Service", "application_edit.png", "tos");
		$this->navlist[] = array("Client Area", "user_go.png", "client");
		$this->navlist[] = array("Support Area", "help.png", "support");
		$this->navlist[] = array("Email Configuration", "email.png", "email");
	}
	
	public function description() {
		return "<strong>System Settings</strong><br />
		This is where you can control the way TheHostingTool operates. Most options available for you to configure<br />
		can be found in one of the sub-menus. Check out the Look &amp; Feel center on the main Admin CP navigation to
		change style-related settings.";
	}
	
	public function content() { // Displays the page
		global $main;
		global $style;
		global $db;
		if($_POST) {
			foreach($main->postvar as $key => $value) {
				if($value == "") {
					$main->errors("Please fill in all fields!");
                    $this->contentSwitch($main->getvar["sub"]);
                    return;
				}
			}
			foreach($main->postvar as $key => $value) {
				$db->updateConfig($key, $value);
			}
			$main->errors("Settings Updated!");
		}
        $this->contentSwitch($main->getvar["sub"]);
	}

    private function contentSwitch($page) {
        global $main, $style, $db;
        switch($page) {
            default: // General options
                $array['NAME'] = $db->config("name");
                $array['URL'] = $db->config("url");
                $array['WWWDROPDOWN'] = $main->dropDown("wwwsubdomain", array(array('Force WWW', 'www'), array('Force No WWW', 'nowww'), array('Both', 'both')), $db->config('wwwsubdomain'));
                $values[] = array("Admin Area", "admin");
                $values[] = array("Order Form", "order");
                $values[] = array("Client Area", "client");
                $array['DROPDOWN'] = $main->dropDown("default", $values, $db->config("default"));
                $timezoneList = array();
                foreach(DateTimeZone::listIdentifiers() as $tz) {
                    $timezoneList[] = array($tz, $tz);
                }
                $array['TZDROPDOWN'] = $main->dropDown("timezone", $timezoneList, $db->config("timezone"));
                // Update timezone right now in the event it just changed
                date_default_timezone_set($db->config("timezone"));
                $array['DATETIME'] = date(DateTime::RFC2822);
                echo $style->replaceVar("tpl/pathsettings.tpl", $array);
                break;

            case "security": // Security settings
                global $db;
                $values[] = array("Yes", "1");
                $values[] = array("No", "0");
                $array['SHOW_VERSION_ID'] = $main->dropDown("show_version_id", $values, $db->config("show_version_id"));
                $array['SHOW_ACP_IN_MENU'] = $main->dropDown("show_acp_menu", $values, $db->config("show_acp_menu"));
                $array['SHOW_PAGE_GENTIME'] = $main->dropDown("show_page_gentime", $values, $db->config("show_page_gentime"));
                $array['SHOW_WHM_SSL'] = $main->dropDown("whm-ssl", $values, $db->config("whm-ssl"));
                $array['SHOW_FOOTER'] = $main->dropDown("show_footer", $values, $db->config("show_footer"));
                echo $style->replaceVar("tpl/asecurity.tpl", $array);
                break;

            case "tos": // Change the Terms of Service
                global $db;
                $array['TOS'] = $db->config("tos");
                echo $style->replaceVar("tpl/tos.tpl", $array);
                break;

            case "signup": // Change some signup and order form options
                $values[] = array("Enabled", "1");
                $values[] = array("Disabled", "0");
                $array['MULTIPLE'] = $main->dropDown("multiple", $values, $db->config("multiple"));
                $array['TLDONLY'] = $main->dropDown("tldonly", $values, $db->config("tldonly"));
                $array['GENERAL'] = $main->dropDown("general", $values, $db->config("general"));
                $array['MESSAGE'] = $db->config("message");
                echo $style->replaceVar("tpl/signupsettings.tpl", $array);

                break;

            case "client": // Change Client CP options
                $values[] = array("Enabled", "1");
                $values[] = array("Disabled", "0");
                $array['DELACC'] = $main->dropDown("delacc", $values, $db->config("delacc"));
                $array['CENABLED'] = $main->dropDown("cenabled", $values, $db->config("cenabled"));
                $array['CMESSAGE'] = $db->config("cmessage");
                $array['ALERTS'] = $db->config("alerts");
                echo $style->replaceVar("tpl/clientsettings.tpl", $array);
                break;

            case "support": // Change support center options
                $values[] = array("Enabled", "1");
                $values[] = array("Disabled", "0");
                $array['SENABLED'] = $main->dropDown("senabled", $values, $db->config("senabled"));
                $array['SMESSAGE'] = $db->config("smessage");
                echo $style->replaceVar("tpl/supportsettings.tpl", $array);
                break;

            case "email": // Change email options
                $values[] = array("PHP Mail", "php");
                $values[] = array("SMTP (PEAR)", "smtp");
                $array['METHOD'] = $main->dropDown("emailmethod", $values, $db->config("emailmethod"), 0);
                $array['EMAILFROM'] = $db->config("emailfrom");
                $array['SMTP_HOST'] = $db->config("smtp_host");
                $array['SMTP_USER'] = $db->config("smtp_user");
                $array['SMTP_PASS'] = $db->config("smtp_password");
                echo $style->replaceVar("tpl/emailsettings.tpl", $array);
                break;
        }
    }
}
?>
