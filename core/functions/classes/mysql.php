<?php

Namespace Spotamon;

Class Mysql {

    public $error;

    public function __construct() {
        $servername = "localhost";
        $username = "John";
        $password = "Starwars1";
        $database = "Spotmon";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);
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
