<?php
//////////////////////////////
// The Hosting Tool
// Client Area - View Package
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	
	public function content() { # Displays the page 
		global $style, $db, $main;
		$data = $db->client($_SESSION['cuser']);
		$query2 = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `userid` = '{$db->strip($data['id'])}'");
		$data3 = $db->fetch_array($query2);
		$query = $db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$db->strip($data3['pid'])}'");
		$data2 = $db->fetch_array($query);
		$array['PACKAGE'] = $data2['name'];
		$array['DESCRIPTION'] = $data2['description'];
		echo $style->replaceVar("tpl/cview.tpl", $array);
	}
}
?>