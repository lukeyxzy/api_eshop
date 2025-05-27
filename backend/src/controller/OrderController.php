<?php
namespace App\controller;

use App\service\OrderService;
use App\model\ErrorLog;
use App\exception\ValidationException;
use App\exception\AuthenticationException;
use Exception;


class OrderController {
    private OrderService $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }


    public function placeOrder($token) {
      $data = json_decode(file_get_contents("php://input"), true);

        if(empty($data["deliveryAddress"]) || empty($data["paymentMethodValue"]) || empty($data["orderItems"])) {
            echo json_encode(["error" => "chybi vsechny hodnoty objednávky"]);
            return;
        }

        $postedOrderWithDbId = $this->orderService->createOrder($data, $token);

        $resultDeliveryAddress = $this->orderService->createDeliveryAddress($data, $postedOrderWithDbId);

        $resultOrderItems = $this->orderService->addOrderItemsToDb($postedOrderWithDbId);

        if (!$resultOrderItems || !$resultDeliveryAddress) {
          echo json_encode(["error" => "Neúspěšné přijetí objednávky"]);
          return;
        };
        echo json_encode($postedOrderWithDbId->getId());
    }


    public function getOrders($token) {
      $result = $this->orderService->getOrders($token);
      echo json_encode($result);

    }


  public function getOrderDetails($orderId, $token) {
     $output =  $orderDetails = $this->orderService->getOrderDetails($orderId, $token);     
     echo json_encode($output);
  } 
  
  
}

?>