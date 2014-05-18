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

// Check if called by script
if(THT != 1){die();}

class page {

    public $navtitle;
    public $navlist = array();
    public $defaultNav;

    public function __construct() {
        $this->navtitle = "Server Status Sub Menu";
        $this->navlist[] = array("Server Status", "application_osx_terminal.png", "status");
        $this->navlist[] = array("PHP Info", "page_white_php.png", "phpinfo");
        $this->defaultNav = 0;
    }

    public function description() {
        return "<strong>Server Status</strong><br />
        Welcome to the server status system. Here you can see your server information, php information and more.";
    }

    public function mysqlVersion() {
        global $db;
        $result = $db->fetch_array($db->query("SELECT Version()"));
        return $result[0];
    }

    public function getLinuxDistro($raw = false) {
        global $main;
        if($main->canRun("shell_exec")) {
            $result = shell_exec("cat /etc/*-release");
            if($raw) {
                return $result;
            }
            if(preg_match('/DISTRIB_DESCRIPTION="(.*)"/', $result, $match)) {
                return $match[1];
            }
            return $result;
        }
        return false;
    }

    public function server_status() {
        global $main;
        $array['EXTRA'] = '';
        if(!$main->canRun('shell_exec')) {
            $array['EXTRA'] = 'Some statistics could not be provided because shell_exec has been disabled.';
        }
        $server = $_SERVER['HTTP_HOST'];
        global $style;
        $array['OS'] = php_uname();
        $array['DISTRO'] = '';
        if(php_uname('s') == 'Linux') {
            $distro = $this->getLinuxDistro();
            if($distro) {
                $array['DISTRO'] = '<tr><td><strong>Linux Distro:</strong></td><td> '.$distro.' </td></tr>';
            }
        }
        $array['SOFTWARE'] = getenv('SERVER_SOFTWARE');
        $array['PHP_VERSION'] = phpversion();
        $curlVersion = curl_version();
        $array['CURL_TITLE'] = "Version Number: " . $curlVersion["version_number"] . "<br />Version: " . $curlVersion["version"]
        . "<br />SSL Version Number: " . $curlVersion["ssl_version_number"] . "<br />SSL Version: " . $curlVersion["ssl_version"]
        . "<br />zlib Version: " . $curlVersion["libz_version"] . "<br />Host: " . $curlVersion["host"] . "<br />Age: " . $curlVersion["age"]
        . "<br />Protocols: " . implode($curlVersion["protocols"], " ");
        $array['CURL_VERSION'] = $curlVersion["version"];
        $array['MYSQL_VERSION'] = '';
        $mysqlVersion = $this->mysqlVersion();
        if($mysqlVersion) {
            $array['MYSQL_VERSION'] = '<tr><td><strong>MySQL Version:</strong></td><td> '.$mysqlVersion.' </td></tr>';
        }
        $array['SERVER'] = $server;
        echo $style->replaceVar('tpl/aserverstatus.tpl',$array);
    }

    public function content() { # Displays the page
        global $main;
        global $page;
        global $style;

        switch($main->getvar['sub']) {
           default: $this->server_status(); break;

           case "phpinfo":
           		echo $style->replaceVar('tpl/phpinfo.tpl',$array);
           break;
        }
    }
}
