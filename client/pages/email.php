<?php
if(THT != 1){die();}

class page {
	public function content($passthrough = false) {
		global $main, $db, $style, $email;
		$id = $_SESSION['cuser'];
		$status = $main->getEmailStatus($id);
		$rep['STATUS'] = $style->notice(true, 'Confirmed');
		$rep['RESEND'] = 'disabled="disabled"';
		$rep['CANCEL'] = 'disabled="disabled"';
		switch($status) {
			case 1:
				$rep['STATUS'] = $style->notice(true, 'Accepted');
				break;
			case 3:
				$rep['CANCEL'] = '';
			case 2:
				$rep['STATUS'] = $style->notice(false, 'Unconfirmed');
				$rep['RESEND'] = '';
				break;
		}
		if($_POST && !$passthrough) {
			if(isset($_POST['change'])) {
				$newemail = $_POST['newemail'];
				if(preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $newemail)) {
					$query = $db->query("SELECT `id` FROM `<PRE>users` WHERE `email` = '{$db->strip($newemail)}' AND `id` != '{$db->strip($id)}'");
					if($db->num_rows($query) != 0) {
						$main->errors("That email address is already in use!");
					} else {
						$db->query("UPDATE `<PRE>users` SET `newemail` = '{$db->strip($newemail)}' WHERE `id` = {$db->strip($id)}");
						if($email->sendConfirmEmail($id)) {
							$main->errors('Confirmation email sent to <code>'.$newemail.'</code>');
						} else {
							$main->errors('Failed to resend email confirmation.');
						}
					}
				} else {
					$main->errors("Email incorrectly formatted.");
				}
			} elseif(isset($_POST['resend']) && $rep['RESEND'] == '') {
				$result = $email->sendConfirmEmail($id);
				if($result) {
					$main->errors("Confirmation email resent to <code>{$result[1]}</code>");
				} else {
					$main->errors("Failed to resend confirmation email.");
				}
			} elseif(isset($_POST['cancel']) && $rep['CANCEL'] == '') {
				$db->query("UPDATE  `<PRE>users` SET `confirmcode` = NULL, `newemail` = NULL WHERE `id` = {$db->strip($id)}");
				$main->errors("Canceled email update.");
			}
			$this->content(true);
			return;
		}
		$client = $db->client($id);
		$rep['EMAIL'] = $client['email'];
		$rep['NEWEMAIL'] = $client['newemail']!==null?$client['newemail']:'';
		echo $style->replaceVar('tpl/clientchangemail.tpl', $rep);
	}
}