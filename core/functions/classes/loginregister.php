<?php
class LoginRegister
{

    public function __construct($form)
    {
        switch ($form) {
            case 'login':
                $this->login();
                break;
            case 'register':
                $this->register();
                break;
        }
    }

    protected function login()
    {
        global $conn;

        $stmt    = $conn->prepare("SELECT upass FROM users WHERE uname = ?;");
        $results = $stmt->bind_param("s", $_POST['uname']);
        if ($results == false) {
            die('Page Error');
        }
        $results = $stmt->execute();
        if ($results == false) {
            die('mysql error');
        }
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($pass);
            if (password_verify($_POST['upass'], $pass)) {
                $_SESSION['uname'] = $_POST['uname'];
            }
        }
        $stmt->close();
    }

    protected function register()
    {
        global $conn;

        $pass  = password_hash($_POST['upass'], PASSWORD_DEFAULT);
        $exist = $this->userexists($_POST['uname']);
        if ($exist >= 1) {
            $taken = $_POST['uname'] . "is already taken";
            $exist->close();
            exit();
        }
        $exist->close();

        $stmt    = $conn->prepare("INSERT INTO users (email, uname, upass) VALUES (?,?,?);");
        $results = $stmt->bind_param("sss", $_POST['email'], $_POST['uname'], $pass);
        if ($results == false) {
            die('input error');
        }
        $results = $stmt->execute();
        if ($results == false) {
            die('Mysql error registration execute');
        }
        $stmt->store_result();
        if ($stmt->affected_rows == 1) {
            $_SESSION['uname'] = $_POST['uname'];
        }
        $stmt->close();
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
        return $data;
    }

}
