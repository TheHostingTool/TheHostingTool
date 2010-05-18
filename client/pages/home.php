<?php
//////////////////////////////
// The Hosting Tool
// Client Area - Home
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	
	public function content() { # Displays the page 
		global $style, $db, $main, $type;
		$data = $db->client($_SESSION['cuser']);
		$array['DATE'] = strftime("%D", $data['signup']);
		$array['EMAIL'] = $data['email'];
		$array['ALERTS'] = $db->config('alerts');
		$query2 = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `userid` = '{$db->strip($data['id'])}'");
		$data3 = $db->fetch_array($query2);
		$query = $db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$db->strip($data3['pid'])}'");
		$data2 = $db->fetch_array($query);
		$array['PACKAGE'] = $data2['name'];
		$invoicesq = $db->query("SELECT * FROM `<PRE>invoices` WHERE `uid` = '{$db->strip($data['id'])}' AND `is_paid` = '0'");
		$array['INVOICES'] = $db->num_rows($invoicesq);
		switch($data3['status']) {
			default:
				$array['STATUS'] = "Other";
				break;
				
			case "1":
				$array['STATUS'] = "Active";
				break;
				
			case "2":
				$array['STATUS'] = "Suspended";
				break;
				
			case "3":
				$array['STATUS'] = "Awaiting Admin";
				break;
		}
		$classname = $type->determineType($data3['pid']);
		$phptype = $type->classes[$classname];
		if($phptype->clientBox) {
			$box = $phptype->clientBox();	
			$array['BOX'] = $main->sub($box[0], $box[1]);
		}
		else {
			$array['BOX'] = "";	
		}
		echo $style->replaceVar("tpl/clienthome.tpl", $array);
	}
}
?>
