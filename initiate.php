<?php
require_once 'login/auth.php';
require_once 'config/config.php';
$conn->close();
// returns the url of the current page (does not account for rewrites or includes)
$protocol   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'];
$domain     = $protocol . $domainName;
$folder     = (dirname($_SERVER['PHP_SELF']));
$trim       = $domain . $folder;
$viewurl    = rtrim($trim, '\/');

// Returns the webroot in relativity to any subflders ###
function directory()
{
    return substr(str_replace('\\', '/', realpath(dirname(__FILE__))), strlen(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']))) + 1);
}
if (directory() != '') {
    $wroot = '/' . directory() . '/';
} else {
    $wroot = '/';
}

// Defines constants for includes and references
$root = __DIR__;
define("S_ROOT", $root);
$config    = S_ROOT . 'config/';
$pages     = S_ROOT . 'core/pages/';
$functions = S_ROOT . 'core/functions/';
$assets    = $viewurl . 'core/assets/';
$css = W_ROOT . 'core/css/';
$js = W_ROOT . 'core/js/';
define("S_CONFIG", $config);
define("S_ASSETS", $assets);
define("S_FUNCTIONS", $functions);
define("S_PAGES", $pages);
define("W_ROOT", $wroot);
define("W_CSS", $css);
define("W_JS", $js);
?>
