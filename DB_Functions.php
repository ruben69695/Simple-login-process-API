<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 03/09/2017
 * Time: 1:18
 * Thanks to https://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/
 */

class DB_Functions {

    private $conn;

    // Class constructor : Constructor de la clase
    function __construct()
    {
        require_once 'DB_Connect.php';

        // Connecting to database : Conectando a la base de datos
        $db = new DB_Connect();
        $this->conn = $db->connect();
    }

    // Class destructor : Destructor de la clase
    function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    /**
     * Storing new user : Guardando nuevo usuario
     * @param $name
     * @param $email
     * @param $password
     * @return array
     * Returns user details : Retorna los detalles del usuario
     */
    public function storeUser($name, $email, $password)
    {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encryptedPassword = $hash["encrypted"];    // Encrypted Password : Password encriptada
        $salt = $hash["salt"];                      // Salt generated : Salt generado

        $stmt = $this->conn->prepare("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at, updated_at) VALUES(?, ?, ?, ?, ? , NOW(), NOW())");
        $stmt->bind_param("sssss", $uuid, $name, $email, $encryptedPassword, $salt);
        $result = $stmt->execute();
        $stmt->close();

        // Check for succesful store : Comprobamos que se haya guardado correctamente
        if($result)
        {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $user;
        }
        else
        {
            return false;
        }
    }

    /**
     * Get user by email and password : Obtener el usuario por email y contraseña
     * @param $email
     * @param $password
     * @return array
     */
    public function getUserByEmailAndPassword($email, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");

        $stmt->bind_param("s", $email);

        if($stmt->execute())
        {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // Verifying user password : Verificamos la contraseña del usuario
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);

            // Check for password equality : Comprobamos lo coincidencia de la contraseña
            if($encrypted_password == $hash) {
                // User authentication details are correct : Detalles de autenticación del usuario correctos
                return $user;
            }
        }
        else
        {
            return null;
        }
    }

    /**
     * Check user is existed or not
     * @param $email
     * @return boolean
     */
    public function isUserExisted($email)
    {
        $stmt = $this->conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0)
        {
            // User existed : El usuario existe
            $stmt->close();
            return true;
        }
        else
        {
            // User not existed : El usuario no existe
            $stmt->close();
            return false;
        }

    }


    /**
     * Encrypting password : Encriptar contraseña
     * @param $password
     * Returns salt and encrypted password : Retorna el salt y la password encriptada
     * @return array
     */
    public function hashSSHA($password)
    {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);

        return $hash;
    }

    /**
     * Decrypting password : Desencriptar contraseña
     * @param $salt
     * @param $password
     * returns hash string
     * @return array
     */
    public function checkhashSSHA($salt, $password)
    {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }
}