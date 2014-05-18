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
define("CRON", 1);
include("compiler.php");
set_time_limit(0);

// Stop the output
ob_start(); // Damn, I swear I use too much of these. Their like crack. So fuckin addictive.

$type->createAll(); // Create all the types
$classes = $type->classes; // Because I'm a tad lazy, I set the types to a shorter variable

// Scans through each type
foreach($classes as $key => $value) {
    // Has the type got a cron?
    if($classes[$key]->cron) {
        // Well run it then...
        $classes[$key]->cron();
    }
}

// 1.2 Run the Paid CronJob.
$invoice->cron();

// Now we get the data
$data = ob_get_clean(); // Get all the HTML created by the script and clean the buffer
echo $data; // Lets just show it. Tickles my pickle. Don't have to keep checking emails.

// Now we mo truckin email it. Yeah I said it. Aren't I smart?
if($data != "") {
    // TBD: Provide an option to have an email where the cron output is sent to
    //$email->staff("Cron Job", $data);
}

// We've done.. Should I say I.
