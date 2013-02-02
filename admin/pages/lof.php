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
            if($main->getvar['sub'] == 'tupload') {
                $this->uploadPost();
                return;
            }
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
					
				case "tupload": // Theme Uploader
                    $this->upload();
					break;
					
				case "cssedit": // CSS Editor
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
					
				case "tpledit":
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

        private function uploadChecks() {
            if(!extension_loaded("zip")) {
                echo "Oh dear. The Zip extension for PHP isn't loaded. Please install and load this extension
                to use the theme uploader.";
                return false;
            }
            if(!is_writable('../themes')) {
                echo "Shoot. Your themes directory isn't writable. Please fix this if you wish to continue.";
                return false;
            }
            return true;
        }

        private function upload() {
            global $style;
            if(!$this->uploadChecks()) {
                return;
            }
            $mainVars = array('MAXSIZE' => ini_get('upload_max_filesize'), 'MAXEXEC' => ini_get('max_execution_time'));
            echo $style->replaceVar("tpl/theme-uploader/main.tpl", $mainVars);
        }

        private function uploadPost() {
            global $main;
            if(!$this->uploadChecks()) {
                return;
            }
            $file = $_FILES['uploadedTheme'];
            $errors = array();
            if($file['error'] != UPLOAD_ERR_OK) {
                switch($file['error']) {
                    case UPLOAD_ERR_SIZE:
                        $errors[] = "Your uploaded file exceeded " . ini_get('upload_max_filesize');
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $errors[] = "Your file was only partially uploaded.";
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $errors[] = "No file was uploaded.";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $errors[] = "A temporary directory is missing.";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $errors[] = "Failed to write to disk.";
                        break;
                    default:
                        $errors[] = "An unknown error occurred: " . $file['error'];
                        break;
                }
            }
            if(preg_match('/^([a-zA-Z0-9]+).zip$/i', $file['name'], $regs)) {
                $themeName = $regs[1];
                $zip = new ZipArchive();
                if($zip->open($file['tmp_name']) === true) {
                    $insecureZip = false;
                    for($i = 0; $i < $zip->numFiles; $i++) {
                        $stat = $zip->statIndex($i);
                        // "Protection" against uploaded server-side script files. Just in case.
                        if(preg_match('/^.+\.((?:php[3-5]?)|(?:cgi)|(?:pl)|(?:phtml))$/i', basename($stat['name']), $regs2)) {
                            $errors[] = strtoupper($regs2[1]) . ' is not a valid file type in a theme zip.';
                            $insecureZip = true;
                            break;
                        }
                    }
                    if(!$insecureZip) {
                        if(is_dir('../themes/'.$themeName) && isset($_POST['overwrite']) && $_POST['overwrite'] == 'overwrite') {
                            $this->recursiveRemoveDirectory('../themes/'.$themeName);
                        }
                        if(is_dir('../themes/'.$themeName)) {
                            $errors[] = "A theme named <code>".$themeName."</code> already exists and was not overwritten.";
                        }
                        else {
                            if(mkdir('../themes/'.$themeName)) {
                                if($zip->extractTo(realpath('../themes/').'/'.$themeName)) {
                                    $errors[] = '<code>'.$themeName.'</code> successfully uploaded!';
                                }
                                else {
                                    $errors[] = 'Could not extract the contents of the zip file to the new theme directory.';
                                }
                            }
                            else {
                                $errors[] = 'Could not create a new directory for your theme.';
                            }
                        }
                    }
                    $zip->close();
                }
                else {
                    $errors[] = "Couldn't open <code>".$file['tmp_name']." as a zip file.</code>";
                }
            }
            else {
                $errors[] = "<code>".$file['name']."</code> is not a valid theme name.";
            }

            if(count($errors) > 0) {
                foreach($errors as $error) {
                    $main->errors($error . "<br />");
                }
                $this->upload();
                return;
            }
        }

        // Source: http://lixlpixel.org/recursive_function/php/recursive_directory_delete/
        private function recursiveRemoveDirectory($directory, $empty=FALSE)
        {
            if(substr($directory,-1) == '/')
            {
                $directory = substr($directory,0,-1);
            }
            if(!file_exists($directory) || !is_dir($directory))
            {
                return FALSE;
            }elseif(is_readable($directory))
            {
                $handle = opendir($directory);
                while (FALSE !== ($item = readdir($handle)))
                {
                    if($item != '.' && $item != '..')
                    {
                        $path = $directory.'/'.$item;
                        if(is_dir($path))
                        {
                            $this->recursiveRemoveDirectory($path);
                        }else{
                            unlink($path);
                        }
                    }
                }
                closedir($handle);
                if($empty == FALSE)
                {
                    if(!rmdir($directory))
                    {
                        return FALSE;
                    }
                }
            }
            return TRUE;
        }
	}
?>
