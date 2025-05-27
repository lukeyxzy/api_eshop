<?php


require_once "vendor/autoload.php";

use App\controller\OrderController;
use App\service\OrderService;
use App\repository\OrderRepository;
use App\service\ProductService;
use App\utils\JwtHelper;
use App\model\Database;
use App\repository\productRepository;

header("Content-Type: application/json");


$token = $_SERVER["HTTP_AUTHORIZATION"] ?? "";
$uri = $_SERVER["REQUEST_URI"];
$baseUrl = $_SERVER['SCRIPT_NAME'];

$rowPath = parse_url($uri, PHP_URL_PATH);
$path = str_replace($baseUrl, "", $rowPath);

$db = new Database();
$jwtHelper = new JwtHelper();
$productRepository = new productRepository($db->getConn());
$productService = new ProductService($productRepository, $jwtHelper);
$orderRepository = new OrderRepository($db->getConn());
$orderService = new OrderService($productService, $orderRepository, $jwtHelper);
$orderController = new OrderController($orderService);

function handleRoute($method, $callback) {

    if($_SERVER["REQUEST_METHOD"] != $method) {
        http_response_code(400);
        echo json_encode(["error"=> "Nepovolená metoda."]);
        return;
    }
$callback();
}

switch($path) {
   
        case "/getOrders":
        handleRoute("GET", fn() => $orderController->getOrders($token));
        break;
        case "/placeOrder":
        handleRoute("POST", fn() => $orderController->placeOrder($token));
        break;
        case "/getOrderDetails":
          $id = $_GET["id"] ?? null;
          handleRoute("GET", fn() => $orderController->getOrderDetails($id, $token));
          break;
        default:
        echo json_encode(["error" => "nepovolena cesta"]);
        http_response_code(400);
        break;
}