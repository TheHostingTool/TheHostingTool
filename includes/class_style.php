<?php
//////////////////////////////
// The Hosting Tool
// Database (mySQL) Class
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

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

	private function getFile($name, $prepare = 1, $override = 0) { # Returns the content of a file
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

	private function prepareCSS($data) { # Returns the CSS with all tags removed
		include(LINK . "css_variables.php");
		return $data;
	}

	public function get($template) { # Fetch a template
		return $this->getFile($template);
	}

	public function css() { # Fetches the CSS and prepares it
        global $db;
		$css = '<style type="text/css">';
		$css .= $this->prepareCSS($this->getFile("style.css", 0, 0));
		$css .= '</style>' . "\n";
		if(FOLDER != "install" && FOLDER != "includes") {
	        $css .= '<link rel="stylesheet" href="'.URL.'includes/css/'.$db->config('ui-theme').'/jquery-ui.css" type="text/css" />';
		}
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
		$folder = LINK ."javascript/";
		$html .= "<script type=\"text/javascript\" src='".URL."includes/javascript/jquery.js'></script>\n";
		if ($handle = opendir($folder)) { # Open the folder
			while (false !== ($file = readdir($handle))) { # Read the files
				if($file != "." && $file != ".." && $file != "jquery.js" && $file != "simpletip.js") { # Check aren't these names
					$base = explode(".", $file); # Explode the file name, for checking
					if($base[1] == "js") { # Is it a JS?
						$html .= "<script type=\"text/javascript\" src='".URL."includes/javascript/{$file}'></script>\n"; # Creates the HTML
					}
				}
			}
		}
        $html .= "<script type=\"text/javascript\" src='".URL."includes/tinymce/jscripts/tiny_mce/tiny_mce.js'></script>";
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
?>
