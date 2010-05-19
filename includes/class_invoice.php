<?php
//////////////////////////////
// The Hosting Tool
// Invoice Class
// By Nick (TheRaptor) + Jonny + Jimmie
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){
	die();
}
//Create the class
class invoice {
	# Start the functions #
	public function create($uid, $amount, $due, $notes) {
		global $db;
		global $email;
		$client = $db->client($uid);
		$emailtemp = $db->emailTemplate("newinvoice");
		$array['USER'] = $client['user'];
		$array['DUE'] = strftime("%D", $due);
		$email->send($client['email'], $emailtemp['subject'], $emailtemp['content'], $array);
		return $db->query("INSERT INTO `<PRE>invoices` (uid, amount, due, notes) VALUES('{$uid}', '{$amount}', '{$due}', '{$notes}')");
	}
	
	public function delete($id) { # Deletes invoice upon invoice id
		global $db;
		$query = $db->query("DELETE FROM `<PRE>invoices` WHERE `id` = '{$id}'"); //Delete the invoice
		return $query;
	}
	public function edit($iid, $uid, $amount, $due, $notes) { # Edit an invoice. Fields created can only be edited?
		global $db;
		$query = $db->query("UPDATE `<PRE>invoices` SET
						   `uid` = '{$uid}',
						   `amount` = '{$amount}',
						   `due` = '{$due}',
						   `notes` = '{$notes}',
						   WHERE `id` = '{$iid}'");
		return $query;
	}

	public function pay($iid, $returnURL = "order/index.php") {
		global $db;
		require_once("paypal/paypal.class.php");
		$paypal = new paypal_class;
		$query = $db->query("SELECT * FROM `<PRE>invoices` WHERE `id` = '{$iid}'");
		$array = $db->fetch_array($query);
		if($_SESSION['cuser'] == $array['uid']) {
			$paypal->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
			$paypal->add_field('business', $db->config('paypalemail'));
			$paypal->add_field('return', $db->config('url')."client/index.php?page=invoices&invoiceID=".$iid);
			$paypal->add_field('cancel_return', $db->config('url')."client/index.php?page=invoices&invoiceID=".$iid);
			$paypal->add_field('notify_url',  $db->config('url')."client/index.php?page=invoices&invoiceID=".$iid);
			$paypal->add_field('item_name', 'THT Order: '.$array['notes']);
			$paypal->add_field('amount', $array['amount']);
			$paypal->add_field('currency_code', $db->config("currency"));
			$paypal->submit_paypal_post(); // submit the fields to paypal
		}
		else {
			echo "You don't seem to be the person who owns that invoice!";	
		}
	}
	
	public function cron(){
		global $db, $server;
		$time = time();
		$query = $db->query("SELECT * FROM `<PRE>packages` WHERE `type` = 'paid'");
		while($array = $db->fetch_array($query)){
			$id = intval($array['id']);
			$query2 = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `pid` = '{$id}'");
			while($array2 = $db->fetch_array($query2)){
				$uid = intval($array2['userid']);
				$query3 = $db->query("SELECT * FROM `<PRE>invoices` WHERE `uid` = '{$uid}' LIMIT 1");
				if(mysql_num_rows($query3) > 0){
					$array3 = $db->fetch_array($query3);
					#$userinfo = $db->client($uid);
					if($time > strtotime($array3['created'])+2592000){
						$this->create($uid, $array3['amount'], $time+intval($db->config("suspensiondays")*24*60*60), $array3['notes']); # Create Invoice
					}
					
					$lastmonth = $time-2592000;
					$suspenddays = intval($db->config('suspensiondays'));
					$suspendseconds = $suspenddays*24*60*60;
					$terminateseconds = intval($db->config('terminationdays'))*24*60*60;
					if($array3['due'] < $time and $array3['is_paid'] == 0){
						if(($time-$suspendseconds) > intval($array3['due']) and $this->is_paid($array3['id']) !== false){
							$server->suspend($array2['id']);
						}
						elseif(($time-$suspendseconds-$terminateseconds) > intval($array3['due']) and $this->is_paid($array3['id']) !== false){
							$server->terminate($array2['id']);
						}
					}
				}
				else{ # User has no invoice yet
					$monthly = $array["additional"];
					// monthly=50,add=Add Package
					$monthly = explode(",", $monthly);
					$monthly = explode("=", $monthly[0]);
					$amount = intval($monthly);
					$this->create($uid, $amount, $time+2592000, ""); # Create Invoice
				}
			}
		}
	}
	
	public function set_paid($iid) { # Pay the invoice by giving invoice id
		global $db, $server;
		$query = $db->query("UPDATE `<PRE>invoices` SET `is_paid` = '1' WHERE `id` = '{$iid}'");
		$query2 = $db->query("SELECT * FROM `<PRE>invoices` WHERE `id` = '{$iid}' LIMIT 1");
		$data2 = $db->fetch_array($query2);
		$query3 = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `userid` = '{$data2['uid']}'");
		$data3 = $db->fetch_array($query3);
		$server->unsuspend($data3['id']);
		return $query;
	}
	
	public function set_unpaid($iid) { # UnPay the invoice by giving invoice id - Don't think this will be useful
		global $db;
		$query = $db->query("UPDATE `<PRE>invoices` SET `is_paid` = '0' WHERE `id` = '{$iid}'");
		return $query;
	}
	
	public function is_paid($id) { # Is the invoice paid - True = Paid / False = Not
		global $db;
		$data = $db->fetch_array($db->query("SELECT * FROM `<PRE>invoices` WHERE `id` = '{$id}'"));
		if($data['is_paid']) {
			return true;	
		}
		else {
			return false;	
		}
	}

}
//End Invoice
?>
