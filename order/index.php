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

//Compile THT
define("LINK", "../includes/");
include(LINK ."compiler.php");

//THT Variables
define("PAGE", "Order Form");
define("SUB", "Account Creation");
define("INFO", "IP Logged: ". $_SERVER['REMOTE_ADDR']);

#If logout
if($main->getvar['do'] == "logout") {
    session_destroy();
    $main->redirect("../order/");
}

echo $style->get("header.tpl"); #Output Header
$ip = $_SERVER['REMOTE_ADDR'];

#Check stuff
if($db->config("general") == 0) {
    $maincontent = $main->table("Signups Closed", $db->config("message"));
}
elseif(!$main->checkIP($ip) && !$db->config("multiple")) {
    $maincontent = $main->table("IP Already Exists!", "Your IP already exists in the database!");
}
elseif($_SESSION['clogged']) {
    $maincontent = $main->table("Unable to sign-up!", "One package per account!");
}
else {
    $_SESSION['orderform'] = true;
}

//Get all packages
if(!$main->getvar['id']) {
    $packages2 = $db->query("SELECT * FROM `<PRE>packages` WHERE `is_hidden` = 0 AND `is_disabled` = 0 ORDER BY `order` ASC");
}
else {
    $packages2 = $db->query("SELECT * FROM `<PRE>packages` WHERE `is_disabled` = 0 AND `id` = '{$main->getvar['id']}'");
}
if($db->num_rows($packages2) == 0) {
    echo $main->table("No packages", "Sorry there are no available packages!");
}
else {
    while($data = $db->fetch_array($packages2)) {
        if(!$n) {
            $array['PACKAGES'] .= "<tr>";
        }
        $array2['NAME'] = $data['name'];
        $array2['DESCRIPTION'] = $data['description'];
        $array2['ID'] = $data['id'];
        $array['PACKAGES'] .= $style->replaceVar("tpl/order/orderpackages.tpl", $array2);
        $n++;
        if($n == 1) {
            $array['PACKAGES'] .= '<td width="2%"></td>';
        }
        if($n == 2) {
            $array['PACKAGES'] .= "</tr>";
            $n = 0;
        }
    }
    $array['TOS'] = $db->config("tos");
    $array['USER'] = "";
    $array['DOMAIN'] = '<input name="cdom" id="cdom" type="text" />';
    $sub = $db->query("SELECT * FROM `<PRE>subdomains`");
    if($db->num_rows($sub) == 0) {
        $array["CANHASSUBDOMAIN"] = "";
    }
    else {
        $array["CANHASSUBDOMAIN"] = '<option value="sub">Subdomain</option>';
    }
    while($sub2 = $db->fetch_array($sub)) {
        $values2[] = array($sub2['subdomain'], $sub2['subdomain']);
    }

    //Determine what to show in Client box
    if(!$_SESSION['clogged']) {
        $content = $style->replaceVar("tpl/clogin.tpl");
    }
    else {
        $clientdata = $db->client($_SESSION['cuser']);
        $array['NAME'] = $clientdata['user'];
        $content = $style->replaceVar("tpl/cdetails.tpl", $array);
    }
    if(!$maincontent) {
        $array["USENEWEFFECT"] = $db->config("useNewOrderSlideEffect") == 1 ? "true" : "false";
        $array["WELCOMEMSG"] = $db->config("welcomemsg");
        $maincontent = $style->replaceVar("tpl/order/orderform.tpl", $array);
    }

    echo '<div>';
    echo $maincontent;
    echo '</div>';

}

echo $style->get("footer.tpl"); #Output Footer

//Output
include(LINK ."output.php");
