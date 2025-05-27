<?php

require_once "vendor/autoload.php";

use App\controller\userController;
use App\service\UserService;
use App\repository\UserRepository;
use App\utils\JwtHelper;
use App\model\Database;

header("Content-Type: application/json");

$token = $_SERVER["HTTP_AUTHORIZATION"] ?? "";
$uri = $_SERVER["REQUEST_URI"];
$baseUrl = $_SERVER['SCRIPT_NAME'];

$rowPath = parse_url($uri, PHP_URL_PATH);
$path = str_replace($baseUrl, "", $rowPath);

$db = new Database();
$jwtHelper = new JwtHelper();
$userRepository = new UserRepository($db->getConn());
$userService = new UserService($userRepository, $jwtHelper);
$userController = new userController($userService);


function handleRoute($method, $callback) {

    if($_SERVER["REQUEST_METHOD"] != $method) {
        http_response_code(400);
        echo json_encode(["error"=> "Nepovolená metoda."]);
        return;
    }
$callback();
}


switch($path) {
    case "/register":
        handleRoute("POST", fn() => $userController->createUser());

        break;
    case "/sign_in":
              handleRoute("POST", fn() =>  $userController->authentization());
        break;
    case "/check_token":
          handleRoute("GET", fn() =>  $userController->check_token($token));
        break;
    
    case "/getLoggedInUserData":
        handleRoute("GET", fn() =>  $userController->getLoggedInUserData($token));
    break;
    case "/editUserData":
       handleRoute("POST", fn() =>    $userController->editUserData($token));
        break;
        case "/changePassword":
            handleRoute("POST", fn() =>    $userController->changePassword($token));
            break;
    case "/log_out":
           handleRoute("GET", fn() =>  $userController->logOut());
        break;
        default:
        http_response_code(400);
        echo json_encode(["error" => "nepovolena cesta"]);
        break;
}