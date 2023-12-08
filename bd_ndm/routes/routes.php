<?php

function counter($array) {
    
    return count($array);
}

$rutasArray = explode("/", $_SERVER['REQUEST_URI']);
$inputs = array();
$inputs['raw_inputs'] = @file_get_contents('php://input');
$_POST = json_decode($inputs['raw_inputs'], true);

if (counter(array_filter($rutasArray)) < 2) {
    $json = array(
        "ruta:" => "not found"
    );
    echo json_encode($json, true);
    return;
} else {
    $endPoint = (array_filter($rutasArray)[2]);
    $complement = (array_key_exists(3, $rutasArray)) ? ($rutasArray)[3] : 0;
    $add = (array_key_exists(4, $rutasArray)) ? $rutasArray[4] : "";
    
    if ($add != "") {
        $complement .= "/" . $add;
    }
    
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($endPoint) {
        case 'users':
            if (isset($_POST)) {
                $user = new userController($method, $complement, $_POST);
            } else {
                $user = new userController($method, $complement, 0);
            }
            $user->index();
            break;
        case 'login':
            if (isset($_POST) && $method == 'POST') {
                $user = new LoginController($method, $_POST);
                $user->index();
            } else {
                $json = array(
                    "ruta:" => "not found"
                );
                echo json_encode($json, true);
                return;
            }
            break;
        default:
            $json = array(
                "ruta:" => "not found"
            );
            echo json_encode($json, true);
            return;
    }
}
?>
