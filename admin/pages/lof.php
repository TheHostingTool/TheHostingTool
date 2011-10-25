<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - Look and Feel
// By: Nick & Kevin M
// Released under the GNU-GPL
// Thanks to: Jimmie32, Jonny H
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	
	public $navtitle;
	public $navlist = array();
	
	public function __construct() {
		$this->navtitle = "Look and Feel Sub Menu";
		$this->navlist[] = array("Theme Chooser", "layout.png", "tchoose");
		$this->navlist[] = array("Theme Uploader", "layout_add.png", "tupload");
		$this->navlist[] = array("jQuery UI Theme", "palette.png", "ui-theme");
		$this->navlist[] = array("CSS Editor", "css.png", "cssedit");
		$this->navlist[] = array("TPL Editor", "xhtml.png", "tpledit");
		$this->navlist[] = array("NavBar Editor", "link_edit.png", "navedit");
	}
	
	public function description() {
		return "<strong>Look and Feel Administration</strong><br />
		Welcome to the Look and Feel Administration Area. This is where you really make your THT installation your very own. You can change and edit your theme, upload a new one, and even customize your navbar.<br />
		To get started, choose a link from the sidebar's SubMenu.<br />";			
	}
	
	private function checkWritable($file) { // Used for the Css & Tpl Editors - Thanks Jimmie!
		if(!is_writable($file)){
			return false;
		}
		else{
			return true;
		}
	}
	
	public function content() { // Displays the page
		global $main;
		global $style;
		global $db;
		if($_POST) {
				foreach($main->postvar as $key => $value) {
					if($value == "" && !$n) {
						$main->errors("Please fill in all the fields!");
						$n++;
					}
				}
			if(!$n) {
				foreach($main->postvar as $key => $value) {
					$db->updateConfig($key, $value);
				}
				$main->errors("Settings Updated!");
				$main->done();
				}
			}
			switch($main->getvar['sub']) {
				case "tchoose": #theme chooser
					$folder = LINK ."../themes/";
					if ($handle = opendir($folder)) { # Open the folder
						while (false !== ($file = readdir($handle))) { # Read the files
							if($file != "." && $file != ".." && $file != ".svn" && $file != "icons" && $file != "index.html" && $file != "flags") { # Check aren't these names
								$values[] = array($file, $file);
							}
						}
					}
					closedir($handle); #Close the folder
					$array['THEME'] = $main->dropDown("theme", $values, $db->config("theme"));
					echo $style->replaceVar("tpl/themesettings.tpl", $array);
					break;
					
				case "tupload": # Theme Uploader
                    if(!extension_loaded("zip")) {
                        echo "Oh dear. The Zip extension for PHP isn't loaded. Please install and load this extension
                        to use the theme uploader.";
                        return;
                    }
                    $mainVars = array('MAXSIZE' => ini_get('upload_max_filesize'));
                    echo $style->replaceVar("tpl/theme-uploader/main.tpl", $mainVars);
					break;
					
				case "cssedit": #CSS Editor - Thanks Jimmie & Kevin!
					$url = $db->config('url')."themes/".$db->config('theme')."/images/";
					$filetochange = LINK."../themes/".$db->config('theme')."/style.css";
					$filetochangeOpen = file_get_contents($filetochange);
					$filetochangeStripped = str_replace("<IMG>", "&lt;IMG&gt;", $filetochangeOpen);
					$css['CSSCONTENT'] = $filetochangeStripped;
					if($this->checkWritable($filetochange)) {
						$css['DISABLED'] = '';
						$css['READONLY'] = '';
						$css['COMMENTHACK'] = '';
						$css['NOTICE'] = '';
					}
					else {
						$css['DISABLED'] = ' disabled="disabled"';
						$css['READONLY'] = ' readonly="readonly"';
						$css['COMMENTHACK'] = '//';
						$css['NOTICE'] = $style->notice(false, "In order to make changes to this file, please CHMOD it to 666.");
					}
					
					echo $style->replaceVar('tpl/cssedit.tpl', $css);
					break;
					
				case "tpledit": //TPL Editor -  Thanks Jimmie & Kevin!
					// Quite simple redir-tor
					echo $style->replaceVar('tpl/tpledit.tpl');
					break;
				case "navedit"; // Navbar Editor - Not Yet Finished
					echo $style->replaceVar("tpl/navedit/top.tpl");
					$query = $db->query("SELECT * FROM `<pre>navbar` ORDER BY `order` ASC");
					while($data = $db->fetch_array($query)) {
						$array2['ID'] = $data['id'];
						$array2['NAME'] = $data['visual'];
						$array2['ICON'] = $data['icon'];
						$array2['LINK'] = $data['link'];
						$array['LINKS'] .= $style->replaceVar("tpl/navedit/linkbox.tpl", $array2);
					}
					echo $style->replaceVar("tpl/navedit/links.tpl", $array);
					echo $style->replaceVar("tpl/navedit/bottom.tpl", array('NOTICE' => $style->notice(true,
						"Changes have been made! Please <a href=\"javascript:window.location.reload();\">refresh</a> to see them in action!")));
					break;

				case "editheader":
					// anyway. Something is crashing
					unset($css);
					unset($conheader);
					unset($filetochange);	
					unset($filetochangeOpen);
					unset($slash);
					$filetochange = LINK."../themes/".$db->config('theme')."/header.tpl";
					$css['CSSCONTENT'] = htmlentities(file_get_contents($filetochange));
					if(!$this->checkWritable($filetochange)) {
						$css['READONLY'] = ' readonly="readonly"';
						$css['DISABLED'] = ' disabled="disabled"';
						$css['NOTICE'] = $style->notice(false, "In order to make changes to this file, please CHMOD it to 666.");
						$css['COMMENTHACK'] = '//';
					}
					else {
						$css['READONLY'] = '';
						$css['DISABLED'] = '';
						$css['NOTICE'] = '';
						$css['COMMENTHACK'] = '';
					}
					echo $style->replaceVar('tpl/headedit.tpl', $css);
					break;
					
				case "editfooter":
					$filetochange = LINK."../themes/".$db->config('theme')."/footer.tpl";
					$css['CSSCONTENT'] = htmlentities(file_get_contents($filetochange));
					$css['EDITED'] = "Editing your footer template!";
					$css['BUTTON'] = $this->checkWritable($filetochange);
					if(!$this->checkWritable($filetochange)) {
						$css['READONLY'] = ' readonly="readonly"';
						$css['DISABLED'] = ' disabled="disabled"';
						$css['NOTICE'] = $style->notice(false, "In order to make changes to this file, please CHMOD it to 666.");
						$css['COMMENTHACK'] = '//';
					}
					else {
						$css['READONLY'] = '';
						$css['DISABLED'] = '';
						$css['NOTICE'] = '';
						$css['COMMENTHACK'] = '';
					}
					echo $style->replaceVar('tpl/footedit.tpl', $css);
					break;
					
				case "ui-theme":
					$folder = LINK ."./css/";
					foreach($main->folderFiles($folder) as $file) {
						$files[] = array($file, $file);
					}
					$array['THEME'] = $main->dropDown("ui-theme", $files, $db->config("ui-theme"));
					$array['NOTICE'] = $style->notice(true, "jQuery UI Theme Changed!");
					echo $style->replaceVar('tpl/ui-theme/chooser.tpl', $array);
					break;
			}
		}
	}
?>
