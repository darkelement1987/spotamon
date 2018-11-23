<?php
require_once 'initiate.php';

$mail = new Spotamon\Mail;
$data = '';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $action = $Validate->getGet('action');
    $since = $Validate->getGet('since');
    if ($action) {
        switch($action){
            case 'inbox':
                $data = $mail->getInbox($uname, $since);
            break;

            case 'conversation':
                $uname2 = $Validate->getGet('uname2');
                $data = $mail->getConvo($uname, $uname2, $since);
            break;

            case 'outbox':
                $data = $mail->getOutbox($uname, $since);
            break;

            case 'trash':
                $data = $mail->getTrash($uname);
            break;
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post = $Validate->post;
    $action= $Validate->getPost('action', null);
    if (verifyCsrf() === true) {
        if ($action) {
            switch ($action) {
                case 'send':
                    $data = $mail->sendMessage($uname, $post->to_user, $post->subject, $post->message);
                break;
                case 'delete':
                    $data = $mail->deleteMessage($post->id, $post->box);
                break;
                case 'read':
                    $data = $mail->read($post->id);
                break;
            }
        }
    } else {
        echo 'CSRF Token does not match.  If this continues please contact the website administrator';
    }
}

echo json_encode($data, JSON_UNESCAPED_SLASHES);
