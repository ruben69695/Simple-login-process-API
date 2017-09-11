<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 03/09/2017
 * Time: 2:22
 * Thanks to https://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/
 */

require_once 'DB_Functions.php';
$db = new DB_Functions();

// Json response Array : Array Json de respuesta
$response = array("error" => false);

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']))
{
    // Reciving the post params : Recibimos los parametros por post
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user already exists : Comprobamos si el usuario ya existe
    if($db->isUserExisted($email))
    {
        // User already exists : usuario ya existe
        $response["error"] = true;
        $response["error_msg"] = "UserAlreadyExist";
        echo json_encode($response);
    }
    else {
        // Create a new user : Creamos un nuevo usuario
        $user = $db->storeUser($name, $email, $password);
        if($user)
        {
            // User stored succesfully : Usuario almacenado correctamente
            $response["error"] = false;
            $response["uid"] = $user['unique_id'];
            $response["user"]["name"] = $user["name"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["created_at"] = $user["created_at"];
            $response["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($response);
        }
        else {
            $response["error"] = true;
            $response["error_msg"] = "UnknownErrorRegistration";
            echo json_encode($response);
        }
    }
}
else
{
    $response["error"] = false;
    $response["error_msg"] = "ErrorPostParamsMissing";
    echo json_encode($response);
}
