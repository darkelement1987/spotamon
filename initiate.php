<?php
require_once 'config/config.php';
require_once 'vendor/autoload.php';
require_once 'core/functions/functions.php';
ini_set('display_errors', '1');
use \Aura\Session\SessionFactory;
if (!isset($_SESSION) || !isset($session)) {
    $session_factory = new \Aura\Session\SessionFactory;
    $session = $session_factory->newInstance($_COOKIE);
    $session->start();
    $sess = $session->getSegment('Spotamon');
}

if ($enableDebug === true) {
    ini_set("log_errors", -1);
ini_set("error_log", "/debug.log");
} else if ($enableDebug === false && file_exists('/error.log') ){
    delete('/error.log');
}

use \Spotamon\Validate;
$Validate = new Validate;
// returns the url of the current page (does not account for rewrites or includes)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
if (!empty($_SERVER['HTTP_HOST'])) {
$domainName = $Validate->clean($_SERVER['HTTP_HOST']);
} else {
$domainName = $_SERVER['SERVER_NAME'];
}
$domain = $protocol . $domainName;
$folder = (dirname($_SERVER['PHP_SELF']));
$trim = $domain . $folder;
$viewurl = rtrim($trim, '\/');
if (!isset($subdir)) {
    $subdir = $folder;
}




$wroot = directory();// Defines constants for includes and references
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



$conn = new mysqli($servername, $username, $password, $database);
$conn->set_charset('utf8mb4');
// Check connection
if ($conn->connect_error) {
    echo "Connection failed: $conn->connection_error";
    }

