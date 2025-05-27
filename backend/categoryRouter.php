<?php


require_once "vendor/autoload.php";

use App\controller\CategoryController;
use App\service\CategoryService;
use App\repository\CategoryRepository;
use App\model\Database;
use App\utils\JwtHelper;
use App\exception\UnauthorizedException;
use App\exception\ValidationException;


header("Content-Type: application/json");


$token = $_SERVER["HTTP_AUTHORIZATION"] ?? "";
$method = $_SERVER["REQUEST_METHOD"];
$uri = $_SERVER["REQUEST_URI"];
$baseUrl = $_SERVER['SCRIPT_NAME'];

$rowPath = parse_url($uri, PHP_URL_PATH);
$path = str_replace($baseUrl, "", $rowPath);

$db = new Database();
$jwtHelper = new JwtHelper();
$categoryRepository = new CategoryRepository($db->getConn());
$categoryService = new CategoryService($categoryRepository, $jwtHelper);
$categoryController = new CategoryController($categoryService);

function handleRoute($method, $callback) {

    if($_SERVER["REQUEST_METHOD"] != $method) {
        http_response_code(400);
        echo json_encode(["error"=> "Nepovolená metoda."]);
        return;
    }
try {
    $callback();
}
        catch(UnauthorizedException | ValidationException $e) {
        http_response_code(400);
        echo json_encode(["error" => $e->getMessage()]);
       }
       catch(Exception $e) {
        ErrorLog::logError($e);
        http_response_code(500);
        echo json_encode(["error" => "Nastala chyba na serveru, opakujte akci později."]);
       }
}

switch($path) {
    case "/getCategoryById":
        $id = $_GET["id"] ?? null;
         handleRoute("GET", fn() => $categoryController->getCategoryById($id));
        break;
        case "/getAllCategories":
      handleRoute("GET", fn() => $categoryController->getAllCategories());
        break;
        case "/addCategory":
            handleRoute("POST", fn() => $categoryController->addCategory($token));
            break;
        default:
        http_response_code(400);
        echo json_encode(["error" => "nepovolena cesta"]);
        break;
}