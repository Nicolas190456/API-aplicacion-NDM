<?php
require_once "controller/routesController.php";
require_once "controller/userController.php";
require_once "controller/LoginController.php";
require_once "model/UserModel.php";

header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('Access-Control-Allow-Headers: Authorization');

$rutasArray = explode("/", $_SERVER['REQUEST_URI']);

$endPoint = isset($rutasArray[2]) ? $rutasArray[2] : null;

$identifier = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
$key = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;

var_dump($identifier);
var_dump($key);

if ($endPoint != 'login') {
    if ($identifier !== null && $key !== null) {
        $ok = false;
        $users = UserModel::getUserAuth();

        foreach ($users as $u) {
            if ($identifier . ":" . $key == $u["us_identifier"] . ":" . $u["us_key"]) {
                $ok = true;
            }
        }

        if ($ok) {
            $routes = new routesController();
            $routes->index();
        } else {
            $result["mensaje"] = "NO TIENES ACCESO";
            echo json_encode($result, true);
            return;
        }
    } else {
        $result["mensaje"] = "ERROR EN CREDENCIAL";
        echo json_encode($result, true);
        return;
    }
} else {
    $routes = new routesController();
    $routes->index();
}
?>
