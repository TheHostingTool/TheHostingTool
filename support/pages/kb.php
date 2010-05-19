<?php
//////////////////////////////
// The Hosting Tool
// Support Area - Tickets
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

class page {
	
	public function content() { # Displays the page 
		global $main;
		global $style;
		global $db;
		global $email;
		$query = $db->query("SELECT * FROM `<PRE>cats`");
		if(!$db->num_rows($query)) {
			echo "There are no Knowledge Base Categories/Articles!";	
		}
		else {
			if($main->getvar['cat']) {
				$cat = $db->query("SELECT * FROM `<PRE>cats` WHERE `id` = '{$main->getvar['cat']}'");
				if(!$db->num_rows($cat)) {
					echo "That category doesn't exist!";	
				}
				else {
					echo $main->sub('<img src="<ICONDIR>arrow_rotate_clockwise.png"><a href="?page=kb">Return To Category Selection</a>','');
					$arts = $db->query("SELECT * FROM `<PRE>articles` WHERE `catid` = '{$main->getvar['cat']}'");
					if(!$db->num_rows($arts)) {
						echo "There are no articles in this category!";	
					}
					else {
						while($art = $db->fetch_array($arts)) {
							$array['NAME'] = $art['name'];
							$array['ID'] = $art['id'];
							echo $style->replaceVar("tpl/support/artbox.tpl", $array);
						}	
					}
				}
			}
			elseif($main->getvar['art']) {
				$cat = $db->query("SELECT * FROM `<PRE>articles` WHERE `id` = '{$main->getvar['art']}'");
				if(!$db->num_rows($cat)) {
					echo "That article doesn't exist!";	
				}
				else {
					$art = $db->fetch_array($cat);
					$array['NAME'] = $art['name'];
					$array['CONTENT'] = $art['content'];
					$array['CATID'] = $art['catid'];
					echo $style->replaceVar("tpl/support/viewarticle.tpl", $array);
				}	
			}
			else {
				while($cat = $db->fetch_array($query)) {
					$array['NAME'] = $cat['name'];
					$array['DESCRIPTION'] = $cat['description'];
					$array['ID'] = $cat['id'];
					echo $style->replaceVar("tpl/support/catbox.tpl", $array);
				}
			}
		}
	}
}
?>
