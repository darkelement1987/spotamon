<?php

namespace Spotamon;

class Authentication
{
    public $debug;
    public $result;
    public function __construct()
    {
        global $Validate;
        $form = $Validate->getSession('form');
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
        if (!$csrf->validateRequest() ) {
            $this->result = 'Validation Error';
            exit();
        }
        $userName = $Validate->getPost('username', 'username');
        $userEmail = $Validate->getPost('username', 'email');
        $userPassword = $Validate->getPost('password', 'password');
        $options = ['uname', 'logged_in', 'login_time', 'state'];

        $stmt = $conn->prepare("SELECT uname, upass FROM users WHERE uname = ? or email = ?;");
        $results = $stmt->bind_param("ss", $userName, $userEmail);

        if (!$results) {
            $this->result = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return false;
            exit();
        }
        $results = $stmt->execute();
        if (!$results) {
            $this->result = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
            exit();
        }
        $pass = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if (empty($pass)) {
            $this->result = "Username does not exist";
            return false;
            exit();
        }
        $user = $pass[0]['uname'];
        $pass = $pass[0]['upass'];


        if (password_verify($userPassword, $pass)) {
            unset($values);
            $logged_in = true;
            $time = time();
            $values = [$user, $logged_in, $time, session_id()];
            $Validate->setSession($options, $values);
            $stmt->close();
            return true;
            exit();

        } else if ( md5($userPassword) === $pass ) {
            header("Location: " . W_PAGES . "temppass.php");
            exit();
        }
        $this->result = 'Password is incorrect';
        return false;
    }

    public static function logout()
    {
        global $Validate;

        $States = ['uname', 'token', 'CSRF', 'logged_in', 'time', 'oauth2tate', 'state'];
        $Validate->setSession($States);
        session_destroy();
        return true;
    }

    protected function register()
    {
        global $conn;
        global $Validate;

        $userName = $Validate->getPost('username', 'username');
        $userEmail = $Validate->getPost('email', 'email');
        $userPassword = $Validate->getPost('password', 'password');
        $userConfirmpass = $Validate->getPost('confirmpassword', 'password', true, $userPassword);
        $options = ['uname', 'logged_in', 'login_time'];

        if ($userConfirmpass === null) {
            $this->result = 'passwords do not match';
            return false;
        }

        $pass = password_hash($userPassword, PASSWORD_DEFAULT);
        $nameexist = $this->userexists($userName);
        $emailexist = $this->emailexists($userEmail);
        if ($nameexist === true) {
            $this->result = $userName . "is already taken";
            return false;
            exit();
        }
        if ($emailexist === true) {
            $this->result = $userEmail . "already is registered";
            return false;
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO users (email, uname, upass) VALUES (?,?,?);");
        $results = $stmt->bind_param("sss", $userEmail, $userName, $pass);

        if ($results == false) {

            $this->result = 'input error';
            return false;
            exit();

        }
        $results = $stmt->execute();
        if ($results == false) {
            $this->result = 'Mysql error registration execute';
            return false;
        }

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
            exit();
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

        $user = $Oauth2->user;
        $logintime = time();
        $emailexists = $this->emailexists($user->email);
        if ($emailexists) {
            \Spotamon\Session::regenerateSession();
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
        \Spotamon\Session::regenerateSession();
        $keys = ['uname', 'logged_in', 'login_time', 'form'];
        $values = [$user->basename, true, time(), null];
        $Validate->setSession($keys, $values);
        $this->insertToken($user->email);
        return 'discord-register';

    }
    private function insertToken($email)
    {
        global $Validate;
        global $conn;
        $jsontoken = $_SESSION['token'];
        $jsontoken = $jsontoken->getRefreshtoken();
        $dbtoken = $conn->prepare("UPDATE user_extended SET token = ? WHERE email = ?;");
        $dbtoken->bind_param('ss', $jsontoken, $email);
        $dbtoken->execute();
        $dbtoken->close();
        $Validate->setSession('token');
    }
}
