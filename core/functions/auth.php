<?php
require 'initiate.php';
use \Spotamon\Authentication;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $Validate->getPost('formtype');
    if ($form !== Null) {
        $Validate-setSession('form', $form);
        unset($form);
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $form = $Validate->getGet('formtype');
    if ($form !== Null) {
        $Validate->setSession('form', $form);
        unset($form);
    }
}
$form = $Validate->getSession('form');

if ($form == 'discordlogin' || $form == 'discordregister') {
    $Oauth2 = new \Spotamon\Oauth2;
}

$authenticated = new \Spotamon\Authentication;

if (!empty($authenticated->result)) {
    echo $authenticated->result;
} else {
echo $authenticated;
}


header('Location: '.W_ROOT.'index.php');
