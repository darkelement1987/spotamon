<?php
require_once 'config/config.php';
require_once 'vendor/autoload.php';

$conn = new mysqli($servername, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    echo "Connection failed: $conn->connection_error";
}


require_once 'core/functions/protected/version.php';
require_once 'core/functions/functions.php';

if ($enableDebug === true) {
    ini_set("log_errors", -1);
ini_set("error_log", "./debug.log");
} else if ($enableDebug === false && file_exists('./error.log') ){
    delete('./error.log');
 }
// returns the url of the current page (does not account for rewrites or includes)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'];
$domain = $protocol . $domainName;
$folder = (dirname($_SERVER['PHP_SELF']));
$trim = $domain . $folder;
$viewurl = rtrim($trim, '\/');
if (!isset($subdir)) {
    $subdir = $folder;
}



// Defines constants for includes and references
$root = __DIR__ . '/';
define("S_ROOT", $root);
$config = S_ROOT . 'config/';
$pages = S_ROOT . 'core/pages/';
$functions = S_ROOT . 'core/functions/';
$classes = $functions . 'classes/';
$wdomain = $domain . $wroot;
define("W_ROOT", $wroot);
define("W_DOMAIN", $wdomain);
$css = W_ROOT . 'core/css/';
$js = W_ROOT . 'core/js/';
$assets = W_ROOT . 'core/assets/';
$wpages = W_ROOT . 'core/pages/';
$wfunctions = W_ROOT . 'core/functions/';
define("S_CONFIG", $config);
define("S_FUNCTIONS", $functions);
define("S_PAGES", $pages);
define("S_CLASSES", $classes);
define("W_ASSETS", $assets);
define("W_PAGES", $wpages);
define("W_CSS", $css);
define("W_JS", $js);
define("W_FUNCTIONS", $wfunctions);
//  Create, check and restrict _SESSION

if (session_status() == PHP_SESSION_NONE) {

    Spotamon\Session::sessionStart('Spotamon', 0, W_DOMAIN, $domainName);
}
use \ParagonIE\AntiCSRF\AntiCSRF;
$csrf2 = new AntiCSRF;
use \ParagonIE\AntiCSRF\Reusable;
$csrf = new Reusable;

use \Spotamon\Validate;
$Validate = new Validate;


