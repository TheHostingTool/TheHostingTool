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
							
	public function __construct() {
		$this->navtitle = "Tickets Menu";
		$this->navlist[] = array("New Ticket", "page_white_add.png", "add");
		$this->navlist[] = array("View Tickets", "page_white_go.png", "view");
	}
	
	public function description() {
		return "<strong>Tickets Area</strong><br />
		This is the area where you can add/view tickets that you've created or just created. Any tickets, responses will be sent via email.";	
	}
	
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
	
	public function content() { # Displays the page 
	global $main;
	global $style;
	global $db;
	global $email;
		switch($main->getvar['sub']) {
			default:
				if($_POST) {
					foreach($main->postvar as $key => $value) {
						if($value == "" && !$n && $key != "admin") {
							$main->errors("Please fill in all the fields!");
							$n++;
						}
					}
					if(!$n) {
						$time = time();
						$db->query("INSERT INTO `<PRE>tickets` (title, content, urgency, time, userid) VALUES('{$main->postvar['title']}', '{$main->postvar['content']}', '{$main->postvar['urgency']}', '{$time}', '{$_SESSION['cuser']}')");
						$main->errors("Ticket has been added!");
						$template = $db->emailTemplate("new ticket");
						$array['TITLE'] = $main->postvar['title'];
						$array['URGENCY'] = $main->postvar['urgency'];
						$array['CONTENT'] = $main->postvar['content'];
						$email->staff($template['subject'], $template['content'], $array);
					}
				}
				echo $style->replaceVar("tpl/support/addticket.tpl", $array);
				break;
			
			case "view":
				if(!$main->getvar['do']) {
					$query = $db->query("SELECT * FROM `<PRE>tickets` WHERE `userid` = '{$_SESSION['cuser']}' AND `reply` = '0'");
					if(!$db->num_rows($query)) {
						echo "You currently have no tickets!";	
					}
					else {
						while($data = $db->fetch_array($query)) {
							$array['TITLE'] = $data['title'];
							$array['UPDATE'] = $this->lastUpdated($data['id']);
							$array['ID'] = $data['id'];
							echo $style->replaceVar("tpl/support/ticketviewbox.tpl", $array);
						}
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
								$db->query("INSERT INTO `<PRE>tickets` (title, content, time, userid, reply, ticketid) VALUES('{$main->postvar['title']}', '{$main->postvar['content']}', '{$time}', '{$_SESSION['cuser']}', '1', '{$main->getvar['do']}')");
								$main->errors("Reply has been added!");
								$data = $db->fetch_array($query);
								$client = $db->client($_SESSION['cuser']);
								$template = $db->emailTemplate("new response");
								$array['TITLE'] = $data['title'];
								$array['USER'] = $client['user'];
								$array['CONTENT'] = $main->postvar['content'];
								$email->staff($template['subject'], $template['content'], $array);
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
						
						$n = 0;
						$array['REPLIES'] = "";
						while($reply = $db->fetch_array($query)) {
							if(!$n) {
								$array['REPLIES'] .= "<br /><b>Replies</b>";
							}
							$array['REPLIES'] .= $this->showReply($reply['id']);
							$n++;
						}
						
						if($data['status'] != 3) {
							$array['ADDREPLY'] .= "<br /><b>Add Reply</b>";
							$array2['TITLE'] = "RE: ". $data['title'];
							$array['ADDREPLY'] .= $style->replaceVar("tpl/support/addreply.tpl", $array2);
						}
						else {
							$array['ADDREPLY'] = "";	
						}
						
						echo $style->replaceVar("tpl/support/viewticket.tpl", $array);	
					}
				}
				break;
		}
	}
}
?>
