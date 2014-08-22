<?php
/* Copyright © 2014 TheHostingTool
 *
 * This file is part of TheHostingTool.
 *
 * TheHostingTool is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TheHostingTool is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with TheHostingTool.  If not, see <http://www.gnu.org/licenses/>.
 */

define("LINK", "./");
include("compiler.php");

class Ajax {

    public function orderIsUser() {
        if(!$_SESSION['clogged']) {
            echo "0";
        } else {
            echo "1";
        }
    }

    public function acpPadd() {
        global $type;
        global $main;
        echo $type->acpPadd($main->getvar['type']);
    }

    public function pdescription() {
        global $main;
        global $db;
        if(!$main->getvar['id']) {
            echo "Select a package to see the description!";
        } else {
            $query = $db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$main->getvar['id']}'");
            $data  = $db->fetch_array($query);
            echo $data['description'];
        }
    }

    public function usernamecheck() {
        global $main;
        global $db;

        //If it's over 8 characters then complain.
        if(strlen($main->getvar['username']) > 8) {
            echo 0;
            return;
        }
        //If it's less than 4 characters then complain.
        if(strlen($main->getvar['username']) < 4) {
            echo 0;
            return;
        } else {
            //If the first character is a number, then complain.
            if(is_numeric(substr($main->getvar['username'], 0, 1))) {
                echo 0;
                return;
            }
        }
        // Alphanumeric only plz.
        if(!preg_match("/^([0-9a-zA-Z])+$/", $main->getvar['username'])) {
            echo 0;
            return;
        }
        if(!$main->getvar['username']) {
            $_SESSION['check']['username'] = false;
            echo 0;
        } else {
            $query = $db->query("SELECT * FROM `<PRE>users` WHERE `user` = '{$main->getvar['user']}'");
            if($db->num_rows($query) == 0) {
                $_SESSION['check']['username'] = true;
                echo 1;
            } else {
                $_SESSION['check']['username'] = false;
                echo 0;
            }
        }
    }

    public function passwordcheck() {
        global $main;
        if($main->getvar['password'] == ":") {
            $_SESSION['check']['password'] = false;
            echo 0;
            return;
        } else {
            $pass = explode(":", $main->getvar['password']);
            if($pass[0] == $pass[1]) {
                $_SESSION['check']['password'] = true;
                echo 1;
            } else {
                $_SESSION['check']['password'] = false;
                echo 0;
            }
        }
    }

    public function emailcheck() {
        global $main, $db;
        if(!$main->getvar['email']) {
            $_SESSION['check']['email'] = false;
            echo 0;
            return;
        }
        $query = $db->query("SELECT * FROM `<PRE>users` WHERE `email` = '{$main->getvar['email']}'");
        if($db->num_rows($query) != 0) {
            $_SESSION['check']['email'] = false;
            echo 0;
            return;
        } else {
            if($main->check_email($main->getvar['email'])) {
                $_SESSION['check']['email'] = true;
                echo 1;
            } else {
                $_SESSION['check']['email'] = false;
                echo 0;
            }
        }
    }

    public function firstnamecheck() {
        global $main;
        if(!preg_match("/^([a-zA-Z\.\'\ \-])+$/", $main->getvar['firstname'])) {
            $_SESSION['check']['firstname'] = false;
            echo 0;
        } else {
            $_SESSION['check']['firstname'] = true;
            echo 1;
        }
    }

    public function lastnamecheck() {
        global $main;
        if(!preg_match("/^([a-zA-Z\.\'\ \-])+$/", $main->getvar['lastname'])) {
            $_SESSION['check']['lastname'] = false;
            echo 0;
        } else {
            $_SESSION['check']['lastname'] = true;
            echo 1;
        }
    }

    public function addresscheck() {
        global $main;
        if(!preg_match("/^([0-9a-zA-Z\.\ \-])+$/", $main->getvar['address'])) {
            $_SESSION['check']['address'] = false;
            echo 0;
        } else {
            $_SESSION['check']['address'] = true;
            echo 1;
        }
    }

    public function citycheck() {
        global $main;
        if(!preg_match("/^([a-zA-Z ])+$/", $main->getvar['city'])) {
            $_SESSION['check']['city'] = false;
            echo 0;
        } else {
            $_SESSION['check']['city'] = true;
            echo 1;
        }
    }

    public function statecheck() {
        global $main;
        if(!preg_match("/^([a-zA-Z\.\ -])+$/", $main->getvar['state'])) {
            $_SESSION['check']['state'] = false;
            echo 0;
        } else {
            $_SESSION['check']['state'] = true;
            echo 1;
        }
    }

    public function zipcheck() {
        global $main;
        if(strlen($main->getvar['zip']) > 7) {
            echo 0;
            return;
        } else {
            if(!preg_match("/^([0-9a-zA-Z\ \-])+$/", $main->getvar['zip'])) {
                $_SESSION['check']['zip'] = false;
                echo 0;
            } else {
                $_SESSION['check']['zip'] = true;
                echo 1;
            }
        }
    }

    public function phonecheck() {
        global $main;
        if(strlen($main->getvar['phone']) > 15) {
            echo 0;
            return;
        } else {
            if(!preg_match("/^([0-9\-])+$/", $main->getvar['phone'])) {
                $_SESSION['check']['phone'] = false;
                echo 0;
            } else {
                $_SESSION['check']['phone'] = true;
                echo 1;
            }
        }
    }

    //Basic captcha check... thanks http://frikk.tk!
    public function humancheck() {
        global $main;
        if($main->getvar['human'] != $_SESSION["pass"]) {
            $_SESSION['check']['human'] = false;
            echo 0;
        } else {
            $_SESSION['check']['human'] = true;
            echo 1;
        }
    }

    public function clientcheck() {
        if($_SESSION['check']['email'] == true && $_SESSION['check']['username'] == true && $_SESSION['check']['password'] == true && $_SESSION['check']['human'] == true && $_SESSION['check']['address'] == true && $_SESSION['check']['state'] == true && $_SESSION['check']['zip'] == true && $_SESSION['check']['phone'] == true) {
            echo 1;
        } else {
            echo 1;
        }
    }

    public function domaincheck() {
        global $main;
        if(!$main->getvar['domain']) {
            echo 0;
        } else {
            $data = explode(".", $main->getvar['domain']);
            if(!$data[0] || !$data[1]) {
                echo 0;
            } else {
                echo 1;
            }
        }
    }

    public function create() {
        global $main;
        global $server;
        $server->signup();
    }

    // Primary ajax order form endpoint
    public function orderForm() {
        global $db, $server, $type;
        if(empty($_POST["operation"])) {
            return;
        }
        header("Content-type: application/json");
        switch($_POST["operation"]) {
            case "checkUsername":
                $result = $server->checkUsername($type->determineServer((int)$_POST["package"]), $_POST["username"]);
                if($result !== true) {
                    echo json_encode(array("valid" => false, "msg" => $result));
                    return;
                }
                if($db->num_rows($db->query("SELECT * FROM `<PRE>users` WHERE `user` = '{$db->strip($_POST['username'])}'")) != 0) {
                    echo json_encode(array("valid" => false, "msg" => "That username is not available."));
                    return;
                }
                echo json_encode(array("valid" => true, "msg" => null));
                return;
            case "checkEmail":
                if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                    echo json_encode(array("valid" => false, "msg" => "Invalid email."));
                    return;
                }
                if($db->num_rows($db->query("SELECT * FROM `<PRE>users` WHERE `email` = '{$db->strip($_POST['email'])}'")) != 0) {
                    echo json_encode(array("valid" => false, "msg" => "That email is unavailable."));
                    return;
                }
                echo json_encode(array("valid" => true, "msg" => null));
                return;
            case "checkDomain":
                $domainRegex = "/^([a-zA-Z0-9]([a-zA-Z0-9\\-]{0,61}[a-zA-Z0-9])?\\.)+[a-zA-Z]{2,6}$/";
                $subdomain = (int)$_POST["subdomain"];
                if($subdomain == -1) {
                    if(!preg_match($domainRegex, $_POST["domain"])) {
                        echo json_encode(array("valid" => false, "msg" => "Invalid domain."));
                        return;
                    }
                    if($db->num_rows($db->query("SELECT `id` FROM `<PRE>user_packs` WHERE `domain` = '{$db->strip($_POST["domain"])}'")) != 0) {
                        echo json_encode(array("valid" => false, "msg" => "That domain is already in use."));
                        return;
                    }
                    echo json_encode(array("valid" => true, "msg" => null));
                    return;
                }
                $sdquery = $db->query("SELECT `subdomain` FROM `<PRE>subdomains` WHERE `id` = '{$db->strip($subdomain)}'");
                if($db->num_rows($sdquery) == 0) {
                    echo json_encode(array("valid" => false, "msg" => "No such subdomain."));
                    return;
                }
                $sdarray = $db->fetch_array($sdquery);
                $fulldomain = $_POST["domain"] . '.' . $sdarray[0];
                if(!preg_match($domainRegex, $fulldomain)) {
                    echo json_encode(array("valid" => false, "msg" => "Invalid subdomain."));
                    return;
                }
                if($db->num_rows($db->query("SELECT `id` FROM `<PRE>user_packs` WHERE `domain` = '{$db->strip($fulldomain)}'")) != 0) {
                    echo json_encode(array("valid" => false, "msg" => "That subdomain is already in use."));
                    return;
                }
                echo json_encode(array("valid" => true, "msg" => null));
                return;
            case "checkPassword":
                if(!isset($_POST["password"]) || empty($_POST["package"])) {
                    return;
                }
                $password = $_POST["password"];
                $package = (int)$_POST["package"];
                global $server;
                $result = $server->passwdStrength($type->determineServer($package), $password);
                if(is_string($result)) {
                    echo json_encode(array("valid" => false, "msg" => $result));
                    return;
                }
                if(is_array($result)) {
                    if($result["strength"] >= $result["required"]) {
                        echo json_encode(array("valid" => true, "msg" => null));
                        return;
                    }
                    // Avoid revealing raw required password strength (if that matters)
                    echo json_encode(array("valid" => false, "msg" => "That password isn't strong enough. " .
                        (int)(($result['strength']/$result['required']) * 100) . "/100"));
                    return;
                }
                echo json_encode(array("valid" => true, "msg" => null));
                return;
        }
    }

    public function cancelacc() {
        global $db, $main, $type, $server, $email;
        $user  = $main->getvar['user'];
        $pass  = $main->getvar['pass'];
        $query = $db->query("SELECT * FROM `<PRE>users` WHERE `id` = '{$db->strip($user)}'");
        if($db->num_rows($query) == 0) {
            echo "That account doesn't exist!";
        } else {
            $data = $db->fetch_array($query);
            if(password_verify($pass, $data['password'])) {
            #if(md5(md5($pass) . md5($data['salt'])) == $data['password']) {
                $query2 = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `userid` = '{$db->strip($user)}'");
                $data2  = $db->fetch_array($query2);
                if($server->cancel($data2['id'])) {
                    echo "Your account has been cancelled successfully!";
                    session_destroy();
                } else {
                    echo "Your account wasn't cancelled! Try again..";
                }
            } else {
                echo "That password is wrong!";
            }
        }
    }

    public function template() {
        global $main, $db, $style;
        if($_SESSION['logged']) {
            $query = $db->query("SELECT * FROM `<PRE>templates` WHERE `id` = '{$main->getvar['id']}'");
            if($db->num_rows($query) == 0) {
                $array['Error']       = "Template not found!";
                $array['Template ID'] = $main->getvar['id'];
                $main->error($array);
            } else {
                $data = $db->fetch_array($query);
                echo $data['subject'] . "{}[]{}" . $data['description'] . "{}[]{}" . $data['content'];
            }
        }
    }

    public function cat() {
        global $main, $db, $style;
        if($_SESSION['logged']) {
            $query = $db->query("SELECT * FROM `<PRE>cats` WHERE `id` = '{$main->getvar['id']}'");
            if($db->num_rows($query) == 0) {
                $array['Error']       = "Category not found!";
                $array['Category ID'] = $main->getvar['id'];
                $main->error($array);
            } else {
                $data = $db->fetch_array($query);
                echo $data['name'] . "{}[]{}" . $data['description'];
            }
        }
    }

    public function art() {
        global $main, $db, $style;
        if($_SESSION['logged']) {
            $query = $db->query("SELECT * FROM `<PRE>articles` WHERE `id` = '{$main->getvar['id']}'");
            if($db->num_rows($query) == 0) {
                $array['Error']      = "Article not found!";
                $array['Article ID'] = $main->getvar['id'];
                $main->error($array);
            } else {
                $data = $db->fetch_array($query);
                echo $data['name'] . "{}[]{}" . $data['content'] . "{}[]{}" . $data['catid'];
            }
        }
    }

    public function search() {
        global $main, $db, $style;
        if($_SESSION['logged']) {
            //echo '<script type="text/javascript" src="'.URL.'includes/javascript/jquerytooltip.js">';
            $type  = $main->postvar['type'];
            $value = $main->postvar['value'];
            if($main->postvar['num']) {
                $show = $main->postvar['num'];
            } else {
                $show = 10;
            }
            if($main->postvar['page'] != 1) {
                $lower = $main->postvar['page'] * $show;
                $lower = $lower - $show;
                $upper = $lower + $show;
            } else {
                $lower = 0;
                $upper = $show;
            }
            $query  = $db->query("SELECT * FROM `<PRE>users`, `<PRE>user_packs` WHERE `{$type}` LIKE '%{$value}%' AND <PRE>user_packs.userid = <PRE>users.id ORDER BY `{$type}` ASC LIMIT {$lower}, {$upper}");
            $rownum = $db->num_rows($query);
            if($db->num_rows($query) == 0) {
                echo "No clients found!";
            } else {
                while($data = $db->fetch_array($query)) {
                    if($n != $show) {
                        $client          = $db->client($data['userid']);
                        $array['ID']     = $data['userid'];
                        $array['USER']   = $data['user'];
                        $array['DOMAIN'] = $client['domain'];
                        $array['URL']    = URL;
                        if($client['status'] == "2") {
                            $array['TEXT'] = "Unsuspend";
                            $array['FUNC'] = "unsus";
                            $array['IMG']  = "accept.png";
                        } elseif($client['status'] == "1") {
                            $array['TEXT'] = "Suspend";
                            $array['FUNC'] = "sus";
                            $array['IMG']  = "exclamation.png";
                        } elseif($client['status'] == "3") {
                            $array['TEXT'] = "Validate";
                            $array['FUNC'] = "none";
                            $array['IMG']  = "user_suit.png";
                        } elseif($client['status'] == "4") {
                            $array['TEXT'] = "Awaiting Payment";
                            $array['FUNC'] = "none";
                            $array['IMG']  = "money.png";
                        } else {
                            $array['TEXT'] = "Other Status";
                            $array['FUNC'] = "none";
                            $array['IMG']  = "help.png";
                        }
                        echo $style->replaceVar("tpl/clientsearchbox.tpl", $array);
                        $n++;
                    }
                }
                echo '<div class="break"></div>';
                echo '<div align="center">';
                $query = $db->query("SELECT * FROM `<PRE>users`, `<PRE>user_packs` WHERE `{$type}` LIKE '%{$value}%' AND <PRE>user_packs.userid = <PRE>users.id ORDER BY `{$type}` ASC");
                $num   = $db->num_rows($query);
                $pages = ceil($num / $show);
                echo "Page..";
                for($i; $i != $pages + 1; $i += 1) {
                    echo ' <a href="Javascript: page(\'' . $i . '\')">' . $i . '</a>';
                }
                echo '</div>';
            }
        }
    }

    public function sub() {
        global $main, $db, $type;
        $pack   = $main->getvar['pack'];
        $server = $type->determineServer($pack);
        $select = $db->query("SELECT * FROM `<PRE>subdomains` WHERE `server` = '{$server}' ORDER BY `subdomain` ASC");
        while($row = $db->fetch_array($select)) {
            $values[] = array(
                $row['subdomain'],
                $row['subdomain']
            );
        }
        echo $main->dropdown("csub2", $values, $values[0]['subdomain']);
    }

    public function phpinfo() {
        if($_SESSION['logged']) {
            phpinfo();
        }
    }

    public function ticketStatus() {
        if(!$_SESSION['logged']) {
            return;
        }
        global $db;
        global $main;
        $id     = $main->postvar['id'];
        $status = $main->postvar['status'];
        $query  = $db->query("UPDATE `<PRE>tickets` SET `status` = '{$status}' WHERE `id` = '{$id}'");
        if($query) {
            echo "<img src=" . URL . "themes/icons/accept.png>";
        } else {
            echo "<img src=" . URL . "themes/icons/cross.png>";
        }
    }

    public function serverhash() {
        global $main;
        $type = $main->getvar['type'];
        include(LINK . "servers/" . $type . ".php");
        $server = new $type;
        if($server->hash) {
            echo 0;
        } else {
            echo 1;
        }
    }

    public function editserverhash() {
        if($_SESSION['logged']) {
            global $main, $db;
            $type = $main->getvar['type'];
            $id   = $main->getvar['server'];
            include(LINK . "servers/" . $type . ".php");
            $server = new $type;
            if($server->hash) {
                echo 0;
            } else {
                echo 1;
            }
            $query = $db->query("SELECT * FROM `<PRE>servers` WHERE `id` = '{$id}'");
            $data  = $db->fetch_array($query);
            echo ";:;" . $data['accesshash'];
        }
    }

    public function sqlcheck() {
        global $main, $style;
        if(INSTALL != 1) {
            $host = $_GET['host'];
            $user = $_GET['user'];
            $pass = $_GET['pass'];
            $db   = $_GET['db'];
            $pre  = $_GET['pre'];
            //die($_SERVER['REQUEST_URI']);
            $con  = @mysql_connect($host, $user, $pass);
            if(!$con) {
                echo 0;
            } else {
                $seldb = mysql_select_db($db, $con);
                if(!$seldb) {
                    echo 1;
                } else {
                    if($this->writeconfig($host, $user, $pass, $db, $pre, "false")) {
                        echo 2;
                    } else {
                        echo 3;
                    }
                }
            }
        } else {
            echo 4;
        }
    }
    private function writeconfig($host, $user, $pass, $db, $pre, $true) {
        global $style;
        $array['HOST'] = $host;
        $array['USER'] = $user;
        $array['PASS'] = $pass;
        $array['DB']   = $db;
        $array['PRE']  = $pre;
        $array['TRUE'] = $true;
        foreach($array as &$v) {
            $v = str_replace("'", "\\'", $v);
        }
        $tpl           = $style->replaceVar("tpl/install/conftemp.tpl", $array);
        $link          = LINK . "conf.inc.php";
        if(is_writable($link)) {
            file_put_contents($link, $tpl);
            return true;
        } else {
            return false;
        }
    }

    public function install() {
        global $style, $db, $main;
        if(INSTALL != 1) {
            include(LINK . "conf.inc.php");
            $dbCon = mysql_connect($sql['host'], $sql['user'], $sql['pass']);
            $dbSel = mysql_select_db($sql['db'], $dbCon);
            if($main->getvar['type'] == "install") {
                $errors = $this->installsql("sql/install.sql", $sql['pre'], $dbCon);
            } elseif($main->getvar['type'] == "upgrade") {
                $errors  = $this->installsql("sql/upgrade.sql", $sql['pre'], $dbCon);
                $porders = mysql_query("SELECT * FROM `{$sql['pre']}packages`", $dbCon);
                $n       = 1;
                while($data = mysql_fetch_array($porders)) {
                    if($data['oid'] == "0") {
                        mysql_query("UPDATE `{$sql['pre']}packages` SET `oid` = '{$n}' WHERE `id` = '{$data['id']}'", $dbCon);
                        $n++;
                    }
                }
                if($n > 1) {
                    mysql_query("ALTER TABLE `{$sql['pre']}packages` ADD UNIQUE (`oid`)", $dbCon);
                }
            } else {
                echo "Fatal Error Debug: " . $main->getvar['type'];
            }
            $vname  = mysql_real_escape_string($_GET['vname']);
            $vcode  = mysql_real_escape_string($_GET['vcode']);
            $query  = mysql_query("UPDATE `{$sql['pre']}config` SET `value` = '{$vname}' WHERE `name` = 'vname'");
            $query2 = mysql_query("UPDATE `{$sql['pre']}config` SET `value` = '{$vcode}' WHERE `name` = 'vcode'");
            if(!$query || !$query2) {
                echo '<div class="errors">There was a problem editing your script version!</div>';
            }
            if($main->getvar['type'] == "install") {
                $query = mysql_query("UPDATE `{$sql['pre']}config` SET `value` = 'Reloaded2' WHERE `name` = 'theme'");
                if(!$query) {
                    echo '<div class="errors">There was a problem setting your default theme!</div>';
                }
            }
            echo "Complete!<br /><strong>There were " . $errors['n'] . " errors while executing the SQL!</strong><br />";
            if(!$this->writeconfig($sql['host'], $sql['user'], $sql['pass'], $sql['db'], $sql['pre'], $main->getvar['type'] == "upgrade" ? "true" : "false")) {
                echo '<div class="errors">There was a problem re-writing to the config!</div>';
            }
            if($main->getvar['type'] == "install") {
                echo '<div align="center"><input type="button" name="button4" id="button4" value="Next Step" onclick="change()" /></div>';
            } elseif($main->getvar['type'] == "upgrade") {
                echo '<div class="errors">Your upgrade is now complete! You can use the script as normal.</div>';
            }
            if($errors['n']) {
                echo "<strong>SQL Queries (Broke):</strong><br /><pre>";
                foreach($errors['errors'] as $value) {
                    echo nl2br(htmlentities($value)) . "<br /><br />";
                }
                echo "</pre>";
            }
        }
    }

    private function installsql($data, $pre, $con = 0) {
        if(INSTALL) {
            return;
        }
        global $style, $db;
        $array['PRE'] = $pre;
        $sContents    = $style->replaceVar($data, $array);
        // replace slash quotes so they don't get in the way during parse
        // tried a replacement array for this but it didn't work
        // what's a couple extra lines of code, anyway?
        $sDoubleSlash = '~~DOUBLE_SLASH~~';
        $sSlashQuote  = '~~SLASH_QUOTE~~';
        $sSlashSQuote = '~~SLASH_SQUOTE~~';

        $sContents = str_replace('\\\\', $sDoubleSlash, $sContents);
        $sContents = str_replace('\"', $sSlashQuote, $sContents);
        $sContents = str_replace("\'", $sSlashSQuote, $sContents);

        $iContents         = strlen($sContents);
        $sDefaultDelimiter = ';';

        $aSql       = array();
        $sSql       = '';
        $bInQuote   = false;
        $sDelimiter = $sDefaultDelimiter;
        $iDelimiter = strlen($sDelimiter);
        $aQuote     = array(
            "'",
            '"'
        );
        for($i = 0; $i < $iContents; $i++) {
            if($sContents[$i] == "\n" || $sContents[$i] == "\r") {
                // Check for Delimiter Statement
                if(preg_match('/delimiter\s+(.+)/i', $sSql, $aMatches)) {
                    $sDelimiter = $aMatches[1];
                    $iDelimiter = strlen($sDelimiter);
                    $sSql       = '';
                    continue;
                }
            }

            if(in_array($sContents[$i], $aQuote)) {
                $bInQuote = !$bInQuote;
                if($bInQuote) {
                    $aQuote = array(
                        $sContents[$i]
                    );
                } else {
                    $aQuote = array(
                        "'",
                        '"'
                    );
                }
            }

            if($bInQuote) {
                $sSql .= $sContents[$i];
            } else {
                // fill a var with the potential delimiter - aka read-ahead
                if(substr($sContents, $i, $iDelimiter) == $sDelimiter) {
                    // Clear Comments
                    $sSql = preg_replace("/^(-{2,}.+)/", '', $sSql);
                    $sSql = preg_replace("/(?:\r|\n)(-{2,}.+)/", '', $sSql);

                    // Put quotes back where you found them
                    $sSql = str_replace($sDoubleSlash, '\\\\', $sSql);
                    $sSql = str_replace($sSlashQuote, '\\"', $sSql);
                    $sSql = str_replace($sSlashSQuote, "\\'", $sSql);

                    // FIXME: odd replacement issue, just fix it for now and move on
                    $sSql = str_replace('IFEXISTS`', 'IF EXISTS `', $sSql);

                    $aSql[] = $sSql;
                    $sSql   = '';

                    // pass delimiter
                    $i += $iDelimiter;
                } else {
                    $sSql .= $sContents[$i];
                }
            }
        }

        $aSql = array_map('trim', $aSql);
        $aSql = array_filter($aSql);

        $n = 0;
        foreach($aSql as $sSql) {
            if($con) {
                $query = mysql_query($sSql, $con);
            } else {
                $query = $db->query($sSql);
            }
            if(!$query) {
                $n++;
                $errors[] = $sSql;
            }
        }
        if(!$n) {
            $n = 0;
        }
        $stuff['n']      = $n;
        $stuff['errors'] = $errors;
        return $stuff;
    }

    public function installfinal() {
        if(INSTALL) {
            echo 0;
            return;
        }
        global $main;
        $db = new db();
        $query = $db->query("SELECT * FROM `<PRE>staff`");
        if(!$db->num_rows($query)) {
            foreach($main->getvar as $key => $value) {
                if(!$value) {
                    $n++;
                }
            }
            if(!$n) {
                $db->query("UPDATE `<PRE>config` SET `value` = '{$main->getvar['url']}' WHERE `name` = 'url'");
                $salt     = md5(rand(0, 99999));
                #$password = md5(md5($main->getvar['pass']) . md5($salt));
                $password = password_hash($main->getvar['pass'], PASSWORD_BCRYPT, array('count'=>PASSWORD_COST));
                $db->query("INSERT INTO `<PRE>staff` (user, email, password, salt, name) VALUES(
                                                                                  '{$main->getvar['user']}',
                                                                                  '{$main->getvar['email']}',
                                                                                  '{$password}',
                                                                                  '{$salt}',
                                                                                  '{$main->getvar['name']}')");
                $sql = array();
                include(LINK.'conf.inc.php');
                echo (int)$this->writeconfig($sql['host'], $sql['user'], $sql['pass'], $sql['db'], $sql['pre'], "true");
            } else {
                echo 0;
            }
        }
    }

    function massemail() {
        if($_SESSION['logged']) {
            global $main, $email, $db;
            $subject = $_POST['subject'];
            $msg     = $_POST['msg'];
            $query   = $db->query("SELECT * FROM `<PRE>users`");
            $error   = false;
            while($client = $db->fetch_array($query)) {
                $result = $email->send($client['email'], $subject, $msg);
                if(!$result) {
                    // Using output buffering may have actually been a good idea after all... Haha.
                    echo ob_get_clean();
                    $error = true;
                    // Break out of the loop and stop here.
                    break;
                }
            }
            if(!$error) {
                echo 1;
            }
        }
    }

    function porder() {
        global $main, $db;
        $order = $main->getvar['order'];
        print_r($main->getvar);
    }

    function padd() {
        global $style;
        echo $style->replaceVar("tpl/acppacks/addbox.tpl");
    }

    function pedit() {
        if($_SESSION['logged']) {
            global $db, $style, $main;
            $query                = $db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$main->getvar['do']}'");
            $data                 = $db->fetch_array($query);
            $array['ID']          = $data['id'];
            $array['BACKEND']     = $data['backend'];
            $array['DESCRIPTION'] = $data['description'];
            $array['NAME']        = $data['name'];
            if($data['admin'] == 1) {
                $array['CHECKED'] = 'checked="checked"';
            } else {
                $array['CHECKED'] = "";
            }
            if($data['reseller'] == 1) {
                $array['CHECKED2'] = 'checked="checked"';
            } else {
                $array['CHECKED2'] = "";
            }
            $additional = explode(",", $data['additional']);
            foreach($additional as $key => $value) {
                $me            = explode("=", $value);
                $cform[$me[0]] = $me[1];
            }
            global $type;
            $array['FORM'] = $type->acpPedit($data['type'], $cform);
            $query         = $db->query("SELECT * FROM `<PRE>servers`");
            while($data = $db->fetch_array($query)) {
                $values[] = array(
                    $data['name'],
                    $data['id']
                );
            }
            $array['SERVER'] = $array['THEME'] = $main->dropDown("server", $values, $data['server']);
            echo $style->replaceVar("tpl/acppacks/editbox.tpl", $array);
        }
    }

    function nedit() {
        if($_SESSION['logged']) {
            global $db, $style, $main;
            $query           = $db->query("SELECT * FROM `<PRE>navbar` WHERE `id` = '{$main->getvar['do']}'");
            $data            = $db->fetch_array($query);
            $array['ID']     = $data['id'];
            $array['NAME']   = $data['name'];
            $array['VISUAL'] = $data['visual'];
            $array['LINK']   = $data['link'];
            $array['ICON']   = $data['icon'];
            //echo $style->replaceVar("tpl/navedit/pbox.tpl", $array);
            //echo "\n<!-- O NOEZ IT R H4XX -->\n"; // <-- Don't remove this.
            echo $style->replaceVar("tpl/navedit/editbox.tpl", $array);
            return true;
        }
    }

    private function randomString($length = 8, $possible = '0123456789bcdfghjkmnpqrstvwxyz') {
        $string = "";
        $i      = 0;
        while($i < $length) {
            $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            if(!strstr($salt, $char)) {
                $string .= $char;
                $i++;
            }
        }
        return $string;
    }

    function editcss() {
        global $main, $db, $style;
        if($_SESSION['logged']) {
            if(isset($_POST['css'])) {
                $url          = $db->config('url') . "themes/" . $db->config('theme') . "/images/";
                $slash        = stripslashes(str_replace("&lt;IMG&gt;", "<IMG>", $_POST['css'])); //Strip it back
                $filetochange = LINK . "../themes/" . $db->config('theme') . "/style.css";
                file_put_contents($filetochange, $slash);
                echo "CSS File Modified! Refresh for changes.";
            } else {
                return;
            }
        }
        return true;
    }

    function edittpl() {
        global $main, $db, $style;
        if($_SESSION['logged']) {
            if(isset($_POST['file']) and isset($_POST['contents'])) {
                $file     = $_POST['file'];
                $contents = $_POST['contents'];
                $slash    = $contents;
                //We have to do some special stuff for the footer.
                //This gets complex. But it works. I might simplify it sometime.
                if($file == "footer") {
                    $slash = str_replace("&lt;COPYRIGHT&gt;", "<COPYRIGHT>", $slash);
                    $slash = stripslashes(str_replace("&lt;PAGEGEN&gt;", "<PAGEGEN>", $slash));
                }
                $slash            = stripslashes(str_replace("&lt;THT TITLE&gt;", "<THT TITLE>", $slash)); // Yay, strip it
                $slash            = str_replace("&lt;JAVASCRIPT&gt;", "<JAVASCRIPT>", $slash); //jav
                $slash            = str_replace("&lt;CSS&gt;", "<CSS>", $slash); //css
                $slash            = str_replace("&lt;ICONDIR&gt;", "<ICONDIR>", $slash); //icondir
                $slash            = str_replace("&lt;IMG&gt;", "<IMG>", $slash);
                $slash            = str_replace("&lt;MENU&gt;", "<MENU>", $slash);
                $slash            = str_replace("&#37;INFO%", "%INFO%", $slash);
                //Alrighty, what to do nexty?
                $filetochange     = LINK . "../themes/" . $db->config('theme') . "/" . $file . ".tpl";
                $filetochangeOpen = fopen($filetochange, "w");
                fputs($filetochangeOpen, $slash);
                fclose($filetochangeOpen) or die("Error Closing File!");
                echo $file . '.tpl Modified! Refresh for changes.';
                die();
            }
        }
        return true;
    }

    function notice() {
        global $style;
        if(isset($_REQUEST['status']) and isset($_REQUEST['message'])) {
            if($_REQUEST['status'] == "good") {
                $status = true;
            } else {
                $status = false;
            }
            echo $style->notice($status, $_REQUEST['message']);
        }
        return true;
    }

    function upload() {
        global $main;
        if($_SESSION['logged']) {
        }
    }

    function navbar() {
        global $main, $db;
        if($_SESSION['logged']) {
            //Cause I'm fairly lazy
            $P = $_POST;
            if(isset($P['action']) or $_GET['action']) {
                //Even lazier?
                $action = $_REQUEST['action'];
                $id     = $main->postvar['id'];
                $name   = $main->postvar['name'];
                $icon   = $main->postvar['icon'];
                $link   = $main->postvar['link'];
                switch($action) {
                    case "add":
                        if(isset($P['name']) and isset($P['icon']) and isset($P['link'])) {
                            $db->query("INSERT INTO `<pre>navbar` (visual, icon, link) VALUES('{$name}', '{$icon}','{$link}')");
                        }
                        break;
                    case "edit":
                        if(isset($P['id']) and isset($P['name']) and isset($P['icon']) and isset($P['link'])) {
                            $db->query("UPDATE `<pre>navbar` SET
                                `visual` = '{$name}',
                                `icon` = '{$icon}',
                                `link` = '{$link}'
                                WHERE `id` = '{$id}'");
                        }
                        break;
                    case "delete":
                        if(isset($_GET['id'])) {
                            $db->query("DELETE FROM `<PRE>navbar` WHERE `id` = '{$main->getvar['id']}'");
                        }
                        break;
                    case "order":
                        if(isset($P['order'])) {
                            $ids = explode("-", $main->postvar['order']);
                            $i   = 0;
                            foreach($ids as $id) {
                                $db->query("UPDATE `<PRE>navbar` SET `order` = {$i} WHERE `id` = {$id}");
                                $i++;
                            }
                        }
                        break;
                }
            }
        }
    }

    function acpPackages() {
        if(!$_SESSION['logged']) {
            return;
        }
        global $db, $type;
        switch($_POST["operation"]) {
            case "new":
            case "edit":
                //print_r($_POST);
                header("Content-type: application/json");
                if(
                    // Can be new0 or 0
                    (!isset($_POST["id"]) || (preg_match("/^(?:new)?[0-9]+$/", $_POST["id"]) === false)) ||
                    // Just can't be empty
                    (!isset($_POST["name"]) || $_POST["name"] == "") ||
                    (!isset($_POST["backend"]) || $_POST["backend"] == "") ||
                    (!isset($_POST["desc"]) || $_POST["desc"] == "") ||
                    (!isset($_POST["type"]) || $_POST["type"] == "") ||
                    // Must be integer
                    (!isset($_POST["server"]) || !ctype_digit($_POST["server"])) ||
                    // Boolean (check later)
                    (!isset($_POST["admin"]) || $_POST["admin"] == "") ||
                    (!isset($_POST["reseller"]) || $_POST["reseller"] == "") ||
                    (!isset($_POST["domain"]) || $_POST["domain"] == "") ||
                    (!isset($_POST["hidden"]) || $_POST["hidden"] == "") ||
                    (!isset($_POST["disabled"]) || $_POST["disabled"] == "") ||
                    // Custom fields. An array of ints. Optional.
                    (isset($_POST["custom"]) && !is_array($_POST["custom"])) ||
                    // Type fields. Assoc array of unknown values. Optional.
                    (isset($_POST["typefields"]) && !is_array($_POST["typefields"]))
                ) {
                    echo json_encode(false);
                    return;
                }
                $id = $_POST["id"];
                $name = $_POST["name"];
                $backend = $_POST["backend"];
                $desc = $_POST["desc"];
                $pkgtype = $_POST["type"];
                $server = (int)$_POST["server"];
                $admin = $_POST["admin"] === "true";
                $reseller = $_POST["reseller"] === "true";
                $domain = $_POST["domain"] === "true";
                $hidden = $_POST["hidden"] === "true";
                $disabled = $_POST["disabled"] === "true";
                $custom = isset($_POST["custom"]) ? $_POST["custom"] : false;
                $typefields = isset($_POST["typefields"]) ? $_POST["typefields"] : false;

                // Make certain custom fields array holds only integers and in DB
                if($custom !== false) {
                    $dbChkFields = array("i" => 0, "in" => ""); // Using our own index i for safety
                    $safeCustom = array(); // An array that is guaranteed to be non-associative
                    foreach($custom as $cf) {
                        if(!ctype_digit($cf)) {
                            echo json_encode(false);
                            return;
                        }
                        $safeCustom[] = (int)$cf;
                        $dbChkFields["in"] .= ":id_" . $dbChkFields["i"]++ . ",";
                    }
                    // $safeCustom should be in the same order as the :id_n params, but here order
                    // doesn't matter. As long as the number of :id_n params = length of $safeCustom
                    $dbChkFields["q"] = $db->query("SELECT `id` FROM `<PRE>orderfields` WHERE `id` IN(" . rtrim($dbChkFields["in"], ",") . ")", $safeCustom);
                    $dbChkFields["a"] = $db->fetch_array($dbChkFields["q"]);
                    // If the db output is less than the db input at least 1 field doesn't exist
                    if((int)$dbChkFields["a"][0] < count($custom)) {
                        echo json_encode(false);
                        return;
                    }
                    $custom = json_encode($safeCustom);
                } else {
                    $custom = "[]";
                }

                // Check to see if $pkgtype is loaded
                if(!array_key_exists($pkgtype, $type->classes)) {
                    echo json_encode(false);
                    return;
                }

                // Validate type fields (if any)
                $typefields = $typefields === false ? array() : $typefields;
                $val = $type->classes[$pkgtype]->validatePkgFields($typefields);
                // Error?
                if(is_string($val) || $val === false) {
                    echo json_encode(false);
                    return;
                }

                if(!$db->num_rows($db->query("SELECT `id` FROM `<PRE>servers` WHERE `id` = '{$db->strip($server)}'"))) {
                    echo json_encode(false);
                    return;
                }
                if($_POST["operation"] != "new") {
                    // Do not allow "new0" ids
                    if(!is_numeric($id)) {
                        echo json_encode(false);
                        return;
                    }
                    $id = abs((int)$id);

                    // Check if $id exists before UPDATE
                    $q = $db->query("SELECT `additional` FROM `<PRE>packages` WHERE `id` = ?", array($id));
                    if($q->rowCount() === 0) {
                        echo json_encode(false);
                        break;
                    }
                    $q = $db->fetch_array($q); // Array dereferencing is PHP 5.4+ only
                    $additional = json_decode($q["additional"], true);
                    $additional = $additional === null ? array() : $additional;
                    // Replace old type data
                    $additional["types"][$pkgtype] = $typefields;
                    $additional = json_encode($additional);

                    $db->query("UPDATE `<PRE>packages` SET `name` = '{$db->strip($name)}', `backend` = '{$db->strip($backend)}',
                        `description` = '{$db->strip($desc)}', `type` = '{$db->strip($pkgtype)}', `server` = '{$db->strip($server)}', `admin` = '{$db->strip((int)$admin)}',
                        `reseller` = '{$db->strip((int)$reseller)}', `is_hidden` = '{$db->strip((int)$hidden)}', `is_disabled` = '{$db->strip((int)$disabled)}',
                        `custom_fields` = '{$db->strip($custom)}', `allow_domains` = '{$db->strip((int)$domain)}',
                        `additional` = '{$db->strip($additional)}' WHERE `id` = '{$db->strip($id)}'");
                    echo json_encode(true);
                    return;
                }
                $additional = json_encode(array("types" => array($pkgtype => $typefields)));
                $db->query("INSERT INTO `<PRE>packages` (`id`, `name`, `backend`, `description`, `type`, `server`, `admin`, `reseller`,
                    `order`, `is_hidden`, `is_disabled`, `custom_fields`, `allow_domains`, `additional`)
                    VALUES (NULL, '{$db->strip($name)}', '{$db->strip($backend)}', '{$db->strip($desc)}', '{$db->strip($pkgtype)}',
                    '{$db->strip($server)}', '{$db->strip((int)$admin)}', '{$db->strip((int)$reseller)}', '0',
                    '{$db->strip((int)$hidden)}', '{$db->strip((int)$disabled)}', '{$db->strip($custom)}', '{$db->strip((int)$domain)}',
                    '{$db->strip($additional)}')");
                echo json_encode(array("oldId" => $id, "insertId" => $db->insert_id(), "typeFields" => $typefields));
                return;
            case "delete":
                header("Content-type: application/json");
                if(!isset($_POST["id"]) || !is_numeric($_POST["id"])) {
                    echo json_encode(false);
                    return;
                }
                $id = (int)$_POST["id"];
                echo json_encode($db->query("DELETE FROM `<PRE>packages` WHERE `id` = '{$db->strip($id)}'"));
                return;
            case "order":
                header("Content-type: application/json");
                $orders = $_POST["order"];
                if(!is_array($orders)) {
                    echo json_encode(false);
                    return;
                }
                for($i = 0; $i < count($orders); $i++) {
                    $id = (int)$orders[$i];
                    $db->query("UPDATE `<PRE>packages` SET `order` = '{$i}' WHERE `id` = '{$id}'");
                }
                echo json_encode(true);
                return;
        }
    }

    function uiThemeChange() {
        global $main, $db;
        if($_SESSION['logged']) {
            if(isset($_POST['theme'])) {
                $db->updateConfig('ui-theme', $main->postvar['theme']);
                echo "true";
            }
        }
    }

    function ispaid() {
        global $db, $main;
        $package = $db->fetch_array($db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$main->getvar['pid']}'"));
        if($package['type'] == "paid") {
            $username = $db->fetch_array($db->query("SELECT * FROM `<PRE>users` WHERE `user` = '{$main->getvar['uname']}'"));
            $id       = $username['id'];
            $invoice  = $db->fetch_array($db->query("SELECT * FROM `<PRE>invoices` WHERE `uid` = '{$id}'"));
            echo $invoice['id'];
        }
    }

    function deleteTicket() {
        if($_SESSION['logged']) {
            global $main, $db;
            $tid = $main->postvar['ticket'];
            if($tid != "" and is_numeric($tid)) {
                $query = "DELETE FROM `<PRE>tickets` WHERE `id` = {$tid}";
                $db->query($query);
                $query = "DELETE FROM `<PRE>tickets` WHERE `ticketid` = {$tid}";
                $db->query($query);
            }
        }
    }

    function clientAction() {
        if(!$_SESSION['logged']) { return; }
        header("Content-type: application/json");
        echo json_encode($this->_clientAction());
    }

    private function _clientAction() {
        global $db, $server;
        $client = $db->client($_POST['id'], true);
        if(!$client) {
            return array("msg" => "Unknown client.", "error" => true);
        }
        $pack = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `userid` = '".$db->strip($_POST['id'])."'");
        if($db->num_rows($pack) == 0) {
            return array("msg" => "Unknown package.", "error" => true);
        }
        $pack = $db->fetch_array($pack);
        switch($_POST['action']) {
            case "unsuspend":
                if($server->unsuspend($pack['id'], true)) {
                    return array("msg" => "Unsuspended.", "error" => false);
                }
                return array("msg" => "Could not unsuspend.", "error" => true);
                break;
            case "suspend":
                if($server->suspend($pack['id'], !empty($_POST['reason']) ? $_POST['reason'] : false, true)) {
                    return array("msg" => "Suspended.", "error" => false);
                }
                return array("msg" => "Could not suspend.", "error" => true);
                break;
            case "cancel":
                if($server->cancel($pack['id'], !empty($_POST['reason']) ? $_POST['reason'] : false, true)) {
                    return array("msg" => "Canceled.", "error" => false);
                }
                return array("msg" => "Could not cancel.", "error" => true);
                break;
            case "terminate":
                if($server->terminate($pack['id'], !empty($_POST['reason']) ? $_POST['reason'] : false, true)) {
                    return array("msg" => "Terminated.", "error" => false);
                }
                return array("msg" => "Could not terminate.", "error" => true);
                break;
            case "validate":
                if($_POST['accept'] == "1") {
                    if($server->approve($pack['id'], true)) {
                        global $email;
                        $emaildata = $db->emailTemplate("approvedacc");
                        $db->query("UPDATE `<PRE>users` SET `status` = '1' WHERE `id` = '{$client['id']}'");
                        $email->send($client['email'], $emaildata['subject'], $emaildata['content']);
                        return array("msg" => "Approved.", "error" => false);
                    }
                    return array("msg" => "Could not approve.", "error" => true);
                }
                if($server->decline($pack['id'], true)) {
                    return array("msg" => "Declined.", "error" => false);
                }
                return array("msg" => "Could not decline.", "error" => true);
                break;
            default:
                return array("msg" => "Unknown action.", "error" => true);
                break;
        }
    }

    // Sets the order of the rows in a table.
    // $_POST["order"] should be a comma separated list of IDs for $_POST["table"]
    // $_POST["table"] should NOT include the table prefix.
    function setOrderOfRows() {
        if($_SESSION['logged'] && isset($_POST["table"]) && isset($_POST["order"])) {
            global $main, $db;
            $i = 0;
            $table = $db->strip($_POST["table"]);
            foreach(explode(',', $main->postvar["order"]) as $id) {
                $q = $db->query("UPDATE `<PRE>{$table}` SET `order` = '{$i}' WHERE `id` = {$id}");
                if(!$q) {
                    echo '0';
                    return;
                }
                $i++;
            }
            echo '1';
        }
    }

    function updateCustomField() {
        if(!$_SESSION['logged'] || !isset($_POST['title']) || !isset($_POST['description']) ||
        !isset($_POST['type']) || !isset($_POST['selectopt']) || !isset($_POST['defaultvalue']) ||
        !isset($_POST['regex']) || !isset($_POST['required']) || !isset($_POST['id'])) {
            return;
        }

        $title = $_POST['title'];
        $desc = $_POST['description'];
        $type = $_POST['type'];
        $selopt = $_POST['selectopt'];
        $defval = $_POST['defaultvalue'];
        $regex = $_POST['regex'];
        $required = $_POST['required'] == 'true';
        $min = $_POST['min'];
        $max = $_POST['max'];
        $step = $_POST['step'];
        $defopt = $_POST['defaultopt'];

        if(empty($title)) {
            echo json_encode(array('error' => true, 'msg' => 'Title cannot be blank.'));
            return;
        }

        $possibleTypes = array('text', 'password', 'checkbox', 'select', 'number', 'tel', 'url', 'email', 'range', 'week');

        if(!in_array($type, $possibleTypes)) {
            echo json_encode(array('error' => true, 'msg' => 'Invalid type.'));
            return;
        }

        if($regex != '') {
            if(@preg_match($regex, '') === false) {
                echo json_encode(array('error' => true, 'msg' => 'Invalid regular expression.'));
                return;
            }
            if($defval != '' && @preg_match($regex, $defval) === 0) {
                echo json_encode(array('error' => true, 'msg' => 'The default value doesn\'t match with the regex.'));
                return;
            }
        }

        if($type == "select") {
            $xml = new SimpleXMLElement("<tbody>".$selopt."</tbody>");
            if($xml === false) {
                echo json_encode(array('error' => true, 'msg' => 'Failed to parse the select options.'));
                return;
            }
            $selopt = array();
            foreach($xml->tr as $tr) {
                $selopt[] = htmlspecialchars_decode((string)$tr->td[0]);
            }
            unset($xml);
            if(count($selopt) == 0) {
                echo json_encode(array('error' => true, 'msg' => 'You have not defined any select options.'));
                return;
            }

            foreach(array_count_values($selopt) as $k => $v) {
                if($v > 1) {
                    echo json_encode(array('error' => true, 'msg' => 'Duplicate select option: ' . $k));
                    return;
                }
            }

            if($defopt != '' && !in_array($defopt, $selopt)) {
                echo json_encode(array('error' => true, 'msg' => 'Invalid default select option.'));
                return;
            }
            $defval = $defopt;
        }
        else {
            $selopt = $defopt = null;
        }

        if($type == 'number' || $type == 'range') {
            if(($min != '' && !is_numeric($min)) || ($max != '' && !is_numeric($max)) || ($step != '' && !is_numeric($step))) {
                echo json_encode(array('error' => true, 'msg' => 'Min, Max, and Step must be empty or numbers.'));
                return;
            }
            $min = $min == '' ? null : (float)$min;
            $max = $max == '' ? null : (float)$max;
            $step = $step == '' ? null : (float)$step;
        }
        elseif($type == 'week') {
            $r = "/^\d{4}-W\d{2}$/";
            if(($min != '' && preg_match($r, $min) === 0) || ($max != '' && preg_match($r, $max) === 0)) {
                echo json_encode(array('error' => true, 'msg' => 'Min and Max must be formatted like this: YYYY-W## eg: '.date('o-\WW').'.'));
                return;
            }
            if($step != '' && !is_numeric($step)) {
                echo json_encode(array('error' => true, 'msg' => 'Step must be a number.'));
                return;
            }
            $step = $step == '' ? null : (float)$step;
        }
        else {
            $min = $max = $step = null;
        }

        if($type == 'checkbox') {
            $defval = (int)($defval == "true");
        }

        $extra = array('min' => $min, 'max' => $max, 'step' => $step, 'selectopt' => $selopt);

        global $main, $db;
        if($_POST["id"] != "new" && $db->num_rows($db->query("SELECT `id` FROM `<PRE>orderfields` WHERE `id` = '{$main->postvar['id']}'")) == 0) {
            echo json_encode(array('error' => true, 'msg' => 'That ID does not exist.'));
            return;
        }
        $extra = json_encode($extra);
        $db->query(($_POST["id"] == "new" ? "INSERT INTO" : "UPDATE") . " `<PRE>orderfields` SET `title` = '{$db->strip($title)}',
            `type` = '{$db->strip($type)}', `default` = '{$db->strip($defval)}', `description` = '{$db->strip($desc)}',
            `required` = '{$db->strip((int)$required)}', `regex` = '{$db->strip($regex)}', `extra` = '{$db->strip($extra)}'"
            . ($_POST["id"] == "new" ? "" : "WHERE `id` = '{$main->postvar['id']}'"));
        if(mysql_affected_rows() == 0) {
            echo json_encode(array('error' => false, 'msg' => 'No changes have been made.'));
            return;
        }

        $newbox = null;
        $counter = (int)$_POST["counter"];
        $id = $_POST["id"];
        if($_POST["id"] == "new") {
            // Let's hope a "page" class hasn't already been defined...
            require_once(LINK . "../admin/pages/orderform.php");
            $admin = new page();
            global $db;
            $id = $db->insert_id();
            $newbox = $admin->buildFieldBox($id, $title, $type, $defval, $desc, $required, $regex, $extra, $counter);
        }
        echo json_encode(array('error' => false, 'msg' => null, 'id' => $id, 'newbox' => $newbox, 'counter' => $counter));
    }


    // This will delete the field from the database, remove all references to it from the packages table,
    // and purge any user data associated with it.
    function deleteCustomField() {
        if(!$_SESSION['logged'] || !isset($_POST['id'])) {
            return;
        }
        global $main, $db;
        $id = (int)$main->postvar["id"];
        $db->query("DELETE FROM `<PRE>orderfields` WHERE `id` = {$id}");
        if(mysql_affected_rows() === 0) {
            echo "0";
            return;
        }
        $query = $db->query("SELECT `id`,`custom_fields` FROM `<PRE>packages` WHERE `custom_fields` != '' AND `custom_fields` != '[]'");
        while($package = $db->fetch_array($query)) {
            $fields = json_decode($package['custom_fields']);
            if($fields == null) {
                continue;
            }
            $key = array_search($id, $fields);
            if($key !== false) {
                unset($fields[$key]);
                $fields = array_values($fields); // Reset the keys
                $db->query("UPDATE `<PRE>packages` SET `custom_fields` = '{$db->strip(json_encode($fields))}' WHERE `id` = '{$db->strip($package["id"])}'");
            }
        }
        echo "1";
    }

    function passwdStrength() {
        global $server, $type;
        header("Content-type: application/json");
        if(!$_SESSION["logged"]) {
            die(json_encode(array("error" => "Not logged in.")));
        }
        if(!isset($_REQUEST["passwd"]) || !(isset($_REQUEST["server"]) xor isset($_REQUEST["package"]))) {
            die(json_encode(array("error" => "Invalid arguments.")));
        }
        $serverId = $_REQUEST["server"];
        if(isset($_REQUEST["package"])) {
            $serverId = $type->determineServer($_REQUEST["package"]);
        }
        $result = $server->passwdStrength($serverId, $_REQUEST["passwd"]);
        echo json_encode(array(is_string($result) ? "error" : "strength" => $result));
    }

}

if(isset($_REQUEST['function']) and $_REQUEST['function'] != "") {
    $Ajax = new Ajax();
    if(method_exists($Ajax, $_REQUEST['function'])) {
        $Ajax->{$_REQUEST['function']}();
        require(LINK . "output.php");
    }
}
