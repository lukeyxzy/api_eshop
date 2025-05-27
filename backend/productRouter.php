<?php

require_once "vendor/autoload.php";

use App\controller\ProductController;
use App\service\ProductService;
use App\repository\ProductRepository;
use App\utils\JwtHelper;
use App\model\Database;

$token = $_SERVER["HTTP_AUTHORIZATION"] ?? "";
$uri = $_SERVER["REQUEST_URI"];
$baseUrl = $_SERVER['SCRIPT_NAME'];

$rowPath = parse_url($uri, PHP_URL_PATH);
$path = str_replace($baseUrl, "", $rowPath);


$jwtHelper = new JwtHelper();
$db = new Database();
$productRepository = new ProductRepository($db->getConn());
$productService = new ProductService($productRepository, $jwtHelper);
$productController = new ProductController($productService);

function handleRoute($method, $callback) {

    if($_SERVER["REQUEST_METHOD"] != $method) {
        http_response_code(400);
        echo json_encode(["error"=> "Nepovolená metoda."]);
        return;
    }
$callback();
}


switch($path) {
    case "/getAllProducts":
        handleRoute("GET", fn() => $productController->getAllProducts());
        break;
    case "/getProductById":
        $id = $_GET["id"] ?? null;
        handleRoute("GET", fn() => $productController->getProductById($id));
        break;
        case "/addProduct":
             handleRoute("POST", fn() => $productController->addProduct($token));
            break;
            case "/getProductsByCategoryId":
                $id = $_GET["id"] ?? null;
                handleRoute("GET", fn() => $productController->getProductsByCategoryId($_GET["id"]));
                break;
        default:
        http_response_code(400);
        echo json_encode(["error" => "nepovolena cesta"]);
                break;
}