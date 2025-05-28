<?php
namespace App\controller;

use App\service\OrderService;
use App\model\ErrorLog;
use App\exception\ValidationException;
use App\exception\AuthenticationException;
use App\controller\BaseController;
use Exception;


class OrderController extends BaseController {
    private OrderService $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }


    public function placeOrder($token) {
             $data = $this->getJsonInput();
        if(empty($data["deliveryAddress"]) || empty($data["paymentMethodValue"]) || empty($data["orderItems"])) {
            echo json_encode(["error" => "chybi vsechny hodnoty objednávky"]);
            return;
        }

        $postedOrderWithDbId = $this->orderService->createOrder($data, $token);

        $resultDeliveryAddress = $this->orderService->createDeliveryAddress($data, $postedOrderWithDbId);

        $resultOrderItems = $this->orderService->addOrderItemsToDb($postedOrderWithDbId);

        if (!$resultOrderItems || !$resultDeliveryAddress) {
          $this->jsonResponse(["error" => "Neúspěšné přijetí objednávky"]);
          return;
        };
        
        $this->jsonResponse($postedOrderWithDbId->getId());
    }


    public function getOrders($token) {
      $result = $this->orderService->getOrders($token);
          $this->jsonResponse($result);
    }


  public function getOrderDetails($orderId, $token) {
     $output =  $orderDetails = $this->orderService->getOrderDetails($orderId, $token);     
        $this->jsonResponse($output);
  } 
  
  
}

?>