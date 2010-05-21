<?php
//////////////////////////////
// The Hosting Tool
// Main functions class
// By Jonny H & Kevin M
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

if(INSTALL == 1) {
	/*
	 * THT Page Generation Time
	 * By Jimmie Lin + Jonny H
	 */
	global $db, $starttime, $style; #Define global, as we are going to pull up things from db
	if($db->config("show_page_gentime") == 1){
		$mtime = explode(' ', microtime());
		$totaltime = $mtime[0] + $mtime[1] - $starttime;
		$gentime = substr($totaltime, 0, 5);
		$array['PAGEGEN'] = $gentime;
		$array['IP'] = getenv('REMOTE_ADDR');
		global $style;
		$pagegen .= $style->replaceVar('tpl/footergen.tpl', $array);
		if($db->config("show_footer")) {
			if(ini_get('safe_mode') or
			strpos(ini_get('disable_functions'), 'shell_exec') != false or
			stristr(PHP_OS, 'Win')) {
				$version[0] = "N/A";
			}
			else {
				$output = shell_exec('mysql -V');
				preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
			}
			global $style;
			$array2['OS'] = PHP_OS;
			$array2['SOFTWARE'] = $_SERVER["SERVER_SOFTWARE"];
			$array2['PHP_VERSION'] = phpversion();
			$array2['MYSQL_VERSION'] = $version[0];
			$array2["SERVER"] = $_SERVER["HTTP_HOST"];
			$array['TITLE'] = $style->replaceVar('tpl/aserverstatus.tpl',$array2);
			$pagegen .= $style->replaceVar('tpl/footerdebug.tpl',$array);
		}
	}
	else{
		$pagegen = '';
	}
	 
	/*
	 * THT Version Function
	 * By Jimmie Lin
	 */
	
	 if($db->config("show_version_id") == 1){
	 $version = $db->config("version");
	}
	else{
		$version = '';
	}
	/*
	 * THT Navigation
	 * By Jonny H - Original Idea: Jimmie
	*/
	if(FOLDER != "install") {
		$navbar = $db->query("SELECT * FROM `<PRE>navbar` ORDER BY `order` ASC");
		while($data2 = $db->fetch_array($navbar)) {
			if(!$db->config("show_acp_menu") && $data2['name'] == "admin") {
				//Do something?
			}
			else {
				$array4['ID'] = "nav_". $data2['name'];
				if(PAGE == $data2['visual']) {
					$array4['ACTIVE'] = ' class="active"';
				}
				else {
					$array4['ACTIVE'] = '';
				}
				$array4['LINK'] = $data2['link'];
				$array4['ICON'] = $data2['icon'];
				$array4['NAME'] = $data2['visual'];
				$navbits .= $style->replaceVar("tpl/navbit.tpl", $array4);
			}
		}
	}
	$array3['NAV'] = $navbits;
	$navigation = $style->replaceVar("tpl/nav.tpl", $array3);
}

/**********************************************************************/
$data = preg_replace("/<THT TITLE>/si", NAME . " :: " . PAGE . " - " . SUB, $data);
$data = preg_replace("/<NAME>/si", NAME, $data);
$data = preg_replace("/<CSS>/si", $this->css(), $data);
$data = preg_replace("/<JAVASCRIPT>/si", $this->javascript(), $data);
$data = preg_replace("/<MENU>/si", $navigation, $data);
$data = preg_replace("/<URL>/si", URL, $data);
$data = preg_replace("/<AJAX>/si", URL."includes/ajax.php", $data);
$data = preg_replace("/<IMG>/si", URL . "themes/". THEME ."/images/", $data);
$data = preg_replace("/<ICONDIR>/si", URL . "themes/icons/", $data);
$data = preg_replace("/<PAGEGEN>/si", $pagegen, $data); #Page Generation Time

$data = preg_replace("/<COPYRIGHT>/si", '<div id="footer">Powered by <a href="http://thehostingtool.com" target="_blank">TheHostingTool</a> '. $version .'</div>', $data);
global $main;
$data = preg_replace("/<ERRORS>/si", '<span class="errors">'.$main->errors().'</span>', $data);
$data = preg_replace("/%INFO%/si", INFO, $data);
?>
