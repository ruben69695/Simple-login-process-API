<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 03/09/2017
 * Time: 1:11
 * Thanks to https://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/
 */

class DB_Connect
{
    private $conn;

    // Connecting to database : Conectando a la base de datos
    public function connect() {
        require_once 'Config.php';

        // Connecting to MySQL database : Conectando a la base de datos MySQL
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE, DB_PORT);

        // Return database handler : Retornamos el manipulador (handler) de la base de datos
        return $this->conn;
    }
}