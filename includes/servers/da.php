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

class da {

    # START THE MO TRUCKIN FUNCTIONS #

    public $name = "Direct Admin"; # THT Values
    public $hash = false; # Password or Access Hash?

    private $server;

    // Valid username regex
    private static $validUsernameRegex = "/^[a-z][a-z0-9]{3,9}$/";

    public function __construct($serverId = null) {
        if(!is_null($serverId)) {
            $this->server = (int)$serverId;
        }
    }

    private function serverDetails($server) {
        global $db;
        global $main;
        $query = $db->query("SELECT * FROM `<PRE>servers` WHERE `id` = '{$db->strip($server)}'");
        if($db->num_rows($query) == 0) {
            $array['Error'] = "That server doesn't exist!";
            $array['Server ID'] = $id;
            $main->error($array);
            return;
        }
        else {
            return $db->fetch_array($query);
        }
    }

    private function remote($action, $url, $get = false, $returnErrors = false) {
        $data = $this->serverDetails($this->server);
        $ch = curl_init();
        $ip = gethostbyname($data['host']);
        $serverstuff = "http://".$data['user'].":".$data['accesshash']."@" . $data['host'] . ":2222/". $action;
        if($get) {
            curl_setopt($ch, CURLOPT_URL, $serverstuff . $url);
        }
        else {
            curl_setopt($ch, CURLOPT_URL, $serverstuff);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $url);
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $data = curl_exec($ch);
        if($data === false) {
            if($returnErrors) {
                return curl_error($ch);
            }
            global $main;
            $main->error(array("DA Connection Error" => curl_error($ch)));
            return false;
        }
        curl_close($ch);
        //Work with data
        $split = explode("&", $data);
        foreach($split as $value) {
            $stuff = explode("=", $value);
            $final[$stuff[0]] = $stuff[1];
        }
        return $final;
    }

    public function GenUsername() {
        $t = rand(5,8);
        for ($digit = 0; $digit < $t; $digit++) {
            $r = rand(0,1);
            $c = ($r==0)? rand(65,90) : rand(97,122);
            $user .= chr($c);
        }
        return $user;
    }

    public function GenPassword() {
        for ($digit = 0; $digit < 5; $digit++) {
            $r = rand(0,1);
            $c = ($r==0)? rand(65,90) : rand(97,122);
            $passwd .= chr($c);
        }
        return $passwd;
    }

    public function signup($server, $reseller, $user = '', $email = '', $pass = '') {
        global $main;
        global $db;
        if ($user == '') { $user = $main->getvar['username']; }
        if ($email == '') { $email = $main->getvar['email']; }
        if ($pass == '') { $pass = $main->getvar['password']; }
        $this->server = $server;
        $data = $this->serverDetails($this->server);
        $ip = gethostbyname($data['host']);
        $string =   "action=create&add=Submit&username=". $user . "".
                    "&passwd=". $pass ."".
                    "&passwd2=". $pass ."".
                    "&domain=". $main->getvar['fdom'] ."".
                    "&package=". $main->getvar['fplan'] ."".
                    "&notify=no".
                    "&email=".$email."";
        if($reseller) {
            $define = "CMD_API_ACCOUNT_RESELLER";
            $string .= "&ip=shared";
        }
        else {
            $define = "CMD_API_ACCOUNT_USER";
            $string .= "&ip=".$ip;
        }
        //echo $action."<br />". $reseller;
        $command = $this->remote($define,$string);
        if($command['error']) {
            echo "<strong>".$command['text']."</strong><br />". $command['details'];
        }
        else {
            return true;
        }
    }

    public function suspend($user, $server, $reason = false) {
        $this->server = $server;
        $define = "CMD_API_SELECT_USERS";
        $action = "dosuspend=Suspend&suspend=suspend&location=CMD_SELECT_USERS&select0=" . strtolower($user);
        $command = $this->remote($define, $action);
        if(!$command['error']) {
            return true;
        }
        else {
            return false;
        }
    }

    public function unsuspend($user, $server) {
        $this->server = $server;
        $define = "CMD_API_SELECT_USERS";
        $action = "dounsuspend=Unsuspend&suspend=unsuspend&select0=" . strtolower($user);
        $command = $this->remote($define ,$action);
        if(!$command['error']) {
            return true;
        }
        else {
            return false;
        }
    }
    public function terminate($user, $server) {
        $this->server = $server;
        $define = "CMD_API_SELECT_USERS";
        $action = "confirmed=Confirm&delete=yes&select0=" . strtolower($user);
        $command = $this->remote($define ,$action);
        if(!$command['error']) {
            return true;
        }
        else {
            return false;
        }
    }

    public function testConnection($serverId = null) {
        if(!is_null($serverId)) {
            $this->server = (int)$serverId;
        }

        // No idea if this will work. Still need a DA testing server.
        $command = $this->remote("CMD_API_ADMIN_STATS", "", true, true);
        if($command["error"] == "1") {
            return "D";
        }
    }

    // http://forum.directadmin.com/showthread.php?t=789&p=4101#post4101 (unsure if this has changed since 2003)
    public function checkUsername($username) {
        // We're not going to check with the actual server because that's an expensive (time) operation
        if(!preg_match(self::$validUsernameRegex, $username)) {
            return "Username must be alphanumeric, cannot start with a number, lowercase, and between 4 and 10 characters.";
        }
        return true;
    }
    
    
    public function changePwd($acct, $newpwd, $server)
    {
        return true;
    }
    
}
