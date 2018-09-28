<?php
Namespace Spotamon;

Class Db {

    public $error;

    public function __construct() {
        global $servername;
        global $username;
        global $password;
        global $database;
        // Create connection
        $conn = new \mysqli($servername, $username, $password, $database);
        // Check connection
        if ($conn->connect_error) {
            $this->error = "Connection failed: $conn->connection_error";
        }
        return $conn;
    }


    public function prepare() {

    }

    public function insert() {

    }

    public function update() {

    }

    public function query() {

    }

    public function assoc() {

    }

    public function close() {

    }
}
