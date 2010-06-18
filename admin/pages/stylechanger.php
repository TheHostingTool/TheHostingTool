<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - Style Modifier
// By Jimmie Lin
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
			 	
	public $navtitle;
	public $navlist = array();
	
	public function __construct() {
		$this->navtitle = "Style Editor Sub Menu";
		$this->navlist[] = array("Edit CSS", "css.png", "css");
		//$this->navlist[] = array("Edit Header Template", "xhtml.png", "header");
		$this->navlist[] = array("Edit Templates", "xhtml.png", "tpls");
	}

	public function description() {
		return "<strong>Style Editor</strong><br />
		Welcome to the style editor! Here you can do a few basic changes on your style so you can adapt it for your host.<br />
		To start, click a link in your Sub-Menu.";	
	}
	
	private function checkWritable($file) {
		if(!is_writable($file)){
			return "<i>Cannot edit file, please CHMOD to 666</i>";
		}
		else{
			return '<input type="submit" value="Save it!" name="editme" id="editme" />';
		}
	}
	
	
	public function content() { # Displays the page 
		global $main;
		global $page;
		global $style;
		global $db;
		
		switch($main->getvar['sub']) {
		   
		   case "switcher":
		      # Switches it!
		      if($_POST['tpl']){
		      	$tpl = $_POST['tpl'];
		      	$url = $db->config('url')."admin/";
		      	header("Location: $url?page=stylechanger&sub=$tpl");
			  }
			  else{
			  	echo "Sorry, a few required vars are missing. You can't access this page individually!";
			  }
		   break;
		   
		   case "tpls":
		      # Quite simple redir-tor
		      echo $style->replaceVar('tpl/tpledit.tpl');
		   break;
		   
		   case "css":
		      /*unset($css);
		      unset($url);
		      unset($filetochange);
		      unset($filetochangeOpen);
		      unset($slash);*/
              if ($_POST) {
				  die(print_r($_POST));
				  $url = $db->config('url')."themes/".$db->config('theme')."/images/";
				  $slash = stripslashes(str_replace("&lt;IMG&gt;", "<IMG>", $_POST['css'])); #Strip it back
				  $filetochange = LINK."../themes/".$db->config('theme')."/style.css";
				  file_put_contents($filetochange, $slash);
				  $css['EDITED'] = "Edited CSS Successfully";
		      }
			  else {
				$css['EDITED'] = "Editing CSS Style..";  
			  }
			  $url = $db->config('url')."themes/".$db->config('theme')."/images/";
			  $filetochange = LINK."../themes/".$db->config('theme')."/style.css";
			  $filetochangeOpen = file_get_contents($filetochange);
			  $filetochangeStripped = str_replace("<IMG>", "&lt;IMG&gt;", $filetochangeOpen);
			  $css['CSSCONTENT'] = $filetochangeStripped;
			  $css['BUTTON'] = $this->checkWritable($filetochange);
                          if(!is_writable($filetochange)) {
                              $css['READONLY'] = ' readonly="readonly"';
                              $css['CODEPRESS'] = '';
                              
                          }
                          else {
                              $css['READONLY'] = '';
                              $css['CODEPRESS'] = 'codepress ';
                          }
			  echo $style->replaceVar('tpl/cssedit.tpl', $css);
		   break;
		   
		   case "header":
		      # anyway. Something is crashing
		      unset($css);
		      unset($conheader);
		      unset($filetochange);
		      unset($filetochangeOpen);
		      unset($slash);
              if ($_POST['edit']) {
              $slash = stripslashes(str_replace("&lt;THT TITLE&gt;", "<THT TITLE>", $_POST['edit'])); # Yay, strip it
              $slash = str_replace("&lt;JAVASCRIPT&gt;", "<JAVASCRIPT>", $slash); #jav
              $slash = str_replace("&lt;CSS&gt;", "<CSS>", $slash); #css
              $slash = str_replace("&lt;ICONDIR&gt;", "<ICONDIR>", $slash); #icondir
              $slash = str_replace("&lt;IMG&gt;", "<IMG>", $slash);
              $slash = str_replace("&lt;MENU&gt;", "<MENU>", $slash);
              $slash = str_replace("&#37;INFO%", "%INFO%", $slash);
              #Alrighty, what to do nexty?
              $filetochange = LINK."../themes/".$db->config('theme')."/header.tpl";
              $filetochangeOpen = fopen($filetochange,"r+");
              fputs($filetochangeOpen,$slash);
              fclose($filetochangeOpen) or die ("Error Closing File!");
              $contheader = str_replace("<THT TITLE>", "&lt;THT TITLE&gt;", file_get_contents($filetochange));
              $contheader = str_replace("<JAVASCRIPT>", "&lt;JAVASCRIPT&gt;", $contheader);
              $contheader = str_replace("<CSS>", "&lt;CSS&gt;", $contheader);
              $contheader = str_replace("<IMG>", "&lt;IMG&gt;", $contheader);
              $contheader = str_replace("<ICONDIR>", "&lt;ICONDIR&gt;", $contheader); #Alrighty, what to do next(y)?
              $contheader = str_replace("%INFO%", "&#37;INFO%", $contheader);
              $contheader = str_replace("<MENU>", "&lt;MENU&gt;", $contheader);
              $css['CSSCONTENT'] = $contheader;
              $css['EDITED'] = "Edited Template Successfully";
              $css['BUTTON'] = $this->checkWritable($filetochange);
              echo $style->replaceVar('tpl/headedit.tpl', $css);
		      }
              else{
              $filetochange = LINK."../themes/".$db->config('theme')."/header.tpl";
              $contheader = str_replace("<THT TITLE>", "&lt;THT TITLE&gt;", file_get_contents($filetochange));
              $contheader = str_replace("<JAVASCRIPT>", "&lt;JAVASCRIPT&gt;", $contheader);
              $contheader = str_replace("<CSS>", "&lt;CSS&gt;", $contheader);
              $contheader = str_replace("<IMG>", "&lt;IMG&gt;", $contheader);
              $contheader = str_replace("%INFO%", "&#37;INFO%", $contheader);
              $contheader = str_replace("<MENU>", "&lt;MENU&gt;", $contheader);
              $contheader = str_replace("<ICONDIR>", "&lt;ICONDIR&gt;", $contheader); #Alrighty, what to do next(y)?
              $css['CSSCONTENT'] = $contheader;
              $css['EDITED'] = "Editing your header template!";
              $css['BUTTON'] = $this->checkWritable($filetochange);
              if(!is_writable($filetochange)) {
                $css['READONLY'] = ' readonly="readonly"';

              }
              else {
                $css['READONLY'] = '';
              }
              echo $style->replaceVar('tpl/headedit.tpl', $css);
		      }
		   break;
		   
		   case "footer":
              if ($_POST['edit']) {
              if(preg_match("/&lt;COPYRIGHT&gt;/", $_POST['edit'])){
              $slash = stripslashes(str_replace("&lt;PAGEGEN&gt;", "<PAGEGEN>", $_POST['edit'])); # Yay, strip it
              $slash = str_replace("&lt;COPYRIGHT&gt;", "<COPYRIGHT>", $slash); #jav
              #Alrighty, what to do nexty?
              $filetochange = LINK."../themes/".$db->config('theme')."/footer.tpl";
              $filetochangeOpen = fopen($filetochange,"r+");
              fputs($filetochangeOpen,$slash);
              fclose($filetochangeOpen) or die ("Error Closing File!");
              $contheader = str_replace("<PAGEGEN>", "&lt;PAGEGEN&gt;", file_get_contents($filetochange));
              $contheader = str_replace("<COPYRIGHT>", "&lt;COPYRIGHT&gt;", $contheader);
              $css['CSSCONTENT'] = $contheader;
              $css['EDITED'] = "Edited Template Successfully";
              $css['BUTTON'] = $this->checkWritable($filetochange);
              echo $style->replaceVar('tpl/footedit.tpl', $css);
		      }
		      else{
		      # I hate people which removes copy.
		      echo "What are you doing? Trying to remove the copyright? DON'T TRY. I WILL EAT YOU!";
		      }
		      }
              else{
              $filetochange = LINK."../themes/".$db->config('theme')."/footer.tpl";
              $contheader = str_replace("<PAGEGEN>", "&lt;PAGEGEN&gt;", file_get_contents($filetochange));
              $contheader = str_replace("<COPYRIGHT>", "&lt;COPYRIGHT&gt;", $contheader); #Alrighty, what to do next(y)?
              $css['CSSCONTENT'] = $contheader;
              $css['EDITED'] = "Editing your footer template!";
              $css['BUTTON'] = $this->checkWritable($filetochange);
              if(!is_writable($filetochange)) {
                $css['READONLY'] = ' readonly="readonly"';

              }
              else {
                $css['READONLY'] = '';
              }
              echo $style->replaceVar('tpl/footedit.tpl', $css);
		      }
		   break;
		  
		}
	}
}
?>
