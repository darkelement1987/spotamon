<?php
require 'initiate.php';
use \Spotamon\Authentication;

parse_str($_SERVER['QUERY_STRING'], $form);
if (isset($form['form']) && !empty($form['form'])) {
    $Validate->setSession('form', $form['form']);
} else if ($Validate->getPost('formtype') !== null) {
    if ($csrf->validateRequest()) {
        $Validate->setSession('form', $Validate->getPost('formtype'));
    } else {
        echo 'Validation error, please try again.';
        exit();
    }
}
$form = $Validate->getSession('form');
if ($form == 'discordlogin' || $form == 'discordregister' ) {
    $Oauth2 = new \Spotamon\Oauth2;
}else if ($form == 'login' || $form == 'register' || (isset($Oauth2->user) && !empty($Oauth2->user))) {
    $Authenticate = new \Spotamon\Authentication();
}
header("location: " . W_ROOT . "index.php");
