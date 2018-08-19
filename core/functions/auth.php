<?php
require 'initiate.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $Validate->getPost('formtype');
    if ($form !== null) {
        $Validate->setSession('form', $form);
        unset($form);
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $form = $Validate->getGet('formtype');
    if ($form !== null) {
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
}

if ($authenticated == true) {
    header('Location: ' . W_ROOT . 'index.php');
} else {
    ?>
<br>
<span><a href="<?=W_ROOT?>index.php">Click Here</a> to return to Spotamon and try again</span>
<?php }
exit();
?>
