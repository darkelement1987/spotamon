<?php

namespace Spotamon;

class Authentication
{
    public $debug;
    public $result;
    public $error;
    public function __construct()
    {
        global $Validate;
        global $sess;
        $this->error = array();
        $form = $sess->get('form');
        switch ($form) {
            case 'login':
                $result = $this->login();
                break;
            case 'register':
                $result = $this->register();
                break;
            case 'discordlogin':
                $result = $this->discordlogin();
                break;
            case 'discordregister':
                $result = $this->discordregister();
                break;
            case 'logout':
                $result = $this->logout();
                break;
            default:
                $result = false;
                break;
        }
        $Validate->setSession('form');
        $this->result = $result;
        }


    protected function login()
    {
        global $conn;
        global $Validate;
        global $csrf;
        global $session;
        if (!verifyCsrf()) {
            $this->error[] = 'Validation Error';
            return false;
        }
        $userName = $Validate->getPost('username', 'username');
        $userEmail = $Validate->getPost('username', 'email');
        $userPassword = $Validate->getPost('password', 'password');
        $options = ['uname', 'logged_in', 'login_time'];

        $stmt = $conn->prepare("SELECT uname, upass FROM users WHERE uname LIKE ? or email = ?;");
        $results = $stmt->bind_param("ss", $userName, $userEmail);

        if (!$results) {
            $this->error[] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
        }
        $results = $stmt->execute();
        if (!$results) {
            $this->error[] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }
        $pass = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if (empty($pass)) {
            $this->error[] = "Username does not exist";
            return false;
        }
        $user = $pass[0]['uname'];
        $pass = $pass[0]['upass'];


        if (password_verify($userPassword, $pass)) {
            unset($values);
            $logged_in = true;
            $time = time();
            $values = [$user, $logged_in, $time];
            $session->regenerateId();
            $Validate->setSession($options, $values);
            $stmt->close();
            return true;

        } else if ( md5($userPassword) === $pass ) {
            header("Location: " . W_PAGES . "temppass.php");
            exit();
        }
        $this->error[] = 'Password is incorrect';
        return false;
    }

    public static function logout()
    {
        global $Validate;
        global $session;
        $States = ['uname', 'token', 'CSRF', 'logged_in', 'time', 'oauth2tate', 'state'];
        $Validate->setSession($States);
        $session->clear();
        return true;
    }

    protected function register()
    {
        global $conn;
        global $Validate;
        global $session;

        if (!verifyCsrf()) {
            return false;
        }
        $userName = $Validate->getPost('username', 'username');
        $userEmail = $Validate->getPost('email', 'email');
        $userPassword = $Validate->getPost('password', 'password');
        $userConfirmpass = $Validate->getPost('confirmpassword', 'password', true, $userPassword);
        $options = ['uname', 'logged_in', 'login_time'];

        if ($userConfirmpass === null) {
            $this->error[] = 'passwords do not match';
            return false;
        }

        $pass = password_hash($userPassword, PASSWORD_DEFAULT);
        $nameexist = $this->userexists($userName);
        $emailexist = $this->emailexists($userEmail);
        if ($nameexist === true) {
            $this->error[] = $userName . "is already taken";
            return false;
        }
        if ($emailexist === true) {
            $this->error[] = $userEmail . "already is registered";
            return false;
        }

        $stmt = $conn->prepare("INSERT INTO users (email, uname, upass) VALUES (?,?,?);");
        $results = $stmt->bind_param("sss", $userEmail, $userName, $pass);

        if ($results == false) {

            $this->error[] = 'input error';
            return false;

        }
        $results = $stmt->execute();
        if ($results == false) {
            $this->error[] = 'Mysql error registration execute';
            return false;
        }
        $session->regenerateId();
        $values = [$userName, true, time()];
        $Validate->setSession($options, $values);

        $stmt->close();
        return true;
    }

    public static function userexists($username)
    {
        global $conn;

        $exist = $conn->prepare("SELECT count(*) FROM users WHERE uname LIKE ?;");
        $exist->bind_param("s", $username);
        $exist->execute();
        $exist->store_result();
        $exist->bind_result($data);
        $exist->close();
        if ($data >= 1) {
            return true;
        }
        return false;
    }

    public static function emailexists($useremail)
    {
        global $conn;
        $email = $useremail;
        $exist = $conn->prepare("SELECT count(*) FROM users WHERE email like ? ;");
        $exist->bind_param("s", $email);
        $exist->execute();
        $data = $exist->get_result();
        $data = $data->fetch_all();
        $data = $data[0][0];
        $exist->close();
        if ($data == '1') {
            return true;
        } else {
        return false;
        }
    }

    private function discordlogin()
    {

        global $conn;
        global $Validate;
        global $Oauth2;
        global $session;

        $user = $Oauth2->user;
        $logintime = time();
        $emailexists = $this->emailexists($user->email);
        if ($emailexists) {
            $session->regenerateId();
            $username = $conn->prepare("SELECT uname FROM users WHERE email = ?;");
            $username->bind_param("s", $user->email);
            $username->execute();
            $data = $username->get_result();
            $uname = $data->fetch_all();
            $uname = $uname[0][0];
            $username->close();
            $avatarupdate = $conn->prepare("UPDATE users SET url = ? WHERE email = ?;");
            $avatarupdate->bind_param("ss", $user->avatar, $user->email);
            $avatarupdate->execute();
            $options = ['uname', 'logged_in', 'login_time', 'form'];
            $values = [$uname, true, $logintime, null];
            $Validate->setSession($options, $values);
            $this->insertToken($user->email);
            return 'discord-login';
        } else {
            return 'You must first register to login with discord';

        }
    }

    private function discordregister()
    {
        global $Oauth2;
        global $Validate;
        global $conn;
        global $session;
        $user = $Oauth2->user;

        $usergroup = 1;
        if (!empty($elevatedUsers)) {
            foreach ($elevatedUsers as $key) {
                if ($user->id == $key) {
                    $usergroup = 3;
                }
            }
        }
        $oo = $ooo = 0;
        $temppass = password_hash('1', PASSWORD_DEFAULT);
        $userinsert = $conn->prepare("INSERT INTO users (email, uname, upass, usergroup, offtrades, reqtrades, url)
                                        Values (?,?,?,?,?,?,?) on duplicate key update url=?;");
        $userinsert->bind_param("sssiiiss", $user->email, $user->basename, $temppass, $usergroup, $oo, $ooo, $user->avatar, $user->avatar);
        $userinsert->execute();
        $discordinsert = $conn->prepare("INSERT IGNORE INTO user_extended (email, discord_id, discord_uname, avatar) values (?,?,?,?);");
        $discordinsert->bind_param("ssss", $user->email, $user->id, $user->username, $user->avatar);
        $discordinsert->execute();
        $discordinsert->close();
        $session->regenerateId();
        $keys = ['uname', 'logged_in', 'login_time', 'form'];
        $values = [$user->basename, true, time(), null];
        $Validate->setSession($keys, $values);
        $this->insertToken($user->email);
        return 'discord-register';

    }
    private function insertToken($email)
    {
        global $sess;
        global $Validate;
        global $conn;
        $jsontoken = $sesh->get('token');
        $jsontoken = $jsontoken->getRefreshtoken();
        $dbtoken = $conn->prepare("UPDATE user_extended SET token = ? WHERE email = ?;");
        $dbtoken->bind_param('ss', $jsontoken, $email);
        $dbtoken->execute();
        $dbtoken->close();
        $Validate->setSession('token');
    }
}
