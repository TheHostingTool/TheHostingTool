<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - Logs
// By KuJoe
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

define("PAGE", "Logs");

class page {
	
	public function content() { # Displays the page 
		global $style;
		global $db;
		global $main;
		
		echo "<div class=\"subborder\"><form id=\"filter\" name=\"filter\" method=\"post\" action=\"\"><select size=\"1\" name=\"show\"><option value=\"all\">ALL</option><option value=\"Registered\">Registered</option><option value=\"Package created\">Package created</option><option value=\"Approved\">Approved</option><option value=\"Declined\">Declined</option><option value=\"Suspended\">Suspended</option><option value=\"Unsuspended\">Unsuspended</option><option value=\"Cancelled\">Cancelled</option><option value=\"Terminated\">Terminated</option><option value=\"cPanel password\">cPanel password change</option><option value=\"Login\">Client Logins (Success/Fail)</option><option value=\"Login successful\">Client Logins (Success)</option><option value=\"Login failed\">Client Logins (Fail)</option><option value=\"STAFF\">Staff Logins (Success/Fail)</option><option value=\"STAFF LOGIN SUCCESSFUL\">Staff Logins (Success)</option><option value=\"STAFF LOGIN FAILED\">Staff Logins (Fail)</option></select><input type=\"submit\" name=\"filter\" id=\"filter\" value=\"Filter Log\" /></form><table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"1\" style=\"border-collapse: collapse\" bordercolor=\"#000000\"><tr bgcolor=\"#EEEEEE\">";
		echo "<td width=\"75\" align=\"center\" style=\"border-collapse: collapse\" bordercolor=\"#000000\">DATE</td><td width=\"60\" align=\"center\" style=\"border-collapse: collapse\" bordercolor=\"#000000\">TIME</td><td width=\"75\" align=\"center\" style=\"border-collapse: collapse\" bordercolor=\"#000000\">USERNAME</td><td align=\"center\" style=\"border-collapse: collapse\" bordercolor=\"#000000\">MESSAGE</td></tr>";
		$l = $main->getvar['l'];
		$p = $main->getvar['p'];
		if (!$main->postvar['show'] && !$main->getvar['show']) {
			$show = "all";
		}
		if (!$main->postvar['show']) {
			$show = $main->getvar['show'];
		}
		else {
			$show = $main->postvar['show'];
			$p = 0;
		}
		if (!($l)) {
			$l = 10;
		}
		if (!($p)) {
			$p = 0;
		}
		if ($show != all) {
			$query = $db->query("SELECT * FROM `<PRE>logs` WHERE `message` LIKE '$show%'");
		}
		else {
			$query = $db->query("SELECT * FROM `<PRE>logs`");
		}
		$pages = intval($db->num_rows($query)/$l);
				if ($db->num_rows($query)%$l) {
					$pages++;
				}
				$current = ($p/$l) + 1;
				if (($pages < 1) || ($pages == 0)) {
					$total = 1;
				}
				else {
					$total = $pages;
				}
				$first = $p + 1;
				if (!((($p + $l) / $l) >= $pages) && $pages != 1) {
					$last = $p + $l;
				}
				else{
					$last = $db->num_rows($query);
				}
				if ($db->num_rows($query) == 0) {
					echo "No logs found.";
				}
				else {
					if ($show != all) {
						$query2 = $db->query("SELECT * FROM `<PRE>logs` WHERE `message` LIKE '$show%' ORDER BY `id` DESC LIMIT $p, $l");
					}
					else {
						$query2 = $db->query("SELECT * FROM `<PRE>logs` ORDER BY `id` DESC LIMIT $p, $l");
					}
					while($data = $db->fetch_array($query2)) {
						$array['USER'] = $data['loguser'];
						$array['DATE'] = strftime("%m/%d/%Y", $data['logtime']);
						$array['TIME'] = strftime("%T", $data['logtime']);
						$array['MESSAGE'] = $data['message'];
					echo $style->replaceVar("tpl/adminlogs.tpl", $array);
					}
				}
		echo "</table></div>";
		echo "<center>";
		if ($p != 0) {
			$back_page = $p - $l;
			echo("<a href=\"$PHP_SELF?page=logs&show=$show&p=$back_page&l=$l\">BACK</a>    \n");
		}

		for ($i=1; $i <= $pages; $i++) {
			$ppage = $l*($i - 1);
			if ($ppage == $p){
				echo("<b>$i</b>\n");
			}
			else{
				echo("<a href=\"$PHP_SELF?page=logs&show=$show&p=$ppage&l=$l\">$i</a> \n");
			}
		}

		if (!((($p+$l) / $l) >= $pages) && $pages != 1) {
			$next_page = $p + $l;
			echo("    <a href=\"$PHP_SELF?page=logs&show=$show&p=$next_page&l=$l\">NEXT</a>");
		}
		echo "</center>";
	}
}
?>