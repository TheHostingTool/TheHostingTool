<?php
//////////////////////////////
// The Hosting Tool
// Support Area - Tickets
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	
	public $navtitle;
	public $navlist = array();
	
	private function lastUpdated($id) { # Returns a the date of last updated on ticket id
		global $db;
		$query = $db->query("SELECT * FROM `<PRE>tickets` WHERE `ticketid` = '{$db->strip($id)}' AND `reply` = '1' ORDER BY `time` DESC");
		if(!$db->num_rows($query)) {
			return "None";	
		}
		else {
			$data = $db->fetch_array($query);
			$username = $this->determineAuthor($data['userid'], $data['staff']);
			return strftime("%D - %T", $data['time']) ." by ". $username;
		}
	}
	
	private function determineAuthor($id, $staff) { # Returns the text of the author of a reply
		global $db;
		switch($staff) {
			case 0:
				$client = $db->client($id);
				$username = $client['user'];
				break;
				
			case 1:
				$client = $db->staff($id);
				$username = $client['name'];
				break;
		}
		return $username;
	}
	
	public function content() { # Displays the page 
		global $main;
		global $style;
		global $db;
		global $email;
		if(!$main->getvar['do']) {
			$query = $db->query("SELECT * FROM `<PRE>tickets` WHERE `reply` = '0' AND `status` ORDER BY `time` DESC");
			if(!$db->num_rows($query)) {
				echo "You currently have no new tickets!";
			}
			else {
				echo "<div style=\"display: none;\" id=\"nun-tickets\">You currently have no new tickets!</div>";
				$num_rows = $db->num_rows($query);
				echo $style->replaceVar("tpl/support/acpticketjs.tpl", array('NUM_TICKETS' => $num_rows));
				$css = "font-weight: bold; font-style: italic;";
				while($data = $db->fetch_array($query)) {
					if($data['urgency'] == "Very High") {
						$urg = " bgcolor=\"#ff5555\">";
						$txt = "<span style=\"$css color: #660000;\">Very High</span>";
					}
					elseif($data['urgency'] == "High") {
						$urg = " bgcolor=\"#eeff66\">";
						$txt = "<span style=\"$css color: #ff6611;\">High</span>";
					}
					elseif($data['urgency'] == "Medium") {
						$urg = " bgcolor=\"#66bbff\">";
						$txt = "<span style=\"$css color: blue;\">Medium</span>";
					}
					else {
						$urg = ">";
						$txt = "<span style=\"$css\">Low</span>";
					}
					$array['TITLE'] = $data['title'];
					$array['UPDATE'] = $this->lastUpdated($data['id']);
					$array['STATUS'] = $data['status'];
					$array['URGCOLOR'] = $urg;
					$array['ID'] = $data['id'];
					$array['URGENCYTEXT'] = $txt;
					echo $style->replaceVar("tpl/support/acpticketviewbox.tpl", $array);
				}
				echo "<center><i><u><a href=\"?page=tickets\" title=\"View open tickets.\">View open tickets</a></u></i></center>";
			}
		}
	}
}
?>
