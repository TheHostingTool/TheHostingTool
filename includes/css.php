<?php
//////////////////////////////
// TheHostingTool
// CSS output
// By Kevin M
// Released under the GNU-GPL
//////////////////////////////

define("LINK", "./");
require(LINK . "compiler.php");

$cssVars = array(
    "URL" => URL,
    "IMG" => URL . "themes/" . THEME . "/images/"
);

$css = $style->get("style.css"); // Gets theme-aware style.css

foreach($cssVars as $k => $v) {
    $css = preg_replace("/<$k>/si", $v, $css);
}

header_remove("Pragma"); // Remove Pragma: no-cache
header("Cache-control: must-revalidate");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + 86400) . " GMT"); // 24h browser cache
header("Content-type: text/css");
echo $css;
