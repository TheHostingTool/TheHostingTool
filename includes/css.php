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
require(LINK . "compiler.php");

// For install when no URL is present
if(!defined("URL")) {
    define("URL", "../");
}

$cssVars = array(
    "URL" => URL,
    "IMG" => URL . "themes/" . THEME . "/images/"
);

$css = $style->get("style.css"); // Gets theme-aware style.css

foreach($cssVars as $k => $v) {
    $css = preg_replace("/<$k>/si", $v, $css);
}

header("Content-type: text/css");
echo $css;
