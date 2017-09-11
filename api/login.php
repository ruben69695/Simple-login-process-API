<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 03/09/2017
 * Time: 1:59
 * Thanks to https://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/
 */

require_once 'DB_Functions.php';
$db = new DB_Functions();

// Json response Array : Array Json de respuesta
$response = array("error" => false);

if(isset($_POST['email']) && isset($_POST['password']))
{
    // Reciving the post params : Recibimos los parametros por post
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Get the user by email and password : Obtenemos el usuario por email y contraseña
    $user = $db->getUserByEmailAndPassword($email, $password);

    if($user != false)
    {
        // User found : Se ha encontrado al usuario en la BD
        $response["error"] = false;
        $response["uid"] = $user['unique_id'];
        $response["user"]["name"] = $user["name"];
        $response["user"]["email"] = $user["email"];
        $response["user"]["created_at"] = $user["created_at"];
        $response["user"]["updated_at"] = $user["updated_at"];
        echo json_encode($response);
    }
    else
    {
        // User not found : Usuario no encontrado
        $response["error"] = true;
        $response["error_msg"] = "Wrong_Credentials";
        echo json_encode($response);
    }
}
else
{
    // Required post params are missing : Parametros post requeridos no se encuentran
    $response["error"] = true;
    $response["error_msg"] = "ErrorPostParamsMissing";
    echo json_encode($response);
}