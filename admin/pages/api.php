<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - (Page Name)
// By Kevin M
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
        public $navtitle;
        public $navlist = array();

        public function __construct() {
            $this->navtitle = "XML-API";
            $this->navlist[] = array("Get API Key", "key.png", "getkey");
            $this->navlist[] = array("Generate New Key", "key_add.png", "genkey");
        }

        public function description() {
            return "<strong>Managing XML-API</strong><br />
		Here you can manage the XML-API interface for TheHostingTool. This allows you to
                connect a client to your THT installation so it can perform tasks remotely
                instead of interacting on a database level. TheHostingTool (official) client app
                will use this API to connect.";
        }

	public function content() { # Displays the page
		global $style;
		global $db;
		global $main;
                
                switch($main->getvar['sub']) {
                    default:
                        echo $style->replaceVar("tpl/api/default.tpl");
                        break;
                    case "getkey":
                        $array = array();
                        $array['KEY'] = $db->config("api-key");
                        echo $style->replaceVar("tpl/api/getKey.tpl", $array);
                        break;
                    case "genkey":
                         echo $style->replaceVar("tpl/api/genKey.tpl");
                        break;
                }
	}
}
?>
