<?php

namespace App\service;

use App\model\Order;
use App\model\OrderItem;
use App\model\DeliveryAddress;
use App\service\ProductService;
use App\repository\OrderRepository;
use App\utils\JwtHelper;
use App\service\UserService;
use App\exception\AuthenticationException;


class orderService {
    private PDO $pdo;
    private ProductService $productService;
    private OrderRepository $orderRepository;
    private JwtHelper $jwtHelper;

    public function __construct(ProductService $productService, OrderRepository $orderRepository, JwtHelper $jwtHelper) {
        $this->productService = $productService;
        $this->orderRepository = $orderRepository;
        $this->jwtHelper =  $jwtHelper;
    }



    public function createOrder($data, $token) {

        $userId = null;
        $guestName = null;
        $guestSurname = null;
        $guestEmail = null;

        $payment_method = $data["paymentMethodValue"];

        if(!isset($data["guestInfo"])) {
        $token = $this->jwtHelper->validateToken($token);
        $userId = $token->userId;
        }
        else {
            $guestName = $data["guestInfo"]["name"];
            $guestSurname = $data["guestInfo"]["surname"];
            $guestEmail = $data["guestInfo"]["email"];
        }
        
        $order = new Order($payment_method, $userId, $guestName, $guestSurname, $guestEmail);


        foreach($data["orderItems"] as $orderItem) {
            $product = $this->productService->getProductById($orderItem["product_id"]);
            $orderItem = new OrderItem($product, $orderItem["quantity"], $orderItem["productSize"], $orderItem["productColor"]);
            $order->addOrderItem($orderItem);
        }

        $orderFromDbWithId = $this->orderRepository->addOrder($order);
        return $orderFromDbWithId;
}

public function createDeliveryAddress($data, $orderFromDbWithId) {
    
    $street = $data["deliveryAddress"]["street"];
    $street_number =  $data["deliveryAddress"]["street_number"];
    $city =  $data["deliveryAddress"]["city"];
    $zip_code =  $data["deliveryAddress"]["zip_code"];
    
    $address = new DeliveryAddress($street, $street_number, $city, $zip_code, $orderFromDbWithId);

    $addressFromDbWithId = $this->orderRepository->addDeliveryAddress($address);
    return $addressFromDbWithId;
}

public function addOrderItemsToDb($order) {
    $result = $this->orderRepository->addOrderItems($order);
    return $result;
}


public function getOrders($token) {
   $tokenData =  $this->jwtHelper->validateToken($token);

   if($tokenData->role == 1) {
    return $this->orderRepository->getAllOrders();
   }
   return $this->orderRepository->getOrdersByUserId($tokenData->userId);
}




public function getOrdersByUserId($userId) {
   return  $this->orderRepository->getOrdersByUserId($userId);
}



public function getOrderDetails($orderId, $token) {
    $userData = $this->jwtHelper->validateToken($token);

    if($userData->role !=1 ) {
    $permission = $this->orderRepository->checkPermissionsToViewOrderDetails($userData->userId, $orderId);
    if(!$permission) {
        throw new AuthenticationException("Nemáte oprávnění na zobrazení detailu této objednávky.");
    }
    }
    
    $orderDetails = $this->orderRepository->getOrderDetails($orderId);
    $orderItems = $this->orderRepository->getOrderItemsByOrderId($orderId);

    return ["orderDetails" => $orderDetails, "orderItems" => $orderItems];
}







}
?>