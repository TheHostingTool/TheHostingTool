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

    public function content() { // Displays the page
        global $style, $db, $main, $type;
        $data = $db->client($_SESSION['cuser']);
        $query = $db->query("SELECT * FROM `<PRE>tickets` WHERE `reply` = '0' AND `userid` = '{$_SESSION['cuser']}'");
        $array['TICKETS'] = $db->num_rows($query);
        $query = $db->query("SELECT * FROM `<PRE>tickets` WHERE `reply` = '0' AND `userid` = '{$_SESSION['cuser']}' AND `status` = '1'");
        $array['OPENTICKETS'] = $db->num_rows($query);
        $query = $db->query("SELECT * FROM `<PRE>tickets` WHERE `reply` = '0' AND `userid` = '{$_SESSION['cuser']}' AND `status` = '3'");
        $array['CLOSEDTICKETS'] = $db->num_rows($query);
        $array['DATE'] = strftime("%D", $data['signup']);
        $lquery = $db->query("SELECT * FROM `<PRE>logs` WHERE `uid` = '{$_SESSION['cuser']}' AND `message` LIKE 'Login%' ORDER BY `id` DESC LIMIT 2,1");
        $ldata = $db->fetch_array($lquery);
        $array['LASTLOGIN'] = $ldata['message'];
        $array['LASTDATE'] = strftime("%m/%d/%Y", $ldata['logtime']);
        $array['LASTTIME'] = strftime("%T", $ldata['logtime']);
        $estatus = $main->getEmailStatus($_SESSION['cuser']);
        $array['EMAIL'] = $estatus==3?$data['newemail']:$data['email'];
        $array['ESTATUS'] = '<span style="color:green;">Confirmed</span>';
        switch ($estatus) {
            case 1:
                $array['ESTATUS'] = '<span style="color:green;">Accepted</span>';
                break;
            case 2:
            case 3:
                $array['ESTATUS'] = '<span style="color:red;font-weight:bold;">Unconfirmed</span>';
                break;
        }
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

            case "4":
                $array['STATUS'] = "Awaiting Payment";
                break;

            case "9":
                $array['STATUS'] = "Cancelled";
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
