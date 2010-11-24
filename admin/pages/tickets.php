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
	
	private function status($status) { # Returns the text of the status
		switch($status) {
			default:
				return "Other";
				break;
			
			case 1:
				return "Open";
				break;
				
			case 2:
				return "On Hold";
				break;
				
			case 3:
				return "Closed";
				break;
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
	
	private function showReply($id) { # Returns the HTML for a ticket box
		global $db, $main, $style;
		$query = $db->query("SELECT * FROM `<PRE>tickets` WHERE `id` = '{$id}'");
		$data = $db->fetch_array($query);
		$array['AUTHOR'] = $this->determineAuthor($data['userid'], $data['staff']);
		$array['CREATED'] = "Posted on: ". strftime("%D at %T", $data['time']);
		$array['REPLY'] = $data['content'];
		$array['TITLE'] = $data['title'];
		$orig = $db->query("SELECT * FROM `<PRE>tickets` WHERE `id` = '{$data['ticketid']}'");
		$dataorig = $db->fetch_array($orig);
		if($dataorig['userid'] == $data['userid']) {
			$array['DETAILS'] = "Original Poster";	
		}
		elseif($data['staff'] == 1) {
			$array['DETAILS'] = "Staff Member";
		}
		else {
			$array['DETAILS'] = "";	
		}
		return $style->replaceVar("tpl/support/replybox.tpl", $array);
	}
	
	public function content($status) { # Displays the page 
		global $main;
		global $style;
		global $db;
		global $email;
		if(!$main->getvar['do']) {
			$query = $db->query("SELECT * FROM `<PRE>tickets` WHERE `reply` = '0' AND `status` != '3' ORDER BY `time` DESC");
			if(!$db->num_rows($query)) {
				echo "You currently have no new tickets! <i><u><a href=\"?page=ticketsall\" title=\"View all tickets.\">View all tickets</a></u></i>";
			}
			else {
				echo "<div style=\"display: none;\" id=\"nun-tickets\">You currently have no new tickets!</div>";
				$num_rows = $db->num_rows($query);
				echo $style->replaceVar("tpl/support/acpticketjs.tpl", array('NUM_TICKETS' => $num_rows));
				while($data = $db->fetch_array($query)) {
					if($data['urgency'] == "Very High") {
						$urg = " bgcolor=\"#FF0000\">";
					}
					elseif($data['urgency'] == "High") {
						$urg = " bgcolor=\"#FFFF00\">";
					}
					elseif($data['urgency'] == "Medium") {
						$urg = " bgcolor=\"#00FFFF\">";
					}
					else {
						$urg = ">";
					}
					$array['TITLE'] = $data['title'];
					$array['UPDATE'] = $this->lastUpdated($data['id']);
					$array['STATUS'] = $data['status'];
					$array['URGCOLOR'] = $urg;
					$array['ID'] = $data['id'];
					echo $style->replaceVar("tpl/support/acpticketviewbox.tpl", $array);
				}
				echo "<center><i><u><a href=\"?page=ticketsall\" title=\"View all tickets.\">View all tickets</a></u></i></center>";
			}
		}
		else {
			$query = $db->query("SELECT * FROM `<PRE>tickets` WHERE `id` = '{$main->getvar['do']}' OR `ticketid` = '{$main->getvar['do']}' ORDER BY `time` ASC");
			if(!$db->num_rows($query)) {
				echo "That ticket doesn't exist!";	
			}
			else {
				if($_POST) {
					foreach($main->postvar as $key => $value) {
						if($value == "" && !$n && $key != "admin") {
							$main->errors("Please fill in all the fields!");
							$n++;
						}
					}
					if(!$n) {
						$time = time();
						$db->query("INSERT INTO `<PRE>tickets` (title, content, time, userid, reply, ticketid, staff) VALUES('{$main->postvar['title']}', '{$main->postvar['content']}', '{$time}', '{$_SESSION['user']}', '1', '{$main->getvar['do']}', '1')");
						$main->errors("Reply has been added!");
						$data = $db->fetch_array($query);
						$client = $db->staff($_SESSION['user']);
						$user = $db->client($data['userid']);
						$template = $db->emailTemplate("clientresponse");
						$array['TITLE'] = $data['title'];
						$array['STAFF'] = $client['name'];
						$array['CONTENT'] = $main->postvar['content'];
						$email->send($user['email'], $template['subject'], $template['content'], $array);
						$main->redirect("?page=tickets&sub=view&do=". $main->getvar['do']);
					}
				}
				$data = $db->fetch_array($query);
				$array['AUTHOR'] = $this->determineAuthor($data['userid'], $data['staff']);
				$array['TIME'] = strftime("%D", $data['time']);
				$array['NUMREPLIES'] = $db->num_rows($query) - 1;
				$array['UPDATED'] = $this->lastUpdated($data['id']);
				$array['ORIG'] = $this->showReply($data['id']);
				$array['URGENCY'] = $data['urgency'];
				$array['STATUS'] = $this->status($data['status']);
				
				$array['REPLIES'] = "";
				$n = 0;
				while($reply = $db->fetch_array($query)) {
					if(!$n) {
						$array['REPLIES'] .= "<br /><b>Replies</b>";
					}
					$array['REPLIES'] .= $this->showReply($reply['id']);
					$n++;
				}
				
				$array['ADDREPLY'] .= "<br /><b>Change Ticket Status</b>";
				$values[] = array("Open", 1);
				$values[] = array("On Hold", 2);
				$values[] = array("Closed", 3);
				$array3['DROPDOWN'] = $main->dropdown("status", $values, $data['status'], 0);
				$array3['ID'] = $data['id'];
				$array['ADDREPLY'] .= $style->replaceVar("tpl/support/changestatus.tpl", $array3);
				
				$array['ADDREPLY'] .= "<br /><b>Add Reply</b>";
				$array2['TITLE'] = "RE: ". $data['title'];
				$array['ADDREPLY'] .= $style->replaceVar("tpl/support/addreply.tpl", $array2);
				
				echo $style->replaceVar("tpl/support/viewticket.tpl", $array);	
			}
		}
	}
}
?>
