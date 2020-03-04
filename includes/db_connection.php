<?php
    require('config.php');

    function get_connection() {
        try {
            $connection = new PDO(DB_NAME, DB_USER, DB_PASS);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;

        }

        catch(PDOException $e) {
            die('PDO Connection Error.');
        }
    }

