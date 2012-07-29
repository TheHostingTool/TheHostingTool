<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - Home
// By Jonny H & Jimmie L
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
    
    public function checkDir($dir){
    	if (is_dir($dir)) { 
    		return "<div class='warn'><img src='../themes/icons/cross.png' alt='' /> Warning: Your install directory still exists. Please delete it!</div>";
		}
		else{
			return "";
		}
	}
	
	public function checkPerms($file){
		if (is_writable($file)) {
			return "<div class='warn'><img src='../themes/icons/error.png' alt='' /> Warning: Configuration file (conf.inc.php) is still writable, please chmod it to 444!</div>";
		}
		else{
			return "";
		}
	}
    
	public function content() { # Displays the page 
		global $db;
		global $main;
		global $style;
		$install_check = $this->checkDir(LINK ."../install/");
		$conf_check = $this->checkPerms(LINK ."conf.inc.php");
        $updateInfo = $main->checkVersion();
        $upgrademsg = "";
        if($updateInfo['devTime']) {
            $r = $updateInfo['cv']['rev'];
			$updatemsg = "<span style='color:green'>DevTime&trade;</span>";
			if(isset($r)) { $updatemsg = "<a target=\"_blank\" href=\"http://code.google.com/p/thehostingtool/source/detail?r=$r\"><span style='color:green'>r$r</span></a>"; }
        }
        elseif($updateInfo['updateAvailable']) {
            $updatemsg = "<span style='color:red'>Upgrade Available</span>";
            $upgrademsg = "<div class='warn'><img src='../themes/icons/error.png' alt='' /> <acronym title=\"".$updateInfo['nv']['code']."\">v".$updateInfo["nv"]["name"]."</acronym> is now available!</div>";
        }
        else {
			$updatemsg = "<span style='color:green'>Up-To-Date</span>";
		}
		$stats['VERSION'] = "v".$updateInfo["cv"]["name"];
        $stats['VCODE'] = $updateInfo["cv"]["code"];
		$stats['THEME'] = $db->config('theme');
		$stats['CENABLED'] = $main->cleaninteger($db->config('cenabled'));
		$stats['SVID'] = $main->cleaninteger($db->config('show_version_id'));
		$stats['SENABLED'] = $main->cleaninteger($db->config('senabled'));
		$stats['DEFAULT'] = $db->config('default');
		$stats['EMETHOD'] = $db->config('emailmethod');
		$stats['SIGNENABLE'] = $main->cleaninteger($db->config('general'));
		$stats['MULTI'] = $main->cleaninteger($db->config('multiple'));
		$stats['UPDATE'] = $updatemsg;
		$stats['UPG_BOX'] = $upgrademsg;
		$stats_box = $style->replaceVar('tpl/dashboard/stats.tpl', $stats);
		$content = '<strong>Welcome to your Admin Dashboard!</strong><br />Welcome to the dashboard of your Admin Control Panel. In this area you can do the tasks that you need to complete such as manage servers, create packages, manage users.<br />
		Here, you can also change the look and feel of your THT Installation. If you require any help, be sure to ask at the <a href="http://thehostingtool.com/forum" title="THT Community is the official stop for THT Support, THT Modules, Developer Center and more! Visit our growing community now!" class="tooltip">THT Community</a><br /><br />'.$stats_box.'<br />'.$install_check.$conf_check.'</div></div>';
		echo $content;
		if($_POST) {
			foreach($main->postvar as $key => $value) {
				if($value == "" && !$n) {
					$main->errors("Please fill in all the fields!");
					$n++;
				}
			}
			if(!$n) {
				foreach($_POST as $key => $value) {
					// We can use $_POST here because updateResource cleans it.
					$db->updateResource($key, $value);
				}
				$main->errors("Settings Updated!");
				$main->done();
			}
		}
		$array['NOTEPAD'] = $db->resources('admin_notes');
		$content_notepad = $style->replaceVar('tpl/notepad.tpl', $array);
		echo '<br />'; //br it, br it
		echo $main->table('Admin Notepad', $content_notepad, 'auto', 'auto');
		require_once(LINK.'rss/rss_fetch.inc');
		$url = "http://thehostingtool.com/forum/syndication.php?fid=2&limit=3";
		$rss = fetch_rss($url);
		$news = $main->sub("<strong>Add the THT RSS Feed!</strong>", '<a href="http://thehostingtool.com/forum/syndication.php?fid=2" target="_blank" class="tooltip" title="Add the THT RSS Feed!"><img src="<URL>themes/icons/feed.png" /></a>');
		foreach ($rss->items as $item) {
			$array['title'] = $item['title'];
			$array['link'] = $item['link'];
			$array['TIME'] = $item["pubdate"];
            // By some miracle, this works perfectly for correcting MyBB's stupid relative URLs in its feeds
            preg_match_all('/(<(?:a|img) (?:href|src)=(?:"|\'))([-A-Z0-9+&@#\/%?=~_|!:,.;]*[A-Z0-9+&@#\/%=~_|])((?:"|\')[a-z0-9]*[^<>]*\/?>)/si', $item['summary'], &$matches, PREG_SET_ORDER);
            for($matchi = 0; $matchi < count($matches); $matchi++) {
                if(stripos($matches[$matchi][2], 'http') === false) {
                    $item['summary'] = str_replace($matches[$matchi][0], $matches[$matchi][1].'http://thehostingtool.com/forum/'.$matches[$matchi][2].$matches[$matchi][3], $item['summary']);
                }
            }
            $array['SUMMARY'] = $item['summary'];
            
			$news .= $style->replaceVar('tpl/newsitem.tpl', $array);
		}
		echo "<br />";
		echo $main->table('THT News & Updates', $news);
	}
}
?>
